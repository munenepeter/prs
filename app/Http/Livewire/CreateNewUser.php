<?php

namespace App\Http\Livewire;

use App\Enums\Roles;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use App\Http\Livewire\Concerns\HasUserInserts;

class CreateNewUser extends Component
{
    use HasUserInserts;

    public string $firstname = '';

    public string $lastname = '';

    public string $email = '';


    public function create()
    {
        $validated = $this->validate();

        $random_password = Str::random(8);

        $user = User::query()->create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($random_password),
        ]);

        $user->roles()->attach(
            Role::query()->where('name', '=', $validated['role'])->first('id')
        );


        $user->sendNewUserCreatedNotification($random_password);

        session()->flash('success', "$user->fullname has been created successfully.");

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.create-new-user');
    }

    protected function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' =>  [
                    'required',
                    new Enum(Roles::class),
                ],
        ];
    }
}
