@extends('layouts.admin')

@section('title', 'Manajemen Katalog Produk')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full">
        
        {{-- KOLOM KIRI: LIST PRODUK --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg h-full border border-gray-200">
            <h1 class="text-xl font-bold text-gray-800 mb-2">List Produk Katalog</h1>
            <p class="text-sm text-gray-500 mb-6">Edit Produk di sini.</p>
            
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4">{{ session('success') }}</div>
            @endif

            <ul class="divide-y divide-gray-200">
                @forelse ($products as $product)
                    <li class="py-4 px-3 hover:bg-gray-50 transition duration-150 rounded-md">

                        <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">

                            <div class="flex items-center space-x-3 md:col-span-4">
                                {{-- FIX: Gunakan asset('storage/') --}}
                                <img src="{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://placehold.co/80x80/f5e8d0/6b4423?text=P' }}"
                                    alt="{{ $product->nama_produk }}"
                                    class="w-12 h-12 object-cover rounded-full">

                                <div>
                                    <p class="font-semibold text-gray-900 leading-tight">
                                        {{ $product->nama_produk }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="md:col-span-6 hidden md:block">
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $product->deskripsi }}
                                </p>
                            </div>

                            <div class="flex items-center md:justify-end space-x-2 md:col-span-2">

                                <a href="{{ route('admin.katalog.edit', $product->id_produk) }}"
                                class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-indigo-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-7l4 4m-4-4l-4 4m4-4l-4 4"/>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.katalog.destroy', $product->id_produk) }}"
                                    method="POST" onsubmit="return confirm('Yakin hapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10H8a1 1 0 00-1 1v2h12v-2a1 1 0 00-1-1z"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>

                        </div>

                    </li>
                @empty
                    <li class="py-10 text-center text-gray-500">Belum ada produk dalam katalog.</li>
                @endforelse
            </ul>
        </div>
        
        {{-- KOLOM KANAN: FORM TAMBAH/EDIT PRODUK --}}
        <div class="lg:col-span-1 bg-[#fcf8f0] p-6 rounded-xl shadow-lg border border-gray-200 lg:sticky lg:top-0">
            <h2 class="text-xl font-extrabold text-grey-800 border-b pb-2 mb-4">
                {{ $productToEdit ? 'Edit Produk' : 'Tambah Produk Baru' }}
            </h2>
            
            <form action="{{ $productToEdit ? route('admin.katalog.update', $productToEdit->id_produk) : route('admin.katalog.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if ($productToEdit)
                    @method('PUT')
                @endif
                
                <div class="mb-4">
                    <label for="nama_produk" class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $productToEdit->nama_produk ?? '') }}" 
                            class="w-full border-gray-300 rounded-lg p-3">
                    @error('nama_produk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="harga" class="block text-sm font-semibold text-gray-700 mb-1">Harga Produk</label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga', $productToEdit->harga ?? '') }}" step="1000"
                            class="w-full border-gray-300 rounded-lg p-3">
                    @error('harga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan Produk</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" 
                              class="w-full border-gray-300 rounded-lg p-3">{{ old('deskripsi', $productToEdit->deskripsi ?? '') }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-1">Upload Foto Produk</label>
                    @if ($productToEdit && $productToEdit->gambar)
                        <p class="text-sm text-gray-500 mb-2">Foto Saat Ini:</p>
                        <img src="{{ asset('storage/' . $productToEdit->gambar) }}" 
                            alt="{{ $productToEdit->nama_produk }}" 
                            class="w-20 h-20 object-cover rounded-lg mb-3 border border-gray-300">
                    @endif
                    <input type="file" name="gambar" id="gambar" accept="image/*"
                            class="w-full border-gray-300 rounded-lg p-3 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="flex justify-end space-x-3">
                     @if ($productToEdit)
                         <a href="{{ route('admin.katalog.index') }}" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-600 transition">Batal</a>
                     @endif
                     <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                         {{ $productToEdit ? 'Update Produk' : 'Tambah Produk' }}
                     </button>
                </div>
            </form>
        </div>
    </div>
@endsection