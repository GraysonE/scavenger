<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\User;
use Scavenger\ModelAccount;
use Auth;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    
	    $currentUser = Auth::user();
	    
	    $modelAccount = ModelAccount::where('social_media_account_id', 3)
                ->where('api_cursor', '!=', 0)
                ->where('sort_order', 1)
                ->get()
                ->first();
                
        echo (int)$modelAccount->api_cursor;
        
        if ((int)$modelAccount->api_cursor === 2147483647) {
	        echo 'true';
        }
	    
//         return view('settings.index', compact('currentUser')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    
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
	    $type = $_GET['type'];
	    
	    if ($type == 'social') {
		    $socialMedia = GlobalSocialMedia::where('id', $id);
			$socialMedia->take(1)->delete();
	    } elseif ($type == 'cta') {
		    $cta = GlobalCta::where('id', $id);
			$cta->take(1)->delete();
	    }
        
        
        return redirect("settings");
    }
    
    
    public function duplicate($id)
    {
	    
	    $type = $_GET['type'];
	    
	    if ($type == 'social') {
		    
		    $socialMedia = GlobalSocialMedia::findOrFail($id)->toArray();
		    $socialMedia['tag'] = 'custom';
		    GlobalSocialMedia::create($socialMedia);
		    
	    } elseif ($type == 'cta') {
		    
		    $cta = GlobalCta::findOrFail($id)->toArray();
		    GlobalCta::create($cta);
			
	    }
	    
	    
	    return redirect("settings");

    }
}
