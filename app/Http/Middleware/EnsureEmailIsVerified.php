<?php
// app/Http/Middleware/EnsureEmailIsVerified.php
// ─────────────────────────────────────────────────────────────────────────────
// Custom replacement for Laravel's built-in verified middleware.
// Redirects unverified users to our code-entry page instead of the
// default magic-link page.
// ─────────────────────────────────────────────────────────────────────────────

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->hasVerifiedEmail()) {
            // AJAX / API request
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your email address is not verified.'], 403);
            }

            return redirect()->route('verification.code.show');
        }

        return $next($request);
    }
}
