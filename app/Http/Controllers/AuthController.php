<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request with brute-force rate limiting.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = $this->throttleKey($request);

        // Check if the user has too many failed login attempts (max 5 attempts per minute)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Demasiados intentos de acceso fallidos. Por favor intenta de nuevo en {$seconds} segundos.",
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Clear rate limiting counter on successful login
            RateLimiter::clear($throttleKey);

            // Regenerate session ID to prevent Session Fixation attacks
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // Increment failed attempts counter (lock for 60 seconds after 5 attempts)
        RateLimiter::hit($throttleKey, 60);

        $attemptsLeft = RateLimiter::remaining($throttleKey, 5);

        return back()->withErrors([
            'email' => $attemptsLeft > 0
                ? "Credenciales incorrectas. Te quedan {$attemptsLeft} intento(s) antes del bloqueo temporal."
                : 'Credenciales incorrectas.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }
}
