<?php

add_shortcode('read_also', 'neon_read_also');

function neon_read_also($atts = []): string
{
    global $post;

    if ( ! is_single() ) {
        return '';
    }

    $atts = shortcode_atts([
        'label' => 'Read Also',
        'ids'   => '',
    ], $atts);

    // cache harus mempertimbangkan attribute (ids/tags)
    $cache_key = 'read_also_' . $post->ID;
    $lists     = get_transient($cache_key);

    if (empty($lists)) {

        $lists = [];

        // =========================
        // MODE MANUAL (ids="…")
        // =========================
        if ( ! empty($atts['ids'])) {

            $ids = array_filter(array_map('intval', explode(',', $atts['ids'])));

            foreach ($ids as $id) {
                $lists[] = [
                    'id'    => $id,
                    'link'  => get_permalink($id),
                    'title' => get_the_title($id),
                ];
            }
        }

        // =========================
        // MODE OTOMATIS (prev/next)
        // =========================
        else {

            $current_id = $post->ID;
            $cats = wp_get_post_categories($current_id, [
                'fields' => 'all',
            ]);

            if (empty($cats)) {
                return '';
            }

            $cat_id     = $cats[0]->term_id;
            $post_count = $cats[0]->count;

            $prev_post = get_adjacent_post(true);
            $prev_id   = $prev_post ? $prev_post->ID : 0;

            // NEXT POST ID
            $next_post = get_adjacent_post(true, '', false);
            $next_id   = $next_post ? $next_post->ID : 0;

            if ($prev_id === 0 && $next_id === 0) {
                set_transient($cache_key, [], HOUR_IN_SECONDS);
                return '';
            }

            if ($post_count > 3 ) {
                if ($prev_id === 0) {
                    // get first posts
                    $last_posts = get_posts([
                        'category'     => $cat_id,
                        'numberposts'  => 1,
                        'orderby'      => 'ID',
                        'order'        => 'DESC',
                        'post__not_in' => [$current_id],
                        'post_status'  => 'publish',
                    ]);

                    $prev_post = ! empty($last_posts) ? $last_posts[0] : null;
                    $prev_id   = $prev_post ? $prev_post->ID : 0;
                }

                if ($next_id === 0) {

                    // get first posts
                    $first_post = get_posts([
                        'category'     => $cat_id,
                        'numberposts'  => 1,
                        'orderby'      => 'ID',
                        'order'        => 'ASC',
                        'post__not_in' => [$current_id],
                        'post_status'  => 'publish',
                    ]);

                    $next_post = ! empty($first_post) ? $first_post[0] : null;
                    $next_id   = $next_post ? $next_post->ID : 0;
                }
            }

            if ( ! empty($prev_id)) {
                $lists[] = [
                    'id'    => $prev_id,
                    'link'  => get_permalink($prev_id),
                    'title' => $prev_post->post_title,
                ];
            }

            if ( ! empty($next_id)) {
                $lists[] = [
                    'id'    => $next_id,
                    'link'  => get_permalink($next_id),
                    'title' => $next_post->post_title,
                ];
            }
        }

        set_transient($cache_key, $lists, DAY_IN_SECONDS);
    }

    if (empty($lists)) {
        return '';
    }

    // =========================
    // OUTPUT
    // =========================
    $output = '<div class="neon-read-also-box"><span>' . esc_html($atts['label']) . '</span><ul>';

    foreach ($lists as $item) {
        $output    .= '<li><a href="' . $item['link'] . '">' . esc_html($item['title']) . '</a></li>';
    }

    $output .= '</ul></div>';

    return $output;
}

add_action('save_post', function ($post_id) {
    delete_transient('read_also_' . $post_id);
});
