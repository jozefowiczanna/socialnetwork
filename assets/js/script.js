const postBtn = document.querySelector(".post-on-profile-js");
const modal = document.querySelector("#profile-modal");
const closeXBtn = document.querySelector(".modal__close");
const closeBtn = document.querySelector(".btn-close-js");

postBtn.addEventListener("click", function(e) {
  e.preventDefault();
  modal.classList.add("modal--is-active");
});

modal.addEventListener("click", function(e) {
  e.preventDefault();
  if (e.target === closeXBtn || e.target === closeBtn) {
    modal.classList.remove("modal--is-active");
  }
});