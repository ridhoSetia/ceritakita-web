<?php
require 'helpers.php';
require 'koneksi.php'; // Hubungkan ke database

// Ambil 2 cerita terbaru dari database
$latest_stories = $conn->query("SELECT id, judul, penulis, isi, gambar FROM cerita ORDER BY tanggal_dibuat DESC LIMIT 2");

// Data untuk halaman ini
$data = [
    'title' => 'CeritaKita - Berbagi Pengalaman Hidup',
    'latest_stories' => $latest_stories // Kirim data cerita ke view
];

// Render view 'home' menggunakan layout 'app'
render('home', $data, 'app');
?>