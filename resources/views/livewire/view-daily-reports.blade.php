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

            <div class="uk-width-1-1">
                <x-label for="duration" value="Filter by range duration e.g today, last week, this month" />
                <x-select wire:model.defer="duration" id="duration" choose_text="Choose the range duration"
                    :hasError="$errors->has('duration')" :collection="$this->durations" :hasError="$errors->has('duration')" :disabled="$has_filter" />
                <x-form-error input="task" />
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
        $totalCompletedTasks = 0;
        $totalDuration = \Carbon\CarbonInterval::create(0, 0, 0, 0); // Initialize the totalDuration as a CarbonInterval;
        $totalUnitshr = 0;
        $aboveTarget = 0;
        $belowTarget = 0;
    @endphp
    <div class="uk-overflow-auto">
        <table class="uk-table uk-table-small uk-table-divider uk-table-middle uk-table-striped uk-table-responsive">
            <thead>
                <tr>
                    <th class="uk-width-small">User</th>
                    <th class="uk-width-small">Project</th>
                    <th class="uk-width-small">Task</th>
                    <th class="uk-table-shrink">Unit type</th>
                    <th class="uk-table-shrink">Completed Tasks</th>
                    <th class="uk-width-small">Duration</th>
                    <th class="uk-width-small">Units/hr</th>
                    <th class="uk-table-expand">Perfomance</th>
                    <th class="uk-table-shrink">Start</th>
                    <th class="uk-table-shrink">End</th>
                    <th class="uk-table-shrink">Date</th>
                    <th class="uk-table-expand">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                    @php
                        $totalCompletedTasks += $report->units_completed;
                        $totalUnitshr += $report->hourlyRate;
                        $totalDuration = $totalDuration->add($report->duration); // Add the durations together
                    @endphp
                    <tr>
                        <td>{{ ucfirst($report->user->fullname) }}</td>
                        <td class="uk-table-link">
                            <a
                                href="{{ route('projects.tasks.show', $report->project->slug) }}">{{ ucfirst($report->project->name) }}</a>
                        </td>
                        <td>{{ ucfirst($report->task->name) }}</td>
                        <td>
                            <span class="uk-label uk-label-{{ $report->task->unit_type->color() }}">
                                {{ $report->task->unit_type->name }}
                            </span>
                        </td>
                        <td>{{ $report->units_completed }} </td>
                        <td>{{ $report->duration->forHumans(['short' => true]) }}</td>
                        <td>
                            @php
                                $currentDate = new DateTime(); // Current date and time
                                $reportDate = $report->reported_at;

                            @endphp

                            @if ($reportDate > $currentDate)
                                <span class="uk-text-small">Pending: {{ $report->hourlyRate }} </span>
                            @else
                                {{ $report->hourlyRate }}
                            @endif

                        <td>


                            @php

                                // Get the individual target for this associate
                                $individualTarget = $report->task->target;

                                // Initialize variables
                                $percentageDifference = 0;
                                $performanceStatus = '';
                                $color = '';

                                // Check if $individualTarget is not zero
                                if ($individualTarget != 0 && $reportDate < $currentDate) {
                                    // Calculate the percentage difference from the target
                                    $percentageDifference = number_format((($report->hourlyRate - $individualTarget) / $individualTarget) * 100, 1);

                                    // Determine if performance is above or below the target
                                    if ($percentageDifference > 0) {
                                        $aboveTarget++;
                                        $performanceStatus = 'Perfomance: ' . $percentageDifference;
                                        $color = 'green';
                                    } elseif ($percentageDifference < 0) {
                                        $belowTarget++;
                                        $performanceStatus = 'Perfomance: ' . abs($percentageDifference);
                                        $color = 'red';
                                    } else {
                                        $performanceStatus = 'On Target';
                                        $color = 'blue';
                                    }
                                } elseif ($reportDate > $currentDate) {
                                    $performanceStatus = 'Pending';
                                    $color = 'orange';
                                } else {
                                    // Handle the case where $individualTarget is zero
                                    $performanceStatus = 'Target is Zero';
                                    $color = 'gray';
                            } @endphp

                            <span class="" style="color: {{ $color }}">
                                {{ $performanceStatus }}
                            </span><br>
                            <span class="uk-text-small">Target: {{ $report->task->target }}</span>
                        </td>
                        <td>{{ $report->started_at->format('H:i:s') }}</td>
                        <td>{{ $report->ended_at->format('H:i:s') }}</td>
                        <td>{{ $report->reported_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="uk-button-group">
                                <a href="{{ route('reports.edit', $report) }}"
                                    class="uk-button uk-button-secondary uk-margin-small-right">Edit</a>
                                <button
                                    onclick="confirm('Are you sure you want to delete this report?') || event.stopImmediatePropagation()"
                                    type="button" wire:click="delete({{ $report->id }})"
                                    class="uk-button uk-button-danger">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="10" class="uk-background-default">
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


            <tfoot>
                <tr>

                    <td style="font-weight: 800" colspan="3">Total Completed Tasks: {{ $totalCompletedTasks }}
                    </td>
                    <td style="font-weight: 800" colspan="3">Total Duration: {{ $totalDuration->forHumans(['short' => true])  }}
                    </td>
                    <td style="font-weight: 800" colspan="2">Total Units/hr: {{ $totalUnitshr }} </td>
                    @if (auth()->user()->isAdmin() ||
                            auth()->user()->isProjectManager())
                        <td style="font-weight: 800" colspan="2">On Target: {{ $aboveTarget }}
                        </td>
                        <td style="font-weight: 800" colspan="2">Below Target: {{ $belowTarget }}
                        </td>
                    @endif
                </tr>
                <tr>
                    <td colspan="8">
                        {{ $reports->links() }}
                    </td>
                </tr>
            </tfoot>


        </table>
    </div>
    <x-top-top />
</div>
