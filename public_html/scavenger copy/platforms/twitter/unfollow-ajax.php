<?php
require ('config.php');
require ('functions.php');

echo 'test';

$unfollowerArray = unserialize($_GET['array']);

$debug = true;

if($debug) {print_r($unfollowerArray);}

//unfollow_by_id($unfollowerArray, $connection);
?>