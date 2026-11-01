<?php
declare(strict_types=1);

const VG_FS001_TARGET = 'vgnova-local-10039';
const VG_FS001_WP_ROOT = '/home/jcschoeman96/Local Sites/voelgoed-novamira/app/public';
const VG_FS001_HOME_URL = 'http://localhost:10039/';
const VG_FS001_SITE_URL = 'http://localhost:10039/';
const VG_FS001_PLUGIN_VERSION = '10.56';
const VG_FS001_FILE_NAME = '10-vg-bricks-medewerker-queries.php';
const VG_FS001_PRE_CODE_SHA256 = '7134a1de4a5e8447314b1c3843a60349b8622beaad5de940c4d8c66cb019ae4e';
const VG_FS001_SOURCE_SHA256 = '8ae19709e1a6979f1d58b4fa30165c77fa677d10139d1f4d2afc3e8c84ceebac';

/** @param array<string,mixed> $identity */
function vg_fs001_assert_runtime_identity(array $identity): void
{
    $expected = [
        'target' => VG_FS001_TARGET,
        'wp_root' => VG_FS001_WP_ROOT,
        'abspath' => VG_FS001_WP_ROOT,
        'home_url' => VG_FS001_HOME_URL,
        'site_url' => VG_FS001_SITE_URL,
        'plugin_version' => VG_FS001_PLUGIN_VERSION,
        'storage_dir' => VG_FS001_WP_ROOT . '/wp-content/fluent-snippet-storage',
    ];

    foreach ($expected as $key => $value) {
        if (($identity[$key] ?? null) !== $value) {
            throw new RuntimeException('FS-001 runtime identity mismatch: ' . $key . '.');
        }
    }
}

/** @param array<string,mixed> $metadata @return array<string,mixed> */
function vg_fs001_stable_metadata(array $metadata, ?string $fileName = null): array
{
    $condition = $metadata['condition'] ?? null;
    if (is_string($condition)) {
        try {
            $condition = json_decode($condition, true, 32, JSON_THROW_ON_ERROR);
        } catch (JsonException $error) {
            throw new RuntimeException('Snippet condition metadata is invalid.', 0, $error);
        }
    }
    if (! is_array($condition)) {
        throw new RuntimeException('Snippet condition metadata is missing.');
    }

    return [
        'file_name' => (string) ($metadata['file_name'] ?? $fileName ?? ''),
        'title' => (string) ($metadata['title'] ?? $metadata['name'] ?? ''),
        'description' => (string) ($metadata['description'] ?? ''),
        'type' => (string) ($metadata['type'] ?? ''),
        'status' => (string) ($metadata['status'] ?? ''),
        'run_at' => (string) ($metadata['run_at'] ?? ''),
        'condition' => $condition,
        'tags' => (string) ($metadata['tags'] ?? ''),
        'group' => (string) ($metadata['group'] ?? ''),
        'priority' => (int) ($metadata['priority'] ?? 0),
        'load_as_file' => (string) ($metadata['load_as_file'] ?? ''),
        'load_in_block_editor' => (string) ($metadata['load_in_block_editor'] ?? ''),
    ];
}

/** @return array{source:string,metadata:array<string,mixed>} */
function vg_fs001_load_approved_source(): array
{
    $sourceDirectory = dirname(__DIR__) . '/fluent-snippets';
    $sourcePath = $sourceDirectory . '/' . VG_FS001_FILE_NAME;
    $metadataPath = $sourceDirectory . '/10-vg-bricks-medewerker-queries.meta.json';

    foreach ([$sourcePath, $metadataPath] as $path) {
        if (is_link($path) || ! is_file($path)) {
            throw new RuntimeException('Approved FluentSnippets source file is missing or unsafe.');
        }
    }

    $source = file_get_contents($sourcePath);
    $metadataJson = file_get_contents($metadataPath);
    if (! is_string($source) || ! is_string($metadataJson)) {
        throw new RuntimeException('Approved FluentSnippets source could not be read.');
    }
    if (hash('sha256', $source) !== VG_FS001_SOURCE_SHA256) {
        throw new RuntimeException('Approved FluentSnippets source hash does not match FS-001.');
    }
    if (! str_starts_with($source, "<?php\n") || str_contains($source, "\r")) {
        throw new RuntimeException('Approved PHP source must begin at its PHP token and use LF line endings.');
    }

    try {
        $metadata = json_decode($metadataJson, true, 32, JSON_THROW_ON_ERROR);
    } catch (JsonException $error) {
        throw new RuntimeException('Approved FluentSnippets metadata JSON is invalid.', 0, $error);
    }
    if (! is_array($metadata)) {
        throw new RuntimeException('Approved FluentSnippets metadata must be a JSON object.');
    }

    $expectedKeys = [
        'file_name', 'title', 'description', 'type', 'status', 'run_at', 'condition',
        'tags', 'group', 'priority', 'load_as_file', 'load_in_block_editor',
    ];
    $actualKeys = array_keys($metadata);
    sort($expectedKeys, SORT_STRING);
    sort($actualKeys, SORT_STRING);
    if ($actualKeys !== $expectedKeys) {
        throw new RuntimeException('Metadata must contain only the approved stable FluentSnippets fields.');
    }

    $stableMetadata = vg_fs001_stable_metadata($metadata);
    if ($stableMetadata['file_name'] !== VG_FS001_FILE_NAME
        || $stableMetadata['title'] !== '[Bricks] Medewerker — Query loop support'
        || $stableMetadata['type'] !== 'PHP'
        || $stableMetadata['status'] !== 'published'
        || $stableMetadata['run_at'] !== 'all') {
        throw new RuntimeException('Approved FluentSnippets metadata identity does not match FS-001.');
    }

    return ['source' => $source, 'metadata' => $stableMetadata];
}

