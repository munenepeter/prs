<?php

namespace App\Http\Livewire\Concerns;

use App\Enums\Roles;

trait HasUserInserts
{
    public int|string $role = -1;

    public function getRolesProperty()
    {
        return Roles::pluck('name', 'value');
    }
}
