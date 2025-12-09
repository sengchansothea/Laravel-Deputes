<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class MLVTCookies
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // 1. Extract all cookies from header
        $header_cookie = [];
        $rawCookies = $request->server('HTTP_COOKIE');
        if ($rawCookies) {
            foreach (explode(';', $rawCookies) as $index => $value) {
                $pair = explode('=', trim($value));
                $key = $pair[0] ?? "{$index}_index_null";
                $val = $pair[1] ?? "{$index}_value_null";
                $header_cookie[$key] = $val;
            }
        }

        // 2. Store cookies in session
        session(['mlvt_sso_cookie' => collect($header_cookie)]);

        // 3. Retrieve the SSO login cookie
        $loginCookie = collect($header_cookie)->get('LOGIN_UNIQUE_COOKIE_UAT');

        // 4. Auto-logout if already logged in and cookie expired
        if (Auth::check()) {
            $user = Auth::user();
            if (
                $user->sso_cookie_expires_at &&
                Carbon::parse($user->sso_cookie_expires_at)->isPast()
            ) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect to SSO logout
                $ssoLogoutUrl = 'https://uat-accounts.mlvt.gov.kh/logout-sso';
                return redirect()->away($ssoLogoutUrl);
            }

            return $next($request); // session still valid
        }

        // 5. If not logged in but cookie exists, try logging in via SSO API
        if ($loginCookie) {
            $cacheKey = 'sso_' . $loginCookie;

            $ssoData = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($loginCookie) {
                $response = Http::withHeaders([
                    'CLIENT-ID' => config('services.sso.client_id') ?? '65e18962086749f2ee477386417ef28741cf79d2c5644ddb6f96cc28',
                    'CLIENT-SECRET-KEY' => config('services.sso.secret_key') ?? 'ff2c71c6956065a4f2766e73c2294082b2e52891243a2cb939a4bcd4e874fd6d',
                    'Content-Type' => 'application/json',
                ])->post('https://uat-accounts.mlvt.gov.kh/api/v1/api-request/account/account-login-by-cookie', [
                    'login_cookie_code' => $loginCookie,
                ]);

                if ($response->ok() && $response->json('code') === 200) {
                    return $response->json('data.result');
                }

                Log::warning('[SSO] Failed or invalid response for cookie: ' . $loginCookie);
                return null;
            });

            if ($ssoData && !empty($ssoData['user_login_info']['logged_email'])) {
                $email = $ssoData['user_login_info']['logged_email'];
                $expires = $ssoData['user_login_info']['cookie_code_expired'] ?? null;

                $user = User::where('email', $email)->first();

                if ($user) {
                    // Update user SSO fields
                    $user->sso_cookie_code = $loginCookie;
                    $user->sso_cookie_expires_at = $expires;
                    $user->save();
                    Auth::login($user);
                } else {
                    Log::warning('[SSO] Email not found in local DB: ' . $email);
                }
            }
        }
        return $next($request);
    }
}
