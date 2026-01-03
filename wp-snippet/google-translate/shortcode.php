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

if (!function_exists('cf_country_translate_config')) {
    /**
     * Mengembalikan konfigurasi bahasa dan label tombol
     * berdasarkan kode negara dari Cloudflare.
     *
     * Digunakan untuk menentukan:
     * - Target bahasa Google Translate
     * - Teks label pada button translate
     *
     * WAJIB digunakan pada website yang berada di belakang Cloudflare
     * karena bergantung pada header `HTTP_CF_IPCOUNTRY`.
     *
     * @param string $country
     *     Kode negara ISO-2 (contoh: ID, JP, US) dari Cloudflare.
     *
     * @return array{
     *     lang: string,
     *     label: string
     * }
     *     Array konfigurasi translate:
     *     - lang  : kode bahasa Google Translate
     *     - label : teks label button
     */
    function cf_country_translate_config($country) {
        $map = [
            // Asia
            'JP' => ['lang' => 'ja',    'label' => '日本語で見る'],
            'CN' => ['lang' => 'zh-CN', 'label' => '查看中文'],
            'TW' => ['lang' => 'zh-TW', 'label' => '查看繁體中文'],
            'KR' => ['lang' => 'ko',    'label' => '한국어로 보기'],
            'ID' => ['lang' => 'id',    'label' => 'Lihat Bahasa Indonesia'],
            'TH' => ['lang' => 'th',    'label' => 'ดูภาษาไทย'],
            'VN' => ['lang' => 'vi',    'label' => 'Xem tiếng Việt'],
            'MY' => ['lang' => 'ms',    'label' => 'Lihat Bahasa Melayu'],
            'PH' => ['lang' => 'tl',    'label' => 'View in Filipino'],
            'IN' => ['lang' => 'hi',    'label' => 'हिंदी में देखें'],

            // Eropa
            'FR' => ['lang' => 'fr', 'label' => 'Voir en français'],
            'DE' => ['lang' => 'de', 'label' => 'Auf Deutsch ansehen'],
            'ES' => ['lang' => 'es', 'label' => 'Ver en español'],
            'IT' => ['lang' => 'it', 'label' => 'Visualizza in italiano'],
            'NL' => ['lang' => 'nl', 'label' => 'Bekijk in het Nederlands'],
            'PT' => ['lang' => 'pt', 'label' => 'Ver em português'],
            'RU' => ['lang' => 'ru', 'label' => 'Смотреть на русском'],
            'PL' => ['lang' => 'pl', 'label' => 'Zobacz po polsku'],
            'UA' => ['lang' => 'uk', 'label' => 'Переглянути українською'],

            // Timur Tengah
            'SA' => ['lang' => 'ar', 'label' => 'عرض باللغة العربية'],
            'AE' => ['lang' => 'ar', 'label' => 'عرض باللغة العربية'],
            'EG' => ['lang' => 'ar', 'label' => 'عرض باللغة العربية'],
            'QA' => ['lang' => 'ar', 'label' => 'عرض باللغة العربية'],

            // Amerika
            'US' => ['lang' => 'en', 'label' => 'View in English'],
            'GB' => ['lang' => 'en', 'label' => 'View in English'],
            'CA' => ['lang' => 'en', 'label' => 'View in English'],
            'MX' => ['lang' => 'es', 'label' => 'Ver en español'],
            'BR' => ['lang' => 'pt', 'label' => 'Ver em português'],

            // Lainnya
            'TR' => ['lang' => 'tr', 'label' => 'Türkçe görüntüle'],
        ];

        return $map[$country] ?? [
            'lang'  => 'en',
            'label' => 'View in English',
        ];
    }
}


if (!function_exists('gt_translate_button_shortcode')) {
    /**
     * Shortcode tombol Google Translate manual (klik-only).
     *
     * Tombol hanya tampil jika:
     * - Request bukan dari Google Translate
     * - Website berjalan di belakang Cloudflare
     * - Pengunjung bukan dari Indonesia (ID)
     *
     * @return string
     */
    function gt_translate_button_shortcode() {

        // Jangan tampilkan tombol jika request dari Google Translate
        if (function_exists('is_google_translate_request') && is_google_translate_request()) {
            return '';
        }

        // Ambil country dari Cloudflare
        $country = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'ID';

        // Jangan tampilkan tombol untuk pengunjung Indonesia
        if ($country === 'ID') {
            return '';
        }

        // Ambil konfigurasi bahasa & label berdasarkan country
        $config = function_exists('cf_country_translate_config')
            ? cf_country_translate_config($country)
            : ['lang' => 'en', 'label' => 'View in English'];

        // Tentukan skema URL
        $scheme = is_ssl() ? 'https://' : 'http://';

        // Parse URL saat ini
        $parsed = parse_url($scheme . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        // Format host untuk translate.goog
        $host = str_replace('.', '-', $parsed['host']);

        // Ambil path URL
        $path = $parsed['path'] ?? '/';

        // Build Google Translate URL (dinamis sesuai country)
        $gt_url =
            'https://' . $host . '.translate.goog' .
            $path .
            '?_x_tr_sl=auto' .
            '&_x_tr_tl=' . rawurlencode($config['lang']) .
            '&_x_tr_hl=id' .
            '&_x_tr_pto=wapp';

        // Output tombol
        return '<a href="' . esc_url($gt_url) . '" target="_blank" rel="noopener" class="gt-translate-button">'
            . esc_html($config['label']) .
        '</a>';
    }
}

add_shortcode('google_translate_button', 'gt_translate_button_shortcode');

