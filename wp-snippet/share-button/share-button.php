<?php

/**
 * Shortcode: [neon_share_button]
 * Menampilkan semua tombol share (SVG icon + title)
 */

add_shortcode('neon_share_button', 'neon_share_button_shortcode');
function neon_share_button_shortcode() {
    if (!is_single()) return;
  
    $url   = urlencode(get_permalink());
    $title = urlencode(get_the_title());

    $shares = [
        'facebook' => [
            'title' => 'Share to Facebook',
            'link'  => 'https://www.facebook.com/sharer/sharer.php?u={url}',
            'icon'  => '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-3h2V9.5c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 3h-1.9v7A10 10 0 0 0 22 12z"/></svg>'
        ],
        'twitter' => [
            'title' => 'Share to X',
            'link'  => 'https://twitter.com/intent/tweet?url={url}&text={title}',
            'icon'  => '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M18.9 2H22l-7.2 8.2L23 22h-6.4l-5-6-5.2 6H2l7.7-8.8L1 2h6.5l4.5 5.4L18.9 2z"/></svg>'
        ],
        'whatsapp' => [
            'title' => 'Share to WhatsApp',
            'link'  => 'https://wa.me/?text={title}%20{url}',
            'icon'  => '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.5A10 10 0 1 0 12 2z"/></svg>'
        ],
        'telegram' => [
            'title' => 'Share to Telegram',
            'link'  => 'https://t.me/share/url?url={url}&text={title}',
            'icon'  => '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M22 2L2 11l5 2 2 6 3-4 5 4z"/></svg>'
        ],
    ];

    $output = '<div class="neon-share-wrapper">';

    foreach ($shares as $key => $data) {
        $link = str_replace(
            ['{url}', '{title}'],
            [$url, $title],
            $data['link']
        );

        $output .= '<a href="' . esc_url($link) . '"
            class="neon-share-btn neon-' . esc_attr($key) . '"
            title="' . esc_attr($data['title']) . '"
            aria-label="' . esc_attr($data['title']) . '"
            target="_blank" rel="noopener noreferrer">'
            . $data['icon'] .
        '</a>';
    }

    $output .= '</div>';

    return $output;
}
