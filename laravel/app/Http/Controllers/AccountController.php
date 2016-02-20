<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\SocialMediaAccount;
use Scavenger\User;
use Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

		if (Auth::user()->id == 1) {
			$socialMediaAccounts = SocialMediaAccount::orderBy('account_type', "DESC")->get();
		} else {
			$socialMediaAccounts = SocialMediaAccount::where('user_id', Auth::user()->id)->get();
		}
		
		$currentAccount = User::where('id', Auth::user()->id)->get()->first()->username;
		
        $bladeVariables = compact('socialMediaAccounts', 'currentAccount');
        return view('accounts.index')->with($bladeVariables);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();

        $this->validate($request, [
            'password' => 'min:6',
            'consumer_key' => 'required|min:25',
            'consumer_secret' => 'required|min:50',
            'access_token' => 'required|min:50',
            'access_token_secret' => 'required|min:45'
        ]);


        SocialMediaAccount::create([
            'user_id' => Auth::user()->id,
            'account_id' => 0,
//             'account_type' => strtolower($data['account_type']),
			'account_type' => 'twitter',
            'screen_name' => $data['screen_name'],
            'account_password' => $data['account_password'],
            'consumer_key' => $data['consumer_key'],
            'consumer_secret' => $data['consumer_secret'],
            'access_token' => $data['access_token'],
            'access_token_secret' => $data['access_token_secret'],
            'auto_follow' => 1,
            'auto_unfollow' => 1,
            'auto_whitelist' => 1,

        ]);

        return redirect('accounts');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $data)
    {
        // Social Media REPEATER
        $keys = array_keys($data->all());

        $k=0;
        $socialMedia_ids = array();
        foreach ($keys as $key) {

            if (preg_match('/^screen_name/', $key)) {
                $keyExploded = explode('-', $key);
                $socialMedia_ids[$k] = $keyExploded[1];

                $k++;

            }

        }

        foreach($socialMedia_ids as $socialMedia_id) {

            $socialMediaUpdate = SocialMediaAccount::findOrFail($socialMedia_id);

            $this->validate($data, [
//                 "account_type-$socialMedia_id" => 'required',
                "screen_name-$socialMedia_id" => 'required'
            ]);

//             $socialMediaUpdate->account_type = $data["account_type-$socialMedia_id"];
            $socialMediaUpdate->screen_name = $data["screen_name-$socialMedia_id"];
//             $socialMediaUpdate->account_id = $data["account_id-$socialMedia_id"];
            $socialMediaUpdate->auto_follow = $data["auto_follow-$socialMedia_id"];
            $socialMediaUpdate->auto_unfollow = $data["auto_unfollow-$socialMedia_id"];
            $socialMediaUpdate->auto_whitelist = $data["auto_whitelist-$socialMedia_id"];
//             $socialMediaUpdate->user_id = Auth::user()->id;
            $socialMediaUpdate->save();
        }

        return redirect('accounts');
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
        $socialMediaAccount = SocialMediaAccount::where('id', $id);

        $socialMediaAccount->take(1)->delete();

        return redirect("accounts");
    }
}
