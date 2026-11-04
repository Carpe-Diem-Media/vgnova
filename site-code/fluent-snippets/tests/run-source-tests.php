<?php
declare(strict_types=1);

$repositoryRoot = dirname(__DIR__, 3);
$sourcePath = $repositoryRoot . '/site-code/fluent-snippets/10-vg-bricks-medewerker-queries.php';
$metadataPath = $repositoryRoot . '/site-code/fluent-snippets/10-vg-bricks-medewerker-queries.meta.json';
$updaterPath = $repositoryRoot . '/site-code/bin/update-fluent-snippet.php';
$failures = [];
$tests = 0;

$test = static function (string $name, callable $assertion) use (&$failures, &$tests): void {
    $tests++;
    try {
        $assertion();
        echo "PASS {$name}\n";
    } catch (Throwable $error) {
        $failures[] = $name . ': ' . $error->getMessage();
        echo "FAIL {$name}: {$error->getMessage()}\n";
    }
};

$assert = static function (bool $condition, string $message): void {
    if (! $condition) {
        throw new RuntimeException($message);
    }
};

if (! defined('VG_FLUENT_SNIPPET_UPDATER_TESTING')) {
    define('VG_FLUENT_SNIPPET_UPDATER_TESTING', true);
}
require_once $updaterPath;

$test('canonical PHP source exists and begins at its opening token', static function () use ($sourcePath, $assert): void {
    $assert(is_file($sourcePath), 'Canonical PHP source is missing.');
    $source = file_get_contents($sourcePath);
    $assert(is_string($source), 'Canonical PHP source could not be read.');
    $assert(str_starts_with($source, '<?php'), 'Source has bytes before the first PHP token.');
    $assert(! str_starts_with($source, "\xEF\xBB\xBF"), 'Source contains a UTF-8 BOM.');
    $assert(hash('sha256', $source) === '8ae19709e1a6979f1d58b4fa30165c77fa677d10139d1f4d2afc3e8c84ceebac', 'Source differs from the approved code with only the leading LF removed.');

    $tokens = token_get_all($source);
    $assert(is_array($tokens[0] ?? null) && $tokens[0][0] === T_OPEN_TAG, 'First PHP token is not the opening tag.');
});

