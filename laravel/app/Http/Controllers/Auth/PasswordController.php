<?php

namespace Scavenger\Http\Controllers\Auth;

use Scavenger\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\Passwords\PasswordBroker;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
  public function __construct(Guard $auth, PasswordBroker $passwords)
  {
    $this->auth = $auth;
    $this->passwords = $passwords;
    $this->subject = 'Your Password Reset Link'; //  < --JUST ADD THIS LINE
    $this->middleware('guest');
  }

}
