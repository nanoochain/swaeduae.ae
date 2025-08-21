<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // --- GOOGLE ---
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $gUser = Socialite::driver('google')->user();

        $user = User::where('email', $gUser->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'name'     => $gUser->getName() ?: ($gUser->user['given_name'] ?? 'Volunteer'),
                'email'    => $gUser->getEmail(),
                'password' => bcrypt(Str::random(32)), // random since social login
                // add any role/flags your app expects, e.g. 'role' => 'volunteer'
            ]);
        }

        // store avatar if you want:
        // if ($gUser->getAvatar()) { ... }

        Auth::login($user, true);
        return redirect()->intended('/');  // or /volunteer/dashboard
    }

    // --- FACEBOOK (optional) ---
    public function facebookRedirect()
    {
        abort_unless(config('services.facebook.client_id'), 404);
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        abort_unless(config('services.facebook.client_id'), 404);
        $fbUser = Socialite::driver('facebook')->user();

        $user = User::where('email', $fbUser->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'name'     => $fbUser->getName() ?: 'Volunteer',
                'email'    => $fbUser->getEmail(),
                'password' => bcrypt(Str::random(32)),
            ]);
        }

        Auth::login($user, true);
        return redirect()->intended('/');
    }
}
