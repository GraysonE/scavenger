<?php

require ('config.php');
require ('functions.php');

/*
$path = dirname ( __FILE__ );
$file = "$dataFileDirectory/user-cursor.php";
$directory = "$path/*";
if (!$frustratingHosting) {
	chmod ( $file , 0600 );
}

$current_date = date(d);

if ($current_date = 13) {
	unlink($file);
	$myfile = fopen("$file", "w") or die("Unable to open file!");
	fclose($myfile);
}
*/

$followingArrayIDs = im_following($connection, $screenName, $count, $debug, 'ids');
$followingArrayScreenNames = im_following($connection, $screenName, $count, $debug, 'screen-names');

var_dump($followingArrayIDs);
echo '<br><br><hr><br><br>';
var_dump($followingArrayScreenNames);

foreach ($followingArrayIDs as $whitelistedUser) {
	//userLookup($connection, $debug, $whitelistedUser);
	whitelist($dataFileDirectory, $whitelistedUser, 'ids');
}

foreach ($followingArrayScreenNames as $whitelistedUser) {
	//userLookup($connection, $debug, $whitelistedUser);
	whitelist($dataFileDirectory, $whitelistedUser, 'screen-names');
}

/*
$getRateLimit = "https://api.twitter.com/1.1/application/rate_limit_status.json";
$rateLimit = $connection->get("$getRateLimit");

var_dump($rateLimit);
*/

?>