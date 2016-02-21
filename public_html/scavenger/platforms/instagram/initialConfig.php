<?php
require ('Instagram-PHP-API/Instagram.php');

use MetzWeb\Instagram\Instagram;

$apiKey = "7d5fc656628a412d91ced88b093d33bb";
$apiSecret = "d0875059cefe453091a875ede6edd45f";
$apiCallback = "http://scavenger-app.com/scavenger/platforms/instagram/test.php";

$instagram = new Instagram(array(
    'apiKey'      => $apiKey,
    'apiSecret'   => $apiSecret,
    'apiCallback' => $apiCallback
));
	
echo "<a href='{$instagram->getLoginUrl()}'>Login with Instagram</a>";
?>