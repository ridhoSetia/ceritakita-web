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
$success_message = '';

if (isset($_GET['status']) && $_GET['status'] == 'registered') {
    $success_message = 'Registrasi berhasil! Silakan login.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Ambil data user dari database
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Login berhasil
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
        }
        $stmt->close();
    } else {
        $error = 'Username dan password tidak boleh kosong!';
    }
}

$data = [
    'title' => 'Login - CeritaKita',
    'error' => $error,
    'success_message' => $success_message
];

render('login', $data, 'app');
?>