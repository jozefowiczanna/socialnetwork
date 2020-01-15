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
  <script src="assets/js/header.js"></script>

</head>
<body>

<?php 
require_once("includes/initialize.php");
require(CONFIG_FILE);
require(LOGIN_HANDLER_FILE);
?>

<div class="bg-image">
</div>
<div class="container">
  <div class="box">
    <form class="form form-login-js form--is-active" action="login.php" method="post">
      <h2 class="form__title">Sign in</h2>
      <input class="form__input" type="email" name="log_email" placeholder="Email" required value="<?php
      if(isset($_SESSION['log_email'])) {
        echo $_SESSION['log_email'];
      }
      ?>">
      <input class="form__input" type="password" name="log_password" placeholder="Password" required>
      <?php if (array_key_exists('invalid', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['invalid']}</p>";
      } ?>
      <input class="form__btn" type="submit" name="login_button" value="Login">
      <p class="form__message">
        Need an account? 
        <a href="register.php" class="form__link link-login-js">Sign up</a>
      </p>
    </form>
  </div>
</div>

<script src="assets/js/login.js"></script>

<?php 
  require 'includes/footer.php';
?>
</body>
</html>