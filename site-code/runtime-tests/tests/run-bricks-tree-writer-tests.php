<?php
declare(strict_types=1);

require_once __DIR__ . '/../../runtime-tools/bricks-tree-writer.php';

function vg_bricks_tree_writer_test(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }
}

function vg_bricks_tree_writer_expect_failure(callable $callback, string $message): void
{
    try {
        $callback();
    } catch (VgBricksTreeWriterException) {
        return;
    }

    throw new RuntimeException($message);
}

function vg_bricks_tree_writer_fixtures(): array
{
    $before = [
        1733 => [
            ['id' => 'course1', 'name' => 'section', 'parent' => 0, 'children' => ['member1', 'controls1'], 'settings' => ['class' => 'course']],
            ['id' => 'member1', 'name' => 'block', 'parent' => 'course1', 'children' => [], 'settings' => ['label' => 'Existing membership']],
            ['id' => 'controls1', 'name' => 'div', 'parent' => 'course1', 'children' => [], 'settings' => ['label' => 'Existing controls']],
        ],
        933 => [
            ['id' => 'friend1', 'name' => 'section', 'parent' => 0, 'children' => ['tiers01', 'buttons1'], 'settings' => ['class' => 'friend']],
            ['id' => 'tiers01', 'name' => 'block', 'parent' => 'friend1', 'children' => [], 'settings' => ['label' => 'Existing tiers']],
            ['id' => 'buttons1', 'name' => 'div', 'parent' => 'friend1', 'children' => [], 'settings' => ['label' => 'Existing buttons']],
        ],
    ];

    $after = $before;
    $after[1733][0]['children'][] = 'start01';
    array_splice($after[1733], 2, 0, [[
        'id' => 'start01',
        'name' => 'form',
        'parent' => 'course1',
        'children' => [],
        'settings' => ['action' => ['vgpc_journey_intent']],
    ]]);
    $after[933][0]['children'][] = 'remind1';
    $after[933][] = [
        'id' => 'remind1',
        'name' => 'block',
        'parent' => 'friend1',
        'children' => [],
        'settings' => ['label' => 'Course reminder'],
    ];

    return [$before, $after];
}

function vg_bricks_tree_writer_plan(array $before, array $after, array $overrides = []): array
{
    $defaults = [
        1733 => ['changed' => ['course1'], 'added' => ['start01']],
        933 => ['changed' => ['friend1'], 'added' => ['remind1']],
    ];
    $documents = [];

    foreach ([1733, 933] as $postId) {
        $scope = $overrides[$postId] ?? $defaults[$postId];
        $documents[] = [
            'post_id' => $postId,
            'post_type' => $postId === 1733 ? 'bricks_template' : 'page',
            'template_type' => $postId === 1733 ? 'content' : null,
            'before_sha256' => VgBricksTreeWriter::treeSha256($before[$postId]),
            'after_sha256' => VgBricksTreeWriter::treeSha256($after[$postId]),
            'before_elements' => $before[$postId],
            'allowed_changed_ids' => $scope['changed'],
            'allowed_added_ids' => $scope['added'],
            'elements' => $after[$postId],
        ];
    }

    return [
        'schema_version' => 1,
        'target' => 'vgnova-local-10039',
        'bricks_version' => '2.4.5',
        'documents' => $documents,
    ];
}

function vg_bricks_tree_writer_state(array $before): array
{
    return [
        1733 => ['post_id' => 1733, 'post_type' => 'bricks_template', 'template_type' => 'content', 'elements' => $before[1733]],
        933 => ['post_id' => 933, 'post_type' => 'page', 'template_type' => null, 'elements' => $before[933]],
    ];
}

function &vg_bricks_tree_writer_document(array &$plan, int $postId): array
{
    foreach ($plan['documents'] as $index => $document) {
        if (($document['post_id'] ?? null) === $postId) {
            return $plan['documents'][$index];
        }
    }

    throw new RuntimeException("Test plan has no document for target {$postId}.");
}

function vg_bricks_tree_writer_refresh_digests(array &$plan): void
{
    foreach ($plan['documents'] as &$document) {
        $document['before_sha256'] = VgBricksTreeWriter::treeSha256($document['before_elements']);
        $document['after_sha256'] = VgBricksTreeWriter::treeSha256($document['elements']);
    }
    unset($document);
}

