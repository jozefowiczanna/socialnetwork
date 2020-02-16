<?php 
  require_once("includes/initialize.php");
  require(FUNCTIONS_FILE);
  require(HEADER_FILE);
  
  if (!isset($userLoggedIn)) {
    header("Location: login.php");
  }

  if (isset($_GET['id'])) {
    $post_obj = new Post($con, $userLoggedIn);
    $post = $post_obj->loadSinglePost($_GET['id']);
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
      
      <div class="posts_area">
        <?php echo $post; ?>
      </div>

    </div><!-- panel--message -->
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