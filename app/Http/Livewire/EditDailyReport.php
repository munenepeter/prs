<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;
use App\Models\Project;
use Carbon\CarbonInterval;
use App\Models\DailyReport;
use App\Enums\TaskUnitTypes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use App\Http\Livewire\Concerns\AddOrEditReport;
use App\Http\Livewire\Concerns\WithProjectsAndItsTasks;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditDailyReport extends Component
{
    use AddOrEditReport;
    use WithProjectsAndItsTasks;
    use AuthorizesRequests;

    public DailyReport $report;

    public bool $projectHasChanged = false;

    public bool $taskHasChanged = false;

    public function updatedReportProjectId(int $project_id)
    {
        $this->tasks = new Collection();

        data_set($this->report->task, 'id', -1);

        $this->validateOnly('report.project.id');

        $this->fetchTasks($project_id);

        $this->projectHasChanged = true;
    }

    public function updatedReportTaskId(int $task_id)
    {
        $this->getUnitType($task_id);
        $this->report->units_completed = 0;
    }

    public function mount()
    {
        $this->authorize('manage-reports', $this->report);
        $this->report->loadMissing('project', 'task');

        $this->getUnitType($this->report->task->id);

        $this->initializeTasks($this->report->project->id);
    }

    public function save()
    {
        if ($this->report->isClean() && $this->report->task->isClean() && $this->report->project->isClean()) {
            $this->report->perfomance = $this->report->calculatePerformance();
            $this->report->save();
            return to_route('reports.index', $this->report->user_id);
        }

        $this->validate();
        $this->report->perfomance = $this->report->calculatePerformance();
        $this->report->save();

        session()->flash('success', "You have successfully updated your report");

        return to_route('reports.index', $this->report->user_id);
    }

    public function render()
    {
        return view('livewire.edit-daily-report');
    }

    protected function rules(): array
    {
        return [
            'report.project.id' => [
                'required',
                'numeric',
                Rule::exists((new Project())->getTable(), 'id'),
            ],
            'report.task.id' => [
                'required',
                'numeric',
                Rule::exists((new Task())->getTable(), 'id'),
            ],
            'report.started_at' => [
                'required',
                'date_format:H:i:s'
            ],
            'report.units_completed' => [
                'required',
                'numeric',
                'min:1',
                Rule::when(
                    $this->unit_type === TaskUnitTypes::HOUR,
                    [
                        'between:1,1440',
                    ]
                ),
            ],
            'report.ended_at' => [
                'required',
                'date_format:H:i:s',
                function ($attribute, $value, $fail) {
                    $value =  CarbonInterval::createFromFormat('H:i:s', $value);
                    $started_at = CarbonInterval::createFromFormat('H:i:s', $this->report->started_at->toTimeString());
                    if ($value->lt($started_at)) {
                        $fail('The end time must be greater than start time');
                    }
                }
            ],
            'report.reported_at' => [
                'required',
                'date'
            ],
        ];
    }
}
