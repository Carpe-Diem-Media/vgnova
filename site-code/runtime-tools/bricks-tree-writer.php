<?php
declare(strict_types=1);

final class VgBricksTreeWriterException extends RuntimeException
{
}

/**
 * Plan validation and compare-before/save/readback checks for the VGN-5B Bricks edit.
 * The WordPress and Bricks calls stay in write-bricks-tree.php.
 */
final class VgBricksTreeWriter
{
    public const TARGET = 'vgnova-local-10039';
    public const BRICKS_VERSION = '2.4.5';

    /** @var array<int,string> */
    private const TARGET_POST_TYPES = [
        933 => 'page',
        1733 => 'bricks_template',
    ];

    private const MAX_PLAN_BYTES = 4_194_304;
    private const MAX_TREE_NODES = 1200;
    private const MAX_TREE_DEPTH = 40;

    /** @return array<string,mixed> */
    public static function loadPrivatePlan(string $path, string $expectedSha256): array
    {
        if (preg_match('/\A[a-f0-9]{64}\z/', $expectedSha256) !== 1) {
            throw new VgBricksTreeWriterException('Invalid plan SHA-256 argument.');
        }
        if (! str_starts_with($path, DIRECTORY_SEPARATOR) || str_contains($path, "\0") || is_link($path)) {
            throw new VgBricksTreeWriterException('Plan path must be an absolute, non-symlink file.');
        }

        $canonicalPath = realpath($path);
        $pathStat = @lstat($path);
        if (! is_string($canonicalPath)
            || $canonicalPath !== $path
            || ! is_array($pathStat)
            || ($pathStat['mode'] & 0170000) !== 0100000
            || ! function_exists('posix_geteuid')
            || ($pathStat['uid'] ?? null) !== posix_geteuid()
            || (($pathStat['mode'] & 0077) !== 0)
            || ($pathStat['size'] ?? self::MAX_PLAN_BYTES + 1) > self::MAX_PLAN_BYTES) {
            throw new VgBricksTreeWriterException('Plan file must be a private, owner-controlled regular file no larger than 4 MiB.');
        }

        $handle = @fopen($path, 'rb');
        if (! is_resource($handle)) {
            throw new VgBricksTreeWriterException('Plan file could not be opened.');
        }

        try {
            $handleStat = fstat($handle);
            if (! is_array($handleStat)
                || ($handleStat['dev'] ?? null) !== ($pathStat['dev'] ?? null)
                || ($handleStat['ino'] ?? null) !== ($pathStat['ino'] ?? null)) {
                throw new VgBricksTreeWriterException('Plan file changed while it was opened.');
            }

            $contents = stream_get_contents($handle, self::MAX_PLAN_BYTES + 1);
        } finally {
            fclose($handle);
        }

        if (! is_string($contents) || strlen($contents) > self::MAX_PLAN_BYTES) {
            throw new VgBricksTreeWriterException('Plan file is unreadable or exceeds 4 MiB.');
        }
        if (! hash_equals($expectedSha256, hash('sha256', $contents))) {
            throw new VgBricksTreeWriterException('Plan file SHA-256 does not match the reviewed invocation.');
        }

        try {
            $plan = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new VgBricksTreeWriterException('Plan file is not valid JSON.');
        }

        if (! is_array($plan)) {
            throw new VgBricksTreeWriterException('Plan root must be a JSON object.');
        }

        return $plan;
    }

    public static function treeSha256(array $tree): string
    {
        return hash('sha256', serialize($tree));
    }

