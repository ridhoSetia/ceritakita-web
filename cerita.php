<?php
session_start();
require 'helpers.php';
require 'koneksi.php';

// --- PENGATURAN PAGINATION DAN PENCARIAN ---

// 1. Tentukan data per halaman (syarat min. 5, kita pakai 6 agar pas di grid)
$data_per_halaman = 6;

// 2. Ambil halaman aktif dari URL, default-nya 1 jika tidak ada
$halaman_aktif = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;

// 3. Hitung OFFSET untuk query SQL
$offset = ($halaman_aktif - 1) * $data_per_halaman;

// 4. Ambil keyword pencarian dari URL
$search_term = $_GET['q'] ?? '';
// Siapkan parameter LIKE untuk prepared statement
$search_param = "%" . $search_term . "%";

// --- QUERY DATABASE ---

// 5. Query untuk menghitung TOTAL data (sesuai filter pencarian)
// Ini penting untuk tahu berapa total halaman yang dibutuhkan
$sql_total = "SELECT COUNT(id) AS total FROM cerita WHERE (judul LIKE ? OR penulis LIKE ?)";
$stmt_total = $conn->prepare($sql_total);
// Bind parameter pencarian (tipe 'ss' = string, string)
$stmt_total->bind_param("ss", $search_param, $search_param);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_data = $result_total->fetch_assoc()['total'];

// 6. Hitung total halaman
$total_halaman = ceil($total_data / $data_per_halaman);


// 7. Query untuk mengambil data CERITA (sesuai filter, urutan, limit, dan offset)
$sql_data = "SELECT id, judul, penulis, gambar FROM cerita 
             WHERE (judul LIKE ? OR penulis LIKE ?)
             ORDER BY tanggal_dibuat DESC 
             LIMIT ? OFFSET ?";

$stmt_data = $conn->prepare($sql_data);
// Bind parameter (tipe 'ssii' = string, string, integer, integer)
$stmt_data->bind_param("ssii", $search_param, $search_param, $data_per_halaman, $offset);
$stmt_data->execute();
$result_data = $stmt_data->get_result();

// --- KIRIM DATA KE VIEW ---

// 8. Siapkan data untuk dikirim ke fungsi render
$data = [
    'title' => 'Kumpulan Cerita - CeritaKita',
    'cerita' => $result_data,           // Hasil query cerita untuk halaman ini
    'halaman_aktif' => $halaman_aktif,  // Halaman yang sedang dibuka
    'total_halaman' => $total_halaman,    // Jumlah total halaman
    'search_term' => $search_term         // Keyword pencarian (untuk ditampilkan di form)
];

render('cerita', $data, 'app');
?>