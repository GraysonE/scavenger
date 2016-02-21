<?php
ini_set('max_execution_time', 30000);	
$debug = (boolean) false;



if (!isset($clientFolder)){
	
	$cron = stripslashes($argv[2]);
	
	if ($cron) {
	
		$clientFolder = stripslashes($argv[1]);
			if($debug) { echo "clientFolder: $clientFolder";}
		
	} else {
		
		$clientFolder = $_COOKIE['scavenger-user'];
		
		if (!isset($clientFolder)) {
			echo '<h2>Please go to the original link the developer sent you to use the application.</h2>';
			if (!$debug) { echo 'cookie value - config: ' . $_COOKIE['scavenger-user']; }
			die();
		}
		
	}
} else {
	$clientFolder = $clientFolderFromCF;
}


//echo 'clientFolder: '.$clientFolder;

require_once("oauth/twitteroauth.php");
require("../../clients/$clientFolder/_client-info.php");
//require("../../assets/db.php");

if ($debug) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

// Should be constants
$targetCount = 5000;
$count = 5000;
$cursorTarget = "-1";

$parentDirectory = dirname(__FILE__);
$parentDirectory = dirname($parentDirectory);
$dataFileDirectory = dirname($parentDirectory) . "/clients/$clientFolder/_data";
if ($debug) {
	echo $dataFileDirectory;
}

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	  return $connection;
}


$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
	//if ($debug) {print_r($connection);}

date_default_timezone_set('America/Denver');
$date = new DateTime();

$cwd = getcwd();


if ($cron) {
	$path = "/home4/grayson/public_html/scavenger/platforms/twitter";

	$phpExecutable = "/opt/php54/bin/php";
	$file = "$path/copy-followers.php";
	$outputNothing = "> /dev/null &";
	
	$command = "$phpExecutable $file ". addslashes($clientFolder) . " " . addslashes(1);
	$output = array();
	//echo $command;
	chdir($path);
	
	exec($command, $output);
	print_r($output);
}
?>