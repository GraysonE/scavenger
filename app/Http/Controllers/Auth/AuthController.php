<?php

namespace Scavenger\Http\Controllers\Auth;

use Scavenger\User;
use Scavenger\SocialMediaAccount;
use Validator;
use Scavenger\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	private $username = 'username';
	
	
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//         $this->middleware('guest', ['except' => 'getLogout']);
        
    }
    
    
    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|min:3|unique:users,username',
            'email' => 'required|email|min:6|unique:users,email',
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'password' => 'required|confirmed|min:6',

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'role' => $data['role'],
            'password' => bcrypt($data['password'])

        ]);


        return $user;

    }
    
    private $redirectTo = 'accounts';
    
   
    
}
