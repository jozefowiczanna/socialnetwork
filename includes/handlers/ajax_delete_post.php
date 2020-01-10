<?php 
  require_once("../initialize.php");
  require_once(CONFIG_FILE);
  require(FUNCTIONS_FILE);
  require(USER_FILE);
  require(POST_FILE);
  
  if (isset($_REQUEST['postId'])) {
    $posts = new Post($con, $_SESSION['username']);
    $posts->deletePost($_REQUEST['postId']);
  }

?>