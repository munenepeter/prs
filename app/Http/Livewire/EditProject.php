<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Task;
use App\Models\Client;
use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Arr;
use App\Enums\ProjectStatuses;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Http\Livewire\Concerns\HasInserts;

class EditProject extends Component
{
    use HasInserts;

    public bool $isTasksCollectionChanged = false;

    public Project $project;

    public function mount()
    {
        $this->project->loadMissing('tasks', 'client', 'manager');
    }

    public function addTask(): void
    {
        if ($this->project->tasks->contains(key: 'name', operator: '=', value: $this->task)) {
            $this->dispatchBrowserEvent(
                event: 'task-exists',
                data: ['message' => 'This task already exists.']
            );

            return;
        }

        $this->validateOnly('task');

        $this->project->tasks()->create([
            'name' => $this->task,
            'target' => $this->target
        ]);

        $this->isTasksCollectionChanged = true;

        $this->project->refresh();

        $this->reset('task');
        $this->reset('target');
    }

    public function removeTask(int $task): void
    {
        if (!$this->project->tasks->contains($task)) {
            $this->taskDoesntExistBrowserEvent();
            return;
        }

        $this->project->tasks->find($task)->delete();

        $this->isTasksCollectionChanged = true;

        $this->project->refresh();

        $this->taskRemovedBrowserEvent();
    }

    public function edit()
    {
        if (!$this->project->isDirty() && !$this->isTasksCollectionChanged &&
            $this->project->manager->isClean() && $this->project->client->isClean() && !$this->create_new_client
        ) {
            // no change was made so redirect.
            return to_route('projects.index');
        }

        $validated = $this->create_new_client
            ? $this->validate(rules: Arr::except(array: $this->rules(), keys: ['project.client.id', 'task']))
            : $this->validate(rules: Arr::except(array: $this->rules(), keys: ['new_client', 'task']));

        $this->resolveClient($validated);

        $this->resolveManager($validated);

        $this->project->push();
        //        Project::query()->where('id', $this->project->id)
        //            ->update(Arr::except($validated['project'], ['client', 'manager']) +
        //                [
        //                    'client_id' => $this->project->client->id,
        //                    'user_id' => $this->project->manager->id,
        //                ]);


        session()->flash('success', "{$this->project->name} has been updated successfully");

        return to_route('projects.index');
    }

    protected function rules(): array
    {
        return [
            'project.name' => Rule::when(
                condition: $this->project->isDirty('name'),
                rules: [
                    'required', 'string', 'min:3',
                    Rule::unique(table: (new Project())->getTable(), column: 'name'),
                ],
            ),
            'project.description' => Rule::when(
                condition: $this->project->isDirty('description'),
                rules: [
                    'sometimes',
                    'nullable',
                    'min:6',
                ],
            ),
            'project.status' => Rule::when(
                condition: $this->project->isDirty('status'),
                rules: [
                    'required',
                    new Enum(ProjectStatuses::class),
                ],
            ),
            'project.manager.id' => Rule::when(
                condition: $this->project->manager->isDirty(),
                rules:  [
                    'required',
                    'numeric',
                    Rule::exists(table: (new User())->getTable(), column: 'id')
                        ->using(
                            callback: fn () => User::query()->projectManagers()
                        ),
                ]
            ),
            'project.client.id' => Rule::when(
                condition: !$this->create_new_client || !$this->project->client->isClean(),
                rules: [
                    'required',
                    'numeric',
                    Rule::exists(table: (new Client())->getTable(), column: 'id'),
                ]
            ),
            'new_client' => Rule::when(
                condition: $this->create_new_client,
                rules: [
                    'required',
                    'string',
                    'min:3',
                    Rule::unique(table: (new Client())->getTable(), column: 'name'),
                ]
            ),
            'task' => Rule::when(
                condition: $this->create_tasks,
                rules: [
                    'required',
                    'string',
                    'min:3',
                    Rule::unique(table: (new Task())->getTable(), column: 'name'),
                ]
            ),
        ];
    }

    protected function resolveClient(array $validated)
    {
        if ($this->project->client->isClean() && !array_key_exists('new_client', $validated)) {
            return;
        }

        $new_client = Arr::exists($validated, 'new_client') ?
            Client::query()->create(['name' => $validated['new_client']]) :
            Client::find(data_get($validated, 'project.client.id'));


        $this->project->client()->dissociate();

        unset($this->project->client);

        $this->project->client()->associate($new_client);
    }

    protected function resolveManager(array $validated)
    {
        if ($this->project->manager->isClean()) {
            return;
        }

        $this->project->manager()->disassociate();

        unset($this->project->manager);

        $this->project->manager()->associate(
            User::find(data_get($validated, 'project.manager.id'))
        );
    }

    public function render()
    {
        return view('livewire.edit-project')->layout('layouts.app', ['title' => "Edit Project"]);
    }
}
