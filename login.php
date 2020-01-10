<?php 
require_once("includes/initialize.php");
require(HEADER_FILE);
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