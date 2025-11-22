<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan setidaknya ada 1 user di tabel pengguna agar tidak error Foreign Key
        // Jika tabel pengguna kosong, kita buat user dummy dulu (Optional, tapi aman)
        $userId = DB::table('pengguna')->first()->id_pengguna ?? DB::table('pengguna')->insertGetId([
            'username' => 'admin_dummy',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('layanan')->insert([
            [
                'id_pengguna' => $userId, // <--- TAMBAHAN WAJIB
                'nama_layanan' => 'Pet Grooming',
                'harga' => 50000, 
                'gambar' => 'grooming.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => $userId, // <--- TAMBAHAN WAJIB
                'nama_layanan' => 'Pet Hotel',
                'harga' => 35000, 
                'gambar' => 'hotel.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => $userId, // <--- TAMBAHAN WAJIB
                'nama_layanan' => 'Home Service',
                'harga' => 75000, 
                'gambar' => 'homeservice.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}