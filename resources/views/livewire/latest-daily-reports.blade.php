<div class="uk-width-1-1">
	<div class="uk-tile uk-tile-muted uk-margin">
		<form wire:submit.prevent="filter"
			  class="uk-form-stacked uk-grid uk-grid-medium uk-child-width-1-2@s uk-child-width-1-1"
			  uk-grid
		>
			<div class="uk-width-1-1">
				<x-label for="user"
						 value="User"
				/>
				<x-select wire:model.lazy="user"
						  id="user"
						  choose_text="Pick a user"
						  :hasError="$errors->has('user')"
						  :collection="$this->users"
						  :hasError="$errors->has('user')"
						  :disabled="$has_filter"

				/>
				<x-form-error input="project" />
			</div>
			<div>
				<x-label for="project"
						 value="Project"
				/>
				<x-select wire:model.lazy="project"
						  id="project"
						  choose_text="Choose the project"
						  :hasError="$errors->has('project')"
						  :collection="$this->projects"
						  :hasError="$errors->has('project')"
						  :disabled="$has_filter"

				/>
				<x-form-error input="project" />
			</div>
			<div>
				<x-label for="task"
						 value="Task"
				/>
				<x-select wire:model.defer="task"
						  id="task"
						  choose_text="Choose the task"
						  :hasError="$errors->has('status')"
						  :collection="$this->tasks"
						  :hasError="$errors->has('task')"
						  :disabled="$has_filter"
				/>
				<x-form-error input="task" />
			</div>
			<div class="uk-width-1-1@s uk-grid-margin uk-first-column">
				<div class="uk-grid uk-grid-medium uk-child-width-1-2"
					 uk-grid
				>
					<div>
						<x-label for="date_from"
								 value="Date From"
						/>
						<x-input type="date"
								 wire:model.defer="date_from"
								 id="date_from"
								 :hasError="$errors->has('date_from')"
								 :disabled="$has_filter"
						/>
						<x-form-error input="date_from" />

					</div>
					<div>
						<x-label for="date_to"
								 value="Date To"
						/>
						<x-input type="date"
								 wire:model.defer="date_to"
								 id="date_to"
								 :hasError="$errors->has('date_to')"
								 :disabled="$has_filter"
						/>
						<x-form-error input="date_to" />
					</div>
				</div>
			</div>

			<div class="uk-width-1-1">
				<x-label for="duration"
						 value="Filter by range duration e.g today, last week, this month"
				/>
				<x-select wire:model.defer="duration"
						  id="duration"
						  choose_text="Choose the range duration"
						  :hasError="$errors->has('duration')"
						  :collection="$this->durations"
						  :hasError="$errors->has('duration')"
						  :disabled="$has_filter"
				/>
				<x-form-error input="task" />
			</div>

			<div class="uk-width-1-1 uk-first-column uk-grid-margin">
				<button type="submit"
						wire:loading.attr="disabled"
						wire:target="filter"
						class="uk-button uk-button-primary uk-width-1-1"
					@disabled($has_filter)
				>
					Filter
				</button>

				<button type="button"
						wire:click="clearFilters"
						class="uk-button uk-button-secondary uk-width-1-1 uk-margin-top" @disabled(!$has_filter)>
					Reset Filter
				</button>

				@if($reports->isNotEmpty())
					<button type="button" wire:click.prevent="export"
							wire:loading.attr="disabled"
							wire:target="export"
							class="uk-button uk-button-secondary uk-width-1-1 uk-margin-top"
						@disabled($reports->isEmpty())
					>
						Export as Excel
					</button>
				@endif
			</div>
		</form>
	</div>

	<div class="uk-overflow-auto">
		<table class="uk-table uk-table-small uk-table-divider uk-table-middle uk-table-striped uk-table-responsive">
			<thead>
				<tr>
					<th class="uk-width-small">User</th>
					<th class="uk-width-small">Project Name</th>
					<th class="uk-width-small">Task Name</th>
					<th class="uk-table-shrink">Unit type</th>
					<th class="uk-table-shrink">Completed Tasks</th>
					<th class="uk-width-small">Duration</th>
					<th class="uk-width-small">Units completed Per Hour</th>
					<th class="uk-table-shrink">Start</th>
					<th class="uk-table-shrink">End</th>
					<th class="uk-table-shrink">Date</th>
					<th class="uk-table-expand">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($reports as $report)
					<tr>
						<td>{{ ucfirst($report->user->fullname) }}</td>
						<td class="uk-table-link">
							<a href="{{ route('projects.tasks.show', $report->project->slug) }}">{{ ucfirst
							($report->project->name) }}</a>
						</td>
						<td>{{ ucfirst($report->task->name) }}</td>
						<td>
							<span class="uk-label uk-label-{{  $report->task->unit_type->color() }}">
								{{ $report->task->unit_type->name }}
							</span>
						</td>
						<td>{{ $report->units_completed }}</td>
						<td>{{ $report->duration }}</td>
						<td>{{ $report->hourlyRate }}</td>
						<td>{{ $report->started_at->format('H:i:s') }}</td>
						<td>{{ $report->ended_at->format('H:i:s') }}</td>
						<td>{{ $report->reported_at->format('d/m/Y') }}</td>
						<td>
							<div class="uk-button-group"
							>
								<a href="{{ route('reports.edit', $report) }}"
								   class="uk-button uk-button-secondary uk-margin-small-right"
								>Edit</a>
								<button type="button"
										wire:click="delete({{ $report->id }})"
										class="uk-button uk-button-danger"
								>
									Delete
								</button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="10"
							class="uk-background-default"
						>
							<div class="uk-padding-small">
								<x-alert message="You dont have any reports at the moment"
										 type="danger"
								>
									<a href="{{ route('reports.create') }}"
									   class="uk-link uk-link-heading"
									>Create a new report?</a>
								</x-alert>
							</div>
						</td>
					</tr>
				@endforelse
			</tbody>
			<tfoot>
			
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
