<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OnboardingController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->hasSelectedRole()) {
            return $this->redirectToDashboard($user);
        }

        return view('onboarding.role');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->hasSelectedRole()) {
            return $this->redirectToDashboard($user);
        }

        $data = $request->validate([
            'role'            => ['required', Rule::in(['customer', 'supplier'])],
            'city'            => ['nullable', 'string', 'max:80'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            
            // Validasi Supplier
            'supplier_type'   => ['required_if:role,supplier', Rule::in(['mahasiswa', 'alumni'])],
            'document'        => ['required_if:role,supplier', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], 
        ], [
            'supplier_type.required_if' => 'Pilih status kamu (Mahasiswa/Alumni).',
            'document.required_if'      => 'Dokumen verifikasi wajib diunggah.',
        ]);

        $user->role             = $data['role'];
        $user->role_selected_at = now();
        $user->city             = $data['city'] ?? null;
        $user->latitude         = $data['latitude'] ?? null;
        $user->longitude        = $data['longitude'] ?? null;

        // ==========================================
        // PROSES VERIFIKASI SUPPLIER
        // ==========================================
        if ($data['role'] === 'supplier') {
            $user->supplier_type = $data['supplier_type'];
            
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                
                // 1. Jalankan Forensik EXIF
                $exifStatus = $this->checkExifMetadata($file->getPathname());

                if ($exifStatus === 'edited') {
                    return back()->withErrors([
                        'document' => 'Terdeteksi manipulasi! Gambar ini sepertinya telah diedit menggunakan aplikasi edit foto (Photoshop, Canva, dll). Harap unggah foto KTM/KRS yang asli.'
                    ])->withInput();
                }

                if ($exifStatus === 'stripped') {
                    return back()->withErrors([
                        'document' => 'File yang diunggah tidak memiliki metadata kamera. Ini biasanya terjadi jika gambar adalah screenshot atau hasil kiriman WhatsApp. Harap unggah file asli langsung dari galeri kamera HP Anda.'
                    ])->withInput();
                }

                // 2. Jika aman, simpan file
                $path = $file->store('verifications', 'public');
                $user->supplier_document_path = $path;
            }
        }

        $user->save();

        return $this->redirectToDashboard($user);
    }

    /**
     * Fungsi Forensik untuk memeriksa EXIF Metadata gambar.
     */
    private function checkExifMetadata($filePath)
    {
        // Baca data EXIF dari gambar
        $exif = @exif_read_data($filePath);

        // Jika tidak ada EXIF sama sekali (kemungkinan screenshot / WhatsApp)
        if ($exif === false) {
            return 'stripped';
        }

        // Daftar software editing yang sering dipakai untuk memalsukan dokumen
        $editingSoftwares = [
            'adobe photoshop', 'lightroom', 'snapseed', 'picsart', 
            'canva', 'pixlr', 'gimp', 'affinity photo', 'corel'
        ];

        // Cek apakah gambar diedit menggunakan software di atas
        if (isset($exif['Software'])) {
            $software = strtolower($exif['Software']);
            foreach ($editingSoftwares as $tool) {
                if (str_contains($software, $tool)) {
                    return 'edited';
                }
            }
        }

        // Cek apakah gambar benar-benar diambil dari kamera (punya nama pabrikan/model HP)
        // Beberapa HP mungkin tidak menulis 'Make', jadi kita cek 'Make' ATAU 'Model'
        if (!isset($exif['Make']) && !isset($exif['Model'])) {
            return 'stripped'; // Anggap mencurigakan jika tidak ada info kamera
        }

        return 'clean';
    }

    protected function redirectToDashboard($user)
    {
        return $user->isSupplier()
            ? redirect()->route('supplier.dashboard')
            : redirect()->route('customer.dashboard');
    }
}