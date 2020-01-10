<?php  
require_once("../initialize.php");
require_once(CONFIG_FILE);
require(FUNCTIONS_FILE);
require(USER_FILE);
require(POST_FILE);

$limit = 10; //Number of posts to be loaded per call

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->loadProfilePosts($_REQUEST, $limit);
?>