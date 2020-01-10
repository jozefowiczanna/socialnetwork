<?php 
  require_once("../initialize.php");
  require_once(CONFIG_FILE);
  require(FUNCTIONS_FILE);
  require(USER_FILE);
  require(POST_FILE);
  
  if (isset($_POST['post_body'])) {
    $post = new Post($con, $_SESSION['username']);
    $post->submitPost($_POST['post_body'], $_SESSION['profile_username']);
  }

?>