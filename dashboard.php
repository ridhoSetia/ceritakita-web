<?php
session_start();
require 'helpers.php';
require 'koneksi.php'; // Hubungkan ke database

// Cek jika pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Ambil semua data cerita dari database
$result = $conn->query("SELECT * FROM cerita ORDER BY tanggal_dibuat DESC");

// Data untuk view
$data = [
    'title' => 'Dashboard - CeritaKita',
    'username' => $_SESSION['username'],
    'halaman' => isset($_GET['halaman']) ? $_GET['halaman'] : 'dashboard',
    'cerita' => $result // Kirim data cerita ke view
];

// Render view 'dashboard' menggunakan layout 'app'
render('dashboard', $data, 'app');
?>