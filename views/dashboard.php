<div class="auth-container dashboard-container">
    <h2>Selamat Datang!</h2>
    <p>
        Halo, <strong class="username"><?php echo htmlspecialchars($username); ?></strong>! Anda berhasil login.
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
            <?php
            $no = 1;
            // Looping untuk menampilkan data cerita
            while ($row = $cerita->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['judul']); ?></td>
                    <td><?php echo htmlspecialchars($row['penulis']); ?></td>
                    <td>
                        <a href="edit_cerita.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="hapus_cerita.php?id=<?php echo $row['id']; ?>" class="btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus cerita ini?');">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
</div>