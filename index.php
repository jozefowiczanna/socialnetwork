<?php 
  include "includes/header.php";
  include "includes/classes/User.php";
  include "includes/classes/Post.php";

  if (!isset($userLoggedIn)) {
    header("Location: login.php");
  }

  // $user_obj = new User($con, $userLoggedIn);

  if (isset($_POST['post_button'])) {
    $post_obj = new Post($con, $userLoggedIn);
    $post_obj->submitPost($_POST['post_text'], 'none');
    header("Location: index.php");
  }
?>
<!--TODO remove branding from mobile (html, css, js) -->
<nav class="nav">
  <div class="wrapper">
    <div class="nav__container">
      <ul class="nav__menu">
        <li class="menu__item">
          <a href="#" class="menu__link">
            <img src="assets/images/fontawesome/envelope-regular.svg" alt="icon" class="menu__icon">
          </a>
        </li>
        <li class="menu__item">
          <a href="#" class="menu__link">
            <img src="assets/images/fontawesome/home-solid.svg" alt="icon" class="menu__icon">
          </a>
        </li>
        <li class="menu__item">
          <a href="#" class="menu__link">
            <img src="assets/images/fontawesome/bell-regular.svg" alt="icon" class="menu__icon">
          </a>
        </li>
        <li class="menu__item">
          <a href="#" class="menu__link">
            <img src="assets/images/fontawesome/users-solid.svg" alt="icon" class="menu__icon">
          </a>
        </li>
        <li class="menu__item">
          <a href="#" class="menu__link">
            <img src="assets/images/fontawesome/cog-solid.svg" alt="icon" class="menu__icon">
          </a>
        </li>
        <li class="menu__item">
          <a href="includes/handlers/logout.php" class="menu__link">
            <img src="assets/images/fontawesome/sign-out-alt-solid.svg" alt="icon" class="menu__icon">
          </a>
        </li>
      </ul>
      <div class="nav__search">
        <input type="text" class="nav__search-input">
      </div>
    </div>
  </div>
</nav>

<div class="under-nav"></div>
<div class="wrapper">
  <div class="panels">
    <div class="panel panel--user">
    <div class="user__img" style="background-image: url('assets/images/profile_pics/defaults/woman.jpeg')"></div>
    <ul class="user__list">
      <li class="user__list-item"><a href="<?php echo $userLoggedIn ?>" class="user__full-name"><?php echo $user['first_name'] . " " . $user['last_name'] ?></a></li>
      <li class="user__list-item">Posts: <?php echo $user['num_posts'] ?></li>
      <li class="user__list-item">Likes: <?php echo $user['num_likes'] ?></li>
    </ul>
    </div><!-- user-panel -->
    <div class="panel panel--message">
      <form action="index.php" class="post-form" method="post">
        <!-- TODO change to modal (overlapping nav on mobile when keyboard popups) -->
        <textarea name="post_text" class="post-form__text" id="" cols="30" rows="10"></textarea>
        <input type="submit" name="post_button" class="post-form__button" value="Post">
      </form>

      <?php 
      
      $post_obj = new Post($con, $userLoggedIn);
      $post_obj->loadPostsFriends();
      ?>

    </div>
  </div><!-- panels -->
</div><!-- wrapper -->

<script src="assets/js/script.js"></script>
<?php 
  include "includes/footer.php";
?>