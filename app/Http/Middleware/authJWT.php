<?php

namespace jobready365\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Auth;

class authJWT
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::toUser($request->input('token'));           
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error'=>'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error'=>'Token is Expired']);
            }else{
                return response()->json(['error'=>'Something is wrong','err'=>$e]);
            }
        }
        return $next($request);
    }

    /*public function handle($request, Closure $next)
    {
        if($request->has('token')){
            $this->auth = JWTAuth::parseToken()->authenticate();
            return $next($request);
        }
        else{
            //if (Auth::guest()) { 
            if (Auth::guard($guard)->guest()) {
                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect()->guest('auth/login');
                }
            }
            return $next($request);
         }
    }*/
}