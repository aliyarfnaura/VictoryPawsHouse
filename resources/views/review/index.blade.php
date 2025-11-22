@extends('layouts.app')

@section('title', 'Ulasan & Rating Pelanggan')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <header class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-[#6b4423]">Ulasan & Rating</h1>
            <p class="mt-3 text-xl text-gray-700">Lihat pengalaman nyata pelanggan kami!</p>
        </header>

        {{-- SUMMARY RATING BOX --}}
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-2xl border-t-4 border-[#6b4423] mb-12 flex items-center">
            
            <div class="text-center w-1/3 border-r pr-8">
                <p class="text-6xl font-extrabold text-[#6b4423]">{{ $averageRating }}</p>
                <p class="text-sm text-gray-600">{{ $totalReviews }} total rating</p>
            </div>

            <div class="w-2/3 pl-8">
                {{-- Logika Tampilan Bintang (Simulasi) --}}
                <div class="flex items-center justify-center space-x-1 text-4xl text-yellow-500">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= round($averageRating))
                            <span class="text-5xl">⭐</span>
                        @else
                            <span class="text-5xl opacity-30">⭐</span>
                        @endif
                    @endfor
                </div>
                <p class="text-center mt-2 text-gray-700 font-semibold">Berdasarkan {{ $totalReviews }} ulasan.</p>
            </div>
        </div>

        {{-- GRID ULASAN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            @forelse ($reviews as $review)
                <div class="bg-[#fcf8f0] p-5 rounded-xl shadow-md border-2 border-gray-300 flex flex-col">
                    
                    {{-- User Info --}}
                    <div class="mb-3 border-b pb-3 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">{{ $review->pengguna->username ?? 'Pengguna Anonim' }}</h3>
                        {{-- Tanggal --}}
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($review->tanggal)->diffForHumans() }}</p>
                    </div>

                    {{-- Rating --}}
                    <div class="flex items-center space-x-1 mb-2">
                        <span class="text-xl font-bold text-[#6b4423]">{{ $review->rating }}/5</span>
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="text-yellow-500 text-sm">
                                @if ($i <= $review->rating)
                                    ⭐
                                @else
                                    ☆
                                @endif
                            </span>
                        @endfor
                    </div>

                    {{-- Komentar --}}
                    <p class="text-gray-700 text-sm italic flex-grow">
                        "{{ $review->komentar }}"
                    </p>
                    
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-xl font-semibold text-gray-500">Belum ada ulasan dari pelanggan.</p>
                </div>
            @endforelse
            
        </div>
        {{-- Akhir Grid Ulasan --}}

    </div>
@endsection