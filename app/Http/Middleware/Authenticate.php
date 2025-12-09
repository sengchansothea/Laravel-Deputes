<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
//        return $request->expectsJson() ? null : route('login');

        if ($request->is('api/*')) {
            return route('unauthorized');
        }
        if (! $request->expectsJson()) {
            return route('login');
        }else{
            return null;
        }
    }

    // Add new method
//    protected function unauthenticated($request, array $guards){
//        abort(response()->json([
//            'status' => 403,
//            'message' => 'Invalid Token',], 403));
//    }


}
