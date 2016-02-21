<?php
	
/*
global $shortcode_tags;
echo "<pre>"; print_r($shortcode_tags); echo "</pre>";
*/
	
	
ini_set('max_execution_time', 30000);
	
	
	

	
	
function find_unfollowers($connection, $myScreenName, $count, $sleep, $debug, $numberOfFollowers, $cursorTarget) {
	
	$numberOfRequests_USER = (int) $numberOfFollowers/5000;
	
	// NEEDS TO BE ROUNDED DOWN AND APPLIED
	$numberOfRequests_target = (int) 15 - $numberOfRequests_USER;
	
	//Unfollower list - array
	$unfollowerArray = array();
	
	
	// YOUR FOLLOWERS
	$followersComprehensive = array();
	
	$cursor = "-1";
	
	do {
		//echo '<h2>Your Followers</h2>';
		$searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";
	
		echo '<br>';
	  $follows = $connection->get("$searchFollowersAPI");
	  	//if($debug){print_r($follows);echo '<br>';}
		  $followersComprehensive = array_merge($follows->ids, $followersComprehensive);
		  $cursor = $follows->next_cursor;
		  	//if($debug) {echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';}
	} while ($cursor > 0);
	
	
	if($followersComprehensive) {		
	
		// PEOPLE YOU'RE FOLLOWING
		$friendsComprehensive = array();
		
		do {
			//echo '<h2>People You Are Following</h2>';
			$searchFriendsAPI = "https://api.twitter.com/1.1/friends/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";
			
			echo '<br>';
		  $friends = $connection->get("$searchFriendsAPI");
		  	//if($debug){print_r($friends);echo '<br>';}
		  $friendsComprehensive = array_merge($friends->ids, $friendsComprehensive);
		  $cursorTarget = $follows->next_cursor;
		  	//if($debug) {echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';}
		  
		} while ($cursorTarget > 0);
		
		
		//FIND PEOPLE THAT AREN'T FOLLOWING YOU BUT YOU'RE FOLLOWING THEM AND UNFOLLOW
		$i=1;
		foreach( $friendsComprehensive as $iFollow ) {
			$isFollowing = in_array( $iFollow, $followersComprehensive );
			
			//echo "<hr>$iFollow: ".( $isFollowing ? 'OK' : '!!!' )."<br/>";
			
			if( !$isFollowing ) {
				$parameters = array( 'user_id' => $iFollow );
				$userID = $parameters['user_id'];
					if($debug){echo "unfollower detected: ".$userID."<br>";}	
					
				$unfollowerArray[$i] = $userID;
					if($debug){print_r($unfollowerArray);echo'<br>';}
				//$unfollowUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/destroy.json?user_id=$userID");
					//if($debug){ echo "<strong>UNFOLLOWED</strong><br>";print_r($unfollowUsers_json);echo "<hr>";}
				//echo "<strong>$i accounts unfollowed.</strong><br><br>";
				$i++;
			} 
			//if ($i++ === 100 ) break;
			
		}
	
	
	} else {
	  	echo 'Please wait for 15 minutes for the api limit to refresh.';
	}

	
	return $unfollowerArray;
	
	
}



function unfollow($unfollowerArray, $connection) {
	
	$i=1;
	foreach($unfollowerArray as $unfollower) {
		
		echo "$i: $unfollower<br>";
		$unfollowUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/destroy.json?user_id=$unfollower");
		echo '<strong>UNFOLLOWED</strong><hr><br><br>';
		$i++;
	}
	
	
	
}




























