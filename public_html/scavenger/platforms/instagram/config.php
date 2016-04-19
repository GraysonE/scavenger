<?php
require ('Instagram-PHP-API/Instagram.php');
require ('functions.php');

use MetzWeb\Instagram\Instagram;

// Custom User Information - TWITTER
$instagramScreenName = 'grayson_erhard';
$instagramUserID = 504804161;

$apiKey = "7d5fc656628a412d91ced88b093d33bb";
$apiSecret = "d0875059cefe453091a875ede6edd45f";
$apiCallback = "http://scavenger-app.com/scavenger/platforms/instagram/test.php";

$instagram = new Instagram(array(
    'apiKey'      => $apiKey,
    'apiSecret'   => $apiSecret,
    'apiCallback' => $apiCallback
));

// grab OAuth callback code
$code = $_GET['code'];
$data = $instagram->getOAuthToken($code);

print_r($data);

// set user access token
$instagram->setAccessToken($data);
echo "<a href='{$instagram->getLoginUrl()}'>Login with Instagram</a>";
echo "<br>";
?>