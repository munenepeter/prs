<div class="uk-width-1-1 uk-padding-medium-horizontal">
    <h1 class="uk-text-bold">Create a new project</h1>
    <hr class="uk-divider-small">
    <x-statuses />
    <form wire:submit.prevent="save" class="uk-grid uk-grid-medium uk-child-width-1-1 uk-form-horizontal" uk-grid>
        <div>
            <x-label for="project_name" value="Define the project name" />
            <x-input type="text" id="project_name" wire:model.defer="name" placeholder="Create a name for the project"
                :hasError="$errors->has('name')" />
            <x-form-error input="name" />
        </div>
        <div class="uk-grid-margin uk-first-column">
            <x-label for="project_status" value="Define the project status" />

            <x-select wire:model.defer="status" id="project_status" choose_option="Choose the project status"
                :hasError="$errors->has('status')" :collection="$this->projectStatuses" />
            <x-form-error input="status" />
        </div>
        <div class="uk-grid-margin uk-first-column">
            <x-label for="project_description" value="Tell us a bit about the project (optional)" />
            <x-textarea wire:model.defer="description" id="project_description"
                placeholder="Provide some brief information on the project." :hasError="$errors->has('description')" />
            <x-form-error input="description" />
        </div>
        @if (!$create_new_client)
            <div class="uk-grid-margin uk-first-column">
                <x-label for="client" value="Assign the client who owns the project" />
                <x-select wire:model.defer="client" id="client" choose_option="Choose a client" :hasError="$errors->has('client')"
                    :collection="$this->clients" />
                <x-form-error input="client" />
            </div>
        @endif
        <div class="uk-grid-margin uk-first-column">
            <div class="uk-grid uk-grid-small uk-grid-stack" uk-grid>
                <div class="uk-width-auto uk-first-column">
                    <label class="switch" for="create_new_client">
                        <input wire:model.lazy="create_new_client" type="checkbox" id="create_new_client">
                        <div class="switch-slider switch-on-off switch-big"></div>
                    </label>
                </div>
                <div class="uk-width-expand">
                    <small class="uk-text-small">Create new client</small>
                </div>
            </div>
        </div>

        @if ($create_new_client)
            <div class="uk-grid-margin uk-first-column">
                <x-label for="new_client" value="Client name" />
                <x-input type="text" id="new_client" wire:model.defer="new_client"
                    placeholder="Input the client name" :hasError="$errors->has('new_client')" />
            </div>
        @endif
        <div class="uk-grid-margin uk-first-column">
            <x-label for="project_manager" value="Assign a project manager" />
            <x-select wire:model.defer="project_manager" id="project_manager" choose_option="Choose a project manager"
                :hasError="$errors->has('project_manager')" :collection="$this->projectManagers" />
            <x-form-error input="project_manager" />
        </div>

        <div class="uk-grid-margin uk-first-column">
            <div class="uk-grid uk-grid-small uk-grid-stack" uk-grid>
                <div class="uk-width-auto uk-first-column">
                    <label class="switch" for="add_new_tasks">
                        <input wire:model.lazy="create_tasks" type="checkbox" id="add_new_tasks">
                        <div class="switch-slider switch-on-off switch-big"></div>
                    </label>
                </div>
                <div class="uk-width-expand">
                    <small class="uk-text-small">Assign tasks along with the project</small>
                </div>
            </div>
        </div>

        @if ($create_tasks)
            <div class="uk-grid-margin">
                <p class="uk-text-lead">Add a new task to {{ $name }} project</p>

                <div>
                    <x-label for="task_name" value="Name of task" />
                    <x-input id="task_name" wire:model.defer="task" :hasError="$errors->has('task')" />
                    <x-form-error input="task" />
                </div>
                <br>
                <input type="hidden" wire:model="unit_type" value="1">
                <!-- <div>
                    <x-label for="unit_type" value="Unit Type" />
                    <x-select id="unit_type" :collection="$this->taskUnitTypes" wire:model="unit_type" :hasError="$errors->has('unit_type')" />
                    <x-form-error input="unit_type" />
                </div> -->
                <br>
                <div>

                    @if ((int) $unit_type === 1)
                        <x-label for="target" value="Target (in minutes)" />
                    @elseif((int) $unit_type >= 1)
                        <x-label for="target" value="Target (in {{ ((int)$unit_type  == 2) ? 'pages' : 'characters' }} in per hr)" />
                    @else
                        <x-label for="target" value="Target" />
                    @endif

                    <x-input id="target" wire:model.defer="target" :hasError="$errors->has('target')" />
                    <x-form-error input="target" />
                </div>
                <br>
                <div>
                    <button type="button" wire:click="addTask" class="uk-button uk-button-primary">Add Task
                    </button>
                </div>

            </div>

            @if ($tasks->isNotEmpty())
                <div class="uk-grid-margin">
                    <div class="uk-placeholder uk-padding uk-grid uk-grid-row-medium uk-grid-column-small uk-flex-center uk-flex-middle uk-child-width-auto"
                        uk-grid>
                        @foreach ($tasks as $index => $task)
                            <div @class(['uk-first-column' => $loop->first])>
                                <div
                                    class="uk-background-secondary uk-text-capitalize uk-text-white task-preview uk-border-rounded uk-flex uk-flex-middle">
                                    {{ $task['name'] }}
                                    <button type="button" wire:click="removeTask('{{ $index }}')"
                                        class="remove-task uk-icon uk-close uk-svg uk-margin-small-left"
                                        uk-close="ratio: 0.8"></button>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif

        <div class="uk-grid-margin uk-first-column">
            <button type="submit" class="uk-button uk-button-primary uk-width-1-1" wire:loading.attr="disabled"
                wire:target="save">
                Create Project
            </button>
        </div>
    </form>

    <x-project-insert-scripts />
</div>
