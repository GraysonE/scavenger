<?php
$debug = false;
$clientFolder = basename(__DIR__);


// RANDOMIZE TIME OCCURANCE
$randomNumber = rand( 1 , 10 ) * 60;
sleep("$randomNumber");

$path = "http://www.scavenger-app.com/scavenger/platforms/twitter/config.php?client=$clientFolder&cron=1";
	if ($debug) {echo $path;}

header("location: $path");
?>