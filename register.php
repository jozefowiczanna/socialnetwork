<?php 
require 'includes/header.php';
require 'includes/form_handlers/register_handler.php';
?>

<div class="bg-image">
</div>
<div class="container">
  <div class="box">
    <form class="form form-register-js" action="register.php" method="post">
      <h2 class="form__title">Sign up</h2>
      <input class="form__input" type="text" name="reg_fname" placeholder="First Name" required value="<?php
      if (isset($_SESSION['reg_fname'])) {
        echo $_SESSION['reg_fname'];
      }
      ?>">
      <?php
      if (array_key_exists('first_name_length', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['first_name_length']}</p>";
      }
      ?>
      <input class="form__input" type="text" name="reg_lname" placeholder="Last Name" required value="<?php
      if (isset($_SESSION['reg_lname'])) {
        echo $_SESSION['reg_lname'];
      }
      ?>">
      <?php
      if (array_key_exists('last_name_length', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['last_name_length']}</p>";
      }
      ?>
      <input class="form__input" type="email" name="reg_email" placeholder="Email" required value="<?php
      if (isset($_SESSION['reg_email'])) {
        echo $_SESSION['reg_email'];
      }
      ?>">
      
      <input class="form__input" type="email" name="reg_email2" placeholder="Confirm Email" required value="<?php
      if (isset($_SESSION['reg_email2'])) {
        echo $_SESSION['reg_email2'];
      }
      ?>">
      <?php
      if (array_key_exists('email_use', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['email_use']}</p>";
      } else if (array_key_exists('email_invalid', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['email_invalid']}</p>";
      } else if (array_key_exists('email_match', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['email_match']}</p>";
      }
      ?>
      <input class="form__input" type="password" name="reg_password" placeholder="Password" required>
      <input class="form__input" type="password" name="reg_password2" placeholder="Confirm Password" required>
      <?php
      if (array_key_exists('password_match', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['password_match']}</p>";
      } else if (array_key_exists('password_regex', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['password_regex']}</p>";
      } else if (array_key_exists('password_length', $error_array)) {
        echo "<p class=\"form__error\">{$error_array['password_length']}</p>";
      }

      //TODO remove msg if instant redirection
      if(isset($msg_registered)) {
        echo $msg_registered;
      }
      ?>

    
      <input class="form__btn" type="submit" name="register_button" value="Register">
      <p class="form__message">
        Already have an account? 
        <a href="login.php" class="form__link link-register-js">Sign in</a>
      </p>
    </form>
  </div>
</div>

<?php 
  require 'includes/footer.php';
?>