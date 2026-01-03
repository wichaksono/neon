<?php
/**
 * PERSYARATAN WAJIB
 * ------------------------------------------------------------------
 * Kode ini WAJIB dijalankan pada website yang berada di belakang
 * Cloudflare.
 *
 * Mengandalkan header `HTTP_CF_IPCOUNTRY` dari Cloudflare
 * untuk mendeteksi negara pengunjung.
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

if (!function_exists('is_social_media_request')) {
    /**
     * Mengecek apakah request berasal dari bot atau aplikasi social media.
     *
     * @return bool
     */
    function is_social_media_request() {
        $agents = [
            'facebookexternalhit',
            'facebookcatalog',
            'fbav',
            'fb_iab',
            'twitterbot',
            'xbot',
            'pinterest',
            'linkedinbot',
            'whatsapp',
            'telegrambot',
            'slackbot',
            'discordbot',
            'skypeuripreview'
        ];

        $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        foreach ($agents as $agent) {
            if (strpos($ua, $agent) !== false) {
                return true;
            }
        }

        return false;
    }
}

/**
 * Redirect otomatis ke Google Translate berdasarkan lokasi pengunjung.
 *
 * @return void
 */
add_action('template_redirect', function () {

    // 1. Stop jika di area admin
    if (is_admin()) return;

    // 2. Stop jika bot / social media
    if (is_social_media_request()) return;

    // 3. Stop jika Google Translate
    if (is_google_translate_request()) return;

    // 4. Stop jika sudah di domain translate.goog
    if (strpos($_SERVER['HTTP_HOST'], 'translate.goog') !== false) return;

    // 5. Stop jika sudah pernah redirect
    if (!empty($_COOKIE['gt_redirected'])) return;

    // 6. Ambil country dari Cloudflare
    $country = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'ID';

    // 7. Stop jika pengunjung dari Indonesia
    if ($country === 'ID') return;

    // 8. Tentukan skema URL
    $scheme = is_ssl() ? 'https://' : 'http://';

    // 9. Parse URL saat ini
    $parsed = parse_url($scheme . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

    // 10. Format host untuk translate.goog
    $host = str_replace('.', '-', $parsed['host']);

    // 11. Ambil path URL
    $path = $parsed['path'] ?? '/';

    // 12. Build URL Google Translate
    $gt_url =
        'https://' . $host . '.translate.goog' .
        $path .
        '?_x_tr_sl=auto&_x_tr_tl=en&_x_tr_hl=id&_x_tr_pto=wapp';

    // 13. Set cookie penanda redirect
    setcookie('gt_redirected', '1', time() + 86400 * 30, '/', '', false, true);

    // 14. Redirect
    wp_redirect($gt_url, 302);
    exit;
});

