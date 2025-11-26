@extends('layouts.app')

@section('title', 'Ulasan & Rating Pelanggan')

@section('content')
<div class="bg-[#F8F4E1] max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <header class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-[#6b4423]">Ulasan & Rating</h1>
        <p class="mt-3 text-lg text-gray-700">Pendapat pelanggan membantu kami meningkatkan layanan.</p>
    </header>

    <section class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-xl border-t-4 border-[#6b4423] mb-12 
                    flex flex-col md:flex-row items-center gap-10">

        <div class="text-center md:w-1/3 md:border-r md:pr-10 border-b md:border-b-0 pb-6 md:pb-0">
            <p class="text-6xl font-extrabold text-[#6b4423]">
                {{ number_format($averageRating, 1) }}
            </p>
            <p class="text-sm text-gray-600">{{ $totalReviews }} ulasan total</p>
        </div>

        <div class="w-full md:w-2/3 md:pl-10 text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start space-x-1 text-4xl text-yellow-500 mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= round($averageRating) ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                @endfor
            </div>
            <p class="text-gray-700 font-semibold">Kami berkomitmen memberikan layanan terbaik.</p>
        </div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        @forelse ($reviews as $review)

            <article class="bg-[#fcf8f0] p-6 rounded-xl shadow-md border border-gray-200 
                            hover:border-[#6b4423] transition-all duration-300 flex flex-col">

                {{-- HEADER --}}
                <div class="mb-4 border-b border-gray-200 pb-4">

                    {{-- Tanggal --}}
                    <span class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">
                        {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}
                    </span>

                    {{-- Nama User --}}
                    <h3 class="text-lg font-semibold text-gray-800 mt-1 line-clamp-1">
                        {{ $review->pengguna->username ?? 'Pengguna' }}
                    </h3>

                    {{-- Layanan --}}
                    <p class="text-xs text-[#6b4423] font-bold mt-1">
                        {{ $review->booking->details->first()->layanan->nama_layanan ?? 'Layanan' }}
                    </p>
                </div>

                <div class="flex items-center space-x-1 mb-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                    @endfor
                    <span class="text-xs font-bold text-gray-400 ml-1">({{ $review->rating }}.0)</span>
                </div>

                <div class="flex-grow flex flex-col gap-3">

                    <p class="text-gray-700 text-sm italic leading-relaxed">
                        "{{ $review->komentar ?? 'Tidak ada komentar.' }}"
                    </p>

                    @if($review->balasan)
                        <div class="bg-blue-50 p-3 rounded-lg border-l-4 border-blue-300 shadow-sm">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                                    Admin
                                </span>
                                <span class="text-xs text-gray-400">Membalas</span>
                            </div>
                            <p class="text-xs text-gray-700 leading-relaxed">
                                "{{ $review->balasan }}"
                            </p>
                        </div>
                    @endif
                </div>

            </article>

        @empty
            <div class="col-span-full text-center py-14 bg-white rounded-xl border-2 border-dashed border-gray-300">
                <p class="text-lg font-semibold text-gray-500">Belum ada ulasan saat ini.</p>
            </div>
        @endforelse

    </section>
</div>
@endsection
