<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'avatar.required' => 'Pilih gambar dulu.',
            'avatar.image'    => 'File harus berupa gambar.',
            'avatar.max'      => 'Ukuran maksimal 2 MB.',
        ]);

        $user = $request->user();

        // Hapus avatar lama
        if ($user->avatar_path && \Storage::disk('public')->exists($user->avatar_path)) {
            \Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        $user->save();

        return back()->with('status', 'avatar-updated');
    }

    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();
    
        if ($user->avatar_path && \Storage::disk('public')->exists($user->avatar_path)) {
            \Storage::disk('public')->delete($user->avatar_path);
        }
    
        $user->avatar_path = null;
        $user->save();
    
        return back()->with('status', 'avatar-removed');
    }
}
