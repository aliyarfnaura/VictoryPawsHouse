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

        $existingUser = DB::table('pengguna')->where('email', 'pembimbing@gmail.com')->first();

    if (!$existingUser) {
        $userId = DB::table('pengguna')->insertGetId([
            'username' => 'pembimbing_erikamaulidiya',
            'email' => 'pembimbing@gmail.com',
            'password' => bcrypt('inipassword123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        $userId = $existingUser->id_pengguna;
    }

        DB::table('layanan')->insert([
            [
                'id_pengguna' => $userId, 
                'nama_layanan' => 'Pet Grooming',
                'harga' => 50000, 
                'gambar' => 'grooming.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => $userId, 
                'nama_layanan' => 'Pet Hotel',
                'harga' => 35000, 
                'gambar' => 'hotel.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => $userId,
                'nama_layanan' => 'Home Service',
                'harga' => 75000, 
                'gambar' => 'homeservice.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}