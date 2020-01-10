<?php 

require_once("../initialize.php");
require_once(CONFIG_FILE);
require_once(USER_FILE);

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

if (strpos($query, "_") !== false) {
  $userReturned = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
} else if (count($names) == 2) {
  $userReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%')AND user_closed='no' LIMIT 8");
} else {
  $userReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' OR last_name LIKE '%$names[0]%')AND user_closed='no' LIMIT 8");
}

if ($query != "") {
  $user_obj = new User($con, $userLoggedIn);
  $return_string = "";

  while ($row = mysqli_fetch_array($userReturned)) {
    if ($row['username'] != $userLoggedIn) {
      $mutual_friends = $user_obj->getMutualFriendsNum($row['username']);

      echo "
      <div class='conv'>
        <a href='messages.php?u=" . $row['username'] . "' class='conv__link'>
          <img src='" . $row['profile_pic'] . "' alt='profile picture' class='conv__img'>
          <div>
            <div class='conv__name'>" . $row['first_name'] . " " . $row['last_name'] . "</div>
            <div>" . $row['username'] . "</div>
            <div>" . $mutual_friends . " friends in common</div>
          </div>
        </a>
      </div>";
    }
  }
}

?>

