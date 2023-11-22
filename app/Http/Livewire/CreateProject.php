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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use App\Http\Livewire\Concerns\HasInserts;
use Lean\LivewireAccess\WithImplicitAccess;
use Lean\LivewireAccess\BlockFrontendAccess;

class CreateProject extends Component {
    use WithImplicitAccess;
    use HasInserts;

    public string $name = '';

    public int $status = 0;

    public ?string $description = null;

    public int $project_manager = 0;

    public int $client = 0;

    #[BlockFrontendAccess]
    public ?Collection $tasks = null;

    public function mount() {
        $this->status = ProjectStatuses::LIVE->value;
    }

    public function render() {
        return view('livewire.create-project')->layout('layouts.app', ['title' => 'Create A New Project']);
    }

    public function updatedCreateNewClient(bool $value): void {
        !$value ? $this->reset('new_client')
            : $this->reset('client');
    }

    public function updatedCreateTasks(bool $value): void {
        if (!$value) {
            $this->reset('tasks');
            $this->reset('target');
            $this->reset('unit_type');
            return;
        }

        $this->tasks = new Collection();
    }

    public function addTask(): void {
        if ($this->tasks?->contains('name', $this->task)) {
            $this->taskExistsBrowserEvent();
            return;
        }

        $this->validateOnly('task');

        $this->tasks?->add([
            'name' => $this->task,
            'unit_type' => 1,
            'target' => $this->target
        ]);

        $this->reset('task');
        $this->reset('target');
        $this->reset('unit_type');
    }

    public function removeTask(int $task): void {
        if (!$this->tasks?->has($task)) {
            $this->taskDoesntExistBrowserEvent();
            return;
        }

        $this->tasks?->forget($task);

        $this->taskRemovedBrowserEvent();
    }

    public function save() {
        $validated = ($this->create_new_client)
            ? $this->validate(rules: Arr::except($this->rules(), keys: ['task', 'client']))
            : $this->validate(rules: Arr::except($this->rules(), keys: ['task', 'new_client']));

        try {
            DB::transaction(function () use ($validated) {
                $this->createProjectAndItsRelations($validated);

                session()->flash('success', 'Project has been created successfully');

                $this->reset();

                $this->status = ProjectStatuses::LIVE->value;

                return to_route('projects.index');
            });
        } catch (\Exception $ex) {
            session()->flash('error', 'We could not create the project. Please try again.');

            return;
        }
    }

    protected function rules(): array {
        return [
            'name' => [
                'required', 'string', 'min:3',
                Rule::unique(table: (new Project())->getTable(), column: 'name'),
            ],
            'description' => [
                'sometimes',
                'nullable',
                'min:6',
                Rule::requiredIf(!is_null($this->description)),
            ],
            'status' => [
                'required',
                new Enum(ProjectStatuses::class),
            ],
            'project_manager' => [
                'required',
                'numeric',
                Rule::exists(table: (new User())->getTable(), column: 'id')
                    ->using(
                        callback: fn () => User::query()->projectManagers()
                    ),
            ],
            'client' => Rule::when(
                condition: !$this->create_new_client,
                rules: [
                    'required',
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

    protected function createProjectAndItsRelations(array $validated): Project {
        $project = new Project([
            'name' => $validated['name'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
        ]);

        if (array_key_exists('new_client', $validated) && $this->create_new_client) {
            $client = Client::query()->create([
                'name' => $validated['new_client']
            ]);
        } else {
            $client = Client::query()->find($validated['client']);
        }
        $user = User::query()->find($validated['project_manager']);

        $project->client()->associate($client);
        $project->manager()->associate($user);

        $project->push();

        if ($this->create_tasks && $this->tasks?->isNotEmpty()) {
            //  dump($this->tasks);
            $project->tasks()->createMany($this->tasks?->toArray());
        }

        return $project;
    }
}
