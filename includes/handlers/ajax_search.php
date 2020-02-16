<?php 

  require_once("../initialize.php");
  require_once(CONFIG_FILE);
  require_once(USER_FILE);

  $names = mysqli_real_escape_string($con, $_REQUEST['q']);
  
  if (strlen($names) > 0 || $names != " ") {
    $names = explode(" ", $names);
  
    if (count($names) == 1) {
      $query = mysqli_query($con, "SELECT * FROM users WHERE first_name LIKE '%$names[0]%' OR last_name LIKE '%$names[0]%'");
    } else if (count($names) == 2) {
      $query = mysqli_query($con, "SELECT * FROM users WHERE first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%'");
    }
  
    $str = "";
  
    while ($row = mysqli_fetch_array($query)) {
      $user_obj = new User($con, $row['username']);
      $fullname = $user_obj->getFirstAndLastName();
      $str .= "<div class='result'>$fullname</div>";
    }
  
    echo $str;
  } else {
    echo "";
  }

?>