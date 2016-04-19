<?php
/* ---------- COOKIE / GENERIC DATA START ------------ */

$clientFolder = basename(__DIR__);
$cookie_name = "scavenger-user";
$cookie_value = "$clientFolder";
$cookie = $_COOKIE["$cookie_name"];

if ((!isset($cookie)) || ($cookie =='')) {
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
} elseif ($cookie != $clientFolder) {
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
}



/* ---------- USER / APP INFO START ------------ */

$name = "Grayson Erhard";
$email = "grow@graysonerhard.com";
$debugEmails = true;





/* ---------- TWITTER START ------------ */

$consumerkey = "4nJh80KE5bVyuyY9WlT5rTYlN";
$consumersecret = "dYvw5tYAc2xnoKw3wdHc13oIdq6i7kEt5ndaxfoA4d0kUgNPmw";
$accesstoken = "1286744324-DwTdCgdd8VrwOvEqJY6jWZjkomtijdmR4vbEvQF";
$accesstokensecret = "FYSZyvgQlT0V2JasCzlAoOPIaVRVpTKANEn4PaTbqd7uz";

// Custom User Information - TWITTER
$userID = 1286744324;
$myScreenName = 'graysonerhard';




/* ---------- INSTAGRAM START ------------ */

/*
$instagramApiKey = "7d5fc656628a412d91ced88b093d33bb";
$instagramApiSecret = "d0875059cefe453091a875ede6edd45f";
$instagramAccessToken = "504804161.7d5fc65.3840adb07afc452d8beeeae5c16f3574";

// Custom User Information - TWITTER
$instagramScreenName = 'grayson_erhard';
$instagramUserID = 504804161;
*/



?>