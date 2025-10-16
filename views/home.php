<section class="section" id="kisah">
    <div class="section-title">
        <h2>Kisah Pengalaman Penuh Makna</h2>
        <p>Jelajahi cerita-cerita pilihan yang akan menyentuh hatimu dan memberimu sudut pandang baru.</p>
    </div>
    <div class="stories-grid">
        <?php // Pengecekan apakah ada cerita untuk ditampilkan
        if ($latest_stories instanceof mysqli_result && $latest_stories->num_rows > 0): ?>
            <?php while ($row = $latest_stories->fetch_assoc()): ?>
                <article class="story-card">
                    <img src="<?php echo htmlspecialchars($row['gambar'] ?? 'img/placeholder.png'); ?>" alt="Gambar sampul untuk <?php echo htmlspecialchars($row['judul']); ?>" />
                    <div class="story-content">
                        <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                        <p>
                            <?php 
                                // Potong isi cerita jika terlalu panjang
                                $snippet = htmlspecialchars($row['isi']);
                                if (strlen($snippet) > 100) {
                                    $snippet = substr($snippet, 0, 100) . '...';
                                }
                                echo $snippet;
                            ?>
                        </p>
                        <a href="detail_cerita.php?id=<?php echo $row['id']; ?>" class="read-more-link">Baca Selengkapnya &rarr;</a>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada cerita yang dipublikasikan saat ini.</p>
        <?php endif; ?>
    </div>
</section>