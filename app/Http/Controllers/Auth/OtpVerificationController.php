<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Str;

class OtpVerificationController extends Controller
{
    public function show()
    {
        if (auth()->user()->email_verified_at) {
            return redirect()->route('dashboard');
        }
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        // Validasi: Wajib diisi, berupa teks, dan panjangnya tepat 6 karakter
        $request->validate([
            'otp' => ['required', 'string', 'size:6']
        ], [
            'otp.required' => 'Kode verifikasi wajib diisi.',
            'otp.size'     => 'Kode verifikasi harus 6 digit.'
        ]);

        $otpString = $request->otp;
        $user = auth()->user();

        // Cek apakah cocok dengan database
        if ($user->email_otp !== $otpString) {
            return back()->withErrors(['otp' => 'Kode verifikasi salah. Silakan cek email kamu lagi.']);
        }

        if (now()->greaterThan($user->email_otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode sudah kedaluwarsa. Silakan kirim ulang kode.']);
        }

        // Tandai email terverifikasi
        $user->email_verified_at = now();
        $user->email_otp = null;
        $user->email_otp_expires_at = null;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi!');
    }

    public function resend()
    {
        $user = auth()->user();
        $otp = rand(100000, 999999);

        $user->email_otp = $otp;
        $user->email_otp_expires_at = now()->addMinutes(15);
        $user->save();

        Mail::to($user->email)->send(new VerificationCodeMail($otp));

        return back()->with('success', 'Kode verifikasi baru telah dikirim ke email kamu.');
    }
}