<?php
ini_set('max_execution_time', 30000);
	
$debug = true;
$clientFolder = basename(__DIR__);

// RANDOMIZE TIME OCCURANCE
$randomNumber = rand( 1 , 10 ) * 60;
sleep("$randomNumber");

$path = "/home4/grayson/public_html/scavenger/platforms/twitter";

$phpExecutable = "/opt/php54/bin/php";
$file = "$path/config.php ". addslashes($clientFolder) . " " . addslashes(1);
$outputNothing = "> /dev/null &";

$command = "$phpExecutable $file";
$output = array();
//echo $command;
chdir($path);

exec($command, $output);

print_r($output);
?>