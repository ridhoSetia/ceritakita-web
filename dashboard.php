<?php
session_start();
require 'helpers.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Data untuk view
$data = [
    'title' => 'Dashboard - CeritaKita',
    'username' => $_SESSION['username'],
    'halaman' => isset($_GET['halaman']) ? $_GET['halaman'] : 'dashboard'
];

// Render view 'dashboard' menggunakan layout 'auth'
render('dashboard', $data, 'auth');