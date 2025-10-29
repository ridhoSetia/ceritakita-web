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
    $gambar_path = $cerita['gambar']; // Gunakan gambar lama sebagai default

    if (empty($judul) || empty($penulis) || empty($isi)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Judul, Penulis, dan Isi Cerita tidak boleh kosong!'];
        header('Location: edit_cerita.php?id=' . $id);
        exit;
    }

    // --- PROSES UPLOAD GAMBAR BARU (LEBIH AMAN) ---
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE && $_FILES['gambar']['size'] > 0) {
        
        $gambar = $_FILES['gambar'];

        // --- PERBAIKAN: Cek error upload dari server/php.ini ---
        if ($gambar['error'] === UPLOAD_ERR_INI_SIZE || $gambar['error'] === UPLOAD_ERR_FORM_SIZE) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Ukuran file terlalu besar (melebihi batas server).'];
            header('Location: edit_cerita.php?id=' . $id);
            exit;
        }

        // Cek error lainnya (selain UPLOAD_ERR_OK)
        if ($gambar['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Terjadi error saat mengupload file. Kode: ' . $gambar['error']];
            header('Location: edit_cerita.php?id=' . $id);
            exit;
        }
        // --- Akhir Pengecekan Error ---

        $tmp_name = $gambar["tmp_name"];

        // Validasi Ukuran File kustom kita (2MB)
        $max_file_size = 2 * 1024 * 1024; // 2 MB (dalam bytes)
        if ($gambar['size'] > $max_file_size) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Ukuran file terlalu besar. Maksimal adalah 2 MB.'];
            header('Location: edit_cerita.php?id=' . $id);
            exit;
        }

        // 1. Verifikasi MIME Type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $tmp_name);
        finfo_close($finfo);

        $allowed_mime_types = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif'
        ];

        if (!isset($allowed_mime_types[$mime_type])) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Format file tidak valid. Hanya JPG, PNG, atau GIF yang diizinkan.'];
            header('Location: edit_cerita.php?id=' . $id);
            exit;
        }
        
        $safe_extension = $allowed_mime_types[$mime_type];
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $new_file_name = uniqid('img_', true) . '.' . $safe_extension;
        $target_file = $upload_dir . $new_file_name;

        // 4. Re-proses Gambar
        $image = null;
        switch ($mime_type) {
            case 'image/jpeg': $image = imagecreatefromjpeg($tmp_name); break;
            case 'image/png':  $image = imagecreatefrompng($tmp_name);  break;
            case 'image/gif':  $image = imagecreatefromgif($tmp_name);  break;
        }

        if ($image === false) {
             $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal memproses file gambar. File mungkin rusak.'];
             header('Location: edit_cerita.php?id=' . $id);
             exit;
        }

        // 5. Simpan gambar yang sudah bersih
        $save_success = false;
        switch ($mime_type) {
            case 'image/jpeg': $save_success = imagejpeg($image, $target_file, 85); break;
            case 'image/png':  $save_success = imagepng($image, $target_file);  break;
            case 'image/gif':  $save_success = imagegif($image, $target_file);  break;
        }
        
        imagedestroy($image);

        if ($save_success) {
            // Hapus gambar lama JIKA gambar baru berhasil disimpan
            if ($cerita['gambar'] && file_exists($cerita['gambar']) && $cerita['gambar'] != 'img/placeholder.png') {
                unlink($cerita['gambar']);
            }
            $gambar_path = $target_file; // Set path ke gambar baru
        } else {
             $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menyimpan gambar yang telah diproses.'];
             header('Location: edit_cerita.php?id=' . $id);
             exit;
        }
    }
    // --- AKHIR DARI PROSES UPLOAD GAMBAR ---


    // Update data di database
    $stmt_update = $conn->prepare("UPDATE cerita SET judul = ?, penulis = ?, isi = ?, gambar = ? WHERE id = ? AND user_id = ?");
    $stmt_update->bind_param("ssssii", $judul, $penulis, $isi, $gambar_path, $id, $user_id);

    if ($stmt_update->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Cerita berhasil diperbarui!'];
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal memperbarui cerita.'];
        header('Location: edit_cerita.php?id=' . $id);
        exit;
    }
}

$data = [
    'title' => 'Edit Cerita - CeritaKita',
    'cerita' => $cerita 
];

render('edit_cerita', $data, 'app');
?>