<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class AuthenticateAdmin
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
        if (! Auth::guard('admin_guard')->check()) {
           return redirect('/admin_login');
       }

        return $next($request);
    }
}
