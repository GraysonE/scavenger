<?php
require 'html.inc';
require 'header.php';
require 'config.php';
require 'functions.php';

$debug = true;
	
$followersToDM = array();

$cursor = "-1";

do {
	
	echo "<br>";
	$query = "https://api.twitter.com/1.1/followers/list.json?cursor=$cursor&count=5000&screen_name=$myScreenName&skip_status=true&include_user_entities=false";
	echo $query;
	$followers = $connection->get("$query");
	print_r($followers);
	
	
	$cursor = $followers->next_cursor;
	
	$errorObject = $followers->errors;
			$error = $errorObject[0]->code;
			$errorMessage = $errorObject[0]->message;

	if($error != '88') {
		
		$users = $followers->users;
		
		foreach($users as $user) {
			$userID = $user->id;
			$createdAt = $user->created_at;
			
			if ($debug) {
				echo "UserID: $userID<br>";
				echo "Created at: $createdAt<br>";
				echo "<br>";
			}
		}
		
		array_push($followersToDM, $userID);
	} else {
		echo 'Please wait at least 15 minutes from when you first saw this message for the api limit on your follower lookup to refresh. '.$errorMessage.".";
		?><script type="text/javascript">hide_loading_gif();</script><?php
		die();
	}
	
	$cursor = 0;
	
} while($cursor > 0);




	
?>
