<?php 
  require_once("includes/initialize.php");
  require_once(HEADER_FILE);
  require(MESSAGE_FILE);
  require(USER_FILE);

  if (!isset($userLoggedIn)) {
    header("Location: login.php");
  }

  if (isset($_GET['profile_username'])) {
    $profile_username = $_GET['profile_username'];
    $_SESSION['profile_username'] = $profile_username;
    $profile_obj = new User($con, $profile_username);
    $logged_in_user_obj = new User($con, $userLoggedIn);

    if ($profile_obj->isClosed()) {
      header("Location: user_closed.php");
    } else {
      $profile_full_name = $profile_obj->getFirstAndLastName();
      $profile_profile_pic = $profile_obj->getProfilePic();
      $profile_num_posts = $profile_obj->getNumPosts();
      $profile_num_likes = $profile_obj->getNumLikes();
      $profile_num_friends = $profile_obj->getNumFriends();
      $mutualFriendsNum = $logged_in_user_obj->getMutualFriendsNum($profile_username);
    }
  }

  if (isset($_POST['remove_friend'])) {
    $logged_in_user_obj->removeFriend($profile_username);
    header("Location: $profile_username");
  }

  if (isset($_POST['add_friend'])) {
    $logged_in_user_obj->sendRequest($profile_username);
    header("Location: $profile_username");
  }

  if (isset($_POST['respond_request'])) {
    header("Location: requests.php");
  }

  $message_obj = new Message($con, $userLoggedIn);

  if (isset($_POST['post_message'])) {
    if (isset($_POST['message_body'])) {
      $body = $_POST['message_body'];
      $date = date("Y-m-d H:i:s");
      $message_obj->sendMessage($profile_username, $body, $date);
      header("Location: $profile_username");
    }
  }
?>

<div class="wrapper">
  <div class="panels panels--profile">
    <div class="panel panel--profile">
      <div class="user__img user__img--round" style="background-image: url('<?php echo $profile_profile_pic ?>')">
      </div>
      <p class="user__name"><?php echo $profile_full_name; ?></p>
      <ul class="user__list user__list--profile">
        <li class="user__list-item">Posts: <?php echo $profile_num_posts; ?></li>
        <li class="user__list-item">Likes: <?php echo $profile_num_likes; ?></li>
        <li class="user__list-item">Friends: <?php echo $profile_num_friends; ?></li>
        <?php 
        if ($profile_username != $userLoggedIn) {
          echo "<li class='user__list-item'>Friends in common: ". $mutualFriendsNum . "</li>";
        }      
        ?>
        
      </ul>


      <?php
      if (isset($userLoggedIn) && !$profile_obj->isClosed()) {
        $logged_in_user_obj = new User($con, $userLoggedIn);
        $isFriend = $logged_in_user_obj->isFriend($profile_username);
        
        echo "<form action='$profile_username' method='POST'>";
        if ($userLoggedIn != $profile_username) {
          if ($isFriend) {
            echo '<button type="submit" name="remove_friend" class="button button--danger">Remove Friend</button>';
            echo '<button class="button open-modal-js">Post Something</button>';
          } else if ($logged_in_user_obj->didReceiveRequest($profile_username)) {
            echo '<button type="submit" name="respond_request" class="button button--success">Respond to Request</button>';
          } else if ($logged_in_user_obj->didSendRequest($profile_username)) {
            echo '<button class="button button--inactive">Request Sent</button>';
          } else {
            echo '<button type="submit" name="add_friend" class="button button--success">Add Friend</button>';
          }
        }
        echo "</form>";
      }
        
      ?>
<!-- didSendRequest -->

    </div><!-- panel--profile -->
    <div class="panel panel--message">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Messages</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="posts-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="true">Posts</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="false">About</a>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane show active" id="messages" role="tabpanel" aria-labelledby="messages-tab">
        <h4 class="h4">Messages</h4>
        <?php 
          echo "<h4 class='h4'>You and <a href='" . $profile_username . "'>" . $profile_full_name . "</a></h4>";
          echo "<div class='messages' id='scroll-messages'>";
          echo $message_obj->getMessages($profile_username);
          echo "</div>";
        ?>

        <form action="" class="post-form" method="post">
          <textarea name="message_body" class="post-form__text" id=" cols="30" rows="10" placeholder="Write your message..."></textarea>
          <input type="submit" name="post_message" class="post-form__button" value="Send">
        </form>
      </div>
      <div class="tab-pane" id="posts" role="tabpanel" aria-labelledby="posts-tab">
        <h4 class="h4">Posts</h4>
        <div class="posts_area">
        </div> <!-- posts_area -->
      </div>
      <div class="tab-pane" id="about" role="tabpanel" aria-labelledby="about-tab">
        <h4 class="h4">About</h4>
      </div>
    </div>

    </div>
  </div><!-- panels -->
</div><!-- wrapper -->


<!-- modal -->
<div id="profile-modal" class="modal">
  <div class="modal__content">
    <span class="modal__close">&times;</span>
    <h3 class="modal__title">Post something!</h3>
    <p>This will appear on the user's profile page and also their newsfeed for your friends to see!</p>
    <form action="" method="POST" class="modal__form">
      <textarea name="post_body" class="modal__textarea" cols="30" rows="10"></textarea>
      <button class="button button--neutral btn-close-js">Close</button>
      <button type="submit" name="profile_post_button" class="button btn-profile-post-js">Post</button>
    </form>
  </div>
</div>

<div id="post-modal" class="modal">
  <div class="modal__content modal__content--delete-post">
    <span class="modal__close">&times;</span>
    <h3 class="modal__title">Are you sure you want to delete this post?</h3>
    <form action="" method="POST" class="modal__form">
      <button class="button button--neutral btn-cancel-js">Cancel</button>
      <button type="submit" name="profile_post_button" class="button button--danger btn-delete-post-js">Delete</button>
    </form>
  </div>
</div>

<script>
  var userLoggedIn = '<?php echo $userLoggedIn; ?>';
  var profileUsername = '<?php echo $profile_username; ?>';

  $(document).ready(function(){
    $('#loading').show();

    // Original ajax request for loading first posts

    $.ajax({
      url: "includes/handlers/ajax_load_profile_posts.php",
      type: "POST",
      data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
      cache: false,

      success: function(data) {
        $('#loading').hide();
        $('.posts_area').html(data);
      },
      error: function() {
        window.alert("Failure");
      }
    });

    $(window).scroll(function(){
      var height = $('.posts_area').height(); // Div containing posts
      var scroll_top = $(this).scrollTop();
      var page = $('.posts_area').find('.nextPage').val();
      var noMorePosts = $('.posts_area').find('.noMorePosts').val();

      if ((document.body.scrollHeight == scroll_top + window.innerHeight) && noMorePosts == 'false') {
        $('#loading').show();

        var ajaxReq = $.ajax({
          url: "includes/handlers/ajax_load_profile_posts.php",
          type: "POST",
          data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
          cache: false,

          success: function(response) {
            $('.posts_area').find('.nextPage').remove(); // Removes current .nextPage
            $('.posts_area').find('.noMorePosts').remove(); // Removes current .noMorePosts
            $('#loading').hide();
            $('.posts_area').append(response);
          }
        });
      }
    });
  });

</script>

<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->
<script src="assets/js/index.js"></script>
<script src="assets/js/messages.js"></script>
<script src="assets/js/profile.js"></script>
<?php 
  include "includes/footer.php";
?>