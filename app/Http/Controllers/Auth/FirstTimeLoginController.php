<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class FirstTimeLoginController extends Controller
{
    public function create(Request $request)
    {
        return view('auth.first-time-login', compact('request'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::query()->firstWhere('email', '=', $request->email);

        if ($user->doesntExist()) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('The email provided does not exist on our database.')]);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'password_changed_at' => now(),
        ])->save();

        return redirect()->intended('login')->with('sdfsd', 'Your password has been successfully updated');
    }
}
