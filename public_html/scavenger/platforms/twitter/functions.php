<?php
ini_set('max_execution_time', 30000);

function loading_gif() {
	echo '<div id="loading_gif_wrap">';
		echo '<h4>Crunching. . . This might take a long time, please be patient.</h4>';
		echo '<br>';
		echo '<img id="loading_gif" src="../../assets/img/ajax_loader.gif" />';
	echo '</div>';
}



function find_unfollowers($connection, $myScreenName, $count, $debug) {

	$numberOfFollowers = number_of_followers($connection, $myScreenName, false);

	$cursor = "-1";
	// YOUR FOLLOWERS
	$followersComprehensive = array();
	
	do {
		
		$searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";

		$follows = $connection->get("$searchFollowersAPI");
		
		$cursor = $follows->next_cursor;
		
		if($debug){
			echo '<h2>Your Followers</h2>';
			echo "<br>$searchFollowersAPI"; 
			echo '<br>'; 
			print_r($follows);
			echo '<br>';
			echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';
		}
		$errorObject = $follows->errors;
			$error = $errorObject[0]->code;
			$errorMessage = $errorObject[0]->message;

		if($error != '88') {
			$followersComprehensive = array_merge($follows->ids, $followersComprehensive);
		} else {
			echo '<div class="errorMessage">Please wait at least 15 minutes from when you first saw this message for the api limit on your follower lookup to refresh. '.$errorMessage.". (find_unfollowers(1))</div>";
			?>
			<script src="../../assets/js/functions.js" type="text/javascript"></script>
			<?php
			die();
		}
		
		
	} while ($cursor > 0);


	$cursor = "-1";

	if($followersComprehensive) {

		// PEOPLE YOU'RE FOLLOWING
		$friendsComprehensive = array();

		do {
			
			$searchFriendsAPI = "https://api.twitter.com/1.1/friends/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";

			$friends = $connection->get("$searchFriendsAPI");
			
			$cursor = $follows->next_cursor;
			
			if($debug){
				echo '<h2>People You Are Following</h2>';
				echo '<br>'; 
				print_r($friends);
				echo '<br>';
				echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';
			}
			$errorObject = $friends->errors;
			$error = $errorObject[0]->code;
			$errorMessage = $errorObject[0]->message;

			if($error != '88') {
				$friendsComprehensive = array_merge($friends->ids, $friendsComprehensive);
			} else {
				echo '<div class="errorMessage">Please wait at least 15 minutes from when you first saw this message for the api limit on your friends lookup to refresh. '.$errorMessage.". (find_unfollowers(2))</div>";
				?>
				<script src="../../assets/js/functions.js" type="text/javascript"></script>
				<?php
				die();
			}
			
			
		} while ($cursor > 0);


		//Unfollower list - array
		$unfollowerArray = array();
		
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

	
	
	} 


return $unfollowerArray;

}















function find_followers($connection, $myScreenName, $count, $debug, $dataFileDirectory) {
	
	$numberOfFollowers = number_of_followers($connection, $myScreenName, true);
	
	$cursor = "-1";
	// PEOPLE YOU'RE FOLLOWING
	$friendsComprehensive = array();

	do {
		
		$searchFriendsAPI = "https://api.twitter.com/1.1/friends/ids.json?cursor=$cursor&screen_name=$myScreenName&count=$count";

		$friends = $connection->get("$searchFriendsAPI");
		
		$cursor = $follows->next_cursor;
		
		if($debug){
			echo '<h2>People You Are Following</h2>';
			echo '<br>'; 
			print_r($friends);
			echo '<br>';
			echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';
		}
		$errorObject = $friends->errors;
		$error = $errorObject[0]->code;
		$errorMessage = $errorObject[0]->message;

		if($error != '88') {
			$friendsComprehensive = array_merge($friends->ids, $friendsComprehensive);
		} else {
			echo '<div class="errorMessage">Please wait at least 15 minutes from when you first saw this message for the api limit on your friends lookup to refresh. '.$errorMessage.".</div>";
			?>
			<script src="../../assets/js/functions.js" type="text/javascript"></script>
			<?php
			die();
		}
		
		
	} while ($cursor > 0);



	// grab whitelisted users
	$path = dirname ( __FILE__ );
	$whitelistedUsers = file_get_contents("$dataFileDirectory/whitelisted-users-ids.csv");
	
	$whitelistedUsers = explode(', ', $whitelistedUsers);
	
	
	if ($debug) {
		foreach($whitelistedUsers as $whitelistedIDs) {
			echo "$userID<br>";
		}
	}

	//Unfollower list - array
	$followingArray = array();
	
	//FIND PEOPLE THAT AREN'T IN YOUR WHITELIST AND UNFOLLOW
	$i=1;
	foreach( $friendsComprehensive as $following ) {
		
		$isWhitelisted = in_array( $following, $whitelistedUsers );
		

		if( !$isWhitelisted ) {
			$parameters = array( 'user_id' => $following );
				$userID = $parameters['user_id'];
				if($debug){echo "unfollower detected: ".$userID."<br>";}
				
			$followingArray[$i] = $following;
			
			if($debug){print_r($followingArray);echo'<br>';}
			$i++;
		}
		//if ($i++ === 100 ) break;

	}


return $followingArray;
}






