<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
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
        Paginator::useBootstrapFive();

        // Every permission key in the registry doubles as a Gate ability, so
        // `@can('products')` and `$user->can('products')` route straight
        // through User::hasPermission() (admins always pass).
        foreach (Permission::keys() as $key) {
            Gate::define($key, fn (User $user) => $user->hasPermission($key));
        }
    }
}
