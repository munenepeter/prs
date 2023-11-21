<div class="uk-width-1-1">
    <div class="uk-tile uk-tile-muted uk-margin">
        <form wire:submit.prevent="filter"
            class="uk-form-stacked uk-grid uk-grid-medium uk-child-width-1-2@s
			  uk-child-width-1-1" uk-grid>
            <div>
                <x-label for="project" value="Project" />
                <x-select wire:model.lazy="project" id="project" choose_text="Choose the project" :hasError="$errors->has('project')"
                    :collection="$this->projects" :hasError="$errors->has('project')" :disabled="$has_filter" />
                <x-form-error input="project" />
            </div>
            <div>
                <x-label for="task" value="Task" />
                <x-select wire:model.defer="task" id="task" choose_text="Choose the task" :hasError="$errors->has('status')"
                    :collection="$this->tasks" :hasError="$errors->has('task')" :disabled="$has_filter" />
                <x-form-error input="task" />
            </div>
            <div class="uk-width-1-1@s uk-grid-margin uk-first-column">
                <div class="uk-grid uk-grid-medium uk-child-width-1-2" uk-grid>
                    <div>
                        <x-label for="date_from" value="Date From" />
                        <x-input type="date" wire:model.defer="date_from" id="date_from" :hasError="$errors->has('date_from')"
                            :disabled="$has_filter" />
                        <x-form-error input="date_from" />

                    </div>
                    <div>
                        <x-label for="date_to" value="Date To" />
                        <x-input type="date" wire:model.defer="date_to" id="date_to" :hasError="$errors->has('date_to')"
                            :disabled="$has_filter" />
                        <x-form-error input="date_to" />
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1@s uk-grid-margin uk-first-column">
                <div class="uk-grid uk-grid-medium uk-child-width-1-2" uk-grid>
                    <div>
                    <x-label for="duration" value="Filter by range duration e.g today" />
                   <x-select wire:model.defer="duration" id="duration" choose_text="Choose the range duration"
                    :hasError="$errors->has('duration')" :collection="$this->durations" :hasError="$errors->has('duration')" :disabled="$has_filter" />
                   <x-form-error input="duration" />

                    </div>
                    <div>
                    <x-label for="perfomance" value="Filter by perfomance e.g Above target" />
                    <x-select wire:model.defer="perfomance" id="perfomance" choose_text="Choose the perfomance"
                    :hasError="$errors->has('perfomance')" :collection="$this->perfomances" :hasError="$errors->has('perfomance')" :disabled="$has_filter" />
                    <x-form-error input="perfomance" />
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1 uk-first-column uk-grid-margin">
                <button type="submit" wire:loading.attr="disabled" wire:target="filter"
                    class="uk-button uk-button-primary uk-width-1-1" @disabled($has_filter)>
                    Filter
                </button>

                <button type="button" wire:click="clearFilters"
                    class="uk-button uk-button-secondary uk-width-1-1 uk-margin-top" @disabled(!$has_filter)>
                    Reset Filter
                </button>

                @if ($reports->isNotEmpty())
                    {{-- <button type="button" wire:click.prevent="exportExcel" wire:loading.attr="disabled"
                        wire:target="exportExcel" class="uk-button uk-button-secondary uk-width-1-1 uk-margin-top"
                        @disabled($reports->isEmpty())>
                        Export as Excel
                    </button> --}}

                    <button type="button" wire:click.prevent="exportPdf" wire:loading.attr="disabled"
                        wire:target="exportPdf" class="uk-button uk-button-danger uk-width-1-1 uk-margin-top"
                        @disabled($reports->isEmpty())>
                        Export as PDF
                    </button>
                @endif
            </div>
        </form>
    </div>

    @php
        $totalUnits = 0;
        $totalDuration = \Carbon\CarbonInterval::create(0, 0, 0, 0); // Initialize the totalDuration as a CarbonInterval;
        $totalUnitshr = 0;

        $totalProjects = $reports->pluck('project.name')->unique()->count();;
        $totalTasks = $reports->pluck('task.name')->unique()->count();

        $aboveTarget = $reports->filter(fn($report) => str_contains($report->perfomance, 'Above Target'))->count();
        $belowTarget = $reports->filter(fn($report) => str_contains($report->perfomance, 'Below Target'))->count();
        $onTarget = $reports->filter(fn($report) => str_contains($report->perfomance, 'On Target'))->count();
        $pendingTarget = $reports->filter(fn($report) => str_contains($report->perfomance, 'pending'))->count();

    @endphp
    <div class="uk-overflow-auto">
    <table style="font-size:14px;"
            class="uk-table uk-table-small uk-table-divider uk-table-middle uk-table-striped uk-table-responsive">
            <thead>
                <tr>
                    <th class="uk-width-small">Project</th>
                    <th class="uk-width-small">Task</th>
                    <th class="uk-width-expand">Duration</th>
                    <th class="uk-table-expand">Perfomance</th>
                    <th class="uk-table-expand">Time</th>
                    <th class="uk-table-shrink">Date</th>
                    <th class="uk-table-expand">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                    @php
                        $totalUnits += $report->task->unit_type->name === 'HOUR' ? 0 : (int) $report->units_completed;
                        $totalUnitshr += $report->hourlyRate;
                        $totalDuration = $totalDuration->add($report->duration); // Add the durations together
                    @endphp
                    <tr>
                        <td class="uk-table-link">
                            <a
                                href="{{ route('projects.tasks.show', $report->project->slug) }}">{{ ucfirst($report->project->name) }}</a>
                        </td>
                        <td>{{ ucfirst($report->task->name) }}</td>
                
                        <td style="font-size:12px;">
                            @if ($report->task->unit_type->name === 'HOUR')
                                <span>{{ number_format($report->duration->totalMinutes, 2) }}
                                    mins</span>
                            @else
                               <span> {{ $report->duration->forHumans(['short' => true]) }}</span>
                            @endif

                        </td>
                        <td style="font-size:12px;"> 

                            <span style="color: {{ $report->perfomanceColor }}">
                                {{ $report->perfomance }}
                            </span>
                            <br>
                            <span class="uk-text-small">Target: {{ $report->task->target }}
                                <span> {{ $report->formattedTarget }} </span></span>
                        </td>


                        <td>Start: {{ $report->started_at->format('H:i:s') }} <br> End:  {{ $report->ended_at->format('H:i:s') }}</td>
                        <td>{{ $report->reported_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="uk-button-group">
                                <a href="{{ route('reports.edit', $report) }}"
                                    class="uk-button uk-button-secondary uk-margin-small-right uk-button-small">Edit</a>
                                <button
                                    onclick="confirm('Are you sure you want to delete this report?') || event.stopImmediatePropagation()"
                                    type="button" wire:click="delete({{ $report->id }})"
                                    class="uk-button uk-button-danger uk-button-small">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="uk-background-default">
                            <div class="uk-padding-small">
                                <x-alert message="You dont have any reports at the moment" type="danger">
                                    <a href="{{ route('reports.create') }}" class="uk-link uk-link-heading">Create a
                                        new report?</a>
                                </x-alert>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>


            <tfoot style="font-size:12px;">
                <tr style="font-weight: 800">
                    <td>Total:{{ $totalProjects <= 0 ? 'N/A' : $totalProjects }} </td>
                    <td>Total:{{ $totalTasks <= 0 ? 'N/A' : $totalTasks }}</td>
                    <td>Total:{{ $totalDuration->forHumans(['short' => true]) }}</td>                       
                    <td>Above Target: {{ $aboveTarget ?? 'N/A' }} <br> Below Target: {{ $belowTarget ?? 'N/A' }} <br> On Target {{$onTarget}}  <br> Pending: {{$pendingTarget}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="7">
                        {{ $reports->links() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <x-top-top />
</div>
