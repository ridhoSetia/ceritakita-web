<nav>
    <div class="logo">
        <a href="index.php">
            <img src="img/logo.png" alt="CeritaKita logo" width="100px" style="cursor: pointer" />
        </a>
    </div>
    <ul>
        <li><a href="#tulis">Tulis Cerita</a></li>
        <li><a href="#komunitas">Komunitas</a></li>
        <li><a href="#kisah">Kisah Inspiratif</a></li>
        <?php if ($isLoggedIn): ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
    <div class="theme-switcher">
        <i class="fa-solid fa-sun"></i>
        <i class="fa-solid fa-moon"></i>
    </div>
    <div class="bx bx-menu hamburger-menu" id="menu-icon"></div>
</nav>