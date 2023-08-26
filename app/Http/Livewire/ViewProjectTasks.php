<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;

class ViewProjectTasks extends Component
{
    public Project $project;

    protected $listeners = [
        'task-edited' => 'taskEdited'
    ];

    public function taskEdited()
    {
        $this->project->refresh();
    }

    public function mount()
    {
        $this->project->loadMissing('tasks');
    }

    public function deleteTask(int $project)
    {
        $this->project->tasks->firstWhere('id', '=', $project)->delete();
        $this->project->refresh();

        $this->dispatchBrowserEvent(
            'task-deleted',
            [
                'message' => 'Task has been successfully deleted'
            ]
        );
    }

    public function render()
    {
        return view('livewire.view-project-tasks')
            ->layout('layouts.app', ['title' => $this->project->name . ' Tasks']);
    }
}
