<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Mail\VerificationCodeMail;

class OtpVerificationController extends Controller
{
    public function show()
    {
        // Jika tidak ada data pending di session, kembalikan ke halaman register
        if (!Session::has('pending_user')) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi habis, silakan daftar ulang.']);
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6']
        ]);

        $pendingUser = Session::get('pending_user');

        if (!$pendingUser) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi habis, silakan daftar ulang.']);
        }

        // Cek OTP
        if ($pendingUser['otp'] != $request->otp) {
            return back()->withErrors(['otp' => 'Kode verifikasi salah. Silakan cek email kamu lagi.']);
        }

        // Cek Kedaluwarsa
        if (now()->greaterThan($pendingUser['expires_at'])) {
            return back()->withErrors(['otp' => 'Kode sudah kedaluwarsa. Silakan kirim ulang kode.']);
        }

        // ==========================================
        // JIKA LOLOS, BARU MASUKKAN KE DATABASE
        // ==========================================
        $user = User::create([
            'name'              => $pendingUser['name'],
            'email'             => $pendingUser['email'],
            'password'          => $pendingUser['password'],
            'email_verified_at' => now(), // Langsung tandai terverifikasi
        ]);

        // Hapus session karena sudah tidak dibutuhkan
        Session::forget('pending_user');

        // Login user
        Auth::login($user);

        // Arahkan ke pemilihan role
        return redirect()->route('onboarding.role')->with('success', 'Email berhasil diverifikasi! Silakan pilih peran.');
    }

    public function resend()
    {
        $pendingUser = Session::get('pending_user');

        if (!$pendingUser) {
            return redirect()->route('register');
        }

        $otp = rand(100000, 999999);
        
        // Update OTP di session
        $pendingUser['otp'] = $otp;
        $pendingUser['expires_at'] = now()->addMinutes(15);
        Session::put('pending_user', $pendingUser);

        Mail::to($pendingUser['email'])->send(new VerificationCodeMail($otp));

        return back()->with('success', 'Kode verifikasi baru telah dikirim ke email kamu.');
    }
}