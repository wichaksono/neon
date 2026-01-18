<?php

add_filter('get_the_date', function ($the_date, $post) {
    $post_time = get_the_time('U', $post);
    $now = current_time('timestamp');

    if (($now - $post_time) <= 3 * DAY_IN_SECONDS) {
        return time_ago_custom($post_time, $now);
    }

    return date_i18n(get_option('date_format'), $post_time);
}, 10, 2);

function time_ago_custom($from, $to = null, $locale = 'id'): string
{
    if (!$to) {
        $to = current_time('timestamp');
    }

    $units = [
        'minute' => MINUTE_IN_SECONDS,
        'hour'   => HOUR_IN_SECONDS,
        'day'    => DAY_IN_SECONDS,
    ];

    $labels = [
        'id' => [
            'minute' => 'menit',
            'hour'   => 'jam',
            'day'    => 'hari',
            'suffix' => 'lalu',
        ],
        'en' => [
            'minute' => 'minute',
            'hour'   => 'hour',
            'day'    => 'day',
            'suffix' => 'ago',
        ],
    ];

    $seconds = $to - $from;

    foreach ($units as $key => $value) {
        if ($seconds < $value * 60) {
            $count = floor($seconds / $value);
            return max(1, $count) . ' ' . $labels[$locale][$key] . ' ' . $labels[$locale]['suffix'];
        }
    }

    $count = floor($seconds / DAY_IN_SECONDS);
    return $count . ' ' . $labels[$locale]['day'] . ' ' . $labels[$locale]['suffix'];
}
