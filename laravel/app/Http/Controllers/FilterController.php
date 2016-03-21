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

/*
			if ($socialMediaAccount->id == 4) {
				continue;
			}
*/
        
        
			/**
             *
             *
             * FILTER THE TARGET ACCOUNTS BASED ON USER LOOKUP
             *
             */


            echo "<h2>@$socialMediaAccount->screen_name - TARGET USERS:</h2>";


			// TODO: LOAD ONLY 180 ACCOUNTS
            $tempAccounts = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
            	->where('to_follow', 0)
            	->take(175)
                ->get();
                
            
                
            $crunchAccount = false;
                
            if ($tempAccounts->isEmpty()) {
	            $tempAccounts = TargetUser::where('to_follow', 0)
            	->take(175)
                ->get();
                
                $crunchAccount = true;
            }

            foreach($tempAccounts as $tempAccount) {

				$errorMessage = "";
				$errorCount = 0;
                $temp_account_id = (int)$tempAccount->account_id;

                echo "<br>Target Account: ";

                //Investigate User Before Following
                $userInvestigationURL = "https://api.twitter.com/1.1/users/lookup.json?user_id=$temp_account_id";
                $userInvestigation_json = $connection->get("$userInvestigationURL");


                if (isset($userInvestigation_json->errors)) {

                    $errorObject = $userInvestigation_json->errors;
                    $ErrorCode = $errorObject[0]->code;
                    $errorCount++;
					$errorMessage .= "<h2>Error $errorCount</h2>";
                    $errorMessage .= "Could not research $temp_account_id for filtration. Error Code: $ErrorCode - " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";

					if ($ErrorCode == 17) {
						$errorMessage .= "<br>CONTINUE";
						$target = TargetUser::where('account_id', $temp_account_id)->get()->first();
						if (isset($target)) {
							TargetUser::find($target['id'])->delete();
						}
						
						
						continue;
						
					} else {
						$errorMessage .= "<br>BREAK";
						break;
					}
						
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

					$to_follow = 0;


                    if (isset($userInvestigation->status->created_at)) {

                        $last_status = (int)strtotime($userInvestigation->status->created_at); // MUST BE UNDER 30 DAYS OLD

                        if($accountCreated_at < ($time-$one_month_unix_time)) { // HAS TO HAVE BEEN CREATED AT LEAST A MONTH AGO
	                        
                            if ($last_status > ($time - ($one_day_unix_time*2))) { // LAST STATUS HAS TO HAVE BEEN IN THE PAST 48 HOURS

                                if ($statuses_count > 50) {

                                    if ($favoritesCount > 50) {

                                        if ($friendsCount >= ($followersCount - 50)) { // MORE PEOPLE FOLLOWING THAN FOLLOWING THEM

                                                echo "$temp_account_id";
                                                
                                                if ($crunchAccount) {
	                                                $target = TargetUser::where('account_id', $temp_account_id)->get()->first();
                                                } else {
	                                                $target = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                                			->where('account_id', $temp_account_id)->get()->first();
                                                }
                                                
                                                $target->screen_name = $requestScreenName;
                                                $target->whitelist = 0;
                                                $target->to_follow = 1;
                                                $target->save();   
                                                
                                                $to_follow = 1;                                             

                                                echo " - $requestScreenName - <strong><em>FLAGGED!!!</em></strong>";
                                                
                                        } else {
                                            echo " - Doesn't have more friends than followers.";
                                        }
                                    } else {
                                        echo " - $favoritesCount favorites";
                                    }
                                } else {
                                    echo " - $statuses_count statuses";
                                }
                            } else {
                                echo " - 0 status in 48 hrs";
                            }
                        } else {
                            echo " - Created < 1 month ago";
                        }
                    } else {
                        echo " - 0 statuses";
                    }

                }
                
                if ($crunchAccount) {
                    $target = TargetUser::where('account_id', $temp_account_id)->where('to_follow', 0)->get()->first();
                } else {
                    $target = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                            ->where('account_id', $temp_account_id)->where('to_follow', 0)->get()->first();
                }
                
                if (isset($target)) {
	                if (!$to_follow) {
		                TargetUser::find($target['id'])->delete();
						echo " $temp_account_id - DELETED.";
	                }
					
                }

            }
        
			if ($errorCount > 0) {
	            Helper::email_admin($errorMessage, $errorCount, "FilterController", $socialMediaAccount->screen_name);
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
