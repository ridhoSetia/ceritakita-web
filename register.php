<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Username dan password tidak boleh kosong!'];
        header('Location: register.php');
        exit;
    } elseif (strlen($password) < 6) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Password minimal harus 6 karakter!'];
        header('Location: register.php');
        exit;
    } else {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Username sudah digunakan, silakan pilih yang lain!'];
            
            // FIX: Tutup statement sebelum redirect
            $stmt->close();
            
            header('Location: register.php');
            exit;
        } else {
            // Hash password untuk keamanan
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // Simpan pengguna baru ke database
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt_insert->bind_param("ss", $username, $password_hash);
            
            // Simpan status eksekusi
            $execute_success = $stmt_insert->execute();
            
            // FIX: Tutup kedua statement di sini, sebelum logika redirect
            $stmt_insert->close();
            $stmt->close();

            if ($execute_success) {
                // Redirect ke halaman login setelah berhasil
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Registrasi berhasil! Silakan login.'];
                header('Location: login.php');
                exit;
            } else {
                $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Terjadi kesalahan, silakan coba lagi.'];
                header('Location: register.php');
                exit;
            }
        }
        // Kode yang 'unreachable' sebelumnya sudah dipindahkan ke atas
    }
}

$data = [
    'title' => 'Register - CeritaKita',
];

render('register', $data, 'app');
?>