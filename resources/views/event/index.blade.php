@extends('layouts.app')

@section('title', 'Jadwal Pameran & Kontes')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <header class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-[#6b4423]">Jadwal Pameran & Kontes</h1>
            <p class="mt-3 text-xl text-gray-700">Ajang Berkumpulnya Pecinta Hewan, Menampilkan yang Terbaik dari Kesayangan Anda.</p>
        </header>

        {{-- Grid Event --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @forelse ($events as $event)
                {{-- Kartu Event --}}
                <div class="bg-white rounded-xl shadow-xl overflow-hidden border-2 border-gray-100 transform hover:scale-[1.02] transition duration-300 flex flex-col">
                    
                    {{-- Placeholder Gambar Event --}}
                    <div class="h-48 bg-[#f5e8d0] flex items-center justify-center relative">
                        @if ($event->gambar)
                             <img src="{{ asset('images/' . $event->gambar) }}" alt="{{ $event->nama_event }}" class="w-full h-full object-cover">
                        @else
                             <span class="text-gray-500 text-lg">Gambar Event</span>
                        @endif
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">NEXT EVENT</span>
                    </div>

                    <div class="p-6 flex flex-col flex-grow text-center">
                        {{-- Nama Event --}}
                        <h2 class="text-2xl font-extrabold text-[#6b4423] mb-3">{{ $event->nama_event }}</h2>
                        
                        {{-- Detail --}}
                        <div class="text-left space-y-2 text-gray-700 flex-grow">
                            <p class="font-bold">Tanggal & Waktu:</p>
                            <p class="text-sm">
                                {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('l, d F Y, \P\u\k\u\l H:i') }} - Selesai
                            </p>
                            
                            <p class="font-bold pt-2">Lokasi:</p>
                            <p class="text-sm">{{ $event->lokasi }}</p>

                            <p class="font-bold pt-2">Deskripsi:</p>
                            <p class="text-sm">{{ $event->deskripsi }}</p>
                        </div>
                        
                        {{-- Tombol Detail --}}
                        <a href="#" class="mt-6 w-full bg-[#6b4423] text-white font-semibold py-2 rounded-lg hover:bg-[#4a3719] transition duration-300 shadow-md">
                            Detail Event
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-white rounded-xl shadow-lg">
                    <p class="text-xl font-semibold text-gray-500">Tidak ada jadwal pameran atau kontes dalam waktu dekat.</p>
                    <p class="mt-2 text-gray-600">Nantikan informasi terbaru di Instagram kami!</p>
                </div>
            @endforelse
            
        </div>
        {{-- Akhir Grid Event --}}

    </div>
@endsection