function unfollow_by_id($connection, $users, $dataFileDirectory) {

	$i=1;
	echo "<div id='wrap'>";
	
	$whitelistArray = getWhitelist($dataFileDirectory, 'ids');
	
	if ($whitelistArray == '') {
		$finalUnfollowArray = $users;
	} else {
		$finalUnfollowArray = array_diff($users, $whitelistArray);
	}
	
	
	
	foreach($finalUnfollowArray as $unfollower) {

		echo "$i: $unfollower - ";
		$unfollowUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/destroy.json?user_id=$unfollower");
		
		echo '<strong>Unfollowed</strong><hr>';
		
		$i++;
	}
	
	echo '</div>';

}
























function follow_check($connection, $debug, $twitterUser, $cursorTarget) {

	$count = 1;

	$followUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/create.json?user_id=$twitterUser&follow=true");

	$errorObject = $followUsers_json->errors;
	$followError = $errorObject[0]->code;

	$searchTargetAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursorTarget&screen_name=$twitterUser&count=$count";
	//echo $searchTargetAPI;
	$targetFollowers = $connection->get("$searchTargetAPI");
	//if($debug){print_r($targetFollowers);echo '<br>';}

	$errorObject = $targetFollowers->errors;
	$targetError = $errorObject[0]->code;

	if (($followError  != '161') || ($targetError != '88')) {
		return true;
	} elseif ($followError == '161') {
		return '161';
	} elseif ($targetError == '88') {
		return '88';
	}
}






function number_of_followers($connection, $myScreenName, $friend) {
	// Find number of followers for user
	$numberOfFollowersURL = "https://api.twitter.com/1.1/users/lookup.json?screen_name=$myScreenName";
	$numberOfFollowersURL_json = $connection->get("$numberOfFollowersURL");
	
	if (isset($numberOfFollowersURL_json->errors)) {
		$errorObject = $numberOfFollowersURL_json->errors;
		$ErrorCode = $errorObject[0]->code;
		$ErrorMessage = $errorObject[0]->message;
		
		if ($ErrorMessage != '') {
			echo "<div class='errorMessage'>Could not look up your user information. ".$ErrorMessage.".</div>";
			die();
		}
	}
	
	
	
		//print_r($numberOfFollowersURL_json);
		if ($friend) {
			$numberOfFollowers = $numberOfFollowersURL_json[0]->friends_count;
		} else {
			$numberOfFollowers = $numberOfFollowersURL_json[0]->followers_count;
		}
		
			//if($debug){echo $myScreenName.' followers count: '.$numberOfFollowers;}
			
		return $numberOfFollowers;
}















