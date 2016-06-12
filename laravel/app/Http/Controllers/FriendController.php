<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\SocialMediaAccount;
use Auth;
use Scavenger\Twitter\TwitterOAuth;
use Carbon\Carbon;
use Scavenger\Helpers\Helper;
use Scavenger\Friend;

class FriendController extends Controller
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

			$errorCount = 0;
			$errorMessage = "";
			$api_requests = 15;

			if (isset($_GET['id'])) {
				if ($socialMediaAccount->id != $_GET['id']) {
		   			continue;
	   			}	
			}
   			
			
            

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
				//$searchFriendsAPI = "";
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
			
			
			echo '<hr>';
            $now = Carbon::now('America/Denver');
            echo "<br>$now";
            
            if ($errorCount > 0) {
	            Helper::email_admin($errorMessage, $errorCount, "FriendController", $socialMediaAccount->screen_name);
            }
            
        }
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
