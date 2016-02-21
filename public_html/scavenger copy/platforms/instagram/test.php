<?php
require ('config.php');

use MetzWeb\Instagram\Instagram;

$debug=true;

$targetUser = 180991007; // therealmckee
$targetUser = 635143849; // aspen_hourglass


copy_followers($instagram, $instagramUserID, $debug, $targetUser, $date, $email, $debugEmails, $name);
?>