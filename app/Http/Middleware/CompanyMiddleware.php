<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //dd(Auth::user()->k_category);//get data from field from table user
        //dd(Auth::user()->isOfficer()); //get data from function in model user
//        if( ! Auth::user()->isCompany() ){
//            //if not officer redirect to company mainboard
//            //return response()->json('You do not have access!!');
//            return redirect("/noaccess");
//            //return redirect('mainboard1');
//        }
        return $next($request);
    }
}
