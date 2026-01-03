# Neon WP Snippets

Kumpulan WordPress snippet berbasis hook, filter, dan shortcode.  
Ditulis untuk penggunaan langsung via kode, tanpa UI tambahan di wp-admin.

---

## Google Translate

- **Base Library**  
  Library dasar untuk integrasi Google Translate.  
  Digunakan sebagai helper bersama (deteksi request, bot, dan mapping negara Cloudflare ke bahasa & label).  
  https://github.com/wichaksono/neon/blob/main/wp-snippet/google-translate/base.php

- **Auto Redirect Google Translate**  
  Hook untuk redirect otomatis ke Google Translate berdasarkan negara pengunjung (Cloudflare).  
  Skip bot, social media, dan Google Translate. Menggunakan cookie agar tidak loop.  
  https://github.com/wichaksono/neon/blob/main/wp-snippet/google-translate/auto-redirect.php

- **Button Redirect Google Translate**  
  Shortcode untuk redirect manual (klik-only) ke Google Translate.  
  Bahasa & label dinamis per negara, tampil hanya untuk non-ID visitor, aman untuk SEO.  
  Shortcode: `[google_translate_button]`  
  https://github.com/wichaksono/neon/blob/main/wp-snippet/google-translate/shortcode.php

---

## Author
Wakhid Wichaksono

[neon.web.id/wichaksono](https://neon.web.id/author/wichaksono/)

[github.com/wichaksono](https://github.com/wichaksono)

## Topics
wordpress, wordpress-snippets
