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
use Scavenger\Follower;

class FollowerController extends Controller
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
			
			echo '<hr>';
            $now = Carbon::now('America/Denver');
            echo "<br>$now";
            
            if ($errorCount > 0) {
	            Helper::email_admin($errorMessage, $errorCount, "FollowerController", $socialMediaAccount->screen_name);
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