function get_followers($connection, $screenName, $count, $debug, $me, $numberOfRequests) {

	// YOUR FOLLOWERS
	$followersComprehensive = array();
	$cursor = "-1";
	$i=0;
	
	//echo '<br>Number of Requests: '.$numberOfRequests;
	
	do {
		
		$i++;
		//echo "<br>$i";
		
		if ($numberOfRequests) {
			if ($i == $numberOfRequests) {
				break;
			}
		}
		
		
		
		if($debug){echo '<h2>Your Followers</h2>';echo '<br>';}
		$searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$screenName&count=$count";
		//if($debug){echo $searchFollowersAPI;}
		$follows = $connection->get("$searchFollowersAPI");
		$errorObject = $follows->errors;
		$error = $errorObject[0]->code;
		$errorMessage = $errorObject[0]->message;

		//if($debug){print_r($follows);echo '<br>';}

		if($error != '88') {
			$followersComprehensive = array_merge($follows->ids, $followersComprehensive);
			$cursor = $follows->next_cursor;
		} else {
			echo '<div class="errorMessage">Please wait at least 15 minutes from when you first saw this message for the api limit on your follower lookup to refresh. '.$errorMessage.".</div>";
			?>
			<script src="../../assets/js/functions.js" type="text/javascript"></script>
			<?php
			die();
		}
		//if($debug) {echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';}
	} while ($cursor > 0);
	
	return $followersComprehensive;
}







function im_following($connection, $screenName, $count, $debug, $dataType) {
	// PEOPLE I'M FOLLOWING
	$friendsComprehensive = array();
	$cursor = "-1";
	$requests = 1;
	do {
		if($debug){echo '<h2>You Are Following</h2>';echo '<br>';}
		$searchFriendsAPI = "https://api.twitter.com/1.1/friends/list.json?cursor=$cursor&screen_name=$screenName&skip_status=true&include_user_entities=false&count=200";
		//if($debug){echo $searchFriendsAPI;}
		$friends = $connection->get("$searchFriendsAPI");

		$cursor = $friends->next_cursor;

		$errorObject = $friends->errors;
		$error = $errorObject[0]->code;
		$errorMessage = $errorObject[0]->message;

		if($debug){print_r($friends);echo '<br>';}

		if($error != '88') {
			$friends = $friends->users;
			
			if ($dataType == 'ids') {
				foreach($friends as $friend) {
					$friend = $friend->id;
					array_push($friendsComprehensive, $friend);
				}
			} elseif ($dataType == 'screen-names') {
				foreach($friends as $friend) {
					$friend = $friend->screen_name;
// 					echo "<br>$friend";
					array_push($friendsComprehensive, $friend);
				}
			}
			
			
		} else {
			echo '<div class="errorMessage">Please wait at least 15 minutes from when you first saw this message for the api limit on who you follow to refresh. '.$errorMessage.".</div>";
			//break;
			?>
			<script src="../../assets/js/functions.js" type="text/javascript"></script>
			<?php
			die();
		}
		$requests++;
		if($debug) {echo "<strong>Next Cursor: </strong>$cursor";echo '<hr>';}
	} while (($requests < 16) && ($cursor > 0));

	if ($debug) {
		echo '<br><br> FRIENDS COMPREHENSIVE<BR>';
		print_r($friendsComprehensive);
	}
	
	return $friendsComprehensive;
}