function copy_followers($connection, $myScreenName, $count, $sleep, $debug, $numberOfFollowers, $cursorTarget) {


	$numberOfRequests_USER = (int) $numberOfFollowers/5000;
	$numberOfRequests_target = (int) 15 - ceil($numberOfRequests_USER);
		if($debug){echo "Number of requests to Twitter's API to make: $numberOfRequests_target<br>";}

	// YOUR FOLLOWERS
	$followersComprehensive = array();
	$cursor = "-1";
	do {
		//if($debug){echo '<h2>Your Followers</h2>';echo '<br>';}
		$searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";
	
	  $follows = $connection->get("$searchFollowersAPI");
	  	//if($debug){print_r($follows);echo '<br>';}
	  $followersComprehensive = array_merge($follows->ids, $followersComprehensive);
	  $cursor = $follows->next_cursor;
	  	//if($debug) {echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';}
	} while ($cursor > 0);
	
	
	if($followersComprehensive) {		
	
		// PEOPLE FOLLOWING TARGET ACCOUNT
		$targetFollowersComprehensive = array();
		
		$requests = 1;
		do {
			
			//if($debug){echo '<h2>Targeted Users</h2>';echo '<br>';}
			$searchTargetAPI = "https://api.twitter.com/1.1/followers/ids.json?screen_name=$twitterUser&count=$count";
			
		  $targetFollowers = $connection->get("$searchTargetAPI");
		  	//if($debug){print_r($targetFollowers);echo '<br>';}
		  $targetFollowersComprehensive = array_merge($targetFollowers->ids, $targetFollowersComprehensive);
		  $cursorTarget = $targetFollowers->next_cursor;
		  	//if($debug) {echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';}
		  $requests++;
		} while (($cursorTarget > 0) && ($requests < $numberOfRequests_target));
	
	
		$i=1;
		foreach( $targetFollowersComprehensive as $targetFollowers ) {

			$isFollowing = in_array( $targetFollowers, $followersComprehensive );
			
			echo "$targetFollowers: ".( $isFollowing ? 'OK' : '!!!' )."<br/>";
			
			if( !$isFollowing )
			{
				$parameters = array( 'user_id' => $targetFollowers );
				$userID = $parameters['user_id'];
					if($debug){echo "Possible follower detected: ".$userID."<br>";}		
				$followUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/create.json?user_id=$userID&follow=true");
					if($debug){ echo "<strong>FOLLOWED</strong><br>";print_r($followUsers_json);echo "<hr>";}
				echo "<strong>$i accounts followed.</strong><br>";
				$i++;
			} 
			//if ($i++ === 100 ) break;
			
		}
	
	
	
	
		
	} else {
	  	echo 'Please wait for 15 minutes for the api limit to refresh.';
	}
	
	
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
// DEPRECATED FUNCTIONS

function unfollow_all_updated($connection, $myScreenName, $count, $sleep, $debug) {
	
	//Unfollower list - array
	$unfollowerArray = array();
	
	
	// YOUR FOLLOWERS
	$followersComprehensive = array();
	$cursor = "-1";
	do {
		//echo '<h2>Your Followers</h2>';
		$searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";
	
		echo '<br>';
	  $follows = $connection->get("$searchFollowersAPI");
	  	//if($debug){print_r($follows);echo '<br>';}
		  $followersComprehensive = array_merge($follows->ids, $followersComprehensive);
		  $cursor = $follows->next_cursor;
		  	//if($debug) {echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';}
	} while ($cursor > 0);
	
	
	if($followersComprehensive) {		
	
		// PEOPLE YOU'RE FOLLOWING
		$friendsComprehensive = array();
		$cursor = "-1";
		do {
			//echo '<h2>People You Are Following</h2>';
			$searchFriendsAPI = "https://api.twitter.com/1.1/friends/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";
			
			echo '<br>';
		  $friends = $connection->get("$searchFriendsAPI");
		  	//if($debug){print_r($friends);echo '<br>';}
		  $friendsComprehensive = array_merge($friends->ids, $friendsComprehensive);
		  $cursor = $follows->next_cursor;
		  	//if($debug) {echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';}
		  
		} while ($cursor > 0);
		
		
		//FIND PEOPLE THAT AREN'T FOLLOWING YOU BUT YOU'RE FOLLOWING THEM AND UNFOLLOW
		$i=1;
		foreach( $friendsComprehensive as $iFollow )
		{
			$isFollowing = in_array( $iFollow, $followersComprehensive );
			
			echo "<hr>$iFollow: ".( $isFollowing ? 'OK' : '!!!' )."<br/>";
			
			if( !$isFollowing )
			{
				$parameters = array( 'user_id' => $iFollow );
				$userID = $parameters['user_id'];
					if($debug){echo "unfollower detected: ".$userID."<br>";}	
					
				$unfollowerArray = array_push($userID, $unfollowerArray);	
				$unfollowUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/destroy.json?user_id=$userID");
					//if($debug){ echo "<strong>UNFOLLOWED</strong><br>";print_r($unfollowUsers_json);echo "<hr>";}
				echo "<strong>$i accounts unfollowed.</strong><br><br>";
				$i++;
			} 
			//if ($i++ === 100 ) break;
			
		}
	
	
	} else {
	  	echo 'Please wait for 15 minutes for the api limit to refresh.';
	}

	
	
	
	
}

	
	
function unfollow_all($connection, $cursor, $myScreenName, $count, $sleep, $debug) {
	
	$searchFollowersAPI = "https://api.twitter.com/1.1/friends/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";
	
	$userIDs_json_twitterUserData = $connection->get("$searchFollowersAPI");
	
	
		
	if ($debug) {print_r($userIDs_json_twitterUserData);}
	
	$cursor = $userIDs_json_twitterUserData->next_cursor;
	
	// User IDs from who I follow
	$userIDs_array_twitterUser = $userIDs_json_twitterUserData->ids;
		
		$k=0;
		$i=0;
		$unfollowerList = array();
		
		foreach($userIDs_array_twitterUser as $userID) {
			
			echo '<br><hr><br>';
			if ($debug) {echo '<strong>'.$i.' User ID:</strong> '.$userID.'';}

			$searchURL = "https://api.twitter.com/1.1/friendships/show.json?source_id=$userID&target_screen_name=$myScreenName";
			
			$userID_search = $connection->get("$searchURL");
			echo '<br>';
			
			if ($debug) {print_r($userID_search);echo '<br>';}
			
			// Information about user
			$userScreenName = $userID_search->relationship->source->screen_name;
				if ($debug){echo '<strong>Screen Name: </strong>'.$userScreenName;echo '<br>';}
			$userFollowingYou = $userID_search->relationship->source->following;
				if ($debug) {echo '<strong>Following You: </strong>'.$userFollowingYou;echo '<br>';}
			$userFollowedByYou = $userID_search->relationship->source->followed_by;
				if ($debug) {echo '<strong>Followed By Me: </strong>'.$userFollowedByYou;echo '<br>';}
			
			
			$error = get_object_vars($userID_search);
			$error = $error['errors'];
			
			if ($error != NULL) {
				$error = get_object_vars($error[0]);
				//echo '<br>';
				$error = $error['code'];
				//echo $error;
				//echo 'Error Code: '.$error;
				
				if (($error == '88') || ($error == '130')) {
					echo '<br><hr>';
					echo 'Rate limit reached, sleep for 15 minutes.';
					sleep( 60*15 );
					echo '<br><hr>';
				}
			}
			
			
			
			
				// Create unfollower array, display user, unfollow
				if (($userFollowedByYou) && (!$userFollowingYou)) {
									
	 				array_push($unfollowerList, array($userID, $userScreenName));
					echo '<strong>Unfollower:</strong> YES';
					echo '<br>';
					$unfollowUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/destroy.json?user_id=$userID");
						if($debug){print_r($unfollowUsers_json); echo "<br>";}
					echo $userScreenName.' <strong>unfollowed.</strong><br>';
					echo "<strong>$k Users Unfollowed </strong>";
					$k++;
				}
				
					//if ($debug){var_dump($unfollowerList);echo '<br>';}

			$i++;
			sleep( $sleep ); // sleep to avoid exceeding api rate
			
		}

	return $cursor;
		
}


















	
function show_unfollowers($unfollowerList) {
	if ($unfollowerList) {
			foreach($unfollowerList as $unfollowerData) {
			
				echo '<br>';
				$unfollowerUserID = $unfollowerData[0];
				echo $unfollowerUserID;
				echo'<br>';
				$unfollowerScreenName = $unfollowerData[1];
				echo $unfollowerScreenName;
				echo 'Unfollow<hr>';
				$unfollowUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/destroy.json?user_id=$unfollowerUserID");
			}
	
			echo '<br>'.$unfollowerUserID;
		}
}

?>