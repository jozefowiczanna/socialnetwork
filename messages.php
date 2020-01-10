<?php 
require_once("includes/initialize.php");
require(FUNCTIONS_FILE);
require(HEADER_FILE);
require(USER_FILE);
require(MESSAGE_FILE);

if (!isset($userLoggedIn)) {
  header("Location: login.php");
}

$message_obj = new Message($con, $userLoggedIn);

if (isset($_GET['u'])) {
  $user_to = $_GET['u'];
} else {
  $user_to = $message_obj->getMostRecentUser();
  if ($user_to == false) {
    $user_to = 'new';
  }
}

if ($user_to != 'new') {
  $user_to_obj = new User($con, $user_to);
}

if (isset($_POST['post_message'])) {
  if (isset($_POST['message_body'])) {
    $body = $_POST['message_body'];
    $date = date("Y-m-d H:i:s");
    $message_obj->sendMessage($user_to, $body, $date);
    header("Location: messages.php?u=$user_to");
  }
}
?>


<div class="wrapper">
  <div class="panels panels--user">
    <div class="panel panel--user">
      <div class="user__img" style="background-image: url('<?php echo $user['profile_pic'] ?>')">
      </div>
      <ul class="user__list">
        <li class="user__list-item"><a href="<?php echo $userLoggedIn ?>" class="user__full-name js-userLoggedIn"><?php echo $user['first_name'] . " " . $user['last_name'] ?></a></li>
        <li class="user__list-item">Posts: <?php echo $user['num_posts'] ?></li>
        <li class="user__list-item">Likes: <?php echo $user['num_likes'] ?></li>
      </ul>
      <div class="conversations">
        <h4 class="h4">Conversations</h4>
        <?php echo $message_obj->getConvos(); ?>
        <a class="new-msg-link" href="messages.php?u=new">New Message</a>
      </div>
    </div><!-- panel--user -->
    <div class="panel panel--message">
      <?php 
        if ($user_to != 'new') {
          echo "<h4 class='h4'>You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h4>";
          echo "<div class='messages' id='scroll-messages'>";
          echo $message_obj->getMessages($user_to);
          echo "</div>";
        } else {
          echo "<h4 class='h4'>New Message</h4>";
        }
      ?>

      <form action="" class="post-form" method="post">
        <?php 
          if ($user_to == "new") {
            echo "<label for='msg-to'>Search the friend you would like to message</label>";
            echo "<input type='text' id='msg-to' class='search-friend' name='q' placeholder='Name' autocomplete='off'>";
            echo "<div class='results'></div>";
          } else {
            echo "<textarea name='message_body' class='post-form__text' id=' cols='30' rows='10' placeholder='Write your message...'></textarea>";
            echo "<input type='submit' name='post_message' class='post-form__button' value='Send'>";
          }
        ?>
      </form>
    </div><!-- panel--message -->
  </div><!-- panels -->
</div><!-- wrapper -->

<script src="assets/js/messages.js"></script>
<?php 
  include "includes/footer.php";
?>