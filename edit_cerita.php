<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Pastikan ada ID cerita di URL
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$error = '';

// Ambil data cerita yang akan diedit
$stmt = $conn->prepare("SELECT * FROM cerita WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cerita = $result->fetch_assoc();

// Jika cerita tidak ditemukan atau bukan milik user, redirect
if (!$cerita) {
    header('Location: dashboard.php');
    exit;
}

// Proses form saat disubmit (method POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $isi = trim($_POST['isi']);

    if (empty($judul) || empty($penulis) || empty($isi)) {
        $error = 'Judul, Penulis, dan Isi Cerita tidak boleh kosong!';
    } else {
        $gambar_path = $cerita['gambar']; // Gunakan gambar lama sebagai default

        // Cek jika ada gambar baru yang diupload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            $file_name = time() . '_' . basename($_FILES['gambar']['name']);
            $target_file = $upload_dir . $file_name;

            // Hapus gambar lama jika ada
            if ($cerita['gambar'] && file_exists($cerita['gambar'])) {
                unlink($cerita['gambar']);
            }

            // Pindahkan gambar baru
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                $gambar_path = $target_file;
            } else {
                $error = 'Gagal mengupload gambar baru.';
            }
        }

        if (empty($error)) {
            // Update data di database
            $stmt_update = $conn->prepare("UPDATE cerita SET judul = ?, penulis = ?, isi = ?, gambar = ? WHERE id = ? AND user_id = ?");
            $stmt_update->bind_param("ssssii", $judul, $penulis, $isi, $gambar_path, $id, $user_id);

            if ($stmt_update->execute()) {
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Cerita berhasil diperbarui!'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Gagal memperbarui cerita.';
            }
        }
    }
}

$data = [
    'title' => 'Edit Cerita - CeritaKita',
    'error' => $error,
    'cerita' => $cerita // Kirim data cerita lama ke view
];

render('edit_cerita', $data, 'app');
?>