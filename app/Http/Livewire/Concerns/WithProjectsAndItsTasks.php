<?php

namespace App\Http\Livewire\Concerns;

use App\Models\Task;
use App\Models\Project;
use App\Enums\ProjectStatuses;
use Illuminate\Support\Collection;
use Lean\LivewireAccess\WithImplicitAccess;
use Lean\LivewireAccess\BlockFrontendAccess;

trait WithProjectsAndItsTasks
{
    use WithImplicitAccess;

    public $project_as_model;

    #[BlockFrontendAccess]
    public Collection $tasks;

    public function getProjectsProperty()
    {
        return Project::query()
            ->where('status', '=', ProjectStatuses::LIVE)
            ->pluck(column: 'name', key: 'id')->sort();
    }

    public function updatedProject(int $project_id)
    {
        $this->reset('task');

        $this->validateOnly('project');

        $this->project_as_model = Project::find($project_id);

        $this->fetchTasks($project_id);
    }

    public function fetchTasks(int $project_id)
    {
        $this->tasks = Task::query()
            ->where('project_id', '=', $project_id)
            ->pluck('name', 'id');
    }
}
