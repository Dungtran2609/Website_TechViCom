<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    // Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(16)), // random password
                'google_id' => $googleUser->getId(),
            ]
        );

    Auth::login($user);
    return redirect('/');
    }

    // Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $fbUser = Socialite::driver('facebook')->user();

        $user = User::firstOrCreate(
            ['email' => $fbUser->getEmail()],
            [
                'name' => $fbUser->getName(),
                'password' => bcrypt(Str::random(16)),
                'facebook_id' => $fbUser->getId(),
            ]
        );

    Auth::login($user);
    return redirect('/');
    }
}
