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

$name = "Vi Wickam";
$email = "";
$debugEmails = true;





/* ---------- TWITTER START ------------ */

$consumerkey = "7yvfLXMoe2dXOz8bjrVCJE4d8";
$consumersecret = "CdYU6eRN07jNS1mJQR8XQqmc6AKQrWtZY0coJkY46F6RvCybSM";
$accesstoken = "823666-yCLhT6tFLxA4s0nezanYilnTaQ1SlSfQl3KuYbh6sPf";
$accesstokensecret = "kNMy6vpZdySSqEvzd92usA1h5XWGGyftj65wGVjvResLS";

// Custom User Information - TWITTER
//$userID = 1286744324;
$myScreenName = 'vithefiddler';




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