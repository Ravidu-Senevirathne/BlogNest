<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the OAuth provider.
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the OAuth provider.
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Check if the user exists
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Update provider details if they've changed
                if ($user->provider == $provider && $user->provider_id == $socialUser->getId()) {
                    // Log user in
                    Auth::login($user);

                    // Redirect based on role
                    if ($user->hasRole('admin')) {
                        return redirect()->route('admin.dashboard');
                    } elseif ($user->hasRole('editor')) {
                        return redirect()->route('editor.dashboard');
                    } else {
                        return redirect()->route('reader.dashboard');
                    }
                }
            }

            // Store user data in session for role selection
            session([
                'social_auth' => [
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                ]
            ]);

            return redirect()->route('social.role.selection');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Something went wrong with the ' . $provider . ' login: ' . $e->getMessage());
        }
    }

    /**
     * Show the role selection form after social authentication.
     */
    public function showRoleSelectionForm()
    {
        if (!session()->has('social_auth')) {
            return redirect()->route('login');
        }

        return view('auth.role-selection');
    }

    /**
     * Store the user with their selected role.
     */
    public function storeUserWithRole(Request $request)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:editor,reader'],
        ]);

        if (!session()->has('social_auth')) {
            return redirect()->route('login');
        }

        $socialData = session('social_auth');

        // Create user
        $user = User::create([
            'name' => $socialData['name'],
            'email' => $socialData['email'],
            'password' => Hash::make(Str::random(24)), // Random secure password
            'provider' => $socialData['provider'],
            'provider_id' => $socialData['provider_id'],
            'avatar' => $socialData['avatar'],
        ]);

        // Assign role
        $user->assignRole($request->role);

        // Login
        Auth::login($user);

        // Clear session data
        session()->forget('social_auth');

        // Redirect to appropriate dashboard
        if ($user->hasRole('editor')) {
            return redirect()->route('editor.dashboard');
        } else {
            return redirect()->route('reader.dashboard');
        }
    }
}
