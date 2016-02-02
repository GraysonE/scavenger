<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\SocialMediaAccount;
use Auth;
use Scavenger\Twitter\TwitterOAuth;
use Scavenger\ModelAccount;
use Scavenger\Friend;
use Scavenger\Follower;
use Scavenger\TargetUser;
use Scavenger\TempTargetUser;
use Carbon\Carbon;
use Scavenger\Helpers\Helper;

/**
 * Class AutomationController
 * @package Scavenger\Http\Controllers
 */
class AutomationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

		$message = "Beginning automation!";
		Helper::email_user($message, 1);

        $count = 5000;

        $socialMediaAccounts = SocialMediaAccount::get()->all();

        foreach($socialMediaAccounts as $socialMediaAccount) {

			if ($socialMediaAccount->id == 4) {
				continue;
			}

            $api_requests = 15;

            $connection = new TwitterOAuth(
                $socialMediaAccount->consumer_key,
                $socialMediaAccount->consumer_secret,
                $socialMediaAccount->access_token,
                $socialMediaAccount->access_token_secret);


			if (!is_null($connection->http_code)) {
			
				($connection->http_code) ? '' : "<h1>HTTP code: $connection->http_code</h1>";
				echo "<h3>Error establishing initial connection. If no HTTP code is provided it is NULL.</h3>";
				
				$errorMessage = "<h3>Error establishing initial connection. If no HTTP code is provided it is NULL.</h3>";
				$errorMessage .= "Error code: $connection->http_code";
				
				Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);
				
				if ($connection->http_code == '401') {
					return view('errors.401')->with(compact('http_code'));
				}
				
				continue;
			}
			
            $myScreenName = $socialMediaAccount->screen_name;

            echo "<h1>$myScreenName</h1>";




			






            /** 
             *
             *
             * Who am I following? / FRIENDS
             *
             *
             */
             
            echo '<h2>ONLINE FRIENDS</h2><br>';
            echo "<h4>API requests left: $api_requests</h4>";

            $cursor = "-1";
            $i=0;

			// GET CURRENT DB FRIENDS
			$oldFriends = Friend::where('social_media_account_id', $socialMediaAccount->id)->select('account_id')->get()->all();
			
			
			
			// PROCESS THEM INTO AN ARRAY
			$oldFriends_ids = array();
			foreach($oldFriends as $account_id) {
				$oldFriends_ids[$i] = $account_id['account_id'];
				$i++;
			}
	
	
			
			// ARRAY FOR ONLINE FRIENDS
			$onlineFriends_ids = array();
			
			
			// PROCESS ONLINE FRIENDS
            do {

                $searchFriendsAPI = "https://api.twitter.com/1.1/friends/ids.json?cursor=$cursor&screen_name=$myScreenName&skip_status=true&include_user_entities=false&count=$count";

                $friends = $connection->get("$searchFriendsAPI");

                if (isset($friends->errors)) {

                    $errorObject = $friends->errors;
                    $error = $errorObject[0]->code;
                    $errorMessage = "Friend lookup needs to refresh. " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";
                    Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                    break;

                } else {

//                      print_r(json_encode($friends));echo '<br>';

                    $cursor = $friends->next_cursor;

					// ADD ONLINE FRIENDS INTO ARRAY
                    foreach ($friends->ids as $friend) {
                        
                        $onlineFriends_ids[$i] = $friend;

                        $i++;
                    }

                }

                echo "<br><br><strong>Next Cursor: </strong>$cursor";

                $api_requests--;

            } while ($cursor > 0);

			
			if (isset($oldFriends_ids)) {
				
				
				// FIND FRIENDS THAT ARE NOT IN THE DATABASE BUT ARE ONLINE AND ADD THEM
				$friendsToAdd_ids = array_diff($onlineFriends_ids, $oldFriends_ids);
				
				foreach($friendsToAdd_ids as $friend_id) {
					
					$newFriend = Friend::create([
                        'account_id' => $friend_id,
                        'social_media_account_id' => $socialMediaAccount->id
                    ]);
                    
				}
				
				// FIND DUPLICATES IN DATABASE AND DELETE THEM
				$dups = array();
				foreach(array_count_values($oldFriends_ids) as $val => $c) {
					if($c > 1) {
						$dups[] = $val;
					}
				}
				
				foreach($dups as $duplicateAccount_id) {
					
					$friendToDelete = Friend::where('social_media_account_id', $socialMediaAccount->id)
						->where('account_id', $duplicateAccount_id)
						->where('to_unfollow', 0)
						->get();
					
					foreach($friendToDelete as $duplicateFriend) {
						$duplicateFriend->delete();
					}
									
					
				}
				    
				
				// FIND FRIENDS THAT ARE IN THE DATABASE BUT NOT ONLINE AND DELETE THEM
				$friendsToDelete_ids = array_diff($oldFriends_ids, $onlineFriends_ids);
				
				foreach($friendsToDelete_ids as $friend_id) {
					
					$friendToDelete = Friend::where('social_media_account_id', $socialMediaAccount->id)
						->where('account_id', $friend_id)
						->get()
						->first();
					
					$friendToDelete->to_unfollow = 1;
			        $friendToDelete->save();
                    
				}

				
			} else {
// 				dd('hello');
				foreach($onlineFriends_ids as $friend_id) {
					
					$newFriend = Friend::create([
                        'account_id' => $friend_id,
                        'social_media_account_id' => $socialMediaAccount->id
                    ]);
                    
				}
				
			}





            /** 1
             *
             *
             * Who is following me? / FOLLOWERS
             *
             *
             */

            echo '<h2>ONLINE FOLLOWERS</h2><br>';
            echo "<h4>API requests left: $api_requests</h4>";

            $cursor = "-1";
            $i=0;

			// GET CURRENT DB Followers
			$oldFollowers = Follower::where('social_media_account_id', $socialMediaAccount->id)->select('account_id')->get()->all();
			
			$oldFollowers_ids = array();
			
			foreach($oldFollowers as $account_id) {
				$oldFollowers_ids[$i] = $account_id['account_id'];
				$i++;
			}
	
			
			// ARRAY FOR ONLINE Followers
			$onlineFollowers_ids = array();
			
            do {

                $searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$myScreenName&skip_status=true&include_user_entities=false&count=$count";

                $followers = $connection->get("$searchFollowersAPI");

                if (isset($followers->errors)) {

                    $errorObject = $followers->errors;
                    $error = $errorObject[0]->code;
                    $errorMessage = "Follower lookup needs to refresh. " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";
                    Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                    break;

                } else {

//                      print_r(json_encode($followers));echo '<br>';

                    $cursor = $followers->next_cursor;

                    foreach ($followers->ids as $follower) {
                        
                        $onlineFollowers_ids[$i] = $follower;

                        $i++;
                    }

                }

                echo "<br><br><strong>Next Cursor: </strong>$cursor";

                $api_requests--;

            } while ($cursor > 0);


			if (isset($oldFollowers_ids)) {
				
				
				// FIND followers THAT ARE NOT IN THE DATABASE BUT ARE ONLINE AND ADD THEM
				$followersToAdd_ids = array_diff($onlineFollowers_ids, $oldFollowers_ids);
				
				foreach($followersToAdd_ids as $follower_id) {
					
					$newFollower = Follower::create([
                        'account_id' => $follower_id,
                        'social_media_account_id' => $socialMediaAccount->id
                    ]);
                    
				}
				
				
				// FIND DUPLICATES IN DATABASE AND DELETE THEM
				$dups = array();
				foreach(array_count_values($oldFollowers_ids) as $val => $c) {
					if($c > 1) {
						$dups[] = $val;
					}
				}
				
				foreach($dups as $duplicateAccount_id) {
					
					$followerToDelete = Follower::where('social_media_account_id', $socialMediaAccount->id)
						->where('account_id', $duplicateAccount_id)
						->get();
					
					foreach($followerToDelete as $duplicateFollower) {
						$duplicateFollower->delete();
					}
							
				}
				
				
				// FIND followers THAT ARE IN THE DATABASE BUT NOT ONLINE AND DELETE THEM
				$followersToDelete_ids = array_diff($oldFollowers_ids, $onlineFollowers_ids);
				
				foreach($followersToDelete_ids as $follower_id) {
					
					$followerToDelete = Follower::where('social_media_account_id', $socialMediaAccount->id)
						->where('account_id', $follower_id)
						->get()
						->first();
					
					$followerToDelete->delete();
                    
				}


			} else {
				
				foreach($onlineFollowers_ids as $follower_id) {
					
					$newFollower = Follower::create([
                        'account_id' => $follower_id,
                        'social_media_account_id' => $socialMediaAccount->id
                    ]);
                    
				}
				
			}







            /** 
             *
             *
             * COMPARE DATABASE TO ONLINE
             *
             *
             */


            // Find number of followers for user
            echo "<h4>API requests left: $api_requests</h4>";
            $numberOfFollowersURL = "https://api.twitter.com/1.1/users/lookup.json?screen_name=$myScreenName";
            $numberOfFollowersURL_json = $connection->get("$numberOfFollowersURL");
            $api_requests--;

            if (isset($numberOfFollowersURL_json->errors)) {
                $errorObject = $numberOfFollowersURL_json->errors;
                $ErrorCode = $errorObject[0]->code;
                $errorMessage = "Could not look up your user information to compare online to database records. " . $errorObject[0]->message;

                echo "<div class='errorMessage'>$errorMessage</div>";

                Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                continue;
            }

            $dbFriends = Friend::where('social_media_account_id', $socialMediaAccount->id)->get();
            $dbFollowers = Follower::where('social_media_account_id', $socialMediaAccount->id)->get();

            echo "<br>$myScreenName online followers count: " . $numberOfFollowersURL_json[0]->followers_count;
            echo "<br>$myScreenName db followers count: " . count($dbFollowers);

            echo "<br>$myScreenName online friends count: " . $numberOfFollowersURL_json[0]->friends_count;
            echo "<br>$myScreenName db friends count: " . count($dbFriends);








            /** 
             *
             *
             * AUTO-WHITELIST
             *
             *
             */


            // FIND VALID DMs
            echo "<h2>VALID DMs</h2>";
            echo "<h4>API requests left: $api_requests</h4>";
            $directMessagesAPI = "https://api.twitter.com/1.1/direct_messages/sent.json?count=200";
            $directMessagesAPI = $connection->get("$directMessagesAPI");

            if (isset($directMessagesAPI->errors)) {

                $errorObject = $directMessagesAPI->errors;
                $error = $errorObject[0]->code;
                $errorMessage = "Direct message request needs to refresh. " . $errorObject[0]->message;

                echo "<div class='errorMessage'>$errorMessage</div>";

                Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                continue;

            }

            $api_requests--;
            $dmArray_ScreenNames = array();
            $dmArray_IDs = array();
            //print_r($directMessagesAPI);

            $i=0;
            foreach($directMessagesAPI as $dm) {

                $userID = $dm->recipient->id;
                $screenName = $dm->recipient->screen_name;
                $text = $dm->text;
                $genericString = "Hey, thanks so much for following";
                $genericString2 = "Hey thanks so much for following";
                $genericString3 = "Hey thank you so much for following";
                $containsGeneric = preg_match("/^$genericString/", $text);
                $containsGeneric2 = preg_match("/^$genericString2/", $text);
                $containsGeneric3 = preg_match("/^$genericString3/", $text);
                //echo $containsGeneric;


                if ((!$containsGeneric) && (!$containsGeneric2) && (!$containsGeneric3)) {

                    if (!in_array($screenName, $dmArray_ScreenNames)) {
                        echo "$screenName was messaged: $text<br>";
                        $dmArray_ScreenNames[$i] = $screenName;
                    }
                    if (!in_array($userID, $dmArray_IDs)) {
                        $dmArray_IDs[$i] = $userID;

                        $i++;
                    }
                }


            }

            // CONTINUE WITH TWO ARRAYS FOR SCREEN_NAME AND IDS
