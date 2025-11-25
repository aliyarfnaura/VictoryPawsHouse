@php
use Carbon\Carbon;
@endphp

<h2 class="text-3xl font-extrabold text-[#6b4423] mb-8">RIWAYAT TRANSAKSI</h2>

<div class="space-y-6">
    @forelse ($data['transactions'] as $booking)
    <div onclick="openHistoryModal(this)"
        class="bg-[#fcf8f0] p-5 rounded-xl shadow-sm hover:shadow-md border-l-4 transition-all cursor-pointer group relative
             @if ($booking->status === 'pending') border-yellow-500 
             @elseif ($booking->status === 'dibayar') border-green-500 
             @elseif ($booking->status === 'ditolak') border-red-500 
             @else border-gray-500 @endif
             flex flex-col md:flex-row justify-between items-start md:items-center gap-4"

        {{-- DATA UNTUK JS --}}
        data-id="{{ $booking->id_booking }}"
        data-total="{{ $booking->total_harga }}"
        data-status="{{ $booking->status }}"
        {{-- LOGIKA BARU: Cek apakah pembayaran sudah ada? --}}
        data-has-payment="{{ $booking->pembayaran ? 'true' : 'false' }}"

        data-details="{{ json_encode($booking->details->map(function($detail) {
                 return [
                     'nama' => $detail->layanan->nama_layanan ?? 'Layanan Dihapus',
                     'harga' => $detail->harga_saat_ini
                 ];
             })) }}">

        <div class="flex-grow">
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold bg-gray-200 px-2 py-1 rounded text-gray-600">#{{ $booking->id_booking }}</span>

                {{-- Tampilan Status di Card --}}
                @if($booking->status == 'pending' && $booking->pembayaran)
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                    Menunggu Verifikasi
                </span>
                @else
                <span class="text-xs font-semibold px-2 py-1 rounded-full
                            @if ($booking->status === 'pending') bg-yellow-100 text-yellow-700 
                            @elseif ($booking->status === 'dibayar') bg-green-100 text-green-700 
                            @elseif ($booking->status === 'ditolak') bg-red-100 text-red-700 
                            @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($booking->status) }}
                </span>
                @endif
            </div>

            <h3 class="text-lg font-bold text-gray-800 group-hover:text-[#6b4423] transition-colors">
                @if($booking->details->count() > 0)
                {{ $booking->details->first()->layanan->nama_layanan ?? 'Layanan' }}
                @if($booking->details->count() > 1)
                <span class="text-sm font-normal text-gray-500">+ {{ $booking->details->count() - 1 }} lainnya</span>
                @endif
                @else
                {{ $booking->tipe_layanan ?? 'Layanan' }}
                @endif
            </h3>

            <p class="text-sm text-gray-600 mt-1">
                {{ Carbon::parse($booking->jadwal)->translatedFormat('d F Y') }} |
                {{ $booking->nama_hewan }} ({{ $booking->jenis_hewan }})
            </p>
        </div>

        <div class="text-left md:text-right w-full md:w-auto mt-2 md:mt-0">
            <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
            <p class="text-xl font-extrabold text-[#6b4423]">
                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
            </p>
            <div class="mt-2 text-xs text-blue-600 font-semibold flex items-center md:justify-end gap-1 group-hover:underline">
                Lihat Detail
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-gray-300">
        <p class="mt-2 text-sm text-gray-500">Belum ada riwayat transaksi.</p>
    </div>
    @endforelse
</div>

