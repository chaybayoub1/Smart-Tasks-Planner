<?php

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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Create and log in the user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        event(new Registered($user));

        // 2. Issue code + send email — both inside one try/catch.
        //    Previously the try/catch only wrapped Mail::send(), so when
        //    issueFor() threw SQLSTATE[22001] (code column too short) it
        //    still produced an unhandled 500 and never reached Mail::send().
        try {
            $record = EmailVerificationCode::issueFor($user);

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
