<nav>
    <div class="logo">
        <a href="/">
            <h2 style="color: #27ae60; text-decoration: underline;">CeritaKita</h2>
        </a>
    </div>
    <ul>
        <li><a href="cerita.php">Kumpulan Cerita</a></li> 
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