<div id="historyModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl relative overflow-hidden max-h-[90vh] overflow-y-auto">

        <div class="bg-[#6b4423] p-5 text-center relative">
            <button onclick="closeHistoryModal()" class="absolute top-4 right-4 text-white/70 hover:text-white text-2xl font-bold">&times;</button>
            <h3 class="text-xl font-bold text-white tracking-wide uppercase">Detail Transaksi</h3>
            <p class="text-orange-100 text-sm mt-1">
                No. Booking: <span class="font-mono font-bold bg-white/20 px-2 py-0.5 rounded" id="modal-booking-id">#000</span>
            </p>
        </div>

        <div class="p-6 bg-[#f8f9fa]">

            <div id="modal-alert-info" class="hidden mb-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-sm">
                <p class="font-bold">Bukti Terkirim!</p>
                <p class="text-sm">Pembayaran Anda sedang diverifikasi oleh Admin. Mohon tunggu.</p>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm mb-6">
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 tracking-wider border-b pb-2">Rincian Pesanan</h4>
                <div id="modal-items-container" class="space-y-2 mb-4"></div>
                <div class="flex justify-between items-center pt-3 border-t border-dashed border-gray-300 bg-gray-50 -mx-4 -mb-4 px-4 py-3 rounded-b-xl">
                    <span class="text-base font-bold text-gray-700">Total Tagihan</span>
                    <span class="text-xl font-extrabold text-[#6b4423]" id="modal-total-harga">Rp 0</span>
                </div>
            </div>

            <div id="modal-instruction-section" class="mb-6 hidden">
                <h4 class="text-sm font-bold text-[#6b4423] uppercase mb-3 text-center flex items-center before:flex-1 before:border-t before:border-gray-300 before:me-4 after:flex-1 after:border-t after:border-gray-300 after:ms-4">
                    <span class="whitespace-nowrap bg-white px-2 rounded-full border border-gray-200">Instruksi Pembayaran</span>
                </h4>
                <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center bg-white p-4 rounded-xl border-2 border-[#6b4423] border-dashed">
                    <div class="flex-1 space-y-3">
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="h-6 w-auto mr-3">
                            <div>
                                <p class="text-[10px] text-blue-800 font-bold uppercase">BCA</p>
                                <p class="text-sm font-black text-gray-800 tracking-widest font-mono">1234567890</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-sky-50 rounded-lg border border-sky-100">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" class="h-6 w-auto mr-3">
                            <div>
                                <p class="text-[10px] text-sky-800 font-bold uppercase">DANA</p>
                                <p class="text-sm font-black text-gray-800 tracking-widest font-mono">08123456789</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal-action-section">
                <a href="#" id="modal-btn-upload" class="block w-full bg-[#6b4423] text-white font-bold py-3 rounded-xl shadow-lg hover:bg-[#4a3719] text-center mb-3 transition hidden">
                    Upload Bukti Pembayaran
                </a>
            </div>

            <button onclick="closeHistoryModal()" class="block w-full text-gray-500 text-center text-sm font-medium hover:text-gray-800 py-2">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openHistoryModal(element) {
        // 1. Ambil Data
        const id = element.getAttribute('data-id');
        const total = parseFloat(element.getAttribute('data-total'));
        const status = element.getAttribute('data-status').toLowerCase();
        const hasPayment = element.getAttribute('data-has-payment') === 'true'; // Cek apakah sudah upload
        const details = JSON.parse(element.getAttribute('data-details'));

        // 2. Isi Data Dasar
        document.getElementById('modal-booking-id').innerText = '#' + id;
        document.getElementById('modal-total-harga').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);

        // 3. Isi Rincian Layanan
        const container = document.getElementById('modal-items-container');
        container.innerHTML = '';
        details.forEach(item => {
            const div = document.createElement('div');
            div.className = 'flex justify-between items-center text-sm border-b border-gray-100 pb-1 last:border-0';
            div.innerHTML = `
                <span class="text-gray-600">${item.nama}</span>
                <span class="text-gray-900 font-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</span>
            `;
            container.appendChild(div);
        });

        // 4. LOGIKA TAMPILAN BERDASARKAN KONDISI (Pending vs Paid vs Uploaded)
        const instructionSection = document.getElementById('modal-instruction-section');
        const btnUpload = document.getElementById('modal-btn-upload');
        const alertInfo = document.getElementById('modal-alert-info');

        // Reset Tampilan
        instructionSection.classList.add('hidden');
        btnUpload.classList.add('hidden');
        alertInfo.classList.add('hidden');

        if (status === 'pending') {
            if (hasPayment) {
                // KONDISI 2: Pending TAPI Sudah Upload -> Tampilkan Alert, Sembunyikan Upload
                alertInfo.classList.remove('hidden');
            } else {
                // KONDISI 1: Pending Murni -> Tampilkan Instruksi & Tombol Upload
                instructionSection.classList.remove('hidden');
                btnUpload.classList.remove('hidden');
                btnUpload.href = "/payment/" + id;
                btnUpload.innerHTML = "Upload Bukti Pembayaran";
                btnUpload.className = "block w-full bg-[#6b4423] text-white font-bold py-3 rounded-xl shadow-lg hover:bg-[#4a3719] text-center mb-3 transition";
            }
        } else if (status === 'dibayar' || status === 'selesai') {
            // KONDISI 3: Sudah Lunas -> Tampilkan Tombol Lihat Detail Hijau
            btnUpload.classList.remove('hidden');
            btnUpload.href = "/payment/" + id;
            btnUpload.innerHTML = "Lihat Bukti / Detail";
            btnUpload.className = "block w-full bg-green-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-green-700 text-center mb-3 transition";
        }

        // 5. Buka Modal
        document.getElementById('historyModal').classList.remove('hidden');
    }

    function closeHistoryModal() {
        document.getElementById('historyModal').classList.add('hidden');
    }
</script>