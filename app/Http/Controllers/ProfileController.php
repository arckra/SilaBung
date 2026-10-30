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
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $user = $request->user();

        // Hapus avatar lama jika ada
        if ($user->avatar_path && file_exists(public_path('uploads/' . $user->avatar_path))) {
            unlink(public_path('uploads/' . $user->avatar_path));
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.jpg'; // Paksa simpan sebagai JPG
            $folderPath = public_path('uploads/avatars');
            $path = $folderPath . '/' . $filename;

            // Pastikan folder ada
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            // === MULAI PROSES AUTO-RESIZE DENGAN GD LIBRARY BAWAAN PHP ===
            $sourcePath = $file->getRealPath();
            $info = getimagesize($sourcePath);
            
            if ($info !== false) {
                $mime = $info['mime'];
                
                // Buat gambar berdasarkan tipe file
                switch ($mime) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $image = imagecreatefromjpeg($sourcePath);
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng($sourcePath);
                        break;
                    case 'image/webp':
                        $image = imagecreatefromwebp($sourcePath);
                        break;
                    default:
                        $image = imagecreatefromjpeg($sourcePath);
                }

                // Ukuran asli gambar
                $width = imagesx($image);
                $height = imagesy($image);

                // Tentukan ukuran target (Persegi 400x400)
                $targetSize = 400;

                // Hitung titik potong tengah (Center Crop)
                $minSide = min($width, $height);
                $srcX = ($width - $minSide) / 2;
                $srcY = ($height - $minSide) / 2;

                // Buat kanvas baru berukuran 400x400
                $newImage = imagecreatetruecolor($targetSize, $targetSize);

                // Pindahkan dan perkecil gambar ke kanvas baru
                imagecopyresampled(
                    $newImage, $image,
                    0, 0, 
                    $srcX, $srcY, 
                    $targetSize, $targetSize, 
                    $minSide, $minSide
                );

                // Simpan sebagai JPG dengan kualitas 85% (ukuran jadi sangat kecil)
                imagejpeg($newImage, $path, 85);

                // Bersihkan memori
                imagedestroy($image);
                imagedestroy($newImage);
            } else {
                // Jika gagal dideteksi, simpan apa adanya
                $file->move($folderPath, $filename);
            }

            $user->avatar_path = 'avatars/' . $filename;
            $user->save();
        }

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
