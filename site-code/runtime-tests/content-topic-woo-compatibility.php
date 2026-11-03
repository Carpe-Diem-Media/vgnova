<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    fwrite(STDERR, "Run this QA file with WP-CLI eval-file inside WordPress.\n");
    exit(1);
}

const VG_CONTENT_TOPIC_WOO_QA_TAXONOMY = 'content_topic';
const VG_CONTENT_TOPIC_WOO_QA_REPORT_PREFIX = '/tmp/vgnova-content-topic-woo-qa-';

function vg_content_topic_woo_qa_check(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }
}

function vg_content_topic_woo_qa_write_report(string $path, array $report): void
{
    if (is_link($path)) {
        throw new RuntimeException('The QA report path must not be a symbolic link.');
    }

    $encoded = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . PHP_EOL;
    $temporary_path = $path . '.' . bin2hex(random_bytes(5)) . '.tmp';
    $written = file_put_contents($temporary_path, $encoded, LOCK_EX);
    if ($written === false || ! chmod($temporary_path, 0600) || ! rename($temporary_path, $path)) {
        if (is_file($temporary_path)) {
            unlink($temporary_path);
        }
        throw new RuntimeException('Could not persist the QA report.');
    }
}

function vg_content_topic_woo_qa_relationship_count(int $term_id): int
{
    global $wpdb;
    $value = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->term_relationships} r INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = r.term_taxonomy_id WHERE tt.taxonomy = %s AND tt.term_id = %d",
        VG_CONTENT_TOPIC_WOO_QA_TAXONOMY,
        $term_id
    ));
    if ($wpdb->last_error !== '' || $value === null) {
        throw new RuntimeException('Could not inspect existing topic relationships.');
    }
    return (int) $value;
}

function vg_content_topic_woo_qa_term_snapshot(): array
{
    $terms = get_terms(['taxonomy' => VG_CONTENT_TOPIC_WOO_QA_TAXONOMY, 'hide_empty' => false]);
    if (is_wp_error($terms)) {
        throw new RuntimeException('Could not read the canonical topic tree.');
    }
    $snapshot = [];
    foreach ($terms as $term) {
        $snapshot[(int) $term->term_id] = [
            'slug' => $term->slug,
            'parent' => (int) $term->parent,
            'name' => $term->name,
            'count' => (int) $term->count,
        ];
    }
    ksort($snapshot);
    return $snapshot;
}

function vg_content_topic_woo_qa_choose_empty_terms(): array
{
    $terms = get_terms(['taxonomy' => VG_CONTENT_TOPIC_WOO_QA_TAXONOMY, 'hide_empty' => false]);
    if (is_wp_error($terms)) {
        throw new RuntimeException('Could not read the canonical topic tree.');
    }
    $by_id = [];
    foreach ($terms as $term) {
        $by_id[(int) $term->term_id] = $term;
    }

    foreach ($terms as $root) {
        if ((int) $root->parent !== 0 || vg_content_topic_woo_qa_relationship_count((int) $root->term_id) !== 0) {
            continue;
        }
        $descendants = get_terms([
            'taxonomy' => VG_CONTENT_TOPIC_WOO_QA_TAXONOMY,
            'hide_empty' => false,
            'child_of' => (int) $root->term_id,
        ]);
        if (is_wp_error($descendants)) {
            throw new RuntimeException('Could not inspect descendants of a candidate root topic.');
        }
        $descendants_empty = true;
        foreach ($descendants as $descendant) {
            if (vg_content_topic_woo_qa_relationship_count((int) $descendant->term_id) !== 0) {
                $descendants_empty = false;
                break;
            }
        }
        if (! $descendants_empty) {
            continue;
        }
        foreach ($terms as $child) {
            if ((int) $child->parent !== (int) $root->term_id) {
                continue;
            }
            if (vg_content_topic_woo_qa_relationship_count((int) $child->term_id) === 0) {
                return ['root' => $root, 'child' => $child, 'by_id' => $by_id, 'descendants' => $descendants];
            }
        }
    }

    throw new RuntimeException('No existing root and child pair has zero assigned objects.');
}

