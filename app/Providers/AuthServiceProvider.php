<?php

namespace App\Providers;

use App\Models\User;
use App\Enums\Roles;
use App\Models\DailyReport;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->getDefinedGates();
    }

    protected function getDefinedGates()
    {
        Gate::define(
            ability: 'view-reports',
            callback: fn (User $user) => $user->id === auth()->id() || $user->hasAnyRoles([Roles::PROJECT_MANAGER,
                    Roles::ADMIN])
        );

        Gate::define(
            ability: 'manage-reports',
            callback: fn (User $user, DailyReport $report) => $user->id === $report->user_id || $user->hasAnyRoles([Roles::PROJECT_MANAGER,
                    Roles::ADMIN])
        );

        Gate::define(
            ability: 'manage-projects',
            callback: fn (User $user) => $user->hasAnyRoles([Roles::PROJECT_MANAGER, Roles::ADMIN])
        );

        Gate::define(
            ability: 'is-admin',
            callback: fn (User $user) => $user->isAdmin()
        );
    }
}