function vg_bricks_tree_writer_by_id(array $elements): array
{
    $byId = [];
    foreach ($elements as $element) {
        $byId[$element['id']] = $element;
    }

    return $byId;
}

$tests = 0;
$test = static function (string $name, callable $callback) use (&$tests): void {
    $callback();
    $tests++;
    echo "PASS {$name}\n";
};

$test('flat Bricks plans validate only the two approved targets', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $documents = VgBricksTreeWriter::validatePlan($plan);
    vg_bricks_tree_writer_test(array_keys($documents) === [933, 1733], 'Plan targets were not normalized to the allow-listed order.');
    vg_bricks_tree_writer_test(count($documents[933]['elements']) === 4, 'Page 933 fixture is not a flat four-record element list.');
    vg_bricks_tree_writer_test($documents[1733]['elements'][2]['parent'] === 'course1', 'New Course form is not a child of its planned parent.');
    vg_bricks_tree_writer_test($documents[1733]['elements'][0]['children'] === ['member1', 'controls1', 'start01'], 'Course parent child references are not flat element IDs.');

    $plan['documents'][1]['post_id'] = 934;
    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::validatePlan($plan),
        'A non-allow-listed post ID passed plan validation.'
    );

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $plan['documents'][0]['template_type'] = 'header';
    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::validatePlan($plan),
        'A header template was accepted for a Course content-tree save.'
    );
});

$test('private plans require owner-only permissions and the reviewed file digest', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $path = sys_get_temp_dir() . '/vg-bricks-plan-' . bin2hex(random_bytes(6)) . '.json';
    $contents = json_encode($plan, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

    try {
        file_put_contents($path, $contents);
        chmod($path, 0600);
        $loaded = VgBricksTreeWriter::loadPrivatePlan($path, hash('sha256', $contents));
        vg_bricks_tree_writer_test($loaded === $plan, 'Private plan did not load without changing its exact data.');
        vg_bricks_tree_writer_expect_failure(
            static fn () => VgBricksTreeWriter::loadPrivatePlan($path, str_repeat('0', 64)),
            'A mismatched reviewed plan digest was accepted.'
        );
        chmod($path, 0644);
        vg_bricks_tree_writer_expect_failure(
            static fn () => VgBricksTreeWriter::loadPrivatePlan($path, hash('sha256', $contents)),
            'A group/world-readable plan was accepted.'
        );
    } finally {
        if (is_file($path) && ! is_link($path)) {
            chmod($path, 0600);
            unlink($path);
        }
    }
});

$test('explicit native child additions preserve all existing records and verify both readbacks', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);
    $writes = [];
    $completed = [];

    $result = VgBricksTreeWriter::execute(
        $plan,
        static function (int $id) use (&$state): array { return $state[$id]; },
        static fn (int $id, array $candidate): array => $candidate,
        static function (int $id, array $candidate) use (&$state, &$writes): void {
            $writes[] = $id;
            $state[$id]['elements'] = $candidate;
        },
        static function (array $record) use (&$completed): void { $completed[] = $record; }
    );

    vg_bricks_tree_writer_test($writes === [933, 1733], 'Writer did not apply both approved child additions in allow-listed order.');
    vg_bricks_tree_writer_test(count($completed) === 2 && $result === $completed, 'Verified completion records were not emitted for both documents.');
    foreach ([933, 1733] as $postId) {
        vg_bricks_tree_writer_test($state[$postId]['elements'] === $after[$postId], "Persisted target {$postId} differs from the complete planned tree.");
        $beforeById = vg_bricks_tree_writer_by_id($before[$postId]);
        $afterById = vg_bricks_tree_writer_by_id($state[$postId]['elements']);
        foreach ($beforeById as $id => $element) {
            if ($id === ($postId === 933 ? 'friend1' : 'course1')) {
                unset($element['children'], $afterById[$id]['children']);
            }
            vg_bricks_tree_writer_test($afterById[$id] === $element, "Existing element {$id} changed outside its approved child references.");
        }
    }
});

