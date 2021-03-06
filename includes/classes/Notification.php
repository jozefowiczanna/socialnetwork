<?php 

class Notification {
  private $user_obj;
  private $con;

  public function __construct($con, $user) {
    $this->con = $con;
    $this->user_obj = new User($con, $user);
  }

  public function getUnreadNumber() {
    $userLoggedIn = $this->user_obj->getUsername();
    $query = mysqli_query($this->con, "SELECT * FROM notifications WHERE viewed='no' AND user_to='$userLoggedIn'");
    return mysqli_num_rows($query);
  }

  public function getNotifications($data, $limit) {
    $page = $data['page'];
    $userLoggedIn = $this->user_obj->getUsername();
    $return_string = "";

    if ($page == 1) {
      $start = 0;
    } else {
      $start = ($page - 1) * $limit;
    }

    $set_viewed_query = mysqli_query($this->con, "UPDATE notifications SET viewed='yes' WHERE user_to='$userLoggedIn'");

    $query = mysqli_query($this->con, "SELECT * FROM notifications WHERE user_to='$userLoggedIn' ORDER BY id DESC");

    if (mysqli_num_rows($query) == 0) {
      echo "You have no notifications";
      return;
    }

    $num_iterations = 0; // Number of messages checked
    $count = 1; // Number of messages posted

    while ($row = mysqli_fetch_array($query)) {

      if ($num_iterations++ < $start) {
        continue;
      }

      if ($count > $limit) {
        break;
      } else {
        $count++;
      }

      $user_from = $row['user_from'];

      $user_query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$user_from'");
      $user_data = mysqli_fetch_array($user_query);

      $time_message = get_timeframe($row['datetime']);



      
      $opened = $row['opened'];
      $class = ($opened == 'no') ? "conv--unopened" : "";

      $return_string .= "
      <div class='conv $class'>
        <a href='" . $row['link'] . "' class='conv__link'>
          <img src='" . $user_data['profile_pic'] . "' alt='profile picture' class='conv__img'>
          <div>
            <div>" . $time_message . "</div>
            <div class='conv__name'>" . $row['message'] . "</div>
          </div>
        </a>
      </div>";
    }

    // if posts were loaded

    if ($count > $limit) {
      $return_string .= "<input type='hidden' class='nextPageDropdownData' value='" . ($page + 1) . "'><input type='hidden' class='noMoreDropdownData' value='false'>";
    } else {
      $return_string .= "<input type='hidden' class='noMoreDropdownData' value='true'><p style='text-align: center;'>No more messages to load!</p>";
    }

    return $return_string;
  }

  public function insertNotification($post_id, $user_to, $type) {
    $userLoggedIn = $this->user_obj->getUsername();
    $userLoggedInName = $this->user_obj->getFirstAndLastName();

    switch ($type) {
      case "comment":
        $message = $userLoggedInName . " commented on your post";
        break;
      case "like":
        $message = $userLoggedInName . " liked your post";
        break;
      case "profile_post":
        $message = $userLoggedInName . " posted on your profile";
        break;
      case "comment_non_owner":
        $message = $userLoggedInName . " commented on a post you commented on";
        break;
      case "profile_comment":
        $message = $userLoggedInName . " commented on your profile post";
        break;
    }

    $link = "post.php?id=" . $post_id;
    $date_time = date('Y-m-d H:i:s');

    $insert_query = mysqli_query($this->con, "INSERT INTO notifications VALUES(NULL, '$user_to', '$userLoggedIn', '$message', '$link', '$date_time', 'no', 'no')");
  }
}

?>