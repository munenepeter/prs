<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;
use App\Models\Project;
use Carbon\CarbonInterval;
use App\Models\DailyReport;
use Illuminate\Validation\Rule;
use App\Http\Livewire\Concerns\AddOrEditReport;

class CreateDailyReport extends Component
{
    use AddOrEditReport;

    public function mount()
    {
        $this->initializeTasks();
    }

    public function save()
    {
        $validated = $this->validate();

        $report =  new DailyReport([
            'units_completed' => $validated['units_completed'],
            'started_at' => $validated['started_at'],
            'ended_at' => $validated['ended_at'],
            'reported_at' => $validated['reported_at']
        ]);

        $report->user()->associate(auth()->user());
        $report->project()->associate(Project::find($validated['project']));
        $report->task()->associate(Task::find($validated['task']));

        $report->perfomance = $report->calculatePerformance();

        $report->save();

        session()->flash('success', 'Report successfully created');

        return to_route('reports.index', $report->user_id);
    }

    public function render()
    {
        return view('livewire.create-daily-report');
    }

    protected function rules(): array
    {
        return [
            'project' => [
                'required',
                'numeric',
                Rule::exists((new Project())->getTable(), 'id'),
            ],
            'task' => [
                'required',
                'numeric',
                Rule::exists((new Task())->getTable(), 'id'),
            ],
            'started_at' => [
                'required',
                'date_format:H:i:s'
            ],
            'units_completed' => [
                'required',
                'numeric',
                'min:1',

            ],
            'ended_at' => [
                'required',
                'date_format:H:i:s',
                function ($attribute, $value, $fail) {
                    $value =  CarbonInterval::createFromFormat('H:i:s', $value);
                    $started_at = CarbonInterval::createFromFormat('H:i:s', $this->started_at);
                    if ($value->lt($started_at)) {
                        $fail('The end time must be greater than start time');
                    }
                }
            ],
            'reported_at' => [
                'required',
                'date',
                'after_or_equal:' .  $this->project_as_model?->created_at,
            ],
        ];
    }
}
