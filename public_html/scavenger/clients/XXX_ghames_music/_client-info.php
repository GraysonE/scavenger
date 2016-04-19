<?php
$consumerkey = "gOOYjeMO1bVdDeCOkVEGeeL10";
$consumersecret = "REMjJA34WXibFkI3FjCQqPezSeZmTyT641mVLencoH1fEdywHE";
$accesstoken = "3273817122-sg9rnKpDhVT8P8QO2SkkE3s4iUYKNG5GSgazKT5";
$accesstokensecret = "inmoGiRzJaG5qpmDUo8kVqRES4nNwsHl4bl452iw7BqVM";

// Custom User Information
$userID = 3273817122;
$myScreenName = 'ghames_music';
$clientFolder = 'ghames';
$name = "Michael Roach";
$email = "ghamesmusic@gmail.com";
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