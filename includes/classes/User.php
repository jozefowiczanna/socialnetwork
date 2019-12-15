<?php 

  class User {
    private $user;
    private $con;

    public function __construct($con, $user) {
      $this->con = $con;
      //TODO test if "SELECT username" would work fine (but update remove & add friend)
      $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user'");
      $this->user = mysqli_fetch_array($user_details_query);
    }

    public function getUsername() {
      return $this->user['username'];
    }

    public function getNumPosts() {
      $username = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT num_posts FROM users WHERE username='$username'");
      $row = mysqli_fetch_array($query);
      return $row['num_posts'];
    }

    public function getNumLikes() {
      $username = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT num_likes FROM users WHERE username='$username'");
      $row = mysqli_fetch_array($query);
      return $row['num_likes'];
    }

    public function getNumFriends() {
      $username = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$username'");
      $row = mysqli_fetch_array($query);
      $num_friends = substr_count($row['friend_array'], ",") - 1;
      return $num_friends;
    }

    public function getFriendArray() {
      $username = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$username'");
      $row = mysqli_fetch_array($query);
      return $row['friend_array'];
    }

    public function getFirstAndLastName() {
      $username = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE username='$username'");
      $row = mysqli_fetch_array($query);
      return $row['first_name'] . " " . $row['last_name'];
    }

    public function getProfilePic() {
      $username = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT profile_pic FROM users WHERE username='$username'");
      $row = mysqli_fetch_array($query);
      return $row['profile_pic'];
    }

    public function isClosed() {
      $username = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT user_closed FROM users WHERE username='$username'");
      $row = mysqli_fetch_array($query);
      
      if ($row['user_closed'] == "yes") {
        return true;
      } else {
        return false;
      }
    }

    public function isFriend($username_to_check) {
      $username_comma = "," . $username_to_check . "," ;

      if (strstr($this->user['friend_array'], $username_comma) || $username_to_check == $this->user['username']) {
        return true;
      } else {
        return false;
      }
    }

    public function didReceiveRequest($user_from) {
      $logged_in_user = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$logged_in_user' AND user_from='$user_from'");
      if (mysqli_num_rows($query) > 0) {
        return true;
      } else {
        return false;
      }
    }

    public function didSendRequest($user_to) {
      $logged_in_user = $this->user['username'];
      $query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$logged_in_user'");
      if (mysqli_num_rows($query) > 0) {
        return true;
      } else {
        return false;
      }
    }

    public function removeFriend($user_to_remove) {
      $logged_in_user = $this->user['username'];

      // remove from friend list
      $query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$user_to_remove'");
      $row = mysqli_fetch_array($query);
      $friend_list = $row['friend_array'];
      $new_list = str_replace($logged_in_user . ",", "", $friend_list);
      $query = mysqli_query($this->con, "UPDATE users SET friend_array='$new_list' where username='$user_to_remove'");

      // remove from logged_in_user list
      $new_list = str_replace($user_to_remove . ",", "", $this->user['friend_array']);
      $query = mysqli_query($this->con, "UPDATE users SET friend_array='$new_list' where username='$logged_in_user'");

    }

    public function sendRequest($user_to) {
      $logged_in_user = $this->user['username'];
      $query = mysqli_query($this->con, "INSERT INTO friend_requests VALUES(NULL, '$user_to', '$logged_in_user')");
      
    }
  }

?>