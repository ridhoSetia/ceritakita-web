<div class="auth-container dashboard-container">
    <h2>Selamat Datang!</h2>
    <p>
        Halo, <strong class="username"><?php echo htmlspecialchars($username); ?></strong>!
    </p>

    <h3>Kumpulan Cerita</h3>
    <a href="tambah_cerita.php" class="button-tambah">Tulis Cerita Baru</a>

    <table class="table-cerita">
        <thead>
            <tr>
                <th>No.</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php // Pengecekan apakah ada data cerita
            if ($cerita instanceof mysqli_result && $cerita->num_rows > 0): ?>
                <?php $no = 1; ?>
                <?php while ($row = $cerita->fetch_assoc()): ?>
                    <tr>
                        <td data-label="No."><?php echo $no++; ?></td>
                        <td data-label="Judul"><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td data-label="Penulis"><?php echo htmlspecialchars($row['penulis']); ?></td>
                        <td data-label="Aksi">
                            <a href="edit_cerita.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="#" data-delete-url="hapus_cerita.php?id=<?php echo $row['id']; ?>"
                                class="btn-hapus confirm-delete-btn">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Anda belum menulis cerita. Ayo mulai menulis!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>