<?php
$consumerkey = "FkN0Vi2ve28cqv2tCD242Gvrm";
$consumersecret = "dS7V9qRvl9xjNlUAWE0ToPhAckhfPnek8VsJXEXwRD3JYUdkvC";
$accesstoken = "1131495416-JwGe5qrDzgzgIVQU3jsyPmKTmqa3Ymw9sQ2ywBN";
$accesstokensecret = "45M6BE2TRKya8yr4gpdGidaBNoqPcCTDKJ0egiZA9yctA";

// Custom User Information
$userID = 1131495416;
$myScreenName = 'aspenhourglass';
$name = "Aspen Hourglass";
$email = "";
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