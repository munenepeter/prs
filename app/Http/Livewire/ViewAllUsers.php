<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use App\Http\Livewire\Concerns\HasUIkitPagination;

class ViewAllUsers extends Component
{
    use WithPagination;
    use HasUIkitPagination;

    public $query = "";
    public $selectedRole = 'all';


    public function deleteUser(string $user_email)
    {

        if (Gate::denies('is-admin', auth()->user())) {
            $this->dispatchBrowserEvent(
                'access-denied',
                [
                    'message' => 'You have no permissions.'
                ]
            );
            return;
        }

        User::where('email', '=', $user_email)->delete();

        $this->dispatchBrowserEvent(
            'user-deleted',
            [
                'message' => 'User successfully deleted.'
            ]
        );

        return;
    }


    public function updatedQuery()
    {
        $this->resetPage();

        $this->validate([
            'query' => 'nullable|min:3',
        ]);
    }


    public function updatedSelectedRole($value)
    {
        $this->resetPage();
    }


    public function render()
    {
        $query = User::query();
        //search the selected roles
        if ($this->selectedRole !== 'all') {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', $this->selectedRole);
            });
        }
        //search based on search input
        $users = $query
            ->when($this->query, function ($query) {
                $query->where('firstname', 'like', '%' . $this->query . '%')
                    ->orWhere('lastname', 'like', '%' . $this->query . '%')
                    ->orWhere('email', 'like', '%' . $this->query . '%');
            })
            ->with([
                'roles' => fn ($query) => $query->select('id', 'name')->orderBy('name', 'asc')
            ])
            ->paginate();

        $this->deleteUsersWithoutRoles();

        return view('livewire.view-all-users', compact('users'));
    }

    private function deleteUsersWithoutRoles()
    {

        $usersWithoutRoles = User::doesntHave('roles')->get();

        $usersWithoutRoles->each(function ($user) {
            $user->delete();
        });
    }
}
