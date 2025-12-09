<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class SSOAuthService
{
    public function extractCookies(string $rawCookies): Collection
    {
        return collect(explode(';', $rawCookies))
            ->mapWithKeys(function ($cookie, $index) {
                $pair = explode('=', trim($cookie), 2);
                $key = $pair[0] ?? "{$index}_key";
                $val = $pair[1] ?? "{$index}_val";
                return [$key => $val];
            });
    }
    public function authenticateViaCookie(string $loginCookie): ?User
    {
        // ✅ Already logged in AND already authenticated via SSO (via session)
        if (auth()->user() && session('sso_authenticated')) {
            return auth()->user();
        }

        $cacheKey = 'sso_response_' . sha1($loginCookie);
        $ssoData = Cache::remember($cacheKey,
            now()->addHours(24),
            fn () => $this->fetchSSOData($loginCookie));

        if (!isset($ssoData['user_login_info']['logged_email'])) {
            Log::warning("[SSO] Missing logged_email from SSO response.");
            return null;
        }

        $email = $ssoData['user_login_info']['logged_email'];
        $expired = $ssoData['user_login_info']['cookie_code_expired'] ?? null;

        $user = User::where('email', $email)->where('banned', 0)->first();

        if (!$user) {
            Log::warning("[SSO] User not found in local DB for email: $email");
            return null;
        }

        // ✅ Cache cookie and expiration locally
        $this->cacheUserCookie($user->id, $loginCookie, $expired);

        // Log the user in
        Auth::login($user);
        session()->regenerate();

        // ✅ Mark SSO login as already processed
        session(['sso_authenticated' => true]);
        session()->save(); // force session to persist immediately

        // ✅ Log activity once per session (2 hours)
        if (!Cache::has("audit_logged_{$user->id}")) {
            AuditService::logActivity($user, 'login', $expired, 'User logged in via SSO');
            Cache::put("audit_logged_{$user->id}", true, now()->addHours(24));
        }
        return $user;
    }

    private function fetchSSOData(string $loginCookie): ?array
    {
        $apiUrl = rtrim(config('services.sso.base_url'), '/') . config('services.sso.login_cookie_api');
        $response = Http::withHeaders([
            'CLIENT-ID' => config('services.sso.client_id') ?? '65e18962086749f2ee477386417ef28741cf79d2c5644ddb6f96cc28',
            'CLIENT-SECRET-KEY' => config('services.sso.secret_key') ?? 'ff2c71c6956065a4f2766e73c2294082b2e52891243a2cb939a4bcd4e874fd6d',
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'login_cookie_code' => $loginCookie,
        ]);

        if ($response->ok() && $response->json('code') === 200) {
            return $response->json('data.result');
        }

        Log::warning("[SSO] Invalid cookie: $loginCookie");
        return null;
    }

    private function cacheUserCookie(int $userId, string $cookieValue, ?string $expiresAt): void
    {
        $expires = $expiresAt ? Carbon::parse($expiresAt) : now()->addMinutes(15);

        Cache::put("user_sso_cookie_{$userId}", [
            'cookie' => $cookieValue,
            'expires_at' => $expires->timestamp,
        ], $expires);
    }

    public function isCachedCookieInvalid(int $userId, string $currentCookie): bool
    {
        $cacheData = Cache::get("user_sso_cookie_{$userId}");

        // ❌ No cache found = invalid
        if (!$cacheData) {
            return true;
        }

        $storedCookie = $cacheData['cookie'] ?? null;
        $expiresAt = $cacheData['expires_at'] ?? null;

        // ❌ Cookie mismatch = invalid
        if ($storedCookie !== $currentCookie) {
            return true;
        }

        // ❌ Expired = invalid
        if ($expiresAt && Carbon::parse($expiresAt)->isPast()) {
            return true;
        }

        // ✅ Valid cookie
        return false;
    }

}
