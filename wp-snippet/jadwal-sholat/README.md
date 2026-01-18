## JADWAL SHOLAT

### HOW TO USE
```php
// inisialisasi
$sholat = new JadwalSholatAPI();

/**
 * 1. Cari kota
 */
$result = $sholat->searchCity('jakarta');

foreach ($result as $city) {
    echo $city['id'] . ' - ' . $city['lokasi'] . '<br>';
}

/**
 * 2. Ambil semua kota
 */
$cities = $sholat->getAllCities();

/**
 * 3. Ambil jadwal sholat bulanan
 * contoh: Jakarta (1301), Januari 2026
 */
$jadwal = $sholat->getMonthlySchedule('1301', '2026', '01');

foreach ($jadwal['jadwal'] as $hari) {
    echo $hari['tanggal'] . ' - ' . $hari['subuh'] . ' - ' . $hari['isya'] . '<br>';
}
```
