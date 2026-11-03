<?php
declare(strict_types=1);

define('ABSPATH', __DIR__ . '/');

/** Standalone hook doubles for the Content Topic and WooCommerce contract. */
$GLOBALS['ctw_hooks'] = [];
$GLOBALS['ctw_admin'] = false;
$GLOBALS['ctw_query_vars'] = [];
$GLOBALS['ctw_terms'] = [];
$GLOBALS['ctw_is_main'] = false;
$GLOBALS['ctw_taxonomy'] = null;
$GLOBALS['ctw_term'] = null;
$GLOBALS['ctw_404'] = false;
$GLOBALS['ctw_store_pages_only'] = 'no';

function add_action(string $hook, callable $callback, int $priority = 10, int $accepted_args = 1): void
{
    $GLOBALS['ctw_hooks'][$hook][$priority][] = [$callback, $accepted_args];
}

function add_filter(string $hook, callable $callback, int $priority = 10, int $accepted_args = 1): void
{
    add_action($hook, $callback, $priority, $accepted_args);
}

function remove_action(string $hook, callable $callback, int $priority = 10): bool
{
    foreach ($GLOBALS['ctw_hooks'][$hook][$priority] ?? [] as $index => [$registered]) {
        if ($registered === $callback) {
            unset($GLOBALS['ctw_hooks'][$hook][$priority][$index]);
            return true;
        }
    }
    return false;
}

function remove_filter(string $hook, callable $callback, int $priority = 10): bool
{
    return remove_action($hook, $callback, $priority);
}

function has_action(string $hook, callable $callback): int|false
{
    foreach ($GLOBALS['ctw_hooks'][$hook] ?? [] as $priority => $entries) {
        foreach ($entries as [$registered]) {
            if ($registered === $callback) return $priority;
        }
    }
    return false;
}

function did_action(string $hook): int
{
    return 0;
}

function is_admin(): bool
{
    return $GLOBALS['ctw_admin'];
}

function is_main_query(): bool
{
    return $GLOBALS['ctw_is_main'];
}

function is_404(): bool
{
    return $GLOBALS['ctw_404'];
}

function is_tax(string $taxonomy = '', string|int|array $term = ''): bool
{
    return $GLOBALS['ctw_taxonomy'] === $taxonomy;
}

function get_queried_object(): mixed
{
    return $GLOBALS['ctw_term'];
}

function get_taxonomy(string $taxonomy): object|false
{
    return $taxonomy === 'content_topic' ? (object) ['name' => 'content_topic'] : false;
}

function get_term(int $term_id, string $taxonomy = ''): mixed
{
    return $GLOBALS['ctw_terms'][$taxonomy][$term_id] ?? null;
}

function get_option(string $name, mixed $default = false): mixed
{
    return $name === 'woocommerce_store_pages_only' ? $GLOBALS['ctw_store_pages_only'] : $default;
}

function apply_filters(string $hook, mixed $value, mixed ...$args): mixed
{
    $entries = $GLOBALS['ctw_hooks'][$hook] ?? [];
    ksort($entries);
    foreach ($entries as $callbacks) {
        foreach ($callbacks as [$callback, $accepted_args]) {
            $value = $callback(...array_slice([$value, ...$args], 0, $accepted_args));
        }
    }
    return $value;
}

function ctw_assert(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }
}

