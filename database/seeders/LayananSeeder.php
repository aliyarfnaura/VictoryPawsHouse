<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
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
                'id_pengguna' => $userId, 
                'nama_layanan' => 'Pet Grooming',
                'harga' => 150000, 
                'gambar' => 'grooming.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => $userId, 
                'nama_layanan' => 'Pet Hotel',
                'harga' => 55000, 
                'gambar' => 'hotel.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => $userId,
                'nama_layanan' => 'Home Service',
                'harga' => 160000, 
                'gambar' => 'homeservice.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
