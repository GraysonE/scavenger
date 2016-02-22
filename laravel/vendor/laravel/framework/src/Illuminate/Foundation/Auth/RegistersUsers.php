<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
//  	    dd($request->all());
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
 		
 		$userView = $request->get('userView');
 		
 		if ($userView){
	 		$this->create($request->all());
	 		return redirect('/users');
 		} else {
	 		Auth::login($this->create($request->all()));
	 		return redirect($this->redirectPath());
 		}
 		
         

        
    }
}