function vg_content_topic_woo_qa_hook_snapshot(): array
{
    $snapshot = [];
    foreach ($GLOBALS['wp_filter'] as $name => $hook) {
        $snapshot[$name] = is_object($hook) ? clone $hook : $hook;
    }
    return $snapshot;
}

function vg_content_topic_woo_qa_restore_hooks(array $snapshot): void
{
    $GLOBALS['wp_filter'] = [];
    foreach ($snapshot as $name => $hook) {
        $GLOBALS['wp_filter'][$name] = is_object($hook) ? clone $hook : $hook;
    }
}

function vg_content_topic_woo_qa_main_query(array $extra_vars, array $baseline_hooks, bool $handle_404 = false): WP_Query
{
    vg_content_topic_woo_qa_restore_hooks($baseline_hooks);
    $previous_query = $GLOBALS['wp_query'] ?? null;
    $previous_the_query = $GLOBALS['wp_the_query'] ?? null;

    $query = new WP_Query();
    $GLOBALS['wp_query'] = $query;
    $GLOBALS['wp_the_query'] = $query;
    $query->query(array_merge([
        'taxonomy' => VG_CONTENT_TOPIC_WOO_QA_TAXONOMY,
        'term' => '',
        'posts_per_page' => 20,
    ], $extra_vars));

    if ($handle_404 && isset($GLOBALS['wp']) && $GLOBALS['wp'] instanceof WP) {
        $GLOBALS['wp']->handle_404($query);
    }

    $GLOBALS['vg_content_topic_woo_qa_query_context'][spl_object_id($query)] = [$previous_query, $previous_the_query];
    return $query;
}

function vg_content_topic_woo_qa_restore_query_globals(WP_Query $query): void
{
    $key = spl_object_id($query);
    if (! isset($GLOBALS['vg_content_topic_woo_qa_query_context'][$key])) {
        return;
    }
    [$previous_query, $previous_the_query] = $GLOBALS['vg_content_topic_woo_qa_query_context'][$key];
    $GLOBALS['wp_query'] = $previous_query;
    $GLOBALS['wp_the_query'] = $previous_the_query;
    unset($GLOBALS['vg_content_topic_woo_qa_query_context'][$key]);
}

function vg_content_topic_woo_qa_ids(WP_Query $query): array
{
    return array_map(static fn (WP_Post $post): int => (int) $post->ID, $query->posts);
}

function vg_content_topic_woo_qa_assert_topic_main_query(WP_Query $query, string $case): void
{
    vg_content_topic_woo_qa_check($query->is_main_query(), $case . ' did not execute as the main query.');
    $term = $query->get_queried_object();
    vg_content_topic_woo_qa_check($term instanceof WP_Term && $term->taxonomy === 'content_topic' && $term->slug === $query->get('term'), $case . ' lost its canonical queried taxonomy or term.');
    vg_content_topic_woo_qa_check(! str_contains(serialize($query->get('tax_query')), 'product_visibility'), $case . ' retained Product visibility clauses.');
    vg_content_topic_woo_qa_check(($query->query_vars['post_type'] ?? null) === 'post', $case . ' did not constrain the topic main query to Articles.');
    vg_content_topic_woo_qa_check(($query->query_vars['post_status'] ?? null) === 'publish', $case . ' changed the published status constraint.');
    vg_content_topic_woo_qa_check(
        ($query->query_vars['orderby'] ?? null) === ['date' => 'DESC', 'ID' => 'DESC'],
        $case . ' changed date and ID descending order.'
    );
    vg_content_topic_woo_qa_check(($query->query_vars['order'] ?? null) === 'DESC', $case . ' changed the descending archive order.');
    vg_content_topic_woo_qa_check(empty($query->query_vars['wc_query']) && empty($query->query_vars['product_query']), $case . ' became a WooCommerce product query.');
    vg_content_topic_woo_qa_check(empty($query->query_vars['meta_key']), $case . ' retained a WooCommerce Product meta key.');
    $sql = strtolower((string) $query->request);
    vg_content_topic_woo_qa_check(! str_contains($sql, 'wc_product_meta_lookup'), $case . ' joined the WooCommerce product lookup table.');
    vg_content_topic_woo_qa_check(! str_contains($sql, 'product_visibility'), $case . ' added WooCommerce product visibility taxonomy SQL.');
    vg_content_topic_woo_qa_check(str_contains($sql, 'post_type') && str_contains($sql, "'post'"), $case . ' SQL did not select Articles.');
}

