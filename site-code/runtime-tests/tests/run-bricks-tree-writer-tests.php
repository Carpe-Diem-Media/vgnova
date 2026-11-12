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
    } catch (Throwable) {
        return;
    }

    throw new RuntimeException($message);
}

function vg_bricks_tree_writer_fixtures(): array
{
    $before = [
        1733 => [
            ['id' => 'course1', 'name' => 'section', 'settings' => ['class' => 'course'], 'children' => [
                ['id' => 'member1', 'name' => 'block', 'settings' => ['label' => 'Existing membership'], 'children' => []],
            ]],
        ],
        933 => [
            ['id' => 'friend1', 'name' => 'section', 'settings' => ['class' => 'friend'], 'children' => [
                ['id' => 'tiers01', 'name' => 'block', 'settings' => ['label' => 'Existing tiers'], 'children' => []],
            ]],
        ],
    ];

    $after = $before;
    $after[1733][0]['children'][] = ['id' => 'start01', 'name' => 'form', 'settings' => ['action' => ['vgpc_journey_intent']], 'children' => []];
    $after[933][0]['children'][] = ['id' => 'remind1', 'name' => 'block', 'settings' => ['label' => 'Course reminder'], 'children' => []];

    return [$before, $after];
}

function vg_bricks_tree_writer_plan(array $before, array $after): array
{
    return [
        'schema_version' => 1,
        'target' => 'vgnova-local-10039',
        'bricks_version' => '2.4.5',
        'documents' => [
            [
                'post_id' => 1733,
                'post_type' => 'bricks_template',
                'template_type' => 'content',
                'before_sha256' => VgBricksTreeWriter::treeSha256($before[1733]),
                'after_sha256' => VgBricksTreeWriter::treeSha256($after[1733]),
                'before_elements' => $before[1733],
                'allowed_changed_ids' => ['course1'],
                'allowed_added_ids' => ['start01'],
                'elements' => $after[1733],
            ],
            [
                'post_id' => 933,
                'post_type' => 'page',
                'template_type' => null,
                'before_sha256' => VgBricksTreeWriter::treeSha256($before[933]),
                'after_sha256' => VgBricksTreeWriter::treeSha256($after[933]),
                'before_elements' => $before[933],
                'allowed_changed_ids' => ['friend1'],
                'allowed_added_ids' => ['remind1'],
                'elements' => $after[933],
            ],
        ],
    ];
}

function vg_bricks_tree_writer_state(array $before): array
{
    return [
        1733 => ['post_id' => 1733, 'post_type' => 'bricks_template', 'template_type' => 'content', 'elements' => $before[1733]],
        933 => ['post_id' => 933, 'post_type' => 'page', 'template_type' => null, 'elements' => $before[933]],
    ];
}

$tests = 0;
$test = static function (string $name, callable $callback) use (&$tests): void {
    $callback();
    $tests++;
    echo "PASS {$name}\n";
};

$test('only the two approved Bricks targets pass plan validation', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $documents = VgBricksTreeWriter::validatePlan($plan);
    vg_bricks_tree_writer_test(array_keys($documents) === [933, 1733], 'Plan targets were not normalized to the allow-listed order.');

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

$test('all before-images are checked before the first write', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);
    $state[933]['elements'][0]['settings']['class'] = 'concurrent-edit';
    $writes = [];

    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::execute(
            $plan,
            static function (int $id) use (&$state): array { return $state[$id]; },
            static fn (int $id, array $candidate): array => $candidate,
            static function (int $id, array $candidate) use (&$writes): void { $writes[] = $id; }
        ),
        'A stale second before-image was not rejected.'
    );
    vg_bricks_tree_writer_test($writes === [], 'A target was written before both before-images passed.');
});

