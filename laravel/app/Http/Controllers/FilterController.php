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

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socialMediaAccounts = SocialMediaAccount::get()->all();

        foreach($socialMediaAccounts as $socialMediaAccount) {

	        $connection = new TwitterOAuth(
	            $socialMediaAccount->consumer_key,
	            $socialMediaAccount->consumer_secret,
	            $socialMediaAccount->access_token,
	            $socialMediaAccount->access_token_secret);

			if ($socialMediaAccount->id == 4) {
				continue;
			}
        
        
			/**
             *
             *
             * FILTER THE TEMP ACCOUNTS BASED ON USER LOOKUP
             *
             */


            echo "<h2>TEMP TARGET USERS:</h2>";


			// TODO: LOAD ONLY 180 ACCOUNTS
            $tempAccounts = TempTargetUser::where('social_media_account_id', $socialMediaAccount->id)
                ->get();

            $api_limit = 180;

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

                    continue;

                } else {

                    $userInvestigation = $userInvestigation_json[0];


                    $time = (int)strtotime(Carbon::now());
                    $one_month_unix_time = (int)86400*30;
                    $one_day_unix_time = (int)86400;
                    $accountCreated_at = (int)strtotime($userInvestigation->created_at);

                    $requestScreenName = "$userInvestigation->screen_name";
                    $followersCount = (int)$userInvestigation->followers_count;
                    $friendsCount = (int)$userInvestigation->friends_count;
                    $favoritesCount = (int)$userInvestigation->favourites_count;
                    $statuses_count = (int)$userInvestigation->statuses_count;


                    /**
                     *
                     *
                     * THE FILTER
                     *
                     */


                    if (isset($userInvestigation->status->created_at)) {

                        $last_status = (int)strtotime($userInvestigation->status->created_at); // MUST BE UNDER 30 DAYS OLD

                        if($accountCreated_at > ($time-$one_month_unix_time)) { // HAS TO HAVE BEEN CREATED AT LEAST A MONTH AGO

                            if ($last_status < ($time - ($one_day_unix_time*2))) { // LAST STATUS HAS TO HAVE BEEN IN THE PAST 48 HOURS

                                if ($statuses_count > 50) {

                                    if ($favoritesCount > 50) {

                                       if ($friendsCount >= ($followersCount - 50)) { // MORE PEOPLE FOLLOWING THAN FOLLOWING THEM

										   // TODO: ALREADY IN THIS TABLE, JUST UPDATE

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
        
        
        
        } // foreach of socialMediaAccounts
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
