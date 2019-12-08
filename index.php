<?php 

require 'config/config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Social media</title>
</head>
<body>
  <p><?php if(isset($_SESSION['username'])) {
    echo "Hello " . $_SESSION['username'];
  } else {
    echo "Hello X";
  }?></p>
  <a href="register.php">Register</a>
</body>
</html>