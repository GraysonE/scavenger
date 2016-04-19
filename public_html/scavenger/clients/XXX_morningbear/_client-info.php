<?php
$consumerkey = "Yy7C7i1z01gyJgRcBAJSiJhPH";
$consumersecret = "EEL2ccDt6lLUPOLhEf4VkGu6OZv47MQMEtjDrYbL0Tp8s687nX";
$accesstoken = "27781618-nu4MxYdM7HrWYLjZ3bjVslzsFUpM1itYO8wnrmWyM";
$accesstokensecret = "jtpAE1yB5mPJ9psQEZtbH9IJHdaFhgF1HCBFw94Y59Dch";

// Custom User Information
$userID = 27781618;
$myScreenName = 'johnmorningbear';
$clientFolder = 'morningbear';
$name = "John Runnels";
$email = "morningbearofficial@gmail.com, grow@graysonerhard.com";
$clientFolder = basename(__DIR__);
$debugEmails = true; 
$frustratingHosting = (boolean) false;

$cookie_name = "scavenger-user";
$cookie_value = "$clientFolder";
$cookie = $_COOKIE["$cookie_name"];

if (!isset($cookie)) {
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
} elseif ($cookie != $clientFolder) {
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
}
?>