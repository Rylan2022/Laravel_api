<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthMiddle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
         $token = session('jwt_token');

        if(!$token) {
            return redirect()->route('login')->with('error','Please login first');
        }

        try {
            JWTAuth::setToken($token)->authenticate();
        } catch (\Exception $e) {
            session()->forget('jwt_token');
            return redirect()->route('login')->with('error','Session expired');
        }

        return $next($request);
    }
}
