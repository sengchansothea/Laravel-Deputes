<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use App\Rules\ReCaptchaV3;
use App\Services\AuditService;
use Biscolab\ReCaptcha\Facades\ReCaptcha;
use GuzzleHttp\Client;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request){

                //Logout Laravel Session Locally
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                //Redirect to SSO logout with return path back to your login
//                $logoutUrl = rtrim(config('services.sso.base_url'), '/') . config('services.sso.logout_path');
//                $redirectUri = urlencode(config('app.url') . 'login');
//                return redirect()->away("{$logoutUrl}?redirect_uri={$redirectUri}");
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function(){
            return view("auth.login"); // to login form login_v2

//            $redirectUri = urlencode(config('app.url') . 'mainboard');
//            $ssoLoginUrl = rtrim(config('services.sso.base_url'), '/') . '/login?redirect_uri=' . $redirectUri;
//            return redirect()->away($ssoLoginUrl);
        });


        Fortify::registerView(function(){
            if(! session('user'))
//                return view("auth.login");
                return view("auth.register");// to register form
        });

        // Add My Coding For: Login using username OR email
        Fortify::authenticateUsing(function (Request $request) {
//            dd($request->all());
            // Validate reCAPTCHA & Credentials
            $request->validate([
                'g-recaptcha-response' => ['required', new ReCaptchaV3],  // Validate reCAPTCHA
            ]);

            // Retrieve The Corresponding User
            $user = User::where('email', $request->username)
                ->orWhere('username', $request->username)
                ->where("banned", 0)
                ->first();
            //dd($user);
            //dd(Hash::make($request->password));

            //Authenticate User
            if ($user &&
                Hash::check($request->password, $user->password)) {
                //dd($user);
                return $user;
            }
        });


        //We pass this block code due to SSO Login Instead
//        Fortify::authenticateUsing(function (Request $request) {
////            $cookie = $request->cookie('login_cookie_code');
//            $loginCookie = session('mlvt_sso_cookie')->get('LOGIN_UNIQUE_COOKIE_UAT');
//
//            $response = Http::withHeaders([
//                'CLIENT-ID' => "",
//                'CLIENT-SECRET-KEY' => "",
//                'Content-Type' => 'application/json',
//            ])->post('https://uat-accounts.mlvt.gov.kh/api/v1/api-request/account/account-login-by-cookie', [
//                'login_cookie_code' => $loginCookie,
//            ]);
//
//            if ($response->failed() || !$response->json()) {
//                return null;
//            }
//
//            $data = $response->json('data.result');
//            $loggedEmail = $data['user_login_info']['logged_email'] ?? 'not found';
//
//            $user = User::where('email', $loggedEmail)->first();
//
//            if (!$user) {
//                Log::warning('SSO user not found in local DB: ' . $loggedEmail);
//                return null; // fail login
//            }
//            return $user; // Fortify logs them in automatically
//
//        });
    }
}
