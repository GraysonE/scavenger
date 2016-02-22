<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Auth;
use Scavenger\User;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $users = User::all();
	    
// 	    dd($users);
	    $editMovie = false;
	    
	    $userView = true;
	    
	    $currentUser = Auth::user();
	    	$lastInitial = substr($currentUser->last_name, 0, 1);
	    	        
	    $bladeVariables = compact('currentUser', 'lastInitial', 'editMovie', 'users', 'userView');
	    
        return view('users.index', $bladeVariables); 
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
//         dd($request->all());
        
        
        $allFields = $request->all();
        
        
			// Social Media REPEATER
			$keys = array_keys($allFields);
	
			$k=0;
			foreach ($keys as $key) {
				
				if (preg_match('/^username/', $key)) {
					$keyExploded = explode('-', $key);
					$user_ids[$k] = $keyExploded[1];
					
					$k++;
					
				}
				
			}
			
			foreach($user_ids as $user_id) {
				
				
		        $user = User::findOrFail($user_id);
		        
		        $this->validate($request, [
		            "username-$user_id" => 'required|max:255|min:3',
		            "email-$user_id" => 'required|email|min:6',
		            "first_name-$user_id" => 'required|min:2',
		            "last_name-$user_id" => 'required|min:2',
		            "role-$user_id" => 'required|integer'
		        ]);
		        
		        
		        $user->username = $allFields["username-$user_id"];
		        $user->first_name = $allFields["first_name-$user_id"];
		        $user->last_name = $allFields["last_name-$user_id"];
		        $user->email = $allFields["email-$user_id"];
		        $user->role = $allFields["role-$user_id"];
		        
		        $user->save();  
			}
			
        
        return redirect('/users/');
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
        echo 'users';
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
    public function destroy($user_id) 
    {
	    
	    $user = User::where('id', $user_id);
        
//         dd($user);
        
        $user->take(1)->delete();
        
        return redirect("/users");
	    
    }
}
