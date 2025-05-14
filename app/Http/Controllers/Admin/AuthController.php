<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return $this->handleFailedLogin('The provided credentials do not match our records.');
        }

        // Verify admin privileges
        if (!Auth::user()->hasRole('admin')) {
            Auth::logout();
            return $this->handleFailedLogin('You do not have admin privileges.');
        }

        // Success - regenerate session and redirect
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Handle a failed login attempt.
     *
     * @param  string  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleFailedLogin(string $message)
    {
        return back()
            ->withErrors([
                'email' => $message,
            ])
            ->onlyInput('email');
    }

    /**
     * Log the admin out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
