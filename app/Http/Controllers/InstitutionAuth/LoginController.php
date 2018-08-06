<?php

namespace App\Http\Controllers\InstitutionAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Class needed for login and Logout logic
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//Auth facade
use Auth;

class LoginController extends Controller
{	
	//Where to redirect seller after login.
    protected $redirectTo = '/institution/home';

    //Trait
    use AuthenticatesUsers;


    //Custom guard for seller
    protected function guard()
    {
    	return Auth::guard('web_institution');
    }

    //Shows seller login form
   	public function showLoginForm()
   	{
    	return view('institution.auth.login');
   	}
}