function copy_followers($connection, $db, $myScreenName, $count, $targetCount, $debug, $cursorTarget, $twitterUser, $date, $email, $debugEmails, $name) {
	
		echo '<br>Retrieving your followers. . .';
	$followersComprehensive = get_followers($connection, $myScreenName, 5000, $debug, true, false);
		echo '<br>Retrieving who you\'re following. . .';
	$friendsComprehensive = im_following($connection, $myScreenName, $count, $debug, 'ids');
		
		
		
		// Following logic
		$numberOfFriends = number_of_followers($connection, $myScreenName, true);
		$numberOfFollowers = number_of_followers($connection, $myScreenName, false);
			echo "<br>$myScreenName has $numberOfFollowers followers";
			echo "<br>$myScreenName has $numberOfFriends friends";

		$numberOfRequests_USER = (int) ($numberOfFollowers+$numberOfFriends)/5000;
			//echo "<br>Number of Twitter API requests made on $myScreenName: $numberOfRequests_USER";
		$numberOfRequests = (int) 15 - ceil($numberOfRequests_USER); // Request as many followers from the user as possible
			//echo "<br>Number of requests to make on $twitterUser: $numberOfRequests_target";
		
		echo '<br>Targeted followers to follow. . .';
	$targetFollowersComprehensive = get_followers($connection, $twitterUser, 5000, $debug, false, $numberOfRequests);
		
		echo "<br>Narrowing target followers. . .";
	$targetFollowersNarrowed = array_diff($targetFollowersComprehensive, $followersComprehensive, $friendsComprehensive);
	if ($debug) { echo '<br>NARROWED TARGET FOLLOWERS:<BR>'; print_r($targetFollowersNarrowed);}
	
		
	
	$message = '';
	$i=1;
	foreach( $targetFollowersNarrowed as $targetFollower ) {

		$isFollowing = in_array( $targetFollower, $followersComprehensive );
		$imFollowing = in_array($targetFollower, $friendsComprehensive);
		//echo "$targetFollower: ".( $isFollowing ? 'OK' : '!!!' )."<br/>";

		if(( !$isFollowing ) && (!$imfollowing))
		{

			$parameters = array( 'user_id' => $targetFollower );
			$userID = $parameters['user_id'];
			//if($debug){echo "Possible follower detected: ".$userID."<br>";}

			//Investigate User Before Following
			$userInvestigationURL = "https://api.twitter.com/1.1/users/lookup.json?user_id=$userID";
			$userInvestigation_json = $connection->get("$userInvestigationURL");

			$errorObject = $userInvestigation_json->errors;
			$ErrorCode = $errorObject[0]->code;
			$ErrorMessage = $errorObject[0]->message;

			if ($ErrorMessage != '') {
				if ($debug) {echo "<div class='errorMessage'>Could not research potential user. ".$ErrorMessage.".</div>";}
				break;
			}

			$userInvestigation_array = $userInvestigation_json[0];



			$requestScreenName = $userInvestigation_array->screen_name;
			$followRequestSent = $userInvestigation_array->follow_request_sent;
			$followingUser = $userInvestigation_array->following;
			$followersCount = $userInvestigation_array->followers_count;
			$friendsCount = $userInvestigation_array->friends_count;
			$favoritesCount = $userInvestigation_array->favourites_count;



			if (!$debug) {
				$message .= '<small id='.$userID.' class="user_info">';
				$message .= "Screen Name: $requestScreenName<br>";
				//echo "UserID: $userID<br>";

				$message .= "Follow Request Sent: ";
				if ($followRequestSent) {
					$message .= 'Yes';
				} else {
					$message .= 'No';
				}
				$message .= '<br>';

				$message .= "Already Following: ";
				if ($followingUser) {
					$message .= 'Yes';
				} else {
					$message .= 'No';
				}
				$message .= '<br>';

				$message .= "Following more than followed: ";
				if ($friendsCount > $followersCount) {
					$message .= 'Yes';
				} else {
					$message .= 'No';
				}
				$message .= '<br>';

				$message .= "Active (more than 10 favorites): ";
				if ($favoritesCount > 10) {
					$message .= 'Yes';
				} else {
					$message .= 'No';
				}
				$message .= '<br>';
				$message .= '</small>';
			}

			// if ((!$followRequestSent) && (!$followingUser) && ($friendsCount > $followersCount) && ($favoritesCount > 10)) {
			if ((!$followRequestSent) && (!$followingUser) && ($favoritesCount > 10)) {

				$followUsers_json = $connection->post("https://api.twitter.com/1.1/friendships/create.json?user_id=$userID&follow=true");
				
				// DB work
/*
				$query = "SELECT userID FROM users WHERE screenName like '$myScreenName';";
				$result = $db->query("$query");
				$myDBUserID = $result->current_field;
				
				$query = "INSERT INTO followers (`userID`, `followerID`) VALUES ('$myDBUserID', '$userID');";
				$result = $db->query("$query");
				
*/
				
				
				if($debug){
					echo '<small>';
					print_r($followUsers_json);
					echo '</small><br>';
				}
				$followingUser = $followUsers_json->following;
				$errorObject = $followUsers_json->errors;
				$error = $errorObject[0]->code;
				if ((($followingUser) && ($error  != '160')) || (($followingUser) && ($error  != '161'))) {
					/*
						echo "<div class='followed'>";
						echo "<h4>$i accounts followed.</h4><br>";
						echo '</div>';
						echo "<br><hr><br><br>";
*/
				}
				$i++;


			}

		}
		if ($error == '161' ) break;

	}

	$messageDebug = $message;
	$k = $i-1;
	echo "<div class='followed'>";
	echo "<strong>$k accounts followed.</strong> To follow more, wait 15 minutes past: ";
	$date = new DateTime();
	echo $date->format('h:i:s');
	echo '</div>';
	echo '<br>';



	if ($k < 5) {

		$date = new DateTime();
		$time = $date->format('h:i:s m-d-Y');

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "To: $name" . "\r\n";
		$headers .= 'From: Scavenger App <grayson@gator3029.hostgator.com>' . "\r\n";

		// The message
		$message ="<html><head><title>Scavenger App Mail</title></head><body>";

		$message .= "<div>@$myScreenName followed only $k of @$twitterUser's followers. The app might have reached all of their followers. If you see this same email a couple more times, go to <a href='http://scavenger-app.com/scavenger/set-user.php'>Set User</a> to follow a different user list!</div><br>";

		$message .= "<h2>Debugging - $time</h2><br>";
		$message .= "<small>$messageDebug</small>";
		$message .="<br><br>Sincerely,<br>The Scavenger App";

		$message .= "</body></html>";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		mail( $email, "@$myScreenName Followed < 5 of @$twitterUser's Users", $message, $headers);

	} else {
		
		$date = new DateTime();
		$time = $date->format('h:i:s m-d-Y');

		if ($debugEmails) {
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "To: $name" . "\r\n";
			$headers .= 'From: Scavenger App <grayson@gator3029.hostgator.com>' . "\r\n";
	
			// The message
			$message = "<html><head><title>Scavenger App Mail</title></head><body>";
			// The message
			$message .= "<div>@$myScreenName followed $k people who follow @$twitterUser and finished at $time.</div>";
			$message .= "</body></html>";
			
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
	
			// Send
			mail($email, "@$myScreenName Followed @$twitterUser's Users", $message, $headers);
		}
		

	}



	return $cursorTarget;
}


