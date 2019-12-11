<?php 
  include "includes/header.php";
  // session_destroy();

  if (isset($user['first_name'])) {
    $user_first_name = $user['first_name'];
  } else {
    $user_first_name = "Guest";
  }
?>

<div class="nav">
  <div class="wrapper">
    <div class="nav__container">
      <div class="nav__branding">
        <a href="" class="nav__logo">Swirlfeed!</a>
        <a href="#" class="nav__username">Hi <?php echo $user_first_name ?>!</a>
      </div>
      <!-- <ul class="nav__menu">
        <li class="menu__item"><a href="#" class="menu__link"><i class="far fa-envelope"></i></a></li>
        <li class="menu__item"><a href="#" class="menu__link"><i class="fas fa-home"></i></a></li>
        <li class="menu__item"><a href="#" class="menu__link"><i class="far fa-bell"></i></a></li>
        <li class="menu__item"><a href="#" class="menu__link"><i class="fas fa-users"></i></a></li>
        <li class="menu__item"><a href="#" class="menu__link"><i class="fas fa-cog"></i></a></li>
        <li class="menu__item"><a href="login.php" class="menu__link"><i class="fas fa-sign-out-alt"></i></a></li>
      </ul> -->

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
          <a href="login.php" class="menu__link">
            <img src="assets/images/fontawesome/sign-out-alt-solid.svg" alt="icon" class="menu__icon">
          </a>
        </li>
      </ul>
      <div class="nav__search">
        <input type="text" class="nav__search-input">
      </div>
    </div>
  </div>
</div>

<div class="under-nav"></div>
<p>This is profile page.</p>
<script src="assets/js/script.js"></script>
<?php 
  include "includes/footer.php";
?>