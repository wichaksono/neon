<?php

class JadwalSholatAPI
{
    private string $baseUrl = 'https://api.myquran.com/v2/sholat';

    /**
     * Cari kota berdasarkan nama
     */
    public function searchCity(string $city): array|false
    {
        $city = urlencode($city);
        $url  = "{$this->baseUrl}/kota/cari/{$city}";

        return $this->request($url);
    }

    /**
     * Ambil semua kota
     */
    public function getAllCities(): array|false
    {
        $url = "{$this->baseUrl}/kota/semua";

        return $this->request($url);
    }

    /**
     * Ambil jadwal sholat bulanan
     * format: kota, tahun (YYYY), bulan (MM)
     */
    public function getMonthlySchedule(string $cityId, string $year, string $month): array|false
    {
        $url = "{$this->baseUrl}/jadwal/{$cityId}/{$year}/{$month}";

        return $this->request($url);
    }

    /**
     * INTERNAL REQUEST
     */
    private function request(string $url): array|false
    {
        $response = wp_remote_get($url, [
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        return $body['data'] ?? false;
    }
}