function search_user($connection, $debug, $whitelistedUserSearch, $dataFileDirectory) {
	
	$count = 5;
	
	$searchQuery = "https://api.twitter.com/1.1/users/search.json?q=$whitelistedUserSearch&page=1&count=$count";
	
	$searchJson = $connection->get("$searchQuery");
	
	if($debug) {print_r($searchJson);}
	
		foreach($searchJson as $search) {
			$userID = $search->id;
			$name = $search->name;
			$screenName = $search->screen_name;
			$location = $search->location;
			$description = $search->description;
 			$picture = $search->profile_image_url;
 			$numberOfFollowers = $search->followers_count;
			
			$whitelistedUsers = searchWhitelist($dataFileDirectory, false, 'ids');
			
			if (!in_array($userID, $whitelistedUsers)) {
				echo "<form method='POST' class='form_search' action='whitelist.php' style='background-image: url(\"$picture\");'>";	
					echo '<div class="user_info">';
						//echo "User ID: $userID<br>";
						echo "<h3>@$screenName - $name</h3><br>";
						echo "<strong>Followers:</strong> $numberOfFollowers<br>";
						echo "<strong>Location:</strong> $location<br><br>";
						echo "<strong>Description:</strong> $description<br>";
						
						echo "<input type='hidden' name='whitelistedUserScreenName' value='$screenName'/>";
						echo "<button name='whitelistedUserID' value='$userID'>Whitelist User</button>";
					echo '</div>';
				echo '</form>';
			} else {
				echo "<h3>User @$screenName already whitelisted</h3>";
			}
			
			
		}
	
	
}