$test('stale target before-image prevents every write', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);
    $state[1733]['elements'][0]['settings']['class'] = 'concurrent-edit';
    $writes = [];

    vg_bricks_tree_writer_expect_failure(
        static function () use ($plan, &$state, &$writes): void {
            VgBricksTreeWriter::execute(
                $plan,
                static function (int $id) use (&$state): array { return $state[$id]; },
                static fn (int $id, array $candidate): array => $candidate,
                static function (int $id, array $candidate) use (&$writes): void { $writes[] = $id; }
            );
        },
        'A stale before-image was not rejected.'
    );
    vg_bricks_tree_writer_test($writes === [], 'A target was written before all before-images passed.');
});

$test('unapproved deletion of an existing flat element is rejected', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][0]['children'] = ['member1', 'start01'];
    $document['elements'] = array_values(array_filter(
        $document['elements'],
        static fn (array $element): bool => $element['id'] !== 'controls1'
    ));
    vg_bricks_tree_writer_refresh_digests($plan);
    $writes = [];

    vg_bricks_tree_writer_expect_failure(
        static function () use ($plan, $before, &$writes): void {
            VgBricksTreeWriter::execute(
                $plan,
                static fn (int $id): array => vg_bricks_tree_writer_state($before)[$id],
                static fn (int $id, array $candidate): array => $candidate,
                static function (int $id, array $candidate) use (&$writes): void { $writes[] = $id; }
            );
        },
        'Deleting an existing Bricks element passed the exact-change guard.'
    );
    vg_bricks_tree_writer_test($writes === [], 'Writer attempted a save after an unapproved deletion.');
});

$test('existing element settings cannot change under a child-addition plan', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][1]['settings']['label'] = 'Changed existing membership';
    $document['allowed_changed_ids'][] = 'member1';
    vg_bricks_tree_writer_refresh_digests($plan);

    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::validatePlan($plan),
        'A plan that changes existing Bricks settings passed validation.'
    );
});

$test('existing element reparenting is rejected even when the plan lists its changed IDs', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $overrides = [
        1733 => ['changed' => ['course1'], 'added' => ['start01']],
        933 => ['changed' => ['buttons1', 'friend1', 'tiers01'], 'added' => ['remind1']],
    ];
    $plan = vg_bricks_tree_writer_plan($before, $after, $overrides);
    $document =& vg_bricks_tree_writer_document($plan, 933);
    $document['elements'][0]['children'] = ['tiers01', 'remind1'];
    $document['elements'][1]['children'] = ['buttons1'];
    $document['elements'][2]['parent'] = 'tiers01';
    vg_bricks_tree_writer_refresh_digests($plan);

    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::validatePlan($plan),
        'Reparenting an existing Bricks element passed validation.'
    );
});

$test('reordering existing records or child references is rejected', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 933);
    $document['elements'] = [$document['elements'][0], $document['elements'][2], $document['elements'][1], $document['elements'][3]];
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::execute(
            $plan,
            static fn (int $id): array => vg_bricks_tree_writer_state($before)[$id],
            static fn (int $id, array $candidate): array => $candidate,
            static function (int $id, array $candidate): void {}
        ),
        'Reordering existing flat element records passed validation.'
    );

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][0]['children'] = ['controls1', 'start01', 'member1'];
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::validatePlan($plan),
        'Reordering existing child references passed validation.'
    );
});

$test('duplicate IDs, dangling references, wrong parents, orphans and cycles are rejected', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][] = $document['elements'][1];
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'Duplicate Bricks element IDs were accepted.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][0]['children'][] = 'missing1';
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'A dangling child reference was accepted.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][0]['children'][0] = ['id' => 'member1'];
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'A nested child object was accepted in the flat element list.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][0]['children'][] = 'member1';
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'Duplicate child ID references were accepted.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][2]['parent'] = 'missing1';
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'A missing parent reference was accepted.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][2]['parent'] = 'member1';
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'An inconsistent parent-child reference was accepted.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][0]['parent'] = 'member1';
    $document['elements'][1]['children'][] = 'course1';
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'A parent cycle was accepted.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 933);
    $document['elements'][] = [
        'id' => 'rootnew',
        'name' => 'section',
        'parent' => 0,
        'children' => [],
        'settings' => [],
    ];
    $document['allowed_added_ids'][] = 'rootnew';
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'A new root element was accepted in a child-addition plan.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $document =& vg_bricks_tree_writer_document($plan, 1733);
    $document['elements'][0]['children'] = ['member1'];
    vg_bricks_tree_writer_refresh_digests($plan);
    vg_bricks_tree_writer_expect_failure(static fn () => VgBricksTreeWriter::validatePlan($plan), 'An element with a parent but no parent child-reference was accepted.');
});

