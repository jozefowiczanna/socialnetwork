const nav = document.querySelector(".nav");
let navIconsPosition = document.querySelector(".nav__menu").offsetTop - 5;

const moveNav = () => {
  if (window.pageYOffset >= navIconsPosition && window.innerWidth <= 600) {
    nav.style.transform = `translateY(-${navIconsPosition}px)`;
  } else {
    nav.style.transform = `translateY(0px)`;
  }
}

window.addEventListener("resize", function() {
  navIconsPosition = document.querySelector(".nav__menu").offsetTop - 5;
  moveNav();
});

window.addEventListener("scroll", function() {
  moveNav();
});

window.addEventListener("touchmove", function() {
  moveNav();
});

