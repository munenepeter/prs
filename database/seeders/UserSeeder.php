<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Enums\Roles;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->admin();
        $this->projectManger();

        User::factory()->count(5)
            ->hasAttached(
                Role::where('name', '=', Roles::USER)->get()
            )
            ->create();
    }

    protected function admin()
    {
        $admin = User::create([
            'firstname' => 'admin',
            'lastname' => '',
            'email' => 'admin@prs.com',
            'gender' => 'Male',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
            'password_changed_at' => now(),
        ]);


        $admin->roles()->attach(Role::where('name', '=', Roles::ADMIN)->get());
    }

    protected function projectManger()
    {
        $project_manager = User::create([
            'firstname' => 'project manager',
            'lastname' => '',
            'email' => 'projectmanager@prs.com',
            'gender' => 'Male',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
            'password_changed_at' => now(),
        ]);

        $project_manager->roles()->attach(Role::where('name', '=', Roles::PROJECT_MANAGER)->get());
    }
}
