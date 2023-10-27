<div class="uk-width-1-1">
	<div class="uk-overflow-auto">
		<table class="uk-table uk-table-small uk-table-divider uk-table-middle uk-table-striped uk-table-responsive">
			<thead>
				<tr>
					<th>Project Name</th>
					<th class="uk-table-shrink">Status</th>
					<th class="uk-table-shrink">Client</th>
					<th class="uk-width-small">Project manager</th>
					<th class="uk-table-shrink">Tasks Count</th>
					<th class="uk-table-shrink">Created</th>
					<th class="uk-table-expand uk-text-center@m">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($projects as $project)
					<tr>
						<td class="uk-text-capitalize">{{ $project->name }}</td>
						<td class="uk-text-capitalize uk-table-shrink">
							<span class="uk-label uk-label-{{ $project->status->color() }}">{{ $project->status->name
							}}</span>
						</td>
						<td class="uk-text-capitalize uk-table-shrink">{{ $project->client->name }}</td>
						<td class="uk-text-capitalize uk-table-shrink">{{ $project->manager->fullname }}</td>
						<td class="uk-text-capitalize uk-table-shrink">{{ $project->tasks_count }}</td>
						<td class="uk-table-shrink">{{ $project->created_at->diffForHumans() }}</td>
						<td class="uk-table-expand">
							<div class="uk-button-group"
							>
								{{--								@can('manage-projects')--}}

								<a href="{{ route('projects.tasks.show', $project->slug)  }}"
								   class="uk-button uk-button-primary  uk-margin-small-right"
								>
									Tasks
								</a>


								<a href="{{ route('projects.edit', $project->slug) }}"
								   class="uk-button uk-button-secondary uk-margin-small-right"
								>
									Edit
								</a>
 {{--
								<button type="button" onclick="confirm('Are you sure you want to delete this project?') || event.stopImmediatePropagation()"
										wire:click="deleteProject({{ $project->id }})"
										class="uk-button uk-button-danger"
								>
									Delete
								</button>
 --}}
								{{--								@endcan--}}
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="7">
							<x-alert message="There are no projects at the moment">
								<a href="{{ route('projects.create') }}"
								   class="uk-button uk-button-text"
								>
									Create a new project
								</a>
							</x-alert>

						</td>
					</tr>
				@endforelse
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7">
						{{ $projects->links() }}
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<a href="#main"
	   class="uk-hidden"
	   uk-scroll="offest: -10"
	></a>

	@push ('scripts')
		<script>
			window.addEventListener('pageUpdated', () => {
				setTimeout(() => {
					UIkit.scroll(document.querySelector("[uk-scroll]"))
					     .scrollTo(document.querySelector('#main'))
				}, 1000);
			});


		</script>
	@endpush
</div>
