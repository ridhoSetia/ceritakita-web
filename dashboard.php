<?php
session_start();
require 'helpers.php';
require 'koneksi.php'; 

// Cek jika pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data cerita milik pengguna yang login
$stmt = $conn->prepare("SELECT id, judul, penulis FROM cerita WHERE user_id = ? ORDER BY tanggal_dibuat DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [
    'title' => 'Dashboard - CeritaKita',
    'username' => $_SESSION['username'],
    'cerita' => $result 
];

render('dashboard', $data, 'app');
?>