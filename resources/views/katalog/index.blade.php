@extends('layouts.app')
@section('title', 'Katalog Produk Victory Paws House')
@section('content')
    <div class="bg-[#F8F4E1] max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <header class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-[#6b4423]">Katalog Produk Terbaik</h1>
            <p class="mt-3 text-xl text-gray-700">Pilih kebutuhan hewan kesayangan Anda, mulai dari makanan premium hingga aksesoris lucu.</p>
            <p class="mt-1 text-xl text-gray-700">Produk tersedia di offline store kami.</p>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @php
            function formatRupiah($amount) {
                return 'Rp ' . number_format($amount, 0, ',', '.');
            }
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

            @forelse($products as $product)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden border border-gray-200 flex flex-col">
                    
                    <div class="h-48 bg-[#f5e8d0] flex items-center justify-center relative">
                        <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" 
                             class="w-full h-full object-cover" 
                             onerror="this.onerror=null; this.src='https://placehold.co/400x192/f5e8d0/6b4423?text=Gambar+Produk'">
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <h2 class="text-xl font-bold text-gray-900 truncate mb-2">{{ $product->nama_produk }}</h2>
                        
                        <p class="text-gray-600 text-sm mb-3 flex-grow">{{ $product->deskripsi }}</p>

                        <div class="flex justify-between items-center mt-3">
                            <span class="text-2xl font-extrabold text-[#6b4423]">
                                {{ formatRupiah($product->harga) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-xl text-gray-500">Belum ada produk yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
