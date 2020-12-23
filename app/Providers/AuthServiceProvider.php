<?php

namespace App\Providers;

use App\DrivingSchool;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // https://spatie.be/docs/laravel-permission/v3/basic-usage/super-admin
        Gate::before(function (User $user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        Gate::define('edit driving school', function (User $user, DrivingSchool $drivingSchool) {
            return $user->hasPermissionTo('edit driving schools')
                || (int)$drivingSchool->author_id === (int)$user->id;
        });
    }
}
