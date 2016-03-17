<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\SocialMediaAccount;
use Auth;
use Scavenger\Twitter\TwitterOAuth;
use Scavenger\ModelAccount;
use Scavenger\User;
use Scavenger\Helpers\Helper;
use Scavenger\Friend;
use Scavenger\Follower;
use Scavenger\TargetUser;
use Carbon\Carbon;

class ModelAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
	    
	    $currentAccount = User::where('id', Auth::user()->id)->get()->first()->username;

        if ($id != 'select' && 'search') {

            $socialMediaAccount = SocialMediaAccount::findOrFail($id);
            $modelAccounts = ModelAccount::where('social_media_account_id', $id)->orderBy('sort_order', 'ASC')->get();

            $bladeVariables = compact('socialMediaAccount', 'modelAccounts', 'currentAccount');

            return view('set-user.search')->with($bladeVariables);

        } else {

			if (Auth::user()->id == 1) {
				$socialMediaAccounts = SocialMediaAccount::get();
			} else {
				$socialMediaAccounts = SocialMediaAccount::where('user_id', Auth::user()->id)->get();
			}

            $bladeVariables = compact('socialMediaAccounts', 'currentAccount');

            return view('set-user.index')->with($bladeVariables);
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
    public function store(Request $request, $id)
    {
        $requestData = json_decode($request->all()['search']);

        $data = new ModelAccount();
        $data->model_user_id = $requestData->id;
        $data->screen_name = $requestData->screen_name;
        $data->social_media_account_id = $id;
        $data->api_cursor = '-1';
        $data->sort_order = 0;
        $data->save();

        return redirect("set-user/$id");
    }

    /**
     * Search for a social media account to follow.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $id)
    {

        $socialMediaAccount = SocialMediaAccount::findOrFail($id);

        $connection = new TwitterOAuth(
            $socialMediaAccount->consumer_key,
            $socialMediaAccount->consumer_secret,
            $socialMediaAccount->access_token,
            $socialMediaAccount->access_token_secret);

        $data = $request->get('model_user');

        $count = 5;

        $searchQuery = "https://api.twitter.com/1.1/users/search.json?q=$data&page=1&count=$count";

        $searchJson = $connection->get("$searchQuery");

        $bladeVariables = compact('socialMediaAccount', 'searchJson');
        
        
        $http_code = $connection->http_code;

		if ($http_code != '200') {
			
			$errorMessage = "HTTP code: $http_code";
			$errorMessage .= "<br><h4>Data Dump</h4>";
			$errorMessage .= var_dump($connection);
			
			Helper::email_admin($errorMessage, 1, "ModelAccountController", $socialMediaAccount->screen_name);
			
			
			if ($http_code == '401') {
				return view('errors.401')->with(compact('http_code'));
			}
			
		} else {
			return view('set-user.show')->with($bladeVariables);
		}

        
    }
    
    
    
    
    public function filter() 
    {

        $socialMediaAccounts = SocialMediaAccount::where('account_type', 'twitter')->get()->all();
		
		$crunchAccounts = SocialMediaAccount::where('account_type', 'crunch')->get()->all();
		
		// Calculate how many accounts to grab from the API to filter
		$count = (count($crunchAccounts) / count($socialMediaAccounts)) * 180;
		
        foreach($socialMediaAccounts as $socialMediaAccount) {
	    
	    
			$errorCount = 0;
			$errorMessage = "";

            $connection = new TwitterOAuth(
                $socialMediaAccount->consumer_key,
                $socialMediaAccount->consumer_secret,
                $socialMediaAccount->access_token,
                $socialMediaAccount->access_token_secret);
			
            $myScreenName = $socialMediaAccount->screen_name;

            echo "<h1>$myScreenName</h1>";
            
            
            
            
            
            
            // GET CURRENT DB Followers
			$oldFollowers = Follower::where('social_media_account_id', $socialMediaAccount->id)->select('account_id')->get()->all();
			
			$oldFollowers_ids = array();
			foreach($oldFollowers as $account_id) {
				$oldFollowers_ids[] = $account_id['account_id'];
				
			}
			
			// GET CURRENT DB FRIENDS
			$oldFriends = Friend::where('social_media_account_id', $socialMediaAccount->id)->select('account_id')->get()->all();
			
			$oldFriends_ids = array();
			foreach($oldFriends as $account_id) {
				$oldFriends_ids[] = $account_id['account_id'];
				
			}
            
            // GET ALL TARGET USERS AND PROCESS INTO ARRAY
			$targetUsers = TargetUser::where('social_media_account_id', $socialMediaAccount->id)->select('account_id')->get()->all();
			
			$targetUsers_ids = array();
			foreach($targetUsers as $id) {
				$targetUsers_ids[] = $id['account_id'];
			}
            
            
			
			
			
			/** 
             *
             *
             * GET MODEL ACCOUNTS'S FOLLOWERS, FILTER IF ALREADY FOLLOWING OR FRIEND, ADD TO TARGET USERS TABLE
             *
             *
             */
             


            // GET MODEL ACCOUNT
            $modelAccount = ModelAccount::where('social_media_account_id', $socialMediaAccount->id)
                ->where('api_cursor', '!=', 0)
                ->where('sort_order', 1)
                ->get()
                ->first();
			

            if (!is_null($modelAccount)) {
				
				$cursor = (int)$modelAccount->api_cursor;
				
				echo "<h2>@". $modelAccount->screen_name . "'s ONLINE FOLLOWERS</h2><br>";

                $searchFollowersAPI = "https://api.twitter.com/1.1/followers/ids.json?cursor=$cursor&screen_name=$modelAccount->screen_name&count=$count";
				
                $followers = $connection->get("$searchFollowersAPI");

                if (isset($followers->errors)) {
					
					$errorCount++;
                    $errorObject = $followers->errors;
                    $error = $errorObject[0]->code;
                    
                    $errorMessage .= "<h2>Error $errorCount</h2>";
                    $errorMessage .= "Model account follower lookup to needs to refresh. " . $errorObject[0]->message;

                    echo "<div class='errorMessage'>$errorMessage</div>";

                    continue;

                } else {


                    $modelFollowers = $followers->ids;

                    foreach ($modelFollowers as $id) {
						$modelFollowers_ids[] = $id;
					}
					
					if (isset($modelFollowers_ids)) {
						
						$filterFollowers = array_diff($modelFollowers_ids, $oldFollowers_ids);
						$filterFriends = array_diff($filterFollowers, $oldFriends_ids);
						$filterTargets = array_diff($filterFriends, $targetUsers_ids);
 						
						
						foreach ($filterTargets as $id) {
							
							$newTarget = TargetUser::create([
                                        'account_id' => $id,
                                        'social_media_account_id' => $socialMediaAccount->id
                                    ]);
							
						}
							
					}
					
					if (count($modelFollowers) > 0) {
						$modelAccount->api_cursor = $followers->next_cursor;
						$modelAccount->save();
						
						if ($modelAccount->api_cursor == 0) {
							$errorCount++;
							$errorMessage .= "<h2>Error $errorCount</h2>";
			                $errorMessage .= "Model Account API cursor equals 0.<br>";
			                $errorMessage .= "Out of a list of 5000, $i were added to target_users table.<br>";
			                
			            }
					}
					
					
					echo "<br><br><strong>Next Cursor: </strong>$followers->next_cursor";

                }

                

            } else {
	            echo "<h1>Add a model account that hasn't been finished!</h1>";
            }
            
        }
        
        echo '<hr>';
        $now = Carbon::now('America/Denver');
        echo "<br>$now";
        
        if ($errorCount > 0) {
            Helper::email_admin($errorMessage, $errorCount, "ModelAccountController", $socialMediaAccount->screen_name);
            
        }
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
    public function destroy($id, $model_account_id)
    {
        $data = ModelAccount::where('id', $model_account_id)->where('social_media_account_id', $id);

        $data->take(1)->delete();

        return redirect("set-user/$id");
    }
}
