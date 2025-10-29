# 📚 CeritaKita

> Platform aplikasi web sederhana untuk berbagi dan membaca cerita

**CeritaKita** adalah platform berbasis web yang memungkinkan pengguna untuk berbagi dan membaca cerita. Dibangun menggunakan PHP native dan MySQL dengan fokus pada operasi CRUD dan autentikasi pengguna yang aman.

![Screenshot Aplikasi CeritaKita](img/ss.jpg)

---

## ✨ Fitur Utama

### 🔐 Autentikasi Pengguna
- ✅ Registrasi pengguna baru (minimal 6 karakter password)
- ✅ Login & Logout yang aman
- ✅ Proteksi halaman otomatis (redirect ke login jika belum terautentikasi)

### 📝 Manajemen Cerita (CRUD)

#### Create (Tambah)
Pengguna dapat mempublikasikan cerita baru lengkap dengan:
- Judul cerita
- Nama penulis
- Isi cerita
- Upload gambar sampul

#### Read (Baca)
- 📖 Daftar semua cerita dengan *lazy loading* / *pagination*
- 📄 Detail cerita di halaman terpisah
- 🆕 Menampilkan 2 cerita terbaru di halaman beranda

#### Update (Edit)
- ✏️ Edit cerita yang Anda miliki melalui dashboard

#### Delete (Hapus)
- 🗑️ Hapus cerita dengan konfirmasi modal

### 🎛️ Dashboard Pengguna
- Kelola semua cerita yang telah Anda tulis
- Akses cepat untuk menambah, mengedit, atau menghapus cerita

### 🎨 Fitur Tambahan

| Fitur | Deskripsi |
|-------|-----------|
| 🔍 **Pencarian** | Cari cerita berdasarkan judul atau penulis |
| 📄 **Pagination** | Navigasi halaman untuk menelusuri banyak cerita |
| 🖼️ **Upload Gambar** | Validasi client & server-side, auto-reprocess untuk keamanan |
| 🌙 **Dark Mode** | Toggle tema terang/gelap |
| 💬 **Notifikasi Modal** | Flash message dalam bentuk pop-up yang elegan |
| ⚠️ **Konfirmasi Modal** | Konfirmasi sebelum menghapus cerita |

---

## 💻 Kebutuhan Sistem

### Server
- Apache / Nginx

### PHP
- **Versi**: 7.4 atau lebih baru (disarankan 8.x)
- **Ekstensi yang diperlukan**:
  - `mysqli` - Koneksi database
  - `gd` - Pemrosesan gambar
  - `fileinfo` - Validasi MIME type

### Database
- MySQL 5.7+ / MariaDB

### Browser
- Browser modern (Chrome, Firefox, Safari, Edge)

---

## 🗄️ Struktur Database

> ⚠️ **Penting**: Database SQL tidak disertakan di repositori. Anda perlu membuat tabel secara manual.

### Tabel `users`

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
```

### Tabel `cerita`

```sql
CREATE TABLE cerita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    isi TEXT NOT NULL,
    gambar VARCHAR(255),
    tanggal_dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 🚀 Instalasi

### Metode 1: Docker (Rekomendasi) 🐳

#### Langkah 1: Install Docker Desktop