function search_user_to_set($connection, $debug, $userSearch) {
	
	$count = 5;
	
	$searchQuery = "https://api.twitter.com/1.1/users/search.json?q=$userSearch&page=1&count=$count";
	
	$searchJson = $connection->get("$searchQuery");
	
	if($debug) {print_r($searchJson);}
	
	
		foreach($searchJson as $search) {
			$userID = $search->id;
			$name = $search->name;
			$screenName = $search->screen_name;
			$location = $search->location;
			$description = $search->description;
 			$picture = $search->profile_image_url;
 			$numberOfFollowers = $search->followers_count;
			
			echo "<form method='POST' class='form_search' action='set-user.php'>";
				echo '<div class="user_info" style="background-image: url(\''.$picture.'\');">';
					echo '<div class="opacity_wrap">';
						//echo "User ID: $userID<br>";
						echo "<h3>@$screenName - $name</h3><br>";
						echo "<strong>Followers:</strong> $numberOfFollowers<br>";
						echo "<strong>Location:</strong> $location<br><br>";
						echo "<strong>Description:</strong> $description<br>";
						
						echo "<input type='hidden' name='screenName' value='$screenName'/>";
						echo "<button name='userID' value='$userID'>Set User</button>";
					echo '</div>'; // OPACITY WRAP
				echo '</div>';
			echo '</form>';
		}
	
}



function userLookup($connection, $userid) {
	$searchQuery = "https://api.twitter.com/1.1/users/lookup.json?user_id=$userid";
	
	$searchJson = $connection->get("$searchQuery");
	
	if($debug) {print_r($searchJson);}
	
	
		foreach($searchJson as $search) {
			$userID = $search->id;
			$name = $search->name;
			$screenName = $search->screen_name;
			$location = $search->location;
			$description = $search->description;
 			$picture = $search->profile_image_url;
 			$numberOfFollowers = $search->followers_count;
			
			echo "<form method='POST' class='form_search' action='set-user.php'>";
				echo '<div class="user_info" style="background-image: url(\''.$picture.'\');">';
					echo '<div class="opacity_wrap">';
						//echo "User ID: $userID<br>";
						echo "<h3>@$screenName - $name</h3><br>";
						echo "<strong>Followers:</strong> $numberOfFollowers<br>";
						echo "<strong>Location:</strong> $location<br><br>";
						echo "<strong>Description:</strong> $description<br>";
						
						echo "<input type='hidden' name='screenName' value='$screenName'/>";
						echo "<button name='userID' value='$userID'>Set User</button>";
					echo '</div>'; // OPACITY WRAP
				echo '</div>';
			echo '</form>';
		}
}







function findValidDirectMessages($connection, $dataType) {
	$directMessagesAPI = "https://api.twitter.com/1.1/direct_messages/sent.json?count=200";

	$directMessagesAPI = $connection->get("$directMessagesAPI");
	
	//print_r($directMessagesAPI);
	
	$goodMentionsArray = array();
	$i=0;
	foreach($directMessagesAPI as $dm) {
		
		$userID = $dm->recipient->id;
		$screenName = $dm->recipient->screen_name;
		$text = $dm->text;
		$genericString = "Hey, thanks so much for following";
		$containsGeneric = preg_match("/^$genericString/", $text);
		//echo $containsGeneric;
		$dmArray[$i] = array();
		
		if (!$containsGeneric) {
		    if ($dataType == 'screen-names') {
			    if (!in_array($screenName, $dmArray)) {
				     echo "$screenName direct messaged: $text<br><br>";
			    	$dmArray[$i] = $screenName;
			    }
		    } elseif ($dataType == 'ids') {
			    if (!in_array($userID, $dmArray)) {
			    	$dmArray[$i] = $userID;
			    }
		    }
	    }
	    $i++;
		
	}
	return $dmArray;
}


function findValidMentions($connection, $dataType) {
	$searchMentionsAPI = "https://api.twitter.com/1.1/statuses/mentions_timeline.json?count=200";

	$mentionsJSON = $connection->get("$searchMentionsAPI");
	
	//print_r($mentionsJSON);
	
	$goodMentionsArray = array();
	$i=0;
	foreach($mentionsJSON as $mentions) {
		
		$fullMention = $mentions->text;
		$userID = $mentions->user->id;
		$screenName = $mentions->user->screen_name;
		
		$atSymbol = strpos($fullMention, '@');
		$lastAtSymbol = strrpos($fullMention, '@');
		
		$genericFollow = strpos($fullMention, 'follow');
		
		if ($atSymbol == $lastAtSymbol) {
			
			if (!$genericFollow) {
				 if ($dataType == 'screen-names') {
				    echo "$screenName mentioned: $fullMention<br><br>";
				    if (!in_array($screenName, $goodMentionsArray)) {
					    $goodMentionsArray[$i] = $screenName;
				    }
				    
			    } elseif ($dataType == 'ids') {
				    if (!in_array($userID, $goodMentionsArray)) {
				    	$goodMentionsArray[$i] = $userID;
				    }
			    }
			    
			    $i++;
			}
			
		   
		}
		
	}
	
	return $goodMentionsArray;
	
}

