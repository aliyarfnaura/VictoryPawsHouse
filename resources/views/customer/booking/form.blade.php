@extends('layouts.app')

@section('title', 'Form Booking Layanan')

@section('content')
<div class="bg-[#F8F4E1] shadow max-w-8xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <div class="bg-[#e7d4b4] rounded-xl shadow-2xl overflow-hidden md:grid md:grid-cols-3">

        <div class="md:col-span-2 p-8 md:p-12">
            <h2 class="text-3xl font-extrabold text-[#6b4423] mb-6">Select Service(s)</h2>
            <p class="text-gray-700 mb-8">Please select the service you're interested in and fill out the form</p>

            <form action="{{ route('booking.store') }}" method="POST" id="booking-form">
                @csrf

                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <strong class="font-bold">Gagal Booking!</strong>
                    <span class="block sm:inline">Mohon perbaiki error berikut:</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- 1. PILIH LAYANAN (CHECKBOX) --}}
                <div class="mb-8">
                    <label class="block text-sm font-bold text-[#6b4423] mb-3">Select Service *</label>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($layanan as $item)
                        <label class="cursor-pointer relative group">
                            <input type="checkbox"
                                name="id_layanan[]"
                                value="{{ $item->id_layanan }}"
                                class="peer sr-only service-checkbox"
                                data-harga="{{ $item->harga }}"
                                data-nama="{{ strtolower($item->nama_layanan) }}">

                            <div class="flex flex-col items-center p-4 border-2 rounded-xl transition duration-200 
                                            hover:border-[#6b4423] hover:bg-white 
                                            peer-checked:border-[#6b4423] peer-checked:bg-[#eddcd2] peer-checked:ring-2 peer-checked:ring-[#6b4423]">

                                <img src="{{ asset('images/' . $item->gambar) }}" alt="{{ $item->nama_layanan }}" class="w-12 h-12 mb-2 object-contain">
                                <span class="text-sm font-bold text-gray-700">{{ $item->nama_layanan }}</span>
                                <span class="text-xs text-[#6b4423] mt-1">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    {{ str_contains(strtolower($item->nama_layanan), 'hotel') ? '/malam' : '' }}
                                </span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('id_layanan') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- 2. INPUT DATA DIRI --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                    {{-- Nama Anda --}}
                    <div>
                        <label for="nama_anda" class="block text-sm font-bold text-[#6b4423] mb-1">Nama Anda *</label>
                        <input type="text" name="nama_anda" id="nama_anda" required value="{{ Auth::user()->username }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423]">
                    </div>

                    {{-- Nama Hewan --}}
                    <div>
                        <label for="nama_hewan" class="block text-sm font-bold text-[#6b4423] mb-1">Nama Hewan *</label>
                        <input type="text" name="nama_hewan" id="nama_hewan" required
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423]">
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label for="nomor_hp" class="block text-sm font-bold text-[#6b4423] mb-1">Nomor HP *</label>
                        <input type="text" name="nomor_hp" id="nomor_hp" required
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423]">
                    </div>

                    {{-- Jenis Hewan --}}
                    <div>
                        <label for="jenis_hewan" class="block text-sm font-bold text-[#6b4423] mb-1">Jenis Hewan *</label>
                        <select name="jenis_hewan" id="jenis_hewan" required
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423]">
                            <option value="">Pilih Jenis</option>
                            <option value="Anjing">Anjing</option>
                            <option value="Kucing">Kucing</option>
                        </select>
                    </div>

                    {{-- Jadwal (Check-in) --}}
                    <div>
                        <label for="jadwal" class="block text-sm font-bold text-[#6b4423] mb-1">Jadwal (Check-in) *</label>
                        <input type="text" name="jadwal" id="jadwal" required placeholder="Pilih Tanggal..."
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423] bg-white">
                    </div>

                    {{-- Gender Hewan --}}
                    <div>
                        <label for="gender_hewan" class="block text-sm font-bold text-[#6b4423] mb-1">Jenis Kelamin Hewan *</label>
                        <select name="gender_hewan" id="gender_hewan" required
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423]">
                            <option value="">Pilih Gender</option>
                            <option value="Jantan">Jantan</option>
                            <option value="Betina">Betina</option>
                        </select>
                    </div>

                    {{-- Checkout (Hidden Default) --}}
                    <div id="checkout-field" class="hidden">
                        <label for="jadwal_checkout" class="block text-sm font-bold text-[#6b4423] mb-1">Durasi (Check-out) *</label>
                        <input type="text" name="jadwal_checkout" id="jadwal_checkout" placeholder="Pilih Tanggal..."
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423] bg-white" disabled>
                    </div>

                    {{-- Jam Booking (Khusus Grooming/Service) --}}
                    <div id="time-field" class="hidden transition-all duration-300">
                        <label for="jam_booking" class="block text-sm font-bold text-[#6b4423] mb-1">
                            Pilih Jam Kedatangan *
                        </label>

                        <select name="jam_booking" id="jam_booking"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423] bg-white">
                            <option value="">-- Pilih Tanggal Dulu --</option>
                        </select>

                        <p class="text-xs text-gray-500 mt-1" id="slot-info">*Silakan pilih tanggal check-in untuk melihat jam tersedia.</p>
                    </div>

                    <div>
                        <label for="metode_pembayaran" class="block text-sm font-bold text-[#6b4423] mb-1">Pilih Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" required
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423]">
                            <option value="">Pilih Metode</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>

                    {{-- Catatan --}}
                    <div class="col-span-1 md:col-span-2">
                        <label for="catatan" class="block text-sm font-bold text-[#6b4423] mb-1">Catatan</label>
                        <label for="catatan" class="block text-sm font-regular text-[#6b4423] mb-1">*Jika memesan layanan home service, WAJIB mengisi alamat lengkap di catatan</label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-[#6b4423] focus:border-[#6b4423] placeholder-gray-400"
                            placeholder="Contoh: Hewan saya memiliki alergi kulit, mohon hati-hati di bagian kaki kanan, alamat lengkap, etc"></textarea>
                    </div>
                </div>

                <input type="hidden" name="total_harga" id="hidden-total" value="0">
            </form>
        </div>

        <div class="md:col-span-1 p-8 bg-[#c0a880] text-white flex flex-col justify-between">
            <div>
                <h2 class="text-3xl font-extrabold mb-6">Contact Us</h2>
                <div class="space-y-4 text-sm">
                    <p>We're open for any suggestion or just to have a chat!</p>
                    <p class="flex items-center space-x-2"><span class="text-lg">📞</span><span>Phone: 08111511050</span></p>
                    <p class="flex items-center space-x-2"><span class="text-lg">📧</span><span>Email: VictoryPawsHouse@gmail.com</span></p>
                    <p class="flex items-start space-x-2"><span class="text-lg">📍</span><span>Address: Jl. Veteran no.11,13,15,17, RT.7/RW.1, Kota Banjarmasin, Kalimantan Selatan</span></p>
                </div>
            </div>

            <div>
                {{-- Total Estimasi --}}
                <div class="mt-12 p-4 bg-white bg-opacity-30 rounded-xl text-lg font-bold text-gray-800 flex justify-between shadow-inner mb-4">
                    <span>Total Estimasi:</span>
                    <span id="total-estimasi-harga">Rp 0</span>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" form="booking-form"
                    class="w-full bg-[#6b4423] text-white font-bold py-3 rounded-xl shadow-lg hover:bg-[#4a3719] transition duration-300">
                    Booking Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 overflow-y-auto py-10">
    {{-- Tambahkan 'overflow-hidden my-auto' agar pas di tengah --}}
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 relative overflow-hidden my-auto">

        <div class="bg-green-600 p-5 text-center relative">
            {{-- Hiasan background pattern halus --}}
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_1px_1px,#fff_1px,transparent_0)] bg-[size:10px_10px]"></div>

            <div class="relative z-10">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-white mb-3 shadow-md ring-4 ring-green-500 ring-opacity-50">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white tracking-wide uppercase">Booking Berhasil!</h3>
                <p class="text-green-100 text-sm mt-1">
                    No. Booking: <span class="font-mono font-bold bg-green-700 px-2 py-0.5 rounded">{{ session('booking_id') }}</span>
                </p>
            </div>
        </div>

        <div class="p-6 bg-[#f8f9fa]">

            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm mb-6">
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 tracking-wider border-b pb-2">Ringkasan Pesanan</h4>
                <div class="space-y-2 mb-4">
                    @if(session('detail_items'))
                    @foreach(session('detail_items') as $item)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 font-medium">{{ $item['nama'] }}</span>
                        <span class="text-gray-900 font-bold">Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-dashed border-gray-300 bg-gray-50 -mx-4 -mb-4 px-4 py-3 rounded-b-xl">
                    <span class="text-base font-bold text-gray-700">Total Yang Harus Dibayar</span>
                    <span class="text-xl font-extrabold text-[#6b4423]">
                        Rp {{ number_format(session('total_bayar'), 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Hanya muncul jika total bayar > 0 dan metode bukan Cash --}}
            @if(session('total_bayar') > 0)
            <div class="mb-6">
                <h4 class="text-sm font-bold text-[#6b4423] uppercase mb-3 text-center md:text-left flex items-center before:flex-1 before:border-t before:border-gray-300 before:me-4 after:flex-1 after:border-t after:border-gray-300 after:ms-4">
                    <span class="whitespace-nowrap bg-white px-2 rounded-full border border-gray-200">Instruksi Pembayaran</span>
                </h4>

                {{-- Container Flexbox Berdampingan (Responsif) --}}
                <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center bg-white p-4 rounded-xl border-2 border-[#6b4423] border-dashed relative">
                    <span class="absolute -top-3 left-4 bg-[#6b4423] text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Penting</span>

                    {{-- KOLOM KIRI: Informasi Rekening (Teks) --}}
                    <div class="flex-1 space-y-3">
                        {{-- Info Bank BCA --}}
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100 hover:shadow-md transition-shadow">
                            {{-- Logo Bank (Gunakan CDN untuk contoh) --}}
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-8 w-auto mr-4 object-contain p-1 bg-white rounded">
                            <div>
                                <p class="text-[10px] text-blue-800 font-bold uppercase tracking-wider">Bank Transfer (BCA)</p>
                                {{-- GANTI NOMOR REKENING ASLI DI SINI --}}
                                <div class="flex items-center gap-2">
                                    <p class="text-lg font-black text-gray-800 tracking-widest font-mono selection:bg-blue-200" id="rek-bca">1234567890</p>
                                    {{-- Tombol Copy Kecil --}}
                                    <button type="button" onclick="copyText('rek-bca')" class="text-gray-400 hover:text-[#6b4423]" title="Salin">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">a.n Victory Paws House</p>
                            </div>
                        </div>

                        {{-- Info E-Wallet (Dana/Gopay) --}}
                        <div class="flex items-center p-3 bg-sky-50 rounded-lg border border-sky-100 hover:shadow-md transition-shadow">
                            {{-- Logo DANA (Gunakan CDN) --}}
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="DANA" class="h-8 w-auto mr-4 object-contain p-1 bg-white rounded">
                            <div>
                                <p class="text-[10px] text-sky-800 font-bold uppercase tracking-wider">E-Wallet (Dana/OVO)</p>
                                {{-- GANTI NOMOR DANA ASLI DI SINI --}}
                                <div class="flex items-center gap-2">
                                    <p class="text-lg font-black text-gray-800 tracking-widest font-mono selection:bg-sky-200" id="no-dana">08123456789</p>
                                    <button type="button" onclick="copyText('no-dana')" class="text-gray-400 hover:text-[#6b4423]" title="Salin">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">a.n Admin Victory</p>
                            </div>
                        </div>
                    </div>

                    {{-- PEMBATAS TENGAH (GARIS PUTUS DI DESKTOP) --}}
                    <div class="hidden md:block w-px bg-gray-300 self-stretch border-r border-dashed"></div>

                    {{-- KOLOM KANAN: QR Code (Gambar) --}}
                    <div class="shrink-0 flex flex-col items-center justify-center text-center md:w-1/3">
                        <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm inline-block relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-tr from-[#6b4423] to-green-500 rounded-xl blur opacity-30 group-hover:opacity-70 transition duration-1000"></div>

                            {{-- Placeholder QR Code (Contoh) --}}
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=ContohQRISVictoryPawsHouse&bgcolor=ffffff&color=222222&margin=5"
                                alt="Scan QRIS"
                                class="relative w-36 h-36 object-contain rounded-lg border border-gray-100"
                                title="Scan untuk membayar">
                        </div>
                        <p class="text-xs font-bold text-gray-700 mt-3 uppercase tracking-wider flex items-center justify-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#6b4423]" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zm-2 7a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zm7-9a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V4zm2 2V5h1v1h-1zM9 4a1 1 0 011-1h1a1 1 0 110 2H10a1 1 0 01-1-1zm-2 8a1 1 0 00-1 1v1a1 1 0 102 0v-1a1 1 0 00-1-1zm6 0a1 1 0 00-1 1v1a1 1 0 102 0v-1a1 1 0 00-1-1zm-4 3a1 1 0 110-2 1 1 0 010 2zm2-1a1 1 0 110-2 1 1 0 010 2zM9 11a1 1 0 011-1h1a1 1 0 110 2H10a1 1 0 01-1-1zm3-3a1 1 0 100-2 1 1 0 000 2zm-3 7a1 1 0 110-2 1 1 0 010 2zm1 2a1 1 0 110-2 1 1 0 010 2zm2 0a1 1 0 110-2 1 1 0 010 2zM5 6h1v1H5V6zm1 1h1v1H6V7zM7 6h1v1H7V6zm1 1h1v1H8V7zM5 8h1v1H5V8zm1 1h1v1H6V9zM7 8h1v1H7V8zm1 1h1v1H8V9z" clip-rule="evenodd" />
                            </svg>
                            Scan QRIS
                        </p>
                        <p class="text-[10px] text-gray-500">Support semua E-Wallet & M-Banking</p>
                    </div>
                </div>
                <p class="text-center text-xs text-gray-500 mt-2">Silakan lakukan pembayaran sesuai total tagihan di atas.</p>
            </div>
            @endif

            {{-- Hanya muncul jika total bayar > 0 --}}
            @if(session('total_bayar') > 0)
            <div class="mt-6">
                <h4 class="text-sm font-bold text-gray-700 uppercase mb-3 text-center md:text-left">
                    Konfirmasi Pembayaran
                </h4>
                <form id="form-upload-bukti" enctype="multipart/form-data" class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    @csrf
                    <input type="hidden" name="id_booking" value="{{ session('booking_id') }}">

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Transfer/QRIS <span class="text-red-500">*</span></label>
                        <div class="flex items-center justify-center w-full group">
                            <label for="file-upload" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 group-hover:bg-gray-100 group-hover:border-[#6b4423] transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-[#6b4423] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <p class="mb-1 text-sm text-gray-500 group-hover:text-gray-700"><span class="font-bold">Klik untuk pilih file</span> gambar</p>
                                    <p class="text-xs text-gray-400" id="file-name-preview">PNG, JPG, JPEG (Maks. 2MB)</p>
                                </div>
                                <input id="file-upload" name="bukti_gambar" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="showFileName(this)" required />
                            </label>
                        </div>
                    </div>

                    <button type="submit" id="btn-upload" class="w-full bg-[#6b4423] text-white font-bold py-3.5 rounded-xl shadow-md hover:bg-[#5a391d] hover:shadow-lg transition-all duration-300 flex justify-center items-center gap-2 text-lg active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Kirim Bukti & Selesai</span>
                    </button>
                </form>
            </div>
            @endif

            <button type="button" onclick="closeModal()" class="mt-4 block w-full text-gray-500 text-center text-sm font-medium hover:text-[#6b4423] hover:underline py-2 transition-colors">
                @if(session('total_bayar') > 0)
                Nanti Saja, Saya Bayar Cash (Di Tempat)
                @else
                Tutup
                @endif
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_orange.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookedDates = @json($fullyBookedDates ?? []);
        const showModal = @json(session("success_popup") ? true : false);

        console.log("Tanggal Dibooking:", bookedDates);

        const maxDate = new Date();
        maxDate.setMonth(maxDate.getMonth() + 2);
        const checkinInput = document.getElementById("jadwal");
        const checkoutInput = document.getElementById("jadwal_checkout");
        const fpCheckout = flatpickr(checkoutInput, {
            minDate: "today",
            maxDate: maxDate,
            dateFormat: "Y-m-d",
            disable: bookedDates,
            onChange: function(selectedDates, dateStr, instance) {
                checkoutInput.dispatchEvent(new Event('change'));
            }
        });

        const fpCheckin = flatpickr(checkinInput, {
            minDate: "today",
            maxDate: maxDate,
            dateFormat: "Y-m-d",
            disable: bookedDates,
            onChange: function(selectedDates, dateStr, instance) {
                if (fpCheckout) {
                    fpCheckout.set('minDate', dateStr);
                }
                fetchAvailableSlots(dateStr);
                checkinInput.dispatchEvent(new Event('change'));
            }
        });

        if (showModal) {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.firstElementChild.classList.add('scale-100');
                    modal.firstElementChild.classList.remove('scale-95');
                }, 10);
            }
        }

        const checkboxes = document.querySelectorAll('.service-checkbox');
        const checkoutField = document.getElementById('checkout-field');
        const totalSpan = document.getElementById('total-estimasi-harga');
        const hiddenTotalInput = document.getElementById('hidden-total');
        const timeField = document.getElementById('time-field');
        const timeInput = document.getElementById('jam_booking');
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });

        function calculateTotal() {
            let total = 0;
            let isHotelSelected = false;
            let isGroomingSelected = false;

            checkboxes.forEach(chk => {
                if (chk.checked) {
                    let harga = parseFloat(chk.getAttribute('data-harga'));
                    let nama = chk.getAttribute('data-nama');

                    if (nama.includes('hotel')) {
                        isHotelSelected = true;

                        const checkin = new Date(checkinInput.value);
                        const checkout = new Date(checkoutInput.value);

                        if (checkinInput.value && checkoutInput.value && !isNaN(checkin) && !isNaN(checkout)) {
                            if (checkout > checkin) {
                                const diffTime = Math.abs(checkout - checkin);
                                const durasiMalam = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                total += (harga * durasiMalam);
                            }
                        }
                    } else {
                        total += harga;
                        isGroomingSelected = true;
                    }
                }
            });

            if (isGroomingSelected) {
                timeField.classList.remove('hidden');
                timeInput.required = true;
            } else {
                timeField.classList.add('hidden');
                timeInput.required = false;
                timeInput.value = '';
            }

            if (isHotelSelected) {
                checkoutField.classList.remove('hidden');
                checkoutInput.required = true;
                checkoutInput.disabled = false;
            } else {
                checkoutField.classList.add('hidden');
                checkoutInput.required = false;
                checkoutInput.disabled = true;
                checkoutInput.value = '';
            }

            totalSpan.textContent = formatter.format(total);
            hiddenTotalInput.value = total;
        }

        function fetchAvailableSlots(dateStr) {
            const selectJam = document.getElementById('jam_booking');
            const slotInfo = document.getElementById('slot-info');

            selectJam.innerHTML = '<option value="">Loading...</option>';
            selectJam.disabled = true;

            fetch(`/booking/check-slots?date=${dateStr}`)
                .then(response => response.json())
                .then(data => {
                    selectJam.innerHTML = '<option value="">-- Pilih Jam --</option>';

                    if (data.length === 0) {
                        selectJam.innerHTML = '<option value="">Tidak ada slot tersedia</option>';
                    }

                    data.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.time;
                        if (slot.available) {
                            option.text = slot.time;
                        } else {
                            option.text = slot.time + ' (Penuh)';
                            option.disabled = true;
                            option.style.color = '#ccc';
                        }
                        selectJam.appendChild(option);
                    });

                    selectJam.disabled = false;
                    slotInfo.innerText = "*Jam yang penuh tidak dapat dipilih.";
                })
                .catch(error => {
                    console.error('Error:', error);
                    selectJam.innerHTML = '<option value="">Gagal memuat jam</option>';
                });
        }

        checkboxes.forEach(chk => chk.addEventListener('change', calculateTotal));
        checkinInput.addEventListener('change', calculateTotal);
        checkoutInput.addEventListener('change', calculateTotal);
    });

    function closeModal() {
        const modal = document.getElementById('successModal');
        modal.classList.add('hidden');
    }

    function showFileName(input) {
        const fileName = input.files[0]?.name;
        const previewText = document.getElementById('file-name-preview');
        if (fileName) {
            previewText.textContent = fileName;
            previewText.classList.add('text-[#6b4423]', 'font-bold');
        }
    }

    function copyText(elementId) {
        var copyText = document.getElementById(elementId);
        navigator.clipboard.writeText(copyText.innerText).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Nomor berhasil disalin ke clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const uploadForm = document.getElementById('form-upload-bukti');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const btnUpload = document.getElementById('btn-upload');
                const originalText = btnUpload.innerHTML;

                btnUpload.disabled = true;
                btnUpload.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengupload...`;

                const formData = new FormData(this);

                fetch("{{ route('payment.uploadBukti') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#6b4423',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    closeModal();
                                    window.location.href = "{{ route('booking.index') }}";
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message,
                                confirmButtonColor: '#6b4423'
                            });
                            btnUpload.disabled = false;
                            btnUpload.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan sistem. Silakan coba lagi. Harap masukkan berupa gambar dengan format PNG, JPG, atau JPEG dan ukuran maksimal 2MB.',
                            confirmButtonColor: '#6b4423'
                        });
                        btnUpload.disabled = false;
                        btnUpload.innerHTML = originalText;
                    });
            });
        }
    });
</script>
@endpush
@endsection