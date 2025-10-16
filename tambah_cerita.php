<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// Cek jika pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $isi = trim($_POST['isi']); // Ambil isi cerita
    $user_id = $_SESSION['user_id'];
    $gambar = $_FILES['gambar'];
    $gambar_path = null;

    // Validasi input, termasuk isi cerita
    if (empty($judul) || empty($penulis) || empty($isi)) {
        $error = 'Judul, Penulis, dan Isi Cerita tidak boleh kosong!';
    } else {
        // Proses upload gambar (tetap sama)
        if ($gambar['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = time() . '_' . basename($gambar['name']);
            $target_file = $upload_dir . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($gambar["tmp_name"]);
            if ($check !== false && in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                if (move_uploaded_file($gambar['tmp_name'], $target_file)) {
                    $gambar_path = $target_file;
                } else {
                    $error = 'Gagal mengupload gambar.';
                }
            } else {
                $error = 'File bukan gambar atau format tidak didukung.';
            }
        }

        // Simpan ke database jika tidak ada error
        if (empty($error)) {
            // Perbarui query INSERT untuk menyertakan isi
            $stmt = $conn->prepare("INSERT INTO cerita (judul, penulis, isi, user_id, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $judul, $penulis, $isi, $user_id, $gambar_path);

            if ($stmt->execute()) {
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Cerita baru berhasil dipublikasikan!'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Gagal menyimpan cerita.';
            }
        }
    }
}

$data = [
    'title' => 'Tulis Cerita Baru - CeritaKita',
    'error' => $error,
];

render('tambah_cerita', $data, 'app');
?>