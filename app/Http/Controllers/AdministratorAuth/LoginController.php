<?php

namespace App\Http\Controllers\AdministratorAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Class needed for login and Logout logic
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//Auth facade
use Auth;

class LoginController extends Controller
{
    //Trait
    use AuthenticatesUsers;

    //Where to redirect seller after login.
    protected $redirectTo = '/admin';

    //Custom guard for Adminstrator
    protected function guard()
    {
      return Auth::guard('admin_guard');
    }

    //Shows admin login form
   public function showLoginForm()
   {
       return view('admin.auth.login');
   }
}
