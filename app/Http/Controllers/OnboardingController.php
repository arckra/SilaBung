<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OnboardingController extends Controller
{
    /**
     * Tampilkan halaman pilih role.
     * Kalau user sudah punya role, langsung arahkan ke dashboard.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->hasSelectedRole()) {
            return $this->redirectToDashboard($user);
        }

        return view('onboarding.role');
    }

    /**
     * Simpan role yang dipilih.
     * Role hanya bisa dipilih SEKALI — immutable setelah ini.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Proteksi: kalau role sudah pernah dipilih, jangan izinkan ubah.
        if ($user->hasSelectedRole()) {
            return $this->redirectToDashboard($user);
        }

        $data = $request->validate([
            'role'      => ['required', Rule::in(['customer', 'supplier'])],
            'city'      => ['nullable', 'string', 'max:80'],
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'role.required' => 'Pilih salah satu peran dulu ya.',
            'role.in'       => 'Peran tidak valid.',
        ]);

        $user->role             = $data['role'];
        $user->role_selected_at = now();
        $user->city             = $data['city'] ?? null;
        $user->latitude         = $data['latitude'] ?? null;
        $user->longitude        = $data['longitude'] ?? null;
        $user->save();

        return $this->redirectToDashboard($user);
    }

    /**
     * Arahkan ke dashboard sesuai role.
     */
    protected function redirectToDashboard($user)
    {
        return $user->isSupplier()
            ? redirect()->route('supplier.dashboard')
            : redirect()->route('customer.dashboard');
    }
}