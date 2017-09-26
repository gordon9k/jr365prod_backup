<?php

namespace jobready365\Http\Middleware;

use Closure;

class ConfirmationMiddleware
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
        if (auth()->check()) {
            if ( ! $request->user()->isActive()) {
                return redirect()->route('register');
            }
        }
        return $next($request);
    }
}
