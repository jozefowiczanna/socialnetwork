<?php 
require_once("includes/initialize.php");
require_once(CONFIG_FILE);

if (isset($_SESSION['username'])) {
  $userLoggedIn = $_SESSION['username'];
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
  $user = mysqli_fetch_array($user_details_query);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Social media</title>
  <!-- <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script> -->
  <script src="assets/js/jquery-3.4.1.min.js"></script>
  <script src="assets/js/jquery.Jcrop.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>

</head>
<body>

<nav class="custom-nav">
  <div class="wrapper">
    <div class="custom-nav__container">
      <ul class="custom-nav__menu">
        <li class="menu__item">
          <a href="messages.php" class="menu__link">
            <img src="assets/images/fontawesome/envelope-regular.svg" alt="icon" class="menu__icon">
          </a>
        </li>
        <li class="menu__item">
          <a href="index.php" class="menu__link">
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
      <div class="custom-nav__search">
        <input type="text" class="custom-nav__search-input">
      </div>
    </div>
  </div>
</nav>

<div class="under-nav"></div>

