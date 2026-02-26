<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{


public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => ['required', Password::min(4)],
        'remember' => 'sometimes|boolean',
    ]);

    $key = Str::lower($request->input('email')).'|'.$request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        return back()->withErrors([
            'message' => "Too many attempts. Try again in {$seconds} seconds."
        ]);
    }

    $remember = $request->boolean('remember');

    if (!auth()->attempt($request->only('email', 'password'), $remember)) {
        RateLimiter::hit($key, 60);
        return back()->withErrors(['message' => 'Invalid credentials']);
    }

    RateLimiter::clear($key);

    // Regenerate session to prevent fixation and then redirect
    $request->session()->regenerate();
    return redirect()->intended(route('dashboard'));
}


    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
