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

$name = "Lukis Gordon";
$email = "lukismusicusa@gmail.com, grow@graysonerhard.com";
$debugEmails = true;





/* ---------- TWITTER ------------ */

$consumerkey = "ZhBpA1xQOjx59bu9v5ShMJC7G";
$consumersecret = "xs07jBlwpzXWYulzR3PrwqulWQyuTfzPbXIQHM99mCOpf8zeZs";
$accesstoken = "31519684-IPQpyNGIKpxQEQxEs269xa27Dp6J1K1oIfBjMRYsT";
$accesstokensecret = "FVFYV8KJNKNEXoOpF2sG0tdRcDtr5m0YkUWxU2W9rXphw";

// Custom User Information - TWITTER
$myScreenName = 'lucasgordon';

$twitterLoginUsername="lucasgordon";
$twitterPassword = ",Fleect9";


/* ---------- INSTAGRAM ------------ */
/*

$instagramApiKey = "";
$instagramApiSecret = "";
$instagramAccessToken = "";

// Custom User Information - TWITTER
$instagramScreenName = '';
$instagramUserID = 0;
*/



?>