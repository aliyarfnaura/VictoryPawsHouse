<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus bagian User::factory yang error
        // Panggil Seeder layanan Anda di sini
        $this->call([
            PenggunaSeeder::class,
            LayananSeeder::class,
        ]);
    }
}