<?php 
  include "includes/header.php";
  include "includes/classes/User.php";
?>

<div class="wrapper">
  <div class="request panel">
    <h2 class="request__title">Friend Requests</h2>
    <?php
      $query = mysqli_query($con, "SELECT * FROM friend_requests WHERE user_to='$userLoggedIn'");
      if (mysqli_num_rows($query) == 0):
        echo "<p>You have no friend requests at this time!</p>";
      else:
        while ($row = mysqli_fetch_array($query)):
          $user_from = $row['user_from'];
          $user_from_obj = new User($con, $user_from);
          echo "<p><a href='$user_from' class='post__profile-link'>" . $user_from_obj->getFirstAndLastName() . "</a> sent you a friend request!</p>";
          
          if (isset($_POST['accept_request' . $user_from])) {
            $add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$userLoggedIn', ',') WHERE username='$user_from'");
            $add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$user_from', ',') WHERE username='$userLoggedIn'");

            $delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
            echo "<p>You are now friends!</p>";
            header("Location: requests.php");
          }

          if(isset($_POST['ignore_request' . $user_from])) {
            $delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
            echo "<p>Request ignored!</p>";
            header("Location: requests.php");
          }
        ?>      
          <form action="requests.php" method="POST" class="form-request">
            <button type="submit" name="accept_request<?php echo $user_from; ?>" class="button button--success">Accept</button>
            <button type="submit" name="ignore_request<?php echo $user_from; ?>" class="button button--danger">Ignore</button>
          </form>
        <?php
        endwhile;
      endif;
    ?>
  </div>
</div>

<?php 
  include "includes/footer.php";
?>