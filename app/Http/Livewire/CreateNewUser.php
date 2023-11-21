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

    public $phone_number;


    public function create()
    {
        $this->validate();

        $random_password = Str::random(8);

        $user = User::query()->create([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'password' => Hash::make($random_password),
        ]);

        $user->roles()->attach(
            Role::query()->where('name', '=', $this->role)->first('id')
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
            'phone_number' => [
                'required',
                'string',
                'digits:10',
                function ($attribute, $value, $fail) {
                    if (substr($value, 0, 1) !== '0') {
                        $fail("The $attribute must start with '0'.");
                    }
                },],
            'gender' => 'required',
            'role' =>  [
                'required',
                new Enum(Roles::class),
            ],
        ];
    }
}
