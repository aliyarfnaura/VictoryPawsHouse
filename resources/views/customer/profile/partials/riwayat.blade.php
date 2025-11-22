@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

<h2 class="text-3xl font-extrabold text-[#6b4423] mb-8">RIWAYAT TRANSAKSI</h2>

<div class="space-y-6">
    @forelse ($data['transactions'] as $booking)
        
        <div class="bg-[#fcf8f0] p-4 rounded-xl shadow border-l-4 
            @if ($booking->status === 'Pending') border-red-500 @elseif ($booking->status === 'Selesai') border-green-500 @else border-gray-500 @endif
            flex justify-between items-center">
            
            <div class="flex-grow">
                {{-- Nama Layanan --}}
                <h3 class="text-lg font-bold text-gray-800">{{ $booking->tipe_layanan }}</h3>
                
                {{-- Detail Waktu dan Item --}}
                <p class="text-sm text-gray-600">
                    {{ Carbon::parse($booking->jadwal)->translatedFormat('l, d F') }} | {{ Carbon::parse($booking->jadwal)->format('H:i') }}
                    <span class="text-xs text-gray-500 block mt-1">{{ $booking->nama_hewan }} ({{ $booking->jenis_hewan }})</span>
                </p>
                
                {{-- Status --}}
                <span class="text-xs font-semibold px-2 py-1 rounded-full mt-2 inline-block
                    @if ($booking->status === 'Pending') bg-red-200 text-red-800 @elseif ($booking->status === 'Selesai') bg-green-200 text-green-800 @else bg-yellow-200 text-yellow-800 @endif
                ">
                    {{ $booking->status }}
                </span>
            </div>
            
            {{-- Harga dan Aksi --}}
            <div class="text-right">
                <p class="text-xl font-extrabold text-[#6b4423]">
                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                </p>
                
                @if ($booking->status === 'Pending')
                    {{-- Tombol Lanjutkan Bayar --}}
                    <a href="{{ route('payment.show', $booking->id_booking) }}" class="text-sm text-indigo-600 hover:underline mt-1 block font-bold">
                        Lanjutkan Bayar
                    </a>
                @elseif ($booking->status === 'Selesai')
                    {{-- KOREKSI UTAMA: Tombol Review hanya jika Selesai, mengarahkan ke tab Ulasan --}}
                    <a href="{{ route('profile.index', ['tab' => 'ulasan', 'booking_id' => $booking->id_booking]) }}" class="text-sm bg-[#c0a880] text-white px-3 py-1 rounded-full hover:bg-[#6b4423] mt-1 inline-block font-bold">
                        Review
                    </a>
                @endif
            </div>
        </div>
    @empty
        <p class="text-center text-gray-500 py-10">Anda belum memiliki riwayat transaksi.</p>
    @endforelse
</div>