function vg_content_topic_woo_qa_fixture_slug(string $token, string $key): string
{
    return 'vg-ctw-' . $token . '-' . sanitize_title($key);
}

function vg_content_topic_woo_qa_create_article(array &$manifest, string $key, string $title, string $slug): int
{
    $manifest['fixtures'][$key] = ['type' => 'post', 'slug' => $slug, 'id' => null];
    vg_content_topic_woo_qa_write_report($manifest['path'], $manifest);
    vg_runtime_mutation_assert_session('vgnova-local-10039');
    $id = wp_insert_post([
        'post_type' => 'post',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => 'Disposable content_topic WooCommerce compatibility fixture.',
        'post_author' => get_current_user_id(),
    ], true);
    if (is_wp_error($id) || (int) $id < 1) {
        throw new RuntimeException('Could not create the disposable Article fixture ' . $key . '.');
    }
    $manifest['fixtures'][$key]['id'] = (int) $id;
    vg_content_topic_woo_qa_write_report($manifest['path'], $manifest);
    return (int) $id;
}

function vg_content_topic_woo_qa_create_product(array &$manifest, string $key, string $slug, string $price): int
{
    $manifest['fixtures'][$key] = ['type' => 'product', 'slug' => $slug, 'id' => null];
    vg_content_topic_woo_qa_write_report($manifest['path'], $manifest);
    vg_content_topic_woo_qa_check(class_exists('WC_Product_Simple'), 'WooCommerce simple Products are unavailable.');
    vg_runtime_mutation_assert_session('vgnova-local-10039');
    $product = new WC_Product_Simple();
    $product->set_name('Disposable topic compatibility ' . $key);
    $product->set_slug($slug);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_virtual(true);
    $product->set_regular_price($price);
    $id = (int) $product->save();
    if ($id < 1 || get_post_type($id) !== 'product') {
        throw new RuntimeException('Could not create the disposable Product fixture ' . $key . '.');
    }
    $manifest['fixtures'][$key]['id'] = $id;
    vg_content_topic_woo_qa_write_report($manifest['path'], $manifest);
    return $id;
}

function vg_content_topic_woo_qa_assign_term(int $post_id, int $term_id): void
{
    vg_runtime_mutation_assert_session('vgnova-local-10039');
    $result = wp_set_object_terms($post_id, [$term_id], VG_CONTENT_TOPIC_WOO_QA_TAXONOMY, true);
    if (is_wp_error($result)) {
        throw new RuntimeException('Could not assign an existing topic term to a disposable fixture.');
    }
}

function vg_content_topic_woo_qa_change_status(int $post_id, string $status): void
{
    vg_runtime_mutation_assert_session('vgnova-local-10039');
    $updated = wp_update_post(['ID' => $post_id, 'post_status' => $status], true);
    if (is_wp_error($updated) || (int) $updated !== $post_id) {
        throw new RuntimeException('Could not change the status of a disposable Article fixture.');
    }
}

function vg_content_topic_woo_qa_topic_ids(string $slug, array $extra = [], bool $handle_404 = false): WP_Query
{
    $baseline = $GLOBALS['vg_content_topic_woo_qa_hooks'];
    return vg_content_topic_woo_qa_main_query(array_merge(['term' => $slug], $extra), $baseline, $handle_404);
}

function vg_content_topic_woo_qa_assert_ids(WP_Query $query, array $expected, string $message): void
{
    $actual = vg_content_topic_woo_qa_ids($query);
    sort($actual);
    sort($expected);
    vg_content_topic_woo_qa_check($actual === $expected, $message);
}

