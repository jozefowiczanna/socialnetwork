<?php  
include "../../config/config.php";
include_once "../functions/functions.php";
include "../classes/User.php";
include "../classes/Post.php";

$limit = 10; //Number of posts to be loaded per call

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->loadPostsFriends($_REQUEST, $limit);
?>