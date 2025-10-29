<div class="search-container">
    <form action="cerita.php" method="GET">
        <input type="text" name="q" placeholder="Cari berdasarkan judul atau penulis..." value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
        <button type="submit">Cari</button>
    </form>
</div>

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
        <?php if (!empty($search_term)): ?>
            <p class="no-results">Tidak ada cerita yang ditemukan untuk "<?php echo htmlspecialchars($search_term); ?>".</p>
        <?php else: ?>
            <p class="no-results">Belum ada cerita yang dipublikasikan saat ini.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="pagination-container">
    <?php // Hanya tampilkan pagination jika total halaman lebih dari 1
    if ($total_halaman > 1): ?>
        
        <?php if ($halaman_aktif > 1): ?>
            <a href="cerita.php?page=<?php echo $halaman_aktif - 1; ?>&q=<?php echo htmlspecialchars($search_term ?? ''); ?>">&laquo;</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
            <a href="cerita.php?page=<?php echo $i; ?>&q=<?php echo htmlspecialchars($search_term ?? ''); ?>" 
               class="<?php echo ($i == $halaman_aktif) ? 'active' : ''; // Beri kelas 'active' jika halaman ini sedang dibuka ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($halaman_aktif < $total_halaman): ?>
            <a href="cerita.php?page=<?php echo $halaman_aktif + 1; ?>&q=<?php echo htmlspecialchars($search_term ?? ''); ?>">&raquo;</a>
        <?php endif; ?>

    <?php endif; ?>
</div>