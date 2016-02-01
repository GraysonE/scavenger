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

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socialMediaAccounts = SocialMediaAccount::get()->all();

        // GraysonErhard = 3
        // iamabadass4real = 4
        // AspenHourglass = 5
        // pandasandpeeps = 6
        // SELECT * FROM `temp_target_users` WHERE `social_media_account_id` like "6"

//        $socialMediaAccount = SocialMediaAccount::findOrFail(6);

        foreach($socialMediaAccounts as $socialMediaAccount) {

            echo "<h2>$socialMediaAccount->screen_name</h2>";

            $connection = new TwitterOAuth(
                $socialMediaAccount->consumer_key,
                $socialMediaAccount->consumer_secret,
                $socialMediaAccount->access_token,
                $socialMediaAccount->access_token_secret);

            $targetUsers = TargetUser::where('social_media_account_id', $socialMediaAccount->id)->get();

            $i = 1;

			$dailyFollowLimit = 142;

            if (is_null($targetUsers)) {
                echo "No Target Users in DB.";
            } else {
	            
	            
	            
                foreach ($targetUsers as $targetUser) {
	                
	                if ($dailyFollowLimit == 0) {
		                break;
	                }
	                
                    $follow = $connection->post("https://api.twitter.com/1.1/friendships/create.json?user_id=$targetUser->account_id&follow=true");

                    if (isset($follow->errors)) {

                        $errorObject = $follow->errors;
                        $error = $errorObject[0]->code;
                        $errorMessage = "Model account follower lookup to needs to refresh. " . $errorObject[0]->message;

                        echo "<div class='errorMessage'>$errorMessage</div>";

                        Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                        break;

                    } else {
	                    
                        echo "<br>$i: $targetUser->screen_name followed!";

                        Friend::create([
                            'account_id' => $targetUser->account_id,
                            'screen_name' => $targetUser->screen_name,
                            'whitelisted' => ($targetUser->whitelist) ? true : false,
                            'social_media_account_id' => $socialMediaAccount->id
                        ]);

                        $oldTargetUser = TargetUser::findOrFail($targetUser->id)->delete();
                        echo " - Target User deleted from DB.";
                        
                        $dailyFollowLimit--;
                        
                    }
                    $i++;
                }
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