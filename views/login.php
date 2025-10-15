<div class="auth-container">
    <h2>Login Akun</h2>
    <?php if ($error ?? false): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST" class="auth-form">
        <input type="text" name="username" placeholder="Masukkan Username Anda" required>
        <input type="password" name="password" placeholder="Masukkan Password" required>
        <button type="submit">Login</button>
    </form>
</div>