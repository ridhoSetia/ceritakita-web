<div class="auth-container">
    <h2>Buat Akun Baru</h2>

    <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST" class="auth-form">
        <input type="text" name="username" placeholder="Masukkan Username Anda" required>
        <input type="password" name="password" placeholder="Buat Password (min. 6 karakter)" required>
        <button type="submit">Register</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>