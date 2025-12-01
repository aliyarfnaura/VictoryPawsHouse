@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran (Card View)')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pembayaran</h1>
            <p class="text-gray-600">Kelola bukti transfer dan input pembayaran tunai manual.</p>
        </div>
        
        {{-- TOMBOL CREATE (INPUT CASH) --}}
        <button onclick="document.getElementById('createCashModal').classList.remove('hidden')" 
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow-md flex items-center gap-2 font-bold transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Input Pembayaran Tunai
        </button>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
        </div>
    @endif

    {{-- GRID CARD CONTAINER --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($payments as $payment)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col relative group-card">
                
                {{-- TOMBOL DELETE (Hanya muncul jika metode Tunai) --}}
                @if($payment->metode == 'Tunai')
                    <form action="{{ route('admin.pembayaran.destroy', $payment->id_pembayaran) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus data pembayaran tunai ini? Status booking akan kembali Pending.')"
                          class="absolute top-2 right-2 z-10">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white p-2 rounded-full shadow hover:bg-red-600 transition" title="Hapus Pembayaran">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                @endif

                {{-- GAMBAR --}}
                <div class="relative h-56 bg-gray-200 group">
                    @if ($payment->bukti_gambar)
                        <img src="{{ asset('uploads/pembayaran/' . $payment->bukti_gambar) }}" alt="Bukti" class="w-full h-full object-cover object-top">
                        <a href="{{ asset('uploads/pembayaran/' . $payment->bukti_gambar) }}" target="_blank" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm font-semibold">Lihat Full Size</span>
                        </a>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100">
                            <svg class="w-16 h-16 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14z"></path></svg>
                            <span class="text-sm font-medium">Belum Ada Bukti</span>
                        </div>
                    @endif
                    
                    {{-- Badge Status --}}
                    @php
                        $status = strtolower($payment->booking->status ?? 'pending');
                        $badgeColor = match($status) {
                            'dibayar', 'selesai' => 'bg-green-500',
                            'ditolak' => 'bg-red-500',
                            default => 'bg-yellow-500'
                        };
                    @endphp
                    <div class="absolute bottom-4 left-4 {{ $badgeColor }} text-white text-xs uppercase font-bold px-3 py-1 rounded-full shadow-sm">
                        {{ ucfirst($status) }}
                    </div>
                </div>

                {{-- INFO --}}
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-[#6b4423]">Booking #{{ $payment->id_booking }}</h3>
                                
                                {{-- [PERBAIKAN] MENAMPILKAN WAKTU REAL-TIME --}}
                                <p class="text-xs text-blue-600 font-bold flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $payment->booking->created_at->format('d M Y, H:i') }}
                                </p>
                                <p class="text-[10px] text-gray-400 italic ml-4">
                                    ({{ $payment->booking->created_at->diffForHumans() }})
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between border-b border-dashed pb-2">
                                <span class="text-gray-500">Customer</span>
                                <span class="font-semibold text-gray-800">{{ $payment->booking->nama ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between pt-1">
                                <span class="text-gray-500">Metode</span>
                                <span class="font-bold {{ $payment->metode == 'Tunai' ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $payment->metode }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- AKSI (VERIFIKASI) --}}
                    @if($payment->metode != 'Tunai')
                    <div class="mt-6 pt-4 border-t border-gray-100 flex gap-3">
                        @if ($payment->bukti_gambar)
                            <form action="{{ route('admin.pembayaran.verify', $payment->id_pembayaran) }}" method="POST" class="flex-1" onsubmit="return confirm('Terima?')">
                                @csrf @method('PUT')
                                <input type="hidden" name="action" value="accept">
                                <button type="submit" class="w-full bg-green-50 text-green-700 hover:bg-green-600 hover:text-white font-bold py-2 px-4 rounded-lg transition text-center text-sm">Terima</button>
                            </form>
                            <form action="{{ route('admin.pembayaran.verify', $payment->id_pembayaran) }}" method="POST" class="flex-1" onsubmit="return confirm('Tolak?')">
                                @csrf @method('PUT')
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="w-full bg-red-50 text-red-700 hover:bg-red-600 hover:text-white font-bold py-2 px-4 rounded-lg transition text-center text-sm">Tolak</button>
                            </form>
                        @else
                           <button onclick="openUploadModal('{{ $payment->id_pembayaran }}', '{{ e($payment->id_booking . ' - ' . $payment->booking->nama) }}')"
                                    class="w-full bg-[#6b4423] text-white hover:bg-[#54361c] font-bold py-2 px-4 rounded-lg transition shadow-md text-sm">
                                Upload Bukti
                            </button>
                        @endif
                    </div>
                    @else
                     <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <span class="text-xs text-gray-400 italic">Pembayaran Tunai (Otomatis Diterima)</span>
                     </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-xl shadow-sm">
                <p class="text-gray-500 text-lg">Belum ada data pembayaran.</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL CREATE CASH --}}
    <div id="createCashModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b bg-[#f8f9fa]">
                <h3 class="text-xl font-bold text-gray-800">Input Pembayaran Tunai</h3>
                <button onclick="document.getElementById('createCashModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.pembayaran.storeCash') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Booking (Metode Tunai)</label>
                        <select name="id_booking" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#6b4423] focus:border-[#6b4423]" required>
                            <option value="">-- Pilih Booking --</option>
                            @forelse($unpaidCashBookings as $ub)
                                <option value="{{ $ub->id_booking }}">
                                    #{{ $ub->id_booking }} - {{ $ub->nama }} (Rp {{ number_format($ub->total_harga) }})
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada booking tunai yang belum lunas.</option>
                            @endforelse
                        </select>
                        <p class="text-xs text-gray-500 mt-1">*Hanya menampilkan booking 'Tunai' yang belum dibayar.</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Struk / Nota</label>
                        <input type="file" name="bukti_struk" accept="image/*" required class="block w-full border border-gray-300 rounded-lg">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('createCashModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-bold">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#6b4423] text-white rounded-lg font-bold hover:bg-[#54361c]">Simpan & Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD ADMIN --}}
    <div id="adminUploadModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
         <div class="relative w-full max-w-md mx-auto my-6">
            <div class="border-0 rounded-2xl shadow-2xl relative flex flex-col w-full bg-white outline-none focus:outline-none">
                <div class="flex items-center justify-between p-5 border-b border-solid border-gray-200 rounded-t">
                    <h3 class="text-xl font-bold text-gray-800">Upload Bukti Manual</h3>
                    <button onclick="closeUploadModal()" class="text-black opacity-50 text-2xl">&times;</button>
                </div>
                <div class="relative p-6 flex-auto">
                    <p class="mb-4 text-gray-600 text-sm">Upload untuk: <span id="modalBookingInfo" class="font-bold text-[#6b4423]"></span></p>
                    <form action="{{ route('admin.pembayaran.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_pembayaran" id="modalPaymentId">
                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2">Pilih File</label>
                            <input type="file" name="bukti_gambar_admin" id="bukti_gambar_admin" accept="image/*" required class="block w-full border rounded-lg">
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeUploadModal()" class="px-4 py-2 bg-gray-200 rounded-lg font-bold">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-[#6b4423] text-white rounded-lg font-bold">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openUploadModal(paymentId, bookingInfo) {
            document.getElementById('modalPaymentId').value = paymentId;
            document.getElementById('modalBookingInfo').textContent = bookingInfo;
            document.getElementById('adminUploadModal').classList.remove('hidden');
            document.getElementById('adminUploadModal').classList.add('flex');
        }
        function closeUploadModal() {
            document.getElementById('adminUploadModal').classList.add('hidden');
            document.getElementById('adminUploadModal').classList.remove('flex');
            document.getElementById('bukti_gambar_admin').value = '';
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById('adminUploadModal')) closeUploadModal();
            if (event.target == document.getElementById('createCashModal')) document.getElementById('createCashModal').classList.add('hidden');
        }
    </script>
@endsection