function vg_content_topic_woo_qa_cleanup(array &$manifest): array
{
    $failures = [];
    foreach (array_reverse($manifest['fixtures'] ?? [], true) as $key => $fixture) {
        $id = (int) ($fixture['id'] ?? 0);
        if ($id < 1) {
            continue;
        }
        try {
            vg_runtime_mutation_assert_session('vgnova-local-10039');
            $deleted = wp_delete_post($id, true);
            if (! $deleted && get_post($id) !== null) {
                $failures[] = 'Fixture ' . $key . ' remains after deletion.';
            }
            if (get_post($id) !== null) {
                $failures[] = 'Fixture ' . $key . ' remains in the database.';
            }
        } catch (Throwable $error) {
            $failures[] = 'Could not remove fixture ' . $key . ': ' . $error->getMessage();
        }
    }

    foreach ($manifest['fixture_reservations'] ?? [] as $key => $reservation) {
        if ((int) ($manifest['fixtures'][$key]['id'] ?? 0) > 0) {
            continue;
        }
        $slug = (string) ($reservation['slug'] ?? '');
        if ($slug === '') {
            continue;
        }
        $orphan = get_page_by_path($slug, OBJECT, [(string) $reservation['type']]);
        if (! $orphan instanceof WP_Post) {
            continue;
        }
        try {
            vg_runtime_mutation_assert_session('vgnova-local-10039');
            wp_delete_post((int) $orphan->ID, true);
            if (get_post((int) $orphan->ID) !== null) {
                $failures[] = 'Reserved fixture ' . $key . ' remains after orphan cleanup.';
            }
        } catch (Throwable $error) {
            $failures[] = 'Could not remove reserved fixture ' . $key . ': ' . $error->getMessage();
        }
    }

    foreach ($manifest['fixtures'] ?? [] as $key => $fixture) {
        $slug = (string) ($fixture['slug'] ?? '');
        if ($slug === '') {
            continue;
        }
        $found = get_posts([
            'post_type' => ['post', 'product'],
            'post_status' => ['publish', 'future', 'draft', 'pending', 'private', 'trash'],
            'name' => $slug,
            'numberposts' => -1,
            'fields' => 'ids',
            'suppress_filters' => true,
        ]);
        if ($found !== []) {
            $failures[] = 'Fixture slug ' . $key . ' still resolves after cleanup.';
        }
    }
    return array_values(array_unique($failures));
}

$run_token = bin2hex(random_bytes(8));
$report_path = VG_CONTENT_TOPIC_WOO_QA_REPORT_PREFIX . $run_token . '.json';
$manifest = [
    'schema_version' => 1,
    'task' => 'content-topic-woo-compatibility',
    'run_token' => $run_token,
    'path' => $report_path,
    'state' => 'preflight',
    'fixtures' => [],
    'checks' => [],
    'limitations' => ['HTTP requests and the public WooCommerce shop were not exercised by this WP-CLI run.'],
];
$original_user_id = get_current_user_id();
$original_wp_query = $GLOBALS['wp_query'] ?? null;
$original_wp_the_query = $GLOBALS['wp_the_query'] ?? null;
$baseline_hooks = vg_content_topic_woo_qa_hook_snapshot();
$GLOBALS['vg_content_topic_woo_qa_hooks'] = $baseline_hooks;
$term_snapshot_before = [];
$failure = null;
$cleanup_failures = [];

