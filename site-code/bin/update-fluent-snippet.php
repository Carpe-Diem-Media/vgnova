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

/** @param array<string,mixed> $metadata @return array{load_as_file:bool,load_in_block_editor:bool} */
function vg_fs001_effective_load_settings(array $metadata): array
{
    // FluentSnippets 10.56 parseBlock() defaults both omitted fields to ''. CodeRunner
    // enables them only for 'yes', so an omitted key and an explicit empty value are
    // equivalent disabled settings for these two approved empty-valued fields.
    return [
        'load_as_file' => ($metadata['load_as_file'] ?? null) == 'yes',
        'load_in_block_editor' => ($metadata['load_in_block_editor'] ?? '') === 'yes',
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

/** @param array<string,mixed> $entries @param array<string,mixed> $expectedMetadata @return list<array{key:string,file_name:string,title:string,status:string}> */
function vg_fs001_conflicting_draft_entries(array $entries, array $expectedMetadata): array
{
    $conflicts = [];
    $expectedTitle = (string) ($expectedMetadata['title'] ?? '');

    foreach ($entries as $key => $entry) {
        if (! is_array($entry)) {
            throw new RuntimeException('FluentSnippets draft index contains a malformed entry.');
        }

        $fileName = (string) ($entry['file_name'] ?? '');
        $title = (string) ($entry['title'] ?? $entry['name'] ?? '');
        if ((string) $key === VG_FS001_FILE_NAME
            || $fileName === VG_FS001_FILE_NAME
            || ($expectedTitle !== '' && $title === $expectedTitle)) {
            $conflicts[] = [
                'key' => (string) $key,
                'file_name' => $fileName,
                'title' => $title,
                'status' => (string) ($entry['status'] ?? ''),
            ];
        }
    }

    return $conflicts;
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
    $storageContents = file_get_contents($snippetPath);
    if (! is_string($storageContents) || hash('sha256', $storageContents) !== $storageHash) {
        throw new RuntimeException('The FluentSnippets storage file changed while its state was being captured.');
    }
    $record = vg_fs001_read_record(VG_FS001_FILE_NAME);
    if (($record['file'] ?? null) !== $snippetPath) {
        throw new RuntimeException('FluentSnippets model resolved the snippet outside its registered storage path.');
    }
    $config = \FluentSnippets\App\Helpers\Helper::getIndexedConfig(false);
    if (! is_array($config)
        || ! is_array($config['published'] ?? null)
        || ! is_array($config['draft'] ?? null)) {
        throw new RuntimeException('The FluentSnippets derived index could not be read.');
    }
    $stableMetadata = vg_fs001_stable_metadata($record['meta'], VG_FS001_FILE_NAME);
    $indexHash = hash_file('sha256', $indexPath);
    if (! is_string($indexHash)) {
        throw new RuntimeException('The FluentSnippets derived index could not be hashed.');
    }

    return [
        'record' => $record,
        'stable_metadata' => $stableMetadata,
        'storage_sha256' => $storageHash,
        'storage_contents' => $storageContents,
        'index_sha256' => $indexHash,
        'storage_path' => $snippetPath,
        'siblings' => vg_fs001_sibling_inventory($storageDirectory),
        'published_index_entry' => $config['published'][VG_FS001_FILE_NAME] ?? null,
        'draft_index_entry' => $config['draft'][VG_FS001_FILE_NAME] ?? null,
        'conflicting_draft_entries' => vg_fs001_conflicting_draft_entries($config['draft'], $stableMetadata),
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

function vg_fs001_assert_no_output_source(string $source): void
{
    if (! str_starts_with($source, "<?php\n") || str_contains($source, "\r")) {
        throw new RuntimeException('Approved FluentSnippets source has an invalid PHP opening token or line ending.');
    }

    try {
        $tokens = token_get_all($source, TOKEN_PARSE);
    } catch (ParseError $error) {
        throw new RuntimeException('Approved FluentSnippets source does not parse.', 0, $error);
    }

    foreach ($tokens as $token) {
        if (! is_array($token)) {
            continue;
        }
        if (in_array($token[0], [T_INLINE_HTML, T_ECHO, T_PRINT, T_CLOSE_TAG], true)) {
            throw new RuntimeException('Approved FluentSnippets source contains direct output.');
        }
    }
}

function vg_fs001_assert_storage_wrapper(string $contents, string $parsedCode): void
{
    $startMarker = '// <Internal Doc Start>';
    $currentGuard = '<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>';
    $legacyGuard = "<?php if (!defined('ABSPATH')) {" . PHP_EOL . "    return;" . PHP_EOL . "} // <Internal Doc End> ?>";
    if (! str_starts_with($contents, "<?php\n")
        || substr_count($contents, $startMarker) !== 1
        || substr_count($contents, '// <Internal Doc End> ?>') !== 1) {
        throw new RuntimeException('FluentSnippets storage wrapper markers are missing or duplicated.');
    }

    $guardPosition = false;
    $matchedGuard = null;
    foreach ([$currentGuard, $legacyGuard] as $baseGuard) {
        $position = strpos($contents, $baseGuard);
        if (is_int($position)) {
            if ($matchedGuard !== null) {
                throw new RuntimeException('FluentSnippets storage wrapper contains multiple accepted guards.');
            }
            $newlineFollows = substr($contents, $position + strlen($baseGuard), strlen(PHP_EOL)) === PHP_EOL;
            $guardPosition = $position;
            $matchedGuard = $baseGuard . ($newlineFollows ? PHP_EOL : '');
        }
    }
    if (! is_int($guardPosition)
        || ! is_string($matchedGuard)
        || $guardPosition < strpos($contents, $startMarker)) {
        throw new RuntimeException('FluentSnippets storage wrapper does not use its registered ABSPATH guard.');
    }

    $metadataWrapper = substr($contents, 0, $guardPosition);
    try {
        $tokens = token_get_all($metadataWrapper, TOKEN_PARSE);
    } catch (ParseError $error) {
        throw new RuntimeException('FluentSnippets metadata wrapper does not parse.', 0, $error);
    }
    foreach ($tokens as $token) {
        if (! is_array($token)
            || ! in_array($token[0], [T_OPEN_TAG, T_COMMENT, T_DOC_COMMENT, T_WHITESPACE, T_CLOSE_TAG], true)) {
            throw new RuntimeException('FluentSnippets metadata wrapper contains unexpected executable code.');
        }
    }

    $storedCode = substr($contents, $guardPosition + strlen($matchedGuard));
    if ($storedCode !== $parsedCode) {
        throw new RuntimeException('FluentSnippets storage contains code outside the parsed executable body.');
    }
}

/** @param array<string,mixed> $state @param array{source:string,metadata:array<string,mixed>} $approved */
function vg_fs001_assert_current_integrity_state(array $state, array $approved): void
{
    $record = $state['record'] ?? null;
    $source = $approved['source'] ?? null;
    $expectedMetadata = $approved['metadata'] ?? null;
    if (! is_array($record)
        || ! is_array($record['meta'] ?? null)
        || ! is_string($record['code'] ?? null)
        || ! is_string($source)
        || ! is_array($expectedMetadata)) {
        throw new RuntimeException('FluentSnippets current-state evidence is incomplete.');
    }

    vg_fs001_assert_no_output_source($source);
    if (! is_string($state['storage_contents'] ?? null)) {
        throw new RuntimeException('FluentSnippets storage wrapper evidence is missing.');
    }
    vg_fs001_assert_storage_wrapper($state['storage_contents'], $record['code']);
    $actualMetadata = vg_fs001_stable_metadata($record['meta'], VG_FS001_FILE_NAME);
    if (($record['status'] ?? null) !== 'published'
        || $record['code'] !== $source
        || hash('sha256', $record['code']) !== VG_FS001_SOURCE_SHA256
        || $actualMetadata !== $expectedMetadata
        || ($state['stable_metadata'] ?? null) !== $actualMetadata
        || ($state['storage_path'] ?? null) !== (VG_FS001_WP_ROOT . '/wp-content/fluent-snippet-storage/' . VG_FS001_FILE_NAME)) {
        throw new RuntimeException('FluentSnippets current record differs from the approved source, metadata, status, or storage path.');
    }

    foreach (['storage_sha256', 'index_sha256'] as $hashKey) {
        if (! is_string($state[$hashKey] ?? null)
            || preg_match('/\A[a-f0-9]{64}\z/', $state[$hashKey]) !== 1) {
            throw new RuntimeException('FluentSnippets current-state hash is invalid: ' . $hashKey . '.');
        }
    }
    if (! is_array($state['siblings'] ?? null)
        || ! is_array($state['conflicting_draft_entries'] ?? null)
        || $state['conflicting_draft_entries'] !== []
        || ($state['draft_index_entry'] ?? null) !== null) {
        throw new RuntimeException('FluentSnippets current state contains an invalid sibling inventory or conflicting draft.');
    }
    foreach ($state['siblings'] as $fileName => $hash) {
        if (! is_string($fileName)
            || ! is_string($hash)
            || preg_match('/\A[a-f0-9]{64}\z/', $hash) !== 1) {
            throw new RuntimeException('FluentSnippets sibling inventory contains invalid evidence.');
        }
    }

    $publishedIndexEntry = $state['published_index_entry'] ?? null;
    vg_fs001_assert_published_index_entry($publishedIndexEntry, $expectedMetadata);
    $disabledLoadSettings = [
        'load_as_file' => false,
        'load_in_block_editor' => false,
    ];
    if (vg_fs001_effective_load_settings($record['meta']) !== $disabledLoadSettings
        || vg_fs001_effective_load_settings($publishedIndexEntry) !== $disabledLoadSettings) {
        throw new RuntimeException('FluentSnippets effective load settings differ from the approved disabled configuration.');
    }
}

/** @param array<string,mixed> $before @param array<string,mixed> $after */
function vg_fs001_assert_state_unchanged(array $before, array $after): void
{
    foreach ([
        'record', 'stable_metadata', 'storage_sha256', 'index_sha256', 'storage_path',
        'siblings', 'published_index_entry', 'draft_index_entry', 'conflicting_draft_entries',
        'runtime_identity', 'storage_realpath',
    ] as $key) {
        if (! array_key_exists($key, $before)
            || ! array_key_exists($key, $after)
            || $before[$key] !== $after[$key]) {
            throw new RuntimeException('FluentSnippets runtime state changed during QA: ' . $key . '.');
        }
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
