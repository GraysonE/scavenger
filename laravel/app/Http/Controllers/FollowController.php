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

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socialMediaAccounts = SocialMediaAccount::where('account_type', 'twitter')
	        ->where('auto_follow', 1)
	        ->get()
	        ->all();	

        foreach($socialMediaAccounts as $socialMediaAccount) {


			$errorMessage = "";
			$errorCount = 0;
			
            echo "<h2>$socialMediaAccount->screen_name</h2>";

/*
			if ($socialMediaAccount->id == 4) {
	            echo "<h3>SKIPPED!</h3>";
				continue;
			}
*/

            $connection = new TwitterOAuth(
                $socialMediaAccount->consumer_key,
                $socialMediaAccount->consumer_secret,
                $socialMediaAccount->access_token,
                $socialMediaAccount->access_token_secret);

            $targetUsers = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
            	->where('to_follow', 1)
            	->take(100)
            	->get();
            	
			$i=1;

            if (is_null($targetUsers)) {
	            
                echo "No Target Users in DB.";
                continue;
                
            } else {
	            
                foreach ($targetUsers as $targetUser) {
	                
                    $follow = $connection->post("https://api.twitter.com/1.1/friendships/create.json?user_id=$targetUser->account_id&follow=true");

                    if (isset($follow->errors)) {

                        $errorObject = $follow->errors;
                        $error = $errorObject[0]->code;
                        $errorCount++;
						$errorMessage .= "<h2>Error $errorCount</h2>";
                        $errorMessage .= "Friendship creator to needs to refresh. " . $errorObject[0]->message;

                        echo "<div class='errorMessage'>$errorMessage</div>";

						$breakableError = "You are unable to follow more people at this time.";
						$blockedError = 'You have been blocked from following this account at the request of the user.';

						if ($errorObject[0]->message == $blockedError) {
							
							$oldTargetUser = TargetUser::find($targetUser->id)->delete();
							echo " - Target User deleted from DB.";
							
						} elseif (strpos($errorObject[0]->message, $breakableError) !== false) {
							
							break;
							
						}

                        continue;

                    } else {
	                    
                        echo "<br>$i: $targetUser->screen_name followed!";

                        Friend::create([
                            'account_id' => $targetUser->account_id,
                            'screen_name' => ($targetUser->screen_name) ? $targetUser->screen_name : '',
                            'whitelisted' => ($targetUser->whitelist) ? true : false,
                            'social_media_account_id' => $socialMediaAccount->id
                        ]);

                        $oldTargetUser = TargetUser::find($targetUser->id)->delete();
                        echo " - Target User deleted from DB.";
                        
                        
                        
                    }
                    $i++;
                }
            }
            
            $i--; // Accurate amount of friendships
            
            
            if ($i == 0) {
	            $message = "$i friendships created for $socialMediaAccount->screen_name! Please check to see if you have a user scheduled.";
            } else {
	            $message = "$i friendships created for $socialMediaAccount->screen_name!";
            }
            
            
            Helper::email_user($message, $socialMediaAccount->user_id);
            
            if ($errorCount > 0) {
	            Helper::email_admin($errorMessage, $errorCount, "FollowController", $socialMediaAccount->screen_name);
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
