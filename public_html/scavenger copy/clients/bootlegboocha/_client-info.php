<?php
$consumerkey = "6i12EhAupwZrEwooMUSwOi4Zq";
$consumersecret = "uStV84hEE6yySo7l9y5HmXNUj5Dp3ICCYXDSCCk4GdDjHwjIB5";
$accesstoken = "3306359274-S2jA0yZKo0iWDpmQOiazkBFqNaRNMGYRgZGTPyO";
$accesstokensecret = "P2eC4wEauUlhTAeKoP94o2dYDhk7Ao2m0mX1XxpbInbhe";

// Custom User Information
$userID = 3306359274;
$myScreenName = 'bootlegboocha';
$clientFolder = 'bootlegboocha';
$name = "Ben and Josh";
$email = "bootlegboocha@gmail.com";
$clientFolder = basename(__DIR__);
$debugEmails = true; 
$frustratingHosting = (boolean) false;

$twitterPassword = "P@ss1234";

$cookie_name = "scavenger-user";
$cookie_value = "$clientFolder";
$cookie = $_COOKIE["$cookie_name"];

if (!isset($cookie)) {
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
} elseif ($cookie != $clientFolder) {
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
}
?>