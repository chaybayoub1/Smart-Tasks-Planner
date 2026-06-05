<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'username'       => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id, 'regex:/^[a-zA-Z0-9_]+$/'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bio'            => ['nullable', 'string', 'max:300'],
            'university'     => ['nullable', 'string', 'max:255'],
            'academic_level' => ['nullable', 'string'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'study_methods'  => ['nullable', 'array'],
            'study_goal'     => ['nullable', 'numeric', 'min:0.5', 'max:12'],
            'theme'          => ['nullable', 'in:dark,light,system'],
            'timezone'       => ['nullable', 'string'],
            'language'       => ['nullable', 'string'],
            'avatar'         => ['nullable', 'image', 'max:2048'],
        ]);

        // Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Remove avatar
        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }

        $user->name           = $request->name;
        $user->username       = $request->username;
        $user->bio            = $request->bio;
        $user->university     = $request->university;
        $user->academic_level = $request->academic_level;
        $user->field_of_study = $request->field_of_study;
        $user->study_methods  = $request->study_methods ?? [];
        $user->study_goal     = $request->study_goal ?? 2;
        $user->theme          = $request->theme ?? 'dark';
        $user->timezone       = $request->timezone ?? 'UTC';
        $user->language       = $request->language ?? 'en';

        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        if ($user->avatar) Storage::disk('public')->delete($user->avatar);

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
