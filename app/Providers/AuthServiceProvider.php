<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function (?User $user): bool|null {
            if ($user && $user->hasRole('SUPER_ADMIN')) {
                return true;
            }

            return null;
        });

        Gate::define('permission', fn (User $user, string $permission) => $user->canDo($permission));
    }
}
