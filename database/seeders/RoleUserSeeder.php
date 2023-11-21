<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Roles;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::query()->inRandomOrder()->take(random_int(3, 5))->get();
        $roles = Role::query()->get();

        $randomUsers = $users->random(3);

        $randomUsers->each(
            fn (User $user) => $user->roles()->attach(
                id: $roles->firstWhere(
                    'name',
                    Roles::USER
                )
            )
        );

        $users->except($randomUsers->pluck('id')->toArray())->each(
            callback: fn (User $user) => $user->roles()->attach(
                id: $roles->firstWhere(
                    'name',
                    Roles::PROJECT_MANAGER
                )
            )
        );
    }
}
