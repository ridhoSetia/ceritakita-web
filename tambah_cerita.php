<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// Cek jika pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect ke login jika belum
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $isi = trim($_POST['isi']); // Ambil isi cerita
    $user_id = $_SESSION['user_id'];
    $gambar = $_FILES['gambar'];
    $gambar_path = 'img/placeholder.png'; // Pastikan file ini ada di folder /img/

    // Validasi input, termasuk isi cerita
    if (empty($judul) || empty($penulis) || empty($isi)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Judul, Penulis, dan Isi Cerita tidak boleh kosong!'];
        header('Location: tambah_cerita.php');
        exit;
    }

    // --- PROSES UPLOAD GAMBAR BARU (LEBIH AMAN) ---
    // Cek jika file DIKIRIM dan BUKAN KOSONG
    if (isset($gambar) && $gambar['error'] !== UPLOAD_ERR_NO_FILE && $gambar['size'] > 0) {
        
        // --- PERBAIKAN: Cek error upload dari server/php.ini ---
        if ($gambar['error'] === UPLOAD_ERR_INI_SIZE || $gambar['error'] === UPLOAD_ERR_FORM_SIZE) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Ukuran file terlalu besar (melebihi batas server).'];
            header('Location: tambah_cerita.php');
            exit;
        }

        // Cek error lainnya (selain UPLOAD_ERR_OK)
        if ($gambar['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Terjadi error saat mengupload file. Kode: ' . $gambar['error']];
            header('Location: tambah_cerita.php');
            exit;
        }
        // --- Akhir Pengecekan Error ---

        $tmp_name = $gambar["tmp_name"];

        // Validasi Ukuran File kustom kita (2MB)
        $max_file_size = 2 * 1024 * 1024; // 2 MB (dalam bytes)
        if ($gambar['size'] > $max_file_size) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Ukuran file terlalu besar. Maksimal adalah 2 MB.'];
            header('Location: tambah_cerita.php');
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
            header('Location: tambah_cerita.php');
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
             header('Location: tambah_cerita.php');
             exit;
        }
        
        // 5. Simpan gambar yang sudah bersih
        $save_success = false;
        switch ($mime_type) {
            case 'image/jpeg': $save_success = imagejpeg($image, $target_file, 85); break;
            case 'image/png':  $save_success = imagepng($image, $target_file);  break;
            case 'image/gif':  $save_success = imagegif($image, $target_file);  break;
        }
        
        imagedestroy($image); // Bebaskan memori

        if ($save_success) {
            $gambar_path = $target_file; // Set path gambar baru jika berhasil
        } else {
             $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menyimpan gambar yang telah diproses.'];
             header('Location: tambah_cerita.php');
             exit;
        }
    }
    // --- AKHIR DARI PROSES UPLOAD GAMBAR ---

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO cerita (judul, penulis, isi, user_id, gambar) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $judul, $penulis, $isi, $user_id, $gambar_path);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Cerita baru berhasil dipublikasikan!'];
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menyimpan cerita.'];
        header('Location: tambah_cerita.php');
        exit;
    }
}

$data = [
    'title' => 'Tulis Cerita Baru - CeritaKita',
];

render('tambah_cerita', $data, 'app');
?>