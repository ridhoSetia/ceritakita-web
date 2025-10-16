<?php

/**
 * Fungsi untuk me-render view dengan data dan membungkusnya dalam layout.
 *
 * @param string $view Nama file view yang akan di-render.
 * @param array $data Data yang akan diekstrak menjadi variabel di dalam view.
 * @param string $layout Nama file layout yang akan digunakan.
 */
function render(string $view, array $data = [], string $layout = 'app')
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $data['isLoggedIn'] = isset($_SESSION['user_id']);
    $data['username'] = $_SESSION['username'] ?? '';

    // Ambil flash message jika ada, lalu hapus
    if (isset($_SESSION['flash_message'])) {
        $data['flash_message'] = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
    }

    extract($data);

    ob_start();
    include "views/{$view}.php";
    if ($view == 'home') {
        ?>
        <html>
        <script>
            const angle_left = document.querySelector("aside > i");

            angle_left.onclick = () => {
                social_media.classList.toggle("active");
                angle_left.classList.toggle("fa-angle-left");
                angle_left.classList.toggle("fa-angle-right");
                angle_left.classList.toggle("active");
            };
        </script>

        </html>
        <?php
    }
    $content = ob_get_clean();

    // Sekarang, sertakan file layout utama
    include "layouts/{$layout}.php";
}