<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($title ?? 'CeritaKita'); ?></title>
    <link rel="stylesheet" href="./css/style.css">
    <?php
    // Definisikan grup halaman dengan judul-judulnya
    $auth_pages = [
        "Login - CeritaKita",
        "Register - CeritaKita"
    ];

    $dashboard_pages = [
        "Dashboard - CeritaKita",
        "Tulis Cerita Baru - CeritaKita",
        "Edit Cerita - CeritaKita"
    ];

    $story_pages = [
        "Kumpulan Cerita - CeritaKita"
    ];

    // --- Logika Pemuatan CSS ---
    
    // Muat login.css jika judul halaman ada di dalam grup autentikasi
    if (in_array($title, $auth_pages)) {
        echo '<link rel="stylesheet" href="./css/login.css">';
    }

    // Muat dashboard.css jika judul halaman ada di dalam grup dashboard
    if (in_array($title, $dashboard_pages)) {
        echo '<link rel="stylesheet" href="./css/dashboard.css">';
    }

    // Muat cerita.css jika judul halaman ada di grup cerita,
    // atau jika BUKAN halaman auth, BUKAN halaman dashboard, dan BUKAN halaman utama.
    // Ini akan secara efektif mencakup halaman detail cerita.
    if (in_array($title, $story_pages) || (!in_array($title, $auth_pages) && !in_array($title, $dashboard_pages) && $title != "CeritaKita - Berbagi Pengalaman Hidup")) {
        echo '<link rel="stylesheet" href="./css/cerita.css">';
    }
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>

    <?php include __DIR__ . '/../partials/_nav.php'; ?>

    <?php
    if ($title == "CeritaKita - Berbagi Pengalaman Hidup") {
        include __DIR__ . '/../partials/_header.php';
    }
    ?>

    <main>
        <?php echo $content; // Dynamic content from the view will be loaded here ?>
    </main>

    <?php include __DIR__ . '/../partials/_footer.php'; ?>

    <script src="app.js"></script>
</body>

</html>