    /** @param array<string,mixed> $plan
     *  @return array<int,array<string,mixed>>
     */
    public static function validatePlan(array $plan): array
    {
        self::assertExactKeys($plan, ['schema_version', 'target', 'bricks_version', 'documents'], 'plan');
        if (($plan['schema_version'] ?? null) !== 1
            || ($plan['target'] ?? null) !== self::TARGET
            || ($plan['bricks_version'] ?? null) !== self::BRICKS_VERSION
            || ! is_array($plan['documents'] ?? null)
            || ! array_is_list($plan['documents'])
            || count($plan['documents']) !== count(self::TARGET_POST_TYPES)) {
            throw new VgBricksTreeWriterException('Plan must bind Bricks 2.4.5 and both approved VGN-5B documents.');
        }

        $documents = [];
        foreach ($plan['documents'] as $document) {
            if (! is_array($document)) {
                throw new VgBricksTreeWriterException('Each document plan must be a JSON object.');
            }
            self::assertExactKeys(
                $document,
                ['post_id', 'post_type', 'template_type', 'before_sha256', 'after_sha256', 'before_elements', 'allowed_changed_ids', 'allowed_added_ids', 'elements'],
                'document'
            );

            $postId = $document['post_id'] ?? null;
            if (! is_int($postId) || ! array_key_exists($postId, self::TARGET_POST_TYPES) || isset($documents[$postId])) {
                throw new VgBricksTreeWriterException('Plan contains an unknown or duplicate Bricks target.');
            }
            if (($document['post_type'] ?? null) !== self::TARGET_POST_TYPES[$postId]) {
                throw new VgBricksTreeWriterException('Plan post type does not match the approved Bricks target.');
            }

            if ($postId === 1733) {
                if (! is_string($document['template_type'] ?? null)
                    || $document['template_type'] === ''
                    || in_array($document['template_type'], ['header', 'footer'], true)) {
                    throw new VgBricksTreeWriterException('Course template plan must include its current Bricks template type.');
                }
            } elseif (($document['template_type'] ?? null) !== null) {
                throw new VgBricksTreeWriterException('Vriendinne page plan must not set a template type.');
            }

            foreach (['before_sha256', 'after_sha256'] as $digestKey) {
                if (! is_string($document[$digestKey] ?? null) || preg_match('/\A[a-f0-9]{64}\z/', $document[$digestKey]) !== 1) {
                    throw new VgBricksTreeWriterException('Document plan has an invalid tree SHA-256.');
                }
            }

            $beforeElements = $document['before_elements'] ?? null;
            $elements = $document['elements'] ?? null;
            if (! is_array($beforeElements) || ! is_array($elements)) {
                throw new VgBricksTreeWriterException('Planned Bricks tree must be an array.');
            }
            self::validateTree($beforeElements, 'planned before tree');
            self::validateTree($elements, 'planned tree');
            if (! hash_equals($document['before_sha256'], self::treeSha256($beforeElements))) {
                throw new VgBricksTreeWriterException('Planned before-tree SHA-256 does not match its exact tree.');
            }
            if (! hash_equals($document['after_sha256'], self::treeSha256($elements))) {
                throw new VgBricksTreeWriterException('Planned after-tree SHA-256 does not match its exact tree.');
            }

            $document['allowed_changed_ids'] = self::validateIdList($document['allowed_changed_ids'] ?? null, 'allowed_changed_ids');
            $document['allowed_added_ids'] = self::validateIdList($document['allowed_added_ids'] ?? null, 'allowed_added_ids');
            if (array_intersect($document['allowed_changed_ids'], $document['allowed_added_ids']) !== []) {
                throw new VgBricksTreeWriterException('Changed and added element ID lists must be disjoint.');
            }

            $documents[$postId] = $document;
        }

        ksort($documents, SORT_NUMERIC);
        if (array_keys($documents) !== [933, 1733]) {
            throw new VgBricksTreeWriterException('Plan must contain exactly page 933 and template 1733.');
        }

        return $documents;
    }

