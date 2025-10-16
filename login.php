<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Logika ini hanya berjalan saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Username dan password tidak boleh kosong!'];
        header('Location: login.php');
        exit;
    }

    // Ambil data user dari database
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $stmt->close(); // Tutup statement di sini
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            // Password salah
            $stmt->close(); // Tutup statement di sini
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Username atau password salah!'];
            header('Location: login.php');
            exit;
        }
    } else {
        // User tidak ditemukan
        $stmt->close(); // Tutup statement di sini
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Username atau password salah!'];
        header('Location: login.php');
        exit;
    }
}

// Data untuk view
$data = [
    'title' => 'Login - CeritaKita',
];

// Render view 'login'
render('login', $data, 'app');
?>