$test('sanitizer changes are detected before any write', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);
    $writes = [];

    vg_bricks_tree_writer_expect_failure(
        static function () use ($plan, &$state, &$writes): void {
            VgBricksTreeWriter::execute(
                $plan,
                static function (int $id) use (&$state): array { return $state[$id]; },
                static function (int $id, array $candidate): array {
                    if ($id === 933) {
                        $candidate[0]['settings']['sanitizer_changed'] = true;
                    }
                    return $candidate;
                },
                static function (int $id, array $candidate) use (&$writes): void { $writes[] = $id; }
            );
        },
        'A non-identical sanitized tree was not rejected during preflight.'
    );
    vg_bricks_tree_writer_test($writes === [], 'A target was written after sanitization changed the candidate.');
});

$test('a just-in-time concurrent edit prevents the second target save', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);
    $reads = [];
    $writes = [];
    $completed = [];

    vg_bricks_tree_writer_expect_failure(
        static function () use ($plan, &$state, &$reads, &$writes, &$completed): void {
            VgBricksTreeWriter::execute(
                $plan,
                static function (int $id) use (&$state, &$reads): array {
                    $reads[$id] = ($reads[$id] ?? 0) + 1;
                    if ($id === 1733 && $reads[$id] === 2) {
                        $state[$id]['elements'][0]['settings']['class'] = 'concurrent-edit';
                    }
                    return $state[$id];
                },
                static fn (int $id, array $candidate): array => $candidate,
                static function (int $id, array $candidate) use (&$state, &$writes): void {
                    $writes[] = $id;
                    $state[$id]['elements'] = $candidate;
                },
                static function (array $record) use (&$completed): void { $completed[] = $record; }
            );
        },
        'A just-in-time concurrent edit was not rejected.'
    );
    vg_bricks_tree_writer_test($writes === [933], 'Writer attempted the second save after its just-in-time before-image changed.');
    vg_bricks_tree_writer_test(count($completed) === 1 && $completed[0]['post_id'] === 933, 'The verified partial completion was not preserved.');
});

$test('post-save readback must match the complete flat plan', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);

    vg_bricks_tree_writer_expect_failure(
        static function () use ($plan, &$state): void {
            VgBricksTreeWriter::execute(
                $plan,
                static function (int $id) use (&$state): array { return $state[$id]; },
                static fn (int $id, array $candidate): array => $candidate,
                static function (int $id, array $candidate) use (&$state): void {
                    $state[$id]['elements'] = $candidate;
                    if ($id === 933) {
                        $state[$id]['elements'][1]['settings']['label'] = 'readback mismatch';
                    }
                }
            );
        },
        'A non-identical persisted Bricks element list passed readback verification.'
    );
});

$test('a failed second save preserves the verified first completion and stops', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);
    $writes = [];
    $completed = [];

    vg_bricks_tree_writer_expect_failure(
        static function () use ($plan, &$state, &$writes, &$completed): void {
            VgBricksTreeWriter::execute(
                $plan,
                static function (int $id) use (&$state): array { return $state[$id]; },
                static fn (int $id, array $candidate): array => $candidate,
                static function (int $id, array $candidate) use (&$state, &$writes): void {
                    $writes[] = $id;
                    if ($id === 1733) {
                        throw new VgBricksTreeWriterException('Simulated second save failure.');
                    }
                    $state[$id]['elements'] = $candidate;
                },
                static function (array $record) use (&$completed): void { $completed[] = $record; }
            );
        },
        'A simulated second-save failure was not returned.'
    );

    vg_bricks_tree_writer_test($writes === [933, 1733], 'Writer retried or changed the write order after a failed save.');
    vg_bricks_tree_writer_test(count($completed) === 1 && $completed[0]['post_id'] === 933, 'Partial completion record was not retained after the second save failed.');
    vg_bricks_tree_writer_test($state[933]['elements'] === $after[933], 'First target was not saved before the simulated second-save failure.');
    vg_bricks_tree_writer_test($state[1733]['elements'] === $before[1733], 'Failed second target changed its persisted tree.');
});

echo "{$tests} Bricks tree writer tests passed.\n";
