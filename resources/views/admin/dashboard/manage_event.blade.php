@extends('layouts.admin')

@section('title', 'Manajemen Event')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: LIST EVENT --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg h-full border border-gray-200">
            <h1 class="text-xl font-bold text-gray-800 mb-2">Event Yang Akan Datang</h1>
            <p class="text-sm text-gray-500 mb-6">Daftar semua event yang akan terdaftar.</p>
            
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4">{{ session('success') }}</div>
            @endif

            <ul class="divide-y divide-gray-200">
                @forelse ($events as $event)
                    <li class="py-4 px-3 hover:bg-gray-50 transition duration-150 rounded-md">

                        <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">

                            {{-- Nama event + tanggal --}}
                            <div class="md:col-span-5">
                                <p class="font-semibold text-gray-900 leading-tight">
                                    {{ $event->nama_event }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>

                            {{-- Lokasi --}}
                            <div class="md:col-span-5 hidden md:block">
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $event->lokasi }}
                                </p>
                            </div>

                            {{-- Aksi --}}
                            <div class="md:col-span-2 flex md:justify-end items-center space-x-2">

                                <a href="{{ route('admin.event.index', $event->id_event) }}"
                                title="Edit"
                                class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-indigo-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-7l4 4m-4-4l-4 4m4-4l-4 4"/>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.event.destroy', $event->id_event) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin hapus event ini?');">
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
                    <li class="py-10 text-center text-gray-500">
                        Belum ada event yang terdaftar.
                    </li>
                @endforelse
            </ul>
        </div>
        
        {{-- KOLOM KANAN: FORM INPUT / EDIT --}}
        <div class="lg:col-span-1 bg-[#fcf8f0] p-6 rounded-xl shadow-lg border border-gray-200 sticky top-0">
            <h2 class="text-xl font-extrabold text-gray-800 border-b pb-2 mb-4">
                {{ $eventToEdit ? 'Edit Event' : 'Tambah Informasi Event' }}
            </h2>
            
            <form action="{{ $eventToEdit ? route('admin.event.update', $eventToEdit->id_event) : route('admin.event.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if ($eventToEdit)
                    @method('PUT')
                @endif
                
                {{-- Field Nama Event --}}
                <div class="mb-4">
                    <label for="nama_event" class="block text-sm font-semibold text-gray-700 mb-1">Nama Event</label>
                    <input type="text" name="nama_event" id="nama_event" value="{{ old('nama_event', $eventToEdit->nama_event ?? '') }}" 
                           class="w-full border-gray-300 rounded-lg p-3">
                    @error('nama_event') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                {{-- Field Tanggal & Waktu --}}
                <div class="mb-4">
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal & Waktu</label>
                    <input type="datetime-local" name="tanggal" id="tanggal" value="{{ old('tanggal', $eventToEdit && $eventToEdit->tanggal ? \Carbon\Carbon::parse($eventToEdit->tanggal)->format('Y-m-d\TH:i') : '') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full border-gray-300 rounded-lg p-3">
                    @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                {{-- Field Lokasi --}}
                <div class="mb-4">
                    <label for="lokasi" class="block text-sm font-semibold text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $eventToEdit->lokasi ?? '') }}" 
                           class="w-full border-gray-300 rounded-lg p-3">
                    @error('lokasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Field Deskripsi --}}
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" 
                              class="w-full border-gray-300 rounded-lg p-3">{{ old('deskripsi', $eventToEdit->deskripsi ?? '') }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="flex justify-end space-x-3">
                     @if ($eventToEdit)
                         <a href="{{ route('admin.event.index') }}" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-600 transition">Batal</a>
                     @endif
                     <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                        {{ $eventToEdit ? 'Update Event' : 'Simpan Event' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection