<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);// home officer account

//                if(Auth::user()->department_id == 1){ //Inspection
//                    if(Auth::user()->k_team == 0){
//                        return redirect(RouteServiceProvider::HOME);// home officer account
//                    }
//                    return redirect(RouteServiceProvider::HOME_COMPANY);//home company account
//                }
//                elseif(Auth::user()->department_id == 4){ //DOSH
//                    return redirect(RouteServiceProvider::HOME_DOSH);//home company account
//                }
//                elseif(Auth::user()->department_id == 6){// Dispute
//                    return redirect(RouteServiceProvider::HOME_DISPUTE);//home company account
//                }
//                elseif(Auth::user()->department_id == 7){ // Employment
//                    return redirect(RouteServiceProvider::HOME_EMPLOYMENT);//home company account
//                }

            }
        }

        return $next($request);
    }
}
