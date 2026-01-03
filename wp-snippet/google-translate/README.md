# Google Translate

---

## Base Library

Library dasar untuk integrasi Google Translate.  
Digunakan sebagai fondasi untuk auto redirect dan shortcode.

Fungsi utama:
- Deteksi request Google Translate
- Deteksi bot / social media
- Mapping negara Cloudflare ke bahasa & label

Link:  
https://github.com/wichaksono/neon/blob/main/wp-snippet/google-translate/base.php

---

## Auto Redirect Google Translate

Hook untuk redirect otomatis ke Google Translate berdasarkan **negara pengunjung (Cloudflare)**.

Keterangan:
- Redirect otomatis non-ID visitor
- Skip bot, social media, dan Google Translate
- Menggunakan cookie agar tidak loop
- Tidak menambahkan UI atau setting

Tipe: **Hooks / Library**

Link:  
https://github.com/wichaksono/neon/blob/main/wp-snippet/google-translate/auto-redirect.php

---

## Button Redirect Google Translate

Shortcode untuk redirect manual (klik-only) ke Google Translate.

Keterangan:
- Bahasa & label tombol dinamis per negara
- Tampil hanya untuk non-ID visitor
- Aman untuk SEO (no forced redirect)
- Cocok dikombinasikan dengan auto redirect

Contoh:
```

[google_translate_button]

```

Tipe: **Shortcode**  

Link:  
https://github.com/wichaksono/neon/blob/main/wp-snippet/google-translate/shortcode.php
