<div class="story-detail-container">
    <img src="<?php echo htmlspecialchars($cerita['gambar'] ?? 'img/placeholder.png'); ?>" alt="Gambar sampul untuk <?php echo htmlspecialchars($cerita['judul']); ?>" class="story-detail-image">
    
    <div class="story-detail-content">
        <h1><?php echo htmlspecialchars($cerita['judul']); ?></h1>
        <div class="story-meta">
            <span>Oleh: <strong><?php echo htmlspecialchars($cerita['penulis']); ?></strong></span>
            <span>Dipublikasikan pada: <?php echo date('d F Y', strtotime($cerita['tanggal_dibuat'])); ?></span>
        </div>
        <div class="story-body">
            <?php echo nl2br(htmlspecialchars($cerita['isi'])); ?>
        </div>
        <a href="cerita.php" class="back-link">&larr; Kembali ke Kumpulan Cerita</a>
    </div>
</div>