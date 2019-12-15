<?php 
  include "includes/header.php";
  include "includes/classes/User.php";

  if (!isset($userLoggedIn)) {
    header("Location: login.php");
  }

  if (isset($_GET['profile_username'])) {
    $profile_username = $_GET['profile_username'];
    $profile_obj = new User($con, $profile_username);

    if ($userLoggedIn != $profile_username) {
      $logged_in_user_obj = new User($con, $userLoggedIn);
    }

    if ($profile_obj->isClosed()) {
      header("Location: user_closed.php");
    } else {
      $profile_full_name = $profile_obj->getFirstAndLastName();
      $profile_profile_pic = $profile_obj->getProfilePic();
      $profile_num_posts = $profile_obj->getNumPosts();
      $profile_num_likes = $profile_obj->getNumLikes();
      $profile_num_friends = $profile_obj->getNumFriends();
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
          echo "<li class='user__list-item'>Friends in common: ". "</li>";
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
            echo '<button class="button post-on-profile-js">Post Something</button>';
            echo '<button type="submit" name="remove_friend" class="button button--danger">Remove Friend</button>';
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
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias libero enim aspernatur quidem assumenda, nemo sequi similique, laborum illo aliquam voluptates. Sint, in. Nulla deserunt sapiente exercitationem impedit odit illo.</p>
      <p>Enim officia unde dicta doloremque! Tempore suscipit possimus adipisci ex eveniet cumque dolorum porro nihil maiores asperiores obcaecati magni blanditiis quisquam odio dolorem accusantium temporibus molestias eum id, sunt autem!</p>
      <p>Consequatur odit a assumenda hic reiciendis voluptatum quas! Assumenda, eum praesentium. Doloribus, et. Quae dolorem odio ab libero, qui recusandae, animi, dignissimos tempore ad nemo impedit obcaecati reprehenderit est id?</p>
      <p>Commodi totam mollitia, excepturi dolores ut nobis tempore adipisci minima dolorem, eius accusantium. Nulla saepe quisquam quia laudantium, doloribus culpa deserunt ab asperiores ducimus incidunt qui corrupti libero recusandae quidem!</p>
      <p>Sunt unde impedit minima optio reiciendis voluptatem numquam? Eveniet consequuntur impedit labore similique nemo nihil quis possimus temporibus, illum aliquam repudiandae odit, et neque omnis laborum veniam est. Aperiam, necessitatibus!</p>
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
      <textarea name="modal_msg" class="modal__textarea" cols="30" rows="10"></textarea>
      <button class="button button--neutral btn-close-js">Close</button>
      <button type="submit" name="post_message" class="button">Post</button>
    </form>
  </div>

</div>
<?php 
  include "includes/footer.php";
?>