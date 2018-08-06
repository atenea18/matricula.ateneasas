<?php

namespace App\Http\Middleware;

use Closure;

//Auth Facade
use Auth;

class RedirectIfAdminAuthenticated
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
        if (Auth::guard()->check()) {
          return redirect('/home');
        }

      //If request comes from logged in seller, he will
      //be redirected to seller's home page.
      if (Auth::guard('admin_guard')->check()) {
          return redirect('/admin');
      }

        return $next($request);
    }
}
