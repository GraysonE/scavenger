<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Carbon\Carbon;
use Scavenger\Helpers\Helper;
use Mailgun\Mailgun;


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

        if (($hour <= 11) || ($hour >= 13)) {

            return redirect('automate');

        } else {
	        
	        if ($weekOfMonth % 2 != 0) { // if week is 1st or 3rd of month, follow

            	return redirect('follow');

        	} else {

            	return redirect('unfollow');
			
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
        $message = "This is a test message";
        Helper::email_admin($message, 'GraysonErhard');		
/*
		# Instantiate the client.
		$mgClient = new Mailgun('key-953a2849b0d5b0a27836662e060eaf47');
		$domain = "sandboxfecd9afae20348628e71034295465d25.mailgun.org";
		
		# Make the call to the client.
		$result = $mgClient->sendMessage("$domain",
      array('from'    => 'Mailgun Sandbox <postmaster@sandboxfecd9afae20348628e71034295465d25.mailgun.org>',
            'to'      => 'Grayson Erhard <web@graysonerhard.com>',
            'subject' => 'Hello Grayson Erhard',
            'text'    => 'Congratulations Grayson Erhard, you just sent an email with Mailgun!  You are truly awesome!  You can see a record of this email in your logs: https://mailgun.com/cp/log .  You can send up to 300 emails/day from this sandbox server.  Next, you should add your own domain so you can send 10,000 emails/month for free.'));
		    
        
*/
        
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
