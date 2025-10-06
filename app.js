const nav = document.querySelector("nav");
const navLinks = document.querySelectorAll("nav ul a");
const social_media = document.querySelector(".social-media");
const hamburgerMenu = document.querySelector("#menu-icon");
const navUl = document.querySelector("nav ul");
const themeSwitcher = document.querySelector(".theme-switcher");
const body = document.body;
const authContainer = document.querySelector(".auth-container");
const authForm = document.querySelector(".auth-form");

// At the top of your file
const savedTheme = localStorage.getItem("theme");
if (savedTheme === "dark-mode") {
  body.classList.add("dark-mode");
  // Also apply theme to other elements if they exist
  if (authContainer) authContainer.classList.add("dark-mode");
  if (authForm) authForm.classList.add("dark-mode");
}

// Your theme switcher function
themeSwitcher.onclick = () => {
  // Toggle the class on all relevant elements
  body.classList.toggle("dark-mode");
  if (authContainer) authContainer.classList.toggle("dark-mode");
  if (authForm) authForm.classList.toggle("dark-mode");

  // Save or remove the single theme key from localStorage
  if (body.classList.contains("dark-mode")) {
    localStorage.setItem("theme", "dark-mode");
  } else {
    localStorage.removeItem("theme");
  }
};

hamburgerMenu.onclick = () => {
  hamburgerMenu.classList.toggle("bx-x");
  navUl.classList.toggle("open");

  if (!navUl.classList.contains("open")) {
    navUl.classList.add("beforeOpen");
    setTimeout(() => {
      navUl.classList.remove("beforeOpen");
    }, 550);
  } else {
    navUl.classList.remove("beforeOpen");
  }

  setTimeout(() => {
    navLinks.forEach((a) => {
      if (navUl.classList.contains("open")) {
        a.classList.add("open");
      } else {
        a.classList.remove("open");
      }
    });
  }, 100);
};

window.addEventListener("scroll", () => {
  if (navUl.classList.contains("open")) {
    hamburgerMenu.classList.remove("bx-x");
    navUl.classList.remove("open");

    navUl.classList.add("beforeOpen");
    setTimeout(() => {
      navUl.classList.remove("beforeOpen");
    }, 550);

    navLinks.forEach((a) => {
      a.classList.remove("open");
    });
  }
});

const quote = document.querySelector("#quote");
const author = document.querySelector("#author");
const url = "https://api.quotable.io/random";

const getQuote = () => {
  fetch(url)
    .then((data) => data.json())
    .then((item) => {
      if (quote && author) {
        quote.innerText = item.content;
        author.innerText = item.author;
      }
    })
    .catch((error) => {
      console.error("Could not fetch quote:", error);
      if (quote) {
        quote.innerText = "The best way to predict the future is to create it.";
      }
      if (author) {
        author.innerText = "Peter Drucker";
      }
    });
};

window.addEventListener("load", getQuote);
