<?php
// app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'username'       => ['required', 'string', 'max:50', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar'         => ['nullable', 'image', 'max:2048'],
            'bio'            => ['nullable', 'string', 'max:300'],
            'university'     => ['nullable', 'string', 'max:255'],
            'academic_level' => ['nullable', 'string'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'study_methods'  => ['nullable', 'array'],
            'study_goal'     => ['nullable', 'numeric', 'min:0.5', 'max:12'],
            'theme'          => ['nullable', 'in:dark,light,system'],
            'timezone'       => ['nullable', 'string'],
            'language'       => ['nullable', 'string'],
        ]);

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'           => $request->name,
            'username'       => $request->username,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'avatar'         => $avatarPath,
            'bio'            => $request->bio,
            'university'     => $request->university,
            'academic_level' => $request->academic_level,
            'field_of_study' => $request->field_of_study,
            'study_methods'  => $request->study_methods ?? [],
            'study_goal'     => $request->study_goal ?? 2,
            'theme'          => $request->theme ?? 'dark',
            'timezone'       => $request->timezone ?? 'UTC',
            'language'       => $request->language ?? 'en',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
