<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Username dan password tidak boleh kosong!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal harus 6 karakter!';
    } else {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Username sudah digunakan, silakan pilih yang lain!';
        } else {
            // Hash password untuk keamanan
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // Simpan pengguna baru ke database
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt_insert->bind_param("ss", $username, $password_hash);

            if ($stmt_insert->execute()) {
                // Redirect ke halaman login setelah berhasil
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Registrasi berhasil! Silakan login.'];
                header('Location: login.php');
                exit;
            } else {
                $error = 'Terjadi kesalahan, silakan coba lagi.';
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}

$data = [
    'title' => 'Register - CeritaKita',
    'error' => $error
];

render('register', $data, 'app');
?>