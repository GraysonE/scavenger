<?php
$consumerkey = "FkN0Vi2ve28cqv2tCD242Gvrm";
$consumersecret = "dS7V9qRvl9xjNlUAWE0ToPhAckhfPnek8VsJXEXwRD3JYUdkvC";
$accesstoken = "1131495416-EGVC1FCoZcZfu3gQ4l7qIUCEFjCdpPmL6bOzrj0";
$accesstokensecret = "7RYBjByr0OvLAJn1fM7hglYWOAgMdrncfpoHotLob9qvp";

// Custom User Information
$userID = 1131495416;
$myScreenName = 'aspenhourglass';
$name = "Aspen Hourglass";
$email = "grow@graysonerhard.com";
$clientFolder = basename(__DIR__);
$debugEmails = true; 
$frustratingHosting = (boolean) false;
$clientFolder = basename(__DIR__);

$cookie_name = "scavenger-user";
$cookie_value = "$clientFolder";
$cookie = $_COOKIE["$cookie_name"];

if (!isset($cookie)) {
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
} elseif ($cookie != $clientFolder) {
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
}
?>