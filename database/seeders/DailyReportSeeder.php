<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\DailyReport;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DailyReport::factory()
            ->count(10)
            ->sequence(function (Sequence $sequence) {
                $project = Project::query()->inRandomOrder()->with('tasks')->first();
                return [
                    'project_id' => $project->id,
                    'user_id' => User::query()->inRandomOrder()->notAdminOrProjectManager()->first(),
                    'task_id' => $project->tasks->random()->id,
                ];
            })
            ->create();
    }
}
