<?php
session_start();
require 'helpers.php';

if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username dan password tidak boleh kosong!';
    }
}

// Data untuk view
$data = [
    'title' => 'Login - CeritaKita',
    'error' => $error
];

// Render view 'login' menggunakan layout 'auth'
render('login', $data, 'auth');