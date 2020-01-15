<?php 
require_once("../initialize.php");
require_once(FUNCTIONS_FILE);
require_once(CONFIG_FILE);
require(USER_FILE);
require(MESSAGE_FILE);

$limit = 3; // Number of messages to load

$message = new Message($con, $_REQUEST['userLoggedIn']);
echo $message->getConvosDropdown($_REQUEST, $limit);

?>