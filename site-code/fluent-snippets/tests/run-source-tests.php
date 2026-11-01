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
    if (! defined('VG_FLUENT_SNIPPET_UPDATER_TESTING')) {
        define('VG_FLUENT_SNIPPET_UPDATER_TESTING', true);
    }
    require_once $updaterPath;
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
