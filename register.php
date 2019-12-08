<?php 

require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Social media</title>
</head>
<body>

<div class="wrapper">
  <div class="container">
    <!-- login -->
    <form class="form form-login-js form--is-active" action="register.php" method="post">
      <h2 class="form__title">Sign in</h2>
      <input class="form__input" type="email" name="log_email" placeholder="Email" required value="<?php
      if(isset($_SESSION['log_email'])) {
        echo $_SESSION['log_email'];
      }
      ?>">
      <input class="form__input" type="password" name="log_password" placeholder="Password" required>
      <?php if (in_array("Invalid email or password", $error_array)) {
        echo "<p class=\"form__error\">Invalid email or password</p>";
      } ?>
      <input class="form__btn" type="submit" name="login_button" value="Login">
      <p class="form__message">
        Need an account? 
        <a href="#" class="form__link link-login-js">Sign up</a>
      </p>
    </form>
    
    <!-- register -->
    <form class="form form-register-js" action="register.php" method="post">
      <h2 class="form__title">Sign up</h2>
      <input class="form__input" type="text" name="reg_fname" placeholder="First Name" required value="<?php
      if (isset($_SESSION['reg_fname'])) {
        echo $_SESSION['reg_fname'];
      }
      ?>">
      <?php if(in_array("First name must be between 2 and 25 characters", $error_array)) echo "<p class=\"form__error\">First name must be between 2 and 25 characters</p>" ?>
      
      <input class="form__input" type="text" name="reg_lname" placeholder="Last Name" required value="<?php
      if (isset($_SESSION['reg_lname'])) {
        echo $_SESSION['reg_lname'];
      }
      ?>">
      
      <?php if(in_array("Last name must be between 2 and 25 characters", $error_array)) echo "Last name must be between 2 and 25 characters" . "" ?>
    
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
      if(in_array("Email already in use", $error_array)) { 
        echo "Email already in use" . "";
      } else if(in_array("Invalid email format", $error_array)) { 
        echo "Invalid email format" . "";
      } else if(in_array("Emails don't match", $error_array)) { 
        echo "Emails don't match" . "";
      }?>
      <input class="form__input" type="password" name="reg_password" placeholder="Password" required>
      <input class="form__input" type="password" name="reg_password2" placeholder="Confirm Password" required>
      <?php 
        if (in_array("Passwords don't match", $error_array)) {
          echo "Passwords don't match" . "";
        } else if(in_array("Your password must contain only english characters or numbers", $error_array)) { 
          echo "Your password must contain only english characters or numbers" . "";
        } else if(in_array("Your password must be between 5 and 30 characters", $error_array)) { 
          echo "Your password must be between 5 and 30 characters" . "";
        }
    
        if(isset($msg)) {
          echo $msg;
        }
      ?>
    
      <input class="form__btn" type="submit" name="register_button" value="Register">
      <p class="form__message">
        Already have an account? 
        <a href="#" class="form__link link-register-js">Sign in</a>
      </p>
    </form>
  </div>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>