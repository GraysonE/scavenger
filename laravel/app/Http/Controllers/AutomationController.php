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
use Carbon\Carbon;
use Scavenger\Helpers\Helper;

error_reporting(E_ALL);

ini_set('display_errors', 1);

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

        $count = 5000;

        $socialMediaAccounts = SocialMediaAccount::where('account_type', 'twitter')->get()->all();
		
        foreach($socialMediaAccounts as $socialMediaAccount) {

/*
$targetUsers = TargetUser::where('social_media_account_id', $socialMediaAccount->id)->select('account_id')->get()->all();
			dd($targetUsers);
*/

			$errorCount = 0;
			$errorMessage = "";

//   			$socialMediaAccount = SocialMediaAccount::find(5);
			
/*
			if ($socialMediaAccount->id == 3) {
				 continue;
			}
*/

            $api_requests = 15;

            $connection = new TwitterOAuth(
                $socialMediaAccount->consumer_key,
                $socialMediaAccount->consumer_secret,
                $socialMediaAccount->access_token,
                $socialMediaAccount->access_token_secret);


			if (!is_null($connection->http_code)) {
			
				$errorCount++;
			
				($connection->http_code) ? '' : "<h1>HTTP code: $connection->http_code</h1>";
				echo "<h3>Error establishing initial connection. If no HTTP code is provided it is NULL.</h3>";
				
				$errorMessage .= "<h2>Error $errorCount</h2>";
				$errorMessage .= "<h3>Error establishing initial connection. If no HTTP code is provided it is NULL.</h3>";
				$errorMessage .= "Error code: $connection->http_code";
				
				
				
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
				$oldFriends_ids[] = $account_id['account_id'];
				
			}
	
	
			
			// ARRAY FOR ONLINE FRIENDS
			$onlineFriends_ids = array();
			
			
			// PROCESS ONLINE FRIENDS
            do {

                $searchFriendsAPI = "https://api.twitter.com/1.1/friends/ids.json?cursor=$cursor&screen_name=$myScreenName&skip_status=true&include_user_entities=false&count=$count";

                $friends = $connection->get("$searchFriendsAPI");

                if (isset($friends->errors)) {
					$errorCount++;
					
                    $errorObject = $friends->errors;
                    $error = $errorObject[0]->code;
                    
                    $errorMessage .= "<h2>Error $errorCount</h2>";
                    $errorMessage .= "Friend lookup needs to refresh. " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";
                    

                    break;

                } else {

//                      print_r(json_encode($friends));echo '<br>';

                    $cursor = $friends->next_cursor;

					// ADD ONLINE FRIENDS INTO ARRAY
                    foreach ($friends->ids as $friend) {
                        
                        $onlineFriends_ids[] = $friend;
                    }

                }

                echo "<br><br><strong>Next Cursor: </strong>$cursor";

                $api_requests--;

            } while ($cursor > 0);

			
			if (!isset($friends->errors)) {
				
				if (!empty($oldFriends_ids)) {
				
				
					// FIND FRIENDS THAT ARE NOT IN THE DATABASE BUT ARE ONLINE AND ADD THEM
					$friendsToAdd_ids = array_diff($onlineFriends_ids, $oldFriends_ids);
					
					foreach($friendsToAdd_ids as $friend_id) {
						
						$newFriend = Friend::create([
		                    'account_id' => $friend_id,
		                    'social_media_account_id' => $socialMediaAccount->id
		                ]);
		                
					}
					    
					
					// FIND FRIENDS THAT ARE IN THE DATABASE BUT NOT ONLINE AND DELETE THEM
					$friendsToDelete_ids = array_diff($oldFriends_ids, $onlineFriends_ids);
					
					foreach($friendsToDelete_ids as $friend_id) {
						
						$friendToDelete = Friend::where('social_media_account_id', $socialMediaAccount->id)
							->where('account_id', $friend_id)
							->get()
							->first();
						
						$friendToDelete->unfollowed = 1;
						$friendToDelete->unfollowed_timestamp = Carbon::now('America/Denver');
				        $friendToDelete->save();
		                
					}
		
					
				} else {
					
					echo "<h3>Adding all friends</h3>";
					
					foreach($onlineFriends_ids as $friend_id) {
						// IF THIS IS THE FIRST TIME YOU SIGN UP YOUR ACCOUNT, ADD EVERYONE
						$newFriend = Friend::create([
		                    'account_id' => $friend_id,
		                    'social_media_account_id' => $socialMediaAccount->id
		                ]);
		                
					}
					
				}
				
			} else {
				echo "<h3>Friend api error</h3>";
			}
			
			





            /** 
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
				$oldFollowers_ids[] = $account_id['account_id'];
				
			}
			
			$i=0;
			
			// ARRAY FOR ONLINE Followers
			$onlineFollowers_ids = array();
			
            do {

                $searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$myScreenName&skip_status=true&include_user_entities=false&count=$count";

                $followers = $connection->get("$searchFollowersAPI");

                if (isset($followers->errors)) {
					$errorCount++;
                    $errorObject = $followers->errors;
                    $error = $errorObject[0]->code;
                    $errorMessage .= "<h2>Error $errorCount</h2>";
                    $errorMessage .= "Follower lookup needs to refresh. " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";
                    
                    break;

                } else {

//                      print_r(json_encode($followers));echo '<br>';

                    $cursor = $followers->next_cursor;

                    foreach ($followers->ids as $follower) {
                        
                        $onlineFollowers_ids[] = $follower;
                    }

                }

                echo "<br><br><strong>Next Cursor: </strong>$cursor";

                $api_requests--;

            } while ($cursor > 0);
            
            

			if (!isset($followers->errors)) {

				if (!empty($oldFollowers_ids)) {
					
					
					// FIND followers THAT ARE NOT IN THE DATABASE BUT ARE ONLINE AND ADD THEM
					$followersToAdd_ids = array_diff($onlineFollowers_ids, $oldFollowers_ids);
					
					foreach($followersToAdd_ids as $follower_id) {
						
						$newFollower = Follower::create([
		                    'account_id' => $follower_id,
		                    'social_media_account_id' => $socialMediaAccount->id
		                ]);
		                
					}
					
					// FIND followers THAT ARE IN THE DATABASE BUT NOT ONLINE AND DELETE THEM
					$followersToDelete_ids = array_diff($oldFollowers_ids, $onlineFollowers_ids);
					
					foreach($followersToDelete_ids as $follower_id) {
						
						$followerToDelete = Follower::where('social_media_account_id', $socialMediaAccount->id)
							->where('account_id', $follower_id)
							->get()->first();
						
						if (isset($followerToDelete)) {
							Follower::find($followerToDelete['id'])->delete();
						}
						
						                 
					}
		
		
				} else {
					// IF THIS IS THE FIRST TIME YOU SIGN UP YOUR ACCOUNT, ADD EVERYONE
					foreach($onlineFollowers_ids as $follower_id) {
						
						$newFollower = Follower::create([
		                    'account_id' => $follower_id,
		                    'social_media_account_id' => $socialMediaAccount->id
		                ]);
		                
					}
					
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
	            
	            $errorCount++;
                $errorObject = $numberOfFollowersURL_json->errors;
                $ErrorCode = $errorObject[0]->code;
                
                $errorMessage .= "<h2>Error $errorCount</h2>";
                $errorMessage .= "Could not look up your user information to compare online to database records. " . $errorObject[0]->message;

                echo "<div class='errorMessage'>$errorMessage</div>";

                continue;
            }

            $dbFriends = Friend::where('social_media_account_id', $socialMediaAccount->id)
            	->where('unfollowed', 0)
            	->get();
            	
            $dbFollowers = Follower::where('social_media_account_id', $socialMediaAccount->id)->get();

            echo "<br>$myScreenName online followers count: " . $numberOfFollowersURL_json[0]->followers_count;
            echo "<br>$myScreenName db followers count: " . count($dbFollowers);

            echo "<br>$myScreenName online friends count: " . $numberOfFollowersURL_json[0]->friends_count;
            echo "<br>$myScreenName db friends count: " . count($dbFriends);








            /** 
             *
             *
             * AUTO-WHITELIST
             * TODO: REMOVE SCREEN_NAME PROCESSING
             * 
             *
             *
             */


            // FIND VALID DMs SENT
            echo "<h2>VALID DMs</h2>";
            echo "<h4>API requests left: $api_requests</h4>";
            $directMessagesAPI = "https://api.twitter.com/1.1/direct_messages/sent.json?count=200";
            
            /** 
             *
             *
             * TODO: SET FLAG THAT DROPS COUNT DOWN TO 50
             * 
             *
             *
             */
            
            $directMessagesAPI = $connection->get("$directMessagesAPI");

            if (isset($directMessagesAPI->errors)) {

				$errorCount++;
                $errorObject = $directMessagesAPI->errors;
                $error = $errorObject[0]->code;
                $errorMessage .= "<h2>Error $errorCount</h2>";
                $errorMessage .= "Direct message request needs to refresh. " . $errorObject[0]->message;

                echo "<div class='errorMessage'>$errorMessage</div>";

                break;

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
                $containsGeneric = preg_match("/^Hey, thanks so much for following/", $text);
                $containsGeneric2 = preg_match("/^Hey thanks so much for following/", $text);
                $containsGeneric3 = preg_match("/^Hey thank you so much for following/", $text);
                $containsGeneric4 = preg_match("/^Thank you so much for following/", $text);
                $containsGeneric5 = preg_match("/^Thanks for following/", $text);
                $containsGeneric6 = preg_match("/^Thank you for following/", $text);
                
                //echo $containsGeneric;


                if ((!$containsGeneric) && (!$containsGeneric2) && (!$containsGeneric3) && (!$containsGeneric4) && (!$containsGeneric5) && (!$containsGeneric6)) {

                    if (!in_array($userID, $dmArray_IDs)) {
	                    echo "$screenName was messaged: $text<br>";
                        $dmArray_IDs[] = $userID;
                    }
                }


            }




			/** 
             *
             *
             * TODO: FIND VALID DMs RECEIVED
             * 
             *
             *
             */



            // FIND VALID MENTIONS
            echo "<h2>VALID MENTIONS</h2>";
            echo "<h3>". Carbon::now()."</h3>";
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

				// TODO: IMPROVE FILTER

                $atSymbol = strpos($fullMention, '@');
                $lastAtSymbol = strrpos($fullMention, '@');

                $genericFollow = strpos($fullMention, 'follow');

                if ($atSymbol == $lastAtSymbol) {

                    if (!$genericFollow) {
	                    
                        if (!in_array($userID, $goodMentionsArray_IDs)) {
	                        echo "<br>$screenName mentioned: $fullMention";
                            $goodMentionsArray_IDs[] = $userID;
                        }


                    }


                }

            }
            
            
            
            
            
            
            echo "<h3>before target ". Carbon::now()."</h3>";
			
			// GET ALL TARGET USERS AND PROCESS INTO ARRAY
			$targetUsers = TargetUser::where('social_media_account_id', $socialMediaAccount->id)->select('account_id')->get()->all();
			
 			echo "<h3>after target ". Carbon::now()."</h3>";
			
			$targetUsers_ids = array();
			
			foreach($targetUsers as $id) {
				$targetUsers_ids[] = $id->account_id;
			}
			
			
			echo "<h3>after array processing ". Carbon::now()."</h3>";
			
			// WHITELIST ACTIVE MENTIONS and DMs
			if((!empty($goodMentionsArray_IDs)) && (!empty($dmArray_IDs)) && (!empty($targetUsers_ids))){
				
				
				// FIND MENTIONS TARGET USERS THAT ARE NOT IN THE DATABASE
				$targetUsersToAdd_ids = array_diff($goodMentionsArray_IDs, $targetUsers_ids);
				
				// FIND DMs THAT ARE NOT IN THE DATABASE AND ADD THEM
				$targetUsersToAdd_ids = array_diff($dmArray_IDs, $targetUsers_ids);
				
				// FILTER OUT FRIENDS
				$targetUsersToAdd_ids = array_diff($targetUsersToAdd_ids, $oldFriends_ids);
				
				foreach($targetUsersToAdd_ids as $id) {
					
					$newFriend = Friend::create([
                        'account_id' => $id,
                        'social_media_account_id' => $socialMediaAccount->id,
                        'to_follow' => 1,
                        'whitelist' => 1
                    ]);
                    
                    echo "<br>$id - Whitelisted";
                    
                    
                    
                    $follow = $connection->post("https://api.twitter.com/1.1/friendships/create.json?user_id=$id&follow=true");

                    if (isset($follow->errors)) {

						$errorCount++;
                        $errorObject = $follow->errors;
                        $error = $errorObject[0]->code;
                        
                        $errorMessage .= "<h2>Error $errorCount</h2>";
                        $errorMessage .= "Friendship creator to needs to refresh. " . $errorObject[0]->message;

                        echo "<div class='errorMessage'>$errorMessage</div>";

                        break;

                    } else {
	                	echo " and followed!";
	                	
	                	$target = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                            ->where('account_id', $id)
                            ->get()
                            ->first();
                        if (isset($target)) {
	                        TargetUser::find($target['id'])->delete();
                        }
                            
	                	
	                }
                    
				}
				
			} 
			

			echo "<h3>". Carbon::now()."</h3>";
			
			
						





            echo '<hr>';
            $now = Carbon::now('America/Denver');
            echo "<br>$now";
            
            if ($errorCount > 0) {
	            Helper::email_admin($errorMessage, $errorCount, "AutomationController", $socialMediaAccount->screen_name);
            }
            
            
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
