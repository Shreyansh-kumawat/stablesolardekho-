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
            'mobile_number' => ['nullable', 'string', 'max:20', 'unique:' . User::class . ',mobile_number'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'mobile_number.unique' => 'This mobile number is already registered. Please use a different number or log in.',
            'email.unique' => 'This email is already registered. Please use a different email or log in.',
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

        // Defense-in-depth: someone may have registered with the same email/mobile
        // during the OTP window, so re-check before insert to avoid a 500.
        if (User::where('email', $regData['email'])->exists()) {
            $otpRecord->delete();
            session()->forget('reg_data');
            return redirect()->route('register')->with('error', 'This email is already registered. Please log in instead.');
        }
        if (!empty($regData['mobile_number']) && User::where('mobile_number', $regData['mobile_number'])->exists()) {
            $otpRecord->delete();
            session()->forget('reg_data');
            return redirect()->route('register')->with('error', 'This mobile number is already registered. Please use a different number or log in.');
        }

        try {
            $user = User::create([
                'name' => $regData['name'],
                'email' => $regData['email'],
                'password' => Hash::make($regData['password']),
                'mobile_number' => $regData['mobile_number'] ?? null,
                'role_id' => 3,
                'email_verified_at' => now(),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Registration failed: ' . $e->getMessage());
            return redirect()->route('register')->with('error', 'Registration failed due to a conflict. Please try again with different details.');
        }

        $otpRecord->delete();
        session()->forget('reg_data');

        $namePart = strtoupper(\Illuminate\Support\Str::substr(preg_replace('/[^a-zA-Z]/', '', $user->name), 0, 4));
        $code = $namePart . rand(1000, 9999);
        while (\App\Models\ReferralCode::where('code', $code)->exists()) {
            $code = $namePart . rand(1000, 9999);
        }
        \App\Models\ReferralCode::create(['user_id' => $user->id, 'code' => $code]);

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
