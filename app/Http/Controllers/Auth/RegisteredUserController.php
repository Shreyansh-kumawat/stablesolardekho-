<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::where('email', $request->email)->delete();
        EmailOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        session([
            'reg_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'mobile_number' => $request->mobile_number,
            ],
        ]);

        try {
            Mail::to($request->email)->send(new OtpMail($otp, $request->name));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Email bhejne me error aaya. Please try again.');
        }

        return redirect()->route('otp.verify.form')->with('success', 'OTP sent to ' . $request->email);
    }

    public function showOtpForm(): View|RedirectResponse
    {
        if (!session('reg_data')) {
            return redirect()->route('register')->with('error', 'Please fill the registration form first.');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $regData = session('reg_data');
        if (!$regData) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        $otpRecord = EmailOtp::where('email', $regData['email'])
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Invalid OTP. Please try again.');
        }

        if ($otpRecord->isExpired()) {
            $otpRecord->delete();
            return back()->with('error', 'OTP expired. Please register again.');
        }

        $user = User::create([
            'name' => $regData['name'],
            'email' => $regData['email'],
            'password' => Hash::make($regData['password']),
            'mobile_number' => $regData['mobile_number'] ?? null,
            'email_verified_at' => now(),
        ]);

        $otpRecord->delete();
        session()->forget('reg_data');

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function resendOtp(): RedirectResponse
    {
        $regData = session('reg_data');
        if (!$regData) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::where('email', $regData['email'])->delete();
        EmailOtp::create([
            'email' => $regData['email'],
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($regData['email'])->send(new OtpMail($otp, $regData['name']));
        } catch (\Exception $e) {
            return back()->with('error', 'Email bhejne me error aaya. Please try again.');
        }

        return back()->with('success', 'New OTP sent to ' . $regData['email']);
    }
}
