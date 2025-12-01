@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        
        {{-- HEADER --}}
        <div class="bg-[#6b4423] px-8 py-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Detail Pembayaran</h1>
                <p class="text-orange-100 text-sm mt-1">No. Booking: #{{ $booking->id_booking }}</p>
            </div>
            <span class="px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                {{ $booking->status == 'dibayar' ? 'bg-green-500 text-white' : 
                  ($booking->status == 'ditolak' ? 'bg-red-500 text-white' : 'bg-yellow-400 text-yellow-900') }}">
                {{ $booking->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3">
            {{-- KOLOM KIRI: RINCIAN --}}
            <div class="md:col-span-2 p-8 border-r border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Rincian Pesanan</h3>
                
                <div class="space-y-4">
                    @foreach($booking->details as $detail)
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-4">
                            {{-- Gambar Layanan --}}
                            <img src="{{ asset('images/' . ($detail->layanan->gambar ?? 'default.png')) }}" 
                                 class="w-12 h-12 object-contain">
                            <div>
                                <p class="font-bold text-gray-700">{{ $detail->layanan->nama_layanan }}</p>
                                <p class="text-xs text-gray-500">Harga satuan: Rp {{ number_format($detail->harga_saat_ini) }}</p>
                            </div>
                        </div>
                        <p class="font-bold text-[#6b4423]">Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-6 border-t border-dashed border-gray-200 flex justify-between items-center">
                    <span class="text-gray-500 font-medium">Total Tagihan</span>
                    <span class="text-2xl font-extrabold text-[#6b4423]">
                        Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                    </span>
                </div>

                {{-- TOMBOL KEMBALI --}}
                <div class="mt-8">
                    <a href="{{ route('booking.index') }}" class="text-gray-500 hover:text-[#6b4423] font-medium text-sm flex items-center gap-2">
                        ← Kembali ke Booking
                    </a>
                </div>
            </div>

            {{-- KOLOM KANAN: STATUS PEMBAYARAN & INSTRUKSI --}}
            <div class="p-8 bg-gray-50 flex flex-col justify-center items-center text-center">
                
                @if($booking->status == 'pending')
                    <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Menunggu Pembayaran</h3>
                    
                    {{-- Tampilkan Info Rekening Jika Belum Bayar --}}
                    @if(!$booking->pembayaran)
                        <p class="text-sm text-gray-500 mt-2 mb-4">Silakan transfer sesuai total tagihan:</p>
                        <div class="bg-white p-3 rounded-lg border border-gray-200 mb-6 w-full">
                            <p class="text-xs font-bold text-blue-800 uppercase">BCA</p>
                            <p class="text-lg font-mono font-black text-gray-800">1234567890</p>
                            <p class="text-[10px] text-gray-500">a.n Victory Paws</p>
                        </div>
                        
                        {{-- TOMBOL TRIGGER MODAL UPLOAD (SUDAH DIPERBAIKI) --}}
                        <button onclick="openUploadModal()" 
                                class="w-full bg-[#6b4423] text-white font-bold py-3 rounded-xl shadow-lg hover:bg-[#4a3719] transition active:scale-95">
                            Upload Bukti Bayar
                        </button>
                    @else
                        <div class="mt-4 bg-blue-100 text-blue-700 p-3 rounded-lg text-sm w-full">
                            <strong>Bukti Terkirim!</strong><br>
                            Sedang diverifikasi admin.
                        </div>
                    @endif

                @elseif($booking->status == 'dibayar' || $booking->status == 'selesai')
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Lunas</h3>
                    <p class="text-sm text-gray-500 mt-2">Pembayaran Anda telah diterima.</p>

                @elseif($booking->status == 'ditolak')
                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Ditolak</h3>
                    <p class="text-sm text-gray-500 mt-2">Bukti pembayaran tidak valid.</p>
                    
                    {{-- Tombol Upload Ulang --}}
                    <button onclick="openUploadModal()" class="mt-4 w-full bg-[#6b4423] text-white font-bold py-2 rounded-lg hover:bg-[#4a3719]">
                        Upload Ulang
                    </button>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL POPUP UPLOAD (SAMA SEPERTI DI BOOKING) --}}
{{-- ========================================== --}}
<div id="uploadModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden">
        
        <div class="bg-[#6b4423] p-5 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Konfirmasi Pembayaran</h3>
            <button onclick="closeUploadModal()" class="text-white/70 hover:text-white text-2xl">&times;</button>
        </div>

        <div class="p-6 bg-gray-50">
            <form id="form-upload-bukti" enctype="multipart/form-data">
                @csrf
                {{-- ID Booking Diambil dari Variabel Blade --}}
                <input type="hidden" name="id_booking" value="{{ $booking->id_booking }}">

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Transfer</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="file-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="mb-1 text-sm text-gray-500"><span class="font-bold">Klik untuk upload</span></p>
                                <p class="text-xs text-gray-400" id="file-name-preview">JPG, PNG (Max 2MB)</p>
                            </div>
                            <input id="file-upload" name="bukti_gambar" type="file" class="hidden" accept="image/*" onchange="showFileName(this)" />
                        </label>
                    </div>
                </div>

                <button type="submit" id="btn-upload" class="w-full bg-[#6b4423] text-white font-bold py-3 rounded-xl shadow-lg hover:bg-[#4a3719] flex justify-center items-center gap-2 transition">
                    Kirim Bukti
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function openUploadModal() {
        document.getElementById('uploadModal').classList.remove('hidden');
    }
    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }

    function showFileName(input) {
        const fileName = input.files[0]?.name;
        const previewText = document.getElementById('file-name-preview');
        if (fileName) {
            previewText.textContent = fileName;
            previewText.classList.add('text-[#6b4423]', 'font-bold');
        }
    }

    const uploadForm = document.getElementById('form-upload-bukti');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const btnUpload = document.getElementById('btn-upload');
            const originalText = btnUpload.innerHTML;
            
            btnUpload.disabled = true;
            btnUpload.innerHTML = `Mengupload...`;

            const formData = new FormData(this);

            fetch("{{ route('payment.uploadBukti') }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!', text: data.message,
                        confirmButtonColor: '#6b4423'
                    }).then(() => {
                        closeUploadModal();
                        location.reload();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                    btnUpload.disabled = false;
                    btnUpload.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.' });
                btnUpload.disabled = false;
                btnUpload.innerHTML = originalText;
            });
        });
    }
</script>
@endsection