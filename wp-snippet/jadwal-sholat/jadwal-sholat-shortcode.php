<?php
/**
 * Shortcode: [jadwal_sholat city="1301" harian="true|false"]
 * Default: bulanan (harian = false)
 */

add_shortcode('jadwal_sholat', 'neon_jadwal_sholat_shortcode');
function neon_jadwal_sholat_shortcode($atts) {

    $atts = shortcode_atts([
        'city'   => '',
        'harian' => 'false',
    ], $atts, 'jadwal_sholat');

    if (empty($atts['city'])) {
        return '';
    }

    $cityId = sanitize_text_field($atts['city']);
    $harian = filter_var($atts['harian'], FILTER_VALIDATE_BOOLEAN);

    $year  = date('Y');
    $month = date('m');
    $today = date('d');

    // TRANSIENT KEY (BULANAN SAJA)
    $transient_key = "jadwal_sholat_monthly_{$cityId}_{$year}_{$month}";

    $monthly = get_transient($transient_key);

    if ($monthly === false) {

        $api = new JadwalSholatAPI();

        $monthly = $api->getMonthlySchedule($cityId, $year, $month);

        if (!$monthly || empty($monthly['jadwal'])) {
            return '';
        }

        set_transient(
            $transient_key,
            $monthly,
            DAY_IN_SECONDS
        );
    }

    // MODE HARIAN → ambil dari data bulanan
    if ($harian === true) {
        foreach ($monthly['jadwal'] as $day) {
            if ($day['tanggal'] === $today) {
                $data = $day;
                break;
            }
        }

        if (empty($data)) {
            return '';
        }

        ob_start();
        ?>
        <div class="jadwal-sholat harian">
            <ul>
                <li>Subuh: <?php echo esc_html($data['subuh']); ?></li>
                <li>Dzuhur: <?php echo esc_html($data['dzuhur']); ?></li>
                <li>Ashar: <?php echo esc_html($data['ashar']); ?></li>
                <li>Maghrib: <?php echo esc_html($data['maghrib']); ?></li>
                <li>Isya: <?php echo esc_html($data['isya']); ?></li>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }

    // MODE BULANAN (DEFAULT)
    ob_start();
    ?>
    <div class="jadwal-sholat bulanan">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Subuh</th>
                    <th>Dzuhur</th>
                    <th>Ashar</th>
                    <th>Maghrib</th>
                    <th>Isya</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($monthly['jadwal'] as $day): ?>
                <tr>
                    <td><?php echo esc_html($day['tanggal']); ?></td>
                    <td><?php echo esc_html($day['subuh']); ?></td>
                    <td><?php echo esc_html($day['dzuhur']); ?></td>
                    <td><?php echo esc_html($day['ashar']); ?></td>
                    <td><?php echo esc_html($day['maghrib']); ?></td>
                    <td><?php echo esc_html($day['isya']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php

    return ob_get_clean();
}
