<?php 

  class Message {
    private $user_obj;
    private $con;

    public function __construct($con, $user) {
      $this->con = $con;
      $this->user_obj = new User($con, $user);
    }

    public function getMostRecentUser() {
      $userLoggedIn = $this->user_obj->getUsername();

      $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC LIMIT 1");

      if (mysqli_num_rows($query) == 0) {
        return false;
      }

      $row = mysqli_fetch_array($query);
      $user_to = $row['user_to'];
      $user_from = $row['user_from'];

      if ($user_to != $userLoggedIn) {
        return $user_to;
      } else {
        return $user_from;
      }
    }

    public function sendMessage($user_to, $body, $date) {
      $userLoggedIn = $this->user_obj->getUsername();
      $body = strip_tags($body);
      $body = str_replace('\r\n', '\n', $body);
      $body = nl2br($body);
      $body = mysqli_real_escape_string($this->con, $body);
      $query = mysqli_query($this->con, "INSERT INTO messages VALUES(NULL, '$user_to', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
    }

    public function getMessages($otherUser) {
      $userLoggedIn = $this->user_obj->getUsername();
      $data = "";

      $query = mysqli_query($this->con, "UPDATE messages SET opened='yes' WHERE user_to='$userLoggedIn' AND user_from='$otherUser'");

      $get_messages_query = mysqli_query($this->con, "SELECT * FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$otherUser') OR (user_to='$otherUser' AND user_from='$userLoggedIn')");

      while ($row = mysqli_fetch_array($get_messages_query)) {
        $user_to = $row['user_to'];
        $user_from = $row['user_from'];
        $body = $row['body'];

        $class_modifier = ($user_to == $userLoggedIn) ? "primary" : "secondary";
        $data = $data
        . "<div class='message'>
            <div class='message__body message__body--$class_modifier'>$body</div><br>
          </div>";
      }

      return $data;
    }

    public function getLatestMessage($userLoggedIn, $username) {
      $details_array = array();
      $query = mysqli_query($this->con, "SELECT user_to, body, date FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$username') OR (user_to='$username' AND user_from='$userLoggedIn') ORDER BY id DESC LIMIT 1");
      
      $row = mysqli_fetch_array($query);
      $sent_by = ($row['user_to'] == $userLoggedIn) ? "They said: " : "You said: ";
      $date_added = $row['date'];
      $time_message = get_timeframe($date_added);

      array_push($details_array, $sent_by);
      array_push($details_array, $row['body']);
      array_push($details_array, $time_message);

      return $details_array;
    }

    public function getConvos() {
      $userLoggedIn = $this->user_obj->getUsername();
      $return_string = "";
      $convos = array();

      $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY date DESC");

      while ($row = mysqli_fetch_array($query)) {
        $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];
        
        if (!in_array($user_to_push, $convos)) {
          array_push($convos, $user_to_push);
        }
      }

      foreach($convos as $username) {
        $user_found_obj = new User($this->con, $username);
        $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);
        $dots = (strlen($latest_message_details[1]) > 12) ? "..." : "";
        $split = str_split($latest_message_details[1], 12);
        $split = $split[0] . $dots;

        $return_string .= "
        <div class='conv'>
          <a href='messages.php?u=$username' class='conv__link'>
            <img src='" . $user_found_obj->getProfilePic() . "' alt='profile picture' class='conv__img'>
            <div>
              <div class='conv__name'>" . $user_found_obj->getFirstAndLastName() . "</div>
              <div>" . $latest_message_details[2] . "</div>
              <div>" . $latest_message_details[0] . $split . "</div>
            </div>
          </a>
        </div>";
      }

      return $return_string;
    }

    public function getConvosDropdown($data, $limit) {
      $page = $data['page'];
      $userLoggedIn = $this->user_obj->getUsername();
      $return_string = "";
      $convos = array();

      if ($page == 1) {
        $start = 0;
      } else {
        $start = ($page - 1) * $limit;
      }

      $set_viewed_query = mysqli_query($this->con, "UPDATE messages SET viewed='yes' WHERE user_to='$userLoggedIn'");

      $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY date DESC");

      while ($row = mysqli_fetch_array($query)) {
        $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];
        
        if (!in_array($user_to_push, $convos)) {
          array_push($convos, $user_to_push);
        }
      }

      $num_iterations = 0; // Number of messages checked
      $count = 1; // Number of messages posted

      foreach($convos as $username) {

        if ($num_iterations++ < $start) {
          continue;
        }

        if ($count > $limit) {
          break;
        } else {
          $count++;
        }
        
        $is_unread_query = mysqli_query($this->con, "SELECT opened FROM messages WHERE user_to='$userLoggedIn' AND user_from='$username' ORDER BY id DESC");
        $row = mysqli_fetch_array($is_unread_query);
        $style = ($row['opened'] == 'no') ? "background-color: #DDEDFF;" : "";
        
        $user_found_obj = new User($this->con, $username);
        $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);
        $dots = (strlen($latest_message_details[1]) > 12) ? "..." : "";
        $split = str_split($latest_message_details[1], 12);
        $split = $split[0] . $dots;

        $return_string .= "
        <div class='conv'>
          <a href='messages.php?u=$username' class='conv__link' style='" . $style . "'>
            <img src='" . $user_found_obj->getProfilePic() . "' alt='profile picture' class='conv__img'>
            <div>
              <div class='conv__name'>" . $user_found_obj->getFirstAndLastName() . "</div>
              <div>" . $latest_message_details[2] . "</div>
              <div>" . $latest_message_details[0] . $split . "</div>
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

    public function getUnreadNumber() {
      $userLoggedIn = $this->user_obj->getUsername();
      $query = mysqli_query($this->con, "SELECT * FROM messages WHERE viewed='no' AND user_to='$userLoggedIn'");
      return mysqli_num_rows($query);
    }
  }

?>