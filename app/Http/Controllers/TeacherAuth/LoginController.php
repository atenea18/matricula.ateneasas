<?php

namespace App\Http\Controllers\TeacherAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Class needed for login and Logout logic
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;

class LoginController extends Controller
{
     //Trait
    use AuthenticatesUsers;

    //Where to redirect seller after login.
    protected $redirectTo = '/teacher';

    //Custom guard for seller
    protected function guard()
    {
      return Auth::guard('teachers');
    }

    //Shows seller login form
    public function showLoginForm()
    {
       return view('teacher.auth.login');
   	}

    public function login(Request $request)
    {

        // Validate form data
        $this->validateLogin($request);

        // Attempt to authenticate user
        // If successful, redirect to their intended location
        if ( $this->attempLogin($request) ) {
            return $this->sendLoginResponse($request);
        }
        return redirect()->route('teacher.login')->withInput( $request->only('username', 'remember') );
    }

    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required',
            'password'  => 'required'
        ],[
            'username.required' =>  'El usuario es requerido',
            'password.required' =>  'La cotraseña es requerida',
        ]);
    }

    public function attempLogin(Request $request)
    {
    	// dd($request->all());
        return $this->guard()->attempt(['username' => $request->username, 'password' => $request->password]);   
    }
}
