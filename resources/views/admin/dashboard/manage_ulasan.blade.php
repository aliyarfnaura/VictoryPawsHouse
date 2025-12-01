@extends('layouts.admin')

@section('title', 'Manajemen Ulasan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Ulasan</h1>
        <p class="text-gray-600">Pantau dan balas ulasan dari pelanggan.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
        </div>
    @endif

    {{-- GRID CARD VIEW --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse ($reviews as $review)
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 flex flex-col h-full relative group">
                
                {{-- HEADER: INFO USER & TANGGAL --}}
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#6b4423] text-white flex items-center justify-center font-bold text-lg">
                            {{ substr($review->pengguna->username ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">{{ $review->pengguna->username ?? 'User Dihapus' }}</h4>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}</p>
                        </div>
                    </div>
                    
                    {{-- TOMBOL HAPUS (POJOK KANAN) --}}
                    <form action="{{ route('admin.ulasan.destroy', $review->id_ulasan) }}" method="POST" onsubmit="return confirm('Yakin hapus ulasan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Hapus Ulasan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>

                {{-- RATING & LAYANAN --}}
                <div class="mb-3">
                    <div class="flex text-yellow-400 mb-1">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $review->rating) <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @else <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endif
                        @endfor
                    </div>
                    <span class="text-xs font-bold text-[#6b4423] bg-orange-100 px-2 py-1 rounded uppercase tracking-wider">
                        {{ $review->booking->details->first()->layanan->nama_layanan ?? 'Layanan' }}
                    </span>
                </div>

                {{-- KOMENTAR USER --}}
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-gray-700 italic mb-4 flex-grow">
                    "{{ $review->komentar ?? 'Tidak ada komentar.' }}"
                </div>

                {{-- BALASAN ADMIN --}}
                @if($review->balasan)
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 mb-4 relative group-reply">
                        <p class="text-xs font-bold text-blue-800 mb-1">Balasan Admin:</p>
                        <p class="text-sm text-blue-900">{{ $review->balasan }}</p>
                        
                        {{-- Tombol Edit Balasan --}}
                        <button onclick="openReplyModal({{ $review->id_ulasan }}, '{{ addslashes($review->balasan) }}')" 
                                class="absolute top-2 right-2 text-blue-400 hover:text-blue-600 opacity-0 group-reply-hover:opacity-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                    </div>
                @endif

                {{-- TOMBOL BALAS --}}
                @if(!$review->balasan)
                    <button onclick="openReplyModal({{ $review->id_ulasan }}, '')" 
                            class="w-full mt-auto bg-[#6b4423] text-white hover:bg-[#54361c] font-bold py-2 rounded-lg transition flex justify-center items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                        Balas Ulasan
                    </button>
                @endif

            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-xl shadow-sm border-2 border-dashed border-gray-200">
                <p class="text-gray-400 text-lg">Belum ada ulasan masuk.</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL BALAS --}}
    <div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-100">
            <div class="bg-[#6b4423] p-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Balas Ulasan</h3>
                <button onclick="document.getElementById('replyModal').classList.add('hidden')" class="text-white/80 hover:text-white text-2xl">&times;</button>
            </div>
            
            <div class="p-6">
                <form id="form-reply" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Isi Balasan</label>
                        <textarea name="balasan" id="reply-content" rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-[#6b4423] focus:border-[#6b4423]" placeholder="Tulis balasan Anda di sini..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('replyModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 rounded-lg font-bold text-gray-700 hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#6b4423] text-white rounded-lg font-bold hover:bg-[#54361c]">Kirim Balasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReplyModal(id, currentReply) {
            document.getElementById('form-reply').action = "/admin/ulasan/" + id + "/reply";
            document.getElementById('reply-content').value = currentReply;
            document.getElementById('replyModal').classList.remove('hidden');
            document.getElementById('replyModal').classList.add('flex');
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('replyModal');
            if (event.target == modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
    
    <style>
        .group-reply:hover button { opacity: 1; }
    </style>
@endsection