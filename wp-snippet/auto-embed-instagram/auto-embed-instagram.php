<?php
/**
 * Auto embed Instagram
 * Single post only
 */

/* enqueue JS hanya di single */
add_action('wp_enqueue_scripts', function () {
    if (!is_single()) return;

    wp_enqueue_script(
        'instagram-embed',
        'https://www.instagram.com/embed.js',
        [],
        null,
        true
    );
});

/* transform konten */
add_filter('the_content', function ($content) {
    if (!is_single()) return $content;
    if (strpos($content, 'instagram.com') === false) return $content;

    return preg_replace(
        '#https?://(www\.)?instagram\.com/(p|reel|tv)/[A-Za-z0-9_-]+/?#i',
        '<blockquote class="instagram-media" data-instgrm-permalink="$0" data-instgrm-version="14"></blockquote>',
        $content
    );
}, 99);

/* trigger render */
add_action('wp_footer', function () {
    if (!is_single()) return;
    ?>
    <script>
    if (window.instgrm && window.instgrm.Embeds) {
        window.instgrm.Embeds.process();
    }
    </script>
    <?php
}, 99);