1. Unduh [Docker Desktop](https://www.docker.com/products/docker-desktop/)
2. Install dan restart komputer jika diperlukan
3. Verifikasi instalasi:

```bash
docker --version
docker-compose --version
```

#### Langkah 2: Struktur Proyek

Pastikan struktur folder Anda seperti ini:

```
ceritakita-web/
├── css/
├── img/
├── layouts/
├── partials/
├── uploads/
├── views/
├── app.js
├── *.php
├── Dockerfile           ← BARU
├── uploads.ini          ← BARU
├── docker-compose.yml   ← MODIFIKASI
└── koneksi.php
```

#### Langkah 3: File Konfigurasi Docker

##### 📄 `Dockerfile`

```dockerfile
# Base image PHP 8.3 dengan Apache
FROM php:8.3-apache

# Copy konfigurasi PHP kustom
COPY uploads.ini /usr/local/etc/php/conf.d/custom-uploads.ini

# Install ekstensi database
RUN docker-php-ext-install mysqli pdo_mysql

# Install dependensi untuk GD
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Konfigurasi dan install GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd

# Enable Apache rewrite module
RUN a2enmod rewrite
```

##### 📄 `uploads.ini`

```ini
; Konfigurasi upload dan memori PHP

memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 25M
```

##### 📄 `docker-compose.yml`

```yaml
version: '3.8'

services:
  web:
    build: .
    container_name: ceritakita_web
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www/html
    depends_on:
      - mysql

  mysql:
    image: mysql:8.0
    container_name: ceritakita_db
    restart: unless-stopped
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: ceritakita_db
      MYSQL_USER: user_ceritakita
      MYSQL_PASSWORD: password_rahasia
      MYSQL_ROOT_PASSWORD: root_password_rahasia
    volumes:
      - mysql_data:/var/lib/mysql

volumes:
  mysql_data:
```

#### Langkah 4: Konfigurasi Database

Edit file `koneksi.php`:

```php
<?php
$db_host = 'mysql';                  // Nama service di docker-compose
$db_user = 'user_ceritakita';        // Sesuai MYSQL_USER
$db_pass = 'password_rahasia';       // Sesuai MYSQL_PASSWORD
$db_name = 'ceritakita_db';          // Sesuai MYSQL_DATABASE

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
```

#### Langkah 5: Jalankan Aplikasi

```bash
# Clone repository
git clone https://github.com/ridhosetia/ceritakita-web.git
cd ceritakita-web

# Build dan jalankan container
docker-compose up -d --build
```

#### Langkah 6: Setup Database

Gunakan database client (DBeaver, HeidiSQL, dll):
- **Host**: `localhost`
- **Port**: `3306`
- **Username**: `user_ceritakita`
- **Password**: `password_rahasia`
- **Database**: `ceritakita_db`

Jalankan query SQL untuk membuat tabel `users` dan `cerita`.

#### Langkah 7: Akses Aplikasi

🎉 Buka browser: `http://localhost:8000`

---

### 📋 Perintah Docker Berguna

```bash
# Matikan container
docker-compose down

# Matikan dan hapus data (reset total)
docker-compose down -v

# Lihat log
docker-compose logs -f web

# Build ulang setelah ubah Dockerfile
docker-compose build
```

---

### Metode 2: XAMPP 🔴

1. **Clone ke folder htdocs**
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/ridhosetia/ceritakita-web.git
   ```

2. **Jalankan XAMPP**
   - Start Apache dan MySQL dari Control Panel

3. **Buat Database**
   - Buka `http://localhost/phpmyadmin`
   - Buat database `ceritakita_db`
   - Buat tabel `users` dan `cerita`

4. **Konfigurasi koneksi.php**
   ```php
   <?php
   $db_host = 'localhost';
   $db_user = 'root';
   $db_pass = '';              // Default XAMPP kosong
   $db_name = 'ceritakita_db';
   
   $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
   $conn->set_charset("utf8mb4");
   ?>
   ```

5. **Akses Aplikasi**
   - 🌐 `http://localhost/ceritakita-web`

---

### Metode 3: Laragon 🔷

1. **Clone ke folder www**
   ```bash
   cd C:\laragon\www
   git clone https://github.com/ridhosetia/ceritakita-web.git
   ```

2. **Jalankan Laragon**
   - Klik "Start All"

3. **Buat Database**
   - Klik tombol "Database" di Laragon
   - Buat database `ceritakita_db`
   - Buat tabel `users` dan `cerita`

4. **Konfigurasi koneksi.php**
   ```php
   <?php
   $db_host = 'localhost';
   $db_user = 'root';
   $db_pass = '';              // Default Laragon kosong
   $db_name = 'ceritakita_db';
   
   $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
   $conn->set_charset("utf8mb4");
   ?>
   ```

5. **Akses Aplikasi**
   - 🌐 `http://ceritakita-web.test` (virtual host)
   - atau `http://localhost/ceritakita-web`

---

## 📁 Struktur Folder

```
ceritakita-web/
│
├── 📂 css/
│   ├── cerita.css
│   ├── dashboard.css
│   ├── login.css
│   └── style.css
│
├── 📂 img/
│   └── placeholder.png
│
├── 📂 layouts/
│   └── app.php
│
├── 📂 partials/
│   ├── _footer.php
│   ├── _header.php
│   └── _nav.php
│
├── 📂 uploads/           ← Upload pengguna
│   ├── img_xxx.png
│   └── ...
│
├── 📂 views/
│   ├── cerita.php
│   ├── dashboard.php
│   ├── detail_cerita.php
│   ├── edit_cerita.php
│   ├── home.php
│   ├── login.php
│   ├── register.php
│   └── tambah_cerita.php
│
├── 📄 app.js
├── 📄 cerita.php
├── 📄 dashboard.php
├── 📄 detail_cerita.php
├── 📄 edit_cerita.php
├── 📄 hapus_cerita.php
├── 📄 helpers.php
├── 📄 index.php
├── 📄 koneksi.php
├── 📄 login.php
├── 📄 logout.php
├── 📄 register.php
├── 📄 tambah_cerita.php
└── 📄 README.md
```

---

## 🔧 Konfigurasi Database

Aplikasi ini tidak menggunakan file `.env`. Konfigurasi koneksi database diatur langsung di `koneksi.php`.

### Contoh Template

```php
<?php
// ============================================
// KONFIGURASI DATABASE
// ============================================

$db_host = 'localhost';        // Host database
$db_user = 'root';             // Username database
$db_pass = '';                 // Password database (kosongkan jika tidak ada)
$db_name = 'ceritakita_db';    // Nama database

// ============================================
// KONEKSI DATABASE
// ============================================

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("❌ Koneksi ke database gagal: " . $conn->connect_error);
}

// Set charset untuk mencegah masalah encoding
$conn->set_charset("utf8mb4");
?>
```

---

## 🎯 Panduan Penggunaan

### Untuk Pengguna Baru

1. **Registrasi**: Buat akun baru di halaman register
2. **Login**: Masuk dengan username dan password Anda
3. **Tambah Cerita**: Klik tombol "Tambah Cerita" di dashboard
4. **Kelola Cerita**: Edit atau hapus cerita Anda kapan saja

### Tips Keamanan

- ✅ Gunakan password yang kuat (minimal 6 karakter)
- ✅ Logout setelah selesai menggunakan aplikasi
- ✅ Jangan share kredensial login Anda

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan fork repository ini dan buat pull request.

---

## 📄 Lisensi

Proyek ini bersifat open source untuk keperluan edukasi.

---

## 📧 Kontak

Jika ada pertanyaan atau masalah, silakan buat issue di repository GitHub.

---

<div align="center">

**Dibuat dengan ❤️ menggunakan PHP & MySQL**

⭐ Jangan lupa beri bintang jika project ini bermanfaat!

</div>