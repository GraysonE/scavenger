<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Carbon\Carbon;
use Scavenger\Helpers\Helper;
use Mail;
use Scavenger\SocialMediaAccount;
use Auth;
use Scavenger\Twitter\TwitterOAuth;
use Scavenger\ModelAccount;
use Scavenger\Friend;
use Scavenger\Follower;
use Scavenger\TargetUser;
use Scavenger\Http\Controllers\AutomationController;
use Scavenger\Http\Controllers\FollowController;
use Scavenger\Http\Controllers\UnfollowController;
use Scavenger\Http\Controllers\FilterController;


class CronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::now('America/Denver');
        echo "<br>$now";                              // 2016-01-11 12:38:36

        $today = Carbon::today('America/Denver');
        echo "<br>$today";                            // 2016-01-11 00:00:00

        $month = $now->month;
        echo "<br>$month";                            // 1
        
        $weekOfMonth = $now->weekOfMonth;
        echo "<br>$weekOfMonth";                      // 2

        $dayOfWeek = $now->dayOfWeek;
        echo "<br>$dayOfWeek";                        // 1

        $day = $now->day;
        echo "<br>$day";                              // 11

        $hour = $now->hour;
        echo "<br>$hour";                             // 12
        
        $minute = $now->minute;
        echo "<br>$minute";                             // 38

        if (($hour == 0) || ($hour == 12) || ($hour == 16)) {

			if ($minute == 0) {
				
				echo '<br>Minute 0 Automation';
				
				$automate = new AutomationController();
				$automate->index();
				
			} elseif (($minute == 15) || ($minute == 30) || ($minute == 45)) {
				
				sleep(120);
				echo '<br>Minute 15/30/45 Filter';
				$filter = new FilterController();
				$filter->index();
				
			}
			
        } else {
	        
	        if ($minute % 15 == 0) {
	        
	        	sleep(120);
	        	echo '<br>Minute 0/15/30/45 Automation';
		        $filter = new FilterController();
				$filter->index();
			
			}
			
        }
	       
	    
	        
        if ($weekOfMonth % 2 != 0) { // if week is 1st or 3rd of month, follow

			if ($hour == 12) {
				
				if ($minute % 15 == 0) {
					echo '<br>Minute 15/30/45 Follow';
					$follow = new FollowController();
					$follow->index();
				
				}
				
			}
			
    	} else {
			
			if ($hour == 12) {
				
				if ($minute % 15 == 0) {
					echo '<br>Minute 15/30/45 Unfollow';
					$unfollow = new UnfollowController();
					$unfollow->index();
				
				}
				
			}
		
		}
		
// 		return false;


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
