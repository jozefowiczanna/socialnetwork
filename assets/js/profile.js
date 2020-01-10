if (document.querySelector(".open-modal-js")) {
  const openModalBtn = document.querySelector(".open-modal-js");
  const modal = document.querySelector("#profile-modal");
  const closeXBtn = document.querySelector(".modal__close");
  const closeBtn = document.querySelector(".btn-close-js");
  const postBtn = document.querySelector(".btn-profile-post-js");
  
  openModalBtn.addEventListener("click", function(e) {
    e.preventDefault();
    modal.classList.add("modal--is-active");
  });
  
  modal.addEventListener("click", function(e) {
    e.preventDefault();
    if (e.target === closeXBtn || e.target === closeBtn) {
      modal.classList.remove("modal--is-active");
    }
  });
  
  postBtn.addEventListener("click", function(e) {
    $.ajax({
      url: "includes/handlers/ajax_submit_profile_post.php",
      type: "POST",
      data: $('form.modal__form').serialize(),
      success: function () {
        modal.classList.remove("modal--is-active");
        location.reload();
      },
      error: function() {
        alert('Failure');
      }
    });
  });
}