$test('the planned change cannot delete or alter unapproved Bricks elements', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);
    $plan['documents'][0]['allowed_changed_ids'] = [];
    $writes = [];

    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::execute(
            $plan,
            static function (int $id) use (&$state): array { return $state[$id]; },
            static fn (int $id, array $candidate): array => $candidate,
            static function (int $id, array $candidate) use (&$writes): void { $writes[] = $id; }
        ),
        'An unapproved element edit passed the exact-diff guard.'
    );
    vg_bricks_tree_writer_test($writes === [], 'A target was written after the exact-diff guard failed.');

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $plan['documents'][0]['elements'][0]['children'] = [];
    $plan['documents'][0]['after_sha256'] = VgBricksTreeWriter::treeSha256($plan['documents'][0]['elements']);
    $plan['documents'][0]['allowed_added_ids'] = [];
    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::execute(
            $plan,
            static function (int $id) use (&$state): array { return $state[$id]; },
            static fn (int $id, array $candidate): array => $candidate,
            static function (int $id, array $candidate) use (&$writes): void { $writes[] = $id; }
        ),
        'A plan that deleted existing content passed validation.'
    );

    $plan = vg_bricks_tree_writer_plan($before, $after);
    $plan['documents'][0]['elements'][] = ['id' => 'extra1', 'name' => 'section', 'settings' => [], 'children' => []];
    $plan['documents'][0]['after_sha256'] = VgBricksTreeWriter::treeSha256($plan['documents'][0]['elements']);
    $plan['documents'][0]['allowed_added_ids'][] = 'extra1';
    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::execute(
            $plan,
            static function (int $id) use (&$state): array { return $state[$id]; },
            static fn (int $id, array $candidate): array => $candidate,
            static function (int $id, array $candidate) use (&$writes): void { $writes[] = $id; }
        ),
        'A top-level Bricks insertion passed the preserved-root guard.'
    );
});

$test('Bricks sanitizer changes are detected before any write', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);
    $writes = [];

    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::execute(
            $plan,
            static function (int $id) use (&$state): array { return $state[$id]; },
            static fn (int $id, array $candidate): array => $id === 1733 ? [] : $candidate,
            static function (int $id, array $candidate) use (&$writes): void { $writes[] = $id; }
        ),
        'A non-identical sanitized tree was not rejected during preflight.'
    );
    vg_bricks_tree_writer_test($writes === [], 'A target was written after sanitization changed the candidate.');
});

$test('each save rechecks its before-image and verifies the complete readback tree', static function (): void {
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

    vg_bricks_tree_writer_test($writes === [933, 1733], 'Writer did not apply the approved documents in ID order.');
    vg_bricks_tree_writer_test(count($completed) === 2 && $result === $completed, 'Verified completion records were not emitted for both documents.');
    vg_bricks_tree_writer_test($state[933]['elements'] === $after[933] && $state[1733]['elements'] === $after[1733], 'The persisted trees do not exactly match the candidate trees.');

    $state = vg_bricks_tree_writer_state($before);
    $writes = [];
    vg_bricks_tree_writer_expect_failure(
        static function () use (&$plan, &$state, &$writes): void {
            VgBricksTreeWriter::execute(
                $plan,
                static function (int $id) use (&$state): array { return $state[$id]; },
                static fn (int $id, array $candidate): array => $candidate,
                static function (int $id, array $candidate) use (&$state, &$writes): void {
                    $writes[] = $id;
                    $state[$id]['elements'] = $candidate;
                    if ($id === 933) {
                        $state[1733]['elements'][0]['settings']['class'] = 'concurrent-edit';
                    }
                }
            );
        },
        'A changed just-in-time before-image was not rejected.'
    );
    vg_bricks_tree_writer_test($writes === [933], 'Writer failed to stop when the second target changed after preflight.');
});

$test('a save with a non-identical post-write tree is rejected', static function (): void {
    [$before, $after] = vg_bricks_tree_writer_fixtures();
    $plan = vg_bricks_tree_writer_plan($before, $after);
    $state = vg_bricks_tree_writer_state($before);

    vg_bricks_tree_writer_expect_failure(
        static fn () => VgBricksTreeWriter::execute(
            $plan,
            static function (int $id) use (&$state): array { return $state[$id]; },
            static fn (int $id, array $candidate): array => $candidate,
            static function (int $id, array $candidate) use (&$state): void {
                $state[$id]['elements'] = $candidate;
                array_pop($state[$id]['elements'][0]['children']);
            }
        ),
        'A non-identical post-write tree passed verification.'
    );
});

echo "{$tests} Bricks tree writer tests passed.\n";