function ctw_same(mixed $expected, mixed $actual, string $message): void
{
    ctw_assert($expected === $actual, $message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.');
}

final class WP_Query
{
    public array $query_vars;

    public function __construct(array $query_vars = [])
    {
        $this->query_vars = $query_vars;
    }

    public function is_main_query(): bool { return $GLOBALS['ctw_is_main']; }
    public function is_tax(string $taxonomy): bool { return $GLOBALS['ctw_taxonomy'] === $taxonomy; }
    public function is_404(): bool { return $GLOBALS['ctw_404']; }
    public function get_queried_object(): mixed { return $GLOBALS['ctw_term']; }

    public function get(string $key): mixed
    {
        return $this->query_vars[$key] ?? '';
    }

    public function set(string $key, mixed $value): void
    {
        $this->query_vars[$key] = $value;
    }
}

final class WP_Term
{
    public function __construct(public int $term_id, public string $taxonomy, public string $slug = '')
    {
    }
}

final class CtwWooQuery
{
    public array $calls = [];
    public function pre_get_posts(WP_Query $query): void
    {
        $this->calls[] = $query;
        $query->set('wc_query', 'product_query');
        $query->set('posts_per_page', 24);
    }
}

final class CtwWooContainer
{
    public function __construct(public CtwWooQuery $query)
    {
    }
}

$GLOBALS['ctw_woo'] = null;
function WC(): ?CtwWooContainer
{
    return $GLOBALS['ctw_woo'];
}

function ctw_run_hook(string $hook, mixed ...$args): void
{
    $entries = $GLOBALS['ctw_hooks'][$hook] ?? [];
    ksort($entries);
    foreach ($entries as $callbacks) {
        foreach ($callbacks as [$callback, $accepted_args]) {
            $callback(...array_slice($args, 0, $accepted_args));
        }
    }
}

function ctw_set_request(bool $main, bool $admin, ?string $taxonomy, mixed $term): void
{
    $GLOBALS['wp_query'] = new WP_Query();
    $GLOBALS['ctw_is_main'] = $main;
    $GLOBALS['ctw_admin'] = $admin;
    $GLOBALS['ctw_taxonomy'] = $taxonomy;
    $GLOBALS['ctw_term'] = $term;
    $GLOBALS['ctw_404'] = false;
}

function ctw_canonical_term(int $id = 23): object
{
    $term = new WP_Term($id, 'content_topic', 'geloof');
    $GLOBALS['ctw_terms']['content_topic'][$id] = $term;
    return $term;
}

$class_file = dirname(__DIR__) . '/includes/Taxonomy/ContentTopicWooCompatibility.php';
if (is_file($class_file)) {
    require_once $class_file;
}

$class_name = 'Voelgoed\\PlatformCore\\Taxonomy\\ContentTopicWooCompatibility';
$woo_query = new CtwWooQuery();
$GLOBALS['ctw_woo'] = new CtwWooContainer($woo_query);
$woo_callback = [$woo_query, 'pre_get_posts'];
$GLOBALS['ctw_hooks']['pre_get_posts'][17][] = [$woo_callback, 1];
if (class_exists($class_name)) {
    $adapter = new $class_name();
    $adapter->register();
    $adapter->register();
}
ctw_run_hook('wp_loaded');

$registered = $GLOBALS['ctw_hooks']['pre_get_posts'][17] ?? [];


ctw_set_request(false, false, 'content_topic', ctw_canonical_term());
$secondary = new WP_Query(['post_type' => ['product'], 'wc_query' => '', 'sentinel' => 'untouched']);
ctw_run_hook('pre_get_posts', $secondary);
ctw_same(1, count($woo_query->calls), 'A secondary query must still reach WooCommerce.');
ctw_same('product_query', $secondary->get('wc_query'), 'Secondary query behavior must remain WooCommerce-owned.');

ctw_set_request(true, false, 'category', new WP_Term(3, 'category'));
$noncanonical = new WP_Query(['post_type' => 'post']);
ctw_run_hook('pre_get_posts', $noncanonical);
ctw_same(2, count($woo_query->calls), 'Noncanonical main queries must reach WooCommerce.');

ctw_set_request(true, false, 'content_topic', new WP_Term(0, 'content_topic'));
$unknown = new WP_Query(['post_type' => 'post']);
ctw_run_hook('pre_get_posts', $unknown);
ctw_same(3, count($woo_query->calls), 'Unknown canonical-taxonomy terms must reach WooCommerce.');

ctw_set_request(true, true, 'content_topic', ctw_canonical_term());
$admin = new WP_Query(['post_type' => 'post']);
ctw_run_hook('pre_get_posts', $admin);
ctw_same(4, count($woo_query->calls), 'Admin queries must reach WooCommerce.');

ctw_set_request(true, false, 'content_topic', ctw_canonical_term());
$topic = new WP_Query([
    'post_type' => ['product', 'post'],
    'post_status' => ['private'],
    'orderby' => ['menu_order' => 'ASC'],
    'order' => 'ASC',
    'wc_query' => 'product_query',
    'posts_per_page' => 77,
    'meta_query' => ['relation' => 'AND', ['key' => 'keep', 'value' => 'me']],
]);
ctw_run_hook('pre_get_posts', $topic);
ctw_same(4, count($woo_query->calls), 'Canonical main topic archives must skip WooCommerce.');
ctw_same('post', $topic->get('post_type'), 'Canonical topic archives must query posts only.');
ctw_same('publish', $topic->get('post_status'), 'Canonical topic archives must query published posts.');
ctw_same(['date' => 'DESC', 'ID' => 'DESC'], $topic->get('orderby'), 'Topic archive ordering must be stable.');
ctw_same('DESC', $topic->get('order'), 'Topic archive order must be descending.');
ctw_same('', $topic->get('wc_query'), 'Woo query mode must be cleared.');
ctw_same(['relation' => 'AND', ['key' => 'keep', 'value' => 'me']], $topic->get('meta_query'), 'Unrelated existing query constraints must survive normalization.');

$GLOBALS['ctw_store_pages_only'] = 'yes';
ctw_set_request(true, false, 'category', new WP_Term(3, 'category'));
ctw_same(false, apply_filters('woocommerce_coming_soon_exclude', false, 99), 'WooCommerce visibility must not change for non-topic contexts.');
ctw_set_request(true, false, 'content_topic', ctw_canonical_term());
ctw_same(true, apply_filters('woocommerce_coming_soon_exclude', false, 99), 'Store-only Coming Soon must exclude canonical Topics.');
ctw_same(true, apply_filters('woocommerce_coming_soon_exclude', true, 99), 'Canonical main topic archives must preserve an already-visible product.');
ctw_set_request(false, false, 'content_topic', ctw_canonical_term());
ctw_same(false, apply_filters('woocommerce_coming_soon_exclude', false, 99), 'Secondary topic requests must preserve false visibility.');
ctw_set_request(true, true, 'content_topic', ctw_canonical_term());
ctw_same(false, apply_filters('woocommerce_coming_soon_exclude', false, 99), 'Admin topic requests must preserve false visibility.');
ctw_set_request(true, false, 'content_topic', ctw_canonical_term());
$GLOBALS['ctw_store_pages_only'] = 'no';
ctw_same(false, apply_filters('woocommerce_coming_soon_exclude', false, 99), 'Global store-pages-only mode must retain WooCommerce visibility restrictions.');
$GLOBALS['ctw_store_pages_only'] = 'yes';
$GLOBALS['ctw_404'] = true;
ctw_same(false, apply_filters('woocommerce_coming_soon_exclude', false, 99), '404 requests must preserve false visibility.');

ctw_assert(count($registered) === 1 && array_values($registered)[0][0] !== $woo_callback, 'Registration must replace the Woo callback once at its original priority.');

// Genuine store classifications retain both native query and visibility ownership.
foreach (['product_cat', 'product_tag', 'pa_colour', 'shop', 'product'] as $taxonomy) {
    ctw_set_request(true, false, $taxonomy, new WP_Term(45, $taxonomy));
    $store = new WP_Query(['post_type' => 'product']);
    $before = count($woo_query->calls);
    ctw_run_hook('pre_get_posts', $store);
    ctw_same($before + 1, count($woo_query->calls), 'Store query must reach Woo: ' . $taxonomy);
    ctw_same('product_query', $store->get('wc_query'), 'Store catalogue query must survive: ' . $taxonomy);
    ctw_same(false, apply_filters('woocommerce_coming_soon_exclude', false), 'Store protection must survive: ' . $taxonomy);
    ctw_same(true, apply_filters('woocommerce_coming_soon_exclude', true), 'Earlier exclusions remain owned by Woo/extensions.');
}
ctw_set_request(true, false, 'content_topic', new WP_Term(31, 'product_cat'));
ctw_same(false, apply_filters('woocommerce_coming_soon_exclude', false), 'A mismatched queried term must not bypass protection.');

$GLOBALS['ctw_woo'] = null;
try {
    (new $class_name())->register();
    ctw_run_hook('wp_loaded');
} catch (Throwable $error) {
    throw new RuntimeException('Registration must be safe when WooCommerce is absent.', 0, $error);
}

// The installed LMS also sorts shared-taxonomy archives at PHP_INT_MAX - 10.
// Canonical main-loop ownership must survive a late catalogue configuration.
ctw_set_request(true, false, 'content_topic', ctw_canonical_term());
add_action('pre_get_posts', static function (WP_Query $query): void {
    $query->set('orderby', 'meta_value_num');
    $query->set('meta_key', '_price');
}, PHP_INT_MAX - 10);
$late_catalogue = new WP_Query();
ctw_run_hook('pre_get_posts', $late_catalogue);
ctw_same(['date' => 'DESC', 'ID' => 'DESC'], $late_catalogue->get('orderby'), 'Late catalogue settings must not replace the editorial ordering.');
ctw_same('', $late_catalogue->get('meta_key'), 'Catalogue price metadata must not exclude editorial posts.');

// The installed Woo version uses priority 10; also exercise that normal registration.
$GLOBALS['ctw_hooks'] = [];
$GLOBALS['ctw_woo'] = new CtwWooContainer(new CtwWooQuery());
add_action('pre_get_posts', [$GLOBALS['ctw_woo']->query, 'pre_get_posts']);
(new $class_name())->register();
ctw_run_hook('wp_loaded');
ctw_set_request(true, false, 'content_topic', ctw_canonical_term());
$normal_priority = new WP_Query();
ctw_run_hook('pre_get_posts', $normal_priority);
ctw_same('', $normal_priority->get('wc_query'), 'Woo priority 10 must also be isolated.');
ctw_same('post', $normal_priority->get('post_type'), 'Woo priority 10 keeps editorial main query.');

echo "Content Topic WooCommerce compatibility tests passed.\n";
