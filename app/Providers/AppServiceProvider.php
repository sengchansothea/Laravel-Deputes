<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        Paginator::useBootstrap();
        /** MY Code Below: 04-08-2023 */
        Gate::define('isOfficerUser', function($user) {
            return $user->k_team == 0 || $user->k_team == 1;
        });
        Gate::define('isCompanyUser', function($user) {
            return $user->k_team == 1;
        });

        Gate::define('isDeveloperUser', function($user) {
            return $user->k_role == 0;
        });

        Gate::define('isSuperUser', function($user) {
            return $user->k_role == 0;
        });

        Gate::define('isInspectorUser', function($user) {
            return $user->k_category == 0 || ($user->k_category == 1 && $user->k_province== 18 && $user->k_parents == 4);
        });
    }
}
