<?php
ini_set('max_execution_time', 30000);
	
$debug = true;
$clientFolder = basename(__DIR__);
$path = "/home4/grayson/public_html/scavenger/platforms/twitter";
$phpExecutable = "/opt/php54/bin/php";
$timestame = time();

// RANDOMIZE TIME OCCURANCE
$randomNumber = rand( 1 , 10 ) * 60;
sleep("$randomNumber");




// INITIATE COPY FOLLOWERS

$file = "$path/config.php ". addslashes($clientFolder) . " " . addslashes(1);
$outputNothing = "> /dev/null &";

$command = "$phpExecutable $file";
$output = array();
//echo $command;
chdir($path);

exec($command, $output);

print_r($output);


// INITIATE WEEKLY CRON
if(date('D', $timestamp) === 'Mon') {
	$file = "$path/weekly-cron.php ";
	$outputNothing = "> /dev/null &";
	
	$command = "$phpExecutable $file";
	$output = array();
	//echo $command;
	chdir($path);
	
	exec($command, $output);
}
?>