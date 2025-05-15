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
     * Redirect the user to the OAuth provider authentication page.
     *
     * @param string $provider The OAuth provider (github, google)
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the OAuth provider after authentication.
     *
     * @param string $provider The OAuth provider (github, google)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($this->shouldLoginExistingUser($user, $provider, $socialUser->getId())) {
                return $this->loginAndRedirectUser($user);
            }

            // Store user data in session for role selection
            $this->storeSocialDataInSession($provider, $socialUser);
            return redirect()->route('social.role.selection');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', "Authentication with $provider failed: " . $e->getMessage());
        }
    }

    /**
     * Determine if we should log in an existing user.
     *
     * @param User|null $user
     * @param string $provider
     * @param string $providerId
     * @return bool
     */
    private function shouldLoginExistingUser(?User $user, string $provider, string $providerId): bool
    {
        return $user && $user->provider == $provider && $user->provider_id == $providerId;
    }

    /**
     * Log in user and redirect based on role.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginAndRedirectUser(User $user)
    {
        Auth::login($user);

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('editor')) {
            return redirect()->route('editor.dashboard');
        } else {
            return redirect()->route('reader.dashboard');
        }
    }

    /**
     * Store social authentication data in session.
     *
     * @param string $provider
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @return void
     */
    private function storeSocialDataInSession(string $provider, $socialUser): void
    {
        session([
            'social_auth' => [
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
            ]
        ]);
    }

    /**
     * Show the role selection form after social authentication.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
        $user = $this->createUserFromSocialData($socialData, $request->role);

        // Login
        Auth::login($user);

        // Clear session data
        session()->forget('social_auth');

        // Redirect to appropriate dashboard
        return $user->hasRole('editor')
            ? redirect()->route('editor.dashboard')
            : redirect()->route('reader.dashboard');
    }

    /**
     * Create a new user from social authentication data.
     *
     * @param array $socialData
     * @param string $role
     * @return User
     */
    private function createUserFromSocialData(array $socialData, string $role): User
    {
        $user = User::create([
            'name' => $socialData['name'],
            'email' => $socialData['email'],
            'password' => Hash::make(Str::random(24)), // Random secure password
            'provider' => $socialData['provider'],
            'provider_id' => $socialData['provider_id'],
            'avatar' => $socialData['avatar'],
        ]);

        $user->assignRole($role);
        return $user;
    }
}
