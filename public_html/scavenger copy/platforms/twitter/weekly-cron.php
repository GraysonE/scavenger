<?php
require ('config.php');
require ('functions.php');
	
// WHITELIST ACTIVE USERS
whitelistActiveMentionUsers($connection, $dataFileDirectory);
whitelistActiveDMUsers($connection, $dataFileDirectory);

// FOLLOW WHITELIST
followWhitelist($connection, $dataFileDirectory);
?>