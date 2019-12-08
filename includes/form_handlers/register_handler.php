<?php 
  
$fname = "";
$lname = "";
$email = "";
$email2 = "";
$password = "";
$password2 = "";
$error_array = array();

if(isset($_POST['register_button'])) {

  //TODO replace with htmlspecialchars or use both
  // first name
  $fname = strip_tags($_POST['reg_fname']);
  $fname = str_replace(' ', '', $fname);
  $fname = ucfirst(strtolower($fname));
  $_SESSION['reg_fname'] = $fname;

  // last name
  $lname = strip_tags($_POST['reg_lname']);
  $lname = str_replace(' ', '', $lname);
  $lname = ucfirst(strtolower($lname));
  $_SESSION['reg_lname'] = $lname;

  // email
  $email = strip_tags($_POST['reg_email']);
  $email2 = strip_tags($_POST['reg_email2']);
  $_SESSION['reg_email'] = $email;
  $_SESSION['reg_email2'] = $email2;

  // password
  $password = strip_tags($_POST['reg_password']);
  $password2 = strip_tags($_POST['reg_password2']);

  // date
  $date = date("Y-m-d");

  // validate values
  if (strlen($fname) > 25 || strlen($fname) < 2) {
    array_push($error_array, "First name must be between 2 and 25 characters") ;
  }

  if (strlen($lname) > 25 || strlen($lname) < 2) {
    array_push($error_array, "Last name must be between 2 and 25 characters") ;
  }

  // validate email
  if ($email != $email2) {
    array_push($error_array, "Emails don't match");
  } else {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email = filter_var($email, FILTER_VALIDATE_EMAIL);
      // check if email already exists
      $email_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");
      $num_rows = mysqli_num_rows($email_check);

      if ($num_rows > 0) {
        array_push($error_array, "Email already in use");
      }

    } else {
      array_push($error_array, "Invalid email format");
    }
  }

  if ($password != $password2) {
    array_push($error_array, "Passwords don't match");
  } else {
    if (preg_match('/[^A-Za-z0-9]/', $password)) {
      array_push($error_array, "Your password must contain only english characters or numbers");
    }
  }

  if(strlen($password) > 30 || strlen($password) < 5) {
    array_push($error_array, "Your password must be between 5 and 30 characters");
  }

  // send form
  if(empty($error_array)) {
    $password = md5($password); // encrypt password before sending to database
    // $password = password_hash($password, PASSWORD_BCRYPT); // encrypt password before sending to database
    
    $username = strtolower($fname . "_" . $lname);
    $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

    $i = 1;
    $temp_username = "";
    // if username exists add number to username
    while(mysqli_num_rows($check_username_query) != 0) {
      $i++;
      $temp_username = $username . "_" . $i;
      $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$temp_username'");
    }
    if (!empty($temp_username)) {
      $username = $temp_username;
    }

    // profile picture assignment
    $rand = rand(1, 2);
  
    if ($rand == 1) {
      $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
    } else if ($rand == 2) {
      $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
    }
    $sql = "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')";
    $query = mysqli_query($con, $sql);
    $msg = "<p style=\"color: green\">Profile registered</p>";
  }
}

?>