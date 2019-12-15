<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Document</title>
  <style>
    body {
      background-color: transparent;
    }
  </style>
</head>
<body>
  <?php 
  
  include "config/config.php";
  include "includes/classes/User.php";
  include "includes/classes/Post.php";

  if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
  }
  
  if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
  }

  $posts_select = mysqli_query($con, "SELECT likes, added_by FROM posts WHERE id='$post_id'");
  $row = mysqli_fetch_array($posts_select);
  $total_post_likes = $row['likes'];
  $post_author = $row['added_by'];

  $users_select = mysqli_query($con, "SELECT num_likes FROM users WHERE username='$post_author'");
  $row = mysqli_fetch_array($users_select);
  $total_user_likes = $row['num_likes'];

  // Like button
  if (isset($_POST['like_button'])) {
    $total_post_likes++;
    $update_post = mysqli_query($con, "UPDATE posts SET likes='$total_post_likes' WHERE id='$post_id'");
    $total_user_likes++;
    $update_user = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$post_author'");
    $insert_like = mysqli_query($con, "INSERT INTO likes VALUES(NULL, '$userLoggedIn', '$post_id')");
  }

  // Unlike button
  if (isset($_POST['unlike_button'])) {
    if ($total_post_likes > 0) {
      $total_post_likes--;
      $update_post = mysqli_query($con, "UPDATE posts SET likes='$total_post_likes' WHERE id='$post_id'");
      $total_user_likes--;
      $update_user = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$post_author'");
      $delete_like = mysqli_query($con, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
    }
  }

  // Check for previous likes
  $check_query = mysqli_query($con, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
  $num_rows = mysqli_num_rows($check_query);
  
  if ($num_rows > 0) {
    echo "<form action='like_frame.php?post_id=$post_id' class='like-form' method='POST'>
      <input type='submit' name='unlike_button' class='like-form__button' value='Unlike'>
      <div class='like-form__info'>$total_post_likes Likes</div>
    </form>";
  } else {
    echo "<form action='like_frame.php?post_id=$post_id' class='like-form' method='POST'>
      <input type='submit' name='like_button' class='like-form__button' value='Like'>
      <div class='like-form__info'>$total_post_likes Likes</div>
    </form>";
  }
  ?>
</body>
</html>