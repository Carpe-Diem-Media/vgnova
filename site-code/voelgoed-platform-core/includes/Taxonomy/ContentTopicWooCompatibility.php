<?php
declare(strict_types=1);

namespace Voelgoed\PlatformCore\Taxonomy;

defined('ABSPATH') || exit;

/** Keeps canonical editorial Topic requests outside Woo's catalogue query. */
final class ContentTopicWooCompatibility
{
    private bool $registered = false;
    private bool $woo_query_wrapped = false;

    public function register(): void
    {
        if ($this->registered) {
            return;
        }
        $this->registered = true;
        add_action('wp_loaded', [$this, 'wrap_woo_query']);
        add_action('pre_get_posts', [$this, 'prepare_topic_query'], 1);
        // Bricks and specialist plugins can apply archive settings later.
        add_action('pre_get_posts', [$this, 'prepare_topic_query'], PHP_INT_MAX);
        add_filter('woocommerce_coming_soon_exclude', [$this, 'exclude_topic_from_store_coming_soon']);
    }

    /**
     * Woo has no public pre-product-query exclusion filter. Forward its public
     * callback at its registered priority for every other query, before Woo can
     * install catalogue SQL callbacks. Do not undo private SQL implementation.
     */
    public function wrap_woo_query(): void
    {
        if ($this->woo_query_wrapped || ! function_exists('WC')) {
            return;
        }
        $woo = WC();
        if (! is_object($woo) || ! isset($woo->query)) {
            return;
        }
        $callback = [$woo->query, 'pre_get_posts'];
        if (! is_callable($callback)) {
            return;
        }
        $priority = has_action('pre_get_posts', $callback);
        if ($priority === false) {
            return;
        }
        remove_action('pre_get_posts', $callback, $priority);
        add_action('pre_get_posts', function (\WP_Query $query) use ($callback): void {
            if (! $this->is_topic_main_query($query)) {
                $callback($query);
            }
        }, $priority);
        $this->woo_query_wrapped = true;
    }

    /** Explicit native Bricks section queries retain their own object types. */
    public function prepare_topic_query(\WP_Query $query): void
    {
        if (! $this->is_topic_main_query($query)) {
            return;
        }
        $query->set('post_type', 'post');
        $query->set('post_status', 'publish');
        $query->set('orderby', ['date' => 'DESC', 'ID' => 'DESC']);
        $query->set('order', 'DESC');
        $query->set('wc_query', '');
        $query->set('meta_key', '');
        $query->set('posts_per_page', $query->query['posts_per_page'] ?? get_option('posts_per_page'));
    }

    /** Exempt editorial discovery only in store-only mode, never site-wide mode. */
    public function exclude_topic_from_store_coming_soon(bool $excluded): bool
    {
        global $wp_query;

        return $excluded || (
            get_option('woocommerce_store_pages_only') === 'yes'
            && $wp_query instanceof \WP_Query
            && $this->is_topic_main_query($wp_query)
        );
    }

    private function is_topic_main_query(\WP_Query $query): bool
    {
        global $wp;

        if (is_admin() || ! $query->is_main_query() || ! $query->is_tax('content_topic') || $query->is_404()) {
            return false;
        }
        // A Topic query string can make the Shop query look like a taxonomy
        // archive. Only WordPress's matched canonical rewrite owns this route.
        $route = [];
        parse_str((string) ($wp->matched_query ?? ''), $route);
        if (! isset($route['content_topic'])) {
            return false;
        }
        $term = $query->get_queried_object();

        return $term instanceof \WP_Term
            && $term->taxonomy === 'content_topic'
            && $term->term_id > 0;
    }
}
