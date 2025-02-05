<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
                if(Auth::user()){
            if(Auth::user()->hasRole('admin')){
                return redirect("admin/home");
            }
            else if(Auth::user()->hasRole("user")){
                return redirect("user/home");
            }
        }


        return $next($request);
    }
}
