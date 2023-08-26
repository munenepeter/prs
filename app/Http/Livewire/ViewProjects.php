<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use App\Http\Livewire\Concerns\HasUIkitPagination;

class ViewProjects extends Component
{
    use HasUIkitPagination;

    public ?User $user;

    public function mount(): void
    {
        $this->user ??= auth()->user();
    }

    public function deleteProject(int $project)
    {
        if (Gate::denies('manage-project')) {
            $this->dispatchBrowserEvent(
                event: 	'access-denied',
                data:['message' => 'You have no permission to perform this action']
            );

            return;
        }

        Project::query()->where(column: 'id', operator: '=', value: $project)->delete();

        session()->flash('success', 'Project successfully deleted');
    }

    public function render()
    {

        $projects = Project::query()->latest()
            ->select(['id', 'slug', 'name', 'client_id', 'user_id', 'created_at', 'status'])
            ->withCount('tasks')
            ->with([
                'manager:id,firstname,lastname',
                'client:id,name'
            ])
            ->when(
                $this->user->isProjectManager(),
                fn ($builder) => $builder->where('user_id', '=', $this->user->id)
            )
            ->paginate();

        $title = 'All Projects';

        return view('livewire.view-projects', compact('projects'))
                ->layout('layouts.app', compact('title'));
    }
}
