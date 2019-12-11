<?php 

ob_start();
session_start();

$timezone = date_default_timezone_set("Europe/Warsaw");

$con = mysqli_connect('localhost', 'root', 'root', 'socialmedia');
mysqli_query($con, "SET CHARSET utf8");
mysqli_query($con, "SET NAMES `utf8` COLLATE `utf8_unicode_ci`");

if (mysqli_connect_errno()) {
  echo "Failed to connect to database.";
}

?>