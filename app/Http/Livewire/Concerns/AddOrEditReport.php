<?php

namespace App\Http\Livewire\Concerns;

use App\Models\Task;
use App\Enums\TaskUnitTypes;
use Illuminate\Support\Collection;
use Lean\LivewireAccess\BlockFrontendAccess;

trait AddOrEditReport
{
    use WithProjectsAndItsTasks;

    public int $project = -1;

    public int $task = -1;

    #[BlockFrontendAccess]
    public TaskUnitTypes $unit_type;

    public int $units_completed = 0;

    public string $started_at = '';

    public string $ended_at = '';

    public string $reported_at = '';

    public function initializeData(bool $edit_mode = false, string $report = 'report')
    {
        if (!$edit_mode) {
            $this->reported_at = today()->toDateString();
        }
    }

    public function initializeTasks(?int $project_id = null)
    {
        if (blank($project_id) && $this->project === -1) {
            $this->tasks = new Collection();
        } else {
            $this->tasks = Task::query()
                ->where('project_id', '=', $project_id)
                ->pluck('name', 'id');
        }
    }

    public function updatedTask(int $task_id)
    {
        if ($task_id < 0) {
            return;
        }

        $this->getUnitType($task_id);
    }

    abstract public function save();

    protected function getUnitType(int $task_id)
    {
        $this->unit_type = Task::query()->where('id', '=', $task_id)
            ->value('unit_type');

        // Set the default value to 420 when unit_type is HOUR
        if ($this->unit_type === TaskUnitTypes::HOUR) {
            $this->units_completed = 420;
        } else {
            $this->units_completed = 0;
        }

    }

}
