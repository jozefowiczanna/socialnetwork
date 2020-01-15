const postsArea = document.querySelector(".posts_area");
const deletePostBtn = document.querySelector(".btn-delete-post-js");
const postModal = document.querySelector("#post-modal");
const modalCloseBtn = document.querySelector(".modal__close");
const modalCancelBtn = document.querySelector(".btn-cancel-js");

let postToBeDeletedId = null;

postsArea.addEventListener("click", function(e) {
  if (e.target.classList.contains("button--delete")) {
    postToBeDeletedId = e.target.dataset.id;
    postModal.classList.add("modal--is-active");
  }
}, false);

postModal.addEventListener("click", function(e) {
  e.preventDefault();
  if (e.target == modalCloseBtn || e.target == modalCancelBtn || e.target == this) {
    postToBeDeletedId = null;
    postModal.classList.remove("modal--is-active");
  } else if (e.target == deletePostBtn) {
    $.ajax({
      url: "includes/handlers/ajax_delete_post.php",
      type: "POST",
      data: "postId=" + postToBeDeletedId,
      cache: false,
      success: function () {
        postModal.classList.remove("modal--is-active");
        location.reload();
      },
      error: function() {
        alert('Failure');
      }
    });
  }
});