function getWhitelist($dataFileDirectory, $dataType) {
	// grab whitelisted users
	$path = dirname ( __FILE__ );
	$whitelistFile = "$dataFileDirectory/whitelisted-users-$dataType.csv";
	//echo $whitelistFile;
	$whitelistedUsers = file_get_contents("$whitelistFile");
	
	if ($whitelistedUsers != '') {
		$whitelistedUsers = explode(', ', $whitelistedUsers);
		$whitelistedUserArray = array();
		
		foreach($whitelistedUsers as $whitelist) {
			if ($whitelist != '') {
				$whitelistedUserArray[$i] = $whitelist;
				$i++;
			}
		}
		return $whitelistedUserArray;
	} return false;
	
	
}



function view_whitelist($dataFileDirectory, $dataType) {
	
	$whitelistedUserArray = getWhitelist($dataFileDirectory, $dataType);
	
	$whitelistedUserArray = array_reverse($whitelistedUserArray);
	
	if ($whitelistedUserArray != '') {
		foreach($whitelistedUserArray as $whitelist) {
			if ($whitelist != '') {
				echo "<div class='user_info'>";
					echo "@$whitelist<br>";
				echo "</div>";
			} else {
				echo "<div class='user_info whitelist'>";
					echo "No users whitelisted!<br>";
				echo "</div>";
			}
		}
	}
	
	
	
}

function searchWhitelist($dataFileDirectory, $goodMentionsArray, $dataType) {
	$whitelistedUserArray = getWhitelist($dataFileDirectory, $dataType);

	if (($whitelistedUserArray) && (!$goodMentionsArray)) {
		return $whitelistedUserArray;
	} elseif ($whitelistedUserArray && $goodMentionsArray) {
		if (($dataType == 'screen-names') && ($goodMentionsArray)) {
			echo "goodMentionsArray: "; print_r($goodMentionsArray); echo '<br>';
	
			//echo "whitelistedUserArray: "; print_r($whitelistedUserArray); echo '<br>';
		}
		
		$usersToWhitelistArray = array_diff($goodMentionsArray, $whitelistedUserArray);
		
		foreach($usersToWhitelistArray as $whitelist) {
			echo $whitelist.'<br>';
		}
		return $usersToWhitelistArray;
		 
	} else {
		return false;
	}
	
}


function whitelist($dataFileDirectory, $whitelistedUser, $dataType) {
	$path = dirname ( __FILE__ );
	$file = "$dataFileDirectory/whitelisted-users-$dataType.csv";
	$directory = "$path/*";
	chmod ( $file , 0600 );
	
	$whitelistUserIDs = searchWhitelist($dataFileDirectory, false, $dataType);
	
	//print_r($whitelistUserIDs);
	
	if ($whitelistUserIDs) {
		if (!in_array($whitelistedUser, $whitelistUserIDs)) {
			$content = "$whitelistedUser, ";
			// Write the contents to the file, 
			// using the FILE_APPEND flag to append the content to the end of the file
			// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
			file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
			
			if ($dataType == 'screen-names') {
				echo "<h3>@$whitelistedUser whitelisted!</h3>";
			}
		} else {
			
			if ($dataType == 'screen-names') {
				echo '<h3>Already whitelisted!</h3>';
			}
			
		}
		
	} else {
		$content = "$whitelistedUser, ";
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
		
		if ($dataType == 'screen-names') {
			echo "<h3>@$whitelistedUser whitelisted!</h3>";
		}
	}
	
	

}

