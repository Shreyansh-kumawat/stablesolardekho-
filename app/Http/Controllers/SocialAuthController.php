<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Google login failed. Please try again.');
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $userRole = Role::where('name', 'user')->first();
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'password'          => bcrypt(str()->random(24)),
                'role_id'           => $userRole?->id ?? 3,
                'email_verified_at' => now(),
                'is_active'         => true,
            ]);
        }

        if (!$user->is_active) {
            return redirect('/')->with('error', 'Your account has been disabled.');
        }

        Auth::login($user, true);

        return redirect()->intended('/')->with('success', 'Welcome, ' . $user->name . '!');
    }
}
