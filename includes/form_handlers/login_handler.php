<?php 
  $error_array = array();
  $log_email = "";

  if (isset($_POST['login_button'])) {
    $log_email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);
    $_SESSION['log_email'] = $log_email;
    $password = md5($_POST['log_password']);

    $check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$log_email' AND password='$password'");
    $check_login_query = mysqli_num_rows($check_database_query);

    if ($check_login_query == 1) {
      $row = mysqli_fetch_array($check_database_query);
      $username = $row['username'];

      $user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$log_email' AND user_closed='yes'");

      if (mysqli_num_rows($user_closed_query) == 1) {
        $reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$log_email'");
        echo mysqli_error($con);
      }

      $_SESSION['username'] = $username;
      header("Location: index.php");
      exit();


    } else {
      $error_array['invalid'] = "Invalid email or password";
    }
  }

?>