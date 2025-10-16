<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// Ambil semua data cerita, diurutkan dari yang terbaru
$result = $conn->query("SELECT id, judul, penulis, gambar FROM cerita ORDER BY tanggal_dibuat DESC");

$data = [
    'title' => 'Kumpulan Cerita - CeritaKita',
    'cerita' => $result
];

render('cerita', $data, 'app');
?>