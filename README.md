# CeritaKita

**CeritaKita** adalah platform aplikasi web sederhana yang memungkinkan pengguna untuk berbagi dan membaca cerita. Aplikasi ini dibangun menggunakan PHP native dan database MySQL, dengan fokus pada operasi CRUD (Create, Read, Update, Delete) untuk cerita, serta fungsionalitas autentikasi pengguna.

![Screenshot Aplikasi CeritaKita](img/ss.jpg)

---

## Fitur yang Tersedia

* **Autentikasi Pengguna**:
    * Registrasi pengguna baru (minimal 6 karakter password).
    * Login pengguna.
    * Logout.
    * Proteksi halaman (redirect ke login jika belum autentikasi).
* **Manajemen Cerita (CRUD)**:
    * **Create**: Pengguna yang sudah login dapat mempublikasikan cerita baru, termasuk judul, penulis, isi cerita, dan mengunggah gambar sampul.
    * **Read**:
        * Menampilkan daftar semua cerita di halaman "Kumpulan Cerita" dengan *lazy loading* / *pagination*.
        * Melihat detail cerita di halaman terpisah.
        * Menampilkan 2 cerita terbaru di halaman beranda.
    * **Update**: Pengguna dapat mengedit cerita yang mereka miliki melalui dashboard.
    * **Delete**: Pengguna dapat menghapus cerita yang mereka miliki melalui dashboard.
* **Dashboard Pengguna**:
    * Menampilkan daftar semua cerita yang telah ditulis oleh pengguna yang sedang login.
    * Menyediakan tautan untuk menambah, mengedit, atau menghapus cerita.
* **Fitur Tambahan**:
    * **Pencarian**: Mencari cerita berdasarkan judul atau penulis di halaman "Kumpulan Cerita".
    * **Pagination**: Navigasi halaman untuk menelusuri banyak cerita.
    * **Upload Gambar**:
        * Validasi sisi klien (client-side) untuk ukuran file (maks 2MB).
        * Validasi sisi server (server-side) untuk tipe MIME (JPG, PNG, GIF) dan ukuran file.
        * Gambar di-reproses di server untuk keamanan.
        * Menggunakan gambar *placeholder* jika tidak ada gambar yang diunggah.
    * **Dark Mode**: *Toggle* untuk mengubah tema tampilan antara mode terang dan gelap.
    * **Notifikasi Modal**: Pesan *flash* (sukses atau error) ditampilkan dalam bentuk modal pop-up.
    * **Modal Konfirmasi**: Konfirmasi muncul sebelum menghapus cerita.

---

## Kebutuhan Sistem

* **Server**: Apache / Nginx
* **PHP**: Versi 7.4 atau lebih baru (disarankan 8.x)
    * Ekstensi PHP: `mysqli`, `gd` (untuk pemrosesan gambar), `fileinfo` (untuk validasi MIME)
* **Database**: MySQL 5.7 atau lebih baru / MariaDB
* **Browser**: Browser modern (Chrome, Firefox, Safari, Edge)

---

## Cara Instalasi dan Konfigurasi

Database SQL untuk tabel `users` dan `cerita` tidak disertakan di repositori ini. Anda perlu membuatnya secara manual.

**Struktur Tabel yang Dibutuhkan:**

1.  **`users`**:
    * `id` (INT, Primary Key, Auto Increment)
    * `username` (VARCHAR, Unique)
    * `password` (VARCHAR - untuk menyimpan *hash*)
2.  **`cerita`**:
    * `id` (INT, Primary Key, Auto Increment)
    * `user_id` (INT, Foreign Key ke `users.id`)
    * `judul` (VARCHAR)
    * `penulis` (VARCHAR)
    * `isi` (TEXT)
    * `gambar` (VARCHAR - untuk menyimpan path file)
    * `tanggal_dibuat` (TIMESTAMP, Default: CURRENT_TIMESTAMP)

---

### 1. Instalasi dengan Docker (Rekomendasi)

### Bagian 1: Prasyarat - Instalasi Docker

Sebelum memulai, Anda harus memiliki **Docker Desktop** yang terinstal di sistem Anda (Windows, macOS, atau Linux).

