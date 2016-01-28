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

class ModelAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        if ($id != 'select' && 'search') {

            $socialMediaAccount = SocialMediaAccount::findOrFail($id);
            $modelAccounts = ModelAccount::where('social_media_account_id', $id)->get();

            $bladeVariables = compact('socialMediaAccount', 'modelAccounts');

            return view('set-user.search')->with($bladeVariables);

        } else {

            $socialMediaAccounts = SocialMediaAccount::where('user_id', Auth::user()->id)->get();

            $bladeVariables = compact('socialMediaAccounts');

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

        return view('set-user.show')->with($bladeVariables);
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
