<?php

/**
 * Resolve the post ID Bricks query loops should use as context.
 */
function vg_bricks_query_context_post_id(): int
{
    if (class_exists('\Bricks\Database') && !empty(\Bricks\Database::$page_data['preview_or_post_id'])) {
        return (int) \Bricks\Database::$page_data['preview_or_post_id'];
    }

    if (is_singular()) {
        return (int) get_queried_object_id();
    }

    return (int) get_the_ID();
}

/**
 * @param int[] $ids
 */
function vg_bricks_query_post_in(array $ids): array
{
    $ids = array_values(array_filter(array_map('intval', $ids)));

    return $ids ?: [0];
}

/**
 * Posts whose ACF relationship/meta field contains $needle_id.
 *
 * @param string[] $post_types
 */
function vg_bricks_query_posts_with_relationship_field(string $field_key, int $needle_id, array $post_types): array
{
    global $wpdb;

    if ($needle_id < 1) {
        return [0];
    }

    $needle = (string) $needle_id;
    $like   = '%' . $wpdb->esc_like($needle) . '%';

    $type_placeholders = implode(',', array_fill(0, count($post_types), '%s'));
    $sql               = "
        SELECT DISTINCT p.ID
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
        WHERE p.post_type IN ($type_placeholders)
          AND p.post_status = 'publish'
          AND pm.meta_key = %s
          AND pm.meta_value LIKE %s
    ";

    $prepare = array_merge($post_types, [$field_key, $like]);
    $ids     = $wpdb->get_col($wpdb->prepare($sql, ...$prepare));

    return $ids ? array_map('intval', $ids) : [0];
}

add_filter('bricks/posts/query_vars', static function ($query_vars, $settings, $element_id, $element_name) {
    $type = $settings['query']['vg_query_type'] ?? '';

    if ($type === '') {
        return $query_vars;
    }

    $context_id = vg_bricks_query_context_post_id();

    switch ($type) {
        case 'event_medewerkers':
            $ids = [];
            if ($context_id && get_post_type($context_id) === 'tc_events') {
                $raw = get_field('event_medewerkers', $context_id);
                if (is_array($raw)) {
                    $ids = array_map('intval', $raw);
                }
            }
            $query_vars['post_type']   = 'medewerker';
            $query_vars['post__in']    = vg_bricks_query_post_in($ids);
            $query_vars['orderby']     = 'post__in';
            $query_vars['nopaging']    = true;
            $query_vars['no_found_rows'] = true;
            break;

        case 'event_products':
            if ($context_id && get_post_type($context_id) === 'tc_events') {
                $query_vars['post_type']  = 'product';
                $query_vars['meta_query'] = [
                    [
                        'key'     => 'produk_bron_event',
                        'value'   => (string) $context_id,
                        'compare' => '=',
                    ],
                ];
            } else {
                $query_vars['post__in'] = [0];
            }
            break;

        case 'medewerker_events':
            if ($context_id && get_post_type($context_id) === 'medewerker') {
                $query_vars['post_type'] = 'tc_events';
                $query_vars['post__in']  = vg_bricks_query_posts_with_relationship_field(
                    'event_medewerkers',
                    $context_id,
                    ['tc_events']
                );
                $query_vars['orderby'] = 'date';
                $query_vars['order']   = 'DESC';
            } else {
                $query_vars['post__in'] = [0];
            }
            break;

        case 'medewerker_products':
            if ($context_id && get_post_type($context_id) === 'medewerker') {
                $query_vars['post_type'] = 'product';
                $query_vars['post__in']  = vg_bricks_query_posts_with_relationship_field(
                    'produk_medewerkers',
                    $context_id,
                    ['product']
                );
                $query_vars['orderby'] = 'date';
                $query_vars['order']   = 'DESC';
            } else {
                $query_vars['post__in'] = [0];
            }
            break;

        case 'product_medewerkers':
            $ids = [];
            if ($context_id && get_post_type($context_id) === 'product') {
                $raw = get_field('produk_medewerkers', $context_id);
                if (is_array($raw)) {
                    $ids = array_map('intval', $raw);
                }
            }
            $query_vars['post_type'] = 'medewerker';
            $query_vars['post__in']  = vg_bricks_query_post_in($ids);
            $query_vars['orderby']   = 'post__in';
            $query_vars['nopaging']  = true;
            $query_vars['no_found_rows'] = true;
            break;

        case 'product_source_event':
            $ids = [];
            if ($context_id && get_post_type($context_id) === 'product') {
                $event_id = get_field('produk_bron_event', $context_id);
                if ($event_id) {
                    $ids = [(int) $event_id];
                }
            }
            $query_vars['post_type'] = 'tc_events';
            $query_vars['post__in']  = vg_bricks_query_post_in($ids);
            $query_vars['nopaging']  = true;
            $query_vars['no_found_rows'] = true;
            break;
    }

    return $query_vars;
}, 10, 4);