$test('canonical PHP source passes php -l', static function () use ($sourcePath, $assert): void {
    if (! is_file($sourcePath)) {
        throw new RuntimeException('Canonical PHP source is missing.');
    }

    $command = [PHP_BINARY, '-l', $sourcePath];
    $process = proc_open($command, [
        0 => ['file', '/dev/null', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ], $pipes);
    $assert(is_resource($process), 'PHP lint process could not start.');
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $status = proc_close($process);
    $assert($status === 0, trim((string) $stdout . (string) $stderr));
});

$test('exact six legacy query modes are preserved', static function () use ($sourcePath, $assert): void {
    if (! is_file($sourcePath)) {
        throw new RuntimeException('Canonical PHP source is missing.');
    }

    $source = file_get_contents($sourcePath);
    $assert(is_string($source), 'Canonical PHP source could not be read.');
    preg_match_all("/case\\s+'([^']+)'\\s*:/", $source, $matches);
    $modes = $matches[1] ?? [];
    $expected = [
        'event_medewerkers',
        'event_products',
        'medewerker_events',
        'medewerker_products',
        'product_medewerkers',
        'product_source_event',
    ];
    $assert($modes === $expected, 'Query modes differ from the exact six-mode runtime baseline.');
});

$test('stable FluentSnippets metadata parses and matches the approved identity', static function () use ($metadataPath, $assert): void {
    $assert(is_file($metadataPath), 'Stable metadata JSON is missing.');
    $metadataJson = file_get_contents($metadataPath);
    $assert(is_string($metadataJson), 'Stable metadata JSON could not be read.');
    $metadata = json_decode($metadataJson, true, 32, JSON_THROW_ON_ERROR);
    $expected = [
        'file_name' => '10-vg-bricks-medewerker-queries.php',
        'title' => '[Bricks] Medewerker — Query loop support',
        'description' => 'Bricks Query Loop vars for Medewerker MVP (ACF relationships).',
        'type' => 'PHP',
        'status' => 'published',
        'run_at' => 'all',
        'condition' => ['status' => 'no', 'run_if' => 'assertive', 'items' => [[]]],
        'tags' => 'medewerker,bricks,acf,query-loop',
        'group' => 'Medewerkers',
        'priority' => 10,
        'load_as_file' => '',
        'load_in_block_editor' => '',
    ];
    $assert($metadata === $expected, 'Stable metadata differs from the approved runtime identity.');
});

$test('updater fails closed for a different runtime target', static function () use ($updaterPath, $assert): void {
    $assert(is_file($updaterPath), 'Runtime updater is missing.');
    $assert(function_exists('vg_fs001_assert_runtime_identity'), 'Updater identity guard is unavailable for verification.');

    $identity = [
        'target' => 'vgnova-local-10039',
        'wp_root' => '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public',
        'abspath' => '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public',
        'home_url' => 'http://localhost:10039/',
        'site_url' => 'http://localhost:10039/',
        'plugin_version' => '10.56',
        'storage_dir' => '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/wp-content/fluent-snippet-storage',
    ];
    vg_fs001_assert_runtime_identity($identity);
    $identity['target'] = 'wrong-runtime';

    try {
        vg_fs001_assert_runtime_identity($identity);
    } catch (RuntimeException) {
        $identity['target'] = 'vgnova-local-10039';
        $identity['wp_root'] = '/wrong/wordpress/root';
        try {
            vg_fs001_assert_runtime_identity($identity);
        } catch (RuntimeException) {
            return;
        }
        throw new RuntimeException('Updater accepted a WordPress root other than the registered Local root.');
    }

    throw new RuntimeException('Updater accepted a runtime target other than vgnova-local-10039.');
});

$approved = vg_fs001_load_approved_source();
$makeCurrentState = static function (array $changes = []) use ($approved): array {
    $state = [
        'record' => [
            'meta' => $approved['metadata'],
            'code' => $approved['source'],
            'file' => VG_FS001_WP_ROOT . '/wp-content/fluent-snippet-storage/' . VG_FS001_FILE_NAME,
            'status' => 'published',
        ],
        'stable_metadata' => $approved['metadata'],
        'storage_sha256' => str_repeat('a', 64),
        'storage_contents' => "<?php\n// <Internal Doc Start>\n/*\n* @name: helper\n*/\n?>\n<?php if (!defined(\"ABSPATH\")) { return;} // <Internal Doc End> ?>\n" . $approved['source'],
        'index_sha256' => str_repeat('b', 64),
        'storage_path' => VG_FS001_WP_ROOT . '/wp-content/fluent-snippet-storage/' . VG_FS001_FILE_NAME,
        'siblings' => ['9-acf-product-copy.php' => str_repeat('c', 64)],
        'published_index_entry' => $approved['metadata'],
        'draft_index_entry' => null,
        'conflicting_draft_entries' => [],
        'runtime_identity' => ['target' => VG_FS001_TARGET],
        'storage_realpath' => VG_FS001_WP_ROOT . '/wp-content/fluent-snippet-storage',
    ];

    foreach ($changes as $path => $value) {
        $segments = explode('.', $path);
        $cursor = &$state;
        foreach (array_slice($segments, 0, -1) as $segment) {
            $cursor = &$cursor[$segment];
        }
        $cursor[end($segments)] = $value;
        unset($cursor);
    }

    return $state;
};

$test('current FluentSnippets state accepts exact published source and metadata', static function () use ($approved, $makeCurrentState, $assert): void {
    $state = $makeCurrentState();
    vg_fs001_assert_current_integrity_state($state, $approved);
    $assert(true, 'Exact current helper state was rejected.');
});

$test('current FluentSnippets state rejects executable-code drift', static function () use ($approved, $makeCurrentState, $assert): void {
    $state = $makeCurrentState(['record.code' => "<?php echo 'changed';\n"]);

    try {
        vg_fs001_assert_current_integrity_state($state, $approved);
    } catch (RuntimeException) {
        $assert(true, 'Executable-code drift was rejected.');
        return;
    }

    throw new RuntimeException('Executable-code drift was accepted.');
});

$test('current FluentSnippets state rejects unexpected snippet status', static function () use ($approved, $makeCurrentState, $assert): void {
    $state = $makeCurrentState(['record.status' => 'draft']);

    try {
        vg_fs001_assert_current_integrity_state($state, $approved);
    } catch (RuntimeException) {
        $assert(true, 'Unexpected snippet status was rejected.');
        return;
    }

    throw new RuntimeException('A draft snippet was accepted as the current state.');
});

$test('current FluentSnippets state rejects missing required metadata', static function () use ($approved, $makeCurrentState, $assert): void {
    $state = $makeCurrentState();
    unset($state['record']['meta']['description']);

    try {
        vg_fs001_assert_current_integrity_state($state, $approved);
    } catch (RuntimeException) {
        $assert(true, 'Missing required metadata was rejected.');
        return;
    }

    throw new RuntimeException('Missing required metadata was accepted.');
});

$test('current FluentSnippets state rejects incorrect active-index configuration', static function () use ($approved, $makeCurrentState, $assert): void {
    $state = $makeCurrentState();
    $state['published_index_entry']['run_at'] = 'admin';

    try {
        vg_fs001_assert_current_integrity_state($state, $approved);
    } catch (RuntimeException) {
        $assert(true, 'Incorrect active-index configuration was rejected.');
        return;
    }

    throw new RuntimeException('Incorrect active-index configuration was accepted.');
});

$test('current FluentSnippets state rejects a conflicting draft', static function () use ($approved, $makeCurrentState, $assert): void {
    $state = $makeCurrentState([
        'conflicting_draft_entries' => [['key' => 'conflicting-draft.php']],
    ]);

    try {
        vg_fs001_assert_current_integrity_state($state, $approved);
    } catch (RuntimeException) {
        $assert(true, 'Conflicting draft was rejected.');
        return;
    }

    throw new RuntimeException('A conflicting draft was accepted.');
});

$test('current FluentSnippets draft inventory detects the canonical title under another file', static function () use ($approved, $assert): void {
    $drafts = [
        '99-helper-draft.php' => [
            'file_name' => '99-helper-draft.php',
            'name' => $approved['metadata']['title'],
            'status' => 'draft',
        ],
    ];
    $conflicts = vg_fs001_conflicting_draft_entries($drafts, $approved['metadata']);
    $assert(count($conflicts) === 1, 'A draft with the canonical helper title was not detected.');
    $assert($conflicts[0]['key'] === '99-helper-draft.php', 'The conflicting draft identity was not retained.');
});

$test('current FluentSnippets state rejects executable code in the storage wrapper', static function () use ($approved, $makeCurrentState, $assert): void {
    $state = $makeCurrentState();
    $state['storage_contents'] = str_replace(
        '// <Internal Doc Start>',
        "echo 'unexpected';\n// <Internal Doc Start>",
        $state['storage_contents']
    );

    try {
        vg_fs001_assert_current_integrity_state($state, $approved);
    } catch (RuntimeException) {
        $assert(true, 'Executable wrapper code was rejected.');
        return;
    }

    throw new RuntimeException('Executable code in the storage wrapper was accepted.');
});

$test('current FluentSnippets state accepts the installed parser legacy wrapper form', static function () use ($approved, $makeCurrentState, $assert): void {
    $state = $makeCurrentState();
    $modernGuard = '<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>' . PHP_EOL;
    $legacyGuard = "<?php if (!defined('ABSPATH')) {" . PHP_EOL . "    return;" . PHP_EOL . "} // <Internal Doc End> ?>" . PHP_EOL;
    $state['storage_contents'] = str_replace($modernGuard, $legacyGuard, $state['storage_contents']);
    vg_fs001_assert_current_integrity_state($state, $approved);
    $assert(true, 'Installed FluentSnippets parser legacy wrapper form was accepted.');
});

$test('current FluentSnippets state rejects invalid runtime identity', static function () use ($assert): void {
    $identity = [
        'target' => 'vgnova-local-10039',
        'wp_root' => '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public',
        'abspath' => '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public',
        'home_url' => 'http://localhost:10039/',
        'site_url' => 'http://localhost:10039/',
        'plugin_version' => '10.56',
        'storage_dir' => '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public/wp-content/fluent-snippet-storage',
    ];
    $identity['plugin_version'] = '10.55';

    try {
        vg_fs001_assert_runtime_identity($identity);
    } catch (RuntimeException) {
        $assert(true, 'Invalid plugin/runtime identity was rejected.');
        return;
    }

    throw new RuntimeException('An invalid FluentSnippets runtime identity was accepted.');
});

$test('current FluentSnippets source rejects direct output tokens', static function () use ($assert): void {
    try {
        vg_fs001_assert_no_output_source("<?php echo 'unexpected';\n");
    } catch (RuntimeException) {
        $assert(true, 'Direct output source was rejected.');
        return;
    }

    throw new RuntimeException('Direct output source was accepted.');
});

$test('current FluentSnippets state detects changes during QA', static function () use ($makeCurrentState, $assert): void {
    $before = $makeCurrentState();
    $after = $makeCurrentState(['index_sha256' => str_repeat('d', 64)]);

    try {
        vg_fs001_assert_state_unchanged($before, $after);
    } catch (RuntimeException) {
        $assert(true, 'Index change during QA was rejected.');
        return;
    }

    throw new RuntimeException('Index state changed during QA without detection.');
});

$test('current FluentSnippets state detects sibling snippet changes during QA', static function () use ($makeCurrentState, $assert): void {
    $before = $makeCurrentState();
    $after = $makeCurrentState(['siblings.9-acf-product-copy.php' => str_repeat('d', 64)]);

    try {
        vg_fs001_assert_state_unchanged($before, $after);
    } catch (RuntimeException) {
        $assert(true, 'Sibling snippet change during QA was rejected.');
        return;
    }

    throw new RuntimeException('Sibling snippet state changed during QA without detection.');
});

$test('semantic rollback preserves the prior output byte and all PHP source', static function () use ($sourcePath, $assert): void {
    if (! defined('VG_FLUENT_SNIPPET_UPDATER_TESTING')) {
        define('VG_FLUENT_SNIPPET_UPDATER_TESTING', true);
    }
    require_once dirname(__DIR__, 3) . '/site-code/bin/update-fluent-snippet.php';
    $source = file_get_contents($sourcePath);
    $assert(is_string($source), 'Canonical PHP source could not be read.');

    $previousCode = "\n" . $source;
    $rollbackSource = vg_fs001_legacy_rollback_source($previousCode);
    $expectedPrefix = "<?php\n?>\n\n";
    $assert(str_starts_with($rollbackSource, $expectedPrefix), 'Rollback did not express the previous LF through FluentSnippets input.');
    $assert(substr($rollbackSource, strlen($expectedPrefix)) === $source, 'Rollback changed the previous PHP source body.');
    $assert("<?php\n" . vg_fs001_helper_body($rollbackSource) === $rollbackSource, 'FluentSnippets update normalization would change the rollback source.');

    $inlineHtml = [];
    foreach (token_get_all($rollbackSource) as $token) {
        if (is_array($token) && $token[0] === T_INLINE_HTML) {
            $inlineHtml[] = $token[1];
        }
    }
    $assert($inlineHtml === ["\n"], 'Rollback did not restore exactly the prior one-LF output.');
});

if ($failures !== []) {
    fwrite(STDERR, implode("\n", $failures) . "\n");
    exit(1);
}

echo "PASS {$tests} FluentSnippets source tests\n";
