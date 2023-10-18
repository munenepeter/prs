<div class="uk-width-1-1">
    <h1>Create Daily Report</h1>

    <form wire:submit.prevent="save" class="uk-grid uk-grid-medium uk-child-width-1-1 uk-form-horizontal" uk-grid>
        <div class="uk-grid-margin uk-first-column">
            <x-label for="project" value="Project" />
            <x-select id="project" wire:model.lazy="project" choose_text="Choose a project" :collection="$this->projects"
                :hasErrors="$errors->has('project')" />
            <x-form-error input="project" />
        </div>
        <div class="uk-grid-margin uk-first-column">
            <x-label for="task" value="Task" />
            <x-select id="task" wire:model.lazy="task" :disabled="$project === -1" choose_text="Choose a task"
                :collection="$this->tasks" :hasErrors="$errors->has('task')" />
            <x-form-error input="task" />
        </div>
        <div class="uk-grid-margin uk-first-column">
            <x-label for="unit_type" value="Unit Type" />
            <x-input id="unit_type" disabled :value="$unit_type?->name" />
        </div>
        @if ($unit_type?->name === 'HOUR')
            <div class="uk-grid-margin uk-first-column">
                <x-label for="units_completed" value="Units Completed" />
                <x-input id="units_completed" type="number" min="1" max="8000" disabled :value="$units_completed" />
                <x-form-error input="units_completed" />
            </div>
        @else
        
            <div class="uk-grid-margin uk-first-column">
                <x-label for="units_completed" value="Units Completed" />
                <x-input id="units_completed" type="number" min="1" max="8000"
                    wire:model.defer="units_completed" />
                <x-form-error input="units_completed" />
            </div>
        @endif


        <div class="uk-width-1-2@m uk-grid-margin">
            <x-label for="started_at" value="Start Time" />
            <x-input id="started_at" type="time" step="1" wire:model.defer="started_at" />
            <x-form-error input="started_at" />
        </div>
        <div class="uk-width-1-2@m">
            <x-label for="ended_at" value="End Time" />
            <x-input id="ended_at" type="time" step="1" wire:model.defer="ended_at" />
            <x-form-error input="ended_at" />
        </div>
        <div>
            <x-label for="reported_at" value="Date" />
            <x-input id="reported_at" type="date" min="{{ $project_as_model?->created_at?->toDateString() }}"
                wire:model.defer="reported_at" />
            <x-form-error input="reported_at" />
        </div>
        <div class="uk-grid-margin uk-first-column">
            <button type="submit" class="uk-button uk-button-primary uk-width-1-1" wire:loading.attr="disabled"
                wire:target="save">
                Create Project
            </button>
        </div>
    </form>

</div>
