<?php

namespace App\Http\Middleware;

use Closure;

//Auth Facade
use Auth;

class AuthenticateInstitution
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        //If request does not comes from logged in seller
        //then he shall be redirected to Seller Login page
        if (! Auth::guard('web_institution')->check()) {
            return redirect('/institution_login');
        }

        return $next($request);
    }
}
