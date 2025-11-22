<h2 class="text-3xl font-extrabold text-[#6b4423] mb-8">INPUT ULASAN & RATING</h2>

@php
    // Ambil booking_id dari URL (query parameter)
    $selectedBookingId = request('booking_id');
    
    // Asumsi: Kita hanya menampilkan form jika ada transaksi yang Selesai
    $reviewableBookings = $data['transactions']->where('status', 'Selesai');
    
    // Cari booking yang sedang diulas
    $bookingToReview = $reviewableBookings->firstWhere('id_booking', $selectedBookingId);
    
    // Jika tidak ada booking yang dipilih, ambil yang pertama sebagai default
    if (!$bookingToReview && $reviewableBookings->count() > 0) {
        $bookingToReview = $reviewableBookings->first();
        $selectedBookingId = $bookingToReview->id_booking;
    }
@endphp

@if ($reviewableBookings->isEmpty())
    <div class="bg-gray-100 p-6 rounded-xl shadow-lg text-center">
        <p class="text-lg text-gray-700">Tidak ada layanan yang sudah Selesai untuk diulas. Cek tab Riwayat Anda!</p>
    </div>
@else
    <form method="POST" action="{{ route('review.store') }}" class="bg-[#fcf8f0] p-6 rounded-xl shadow-lg">
        @csrf

        {{-- Pilih Transaksi yang Sudah Selesai --}}
        <div class="mb-4">
            <label for="booking_id" class="block text-lg font-semibold text-gray-800 mb-2">Pilih Transaksi untuk Diulas</label>
            <select name="booking_id" id="booking_id" required class="w-full border-gray-300 rounded-lg p-3">
                @foreach ($reviewableBookings as $booking)
                    <option value="{{ $booking->id_booking }}" 
                        @if($booking->id_booking == $selectedBookingId) selected @endif>
                        {{ $booking->tipe_layanan }} ({{ \Carbon\Carbon::parse($booking->jadwal)->format('d M Y') }})
                    </option>
                @endforeach
            </select>
            @error('booking_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        
        {{-- Rating --}}
        <div class="mb-4">
            <label for="rating" class="block text-lg font-semibold text-gray-800 mb-2">Rating (1-5) *</label>
            <select name="rating" id="rating" required class="w-full border-gray-300 rounded-lg p-3">
                <option value="">Pilih Rating</option>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }} Bintang</option>
                @endfor
            </select>
            @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Komentar/Pesan --}}
        <div class="mb-6">
            <label for="komentar" class="block text-lg font-semibold text-gray-800 mb-2">Pesan *</label>
            <textarea name="komentar" id="komentar" rows="5" placeholder="Enter your Message" required class="w-full border-gray-300 rounded-lg p-3"></textarea>
            @error('komentar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        
        <div class="text-right">
            <button type="submit" class="bg-[#6b4423] text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:bg-[#4a3719]">
                Send Message
            </button>
        </div>
    </form>
@endif