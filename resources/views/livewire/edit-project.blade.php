<div>
    <h1 class="uk-text-bold uk-text-capitalize">Edit {{ $project->name }}</h1>
    <hr class="uk-divider-small">

    <form wire:submit.prevent="edit" class="uk-grid uk-grid-medium uk-child-width-1-1 uk-form-horizontal" uk-grid>
        <div>
            <x-label for="project_name" value="Define the project name" />
            <x-input type="text" id="project_name" wire:model.defer="project.name" placeholder="Project name"
                :hasError="$errors->has('project.name')" />
            <x-form-error input="project.name" />
        </div>
        <div class="uk-grid-margin uk-first-column">
            <x-label for="project_status" value="Define the project status" />

            <x-select wire:model.defer="project.status" id="project_status" choose_option="Choose the project status"
                :hasError="$errors->has('project.status')" :collection="$this->projectStatuses" />
            <x-form-error input="project.status" />
        </div>
        <div class="uk-grid-margin uk-first-column">
            <x-label for="project_description" value="Tell us a bit about the project (optional)" />
            <x-textarea wire:model.defer="project.description" id="project_description"
                placeholder="Provide some brief information on the project." :hasError="$errors->has('project.description')" />
            <x-form-error input="project.description" />
        </div>
        @if (!$create_new_client)
            <div class="uk-grid-margin uk-first-column">
                <x-label for="client" value="Assign the client who owns the project" />
                <x-select wire:model.defer="project.client.id" id="client" choose_option="Choose a client"
                    :hasError="$errors->has('project.client.id')" :collection="$this->clients" />
                <x-form-error input="project.client.id" />
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
                <x-form-error input="new_client" />
            </div>
        @endif
        <div class="uk-grid-margin uk-first-column">
            <x-label for="project_manager" value="Assign a project manager" />
            <x-select wire:model.defer="project.manager.id" id="project_manager"
                choose_option="Choose a project manager" :hasError="$errors->has('project.manager.id')" :collection="$this->projectManagers" />
            <x-form-error input="project.manager.id" />
        </div>

        @if ($project->tasks->isNotEmpty())
            <div class="uk-grid-margin uk-tile uk-tile-small uk-padding-small-horizontal">
                <h2 class="uk-h4">Project Tasks</h2>
                <ul class="uk-list uk-list-striped">
                    <li class="uk-flex uk-flex-middle uk-flex-between">
                        <span># &nbsp;Task Name</span>
                        <span>Task Target</span>
                        <span>Remove Task</span>
                    </li>
                    @foreach ($project->tasks as $task)
                        <li class="uk-flex uk-flex-middle uk-flex-between">
                            <span> {{ $loop->iteration }}. &nbsp; {{ $task->name }}</span>
                            <span>{{ $task->target }}</span>
                            <button type="button" wire:click="removeTask({{ $task->id }})"
                                class="remove-employee uk-icon uk-close uk-svg uk-margin-small-left"
                                uk-close="ratio: 0.8"></button>
                        </li>
                    @endforeach
                </ul>

            </div>

        @endif

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
                <p class="uk-text-lead">Add a new task to {{ $project->name }} project</p>

                <div>
                    <x-label for="project_manager" value="Task Name" />
                    <div class="uk-form-controls uk-grid uk-flex-middle uk-grid-small" uk-grid>
                        <div class="uk-width-expand">
                            <input type="text" wire:model.defer="task" id="task" @class(['uk-input', 'uk-form-danger' => $errors->has('task')])
                                placeholder="Name of task">
                        </div>
                        <x-label for="project_manager" value="Task Target" />
                        <div class="uk-width-expand">
                            <input type="number" wire:model.defer="target" id="target" @class(['uk-input', 'uk-form-danger' => $errors->has('target')])
                                placeholder="Target of the task">
                        </div>
                        <div class="uk-width-auto">
                            <button type="button" wire:click="addTask" class="uk-button uk-button-primary">Add Task
                            </button>
                        </div>
                        <div class="uk-width-1-1">
                            <x-form-error input="task" />
                        </div>
                    </div>
                </div>

            </div>
        @endif

        <div class="uk-grid-margin uk-first-column">
            <button type="submit" class="uk-button uk-button-primary uk-width-1-1">
                Save Project
            </button>
        </div>
    </form>

    <x-project-insert-scripts />

</div>
