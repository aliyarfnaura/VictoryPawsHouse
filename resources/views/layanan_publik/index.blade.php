@extends('layouts.app') 
@section('title', 'Daftar Layanan Kami') 
@section('content')
    <main class="bg-[#F8F4E1] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- 1. Grooming & Styling (Layanan) --}}
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border-2 border-gray-100 transform hover:scale-[1.02] transition duration-300">
                <img src="{{ asset($grooming->gambar ?? 'images/placeholder_grooming.jpg') }}" alt="Grooming & Styling" class="w-full h-48 object-cover">
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-custom-brown mb-2">Grooming & Styling</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ $grooming->deskripsi ?? 'Perawatan bulu, kulit, kuku, dan telinga oleh groomer profesional untuk kesehatan dan penampilan prima.' }}
                    </p>
                    <a href="{{ route('booking.index') }}" class="bg-custom-brown text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-300 transition duration-300">
                        Booking Sekarang
                    </a>
                </div>
            </div>

            {{-- 2. Pet Hotel (Layanan) --}}
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border-2 border-gray-100 transform hover:scale-[1.02] transition duration-300">
                <img src="{{ asset($hotel->gambar ?? 'images/placeholder_hotel.jpg') }}" alt="Pet Hotel" class="w-full h-48 object-cover">
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-custom-brown mb-2">Pet Hotel</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ $hotel->deskripsi ?? 'Penginapan aman dan nyaman dengan fasilitas lengkap. Peliharaan terjamin terawat selama Anda berpergian.' }}
                    </p>
                    <a href="{{ route('booking.index') }}" class="bg-custom-brown text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-300 transition duration-300">
                        Booking Sekarang
                    </a>
                </div>
            </div>

            {{-- 3. Home Service (Layanan) --}}
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border-2 border-gray-100 transform hover:scale-[1.02] transition duration-300">
                <img src="{{ asset($home_service->gambar ?? 'images/placeholder_homeservice.jpg') }}" alt="Home Service" class="w-full h-48 object-cover">
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-custom-brown mb-2">Home Service</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ $home_service->deskripsi ?? 'Layanan grooming langsung ke rumah Anda. Praktis, cepat, dan nyaman!' }}
                    </p>
                    <a href="{{ route('booking.index') }}" class="bg-custom-brown text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-300 transition duration-300">
                        Booking Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            
            {{-- 4. Pet Exhibition (Event) --}}
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border-2 border-gray-100 flex flex-col transform hover:scale-[1.02] transition duration-300">
                <img src="{{ asset($event_display->gambar ?? 'images/placeholder_event.jpg') }}" alt="Pet Exhibition" class="w-full h-64 object-cover">
                <div class="p-6 flex-grow text-center">
                    <h3 class="text-xl font-bold text-custom-brown mb-2">Pet Exhibition</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Ajeng pameran dan kontes menarik. More info on Instagram <a href="https://instagram.com/victorypawshouse" class="text-blue-600 hover:underline">@victorypawshouse</a>
                    </p>
                    <a href="{{ route('event.index') }}" class="bg-custom-brown text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-300 transition duration-300">
                        Lihat Detail Event
                    </a>
                </div>
            </div>

            {{-- 5. Pet Supplies (Produk) --}}
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border-2 border-gray-100 flex flex-col transform hover:scale-[1.02] transition duration-300">
                <img src="{{ asset('images/placeholder_supplies.jpg') }}" alt="Pet Supplies" class="w-full h-64 object-cover">
                <div class="p-6 flex-grow text-center">
                    <h3 class="text-xl font-bold text-custom-brown mb-2">Pet Supplies</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Untuk kebutuhan hewan kesayangan Anda.
                    </p>
                    <a href="{{ route('katalog.index') }}" class="bg-custom-brown text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-300 transition duration-300">
                        Lihat Sekarang
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection