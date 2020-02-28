<?php

namespace App\Http\Middleware;

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

            $user = Auth::user();
            
            if($user->role == 0){
                return redirect('/admin/dashboard');
            }elseif($user->role == 1){
                return redirect('/employee/dashboard');
            }elseif($user->role == 2){
                return redirect('/seller/dashboard');
            }elseif($user->role == 3){
                return redirect('/user/dashboard');
            }
            
        }

        return $next($request);
    }
}
