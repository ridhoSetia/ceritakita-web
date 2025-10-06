<?php
require 'helpers.php';

// Data untuk halaman ini
$data = [
    'title' => 'CeritaKita - Berbagi Pengalaman Hidup'
];

// Render view 'home' menggunakan layout 'app'
render('home', $data, 'app');