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
use Carbon\Carbon;
use Scavenger\Helpers\Helper;

class UnfollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    
        $socialMediaAccounts = SocialMediaAccount::where('account_type', 'twitter')
	        ->where('auto_unfollow', 1)
	        ->get()
	        ->all();

        foreach($socialMediaAccounts as $socialMediaAccount) {

			$errorMessage = "";
			$errorCount = 0;
			$i = 1;

			if ($socialMediaAccount->id != 10) {
				continue;
			}

            echo "<h2>$socialMediaAccount->screen_name</h2>";
			
            $connection = new TwitterOAuth(
                $socialMediaAccount->consumer_key,
                $socialMediaAccount->consumer_secret,
                $socialMediaAccount->access_token,
                $socialMediaAccount->access_token_secret);

            $friends = Friend::where('social_media_account_id', $socialMediaAccount->id)
            	->where('whitelisted', 0)
//             	->where('unfollowed', 0)
//             	->where('created_at', '<=', Carbon::today('America/Denver')->subweek())
            	->select('account_id')
            	->take(100)
            	->get()
            	->toArray();

			// ->where('created_at', '<=', Carbon::today('America/Denver')->subweek())

			

			$oldFriends_ids = array();
			
			foreach($friends as $account_id) {
				$oldFriends_ids[] = $account_id['account_id'];
			}


            if (is_null($oldFriends_ids)) {
	            
                echo "No friends in DB.";
                
            } else {
	            
                foreach ($oldFriends_ids as $oldFriend) {

                    $destroyFriendship = $connection->post("https://api.twitter.com/1.1/friendships/destroy.json?user_id=$oldFriend");
										
                    if (isset($destroyFriendship->errors)) {

                        $errorObject = $destroyFriendship->errors;
                        $error = $errorObject[0]->code;
                        $errorCode = $errorObject[0]->code;
                        $errorCount++;
						$errorMessage .= "<h2>Error $errorCount</h2>";
                        $errorMessage .= $errorObject[0]->message;

                        echo "<div class='errorMessage'>$errorMessage</div>";
						
						if (261 == $errorCode) {
							
							Helper::email_admin("Need to reset Twitter Auth Tokens", 1, "UnfollowController", $socialMediaAccount->screen_name);
							break;
							
						} elseif (34 == $errorCode) {
							
							break;
							
						} else {
							
							continue;
							
						}

                    } else {
	                    
                        echo "<br>$i: $oldFriend - friendship destroyed!";

                        $friend = Friend::where('social_media_account_id', $socialMediaAccount->id)
			            	->where('account_id', $oldFriend)
			            	->get()
			            	->first();
			            	
			            $friend->unfollowed = 1;
			            $friend->unfollowed_timestamp = Carbon::now('America/Denver');
			            $friend->save();
                        echo " - Unfollow flagged in DB.";
                        
                        
                        
                    }
                    $i++;
                }
            }
            
            $i--; // Accurate amount of friendships
            
            $message = "$i friendships destroyed for $socialMediaAccount->screen_name.";
            
            Helper::email_user($message, $socialMediaAccount->user_id);
            
            if ($errorCount > 0) {
	            Helper::email_admin($errorMessage, $errorCount, "UnfollowController", $socialMediaAccount->screen_name);
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
