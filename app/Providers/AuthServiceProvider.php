<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            // Check if user is admin and grant all permissions
            if ($user && $user->roles && method_exists($user->roles, 'isNotEmpty') && $user->roles->isNotEmpty()) {
                if ($user->hasRole('admin')) {
                    return true;
                }
            }
            
            return null; // Fall back to policy rules
        });
    }
}
