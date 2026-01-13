# Convert Terms to Internal Link

Auto-link kategori utama dan tag ke dalam konten artikel WordPress secara otomatis, ringan, dan aman untuk SEO.

Function utama: `neon_autolink_terms`

---

## Fitur Utama

- Auto internal linking tanpa plugin berat
- SEO-friendly (1 link per term)
- Hanya berjalan di single post
- Hanya memproses paragraf `<p>`
- Skip paragraf yang sudah memiliki `<a>`
- Mengurutkan term dari karakter terpendek ke terpanjang (anti keyword overlap)
- Prefix `neon_` aman dari konflik theme / plugin lain

---

## Cara Pemasangan

### Opsi 1 — Melalui functions.php (Disarankan)

1. Buka file:

```

/wp-content/themes/your-theme/functions.php

````

2. Tambahkan kode berikut:

```php
add_filter('the_content', 'neon_autolink_terms');

/* isi function neon_autolink_terms di sini */
````

3. Simpan file

Selesai.

---

### Opsi 2 — Menggunakan Plugin Custom

1. Buat file baru:

```
/wp-content/plugins/neon-autolink-terms.php
```

2. Isi file dengan:

```php
<?php
/**
 * Plugin Name: Neon AutoLink Terms
 * Description: Auto-link kategori dan tag di dalam konten post.
 * Version: 1.0.0
 * Author: Neon
 */

add_filter('the_content', 'neon_autolink_terms');

/* isi function neon_autolink_terms di sini */
```

3. Aktifkan plugin dari WordPress Admin

Selesai.

---

## Cara Kerja Singkat

1. Ambil kategori utama (kategori pertama post)
2. Ambil seluruh tag post
3. Gabungkan kategori + tag menjadi satu list term
4. Urutkan term dari terpendek ke terpanjang
5. Untuk setiap term:

   * Cari paragraf `<p>` yang mengandung term
   * Skip paragraf yang sudah memiliki `<a>`
   * Auto-link hanya 1x per term

---

## Perilaku Penting

* Satu term hanya dilink satu kali
* Dalam satu paragraf bisa terdapat banyak link (selama beda term)
* Tidak memodifikasi heading, list, atau elemen lain
* Aman untuk konten panjang

---

## Artikel Tutorial Lengkap

Penjelasan teknis lengkap, alasan arsitektur kode, serta studi kasus SEO internal linking dapat dibaca di artikel berikut:

https://neon.web.id/membuat-auto-internal-link-dari-term-wordpress

Direkomendasikan membaca artikel tersebut sebelum melakukan modifikasi lanjutan.

---

## Tips SEO

* Gunakan tag yang benar-benar relevan
* Jangan terlalu banyak tag dalam satu post
* Biarkan keyword muncul natural di konten
* Gunakan kategori sebagai anchor utama

---

## Lisensi

MIT License
Bebas digunakan, dimodifikasi, dan dikembangkan.

---

