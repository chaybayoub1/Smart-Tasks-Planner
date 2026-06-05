<?php
// app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCodeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
<<<<<<< HEAD
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
=======
use Illuminate\Support\Facades\Storage;
>>>>>>> hiba
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
<<<<<<< HEAD
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Create and log in the user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
=======
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
>>>>>>> hiba
        ]);

        Auth::login($user);
        event(new Registered($user));
<<<<<<< HEAD

        // 2. Issue code + send email — both inside one try/catch.
        //    Previously the try/catch only wrapped Mail::send(), so when
        //    issueFor() threw SQLSTATE[22001] (code column too short) it
        //    still produced an unhandled 500 and never reached Mail::send().
        try {
            $record = EmailVerificationCode::issueFor($user);
=======
        Auth::login($user);
>>>>>>> hiba

            Mail::to($user->email)
                ->send(new EmailVerificationCodeMail($user, $record->code));

            Log::info('Verification email sent successfully', [
                'user_id' => $user->id,
                'email'   => $user->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Verification email failed', [
                'user_id'   => $user->id,
                'email'     => $user->email,
                'exception' => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ]);
            // Registration succeeded — redirect so the user can request a resend.
        }

        return redirect()->route('verification.code.show');
    }
}
