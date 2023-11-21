<?php

namespace App\Http\Livewire\Concerns;

use App\Models\User;
use App\Models\Client;
use App\Enums\TaskUnitTypes;
use App\Enums\ProjectStatuses;
use Illuminate\Support\Collection;

trait HasInserts
{
    public string $task = '';

    public int $target = 0;

    public string $unit_type = '';

    public ?string $new_client = null;

    public bool $create_tasks = false;

    public bool $create_new_client = false;

    abstract public function addTask(): void;

    abstract public function removeTask(int $task): void;

    public function getProjectStatusesProperty(): array
    {
        return ProjectStatuses::pluck('name', 'value');
    }

    public function getClientsProperty(): Collection
    {
        return Client::query()->pluck(column: 'name', key: 'id');
    }

    public function getProjectManagersProperty(): Collection
    {
        return User::query()->projectManagers()
            ->get()
            ->pluck('fullname', 'id');
    }
    public function getTaskUnitTypesProperty(): array
    {

        return TaskUnitTypes::pluck('name', 'value');
    }

    public function taskExistsBrowserEvent(): void
    {
        $this->dispatchBrowserEvent(
            event: 'task-exists',
            data: ['message' => 'You have already created this task']
        );
    }

    public function taskDoesntExistBrowserEvent(): void
    {
        $this->dispatchBrowserEvent(
            event: 'task-doesnt-exists',
            data: ['message' => 'This task does not exist.']
        );
    }

    public function taskRemovedBrowserEvent(): void
    {
        $this->dispatchBrowserEvent(
            event: 'task-removed',
            data: ['message' => 'Task has been successfully removed']
        );
    }
}
