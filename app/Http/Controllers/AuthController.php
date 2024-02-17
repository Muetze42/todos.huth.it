<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect(Request $request)
    {
        $request->validate([
            'remember' => 'boolean',
            'accepted' => 'accepted',
        ]);

        if ($request->hasSession()) {
            $request->session()->put('AuthRemember', $request->boolean('remember'));
        }

        return Socialite::driver('github')->redirect()->getTargetUrl();
    }

    public function callback(Request $request)
    {
        /* @var \SocialiteProviders\Manager\OAuth2\User|\SocialiteProviders\GitHub\Provider $githubUser */
        $githubUser = Socialite::driver('github')->stateless()->user();

        $remember = false;
        if ($request->hasSession()) {
            $remember = (bool) $request->session()->get('AuthRemember', false);
            $request->session()->forget('AuthRemember');
        }

        $user = User::updateOrCreate(
            ['github_id' => $githubUser->getId()],
            [
                'name' => $githubUser->getName(),
                'email' => $githubUser->getEmail(),
                'nickname' => $githubUser->getNickname(),
                'avatar' => $githubUser->getAvatar(),
                'blog' => data_get($githubUser->getRaw(), 'blog'),
            ]
        );

        Auth::login($user, $remember);

        return redirect()->intended();
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
