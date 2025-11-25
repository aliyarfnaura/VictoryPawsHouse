@extends('layouts.app')

@section('title', 'Ulasan & Rating Pelanggan')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <header class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-[#6b4423]">Ulasan & Rating</h1>
            <p class="mt-3 text-xl text-gray-700">Lihat pengalaman nyata pelanggan kami!</p>
        </header>

        {{-- SUMMARY RATING BOX --}}
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-2xl border-t-4 border-[#6b4423] mb-12 flex flex-col md:flex-row items-center justify-center gap-8">
            
            <div class="text-center md:w-1/3 md:border-r md:pr-8 border-b md:border-b-0 pb-6 md:pb-0">
                <p class="text-6xl font-extrabold text-[#6b4423]">{{ number_format($averageRating, 1) }}</p>
                <p class="text-sm text-gray-600">{{ $totalReviews }} ulasan total</p>
            </div>

            <div class="w-full md:w-2/3 md:pl-8 text-center md:text-left">
                {{-- Tampilan Bintang Rata-rata --}}
                <div class="flex items-center justify-center md:justify-start space-x-1 text-4xl text-yellow-500 mb-2">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= round($averageRating))
                            <span>★</span>
                        @else
                            <span class="text-gray-300">★</span>
                        @endif
                    @endfor
                </div>
                <p class="text-gray-700 font-semibold">Kepuasan pelanggan adalah prioritas kami.</p>
            </div>
        </div>

        {{-- GRID ULASAN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            @forelse ($reviews as $review)
                <div class="bg-[#fcf8f0] p-5 rounded-xl shadow-md border-2 border-gray-200 hover:border-[#6b4423] transition-all duration-300 flex flex-col h-full">
                    
                    {{-- HEADER CARD (Layout Baru: Atas-Bawah) --}}
                    <div class="mb-3 border-b border-gray-200 pb-3">
                        
                        {{-- 1. TANGGAL (Ditaruh Paling Atas, Kecil & Abu-abu) --}}
                        <div class="flex justify-between items-start mb-1">
                             <span class="text-[11px] text-gray-400 font-medium uppercase tracking-wider">
                                {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}
                            </span>
                        </div>

                        {{-- 2. NAMA PENGGUNA --}}
                        <h3 class="text-lg font-bold text-gray-800 line-clamp-1 leading-tight">
                            {{ $review->pengguna->username ?? 'Pengguna Anonim' }}
                        </h3>

                        {{-- 3. NAMA LAYANAN --}}
                        <p class="text-xs text-[#6b4423] font-bold mt-1">
                            {{ $review->booking->details->first()->layanan->nama_layanan ?? 'Layanan' }}
                        </p>
                    </div>

                    {{-- Rating Bintang --}}
                    <div class="flex items-center space-x-1 mb-3">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="text-sm">
                                @if ($i <= $review->rating)
                                    <span class="text-yellow-500">★</span>
                                @else
                                    <span class="text-gray-300">★</span>
                                @endif
                            </span>
                        @endfor
                        <span class="text-xs font-bold text-gray-400 ml-2">({{ $review->rating }}.0)</span>
                    </div>

                    {{-- Komentar --}}
                    <div class="flex-grow flex flex-col gap-3">
    {{-- Komentar User --}}
    <p class="text-gray-700 text-sm italic relative pl-3">
        <span class="absolute left-0 top-0 text-2xl text-gray-300 leading-none">"</span>
        {{ $review->komentar ?? 'Tidak ada komentar.' }}
    </p>

    {{-- [BARU] Balasan Admin --}}
    @if($review->balasan)
        <div class="ml-4 mt-2 bg-blue-50 p-3 rounded-lg border-l-4 border-blue-300 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Admin</span>
                <span class="text-xs text-gray-400">
                    Membalas
                </span>
            </div>
            <p class="text-xs text-gray-700 leading-relaxed">
                "{{ $review->balasan }}"
            </p>
        </div>
    @endif
</div>
                    
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-xl border-2 border-dashed border-gray-300">
                    <p class="mt-2 text-lg font-semibold text-gray-500">Belum ada ulasan saat ini.</p>
                </div>
            @endforelse
            
        </div>
        {{-- Akhir Grid --}}

    </div>
@endsection