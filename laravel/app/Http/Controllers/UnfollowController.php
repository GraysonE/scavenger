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
	    
	    $oneWeekOld = Carbon::today('America/Denver')->subweek();
	    
		$message = "Beginning unfollow!";
		Helper::email_user($message, 1);
	    
        $socialMediaAccounts = SocialMediaAccount::get()->all();

        foreach($socialMediaAccounts as $socialMediaAccount) {

			$i = 1;

            echo "<h2>$socialMediaAccount->screen_name</h2>";
            
            if ($socialMediaAccount->id == 4) {
	            echo "<h3>SKIPPED!</h3>";
				continue;
			}

            $connection = new TwitterOAuth(
                $socialMediaAccount->consumer_key,
                $socialMediaAccount->consumer_secret,
                $socialMediaAccount->access_token,
                $socialMediaAccount->access_token_secret);

            $friends = Friend::where('social_media_account_id', $socialMediaAccount->id)
            	->where('whitelisted', 0)
            	->where('created_at', '<=', $oneWeekOld)
            	->select('account_id')
            	->get()
            	->toArray();

			$oldFriends_ids = array();
			
			foreach($friends as $account_id) {
				$oldFriends_ids[] = $account_id['account_id'];
			}

			$limit = 142;

            if (is_null($oldFriends_ids)) {
	            
                echo "No friends in DB.";
                
            } else {
	            
                foreach ($oldFriends_ids as $oldFriend) {
	                
	                if ($limit == 0) {
		                break;
	                }
	                
	                
	                
                    $destroyFriendship = $connection->post("https://api.twitter.com/1.1/friendships/destroy.json?user_id=$oldFriend&follow=true");

                    if (isset($destroyFriendship->errors)) {

                        $errorObject = $destroyFriendship->errors;
                        $error = $errorObject[0]->code;
                        $errorMessage = "Friend destroyer to needs to refresh. " . $errorObject[0]->message;

                        echo "<div class='errorMessage'>$errorMessage</div>";

                        Helper::email_admin($errorMessage, $socialMediaAccount->screen_name);

                        continue;

                    } else {
	                    
                        echo "<br>$i: $oldFriend - friendship destroyed!";

                        $friend = Friend::where('social_media_account_id', $socialMediaAccount->id)
			            	->where('account_id', $oldFriend)
			            	->get()
			            	->first();
			            	
			            $friend->to_unfollow = 1;
			            $friend->save();
// 			            $friend->delete();
                        echo " - Unfollow flagged in DB.";
                        
                        
                        
                    }
                    $limit--;
                    $i++;
                }
            }
            
            $message = "$i friendships destroyed for $socialMediaAccount->screen_name.";
            
            Helper::email_user($message, $socialMediaAccount->user_id);
            
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
