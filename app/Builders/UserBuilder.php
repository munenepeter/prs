<?php

namespace App\Builders;

use App\Enums\Roles;
use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder
{
    public function admins(): static
    {
        return $this->whereHas(
            relation: 'roles',
            callback: fn (Builder $builder) => $builder->where(
                column: 'name',
                operator: '=',
                value: Roles::ADMIN
            )
        );
    }

    public function projectManagers(): static
    {
        return $this->whereHas(
            relation: 'roles',
            callback: fn (Builder $builder) => $builder->where(
                column: 'name',
                operator: '=',
                value: Roles::PROJECT_MANAGER
            )
        );
    }

    public function notAdminOrProjectManager(): static
    {
        return $this->whereDoesntHave(
            relation: 'roles',
            callback: fn (Builder $builder) => $builder->whereIn(
                column: 'name',
                values: [Roles::PROJECT_MANAGER, Roles::ADMIN]
            )
        );
    }
}
