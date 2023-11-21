<div id="edit-task-{{ $task_id }}"
	 @class([
		  'uk-flex-top uk-modal',
		  'uk-flex uk-open' => $errors->any()
	  ])
	 uk-modal
	 tabindex="-1"
>

	<div class="uk-modal-dialog uk-margin-auto-vertical">

		<button class="uk-modal-close-default uk-icon uk-close"
				type="button"
				uk-close
		></button>

		<div class="uk-modal-header">
			<h2 class="uk-modal-title uk-text-truncate">
				Edit {{ $task->name }}
			</h2>
		</div>

		<div class="uk-modal-body">
			<form class="uk-grid uk-grid-medium uk-child-width-1-1 uk-form-horizontal"
				  uk-grid
				  wire:submit.prevent="editTask"
			>
				<div>
					<x-label for="task_name"
							 value="Name of task"
					/>
					<x-input id="task_name"
							 wire:model.defer="task.name"
							 :hasError="$errors->has('task.name')"
					/>
					<x-form-error input="task.name" />
				</div>
				<!-- <div>
					<x-label for="unit_type"
							 value="Unit Type"
					/>
					<x-select id="unit_type"
							  :collection="$this->taskUnitTypes"
							  wire:model.defer="task.unit_type"
							  :hasError="$errors->has('task.unit_type')"
					/>
					<x-form-error input="task.unit_type" />
				</div> -->
				<div>
					<x-label for="target"
							 value="Target"
					/>
					<x-input id="target"
							  wire:model.defer="task.target"
							  :hasError="$errors->has('task.target')"
					/>
					<x-form-error input="task.target" />
				</div>

				<div>
					<button type="submit"
							class="uk-button uk-button-primary"
							wire:loading.attr="disabled"
							wire:target="editTask"
					>
						Edit Task
					</button>
				</div>


			</form>
		</div>


	</div>

</div>
