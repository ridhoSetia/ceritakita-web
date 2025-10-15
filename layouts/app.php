<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($title ?? 'CeritaKita'); ?></title>
    <link rel="stylesheet" href="./css/style.css">
    <?php if ($title == "Login - CeritaKita"): ?>
        <link rel="stylesheet" href="./css/login.css">
    <?php endif; ?>
    <?php if ($title == "Dashboard - CeritaKita"): ?>
        <link rel="stylesheet" href="./css/dashboard.css">
    <?php endif; ?>
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