function whitelistActiveMentionUsers($connection, $dataFileDirectory) {
	
	// WHITELIST IDS
	$goodMentionsIdArray = findValidMentions($connection, 'ids');
	$whitelistUserIDs = searchWhitelist($dataFileDirectory, $goodMentionsIdArray, 'ids');
	
	if ($whitelistUserIDs != '') {
		foreach ($whitelistUserIDs as $userID) {
			whitelist($dataFileDirectory, $userID, 'ids');
		}
	}
	
	
	//WHITELIST SCREEN_NAMES
	$goodMentionsSNArray = findValidMentions($connection, 'screen-names');
	$whitelistUserScreenNames = searchWhitelist($dataFileDirectory, $goodMentionsSNArray, 'screen-names');
	if ($whitelistUserIDs != '') {
		foreach ($whitelistUserScreenNames as $screenName) {
			whitelist($dataFileDirectory, $screenName, 'screen-names');
			//echo "$screenName whitelisted.<br>";
		}
	}
		
}

function whitelistActiveDMUsers($connection, $dataFileDirectory) {
	
	// WHITELIST IDS
	$goodMentionsIdArray = findValidDirectMessages($connection, 'ids');
	$whitelistUserIDs = searchWhitelist($dataFileDirectory, $goodMentionsIdArray, 'ids');
	
	if ($whitelistUserIDs != '') {
		foreach ($whitelistUserIDs as $userID) {
			echo "$userID to be whitelisted.<br>";
			whitelist($dataFileDirectory, $userID, 'ids');
		}
	}
	//WHITELIST SCREEN_NAMES
	$goodMentionsSNArray = findValidDirectMessages($connection, 'screen-names');
	$whitelistUserScreenNames = searchWhitelist($dataFileDirectory, $goodMentionsSNArray, 'screen-names');
	if ($whitelistUserIDs != '') {
		foreach ($whitelistUserScreenNames as $screenName) {
			whitelist($dataFileDirectory, $screenName, 'screen-names');
		}
	}
		
}

function lookupUserRelationshipArray($connection, $chunkString) {
	$relationshipJSON = $connection->get("https://api.twitter.com/1.1/friendships/lookup.json?user_id=$chunkString");
	
	//print_r($relationshipJSON);
	
	$usersNeedToFollowArray = array();
	$i=0;
	foreach ($relationshipJSON as $relationship) {
		$following = $relationship->connections[0];
	
		if (($following == 'followed_by') || ($following == 'none')) {
			$userID = $relationship->id;
			//print_r( $relationship); echo"<br>";
			$usersNeedToFollowArray[$i] = $userID;
			$i++;
		}
		
	}
	return $usersNeedToFollowArray;
}

function followWhitelist($connection, $dataFileDirectory) {
	$whitelistedUserArray = getWhitelist($dataFileDirectory, 'ids');
	$count = count($whitelistedUserArray);
	echo $count."<br>";
	
	$chunk = array_chunk($whitelistedUserArray, 100);
	
	//print_r($chunk);
	
	$chunkStringArray = array();
	
	$i=0;
	foreach ($chunk as $singleChunk) {
		$k = 0;
		${"chunkString" . $i} = '';
		foreach($singleChunk as $userID) {
			if ($k == 0) {
				${"chunkString" . $i} .= "$userID";
			} else {
				${"chunkString" . $i} .= ",$userID";
			}
			
			$k++;
		}
		$i++;
	}
	
	for($i=0; $i<=$count/100; $i++) {
		//echo ${"chunkString". $i}."<br>";
		$chunkStringArray[$i] = ${"chunkString". $i};
	}
	
	foreach ($chunkStringArray as $chunkString) {
		//echo "chunkString: $chunkString <br>";
		$usersNeedToFollowArray = lookupUserRelationshipArray($connection, $chunkString);
		//echo '<br><hr>Users need to follow:<br>';
		//print_r($usersNeedToFollowArray);
		foreach ($usersNeedToFollowArray as $userID) {
			echo "following: $userID<br>";
			$connection->post("https://api.twitter.com/1.1/friendships/create.json?user_id=$userID&follow=true");
			
		}
	}
	
}

