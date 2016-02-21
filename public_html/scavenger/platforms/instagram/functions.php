<?php
	
function getFollowers($instagram, $instagramUserID, $debug) {
	// get all user likes
	$data = $instagram->getUserFollower($instagramUserID);
	
	if ($debug) {echo "<br>";print_r($data);}
	
	$followerArray = array();
	$i=1;
	do {
		
		$followerIds = $data->data;
		
		foreach($followerIds as $follower) {
			$followerId = $follower->id;
			$followerArray[$i] = $followerId;
				if ($debug) {echo "follower $i: $followerId<br>";}
			$i++;
		}
		
		$data = $instagram->pagination($data);
		$cursor = $data->pagination->next_cursor;
	} while ($cursor > 0);
	
	if ($debug) {echo "<br>";print_r($data);}
	
	$cursor = $data->pagination->next_cursor;
	
	return $followerArray;
}



function iAmFollowing($instagram, $instagramUserID, $debug) {
	// get all user likes
	$data = $instagram->getUserFollows($instagramUserID);
	
	if ($debug) {echo "<br>";print_r($data);}
	
	$followingArray = array();
	$i=1;
	do {
		
		$followerIds = $data->data;
		
		foreach($followerIds as $follower) {
			$followerId = $follower->id;
			$followingArray[$i] = $followerId;
				if ($debug) {echo "follower $i: $followerId<br>";}
			$i++;
		}
		
		$data = $instagram->pagination($data);
		$cursor = $data->pagination->next_cursor;
	} while ($cursor > 0);
	
	if ($debug) {echo "<br>";print_r($data);}
	
	$cursor = $data->pagination->next_cursor;
	
	return $followingArray;
}







function copy_followers($instagram, $instagramUserID, $debug, $targetUser, $date, $email, $debugEmails, $name) {
$debug = false;

/*
	$numberOfFollowers = number_of_followers($connection, $myScreenName);

	$numberOfRequests_USER = (int) $numberOfFollowers/5000;
	$numberOfRequests_target = (int) 15 - ceil($numberOfRequests_USER); // Request as many followers from the user as possible
	// $numberOfRequests_target = (int) ceil($numberOfRequests_USER); // Request only the amount of followers that you follow


	//$numberOfRequests_target = 2;

	if($debug){echo "Number of target user requests to Twitter's API to make: $numberOfRequests_target<br>";}
*/

	
	$followerArray = getFollowers($instagram, $instagramUserID, $debug);
		if ($debug) {echo "got my followers";}
	$followingArray = iAmFollowing($instagram, $instagramUserID, $debug);
		if ($debug) {echo "<br>got who I'm following";}
	$targetFollowerArray = getFollowers($instagram, $targetUser, $debug);
		if ($debug) {echo "<br>got target user's followers";}
	
	echo "<br><br><hr><br><br>";
	$debug=true;
	$targetFollowersNarrowed = array_diff($targetFollowerArray, $followerArray, $followingArray);
		if ($debug) { echo '<br>NARROWED TARGET FOLLOWERS:<BR>'; print_r($targetFollowersNarrowed);}
	
	$message = '';
	$i=1;
	foreach( $targetFollowersNarrowed as $targetFollower ) {
	
		$isFollowing = in_array( $targetFollowerArray, $followerArray );
		$imFollowing = in_array($targetFollowerArray, $followingArray);
		//echo "$targetFollower: ".( $isFollowing ? 'OK' : '!!!' )."<br/>";
	
		if(( !$isFollowing ) && (!$imfollowing))
		{
	
			$parameters = array( 'user_id' => $targetFollower );
			$userID = $parameters['user_id'];
			//if($debug){echo "Possible follower detected: ".$userID."<br>";}
	
			//Investigate User Before Following
			$userData = $instagram->getUserRelationship($userID);
				if ($debug) {echo"<br>";print_r($userData);}

			$privateUser = $userData->data->target_user_is_private;
			$outgoingStatus = $userData->data->outgoing_status;


			if (($outgoingStatus != 'requested') && ($privateUser != 1)) {

				$follow = $instagram->modifyRelationship('follow', $userID);

				if($debug){
					echo '<br><small>';
					print_r($follow);
					echo '</small><br>';
				}
				
				$i++;


			}

		}

	}

}