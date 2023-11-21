<?php

namespace App\Http\Livewire;

use App\Enums\Roles;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Illuminate\Validation\Rules\Enum;
use App\Http\Livewire\Concerns\HasUserInserts;

class EditUser extends Component
{
    use HasUserInserts;

    public User $user;

    public function mount()
    {
        $this->user->loadMissing('roles');

        $this->role = $this->user->roles->first()->name->value;
    }

    public function getUserRoleProperty()
    {
        return $this->user->first();
    }

    public function edit()
    {

        //Some witchcraft which makes the user uneditable

        // if (
        //     $this->user->isClean() ||
        //     $this->userRole->name === Roles::tryFrom($this->role)
        // ) {
        //     return redirect()->route('users.index');
        // }

        $validated = $this->validate();

        $role = Role::query()->where('name', '=', $validated['role'])->value('id');

        $this->user->roles()->sync([$role]);

        $this->user->save();

        session()->flash('success', 'User updated successfully');

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.edit-user');
    }

    protected function rules()
    {
        return [
            'user.firstname' => [
                'required',
                'string',
                'min:3'
            ],
            'user.lastname' => [
                'required',
                'string',
                'min:3'
            ],
            'user.email' => [
                'required',
                'string'
            ],
            'user.phone_number' =>
            [
                'required',
                'string',
                'digits:10',
                function ($attribute, $value, $fail) {
                    if (substr($value, 0, 1) !== '0') {
                        $fail("The $attribute must start with '0'.");
                    }
                }
            ],
            'user.gender' => [
                'required',
                $this->genders
            ],
            'role' => [
                'required',
                new Enum(Roles::class)
            ]
        ];
    }
}
