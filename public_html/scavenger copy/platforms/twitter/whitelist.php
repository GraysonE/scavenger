<?php
require 'html.inc';
require 'header.php';
require 'config.php';
require 'functions.php';


$whitelistedUserSearch = $_POST['whitelistedUserSearch'];
$whitelistedUserID = $_POST['whitelistedUserID'];
$whitelistedUserScreenName = strtolower($_POST['whitelistedUserScreenName']);

$viewWhitelist = $_POST['viewWhitelist'];

echo "<h4>Whitelisted users you choose here will not be unfollowed when using the <a href='unfollow-all.php'>Unfollow All</a> feature.</h4>";
	
echo '<form class="copy_form" method="POST" >';
	echo '<input class="form_input" type="text" name="whitelistedUserSearch" tabindex="0" placeholder="Search user to whitelist" />';
	echo '<button class="button" type="submit" name="submitTargetAccount" tabindex="1">Search Users</button>';
echo '</form>';
	
/*
echo '<form class="copy_form" method="POST" >';
	echo '<button class="button" type="submit" name="viewWhitelist" value="1" tabindex="2">View Whitelist</button>';
echo '</form>';
*/

if (($whitelistedUserSearch != '') && ($whitelistedUserID == '') && ($whitelistedUserScreenName == '')) {
	
	search_user($connection, $debug, $whitelistedUserSearch, $dataFileDirectory);
	
} 

if (($whitelistedUserID != '') && ($whitelistedUserScreenName != '')) {
	
	
	
	whitelist($dataFileDirectory, $whitelistedUserID, 'ids');
	whitelist($dataFileDirectory, $whitelistedUserScreenName, 'screen-names');
	
}


/*
if ($viewWhitelist) {
	
}
*/

view_whitelist($dataFileDirectory, "screen-names");

echo '</div>';