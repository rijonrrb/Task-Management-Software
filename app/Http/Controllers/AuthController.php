<?php

namespace App\Http\Controllers;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  CONTROLLER: AuthController                                  ║
 * ║  Purpose: Handles user registration, login, and logout       ║
 * ║  Learning: Form validation, auth, password hashing, redirect ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ──────────────────────────────────────────────
    // REGISTRATION
    // ──────────────────────────────────────────────

    /**
     * Show the registration form.
     * Route: GET /register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration form submission.
     * Route: POST /register
     *
     * Steps:
     * 1. Validate input data
     * 2. Create user with hashed password
     * 3. Log the user in automatically
     * 4. Redirect to dashboard
     */
    public function register(Request $request)
    {
        // Step 1: Validate — Laravel automatically returns errors if validation fails
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        // Step 2: Create the user (password is auto-hashed via model cast)
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'], // Hashed automatically by model cast
        ]);

        // Step 3: Log the user in
        Auth::login($user);

        // Step 4: Redirect with success message
        return redirect()->route('dashboard')
                         ->with('success', 'Welcome to TaskFlow! Your account has been created.');
    }

    // ──────────────────────────────────────────────
    // LOGIN
    // ──────────────────────────────────────────────

    /**
     * Show the login form.
     * Route: GET /login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login form submission.
     * Route: POST /login
     *
     * Steps:
     * 1. Validate credentials
     * 2. Attempt authentication
     * 3. Regenerate session (security: prevents session fixation)
     * 4. Redirect to intended page or dashboard
     */
    public function login(Request $request)
    {
        // Step 1: Validate
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Step 2: Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Step 3: Regenerate session ID for security
            $request->session()->regenerate();

            // Step 4: Redirect to the page they were trying to access, or dashboard
            return redirect()->intended(route('dashboard'))
                             ->with('success', 'Welcome back, ' . Auth::user()->first_name . '!');
        }

        // If login fails, redirect back with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // ──────────────────────────────────────────────
    // LOGOUT
    // ──────────────────────────────────────────────

    /**
     * Log the user out.
     * Route: POST /logout
     *
     * Steps:
     * 1. Log out the user
     * 2. Invalidate the session
     * 3. Regenerate CSRF token
     * 4. Redirect to login
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'You have been logged out successfully.');
    }
}
