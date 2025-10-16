<div class="story-gallery-grid">
    <?php if ($cerita instanceof mysqli_result && $cerita->num_rows > 0): ?>
        <?php while ($row = $cerita->fetch_assoc()): ?>
            <a href="detail_cerita.php?id=<?php echo $row['id']; ?>" class="story-gallery-card">
                <img src="<?php echo htmlspecialchars($row['gambar'] ?? 'img/placeholder.png'); ?>" alt="Gambar sampul untuk <?php echo htmlspecialchars($row['judul']); ?>">
                <div class="story-card-overlay">
                    <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                    <p>Oleh: <?php echo htmlspecialchars($row['penulis']); ?></p>
                </div>
            </a>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada cerita yang dipublikasikan saat ini.</p>
    <?php endif; ?>
</div>