/** @return array<string,mixed> */
function vg_fs001_read_record(string $fileName): array
{
    $record = (new \FluentSnippets\App\Model\Snippet())->findByFileName($fileName);
    if (is_wp_error($record)) {
        throw new RuntimeException('FluentSnippets could not read the expected snippet.');
    }
    if (! is_array($record) || ! is_array($record['meta'] ?? null) || ! is_string($record['code'] ?? null)) {
        throw new RuntimeException('FluentSnippets returned an incomplete snippet record.');
    }
    return $record;
}

/** @return array<string,string> */
function vg_fs001_sibling_inventory(string $storageDirectory): array
{
    $inventory = [];
    foreach (glob($storageDirectory . '/*.php') ?: [] as $path) {
        $fileName = basename($path);
        if (in_array($fileName, [VG_FS001_FILE_NAME, 'index.php'], true)) {
            continue;
        }
        if (is_link($path) || ! is_file($path)) {
            throw new RuntimeException('A sibling FluentSnippets file is missing or unsafe.');
        }
        $hash = hash_file('sha256', $path);
        if (! is_string($hash)) {
            throw new RuntimeException('A sibling FluentSnippets file could not be hashed.');
        }
        $inventory[$fileName] = $hash;
    }
    ksort($inventory, SORT_STRING);
    return $inventory;
}

/** @return array<string,mixed> */
function vg_fs001_capture_state(string $storageDirectory): array
{
    $snippetPath = $storageDirectory . '/' . VG_FS001_FILE_NAME;
    $indexPath = $storageDirectory . '/index.php';
    if (is_link($snippetPath) || ! is_file($snippetPath) || is_link($indexPath) || ! is_file($indexPath)) {
        throw new RuntimeException('The snippet or FluentSnippets index file is missing or unsafe.');
    }

    $storageHash = hash_file('sha256', $snippetPath);
    if (! is_string($storageHash)) {
        throw new RuntimeException('The FluentSnippets storage file could not be hashed.');
    }
    $record = vg_fs001_read_record(VG_FS001_FILE_NAME);
    $config = \FluentSnippets\App\Helpers\Helper::getIndexedConfig(false);
    if (! is_array($config)) {
        throw new RuntimeException('The FluentSnippets derived index could not be read.');
    }

    return [
        'record' => $record,
        'stable_metadata' => vg_fs001_stable_metadata($record['meta'], VG_FS001_FILE_NAME),
        'storage_sha256' => $storageHash,
        'siblings' => vg_fs001_sibling_inventory($storageDirectory),
        'published_index_entry' => $config['published'][VG_FS001_FILE_NAME] ?? null,
        'draft_index_entry' => $config['draft'][VG_FS001_FILE_NAME] ?? null,
    ];
}

/** @param array<string,mixed> $entry @param array<string,mixed> $expectedMetadata */
function vg_fs001_assert_published_index_entry(mixed $entry, array $expectedMetadata): void
{
    if (! is_array($entry)
        || vg_fs001_stable_metadata($entry) !== $expectedMetadata
        || ($entry['status'] ?? null) !== 'published'
        || ($entry['file_name'] ?? null) !== VG_FS001_FILE_NAME) {
        throw new RuntimeException('FluentSnippets index does not contain the expected published snippet.');
    }
}

