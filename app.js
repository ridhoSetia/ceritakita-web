document.addEventListener("DOMContentLoaded", () => {
    const nav = document.querySelector("nav");
    const hamburgerMenu = document.querySelector("#menu-icon");
    const navUl = document.querySelector("nav ul");
    const themeSwitcher = document.querySelector(".theme-switcher");
    const body = document.body;

    // Sticky navigation on scroll
    window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
            nav.classList.add("scrolled");
        } else {
            nav.classList.remove("scrolled");
        }
    });

    // Mobile menu toggle
    if (hamburgerMenu) {
        hamburgerMenu.onclick = () => {
            hamburgerMenu.classList.toggle("bx-x");
            navUl.classList.toggle("open");
        };
    }

    // Theme switcher logic
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark-mode") {
        body.classList.add("dark-mode");
    }
    if (themeSwitcher) {
        themeSwitcher.onclick = () => {
            body.classList.toggle("dark-mode");
            localStorage.setItem("theme", body.classList.contains("dark-mode") ? "dark-mode" : "");
        };
    }

    // --- LOGIKA MODAL POP-UP ---

    // 1. Modal Notifikasi (Untuk pesan sukses/error)
    const notificationModal = document.getElementById('notification-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalCloseBtn = document.getElementById('modal-close-btn');

    function showNotificationModal(title, message, type = 'success') {
        if (!notificationModal) return;
        modalTitle.textContent = title;
        modalMessage.textContent = message;

        // Reset style sebelum menerapkan yang baru
        modalTitle.style.color = '';
        modalCloseBtn.classList.remove('danger');

        if (type === 'error') {
            modalTitle.style.color = '#e74c3c'; // Warna judul merah
            modalCloseBtn.classList.add('danger'); // Tambahkan kelas .danger ke tombol
        } else {
            modalTitle.style.color = '#27ae60'; // Warna judul hijau (sukses)
            // Tombol akan menggunakan style default (hijau)
        }

        notificationModal.style.display = 'flex';
        setTimeout(() => notificationModal.classList.add('show'), 10);
    }

    function hideNotificationModal() {
        if (!notificationModal) return;
        notificationModal.classList.remove('show');
        
        // Cleanup style setelah animasi fade-out selesai
        setTimeout(() => {
            notificationModal.style.display = 'none';
            
            // Reset style modal untuk penggunaan berikutnya
            modalTitle.style.color = '';
            modalCloseBtn.classList.remove('danger');
        }, 300); // 300ms harus sama dengan durasi transisi di CSS
    }

    if (modalCloseBtn) {
        modalCloseBtn.onclick = hideNotificationModal;
    }
    
    // Cek flash message dari PHP dan tampilkan modal
    const flashMessageData = document.body.dataset.flashMessage;
    if (flashMessageData) {
        try {
            const flash = JSON.parse(flashMessageData);
            const title = flash.type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan';
            showNotificationModal(title, flash.message, flash.type);
        } catch (e) {
            console.error("Gagal parsing flash message:", e);
        }
    }

    // 2. Modal Konfirmasi (Untuk hapus cerita)
    const confirmationModal = document.getElementById('confirmation-modal');
    const confirmDeleteLink = document.getElementById('confirm-delete-btn');
    const confirmCancelBtn = document.getElementById('confirm-cancel-btn');
    const allDeleteLinks = document.querySelectorAll('a[data-delete-url]');

    function showConfirmationModal(deleteUrl) {
        if (!confirmationModal || !confirmDeleteLink) return;
        confirmDeleteLink.href = deleteUrl;
        confirmationModal.style.display = 'flex';
        setTimeout(() => confirmationModal.classList.add('show'), 10);
    }
    
    function hideConfirmationModal() {
        if (!confirmationModal) return;
        confirmationModal.classList.remove('show');
        setTimeout(() => confirmationModal.style.display = 'none', 300);
    }

    allDeleteLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const url = link.dataset.deleteUrl;
            showConfirmationModal(url);
        });
    });

    if (confirmCancelBtn) {
        confirmCancelBtn.onclick = hideConfirmationModal;
    }
    
    // Sembunyikan modal jika klik di luar area konten
    window.addEventListener('click', (e) => {
        if (e.target === notificationModal) hideNotificationModal();
        if (e.target === confirmationModal) hideConfirmationModal();
    });

    // --- LOGIKA VALIDASI UKURAN FILE CLIENT-SIDE ---
    // Kita target form dengan class .story-form, yang ada di tambah & edit
    const storyForm = document.querySelector('.story-form'); 
    
    if (storyForm) {
        storyForm.addEventListener('submit', function(event) {
            const fileInput = document.getElementById('gambar'); // ID input file
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxFileSize = 2 * 1024 * 1024; // 2 MB (batas yang kita inginkan)

                if (file.size > maxFileSize) {
                    // 1. Hentikan submit form
                    event.preventDefault(); 
                    
                    // 2. Tampilkan modal error
                    showNotificationModal(
                        'Terjadi Kesalahan', 
                        'Ukuran file terlalu besar. Maksimal adalah 2 MB.', 
                        'error'
                    );
                }
            }
        });
    }

    // --- Logika Quote (Tetap Sama) ---
    const quoteEl = document.querySelector("#quote");
    const authorEl = document.querySelector("#author");
    if (quoteEl && authorEl) {
        fetch("https://api.quotable.io/random")
            .then((res) => res.json())
            .then((data) => {
                quoteEl.innerText = `"${data.content}"`;
                authorEl.innerText = `— ${data.author}`;
            })
            .catch(() => {
                quoteEl.innerText = '"The best way to predict the future is to create it."';
                authorEl.innerText = "— Peter Drucker";
            });
    }
});