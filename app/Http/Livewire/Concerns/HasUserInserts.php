<?php

namespace App\Http\Livewire\Concerns;

use App\Enums\Roles;

trait HasUserInserts
{
    public int|string $role = -1;
    public string $gender = '';

    public function getRolesProperty()
    {
        return Roles::pluck('name', 'value');
    }

    public function getGendersProperty(): array
    {
        return [
            'Male' => 'Male',
            'Female' => 'Female'
        ];
    }
}
