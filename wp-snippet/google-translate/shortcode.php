<?php
/**
 * PERSYARATAN WAJIB
 * ------------------------------------------------------------------
 * Shortcode ini WAJIB digunakan pada website yang berada di belakang
 * Cloudflare karena menggunakan header `HTTP_CF_IPCOUNTRY`.
 * ------------------------------------------------------------------
 */

if (!function_exists('is_google_translate_request')) {
    /**
     * Mengecek apakah request berasal dari Google Translate.
     *
     * @return bool
     */
    function is_google_translate_request() {
        return (
            isset($_SERVER['HTTP_VIA']) &&
            stripos($_SERVER['HTTP_VIA'], 'translate.google.com') !== false
        );
    }
}

if (!function_exists('gt_translate_button_shortcode')) {
    /**
     * Shortcode tombol Google Translate manual.
     *
     * Redirect ke Google Translate HANYA ketika visitor
     * mengklik tombol.
     *
     * @return string
     */
    function gt_translate_button_shortcode() {

        // Jangan tampilkan tombol jika request dari Google Translate
        if (is_google_translate_request()) {
            return '';
        }

        // Ambil country dari Cloudflare
        $country = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'ID';

        // Jangan tampilkan tombol untuk pengunjung Indonesia
        if ($country === 'ID') {
            return '';
        }

        // Tentukan skema URL
        $scheme = is_ssl() ? 'https://' : 'http://';

        // Parse URL saat ini
        $parsed = parse_url($scheme . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        // Format host untuk translate.goog
        $host = str_replace('.', '-', $parsed['host']);

        // Ambil path URL
        $path = $parsed['path'] ?? '/';

        // Build Google Translate URL
        $gt_url =
            'https://' . $host . '.translate.goog' .
            $path .
            '?_x_tr_sl=auto&_x_tr_tl=en&_x_tr_hl=id&_x_tr_pto=wapp';

        // Output tombol
        return '<a href="' . esc_url($gt_url) . '" target="_blank" rel="noopener" class="gt-translate-button">
            View in English
        </a>';
    }
}

add_shortcode('google_translate_button', 'gt_translate_button_shortcode');
