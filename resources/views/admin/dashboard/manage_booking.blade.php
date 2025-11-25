@extends('layouts.admin')

@section('title', 'Manajemen Booking')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Booking</h1>

    {{-- TOMBOL CETAK SEMUA --}}
    <a href="{{ route('admin.booking.pdf_all') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Cetak Laporan Full
    </a>
</div>

{{-- Pesan Sukses --}}
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
    {{ session('success') }}
</div>
@endif

<div class="p-6 bg-white rounded-xl shadow-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Status</th> {{-- Judul Kolom Diubah --}}
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">#{{ $booking->id_booking }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $booking->nama }}
                        <div class="text-xs text-gray-400">{{ $booking->nomor_hp }}</div>
                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <ul class="list-disc list-inside text-xs">
                            @foreach($booking->details as $detail)
                            <li>{{ $detail->layanan->nama_layanan ?? '-' }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#6b4423]">
                        Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                    </td>
                    {{-- JADWAL --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex flex-col">
                            <span>Check-in: {{ \Carbon\Carbon::parse($booking->jadwal)->format('d M Y') }}</span>
                            @if($booking->tanggal_checkout)
                            <span class="text-xs text-gray-400">Out: {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M Y') }}</span>
                            @endif
                            @if($booking->jam_booking)
                            <span class="text-xs text-orange-600 font-bold">Jam: {{ \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') }}</span>
                            @endif
                        </div>
                    </td>
                    {{-- FITUR STATUS DROPDOWN (INTERAKTIF) --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('admin.booking.updateStatus', $booking->id_booking) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @php
                            $bgStatus = match($booking->status) {
                            'dibayar' => 'bg-green-100 text-green-800 border-green-300', // Dibayar jadi HIJAU (Sukses)
                            'ditolak' => 'bg-red-100 text-red-800 border-red-300',
                            default => 'bg-yellow-100 text-yellow-800 border-yellow-300' // Pending
                            };
                            @endphp

                            <select name="status" onchange="this.form.submit()"
                                class="text-xs font-bold rounded-full px-3 py-1 border-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 {{ $bgStatus }}">

                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="dibayar" {{ $booking->status == 'dibayar' ? 'selected' : '' }}>Dibayar (Lunas/Selesai)</option>
                                <option value="ditolak" {{ $booking->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>

                            </select>
                        </form>
                    </td>
                    
                    

                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        {{-- TOMBOL PDF SATUAN --}}
                        <a href="{{ route('admin.booking.pdf', $booking->id_booking) }}" class="text-red-600 hover:text-red-900 flex justify-end items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">Tidak ada data booking.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection