<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate OTP 6 digit
        $otp = rand(100000, 999999);

        // 1. Buat user TANPA OTP dulu
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 2. Paksa simpan OTP ke database
        $user->email_otp = $otp;
        $user->email_otp_expires_at = now()->addMinutes(15);
        $user->save();

        // 3. Kirim Email
        try {
            Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($otp));
        } catch (\Exception $e) {
            // Abaikan jika gagal kirim
        }

        Auth::login($user);

        return redirect()->route('verification.otp');
    }
}
