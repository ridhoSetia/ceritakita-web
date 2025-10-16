<?php
// Memulai session
session_start();

// Hapus semua variabel session
$_SESSION = array();

session_destroy();

// Redirect
header('Location: /');
exit;
?>