try {
    vg_content_topic_woo_qa_check(taxonomy_exists(VG_CONTENT_TOPIC_WOO_QA_TAXONOMY), 'Platform Core content_topic taxonomy is not registered.');
    vg_content_topic_woo_qa_check(class_exists('WooCommerce') && class_exists('WC_Product_Simple'), 'WooCommerce is not loaded.');
    $terms = vg_content_topic_woo_qa_choose_empty_terms();
    $root = $terms['root'];
    $child = $terms['child'];
    $term_snapshot_before = vg_content_topic_woo_qa_term_snapshot();
    $pagination_probe = vg_content_topic_woo_qa_topic_ids($root->slug, ['posts_per_page' => 2]);
    $pagination_page_size = (int) ($pagination_probe->query_vars['posts_per_page'] ?? 0);
    vg_content_topic_woo_qa_restore_query_globals($pagination_probe);
    vg_content_topic_woo_qa_check($pagination_page_size > 0 && $pagination_page_size <= 25, 'The effective topic page size is outside the QA fixture bound.');
    $root_article_count = max(5, $pagination_page_size + 1);
    $manifest['terms'] = [
        'root' => ['id' => (int) $root->term_id, 'slug' => $root->slug],
        'child' => ['id' => (int) $child->term_id, 'slug' => $child->slug],
        'empty_descendant_ids' => array_map(static fn (WP_Term $term): int => (int) $term->term_id, $terms['descendants']),
    ];
    $manifest['state'] = 'running';
    $manifest['fixture_reservations'] = [];
    $reservation_keys = ['lifecycle_article', 'child_article', 'root_product', 'child_product'];
    for ($index = 1; $index <= $root_article_count; $index++) {
        $reservation_keys[] = sprintf('root_article_%02d', $index);
    }
    foreach ($reservation_keys as $key) {
        $type = str_ends_with($key, 'product') ? 'product' : 'post';
        $manifest['fixture_reservations'][$key] = [
            'type' => $type,
            'slug' => vg_content_topic_woo_qa_fixture_slug($run_token, $key),
            'state' => 'reserved_before_create',
        ];
        vg_content_topic_woo_qa_check(
            get_page_by_path($manifest['fixture_reservations'][$key]['slug'], OBJECT, [$type]) === null,
            'A reserved fixture slug already exists. No runtime writes have started.'
        );
    }
    vg_content_topic_woo_qa_write_report($report_path, $manifest);

    foreach (['root' => $root, 'child' => $child] as $kind => $term) {
        vg_content_topic_woo_qa_check(vg_content_topic_woo_qa_relationship_count((int) $term->term_id) === 0, 'The selected ' . $kind . ' term gained relationships before its empty-query check.');
        if ($kind === 'root') {
            foreach ($terms['descendants'] as $descendant) {
                vg_content_topic_woo_qa_check(vg_content_topic_woo_qa_relationship_count((int) $descendant->term_id) === 0, 'A descendant of the selected root has an assigned object.');
            }
        }
        $empty = vg_content_topic_woo_qa_topic_ids($term->slug, [], true);
        vg_content_topic_woo_qa_check($empty->posts === [], 'The initially empty ' . $kind . ' topic query returned objects.');
        vg_content_topic_woo_qa_check($empty->is_tax(VG_CONTENT_TOPIC_WOO_QA_TAXONOMY), 'The empty ' . $kind . ' query lost its topic archive context.');
        vg_content_topic_woo_qa_check(! $empty->is_404, 'A valid empty ' . $kind . ' archive incorrectly resolved as a 404.');
        vg_content_topic_woo_qa_restore_query_globals($empty);
    }
    $manifest['checks'][] = 'empty_root_and_child';

    $child_product = vg_content_topic_woo_qa_create_product($manifest, 'child_product', $manifest['fixture_reservations']['child_product']['slug'], '89.00');
    vg_content_topic_woo_qa_assign_term($child_product, (int) $child->term_id);
    $child_product_only = vg_content_topic_woo_qa_topic_ids($child->slug);
    vg_content_topic_woo_qa_assert_topic_main_query($child_product_only, 'Product-only child archive');
    vg_content_topic_woo_qa_check($child_product_only->posts === [], 'A Product-only topic populated the Article main query.');
    vg_content_topic_woo_qa_restore_query_globals($child_product_only);
    $child_secondary = new WP_Query([
        'post_type' => 'product',
        'post_status' => 'publish',
        'tax_query' => [[
            'taxonomy' => VG_CONTENT_TOPIC_WOO_QA_TAXONOMY,
            'field' => 'term_id',
            'terms' => [(int) $child->term_id],
        ]],
        'posts_per_page' => 20,
    ]);
    vg_content_topic_woo_qa_assert_ids($child_secondary, [$child_product], 'The explicit secondary Product query did not return the Product-only fixture.');
    $manifest['checks'][] = 'product_only_and_explicit_secondary_product_query';

    $root_articles = [];
    for ($index = 1; $index <= $root_article_count; $index++) {
        $key = sprintf('root_article_%02d', $index);
        $id = vg_content_topic_woo_qa_create_article($manifest, $key, 'Disposable content topic Article ' . $index, $manifest['fixture_reservations'][$key]['slug']);
        vg_content_topic_woo_qa_assign_term($id, (int) $root->term_id);
        $root_articles[] = $id;
    }
    $root_article_only = vg_content_topic_woo_qa_topic_ids($root->slug);
    vg_content_topic_woo_qa_assert_topic_main_query($root_article_only, 'Article-only root archive');
    vg_content_topic_woo_qa_assert_ids($root_article_only, $root_articles, 'The Article-only root archive returned the wrong objects.');
    vg_content_topic_woo_qa_restore_query_globals($root_article_only);

    $root_product = vg_content_topic_woo_qa_create_product($manifest, 'root_product', $manifest['fixture_reservations']['root_product']['slug'], '119.00');
    vg_content_topic_woo_qa_assign_term($root_product, (int) $root->term_id);
    $mixed_root = vg_content_topic_woo_qa_topic_ids($root->slug);
    vg_content_topic_woo_qa_assert_topic_main_query($mixed_root, 'Mixed root archive');
    vg_content_topic_woo_qa_assert_ids($mixed_root, $root_articles, 'Products leaked into the mixed root Article main query.');
    vg_content_topic_woo_qa_restore_query_globals($mixed_root);

    $child_article = vg_content_topic_woo_qa_create_article($manifest, 'child_article', 'Disposable content topic child Article', $manifest['fixture_reservations']['child_article']['slug']);
    vg_content_topic_woo_qa_assign_term($child_article, (int) $child->term_id);
    $mixed_child = vg_content_topic_woo_qa_topic_ids($child->slug);
    vg_content_topic_woo_qa_assert_topic_main_query($mixed_child, 'Mixed child archive');
    vg_content_topic_woo_qa_assert_ids($mixed_child, [$child_article], 'The mixed child archive did not return only its Article.');
    vg_content_topic_woo_qa_restore_query_globals($mixed_child);
    $child_secondary_mixed = new WP_Query([
        'post_type' => 'product',
        'post_status' => 'publish',
        'tax_query' => [[
            'taxonomy' => VG_CONTENT_TOPIC_WOO_QA_TAXONOMY,
            'field' => 'term_id',
            'terms' => [(int) $child->term_id],
        ]],
        'posts_per_page' => 20,
    ]);
    vg_content_topic_woo_qa_assert_ids($child_secondary_mixed, [$child_product], 'The mixed child secondary Product query returned the wrong objects.');
    $manifest['checks'][] = 'article_only_and_mixed_archives';

    foreach ([
        'price' => ['orderby' => 'price', 'order' => 'asc', 'meta_key' => '_price'],
        'popularity' => ['orderby' => 'popularity', 'order' => 'desc'],
        'rating' => ['orderby' => 'rating', 'order' => 'desc'],
        'min_max_price' => ['min_price' => '50', 'max_price' => '150'],
        'rating_filter' => ['rating_filter' => '5'],
        'attribute_filter' => ['filter_colour' => 'navy', 'query_type_colour' => 'or'],
    ] as $case => $vars) {
        $previous_get = $_GET;
        $previous_request = $_REQUEST;
        $request_vars = $vars;
        unset($request_vars['meta_key']);
        $_GET = array_merge($_GET, $request_vars);
        $_REQUEST = array_merge($_REQUEST, $request_vars);
        try {
            $woo_request = vg_content_topic_woo_qa_topic_ids($root->slug, $vars);
        } finally {
            $_GET = $previous_get;
            $_REQUEST = $previous_request;
        }
        vg_content_topic_woo_qa_assert_topic_main_query($woo_request, 'WooCommerce ' . $case . ' topic request');
        vg_content_topic_woo_qa_assert_ids($woo_request, array_merge($root_articles, [$child_article]), 'WooCommerce ' . $case . ' parameters changed the Article topic results.');
        vg_content_topic_woo_qa_restore_query_globals($woo_request);
        $manifest['checks'][] = 'woo_parameter_' . $case;
    }

    $page_ids = [];
    $expected_page_count = (int) ceil((count($root_articles) + 1) / $pagination_page_size);
    for ($page_number = 1; $page_number <= $expected_page_count; $page_number++) {
        $remaining = count($root_articles) + 1 - (($page_number - 1) * $pagination_page_size);
        $expected_size = min($pagination_page_size, $remaining);
        $page = vg_content_topic_woo_qa_topic_ids($root->slug, ['posts_per_page' => $pagination_page_size, 'paged' => $page_number]);
        vg_content_topic_woo_qa_assert_topic_main_query($page, 'Paginated topic archive');
        vg_content_topic_woo_qa_check(count($page->posts) === $expected_size, 'Topic pagination returned the wrong page size.');
        vg_content_topic_woo_qa_check($page->max_num_pages === $expected_page_count, 'Topic pagination reported the wrong page count.');
        $page_ids = array_merge($page_ids, vg_content_topic_woo_qa_ids($page));
        vg_content_topic_woo_qa_restore_query_globals($page);
    }
    sort($page_ids);
    $expected_page_ids = array_merge($root_articles, [$child_article]);
    sort($expected_page_ids);
    vg_content_topic_woo_qa_check($page_ids === $expected_page_ids, 'Topic pagination skipped or repeated Articles.');
    $manifest['checks'][] = 'pagination';

    $feed = vg_content_topic_woo_qa_topic_ids($root->slug, ['feed' => 'rss2']);
    vg_content_topic_woo_qa_check($feed->is_feed(), 'The feed request did not retain feed context.');
    vg_content_topic_woo_qa_assert_topic_main_query($feed, 'Topic feed');
    $expected_feed_ids = array_merge($root_articles, [$child_article]);
    rsort($expected_feed_ids);
    $expected_feed_ids = array_slice($expected_feed_ids, 0, max(0, (int) ($feed->query_vars['posts_per_page'] ?? 0)));
    vg_content_topic_woo_qa_assert_ids($feed, $expected_feed_ids, 'The topic feed returned the wrong Articles.');
    vg_content_topic_woo_qa_restore_query_globals($feed);
    $manifest['checks'][] = 'feed';

    $unknown = vg_content_topic_woo_qa_topic_ids('vg-content-topic-unknown-' . $run_token, [], true);
    vg_content_topic_woo_qa_check($unknown->posts === [] && $unknown->is_404, 'An unknown topic did not resolve as a 404.');
    vg_content_topic_woo_qa_restore_query_globals($unknown);
    $manifest['checks'][] = 'unknown_topic_404';

    $lifecycle = vg_content_topic_woo_qa_create_article($manifest, 'lifecycle_article', 'Disposable content topic lifecycle Article', $manifest['fixture_reservations']['lifecycle_article']['slug']);
    vg_content_topic_woo_qa_assign_term($lifecycle, (int) $root->term_id);
    foreach (['draft', 'private'] as $status) {
        vg_content_topic_woo_qa_change_status($lifecycle, $status);
        $administrator_ids = get_users(['role' => 'administrator', 'number' => 1, 'fields' => 'ID']);
        vg_content_topic_woo_qa_check(isset($administrator_ids[0]) && (int) $administrator_ids[0] > 0, 'An existing administrator account is required for the user-context check.');
        foreach ([0, (int) $administrator_ids[0]] as $user_id) {
            wp_set_current_user((int) $user_id);
            $lifecycle_query = vg_content_topic_woo_qa_topic_ids($root->slug);
            vg_content_topic_woo_qa_check(! in_array($lifecycle, vg_content_topic_woo_qa_ids($lifecycle_query), true), 'A ' . $status . ' fixture appeared in a published topic archive.');
            vg_content_topic_woo_qa_restore_query_globals($lifecycle_query);
        }
    }
    vg_runtime_mutation_assert_session('vgnova-local-10039');
    vg_content_topic_woo_qa_check(wp_trash_post($lifecycle) instanceof WP_Post, 'Could not trash the disposable lifecycle Article.');
    $trashed_query = vg_content_topic_woo_qa_topic_ids($root->slug);
    vg_content_topic_woo_qa_check(! in_array($lifecycle, vg_content_topic_woo_qa_ids($trashed_query), true), 'A trashed fixture appeared in the topic archive.');
    vg_content_topic_woo_qa_restore_query_globals($trashed_query);
    vg_runtime_mutation_assert_session('vgnova-local-10039');
    vg_content_topic_woo_qa_check(wp_untrash_post($lifecycle) instanceof WP_Post && get_post_status($lifecycle) === 'draft', 'The lifecycle fixture did not return from trash as a draft.');
    vg_content_topic_woo_qa_change_status($lifecycle, 'publish');
    $republished = vg_content_topic_woo_qa_topic_ids($root->slug);
    vg_content_topic_woo_qa_check(in_array($lifecycle, vg_content_topic_woo_qa_ids($republished), true), 'The republished lifecycle fixture did not return to the topic archive.');
    vg_content_topic_woo_qa_restore_query_globals($republished);
    $manifest['checks'][] = 'draft_private_trash_republish_and_user_context';

    wp_set_current_user($original_user_id);
    vg_content_topic_woo_qa_restore_hooks($baseline_hooks);
    $previous_store_query = $GLOBALS['wp_query'] ?? null;
    $previous_store_the_query = $GLOBALS['wp_the_query'] ?? null;
    $store_query = new WP_Query();
    $GLOBALS['wp_query'] = $store_query;
    $GLOBALS['wp_the_query'] = $store_query;
    $store_query->query([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 20,
    ]);
    $GLOBALS['vg_content_topic_woo_qa_query_context'][spl_object_id($store_query)] = [$previous_store_query, $previous_store_the_query];
    vg_content_topic_woo_qa_check(($store_query->query_vars['post_type'] ?? null) === 'product', 'A native Product query no longer uses the Product post type.');
    vg_content_topic_woo_qa_check(($store_query->query_vars['wc_query'] ?? null) === 'product_query', 'The native WooCommerce shop query did not retain WooCommerce query mode.');
    vg_content_topic_woo_qa_check(in_array($root_product, vg_content_topic_woo_qa_ids($store_query), true), 'The native WooCommerce Product query did not return the visible QA Product.');
    vg_content_topic_woo_qa_restore_query_globals($store_query);
    $manifest['checks'][] = 'native_product_query';
    $manifest['state'] = 'checks_complete';
} catch (Throwable $error) {
    $failure = $error->getMessage();
} finally {
    wp_set_current_user($original_user_id);
    vg_content_topic_woo_qa_restore_hooks($baseline_hooks);
    $GLOBALS['wp_query'] = $original_wp_query;
    $GLOBALS['wp_the_query'] = $original_wp_the_query;
    $cleanup_failures = vg_content_topic_woo_qa_cleanup($manifest);
    $manifest['cleanup_failures'] = $cleanup_failures;
    $manifest['term_tree_restored'] = false;
    try {
        $term_snapshot_after = vg_content_topic_woo_qa_term_snapshot();
        $manifest['term_tree_restored'] = $term_snapshot_after === $term_snapshot_before;
        if (! $manifest['term_tree_restored']) {
            $cleanup_failures[] = 'The content_topic term tree or counts changed during cleanup.';
        }
        foreach ($manifest['terms']['empty_descendant_ids'] ?? [] as $term_id) {
            if (vg_content_topic_woo_qa_relationship_count((int) $term_id) !== 0) {
                $cleanup_failures[] = 'A descendant of the initially empty root retains relationships.';
            }
        }
        foreach (['root', 'child'] as $term_key) {
            if (isset($manifest['terms'][$term_key]) && vg_content_topic_woo_qa_relationship_count((int) $manifest['terms'][$term_key]['id']) !== 0) {
                $cleanup_failures[] = 'A selected initially empty content_topic term retains relationships.';
            }
        }
    } catch (Throwable $error) {
        $cleanup_failures[] = 'Could not verify topic cleanup: ' . $error->getMessage();
    }
    $manifest['cleanup_failures'] = array_values(array_unique($cleanup_failures));
    $manifest['failure'] = $failure;
    $manifest['result'] = $failure === null && $cleanup_failures === [] ? 'PASS' : 'FAIL';
    $manifest['state'] = $manifest['result'] === 'PASS' ? 'complete' : 'failed';
    unset($manifest['path']);
    try {
        vg_content_topic_woo_qa_write_report($report_path, $manifest);
    } catch (Throwable $report_error) {
        $manifest['result'] = 'FAIL';
        $manifest['failure'] = 'Could not persist the final QA report.';
    }
}

echo json_encode([
    'result' => $manifest['result'] ?? 'FAIL',
    'checks' => $manifest['checks'],
    'cleanup_failures' => $manifest['cleanup_failures'],
    'report' => $report_path,
    'failure' => $manifest['failure'],
    'http_coverage' => 'Not covered by this WP-CLI harness.',
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . PHP_EOL;
exit(($manifest['result'] ?? 'FAIL') === 'PASS' ? 0 : 1);
