<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use App\Services\SSOAuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MLVTCookies
{
    public function __construct(protected SSOAuthService $sso) {}

    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // 🚨 Skip SSO check for the unauthorized page
        if (url()->current() === url('/unauthorized')) {
            return $next($request);
        }

        //Extract raw cookies and store in session
        $cookieCollection = $this->sso->extractCookies($request->server('HTTP_COOKIE', ''));

        //Retrieve the SSO login cookie
        $loginCookie = $cookieCollection->get('LOGIN_UNIQUE_COOKIE_UAT');

        // ✅ Case 1: User is logged in — validate SSO cookie
        if (Auth::check()) {
            $user = Auth::user();
            if ($this->sso->isCachedCookieInvalid($user->id, $loginCookie)) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                return $this->redirectToSSOLogout($user);
            }
            return $next($request);
        }

        // ✅ Case 2: Not logged in but has valid cookie — try auto-login via SSO API
        if ($loginCookie && !Auth::check()) {
//            $this->sso->authenticateViaCookie($loginCookie);
            $user = $this->sso->authenticateViaCookie($loginCookie);
            if (!$user) {
                return redirect()->route('unauthorized');
            }

        }
        return $next($request);
    }
    private function redirectToSSOLogout($user): Response
    {
        AuditService::logActivity($user, 'logout', null , 'SSO cookie mismatch or expired');

        $logoutBase = rtrim(config('services.sso.base_url'), '/');
        $logoutPath = config('services.sso.logout_path', '/logout-sso');
        $redirectTo = rtrim(config('app.url'), '/') . '/login';

        $url = $logoutBase . $logoutPath . '?redirect_uri=' . urlencode($redirectTo);

        return redirect()->away($url);
    }


}
