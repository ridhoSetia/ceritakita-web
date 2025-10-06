<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($title ?? 'CeritaKita'); ?></title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body class="<?php echo $bodyClass ?? ''; ?>">

    <?php include __DIR__ . '/../partials/_header.php'; ?>
    <?php include __DIR__ . '/../partials/_nav.php'; ?>
    
    <main>
        <?php echo $content; // Konten dinamis dari view akan dimuat di sini ?>
    </main>

    <?php include __DIR__ . '/../partials/_footer.php'; ?>
    
    <script src="../app.js"></script>
</body>
</html>