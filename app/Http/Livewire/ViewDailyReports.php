<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Lean\LivewireAccess\WithImplicitAccess;
use Lean\LivewireAccess\BlockFrontendAccess;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Http\Livewire\Concerns\HasDailyReportFilters;
use App\Http\Livewire\Concerns\WithExportDailyReport;

class ViewDailyReports extends Component
{
    use HasDailyReportFilters;
    use WithImplicitAccess;
    use WithExportDailyReport;


    #[BlockFrontendAccess]
    public User $user;

    public ?string $search = null;

    public function clearFilters(): void
    {
        $this->repopulateReports();
    }

    public function filter(): void
    {
        $validated = $this->validate();

        $this->resetPage();

        $this->has_filter = true;
    }


    public function render()
    {
        $title = ucwords($this->user->fullname)."'s Daily Reports";

        $this->reports = $this->resolveReports();

        return view('livewire.view-daily-reports', ['reports' => $this->reports])
            ->layout('layouts.app', ['title' => $title]);
    }

    protected function applyFilters(): Builder
    {
        return $this->user->reports()
            ->when(
                filled($this->project) && $this->project !== -1,
                fn (Builder $builder) => $builder->where('project_id', '=', $this->project)
            )
            ->when(
                filled($this->task) && $this->task !== -1,
                fn (Builder $builder) => $builder->where('task_id', '=', $this->project)
            )
            ->when(
                filled($this->date_from) && filled($this->date_to),
                fn (Builder $builder) => $builder->whereBetween(
                    'reported_at',
                    [$this->date_from, $this->date_to ?? today()->toDateString()]
                )
            )
            ->when(
                filled($this->duration) && $this->duration !== -1,
                function (Builder $builder) {
                    return  match ($this->duration) {
                        'today' => $builder->whereDate('reported_at', '=', now()),
                        'week' => $builder->whereRaw(
                            "reported_at between ? and ?",
                            [
                                now()->toImmutable()->startOfWeek(),
                                now()->toImmutable()->endOfWeek(),
                            ]
                        ),
                        'last_week' => $builder->whereRaw(
                            "reported_at between ? and ?",
                            [
                                now()->toImmutable()->subWeek()->startOfWeek(),
                                now()->toImmutable()->subWeek()->endOfWeek(),
                            ]
                        ),
                        'month' => $builder->whereRaw(
                            "reported_at between ? and ?",
                            [
                                now()->toImmutable()->startOfMonth(),
                                now()->toImmutable()->endOfMonth(),
                            ]
                        ),
                        'last_month' => $builder->whereRaw(
                            "reported_at between ? and ?",
                            [
                                now()->toImmutable()->subMonthNoOverflow()->startOfMonth(),
                                now()->toImmutable()->subMonthNoOverflow()->endOfMonth(),
                            ]
                        ),
                        'year' => $builder->whereRaw(
                            "reported_at between ? and ?",
                            [
                                now()->toImmutable()->startOfYear(),
                                now()->toImmutable()->endOfYear(),
                            ]
                        ),
                        'last year' => $builder->whereRaw(
                            "reported_at between ? and ?",
                            [
                                now()->toImmutable()->subYearNoOverflow()->startOfYear(),
                                now()->toImmutable()->subYearNoOverflow()->endOfYear(),
                            ]
                        ),
                        'quarter' => $builder->whereRaw(
                            "reported_at between ? and ?",
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
            ->orderBy('reported_at', 'DESC');
    }

    protected function populateReports(): Builder
    {
        return $this->user->reports()
            ->with([
                'project:id,name,slug',
                'task:id,name,unit_type,target',
            ])
            ->orderBy('reported_at', 'DESC');
    }

    protected function rules(): array
    {
        return $this->defaultRules();
    }
}
