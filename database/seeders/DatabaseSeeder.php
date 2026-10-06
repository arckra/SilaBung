<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* ---------- Kategori ---------- */
        $categories = [
            ['slug' => 'kardus',     'name' => 'Kardus',     'emoji' => '📦'],
            ['slug' => 'plastik',    'name' => 'Plastik',    'emoji' => '♻️'],
            ['slug' => 'botol',      'name' => 'Botol',      'emoji' => '🍶'],
            ['slug' => 'kertas',     'name' => 'Kertas',     'emoji' => '📄'],
            ['slug' => 'kaca',       'name' => 'Kaca',       'emoji' => '🫙'],
            ['slug' => 'kayu',       'name' => 'Kayu',       'emoji' => '🪵'],
            ['slug' => 'logam',      'name' => 'Logam',      'emoji' => '🔩'],
            ['slug' => 'elektronik', 'name' => 'Elektronik', 'emoji' => '🔌'],
            ['slug' => 'pakaian',    'name' => 'Pakaian',    'emoji' => '👕'],
            ['slug' => 'lainnya',    'name' => 'Lainnya',    'emoji' => '📎'],
        ];
        foreach ($categories as $c) {
            Category::updateOrCreate(['slug' => $c['slug']], $c);
        }

        /* ---------- Supplier ---------- */
        $budi = User::updateOrCreate(
            ['email' => 'budi@silabung.test'],
            [
                'name'             => 'Budi Santoso',
                'password'         => Hash::make('password'),
                'role'             => 'supplier',
                'role_selected_at' => now(),
                'city'             => 'Cikarang Selatan',
                'address'          => 'Jl. Industri No. 12',
                'latitude'         => -6.2884,
                'longitude'        => 107.1510,
            ]
        );

        $siti = User::updateOrCreate(
            ['email' => 'siti@silabung.test'],
            [
                'name'             => 'Siti Aminah',
                'password'         => Hash::make('password'),
                'role'             => 'supplier',
                'role_selected_at' => now(),
                'city'             => 'Bekasi Timur',
                'address'          => 'Jl. Ahmad Yani No. 45',
                'latitude'         => -6.2380,
                'longitude'        => 107.0020,
            ]
        );

        $andi = User::updateOrCreate(
            ['email' => 'andi@silabung.test'],
            [
                'name'             => 'Andi Wijaya',
                'password'         => Hash::make('password'),
                'role'             => 'supplier',
                'role_selected_at' => now(),
                'city'             => 'Tambun',
                'address'          => 'Jl. Sultan Hasanudin No. 8',
                'latitude'         => -6.2570,
                'longitude'        => 107.0670,
            ]
        );

        /* ---------- Customer ---------- */
        User::updateOrCreate(
            ['email' => 'ari@silabung.test'],
            [
                'name'             => 'Ari Pratama',
                'password'         => Hash::make('password'),
                'role'             => 'customer',
                'role_selected_at' => now(),
                'city'             => 'Cikarang Utara',
                'latitude'         => -6.2700,
                'longitude'        => 107.1400,
            ]
        );

        /* ---------- Barang ---------- */
        $items = [
            ['supplier' => $budi, 'cat' => 'kardus',  'name' => 'Kardus Bekas',   'qty' => 10, 'unit' => 'pcs', 'cond' => 'Baik',
             'desc' => 'Kardus bekas pengiriman yang masih layak digunakan untuk packaging, tugas, atau kerajinan.'],
            ['supplier' => $budi, 'cat' => 'botol',   'name' => 'Botol Plastik',  'qty' => 25, 'unit' => 'pcs', 'cond' => 'Baik',
             'desc' => 'Botol plastik bening bersih, cocok untuk daur ulang atau kerajinan.'],
            ['supplier' => $siti, 'cat' => 'kayu',    'name' => 'Kayu Bekas',     'qty' => 8,  'unit' => 'pcs', 'cond' => 'Cukup',
             'desc' => 'Potongan kayu bekas proyek, masih kokoh untuk kerajinan atau bahan bakar.'],
            ['supplier' => $siti, 'cat' => 'kardus',  'name' => 'Kardus',         'qty' => 15, 'unit' => 'pcs', 'cond' => 'Baik',
             'desc' => 'Kardus tebal bekas pindahan, kondisi masih kuat.'],
            ['supplier' => $andi, 'cat' => 'botol',   'name' => 'Botol Plastik',  'qty' => 30, 'unit' => 'pcs', 'cond' => 'Baik',
             'desc' => 'Botol air mineral 600 ml, sudah dicuci bersih.'],
            ['supplier' => $andi, 'cat' => 'kertas',  'name' => 'Kertas Bekas',   'qty' => 20, 'unit' => 'kg',  'cond' => 'Baik',
             'desc' => 'Kertas HVS bekas pakai, masih bisa digunakan untuk catatan atau daur ulang.'],
        ];

        foreach ($items as $row) {
            $cat = Category::where('slug', $row['cat'])->first();
            Item::updateOrCreate(
                [
                    'supplier_id' => $row['supplier']->id,
                    'name'        => $row['name'],
                ],
                [
                    'category_id' => $cat->id,
                    'description' => $row['desc'],
                    'quantity'    => $row['qty'],
                    'unit'        => $row['unit'],
                    'condition'   => $row['cond'],
                    'status'      => 'available',
                    'city'        => $row['supplier']->city,
                    'address'     => $row['supplier']->address,
                    'latitude'    => $row['supplier']->latitude,
                    'longitude'   => $row['supplier']->longitude,
                ]
            );
        }
    }
}