/*
            echo "<h2>GOOD DM ARRAYS:</h2>";
            print_r($dmArray_ScreenNames);
            echo "<br>";
            print_r($dmArray_IDs);
*/


            // FIND VALID MENTIONS
            echo "<h2>VALID MENTIONS</h2>";
            echo "<h4>API requests left: $api_requests</h4>";
            $searchMentionsAPI = "https://api.twitter.com/1.1/statuses/mentions_timeline.json?count=200";

            $mentionsJSON = $connection->get("$searchMentionsAPI");
            $api_requests--;
            //print_r($mentionsJSON);

            $goodMentionsArray_IDs = array();
            $goodMentionsArray_ScreenNames = array();
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

                        echo "<br>$screenName mentioned: $fullMention";
                        if (!in_array($screenName, $goodMentionsArray_ScreenNames)) {
                            $goodMentionsArray_ScreenNames[$i] = $screenName;
                        }
                        if (!in_array($userID, $goodMentionsArray_IDs)) {
                            $goodMentionsArray_IDs[$i] = $userID;

                            $i++;
                        }


                    }


                }

            }

/*
            echo "<h2>GOOD MENTIONS ARRAYS:</h2>";
            print_r($goodMentionsArray_ScreenNames);
            echo "<br>";
            print_r($goodMentionsArray_IDs);
*/

            // CONTINUE WITH GOOD MENTIONS ARRAYS WITH SCREEN NAMES AND IDS


            // GET WHITELISTED USERS
            $whitelist = Friend::where('social_media_account_id', $socialMediaAccount->id)
                ->where('whitelisted', true)
                ->get();
            echo "<h2>DB WHITELISTERS</h2>";

            // WHITELIST ACTIVE MENTIONS

            $k=0;

            if (count($goodMentionsArray_IDs)) {
                echo "<br>1";
                
                foreach ($goodMentionsArray_IDs as $id) {
                    echo " - 2";

                    $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                        ->where('account_id', $id)
                        ->get()
                        ->first();

                    if ((!$whitelist->isEmpty()) && (!is_null($oldFriend))) {
                        echo ", 3";

                            if ($id != $oldFriend->account_id) {
                                echo ", 4";
                                
                                $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                    ->where('account_id', $id)
                                    ->get()
                                    ->first();

                                if (is_null($oldTarget)) {

                                    echo "<br>Mention id: $id";
                                    $newTarget = TargetUser::create([
                                        'account_id' => $id,
                                        'screen_name' => $goodMentionsArray_ScreenNames[$k],
                                        'whitelist' => 1,
                                        'social_media_account_id' => $socialMediaAccount->id
                                    ]);

//                                     echo "<br>Mention Whitelisted: "; print_r($newTarget);

                                }

                            } else {
                                echo ", 5";
                                
                                $friend_to_be_whitelisted = Friend::findOrFail($oldFriend->id);
                                $friend_to_be_whitelisted->whitelisted = 1;
                                $saved = $friend_to_be_whitelisted->save();

                                if ($saved) {
                                    echo "<br><strong>Mention Whitelisted: </strong>$goodMentionsArray_ScreenNames[$k]";
                                }
                            }

                    } else {
                        echo ", 6";
                        $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                            ->where('account_id', $id)
                            ->get()
                            ->first();

                        if (is_null($oldFriend)) {

                            $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                ->where('account_id', $id)
                                ->get()
                                ->first();

                            if (is_null($oldTarget)) {

                                echo "<br>Mention id: $id";
                                $newTarget = TargetUser::create([
                                    'account_id' => $id,
                                    'screen_name' => $goodMentionsArray_ScreenNames[$k],
                                    'whitelist' => 1,
                                    'social_media_account_id' => $socialMediaAccount->id
                                ]);

//                                 echo "<br>Mention Whitelisted: "; print_r($newTarget);

                            }



                        } else {
                            echo ", 7";
                            $friend_to_be_whitelisted = Friend::findOrFail($oldFriend->id);
                            $friend_to_be_whitelisted->whitelisted = 1;
                            $saved = $friend_to_be_whitelisted->save();

                            if ($saved) {
                                echo "<br><strong>Mention Whitelisted: </strong>$goodMentionsArray_ScreenNames[$k]";
                            }
                        }


                    }

                    $k++;
                }
            }


            // WHITELIST DMs

            $k=0;

            if (count($dmArray_IDs)) {
                echo "<br>1";

                foreach ($dmArray_IDs as $id) {
                    echo " - 2";

                    $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                        ->where('account_id', $id)
                        ->get()
                        ->first();

                    if ((!$whitelist->isEmpty()) && (!is_null($oldFriend))) {
						echo ", 3";

                        if ($id != $oldFriend->account_id) {
                            echo ", 4";
                            
                            $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                ->where('account_id', $id)
                                ->get()
                                ->first();

                            if (is_null($oldTarget)) {

                                echo "<br>DM id: $id";
                                $newTarget = TargetUser::create([
                                    'account_id' => $id,
                                    'screen_name' => $dmArray_ScreenNames[$k],
                                    'whitelist' => 1,
                                    'social_media_account_id' => $socialMediaAccount->id
                                ]);
								
								if ($newTarget) {
									echo "<br>DM Whitelisted and Added to Target Table: $newTarget->screen_name";
								}
								
                            }

                        } else {
                            echo ", 5";
                            
                            $friend_to_be_whitelisted = Friend::findOrFail($oldFriend->id);
                            $friend_to_be_whitelisted->whitelisted = 1;
                            $saved = $friend_to_be_whitelisted->save();

                            if ($saved) {
                                echo "<br><strong>DM Whitelisted: </strong>$dmArray_ScreenNames[$k]";
                            }
                        }

                    } else {
                        echo ", 6";
                        
                        $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                            ->where('account_id', $id)
                            ->get()
                            ->first();

                        if (is_null($oldFriend)) {

                            $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                ->where('account_id', $id)
                                ->get()
                                ->first();

                            if (is_null($oldTarget)) {

                                echo "<br>DM id: $id";
                                $newTarget = TargetUser::create([
                                    'account_id' => $id,
                                    'screen_name' => $dmArray_ScreenNames[$k],
                                    'whitelist' => 1,
                                    'social_media_account_id' => $socialMediaAccount->id
                                ]);

                                if ($newTarget) {
									echo "<br>DM Whitelisted and Added to Target Table: $newTarget->screen_name";
								}

                            }



                        } else {
                            echo ", 7";
                            $friend_to_be_whitelisted = Friend::findOrFail($oldFriend->id);
                            $friend_to_be_whitelisted->whitelisted = 1;
                            $saved = $friend_to_be_whitelisted->save();

                            if ($saved) {
                                echo "<br><strong>DM Whitelisted: </strong>$dmArray_ScreenNames[$k]";
                            }
                        }


                    }

                    $k++;
                }
            }





			
			
			
			
			/** 
             *
             *
             * GET MODEL ACCOUNTS'S FOLLOWERS, FILTER IF ALREADY FOLLOWING OR FRIEND, ADD TO TEMP TARGET USERS TABLE
             *
             *
             */

            echo "<h4>API requests left before model user: $api_requests</h4>";


            // GET MODEL ACCOUNT
            // TODO: ADD SORT_ORDER TO QUERY
            $modelAccount = ModelAccount::where('social_media_account_id', $socialMediaAccount->id)
                ->where('api_cursor', '!=', 0)
                ->where('sort_order', 1)
                ->get()
                ->first();


            


            if (!is_null($modelAccount)) {

				echo "<h2>@". $modelAccount->screen_name . "'s ONLINE FOLLOWERS</h2><br>";

				$i=1;

                do {

                    $searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$modelAccount->api_cursor&screen_name=$modelAccount->screen_name&count=$count";

                    $followers = $connection->get("$searchFollowersAPI");


                    if (isset($followers->errors)) {

                        $errorObject = $followers->errors;
                        $error = $errorObject[0]->code;
                        $errorMessage = "Model account follower lookup to needs to refresh. " . $errorObject[0]->message;

                        echo "<div class='errorMessage'>$errorMessage</div>";

                        Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                        break;

                    } else {


                        $onlineFollowers = $followers->ids;

                        foreach ($onlineFollowers as $follower) {

//                             echo "<br><strong>Online: </strong>$follower";

                            $oldFollower = Follower::where('social_media_account_id', $socialMediaAccount->id)
                                ->where('account_id', $follower)
                                ->get()
                                ->first();

                            if (is_null($oldFollower)) {

                                $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                                    ->where('account_id', $follower)
                                    ->get()
                                    ->first();

                                if (is_null($oldFriend)) {

                                    $oldTemp = TempTargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                        ->where('account_id', $follower)
                                        ->get()
                                        ->first();

                                    if (is_null($oldTemp)) {

                                        $newTemp = TempTargetUser::create([
                                            'account_id' => $follower,
                                            'social_media_account_id' => $socialMediaAccount->id
                                        ]);

										$i++;

//                                        echo "<br>"; print_r($newTemp);

                                    }



                                }


                            }



                        }


                    }
                    $api_requests--;

                    $modelAccount->api_cursor = $followers->next_cursor;
                    
                    if ($modelAccount->api_cursor == 0) {
	                    $errorMessage = "Model Account API cursor equals 0.<br>";
	                    $errorMessage = "Out of a list of 5000, $i were added to temp_target_users table.<br>";
	                    Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);
                    }
                    
                    $modelAccount->save();


                    echo "<br><br><strong>Next Cursor: </strong>$followers->next_cursor";
                } while ($followers->next_cursor > 0);
            } else {
	            echo "<h1>Add a model account that hasn't been finished!</h1>";
            }
			
			
			
			

            



            /**
             *
             *
             * FILTER THE TEMP ACCOUNTS BASED ON USER LOOKUP
             *
             */


            echo "<h2>TEMP TARGET USERS:</h2>";

            $tempAccounts = TempTargetUser::where('social_media_account_id', $socialMediaAccount->id)
                ->get();

            $api_limit = 180;

            // BEGINNING CONTENT FOR CSV FILE (CAN'T BE INSIDE LOOP)
    //            $content = "screen_name,followers_count,friends_count,favorites_count,statuses_count,last_status,created_at,\n";

            $iteration = 1;

            foreach($tempAccounts as $tempAccount) {

                $temp_account_id = (int)$tempAccount->account_id;

                if ($tempAccount->account_id == '1895920429') {
                    continue;
                }

                if ($api_limit == 1) {
                    break;
                }

                echo "<br>Temp Account: $temp_account_id";

                //Investigate User Before Following
                $userInvestigationURL = "https://api.twitter.com/1.1/users/lookup.json?user_id=$temp_account_id";
                $userInvestigation_json = $connection->get("$userInvestigationURL");


                if (isset($userInvestigation_json->errors)) {

                    $errorObject = $userInvestigation_json->errors;
                    $ErrorCode = $errorObject[0]->code;
                    $errorMessage = "Could not research potential user for filtration. " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";

                    Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                    break;

                } else {

                    $userInvestigation = $userInvestigation_json[0];


                    $time = (int)strtotime(Carbon::now());
                    $one_month_unix_time = 86400*30;
                    $one_day_unix_time = (int)86400;
                    $created_at = (int)strtotime($userInvestigation->created_at);

                    $requestScreenName = "$userInvestigation->screen_name";
                    $followersCount = (int)$userInvestigation->followers_count;
                    $friendsCount = (int)$userInvestigation->friends_count;
                    $favoritesCount = (int)$userInvestigation->favourites_count;
                    $list_count = (int)$userInvestigation->listed_count; // doesnt matter
                    $statuses_count = (int)$userInvestigation->statuses_count;


                    /**
                     *
                     *
                     * THE FILTER
                     *
                     */


                    if (isset($userInvestigation->status->created_at)) {

                        $last_status = (int)strtotime($userInvestigation->status->created_at); // MUST BE UNDER 30 DAYS OLD

                        if($created_at > ($time-$one_month_unix_time)) { // HAS TO HAVE BEEN CREATED AT LEAST A MONTH AGO

                            if ($last_status < ($time - ($one_day_unix_time*2))) { // LAST STATUS HAS TO HAVE BEEN IN THE PAST WEEK

                                if ($statuses_count > 50) {

                                        if ($favoritesCount > 50) {

                                            if ($friendsCount >= ($followersCount - 50)) { // MORE PEOPLE FOLLOWING THAN FOLLOWING THEM


                                            $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                                ->where('account_id', $temp_account_id)
                                                ->where('screen_name', $requestScreenName)
                                                ->get()
                                                ->first();

                                            if (is_null($oldTarget)) {

                                                echo "<br>Target id: $temp_account_id";
                                                $newTarget = TargetUser::create([
                                                    'account_id' => $temp_account_id,
                                                    'screen_name' => $requestScreenName,
                                                    'whitelist' => 0,
                                                    'social_media_account_id' => $socialMediaAccount->id
                                                ]);

                                                echo " - $requestScreenName - <strong>ADDED!!!</strong>";

                                            } else {
                                                echo " - <strong>Already in target_users table.</strong>";
                                            }
                                        } else {
                                            echo " - Doesn't have more friends than followers.";
                                        }
                                    } else {
                                        echo " - Only $favoritesCount favorites.";
                                    }
                                } else {
                                    echo " - Only $statuses_count statuses.";
                                }
                            } else {
                                echo " - Hasn't made a status in the last month";
                            }
                        } else {
                            echo " - Account created less than a month ago";
                        }
                    } else {
                        echo " - Has never made a status.";
                    }

                    // Delete temporary account

                    $tempAccountToDelete = TempTargetUser::where('account_id', $temp_account_id)
                        ->where('social_media_account_id', $socialMediaAccount->id)
                        ->get()
                        ->first()
                        ->delete();

                    if ($tempAccountToDelete) {
                        echo " - Deleted from Temp_Target_Users.";
                    }

                    $api_limit--;

                }

                $iteration++;
            }





            echo '<hr>';
            $now = Carbon::now('America/Denver');
            echo "<br>$now";
        } // main foreach that goes through $socialMediaAccounts








    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
