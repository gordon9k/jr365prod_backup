<?php

namespace jobready365\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {	
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }
        return $next($request);
        /*if (Auth::guard($guard)->guest()) {
    		if ($request->ajax() || $request->wantsJson()) {
    			return response('Unauthorized.', 401);
    		} else {
    			return redirect()->guest('login');
    		}
    	}    	
    	return $next($request);*/
    }
}