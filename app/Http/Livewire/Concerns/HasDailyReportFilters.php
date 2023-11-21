<?php

namespace App\Http\Livewire\Concerns;

use App\Models\Task;
use App\Models\Project;
use App\Models\DailyReport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait HasDailyReportFilters
{
    use HasUIkitPagination;
    use AuthorizesRequests;
    use WithProjectsAndItsTasks;

    public ?int $project = -1;
    public ?int $task = -1;


    public $project_manager;
    public ?string $date_from = null;
    public ?string $date_to;

    public ?string $duration = null;
    public ?string $perfomance;
    public bool $has_filter = false;

    protected ?LengthAwarePaginator $reports = null;

    public function mount()
    {
        $this->tasks = new Collection();
        $this->date_to = today()->toDateString();
        $this->perfomance = "all";

        if (auth()->user()->isProjectManager()) {
            $this->project_manager = auth()->user();
        }
    }

    public function getDurationsProperty()
    {
        return collect([
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'week' => 'This week',
            'month' => 'This month',
            'quarter' => 'This quarter',
            'year' => 'This year',
            'last_week' => 'Last week',
            'last_month' => 'Last month',
            'last year' => 'Last Year'
        ]);
    }

    public function getPerfomancesProperty()
    {
        return collect([
            'all' => 'All Perfomances',
            'on_target' => 'On Target',
            'above_target' => 'Above Target',
            'below_target' => 'Below Target',
            'pending' => 'Pending Perfomances',
        ]);
    }

    abstract public function clearFilters(): void;

    abstract public function filter(): void;

    public function repopulateReports()
    {
        $this->reset('date_from', 'task', 'project', 'has_filter');

        $this->reports = $this->populateReports()->paginate(20);
    }

    public function delete(DailyReport $report)
    {
        if (Gate::denies('manage-reports', $report)) {
            $this->dispatchBrowserEvent(
                event: 'access-denied',
                data: [
                    'message' => 'This action cannot be completed',
                ]
            );

            return;
        }

        $report->delete();

        $this->dispatchBrowserEvent(
            event: 'report-deleted',
            data: [
                'message' => 'Report has been deleted',
            ]
        );
    }


    protected function resolveReports()
    {
        if ($this->has_filter) {
            $builder  = $this->applyFilters();
        } else {
            $builder = $this->populateReports();
        }

        return $builder->paginate(20);
    }

    abstract protected function populateReports(): \Illuminate\Contracts\Database\Eloquent\Builder;

    protected function defaultRules(): array
    {
        return [
            'project' => Rule::when(
                filled($this->project) && $this->project !== -1,
                [
                    'required',
                    'numeric',
                    Rule::exists((new Project())->getTable(), 'id'),
                ]
            ),
            'task' => Rule::when(
                filled($this->task) && $this->task !== -1,
                [
                    'required',
                    'numeric',
                    Rule::exists((new Task())->getTable(), 'id'),
                ]
            ),
            'date_from' => Rule::when(
                filled($this->date_from),
                [
                    'required',
                    'date',
                ]
            ),
            'date_to' => Rule::when(
                filled($this->date_to),
                [
                    'required',
                    'date',
                ]
            ),
        ];
    }
}
