<?php 
require_once("includes/initialize.php");
require_once(CONFIG_FILE);
require(FUNCTIONS_FILE);
require(USER_FILE);
require(POST_FILE);

if (isset($_SESSION['username'])) {
  $userLoggedIn = $_SESSION['username'];
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
  $user = mysqli_fetch_array($user_details_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/style.css">
  <title></title>
</head>
<body class="comment-frame">

  <?php 

  //get id of post
  if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
  }

  $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
  $row = mysqli_fetch_array($user_query);

  $posted_to = $row['added_by'];
  if (isset($_POST['postComment' . $post_id])) {
    $post_body = $_POST['post_body'];
    $post_body = mysqli_real_escape_string($con, $post_body);
    $date_time_now = date("Y-m-d H:i:s");
    $insert_post = mysqli_query($con, "INSERT INTO comments VALUES (NULL, '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id')");
    echo "<p>Comment Posted! </p>";
  }

  ?>

  <!-- TODO fix mixeds name styles - camelCase to snake_case camelCase -->
  <form action="comment_frame.php?post_id=<?php echo $post_id; ?>" class="comment-form" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
    <textarea name="post_body" class="comment__textarea"></textarea>
    <input type="submit" name="postComment<?php echo $post_id; ?>" value="Post" class="comment__button">
  </form>

  <!-- Load comments -->
  <?php
  
  $get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id'");
  $count = mysqli_num_rows($get_comments);

  if ($count != 0) {
    while ($comment = mysqli_fetch_array($get_comments)):
      $post_body = $comment['post_body'];
      $posted_by = $comment['posted_by'];
      $posted_to = $comment['posted_to'];
      $date_added = $comment['date_added'];
      $removed = $comment['removed'];

      $time_message = get_timeframe($date_added);
      $user = new User($con, $posted_by);
      $full_name = $user->getFirstAndLastName();
      $profile_pic = $user->getProfilePic();
      ?>
      
      <div class='comment_section post post--comment'>
        <a href='<?php echo $posted_by; ?>' class="post__img-outer" target="_parent"><img class="post__img post__img--comment" src='<?php echo $profile_pic; ?>'></a>
        <a href='<?php echo $posted_by; ?>' class="post__profile-link post__profile-link--comment" target="_parent"><?php echo $full_name; ?></a> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message; ?>
        <div class="post__body"><?php echo $post_body; ?></div>
      </div>

    <?php
    endwhile;
  } else {
    echo "<p class='comment__no-comment'>No comments to show!</p>";
  }

  ?>

</body>
</html>