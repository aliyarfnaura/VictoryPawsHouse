@extends('layouts.app')

@section('title', 'Katalog Produk Victory Paws House')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <header class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-[#6b4423]">Katalog Produk Terbaik</h1>
            <p class="mt-3 text-xl text-gray-700">Pilih kebutuhan hewan kesayangan Anda, mulai dari makanan premium hingga aksesoris lucu.</p>
            <p class="mt-1 text-xl text-gray-700">Produk tersedia di offline store kami.</p>
        </header>

        {{-- Logika untuk menampilkan pesan Flash (jika ada) --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Grid Produk --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

            {{-- Mock Data Produk (Nantinya akan diganti dengan @foreach loop dari Controller) --}}
            @php
                $mockProducts = [
                    ['name' => 'Royal Canine Kucing Adult', 'price' => 150000, 'stock' => 50, 'description' => 'Makanan kering premium untuk kucing dewasa.'],
                    ['name' => 'Kalung Kutu & Kutu', 'price' => 85000, 'stock' => 25, 'description' => 'Kalung anti kutu yang efektif dan aman.'],
                    ['name' => 'Mainan Bola Karet Bunyi', 'price' => 30000, 'stock' => 100, 'description' => 'Mainan interaktif yang disukai anjing.'],
                    ['name' => 'Shampoo Premium Anjing', 'price' => 65000, 'stock' => 40, 'description' => 'Shampoo dengan formula lembut untuk bulu yang berkilau.'],
                ];

                // Fungsi helper untuk format Rupiah
                function formatRupiah($amount) {
                    return 'Rp ' . number_format($amount, 0, ',', '.');
                }
            @endphp

            @foreach($mockProducts as $product)
                {{-- Card Produk --}}
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden border border-gray-200 flex flex-col">
                    
                    {{-- Placeholder Gambar --}}
                    <div class="h-48 bg-[#f5e8d0] flex items-center justify-center">
                        <span class="text-gray-500 text-lg">Gambar Produk</span>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        {{-- Nama Produk --}}
                        <h2 class="text-xl font-bold text-gray-900 truncate mb-2">{{ $product['name'] }}</h2>
                        
                        {{-- Deskripsi Singkat --}}
                        <p class="text-gray-600 text-sm mb-3 flex-grow">{{ $product['description'] }}</p>

                        {{-- Harga --}}
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-2xl font-extrabold text-[#6b4423]">
                                {{ formatRupiah($product['price']) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
            
        </div>
        {{-- Akhir Grid Produk --}}

    </div>
@endsection