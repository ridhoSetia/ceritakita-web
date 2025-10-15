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
            if (body.classList.contains("dark-mode")) {
                localStorage.setItem("theme", "dark-mode");
            } else {
                localStorage.removeItem("theme");
            }
        };
    }

    // Fetch random quote for the footer
    const quoteEl = document.querySelector("#quote");
    const authorEl = document.querySelector("#author");
    const quoteUrl = "https://api.quotable.io/random";

    const getQuote = () => {
        fetch(quoteUrl)
            .then((response) => response.json())
            .then((data) => {
                if (quoteEl && authorEl) {
                    quoteEl.innerText = `"${data.content}"`;
                    authorEl.innerText = `— ${data.author}`;
                }
            })
            .catch(() => {
                if (quoteEl && authorEl) {
                    quoteEl.innerText = '"The best way to predict the future is to create it."';
                    authorEl.innerText = "— Peter Drucker";
                }
            });
    };

    if (quoteEl && authorEl) {
        getQuote();
    }
});