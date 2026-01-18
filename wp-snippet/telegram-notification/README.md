# TelegramNotificationManager

Class sederhana untuk mengirim notifikasi ke Telegram menggunakan **Bot Telegram** di lingkungan **WordPress (wp_remote_post)**.

---

## 1. Instalasi

Simpan file class ini, misalnya:

```

/wp-content/mu-plugins/TelegramNotificationManager.php

````

atau include di plugin / theme kamu:

```php
require_once __DIR__ . '/TelegramNotificationManager.php';
````

---

## 2. Cara Pakai Class

### Inisialisasi

```php
$telegram = new TelegramNotificationManager(
    'BOT_TOKEN_KAMU',
    'CHAT_ID_KAMU'
);
```

### Kirim Pesan

```php
$telegram->send('<b>Notifikasi</b><br>Website aktif');
```

### Contoh dengan data user login

```php
$user = wp_get_current_user();

$message = "
<b>User Login</b>
Username: {$user->user_login}
Email: {$user->user_email}
IP: {$_SERVER['REMOTE_ADDR']}
";

$telegram->send($message);
```

> Default `parse_mode` adalah **HTML**

---

## 3. Cara Mendapatkan Bot Token

1. Buka Telegram
2. Cari **@BotFather**
3. Kirim perintah:

   ```
   /start
   ```
4. Buat bot baru:

   ```
   /newbot
   ```
5. Ikuti instruksi:

   * Isi nama bot
   * Isi username bot (harus berakhiran `bot`)
6. BotFather akan memberikan **Bot Token**, contoh:

   ```
   123456789:AAAbbbCCCdddEEEfff
   ```

---

## 4. Cara Mendapatkan Chat ID

### Private Chat

1. Buka browser:

   ```
   https://api.telegram.org/bot<BOT_TOKEN>/getUpdates
   ```
2. Kirim pesan apa saja ke bot kamu di Telegram
3. Refresh URL di atas
4. Cari:

   ```json
   "chat":{"id":123456789}
   ```
5. Angka tersebut adalah **Chat ID**

---

### Group / Channel

* Tambahkan bot ke group / channel
* Kirim pesan di group
* Buka `getUpdates`
* Gunakan `chat.id` (biasanya bernilai negatif)