/** @param array<string,mixed> $state @param array<string,mixed> $expectedMetadata */
function vg_fs001_assert_pre_change_baseline(array $state, string $source, array $expectedMetadata): void
{
    $record = $state['record'];
    if (($record['status'] ?? null) !== 'published'
        || ($state['stable_metadata'] ?? null) !== $expectedMetadata
        || hash('sha256', (string) ($record['code'] ?? '')) !== VG_FS001_PRE_CODE_SHA256
        || ($record['code'] ?? null) !== "\n" . $source
        || ($state['draft_index_entry'] ?? null) !== null) {
        throw new RuntimeException('Runtime snippet no longer matches the approved FS-001 pre-change baseline.');
    }
    vg_fs001_assert_published_index_entry($state['published_index_entry'] ?? null, $expectedMetadata);
}

function vg_fs001_helper_body(string $phpSource): string
{
    if (! str_starts_with($phpSource, "<?php\n")) {
        throw new RuntimeException('Canonical FluentSnippets source must begin with an LF PHP opening tag.');
    }
    return substr($phpSource, strlen("<?php\n"));
}

/** @param array<string,mixed> $baseMetadata @param array<string,mixed> $stableMetadata @return array<string,mixed> */
function vg_fs001_write_metadata(array $baseMetadata, array $stableMetadata): array
{
    $baseMetadata['name'] = $stableMetadata['title'];
    foreach ([
        'description', 'type', 'status', 'run_at', 'condition', 'tags', 'group',
        'priority', 'load_as_file', 'load_in_block_editor',
    ] as $key) {
        $baseMetadata[$key] = $stableMetadata[$key];
    }
    return $baseMetadata;
}

/** @param array<string,mixed> $metadata */
function vg_fs001_update_through_fluentsnippets(string $fileName, string $phpSource, array $metadata): void
{
    vg_runtime_mutation_assert_session(VG_FS001_TARGET);
    $result = \FluentSnippets\App\Helpers\Helper::updateSnippet([
        'meta' => $metadata,
        'code' => vg_fs001_helper_body($phpSource),
        'file_name' => $fileName,
        'reactivate' => false,
    ]);
    if (is_wp_error($result)) {
        throw new RuntimeException('FluentSnippets rejected the supported snippet update.');
    }
    if ($result !== $fileName) {
        throw new RuntimeException('FluentSnippets updated an unexpected snippet identity.');
    }
}

/** @param array<string,mixed> $expectedMetadata @param array<string,string> $expectedSiblings */
function vg_fs001_verify_updated_state(string $storageDirectory, string $source, array $expectedMetadata, array $expectedSiblings): void
{
    $state = vg_fs001_capture_state($storageDirectory);
    $record = $state['record'];
    if (($record['status'] ?? null) !== 'published'
        || ($record['code'] ?? null) !== $source
        || hash('sha256', (string) ($record['code'] ?? '')) !== VG_FS001_SOURCE_SHA256
        || ($state['stable_metadata'] ?? null) !== $expectedMetadata
        || ($state['siblings'] ?? null) !== $expectedSiblings) {
        throw new RuntimeException('FluentSnippets post-update semantic or sibling verification failed.');
    }
    vg_fs001_assert_published_index_entry($state['published_index_entry'] ?? null, $expectedMetadata);
    if (($state['draft_index_entry'] ?? null) !== null) {
        throw new RuntimeException('Updated snippet also appears in the FluentSnippets draft index.');
    }
}

function vg_fs001_legacy_rollback_source(string $previousCode): string
{
    if (! str_starts_with($previousCode, "\n<?php\n")) {
        throw new RuntimeException('Captured previous FluentSnippets code does not match the known leading-output baseline.');
    }

    // Helper::updateSnippet() owns the PHP opener and removes bytes before it. Recreate
    // the old one-LF output through PHP itself so rollback restores the prior observable
    // bootstrap behavior while keeping all six snippet branches unchanged.
    return "<?php\n?>\n\n" . substr($previousCode, 1);
}

