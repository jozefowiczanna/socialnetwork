<?php 

$error_array = array();
  
$fname = "";
$lname = "";
$email = "";
$email2 = "";
$password = "";
$password2 = "";

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
    $error_array['first_name_length'] = "First name must be between 2 and 25 characters";
  }

  if (strlen($lname) > 25 || strlen($lname) < 2) {
    $error_array['last_name_length'] = "Last name must be between 2 and 25 characters";
  }

  // validate email
  if ($email != $email2) {
    $error_array['email_match'] = "Emails don't match";
  } else {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email = filter_var($email, FILTER_VALIDATE_EMAIL);
      // check if email already exists
      $email_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");
      $num_rows = mysqli_num_rows($email_check);

      if ($num_rows > 0) {
        $error_array['email_use'] = "Email already in use";
      }

    } else {
      $error_array['email_invalid'] = "Invalid email format";
    }
  }

  if ($password != $password2) {
    $error_array['password_match'] = "Passwords don't match";
    // array_push($error_array, "Passwords don't match");
  } else {
    if (preg_match('/[^A-Za-z0-9]/', $password)) {
      $error_array['password_regex'] = "Your password must contain only english characters or numbers";
    }
  }

  if(strlen($password) > 30 || strlen($password) < 5) {
    $error_array['password_length'] = "Your password must be between 5 and 30 characters";
  }

  // send form
  if(empty($error_array)) {
    $password = md5($password); // encrypt password before sending to database
    //TODO $password = password_hash($password, PASSWORD_BCRYPT); // encrypt password before sending to database
    
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
    $msg_registered = "<p style=\"color: green\">Profile registered</p>";
  }
}

?>