    /**
     * @param array<string,mixed> $plan
     * @param callable(int):array<string,mixed> $readDocument
     * @param callable(int,array,array<string,mixed>):array $normalizeElements
     * @param callable(int,array,array<string,mixed>):void $saveDocument
     * @param callable(array<string,mixed>):void|null $onCompleted
     * @return list<array<string,mixed>>
     */
    public static function execute(
        array $plan,
        callable $readDocument,
        callable $normalizeElements,
        callable $saveDocument,
        ?callable $onCompleted = null
    ): array {
        $documents = self::validatePlan($plan);
        $prepared = [];

        // Refuse the whole operation before the first write if either before-image or sanitizer result differs.
        foreach ($documents as $postId => $document) {
            $current = $readDocument($postId);
            self::assertDocumentIdentity($current, $postId, $document);
            self::assertTreeDigest($current['elements'] ?? null, $document['before_sha256'], 'before-image');
            if ($current['elements'] !== $document['before_elements']) {
                throw new VgBricksTreeWriterException("Live Bricks before-image does not exactly match the reviewed tree for target {$postId}.");
            }
            self::assertMinimalChange($document['before_elements'], $document['elements'], $document);

            $normalized = $normalizeElements($postId, $document['elements'], $current);
            if (! is_array($normalized) || $normalized !== $document['elements']) {
                throw new VgBricksTreeWriterException("Bricks sanitizer changed the planned tree for target {$postId}.");
            }

            $prepared[$postId] = ['document' => $document, 'before' => $current];
        }

        $completed = [];
        foreach ($prepared as $postId => $entry) {
            $document = $entry['document'];
            $latest = $readDocument($postId);
            self::assertDocumentIdentity($latest, $postId, $document);
            self::assertTreeDigest($latest['elements'] ?? null, $document['before_sha256'], 'just-in-time before-image');
            if ($latest['elements'] !== $document['before_elements']) {
                throw new VgBricksTreeWriterException("Live Bricks tree changed before save for target {$postId}.");
            }

            $saveDocument($postId, $document['elements'], $document);

            $after = $readDocument($postId);
            self::assertDocumentIdentity($after, $postId, $document);
            if (! is_array($after['elements'] ?? null)
                || $after['elements'] !== $document['elements']
                || ! hash_equals($document['after_sha256'], self::treeSha256($after['elements']))) {
                throw new VgBricksTreeWriterException("Persisted Bricks tree does not exactly match the planned tree for target {$postId}.");
            }

            $record = [
                'post_id' => $postId,
                'before_sha256' => $document['before_sha256'],
                'after_sha256' => $document['after_sha256'],
                'element_count' => self::treeNodeCount($after['elements']),
                'status' => 'verified',
            ];
            $completed[] = $record;
            if ($onCompleted !== null) {
                $onCompleted($record);
            }
        }

        return $completed;
    }

    /** @param array<string,mixed> $document */
    private static function assertDocumentIdentity(array $actual, int $postId, array $document): void
    {
        if (($actual['post_id'] ?? null) !== $postId
            || ($actual['post_type'] ?? null) !== $document['post_type']
            || ($actual['template_type'] ?? null) !== $document['template_type']) {
            throw new VgBricksTreeWriterException("Bricks target identity changed for post {$postId}.");
        }
        if (! is_array($actual['elements'] ?? null)) {
            throw new VgBricksTreeWriterException("Bricks content tree is unavailable for post {$postId}.");
        }
    }

    private static function assertTreeDigest(mixed $tree, string $expected, string $stage): void
    {
        if (! is_array($tree) || ! hash_equals($expected, self::treeSha256($tree))) {
            throw new VgBricksTreeWriterException("Bricks {$stage} changed or mismatched.");
        }
    }

    /** @param array<string,mixed> $document */
    private static function assertMinimalChange(array $before, array $after, array $document): void
    {
        self::validateTree($before, 'current tree');
        $beforeRootIds = array_column($before, 'id');
        $afterRootIds = array_column($after, 'id');
        if ($beforeRootIds !== $afterRootIds) {
            throw new VgBricksTreeWriterException('Planned Bricks tree changes or reorders top-level content.');
        }
        $beforeById = self::elementsById($before);
        $afterById = self::elementsById($after);
        $changed = [];
        $added = [];

        foreach ($beforeById as $id => $element) {
            if (! array_key_exists($id, $afterById)) {
                throw new VgBricksTreeWriterException('Planned Bricks tree deletes existing content.');
            }
            if ($element !== $afterById[$id]) {
                $changed[] = $id;
            }
        }
        foreach ($afterById as $id => $_element) {
            if (! array_key_exists($id, $beforeById)) {
                $added[] = $id;
            }
        }

        $changed = self::sortIds($changed);
        $added = self::sortIds($added);
        $allowedChanged = self::sortIds($document['allowed_changed_ids']);
        $allowedAdded = self::sortIds($document['allowed_added_ids']);
        if ($changed !== $allowedChanged || $added !== $allowedAdded) {
            throw new VgBricksTreeWriterException('Bricks tree changes do not match the exact approved element ID lists.');
        }
    }

