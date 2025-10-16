<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// Cek apakah ada ID di URL
if (!isset($_GET['id'])) {
    header('Location: cerita.php');
    exit;
}

$id = $_GET['id'];

// Ambil data cerita spesifik dari database
$stmt = $conn->prepare("SELECT judul, penulis, isi, gambar, tanggal_dibuat FROM cerita WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cerita = $result->fetch_assoc();

// Jika cerita tidak ditemukan, redirect
if (!$cerita) {
    header('Location: cerita.php');
    exit;
}

$data = [
    'title' => htmlspecialchars($cerita['judul']) . ' - CeritaKita',
    'cerita' => $cerita
];

render('detail_cerita', $data, 'app');
?>