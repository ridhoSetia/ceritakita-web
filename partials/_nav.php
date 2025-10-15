<nav>
    <div class="logo">
        <a href="index.php">
            <img src="img/logo.png" alt="CeritaKita logo" />
        </a>
    </div>
    <ul>
        <li><a href="index.php#fitur">Fitur</a></li>
        <li><a href="index.php#kisah">Kisah Inspiratif</a></li>
        <?php if ($isLoggedIn ?? false): ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php" class="nav-button">Login</a></li>
        <?php endif; ?>
        <li class="theme-switcher">
            <i class="fa-solid fa-sun"></i>
            <i class="fa-solid fa-moon"></i>
        </li>
    </ul>
    <div class="bx bx-menu" id="menu-icon"></div>
</nav>