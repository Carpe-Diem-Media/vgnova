<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/voelgoed-platform-core/includes/Runtime/RuntimeMutationSession.php';
vg_runtime_mutation_assert_session('vgnova-local-10039');
require_once __DIR__ . '/bricks-tree-writer.php';

final class VgBricksTreeWriterAjaxResponse extends RuntimeException
{
}

/** @return array<string,mixed> */
function vg_bricks_tree_writer_read_document(int $postId): array
{
    vg_runtime_mutation_assert_session('vgnova-local-10039');
    $post = get_post($postId);
    if (! $post instanceof WP_Post) {
        throw new VgBricksTreeWriterException("Bricks target {$postId} is missing.");
    }

    $values = get_post_meta($postId, BRICKS_DB_PAGE_CONTENT, false);
    if (! is_array($values) || count($values) !== 1 || ! is_array($values[0])) {
        throw new VgBricksTreeWriterException("Bricks target {$postId} does not have exactly one element tree.");
    }

    return [
        'post_id' => (int) $post->ID,
        'post_type' => (string) $post->post_type,
        'template_type' => $post->post_type === BRICKS_DB_TEMPLATE_SLUG
            ? (string) get_post_meta($postId, BRICKS_DB_TEMPLATE_TYPE, true)
            : null,
        'elements' => $values[0],
    ];
}

/**
 * Run Bricks' installed builder save handler and capture its JSON response without an HTTP request.
 *
 * @param array<string,mixed> $elements
 * @param array<string,mixed> $document
 */
function vg_bricks_tree_writer_save_document(int $postId, array $elements, array $document): void
{
    vg_runtime_mutation_assert_session('vgnova-local-10039');
    if (! defined('DOING_AJAX')) {
        define('DOING_AJAX', true);
    } elseif (DOING_AJAX !== true) {
        throw new VgBricksTreeWriterException('WP-CLI runtime already defines a non-AJAX request context.');
    }

    if (! class_exists(Bricks\Theme::class)
        || ! Bricks\Theme::$instance instanceof Bricks\Theme
        || ! Bricks\Theme::$instance->ajax instanceof Bricks\Ajax) {
        throw new VgBricksTreeWriterException('Installed Bricks builder save handler is unavailable.');
    }

    $previousPost = $_POST;
    $previousRequest = $_REQUEST;
    $previousUserId = get_current_user_id();
    $request = [
        'postId' => $postId,
        'nonce' => wp_create_nonce('bricks-nonce-builder'),
        // Bricks 2.4.5 decodes the slashed JSON, sanitizes it, then saves the Bricks content meta.
        'content' => wp_slash(wp_json_encode($elements)),
    ];
    if ($postId === 1733) {
        $request['templateType'] = $document['template_type'];
    }

    $_POST = $request;
    $_REQUEST = array_merge($previousRequest, $request);
    $filter = static function (): callable {
        return static function (mixed $message = '', mixed $title = '', mixed $arguments = []): never {
            throw new VgBricksTreeWriterAjaxResponse('Bricks builder save handler completed.');
        };
    };
    add_filter('wp_die_ajax_handler', $filter, PHP_INT_MAX);
    ob_start();

    try {
        if ($previousUserId <= 0
            || ! current_user_can('edit_post', $postId)
            || ! Bricks\Capabilities::current_user_can_use_builder($postId)) {
            throw new VgBricksTreeWriterException("Current WP-CLI user cannot save Bricks target {$postId}.");
        }

        do_action('wp_ajax_bricks_save_post');
    } catch (VgBricksTreeWriterAjaxResponse) {
        // The native handler prints its JSON result before calling wp_die().
    } finally {
        remove_filter('wp_die_ajax_handler', $filter, PHP_INT_MAX);
        $output = ob_get_clean();
        $_POST = $previousPost;
        $_REQUEST = $previousRequest;
    }

    if (! is_string($output)) {
        throw new VgBricksTreeWriterException("Bricks save response for target {$postId} was unavailable.");
    }

    $response = json_decode(trim($output), true);
    if (! is_array($response) || ($response['success'] ?? null) !== true) {
        throw new VgBricksTreeWriterException("Bricks native save handler rejected target {$postId}.");
    }
}

$result = [
    'ok' => false,
    'target' => 'vgnova-local-10039',
    'bricks_version' => defined('BRICKS_VERSION') ? BRICKS_VERSION : null,
    'completed' => [],
    'error' => null,
];
$stage = 'preflight';

try {
    if (! defined('WP_CLI') || WP_CLI !== true || ! defined('ABSPATH')) {
        throw new VgBricksTreeWriterException('Run this writer through WP-CLI eval-file inside WordPress.');
    }
    if (home_url('/') !== 'http://localhost:10039/' || site_url('/') !== 'http://localhost:10039/') {
        throw new VgBricksTreeWriterException('WordPress site identity does not match the approved LocalWP target.');
    }
    if (! defined('BRICKS_VERSION') || BRICKS_VERSION !== VgBricksTreeWriter::BRICKS_VERSION) {
        throw new VgBricksTreeWriterException('Installed Bricks version does not match the reviewed plan contract.');
    }
    if (! isset($args) || ! is_array($args) || count($args) !== 2
        || ! is_string($args[0] ?? null) || ! is_string($args[1] ?? null)) {
        throw new VgBricksTreeWriterException('Pass the private plan path and its reviewed SHA-256 as two WP-CLI positional arguments.');
    }

    $plan = VgBricksTreeWriter::loadPrivatePlan($args[0], $args[1]);
    VgBricksTreeWriter::validatePlan($plan);
    if (get_current_user_id() <= 0) {
        throw new VgBricksTreeWriterException('WP-CLI must run as an authorized Bricks editor user.');
    }

    foreach ([933, 1733] as $postId) {
        if (! current_user_can('edit_post', $postId)
            || ! Bricks\Capabilities::current_user_can_use_builder($postId)) {
            throw new VgBricksTreeWriterException("Current WP-CLI user cannot edit approved Bricks target {$postId}.");
        }
    }

    $stage = 'native_save';
    $records = VgBricksTreeWriter::execute(
        $plan,
        static function (int $postId): array {
            return vg_bricks_tree_writer_read_document($postId);
        },
        static function (int $postId, array $elements): array {
            $sanitized = Bricks\Helpers::security_check_elements_before_save(
                wp_slash($elements),
                $postId,
                'content'
            );
            return is_array($sanitized) ? wp_unslash($sanitized) : [];
        },
        static function (int $postId, array $elements, array $document): void {
            vg_bricks_tree_writer_save_document($postId, $elements, $document);
        },
        static function (array $record) use (&$result): void {
            $result['completed'][] = $record;
        }
    );
    $result['completed'] = $records;
    $result['ok'] = true;
    $stage = 'complete';
} catch (Throwable $error) {
    $result['error'] = [
        'stage' => $stage,
        'type' => $error instanceof VgBricksTreeWriterAjaxResponse ? 'unexpected_ajax_exit' : get_class($error),
        'message' => $error instanceof VgBricksTreeWriterException
            ? $error->getMessage()
            : 'Unexpected runtime failure. Stop and inspect the Bricks targets read-only.',
    ];
}

echo wp_json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
if ($result['ok'] !== true) {
    exit(1);
}
