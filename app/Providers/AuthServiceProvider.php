<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Annonce;
use App\Policies\AnnoncePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Annonce::class => AnnoncePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    
        // Define gates for different roles
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });
    
        Gate::define('is-recruteur', function (User $user) {
            return $user->role === 'recruteur';
        });
    
        Gate::define('is-candidat', function (User $user) {
            return $user->role === 'candidat';
        });
    }
}
