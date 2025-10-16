<?php
session_start();
require 'koneksi.php';

// Cek jika pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Ambil path gambar untuk dihapus dari server
    $stmt = $conn->prepare("SELECT gambar FROM cerita WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if ($row['gambar'] && file_exists($row['gambar'])) {
            unlink($row['gambar']); // Hapus file gambar
        }
    }
    $stmt->close();

    // Hapus record dari database
    $stmt_delete = $conn->prepare("DELETE FROM cerita WHERE id = ? AND user_id = ?");
    $stmt_delete->bind_param("ii", $id, $user_id);
    $stmt_delete->execute();
    $stmt_delete->close();
}

header('Location: dashboard.php?status=deleted');
exit;
?>