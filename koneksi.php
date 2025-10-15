<?php
// Pengaturan koneksi database
$db_host = 'mysql';      // Host harus 'mysql' sesuai nama service di docker-compose
$db_user = 'root';      // User yang Anda buat di environment
$db_pass = '250507';      // Password yang Anda buat di environment
$db_name = 'ceritakita_db';  // Nama database yang Anda buat di environment

// Membuat koneksi ke database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    // Jika koneksi gagal, hentikan eksekusi dan tampilkan pesan error
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Mengatur set karakter untuk komunikasi dengan database
$conn->set_charset("utf8mb4");
?>