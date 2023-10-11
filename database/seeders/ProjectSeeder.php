<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::factory()
            ->count(7)
            ->sequence(
                fn (Sequence $sequence) => [
                    'user_id' => User::query()->projectManagers()->inRandomOrder()->first('id'),
                    'client_id' => Client::query()->inRandomOrder()->first('id'),
                ]
            )
            ->has(
                factory: Task::factory()->count(random_int(3, 15)),
                relationship: 'tasks'
            )
            ->create();
    }
}
