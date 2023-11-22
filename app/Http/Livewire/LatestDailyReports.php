<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\DailyReport;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Livewire\Concerns\HasDailyReportFilters;
use App\Http\Livewire\Concerns\WithExportDailyReport;

class LatestDailyReports extends Component {
    use HasDailyReportFilters;
    use WithExportDailyReport;

    public int $user = -1;


    public function clearFilters(): void {
        $this->repopulateReports();
        $this->reset('user');
    }

    public function filter(): void {
        $this->validate();
        $this->resetPage();

        $this->has_filter = true;
    }

    public function getUsersProperty() {
        return User::query()
            ->notAdminOrProjectManager()
            ->get()->pluck('fullname', 'id');
    }

    public function render() {
        $this->reports = $this->resolveReports();

        return view('livewire.latest-daily-reports', ['reports' => $this->reports]);
    }

    protected function applyFilters() {
        return  DailyReport::query()
            ->when(
                filled($this->user) && $this->user !== -1,
                fn (Builder $builder) => $builder->where('user_id', '=', $this->user)
            )
            ->when(
                filled($this->project) && $this->project !== -1,
                fn (Builder $builder) => $builder->where('project_id', '=', $this->project)
            )
            ->when(
                filled($this->task) && $this->task !== -1,
                fn (Builder $builder) => $builder->where('task_id', '=', $this->project)
            )
            ->when(
                filled($this->duration) && $this->duration !== -1,
                function (Builder $builder) {
                    return  match ($this->duration) {
                        'today' => $builder->whereDate('reported_at', '=', now()),
                        'yesterday' => $builder->whereDate('reported_at', '=', now()->subDay()),
                        'week' => $builder->whereRaw(
                            "DATE(reported_at) between DATE(?) and DATE(?)",
                            [
                                now()->toImmutable()->startOfWeek(),
                                now()->toImmutable()->endOfWeek(),
                            ]
                        ),
                        'last_week' => $builder->whereRaw(
                            "DATE(reported_at) between DATE(?) and DATE(?)",
                            [
                                now()->toImmutable()->subWeek()->startOfWeek(),
                                now()->toImmutable()->subWeek()->endOfWeek(),
                            ]
                        ),
                        'month' => $builder->whereRaw(
                            "DATE(reported_at) between DATE(?) and DATE(?)",
                            [
                                now()->toImmutable()->startOfMonth(),
                                now()->toImmutable()->endOfMonth(),
                            ]
                        ),
                        'last_month' => $builder->whereRaw(
                            "DATE(reported_at) between DATE(?) and DATE(?)",
                            [
                                now()->toImmutable()->subMonthNoOverflow()->startOfMonth(),
                                now()->toImmutable()->subMonthNoOverflow()->endOfMonth(),
                            ]
                        ),
                        'year' => $builder->whereRaw(
                            "DATE(reported_at) between DATE(?) and DATE(?)",
                            [
                                now()->toImmutable()->startOfYear(),
                                now()->toImmutable()->endOfYear(),
                            ]
                        ),
                        'last year' => $builder->whereRaw(
                            "DATE(reported_at) between DATE(?) and DATE(?)",
                            [
                                now()->toImmutable()->subYearNoOverflow()->startOfYear(),
                                now()->toImmutable()->subYearNoOverflow()->endOfYear(),
                            ]
                        ),
                        'quarter' => $builder->whereRaw(
                            "DATE(reported_at) between DATE(?) and DATE(?)",
                            [
                                now()->toImmutable()->startOfQuarter(),
                                now()->toImmutable()->endOfQuarter(),
                            ]
                        ),
                        default => $builder
                    };
                }
            )
            ->when(
                filled($this->date_from) && filled($this->date_to),
                fn (Builder $builder) => $builder->whereBetween(
                    'reported_at',
                    [$this->date_from, $this->date_to ?? today()->toDateString()]
                )
            )
            ->when(
                filled($this->perfomance),
                function (Builder $builder) {
                    return match ($this->perfomance) {
                        'all' => $builder,
                        'on_target' => $builder->where('perfomance', 'On Target'),
                        'above_target' => $builder->where('perfomance', 'like', 'Above Target%'),
                        'below_target' => $builder->where('perfomance', 'like', 'Below Target%'),
                        'pending' => $builder->where('perfomance', 'Pending')
                    };
                }
            )
            ->with([
                'project:id,name,slug',
                'task:id,name,target,unit_type',
                'user:id,firstname,lastname'
            ])
            ->orderBy('reported_at', 'DESC');
    }

    protected function populateReports(): Builder {
        return DailyReport::query()
            ->select([
                'id', 'project_id', 'user_id', 'task_id', 'units_completed', 'perfomance',
                'started_at', 'ended_at', 'reported_at'
            ])
            ->when(
                filled($this->project_manager),
                fn (Builder $builder) => $builder->whereHas(
                    'project',
                    fn (Builder $query) => $query->where('user_id', '=', $this->project_manager->id)
                )
            )
            ->with([
                'project:id,name,slug',
                'task:id,name,target,unit_type',
                'user:id,firstname,lastname'
            ])
            ->orderBy('reported_at', 'DESC');
    }

    protected function rules(): array {
        return array_merge(
            $this->defaultRules(),
            [
                'user' => Rule::when(
                    filled($this->user) && $this->user !== -1,
                    [
                        'required',
                        'numeric',
                        Rule::exists((new User())->getTable(), 'id'),
                    ]
                ),
            ]
        );
    }
}
