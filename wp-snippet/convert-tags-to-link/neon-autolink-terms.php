<?php
/**
 * Auto-link kategori utama dan tag ke dalam konten post.
 *
 * - Hanya berjalan di single post
 * - Setiap term (kategori / tag) hanya dilink 1 kali
 * - Link hanya dibuat di dalam <p>
 * - Skip paragraf yang sudah memiliki <a>
 * - Term diurutkan dari yang paling pendek ke paling panjang
 */
add_filter('the_content', 'neon_autolink_terms');

function neon_autolink_terms($content)
{
    // Jalankan hanya di halaman single post
    if (!is_single()) {
        return $content;
    }

    // Kumpulkan semua term (kategori utama + tag)
    $terms = [];

    // Ambil kategori utama (kategori pertama)
    $cats = get_the_category();
    if (!empty($cats)) {
        $terms[] = [
            'name' => $cats[0]->name,
            'link' => get_category_link($cats[0]->term_id),
        ];
    }

    // Ambil semua tag
    $tags = get_the_tags();
    if (!empty($tags)) {
        foreach ($tags as $tag) {
            $terms[] = [
                'name' => $tag->name,
                'link' => get_tag_link($tag->term_id),
            ];
        }
    }

    // Jika tidak ada term, kembalikan konten apa adanya
    if (empty($terms)) {
        return $content;
    }

    // Urutkan term dari terpendek ke terpanjang
    usort($terms, function ($a, $b) {
        return mb_strlen($a['name']) <=> mb_strlen($b['name']);
    });

    // Proses auto-link per term
    foreach ($terms as $term) {

        $term_regex = preg_quote($term['name'], '/');
        $term_link  = esc_url($term['link']);
        $done       = false;

        $content = preg_replace_callback(
            '/<p\b[^>]*>.*?<\/p>/is',
            function ($matches) use ($term_regex, $term_link, &$done) {

                if ($done) {
                    return $matches[0];
                }

                // Skip paragraf yang sudah mengandung link
                if (preg_match('/<a\s/i', $matches[0])) {
                    return $matches[0];
                }

                // Skip jika term tidak ada di paragraf
                if (!preg_match('/\b' . $term_regex . '\b/i', $matches[0])) {
                    return $matches[0];
                }

                $done = true;

                // Link kemunculan pertama term
                return preg_replace(
                    '/\b(' . $term_regex . ')\b/i',
                    '<a href="' . $term_link . '">$1</a>',
                    $matches[0],
                    1
                );
            },
            $content
        );
    }

    return $content;
}
