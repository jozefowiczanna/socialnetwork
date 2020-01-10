<?php 
  require_once("includes/initialize.php");
  require(FUNCTIONS_FILE);
  require(HEADER_FILE);
  require(USER_FILE);
  require(POST_FILE);
  
  if (!isset($userLoggedIn)) {
    header("Location: login.php");
  }

  if (isset($_POST['post_button'])) {
    $post_obj = new Post($con, $userLoggedIn);
    $post_obj->submitPost($_POST['post_text'], 'none');
    header("Location: index.php");
  }
?>


<div class="wrapper">
  <div class="panels panels--user">
    <div class="panel panel--user">
      <div class="user__img" style="background-image: url('<?php echo $user['profile_pic'] ?>')">
      </div>
      <ul class="user__list">
        <li class="user__list-item"><a href="<?php echo $userLoggedIn ?>" class="user__full-name js-userLoggedIn"><?php echo $user['first_name'] . " " . $user['last_name'] ?></a></li>
        <li class="user__list-item">Posts: <?php echo $user['num_posts'] ?></li>
        <li class="user__list-item">Likes: <?php echo $user['num_likes'] ?></li>
      </ul>
    </div><!-- panel--user -->
    <div class="panel panel--message">
      <form action="index.php" class="post-form" method="post">
        <!-- TODO change to modal (overlapping nav on mobile when keyboard popups) -->
        <textarea name="post_text" class="post-form__text" id="" cols="30" rows="10"></textarea>
        <input type="submit" name="post_button" class="post-form__button" value="Post">
      </form>

      <div class="posts_area"></div>
      <img id="loading" src="assets/images/icons/loading.gif">

    </div><!-- panel--message -->
    <script>
      var userLoggedIn = '<?php echo $userLoggedIn; ?>';
      $(document).ready(function() {
        $('#loading').show();

        // Original ajax request for loading first posts

        $.ajax({
          url: "includes/handlers/ajax_load_posts.php",
          type: "POST",
          data: "page=1&userLoggedIn=" + userLoggedIn,
          cache: false,

          success: function(data) {
            $('#loading').hide();
            $('.posts_area').html(data);
          }
        });

        $(window).scroll(function(){
          var height = $('.posts_area').height(); // Div containing posts
          var scroll_top = $(this).scrollTop();
          var page = $('.posts_area').find('.nextPage').val();
          var noMorePosts = $('.posts_area').find('.noMorePosts').val();

          if ((document.body.scrollHeight == scroll_top + window.innerHeight) && noMorePosts == 'false') {
            $('#loading').show();

            var ajaxReq = $.ajax({
              url: "includes/handlers/ajax_load_posts.php",
              type: "POST",
              data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
              cache: false,

              success: function(response) {
                $('.posts_area').find('.nextPage').remove(); // Removes current .nextPage
                $('.posts_area').find('.noMorePosts').remove(); // Removes current .noMorePosts
                $('#loading').hide();
                $('.posts_area').append(response);
              }
            });
          }
        });
      });
    </script>
  </div><!-- panels -->
</div><!-- wrapper -->
<div id="post-modal" class="modal">
  <div class="modal__content modal__content--delete-post">
    <span class="modal__close">&times;</span>
    <h3 class="modal__title">Are you sure you want to delete this post?</h3>
    <form action="" method="POST" class="modal__form">
      <button class="button button--neutral btn-cancel-js">Cancel</button>
      <button type="submit" name="profile_post_button" class="button button--danger btn-delete-post-js">Delete</button>
    </form>
  </div>
</div>
<script src="assets/js/index.js"></script>
<?php 
  include "includes/footer.php";
?>