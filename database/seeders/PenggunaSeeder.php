<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengguna')->insert([
            'username'   => 'adminvph',
            'email'      => 'admin@gmail.com',
            'password'   => Hash::make('adminbro'), 
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('pengguna')->insert([
            'username'   => 'pembimbing_erikamaulidiya',
            'email'      => 'pembimbing@gmail.com',
            'password'   => Hash::make('inipassword123'),
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('pengguna')->insert([
            'username'   => 'pelanggan_erikamaulidiya',
            'email'      => 'pelanggan@gmail.com',
            'password'   => Hash::make('pelanggan123'),
            'role'       => 'pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
