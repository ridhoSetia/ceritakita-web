<div class="dashboard-container">
    <h2>Edit Cerita</h2>
    <a href="dashboard.php" class="back-link">&larr; Kembali ke Dashboard</a>

    <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="edit_cerita.php?id=<?php echo htmlspecialchars($cerita['id']); ?>" method="POST" enctype="multipart/form-data" class="story-form">
        
        <div class="form-group">
            <label for="judul">Judul Cerita</label>
            <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($cerita['judul']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="penulis">Nama Penulis</label>
            <input type="text" id="penulis" name="penulis" value="<?php echo htmlspecialchars($cerita['penulis']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="isi">Isi Cerita</label>
            <textarea id="isi" name="isi" rows="10" required><?php echo htmlspecialchars($cerita['isi']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="gambar">Ganti Gambar Sampul (Opsional)</label>
            <?php if (!empty($cerita['gambar'])): ?>
                <div style="margin-bottom: 10px;">
                    <p>Gambar saat ini:</p>
                    <img src="<?php echo htmlspecialchars($cerita['gambar']); ?>" alt="Gambar saat ini" style="max-width: 200px; border-radius: 8px;">
                </div>
            <?php endif; ?>
            <input type="file" id="gambar" name="gambar">
        </div>

        <button type="submit" class="button-tambah">Simpan Perubahan</button>
    </form>
</div>