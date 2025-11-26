<h2 class="text-3xl font-extrabold text-[#6b4423] mb-8">ULASAN & PENILAIAN</h2>

<div class="space-y-12">

    <div>
        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2 uppercase tracking-wide border-b pb-2">
            <span>📝</span> Menunggu Ulasan Anda
            @if(count($data['pending_reviews']) > 0)
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ count($data['pending_reviews']) }}</span>
            @endif
        </h3>

        @if(count($data['pending_reviews']) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($data['pending_reviews'] as $booking)
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-yellow-200 relative overflow-hidden transition hover:shadow-md">
                        <div class="absolute top-0 left-0 w-1 h-full bg-yellow-400"></div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-mono text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">#{{ $booking->id_booking }}</span>
                            <span class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($booking->jadwal)->format('d M Y') }}</span>
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg truncate mb-1">
                            {{ $booking->details->first()->layanan->nama_layanan ?? 'Layanan' }}
                        </h4>
                        <p class="text-sm text-gray-500 mb-4">{{ $booking->nama_hewan }}</p>
                        
                        <button onclick="openReviewModal({{ $booking->id_booking }}, '{{ $booking->details->first()->layanan->nama_layanan ?? 'Layanan' }}')" 
                            class="w-full bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold py-2.5 rounded-lg transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            Beri Nilai
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-gray-500 text-sm font-medium">Semua pesanan selesai sudah Anda ulas.</p>
            </div>
        @endif
    </div>

    <div>
        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2 uppercase tracking-wide border-b pb-2">
            <span>⭐</span> Ulasan Saya
        </h3>

        <div class="space-y-4">
            @forelse($data['history_reviews'] as $review)
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow pr-4">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-bold text-gray-800 text-lg">
                                    {{ $review->booking->details->first()->layanan->nama_layanan ?? 'Layanan' }}
                                </h4>
                                <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded text-gray-500">#{{ $review->id_booking }}</span>
                            </div>
                            
                            <p class="text-xs text-gray-400 mb-3 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Diulas pada {{ \Carbon\Carbon::parse($review->created_at)->format('d F Y') }}
                            </p>
                            
                            {{-- Tampilan Bintang --}}
                            <div class="flex text-yellow-400 mb-3">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= $review->rating) 
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else 
                                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> 
                                    @endif
                                @endfor
                            </div>

                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-sm text-gray-700 italic">
                                "{{ $review->komentar ?? 'Tidak ada komentar.' }}"
                            </div>

                            {{-- [BARU] BALASAN ADMIN --}}
                            @if($review->balasan)
                                <div class="mt-3 ml-4 bg-blue-50 p-3 rounded-lg border-l-4 border-blue-400 shadow-sm">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Admin</span>
                                        <span class="text-xs text-gray-500">Membalas</span>
                                    </div>
                                    <p class="text-sm text-gray-800 leading-relaxed">
                                        "{{ $review->balasan }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- TOMBOL AKSI (EDIT & DELETE) --}}
                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end gap-3">
                        {{-- Tombol Edit --}}
                        <button onclick="openEditModal({{ $review->id_ulasan }}, {{ $review->rating }}, '{{ addslashes($review->komentar) }}')" 
                                class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-800 font-bold py-1.5 px-3 rounded-lg flex items-center gap-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </button>

                        {{-- Tombol Hapus (BARU) --}}
                        <form action="{{ route('review.destroy', $review->id_ulasan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-800 font-bold py-1.5 px-3 rounded-lg flex items-center gap-1 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-gray-300">
                    <p class="text-gray-500 text-sm">Belum ada ulasan yang Anda berikan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL CREATE REVIEW --}}
<div id="reviewModal" class="fixed inset-0 z-[60] flex items-center justify-center hidden bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-100 transition-transform">
        <div class="bg-yellow-400 p-4 flex justify-between items-center">
            <h3 class="text-lg font-bold text-yellow-900 flex items-center gap-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Beri Ulasan
            </h3>
            <button onclick="document.getElementById('reviewModal').classList.add('hidden')" class="text-yellow-900 hover:text-white text-2xl font-bold">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">Layanan: <span id="review-service-name" class="font-bold text-[#6b4423]"></span></p>
            <form action="{{ route('review.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_booking" id="review-booking-id">
                <div class="flex flex-row-reverse justify-center gap-2 mb-6">
                    @for($i=5; $i>=1; $i--)
                        <input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" class="peer hidden" required />
                        <label for="star{{$i}}" class="text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 cursor-pointer text-2xl transition-colors transform hover:scale-110">★</label>
                    @endfor
                </div>
                <textarea name="komentar" class="w-full border-gray-300 rounded-lg p-3 focus:ring-yellow-400 focus:border-yellow-400 mb-4" rows="3" placeholder="Ceritakan pengalaman Anda..."></textarea>
                <button type="submit" class="w-full bg-[#6b4423] text-white font-bold py-3 rounded-xl shadow hover:bg-[#5a391d] transition">Kirim Ulasan</button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT REVIEW --}}
<div id="editReviewModal" class="fixed inset-0 z-[60] flex items-center justify-center hidden bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-100 transition-transform">
        <div class="bg-blue-500 p-4 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">Edit Ulasan</h3>
            <button onclick="document.getElementById('editReviewModal').classList.add('hidden')" class="text-white/80 hover:text-white text-2xl font-bold">&times;</button>
        </div>
        <div class="p-6">
            <form id="form-edit-review" method="POST">
                @csrf
                @method('PUT')
                
                <div class="flex flex-row-reverse justify-center gap-2 mb-6">
                    @for($i=5; $i>=1; $i--)
                        <input type="radio" id="edit-star{{$i}}" name="rating" value="{{$i}}" class="peer hidden" required />
                        <label for="edit-star{{$i}}" class="text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 cursor-pointer text-2xl transition-colors transform hover:scale-110">★</label>
                    @endfor
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Komentar</label>
                    <textarea name="komentar" id="edit-komentar" rows="3" class="w-full border-gray-300 rounded-lg p-3 focus:ring-blue-400 focus:border-blue-400"></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl shadow hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openReviewModal(bookingId, serviceName) {
        document.getElementById('review-booking-id').value = bookingId;
        document.getElementById('review-service-name').innerText = serviceName;
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function openEditModal(id, rating, komentar) {
        const form = document.getElementById('form-edit-review');
        form.action = "/review/" + id; 
        
        // Reset radio buttons
        document.querySelectorAll('input[name="rating"]').forEach(r => r.checked = false);
        
        const starRadio = document.getElementById('edit-star' + rating);
        if (starRadio) starRadio.checked = true;

        document.getElementById('edit-komentar').value = komentar;
        document.getElementById('editReviewModal').classList.remove('hidden');
    }
    
    // Close modal on outside click
    window.onclick = function(event) {
        const modals = ['reviewModal', 'editReviewModal'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (event.target == modal) modal.classList.add('hidden');
        });
    }
</script>