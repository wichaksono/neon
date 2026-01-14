<?php
/**
 * Automatically adds internal links for the current post's category and tags.
 *
 * Rules:
 * - Works only on single post pages.
 * - Uses the primary (first) category and all post tags.
 * - Each term is linked only once across the entire content.
 * - Maximum of 2 automatic links per paragraph.
 * - Does not modify or nest existing <a> tags.
 * - Case-insensitive and word-boundary safe.
 *
 * @param string $content The post content.
 * @return string Modified content with automatic internal links.
 */
add_filter('the_content', 'neon_autolink_terms');

function neon_autolink_terms($content)
{
    if (!is_single()) {
        return $content;
    }

    $terms = [];

    // Primary category (first)
    $cats = get_the_category();
    if (!empty($cats)) {
        $terms[] = [
            'name' => $cats[0]->name,
            'link' => get_category_link($cats[0]->term_id),
        ];
    }

    // Tags
    $tags = get_the_tags();
    if (!empty($tags)) {
        foreach ($tags as $tag) {
            $terms[] = [
                'name' => $tag->name,
                'link' => get_tag_link($tag->term_id),
            ];
        }
    }

    if (empty($terms)) {
        return $content;
    }

    // Sort by term length (short → long)
    usort($terms, function ($a, $b) {
        return mb_strlen($a['name']) <=> mb_strlen($b['name']);
    });

    $linked_terms = [];

    $content = preg_replace_callback(
        '/<p\b[^>]*>.*?<\/p>/is',
        function ($matches) use ($terms, &$linked_terms) {

            $paragraph = $matches[0];
            $links_in_paragraph = 0;

            foreach ($terms as $term) {

                if ($links_in_paragraph >= 2) {
                    break;
                }

                if (isset($linked_terms[$term['name']])) {
                    continue;
                }

                $regex = '/\b(' . preg_quote($term['name'], '/') . ')\b(?![^<]*<\/a>)/i';

                if (!preg_match($regex, $paragraph)) {
                    continue;
                }

                $paragraph = preg_replace(
                    $regex,
                    '<a href="' . esc_url($term['link']) . '">$1</a>',
                    $paragraph,
                    1
                );

                $linked_terms[$term['name']] = true;
                $links_in_paragraph++;
            }

            return $paragraph;
        },
        $content
    );

    return $content;
}
