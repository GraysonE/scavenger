<?php
	
require 'html.inc';
require 'config.php';
require 'header.php';
require 'functions.php';
require ("$dataFileDirectory/user-cursor.php");

$twitterUser_post = $_POST['screenName'];
$userSearch = $_POST['userSearch'];
$userID_search = $_POST['userID'];

if ($debug) {
	echo '$_POST variables:<br>';
	echo "twitterUser: $twitterUser<br>";
	echo "userSearch: $userSearch<br>";
	echo "userID_search: $userID_search<br>";
}

echo "<h4>Search a user, select the user you want to follow, then use the rest of Scavenger's features to follow or unfollow!</h4>";

echo '<form class="copy_form" method="POST" >';
	echo '<input class="form_input" type="text" name="userSearch" tabindex="0" placeholder="';
	if (isset($twitterUser)) {
		echo 'Following @'.$twitterUser.'" />';
	} else {
		echo 'Search user to set. . ." />';
	}
	
	echo '<button class="button" type="submit" name="submitTargetAccount" tabindex="2">Search Users</button>';
echo '</form>';

if (($userSearch != '') && ($userID_search == '') && ($twitterUser_post == '')) {
	
	echo '<div class="search_wrap">';
		search_user_to_set($connection, $debug, $userSearch);
	echo '</div>';
	
} elseif (isset($userID_search)) {
	
	date_default_timezone_set('America/Denver');
	$date = new DateTime();	
	$time = $date->format('h:i:s m-d-Y');
	$path = dirname ( __FILE__ );
	$file = "$dataFileDirectory/user-cursor.php";
	$content = "\n".'<?php /* '.$time.' - set by set-user.php */ $twitterUser = "'.$twitterUser_post.'"; $cursorTarget_post = "-1";?>';
	
	if ($debug) {
		echo "date: $time<br>";
		echo "file path: $path<br>";
		echo "absolute file path: $file<br>";
		echo "twitterUser: $twitterUser_post<br>";
		echo "content: $content<br>";
	}
	
	// Write the contents to the file, 
	// using the FILE_APPEND flag to append the content to the end of the file
	// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
	if (!$frustratingHosting) {
		chmod ( $file , 0600 );
	}
	
	file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
	
	echo '<h3>Now following all active users who follow <strong>@'.$twitterUser_post.'</strong>.</h3>';
		
}
//require 'footer.inc';
?>