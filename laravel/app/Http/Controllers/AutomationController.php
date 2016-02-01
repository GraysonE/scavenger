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


        $count = 5000;

        $socialMediaAccounts = SocialMediaAccount::get()->all();

        // GraysonErhard = 3
        // iamabadass4real = 4
        // AspenHourglass = 5
        // pandasandpeeps = 6

//        $socialMediaAccount = SocialMediaAccount::findOrFail(6);

        foreach($socialMediaAccounts as $socialMediaAccount) {

            $api_requests = 15;

            $connection = new TwitterOAuth(
                $socialMediaAccount->consumer_key,
                $socialMediaAccount->consumer_secret,
                $socialMediaAccount->access_token,
                $socialMediaAccount->access_token_secret);


            $screenName = $socialMediaAccount->screen_name;
            $myScreenName = $socialMediaAccount->screen_name;

            echo "<h1>$myScreenName</h1>";

            /** 1
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

            do {


                $searchFriendsAPI = "https://api.twitter.com/1.1/friends/list.json?cursor=$cursor&screen_name=$screenName&skip_status=true&include_user_entities=false&count=200";

                $friends = $connection->get("$searchFriendsAPI");

                if (isset($friends->errors)) {

                    $errorObject = $friends->errors;
                    $error = $errorObject[0]->code;
                    $errorMessage = "Friend lookup needs to refresh. " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";
                    Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                    break;

                } else {

//                    print_r(json_encode($friends));echo '<br>';

                    $friendUsers = $friends->users;
                    $cursor = $friends->next_cursor;

                    foreach ($friendUsers as $friend) {

                        if ($i %9 == 0) {
                            echo "<div class='floating_box'>";
                        }

//                         echo "<br><strong>Online: </strong>$friend->screen_name";

                        if ($i %19 == 0) {
                            echo "</div>";
                        }

                        $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                            ->where('account_id', $friend->id)
                            ->where('screen_name', $friend->screen_name)
                            ->get()
                            ->first();

                        if (is_null($oldFriend)) {

                            $newFriend = Friend::create([
                                'account_id' => $friend->id,
                                'screen_name' => $friend->screen_name,
                                'social_media_account_id' => $socialMediaAccount->id
                            ]);

//                            echo "<br>"; print_r($newFriend);

                        }


                        $i++;
                    }

                }

                echo "<br><br><strong>Next Cursor: </strong>$cursor";

                $api_requests--;

            } while (($api_requests > 0) && ($cursor > 0));



            echo '<br><br><h2>FRIENDS IN DATABASE</h2><BR>';

            $friendsComprehensive = Friend::where('social_media_account_id', $socialMediaAccount->id)->get();

            foreach ($friendsComprehensive as $friend) {
//                 echo '<br><strong>DB: </strong>'. $friend->screen_name;
            }





            /** 1
             *
             *
             * Who is following me? / FOLLOWERS
             *
             *
             */

            echo '<h2>ONLINE FOLLOWERS</h2>';echo '<br>';
            echo "<h4>API requests left: $api_requests</h4>";

            $cursor = "-1";
            $i=0;


            do {

                $i++;


                $searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$screenName&count=$count";

                $followers = $connection->get("$searchFollowersAPI");
                if (isset($followers->errors)) {

                    $errorObject = $followers->errors;
                    $error = $errorObject[0]->code;
                    $errorMessage = $errorObject[0]->message;
                    $errorMessage = "Follower lookup needs to refresh. " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";

                    Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                    break;

                } else {

                    $cursor = $followers->next_cursor;
                    $onlineFollowers = $followers->ids;

                    foreach ($onlineFollowers as $follower) {

//                        echo "<br><br>type: ".gettype($follower);

                        if ($i %10 == 0) {
                            echo "<div class='floating_box'>";
                        }

//                         echo "<br><strong>Online: </strong>$follower";

                        if ($i %20 == 0) {
                            echo "</div>";
                        }


                        $oldFollower = Follower::where('social_media_account_id', $socialMediaAccount->id)
                            ->where('account_id', $follower)
                            ->get()
                            ->first();

                        if (is_null($oldFollower)) {


                            $newFollower = Follower::create([
                                'account_id' => $follower,
                                'social_media_account_id' => $socialMediaAccount->id
                            ]);

//                            echo "<br>"; print_r($newFollower);
                        }



                    }


                }
                $api_requests--;
                echo "<br><br><strong>Next Cursor: </strong>$cursor";
            } while (($api_requests > 0) && ($cursor > 0));








            /** 1
             *
             *
             * How many followers do I have?
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
                $errorMessage = "Could not look up your user information. " . $errorObject[0]->message;

                echo "<div class='errorMessage'>$errorMessage</div>";

                Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                break;
            }

            $dbFriends = Friend::where('social_media_account_id', '=', $socialMediaAccount->id)
                ->get();
            $dbFollowers = Follower::where('social_media_account_id', '=', $socialMediaAccount->id)
                ->get();

            echo "<br>$myScreenName online followers count: " . $numberOfFollowersURL_json[0]->followers_count;
            echo "<br>$myScreenName db followers count: " . count($dbFollowers);

            echo "<br>$myScreenName online friends count: " . $numberOfFollowersURL_json[0]->friends_count;
            echo "<br>$myScreenName db friends count: " . count($dbFriends);


            /** 2
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
                $genericString = "Hey, thanks so much for following";
                $containsGeneric = preg_match("/^$genericString/", $text);
                //echo $containsGeneric;


                if (!$containsGeneric) {

                    if (!in_array($screenName, $dmArray_ScreenNames)) {
                        echo "<br>$screenName was messaged: $text<br>";
                        $dmArray_ScreenNames[$i] = $screenName;
                    }
                    if (!in_array($userID, $dmArray_IDs)) {
                        $dmArray_IDs[$i] = $userID;

                        $i++;
                    }
                }


            }

            // CONTINUE WITH TWO ARRAYS FOR SCREEN_NAME AND IDS
            echo "<h2>GOOD DM ARRAYS:</h2>";
            print_r($dmArray_ScreenNames);
            echo "<br>";
            print_r($dmArray_IDs);


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

                        echo "<br>$screenName mentioned: $fullMention<br>";
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

            echo "<h2>GOOD MENTIONS ARRAYS:</h2>";
            print_r($goodMentionsArray_ScreenNames);
            echo "<br>";
            print_r($goodMentionsArray_IDs);

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
                    echo "<br>2";

                    $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                        ->where('account_id', $id)
                        ->where('screen_name', $goodMentionsArray_ScreenNames[$k])
                        ->get()
                        ->first();

                    if ((!$whitelist->isEmpty()) && (!is_null($oldFriend))) {
                        echo "<br>3";

                            if ($id != $oldFriend->account_id) {
                                echo "<br>4";
                                $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                    ->where('account_id', $id)
                                    ->where('screen_name', $goodMentionsArray_ScreenNames[$k])
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

                                    echo "<br>Mention Whitelisted: "; print_r($newTarget);

                                }

                            } else {
                                echo "<br>5";
                                $friend_to_be_whitelisted = Friend::findOrFail($oldFriend->id);
                                $friend_to_be_whitelisted->whitelisted = 1;
                                $saved = $friend_to_be_whitelisted->save();

                                if ($saved) {
                                    echo "<br><strong>Mention Whitelisted: </strong>$goodMentionsArray_ScreenNames[$k]";
                                }
                            }

                    } else {
                        echo "<br>6";
                        $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                            ->where('account_id', $id)
                            ->where('screen_name', $goodMentionsArray_ScreenNames[$k])
                            ->get()
                            ->first();

                        if (is_null($oldFriend)) {

                            $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                ->where('account_id', $id)
                                ->where('screen_name', $goodMentionsArray_ScreenNames[$k])
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

                                echo "<br>Mention Whitelisted: "; print_r($newTarget);

                            }



                        } else {
                            echo "<br>5";
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
                    echo "<br>2";

                    $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                        ->where('account_id', $id)
                        ->where('screen_name', $dmArray_ScreenNames[$k])
                        ->get()
                        ->first();

                    if ((!$whitelist->isEmpty()) && (!is_null($oldFriend))) {

                        if ($id != $oldFriend->account_id) {
                            echo "<br>4";
                            $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                ->where('account_id', $id)
                                ->where('screen_name', $dmArray_ScreenNames[$k])
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

                                echo "<br>DM Whitelisted: "; print_r($newTarget);

                            }

                        } else {
                            echo "<br>5";
                            $friend_to_be_whitelisted = Friend::findOrFail($oldFriend->id);
                            $friend_to_be_whitelisted->whitelisted = 1;
                            $saved = $friend_to_be_whitelisted->save();

                            if ($saved) {
                                echo "<br><strong>DM Whitelisted: </strong>$dmArray_ScreenNames[$k]";
                            }
                        }

                    } else {
                        echo "<br>6";
                        $oldFriend = Friend::where('social_media_account_id', $socialMediaAccount->id)
                            ->where('account_id', $id)
                            ->where('screen_name', $dmArray_ScreenNames[$k])
                            ->get()
                            ->first();

                        if (is_null($oldFriend)) {

                            $oldTarget = TargetUser::where('social_media_account_id', $socialMediaAccount->id)
                                ->where('account_id', $id)
                                ->where('screen_name', $dmArray_ScreenNames[$k])
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

                                echo "<br>DM Whitelisted: "; print_r($newTarget);

                            }



                        } else {
                            echo "<br>5";
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




            /** 2
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
                ->get()
                ->first();


            $i=0;



            if (!is_null($modelAccount)) {

                echo "<h4>Model User: $modelAccount->screen_name</h4>";

                do {

                    $i++;

                    echo '<h2>MODEL ACCOUNT ONLINE FOLLOWERS</h2>';echo '<br>';

                    $cursor = $modelAccount->api_cursor;

                    $searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$modelAccount->screen_name&count=$count";

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

//                        echo "<br><br>type: ".gettype($follower);

                            echo "<br><strong>Online: </strong>$follower";

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

//                                        echo "<br>"; print_r($newTemp);

                                    }



                                }


                            }



                        }


                    }
                    $api_requests--;
                    $cursor = $followers->next_cursor;

                    $modelAccount->api_cursor = $followers->next_cursor;
                    $modelAccount->save();


                    echo "<br><br><strong>Next Cursor: </strong>$cursor";
                } while (($api_requests > 0) && ($cursor > 0));
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
                    $errorMessage = "Could not research potential user. " . $errorObject[0]->message;

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

                        // MAKE CSV WITH ALL RELEVANT TWITTER USER DATA
//                        if (isset($content)) {
//                            if ($content != "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,$statuses_count,$last_status,$created_at,1,\n") {
//                                $content = "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,$statuses_count,$last_status,$created_at,1,\n";
//                            }
//                        } else {
//                            $content = "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,$statuses_count,$last_status,$created_at,1,\n";
//                        }
//
//                        $file = "/Applications/XAMPP/xamppfiles/htdocs/scavvy/laravel/public/twitter-users.csv";
//                        file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

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

                        // MAKE CSV WITH ALL RELEVANT TWITTER USER DATA
//                        if (isset($content)) {
//                            if ($content != "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,0,NULL,$created_at,2,\n") {
//                                $content = "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,0,NULL,$created_at,2,\n";
//                            }
//                        } else {
//                            $content = "$requestScreenName,$temp_account_id,$followersCount,$friendsCount,$favoritesCount,0,NULL,$created_at,2,\n";
//                        }
//
//                        $file = "/Applications/XAMPP/xamppfiles/htdocs/scavvy/laravel/public/twitter-users.csv";
//                        file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

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
