<?php
$consumerkey = "BBnVpHzZRLpuQNGDp51RLtR23";
$consumersecret = "OkKevsOit1ZMbDx29LfQavdA4NxiM3XwEvf55pkbwd5gVSqYYw";
$accesstoken = "1000589353-zgAIB3rzazAZjtQwKyeMjADN9W6rpoA8NIFntYQ";
$accesstokensecret = "nZIpRHokxhes5diXHNwEEx2reps9Ie3402yKQOxxAfB0A";

// Custom User Information
$userID = 1000589353;
$myScreenName = 'skydyedmusic';
$clientFolder = 'skydyed';
$name = "Skydyed";
$email = "skydyedmusic@gmail.com, grow@graysonerhard.com";
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