    /** @return array<string,array<string,mixed>> */
    private static function elementsById(array $tree): array
    {
        $elements = [];
        $walk = static function (array $nodes) use (&$walk, &$elements): void {
            foreach ($nodes as $node) {
                $elements[$node['id']] = $node;
                $children = $node['children'] ?? [];
                if (is_array($children)) {
                    $walk($children);
                }
            }
        };
        $walk($tree);
        return $elements;
    }

    /** @return list<string> */
    private static function validateIdList(mixed $value, string $name): array
    {
        if (! is_array($value) || ! array_is_list($value)) {
            throw new VgBricksTreeWriterException("{$name} must be a list of Bricks element IDs.");
        }

        $ids = [];
        foreach ($value as $id) {
            if (! is_string($id) || preg_match('/\A[a-zA-Z0-9]{6,32}\z/', $id) !== 1 || isset($ids[$id])) {
                throw new VgBricksTreeWriterException("{$name} contains an invalid or duplicate Bricks element ID.");
            }
            $ids[$id] = true;
        }

        return array_keys($ids);
    }

    private static function validateTree(array $tree, string $description): void
    {
        if (! array_is_list($tree) || $tree === []) {
            throw new VgBricksTreeWriterException("{$description} must be a non-empty Bricks element list.");
        }

        $ids = [];
        $nodeCount = 0;
        $walk = static function (array $nodes, int $depth) use (&$walk, &$ids, &$nodeCount, $description): void {
            if ($depth > self::MAX_TREE_DEPTH) {
                throw new VgBricksTreeWriterException("{$description} exceeds the Bricks nesting limit.");
            }
            foreach ($nodes as $node) {
                $nodeCount++;
                if ($nodeCount > self::MAX_TREE_NODES
                    || ! is_array($node)
                    || ! is_string($node['id'] ?? null)
                    || preg_match('/\A[a-zA-Z0-9]{6,32}\z/', $node['id']) !== 1
                    || isset($ids[$node['id']])
                    || ! is_string($node['name'] ?? null)
                    || $node['name'] === ''
                    || ! is_array($node['settings'] ?? null)) {
                    throw new VgBricksTreeWriterException("{$description} contains an invalid Bricks element or duplicate ID.");
                }
                $ids[$node['id']] = true;
                $children = $node['children'] ?? [];
                if (! is_array($children) || ! array_is_list($children)) {
                    throw new VgBricksTreeWriterException("{$description} contains an invalid Bricks children list.");
                }
                if ($children !== []) {
                    $walk($children, $depth + 1);
                }
            }
        };
        $walk($tree, 1);
    }

    private static function treeNodeCount(array $tree): int
    {
        $count = 0;
        foreach ($tree as $element) {
            $count++;
            if (is_array($element['children'] ?? null)) {
                $count += self::treeNodeCount($element['children']);
            }
        }
        return $count;
    }

    /** @param list<string> $ids
     *  @return list<string>
     */
    private static function sortIds(array $ids): array
    {
        sort($ids, SORT_STRING);
        return $ids;
    }

    /** @param array<string,mixed> $value
     *  @param list<string> $expected
     */
    private static function assertExactKeys(array $value, array $expected, string $description): void
    {
        $actual = array_keys($value);
        sort($actual, SORT_STRING);
        sort($expected, SORT_STRING);
        if ($actual !== $expected) {
            throw new VgBricksTreeWriterException("{$description} has unexpected or missing fields.");
        }
    }
}
