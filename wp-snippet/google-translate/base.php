<?php
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
