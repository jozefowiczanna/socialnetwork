<?php 
require_once("../initialize.php");
require_once(FUNCTIONS_FILE);
require_once(CONFIG_FILE);
require(USER_FILE);
require(NOTIFICATION_FILE);

$limit = 3; // Number of messages to load

$notification = new Notification($con, $_REQUEST['userLoggedIn']);
echo $notification->getNotifications($_REQUEST, $limit);

?>