/** @param array<string,mixed> $previousState @param array<string,mixed> $previousStableMetadata */
function vg_fs001_semantic_rollback(string $storageDirectory, array $previousState, array $previousStableMetadata): void
{
    $previousRecord = $previousState['record'];
    $rollbackSource = vg_fs001_legacy_rollback_source((string) ($previousRecord['code'] ?? ''));

    vg_fs001_update_through_fluentsnippets(
        VG_FS001_FILE_NAME,
        $rollbackSource,
        vg_fs001_write_metadata($previousRecord['meta'], $previousStableMetadata)
    );

    $restored = vg_fs001_capture_state($storageDirectory);
    if (($restored['record']['status'] ?? null) !== ($previousRecord['status'] ?? null)
        || ($restored['record']['code'] ?? null) !== $rollbackSource
        || ($restored['stable_metadata'] ?? null) !== $previousStableMetadata
        || ($restored['siblings'] ?? null) !== ($previousState['siblings'] ?? null)) {
        throw new RuntimeException('Semantic rollback did not restore the previous code, metadata, status, and sibling inventory.');
    }

    $inlineHtml = [];
    foreach (token_get_all($restored['record']['code']) as $token) {
        if (is_array($token) && $token[0] === T_INLINE_HTML) {
            $inlineHtml[] = $token[1];
        }
    }
    if ($inlineHtml !== ["\n"]) {
        throw new RuntimeException('Semantic rollback did not restore the prior one-LF bootstrap output.');
    }

    if (($previousRecord['status'] ?? null) === 'published') {
        vg_fs001_assert_published_index_entry($restored['published_index_entry'] ?? null, $previousStableMetadata);
        if (($restored['draft_index_entry'] ?? null) !== null) {
            throw new RuntimeException('Semantic rollback left the snippet in the FluentSnippets draft index.');
        }
    }
}

function vg_fs001_main(): void
{
    require_once dirname(__DIR__) . '/runtime-tools/runtime-mutation-session.php';
    vg_runtime_mutation_assert_session(VG_FS001_TARGET);

    if (! defined('ABSPATH')) {
        throw new RuntimeException('FS-001 requires a bootstrapped WordPress CLI process.');
    }
    if (! class_exists(\FluentSnippets\App\Helpers\Helper::class)
        || ! class_exists(\FluentSnippets\App\Model\Snippet::class)
        || ! function_exists('is_wp_error')
        || ! defined('FLUENT_SNIPPETS_PLUGIN_VERSION')) {
        throw new RuntimeException('The installed FluentSnippets runtime API is unavailable.');
    }

    $storageDirectory = \FluentSnippets\App\Helpers\Helper::getStorageDir();
    vg_fs001_assert_runtime_identity([
        'target' => (string) (getenv('VGNOVA_MUTATION_TARGET') ?: ''),
        'wp_root' => rtrim((string) (getenv('VGNOVA_MUTATION_WP_ROOT') ?: ''), '/'),
        'abspath' => rtrim((string) ABSPATH, '/'),
        'home_url' => home_url('/'),
        'site_url' => site_url('/'),
        'plugin_version' => (string) FLUENT_SNIPPETS_PLUGIN_VERSION,
        'storage_dir' => $storageDirectory,
    ]);
    if (realpath(VG_FS001_WP_ROOT) !== VG_FS001_WP_ROOT
        || realpath($storageDirectory) !== $storageDirectory) {
        throw new RuntimeException('FS-001 runtime paths are symlinked or do not resolve to the registered target.');
    }

    $approved = vg_fs001_load_approved_source();
    $before = vg_fs001_capture_state($storageDirectory);
    vg_fs001_assert_pre_change_baseline($before, $approved['source'], $approved['metadata']);

    try {
        vg_fs001_update_through_fluentsnippets(
            VG_FS001_FILE_NAME,
            $approved['source'],
            vg_fs001_write_metadata($before['record']['meta'], $approved['metadata'])
        );
        vg_fs001_verify_updated_state(
            $storageDirectory,
            $approved['source'],
            $approved['metadata'],
            $before['siblings']
        );
    } catch (Throwable $updateError) {
        try {
            vg_fs001_semantic_rollback($storageDirectory, $before, $before['stable_metadata']);
        } catch (Throwable $rollbackError) {
            throw new RuntimeException(
                'FluentSnippets update failed and semantic rollback could not be verified: ' . $rollbackError->getMessage(),
                0,
                $updateError
            );
        }
        throw new RuntimeException(
            'FluentSnippets update failed; previous semantic code and stable metadata were restored: ' . $updateError->getMessage(),
            0,
            $updateError
        );
    }

    fwrite(STDOUT, json_encode([
        'target' => VG_FS001_TARGET,
        'file_name' => VG_FS001_FILE_NAME,
        'status' => 'published',
        'pre_storage_sha256' => $before['storage_sha256'],
        'semantic_code_sha256' => VG_FS001_SOURCE_SHA256,
        'sibling_count' => count($before['siblings']),
        'index_verified' => true,
        'runtime_update' => 'complete',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . "\n");
}

if (! defined('VG_FLUENT_SNIPPET_UPDATER_TESTING')) {
    if (! defined('WP_CLI') || WP_CLI !== true) {
        throw new RuntimeException('Run this updater only through WP-CLI under the PE-007 coordinator.');
    }
    vg_fs001_main();
}