1.  **Unduh Docker Desktop**: Kunjungi situs web resmi [Docker](https://www.docker.com/products/docker-desktop/) dan unduh installer yang sesuai untuk sistem operasi Anda.
2.  **Instal Docker Desktop**: Jalankan installer dan ikuti petunjuk di layar. Proses ini mungkin memerlukan restart komputer.
3.  **Verifikasi Instalasi**: Setelah terinstal dan berjalan, buka Terminal (atau PowerShell/CMD di Windows) dan jalankan dua perintah berikut untuk memastikan Docker dan Docker Compose siap digunakan:
    ```bash
    docker --version
    docker-compose --version
    ```
    *(Catatan: Docker Desktop versi modern sudah menyertakan Docker Compose di dalamnya).*

---

### Bagian 2: Struktur Folder Proyek

Untuk membuat panduan ini berfungsi, kita akan menempatkan file-file Docker di *root* folder proyek Anda agar mudah dikelola.

Pastikan struktur folder Anda terlihat seperti ini:

praktikum-web-a24/

├── css/

├── img/

├── layouts/

├── partials/

├── uploads/

├── views/

├── app.js

├── cerita.php

├── ... (semua file .php lainnya)

│

├── Dockerfile           <-- (BARU: File untuk build image PHP)

├── uploads.ini          <-- (BARU: File konfigurasi PHP kustom)

├── docker-compose.yml   <-- (MODIFIKASI: Untuk menggunakan Dockerfile)

└── koneksi.php

---

### Bagian 3: File Konfigurasi Docker

Sekarang, buat tiga file berikut di dalam folder *root* `praktikum-web-a24/`.

#### 1. `Dockerfile`
Buat file bernama `Dockerfile` (tanpa ekstensi) dan salin kode ini:

```dockerfile
# 1. Mulai dari image PHP-Apache (Basis Debian)
# Kita gunakan versi 8.3-apache seperti permintaan Anda
FROM php:8.3-apache

# 2. Salin file konfigurasi kustom kita ke dalam folder konfigurasi PHP
# Ini akan menaikkan batas memori, post, dan upload
COPY uploads.ini /usr/local/etc/php/conf.d/custom-uploads.ini

# 3. Instal ekstensi database yang dibutuhkan
# (mysqli dan pdo_mysql)
RUN docker-php-ext-install mysqli pdo_mysql

# 4. Instal dependensi sistem (Debian) yang dibutuhkan oleh ekstensi GD
# Ini penting untuk memproses gambar (upload, resize, dll)
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# 5. Setelah dependensi sistem ada, baru kita konfigurasi dan instal ekstensi PHP 'gd'
# Ini memungkinkan PHP bekerja dengan gambar (JPG, PNG, WebP)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd

# 6. Aktifkan modul rewrite Apache
# Ini diperlukan jika Anda berencana menggunakan URL yang 'cantik' (cth: /cerita/1)
RUN a2enmod rewrite
```

#### 2. `uploads.ini`
Buat file bernama `uploads.ini`. File ini akan menimpa pengaturan default PHP di dalam container.

```ini
; File ini akan menaikkan batas upload dan memori PHP

; Menaikkan batas memori dari 128M ke 256M
memory_limit = 256M

; Menaikkan batas ukuran file yang bisa di-upload
; Kita set ke 20M agar server tidak menolak file > 2MB
; Validasi 2MB di dalam kode PHP (tambah_cerita.php) akan tetap berjalan
upload_max_filesize = 20M

; Menaikkan batas total data POST (termasuk file + form)
post_max_size = 25M
```
#### 3. `docker-compose.yml`
Ini adalah file `docker-compose.yml` yang telah **dimodifikasi** untuk menggunakan `Dockerfile` baru Anda.

```yml
version: '3.8'

services:
  web:
    # MODIFIKASI: Ganti 'image' dengan 'build: .'
    # Ini memberitahu Docker Compose untuk MEMBANGUN image dari
    # Dockerfile di folder ini, alih-alih mengunduhnya.
    build: .
    container_name: ceritakita_web
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www/html
    depends_on:
      - mysql
    # CATATAN: 'command' untuk install ekstensi sudah dihapus
    # karena semua ekstensi (mysqli, gd) sudah diinstal
    # langsung di dalam Dockerfile.

  mysql:
    image: mysql:8.0
    container_name: ceritakita_db
    restart: unless-stopped
    ports:
      - "3306:3306" # Anda bisa ubah port host jika 3306 sudah terpakai
    environment:
      MYSQL_DATABASE: ceritakita_db
      MYSQL_USER: user_ceritakita
      MYSQL_PASSWORD: password_rahasia
      MYSQL_ROOT_PASSWORD: root_password_rahasia
    volumes:
      - mysql_data:/var/lib/mysql

volumes:
  mysql_data:
    # Volume ini akan menyimpan data DB Anda secara permanen
    # walaupun container mysql dihapus.
```

### Bagian 4: Instalasi dan Menjalankan Proyek

Setelah semua file di atas (`Dockerfile`, `uploads.ini`, `docker-compose.yml`) berada di folder *root* proyek Anda, ikuti langkah-langkah berikut:

1.  **Clone Repositori (Jika belum)**:
    ```bash
    git clone [https://github.com/ridhosetia/praktikum-web-a24.git](https://github.com/ridhosetia/praktikum-web-a24.git)
    cd praktikum-web-a24
    ```
    *(Pastikan Anda membuat 3 file dari Bagian 3 di dalam folder ini)*

2.  **Konfigurasi `koneksi.php`**:
    Pastikan file `koneksi.php` Anda sesuai dengan *environment* di `docker-compose.yml`.
    ```php
    <?php
    $db_host = 'mysql';      // HARUS 'mysql', sesuai nama service di docker-compose
    $db_user = 'user_ceritakita'; // Sesuai MYSQL_USER
    $db_pass = 'password_rahasia';  // Sesuai MYSQL_PASSWORD
    $db_name = 'ceritakita_db';  // Sesuai MYSQL_DATABASE
    
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    // ... sisa file
    ?>
    ```

3.  **Jalankan Docker Compose (Build Pertama Kali)**:
    Buka Terminal Anda di folder `praktikum-web-a24` dan jalankan:
    ```bash
    docker-compose up -d --build
    ```
    * `up -d`: Menjalankan container di *background* (detached).
    * `--build`: Ini adalah bagian **penting**. Ini memaksa Docker Compose untuk *membangun (build)* image kustom Anda dari `Dockerfile` sebelum menjalankannya. Ini mungkin memakan waktu beberapa menit saat pertama kali dijalankan.

4.  **Setup Database**:
    Container Anda sekarang berjalan.
    * Gunakan *database client* favorit Anda (seperti DBeaver, DataGrip, atau HeidiSQL).
    * Buat koneksi baru ke `localhost` pada port `3306`.
    * Gunakan username `user_ceritakita` dan password `password_rahasia`.
    * Masuk ke database `ceritakita_db`.
    * Buat tabel `users` dan `cerita` yang dibutuhkan oleh aplikasi. (Lihat struktur tabel di bagian atas README ini).

5.  **Akses Aplikasi**:
    Selesai! Buka browser Anda dan akses `http://localhost:8000`.

---

### Perintah Docker Tambahan (Berguna)

* **Untuk mematikan container**:
    ```bash
    docker-compose down
    ```
* **Untuk mematikan DAN menghapus data database (Reset total)**:
    ```bash
    docker-compose down -v
    ```
* **Untuk melihat log (jika terjadi error)**:
    ```bash
    docker-compose logs -f web
    ```
    (Ganti `web` dengan `mysql` untuk melihat log database)
* **Untuk membangun ulang image jika Anda mengubah `Dockerfile`**:
    ```bash
    docker-compose build
    ```

---

### 2. Instalasi dengan XAMPP

1.  **Clone Repositori**:
    Clone atau unduh repositori ini ke dalam folder `htdocs` XAMPP Anda.
    ```bash
    cd C:\xampp\htdocs
    git clone [https://github.com/ridhosetia/praktikum-web-a24.git](https://github.com/ridhosetia/praktikum-web-a24.git)
    ```
    (Aplikasi akan berada di `C:\xampp\htdocs\praktikum-web-a24`)

2.  **Jalankan XAMPP**:
    Pastikan modul **Apache** dan **MySQL** berjalan dari XAMPP Control Panel.

3.  **Buat Database**:
    * Buka `http://localhost/phpmyadmin`.
    * Buat database baru, misalnya `ceritakita_db`.
    * Buat tabel `users` dan `cerita` sesuai struktur di atas.

4.  **Konfigurasi `koneksi.php`**:
    Sesuaikan file `koneksi.php` dengan pengaturan XAMPP Anda (biasanya `root` tanpa password).
    ```php
    <?php
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = ''; // Default XAMPP
    $db_name = 'ceritakita_db';
    
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    // ... sisa file
    ?>
    ```

5.  **Akses Aplikasi**:
    Buka `http://localhost/praktikum-web-a24` di browser Anda.

---

### 3. Instalasi dengan Laragon

1.  **Clone Repositori**:
    Clone atau unduh repositori ini ke dalam folder `www` Laragon Anda.
    ```bash
    cd C:\laragon\www
    git clone [https://github.com/ridhosetia/praktikum-web-a24.git](https://github.com/ridhosetia/praktikum-web-a24.git)
    ```
    (Aplikasi akan berada di `C:\laragon\www\praktikum-web-a24`)

2.  **Jalankan Laragon**:
    Klik "Start All" untuk menjalankan Apache/Nginx dan MySQL.

3.  **Buat Database**:
    * Klik tombol "Database" di Laragon untuk membuka Adminer/HeidiSQL.
    * Buat database baru, misalnya `ceritakita_db`.
    * Buat tabel `users` dan `cerita` sesuai struktur di atas.

4.  **Konfigurasi `koneksi.php`**:
    Sesuaikan file `koneksi.php` dengan pengaturan Laragon Anda (defaultnya `root` tanpa password).
    ```php
    <?php
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = ''; // Default Laragon
    $db_name = 'ceritakita_db';
    
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    // ... sisa file
    ?>
    ```

5.  **Akses Aplikasi**:
    Laragon biasanya membuat *virtual host* secara otomatis. Anda mungkin bisa mengaksesnya di `http://praktikum-web-a24.test`. Jika tidak, `http://localhost/praktikum-web-a24` juga seharusnya berfungsi.

---

## Struktur Folder
/

├── css/

│   ├── cerita.css

│   ├── dashboard.css

│   ├── login.css

│   └── style.css

├── img/

│   └── placeholder.png

├── layouts/

│   └── app.php

├── partials/

│   ├── _footer.php

│   ├── _header.php

│   └── _nav.php

├── uploads/              <- (Folder untuk gambar yang di-upload pengguna)

│   ├── img_xxx.png

│   └── ...

├── views/

│   ├── cerita.php

│   ├── dashboard.php

│   ├── detail_cerita.php

│   ├── edit_cerita.php

│   ├── home.php

│   ├── login.php

│   ├── register.php

│   └── tambah_cerita.php

├── app.js

├── cerita.php

├── dashboard.php

├── detail_cerita.php

├── edit_cerita.php

├── hapus_cerita.php

├── helpers.php

├── index.php

├── koneksi.php

├── login.php

├── logout.php

├── register.php

├── tambah_cerita.php

└── README.md

---

## Contoh Environment Config

Aplikasi ini tidak menggunakan file `.env`. Konfigurasi koneksi database diatur langsung di `koneksi.php`.

**Contoh `koneksi.php`:**

```php
<?php
// Pengaturan koneksi database
$db_host = 'localhost'; // atau 'mysql' jika pakai Docker
$db_user = 'root';      // User database Anda
$db_pass = '250507';      // Password database Anda (kosongkan jika tidak ada)
$db_name = 'ceritakita_db';  // Nama database Anda

// Membuat koneksi ke database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Mengatur set karakter untuk komunikasi dengan database
$conn->set_charset("utf8mb4");
?>
```