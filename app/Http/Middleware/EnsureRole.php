<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Pastikan user sudah login, sudah memilih role,
     * dan rolenya sesuai dengan yang diminta.
     *
     * Contoh pemakaian di route:
     *   ->middleware('role:customer')
     *   ->middleware('role:supplier')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Belum memilih role → arahkan ke onboarding.
        if (! $user->hasSelectedRole()) {
            return redirect()->route('onboarding.role');
        }

        // Role tidak sesuai → tampilkan 403 (bukan redirect).
        if ($user->role !== $role) {
            abort(403, 'Kamu tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}