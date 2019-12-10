<?php 
  include "includes/header.php";
  // session_destroy();
?>

<div class="nav">
  <div class="wrapper">
    <div class="nav__container">
      <div class="nav__branding">
        <a href="" class="nav__logo">Swirlfeed!</a>
        <a href="#" class="nav__username">Hi <?php echo $user['first_name'] ?>!</a>
      </div>
      <ul class="nav__menu">
        <li class="menu__item"><a href="#" class="menu__link"><i class="far fa-envelope"></i></a></li>
        <li class="menu__item"><a href="#" class="menu__link"><i class="fas fa-home"></i></a></li>
        <li class="menu__item"><a href="#" class="menu__link"><i class="far fa-bell"></i></a></li>
        <li class="menu__item"><a href="#" class="menu__link"><i class="fas fa-users"></i></a></li>
        <li class="menu__item"><a href="#" class="menu__link"><i class="fas fa-cog"></i></a></li>
        <li class="menu__item"><a href="login.php" class="menu__link"><i class="fas fa-sign-out-alt"></i></a></li>
      </ul>
      <div class="nav__search">
        <input type="text" class="nav__search-input">
      </div>
    </div>
  </div>
</div>

<?php 
  include "includes/footer.php";
?>