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

        // GraysonErhard = 3
        // iamabadass4real = 4
        // AspenHourglass = 5
        // pandasandpeeps = 6
        // SELECT * FROM `temp_target_users` WHERE `social_media_account_id` like "6"

        $socialMediaAccount = SocialMediaAccount::findOrFail(6);

//        foreach($socialMediaAccounts as $socialMediaAccount) {

        $connection = new TwitterOAuth(
            $socialMediaAccount->consumer_key,
            $socialMediaAccount->consumer_secret,
            $socialMediaAccount->access_token,
            $socialMediaAccount->access_token_secret);


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

            if ($tempAccount->account_id == '4722600750') {
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
                $errorMessage = "Could not research potential user. " . $errorObject[0]->message;

                echo "<div class='errorMessage'>$errorMessage</div>";

                Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                break;

            } else {

                $userInvestigation = $userInvestigation_json[0];


                $time = (int)strtotime(Carbon::now());
                $thirty_days_unix_time = (int)2963452 / 2;
                $sixty_days_unix_time = (int)2963452;
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

                    // MAKE CSV WITH ALL RELEVANT TWITTER USER DATA
                    if (isset($content)) {
                        if ($content != "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,$statuses_count,$last_status,$created_at,1,\n") {
                            $content = "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,$statuses_count,$last_status,$created_at,1,\n";
                        }
                    } else {
                        $content = "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,$statuses_count,$last_status,$created_at,1,\n";
                    }

                    $file = "/Applications/XAMPP/xamppfiles/htdocs/scavvy/laravel/public/twitter-users.csv";
                    file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

                    if ($last_status < ($time - $thirty_days_unix_time)) { // LAST STATUS HAS TO HAVE BEEN IN THE PAST MONTH

                        if ($statuses_count > 20) {

                            if ($friendsCount >= ($followersCount - 50)) { // MORE PEOPLE FOLLOWING THAN FOLLOWING THEM

                                if ($favoritesCount > 20) {

//                                        if($created_at > ($time-$thirty_days_unix_time)) { // HAS TO HAVE BEEN CREATED AT LEAST A MONTH AGO

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

//                                        } else {
//                                        echo " - Account created less than a month ago";
//
//                                        }
                                } else {
                                    echo " - Only $favoritesCount favorites.";
                                }
                            } else {
                                echo " - Doesn't have more friends than followers.";
                            }
                        } else {
                            echo " - Only $statuses_count statuses.";
                        }
                    } else {
                        echo " - Hasn't made a status in the last month";
                    }
                } else {
                    echo " - Has never made a status.";

                    // MAKE CSV WITH ALL RELEVANT TWITTER USER DATA
                    if (isset($content)) {
                        if ($content != "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,0,NULL,$created_at,2,\n") {
                            $content = "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,0,NULL,$created_at,2,\n";
                        }
                    } else {
                        $content = "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,0,NULL,$created_at,2,\n";
                    }

                    $file = "/Applications/XAMPP/xamppfiles/htdocs/scavvy/laravel/public/twitter-users.csv";
                    file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

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

        // } // foreach of socialMediaAccounts
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
