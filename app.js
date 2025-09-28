const nav = document.querySelector("nav");
const navLinks = document.querySelectorAll("nav ul a");
const social_media = document.querySelector(".social-media");
const angle_left = document.querySelector("aside > i");
const hamburgerMenu = document.querySelector("#menu-icon");
const navUl = document.querySelector("nav ul");

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

window.addEventListener('scroll', () => {
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

angle_left.onclick = () => {
  social_media.classList.toggle("active");
  angle_left.classList.toggle("fa-angle-left");
  angle_left.classList.toggle("fa-angle-right");
  angle_left.classList.toggle("active");
};

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
    .catch(error => {
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