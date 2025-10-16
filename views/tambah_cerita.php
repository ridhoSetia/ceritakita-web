<div class="dashboard-container">
    <h2>Tulis Cerita Baru</h2>
    <a href="dashboard.php" class="back-link">&larr; Kembali ke Dashboard</a>

    <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="tambah_cerita.php" method="POST" enctype="multipart/form-data" class="story-form">
        <div class="form-group">
            <label for="judul">Judul Cerita</label>
            <input type="text" id="judul" name="judul" required>
        </div>
        <div class="form-group">
            <label for="penulis">Nama Penulis</label>
            <input type="text" id="penulis" name="penulis" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="isi">Isi Cerita</label>
            <textarea id="isi" name="isi" rows="10" placeholder="Mulai tulis ceritamu di sini..." required></textarea>
        </div>
        <div class="form-group">
            <label for="gambar">Upload Gambar Sampul</label>
            <input type="file" id="gambar" name="gambar">
        </div>
        <button type="submit" class="button-tambah">Publikasikan Cerita</button>
    </form>
</div>