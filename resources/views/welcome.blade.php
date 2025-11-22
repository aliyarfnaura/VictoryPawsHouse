<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Victory Paws House - Wujudkan Peliharaan Sehat dan Ceria!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">

    <!-- Header atas -->
    <div class="bg-[#6b4423] text-white text-sm py-2">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo_ig.png') }}" alt="paw" class="w-5 h-5">
                <a href="https://instagram.com/victorypawshouse" target="_blank" class="hover:underline">@victorypawshouse</a>
                <img src="{{ asset('images/logo_wa.png') }}" alt="wa" class="w-5 h-5">
                <span> 08111511050</span>
            </div>

            <div class="flex items-center space-x-4 font-bold uppercase">
                @auth
                    {{-- JIKA SUDAH LOGIN --}}
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm">HELLO, {{ strtoupper(Auth::user()->username) }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <span class="text-sm">|</span>
                        <button type="submit" class="hover:underline text-sm ml-2">LOGOUT</button>
                    </form>
                @endauth
                
                @guest
                    {{-- JIKA BELUM LOGIN --}}
                    <a href="{{ route('login') }}" class="hover:underline text-sm">LOGIN</a>
                    <span class="text-sm">|</span>
                    <a href="{{ route('register') }}" class="hover:underline">SIGN UP</a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Navbar utama -->
    <nav class="bg-[#f5e8d0] shadow">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-20">
            <!-- Logo dan alamat -->
            <div class="flex items-start space-x-3">
    <!-- Gambar Paw -->
    <img src="{{ asset('images/paw.png') }}" alt="paw" class="w-10 h-10 mt-1">

    <!-- Teks Nama & Alamat -->
    <div>
        <div class="text-2xl font-bold text-gray-900">VICTORY PAWSHOUSE</div>
        <p class="text-xs text-gray-700 leading-tight">
            GROOMING & PET CARE BANJARMASIN <br>
            <b>Jl. Veteran no.11,13,15,17, RT.7/RW.1, Kota Banjarmasin, Kalimantan Selatan</b>
        </p>
    </div>
</div>
            <!-- Menu navigasi -->
            <div class="flex space-x-6 text-base font-medium">
                @php $isActive = Request::is('/') || Request::routeIs('home'); @endphp
                    <a href="/" 
                    class="@if ($isActive) 
                                text-gray-900 border-b-2 border-[#6b4423] pb-1 
                            @else 
                                text-gray-700 hover:text-gray-900 
                            @endif">Home</a>
                    @php $isActive = Request::routeIs('layanan.publik.index'); @endphp
                    <a href="{{ route('layanan.publik.index') }}" 
                    class="@if ($isActive) 
                                text-gray-900 border-b-2 border-[#6b4423] pb-1 
                            @else 
                                text-gray-700 hover:text-gray-900 
                            @endif">Layanan</a>
                <a href="#" class="text-gray-700 hover:text-gray-900">Katalog</a>
                <a href="{{ route('booking.index') }}" class="text-gray-700 hover:text-gray-900">Booking</a>
                <a href="#" class="text-gray-700 hover:text-gray-900">Event</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative hero-bg">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-6 py-24 flex items-center">
            <div class="max-w-2xl text-left">
                <h1 class="text-4xl font-extrabold text-black mb-4">
                    Wujudkan Peliharaan Sehat dan Ceria!
                </h1>
                <p class="text-gray-800 text-base mb-6">
                    Mari berikan yang terbaik untuk hewan kesayangan Anda di Victory Paws House.
                    Kami menawarkan perawatan grooming, penginapan, dan aktivitas menyenangkan.
                    Dapatkan kemudahan booking dan belanja kebutuhan hewan hanya dengan satu klik.
                </p>

                <div class="flex space-x-4">
                    <a href="#"
                       class="px-6 py-2 border-2 border-[#6b4423] text-[#6b4423] font-semibold rounded hover:bg-[#6b4423] hover:text-white transition">
                       LIHAT RATING
                    </a>
                    <a href="{{ route('layanan.publik.index') }}"
                       class="px-6 py-2 bg-[#6b4423] text-white font-semibold rounded hover:bg-[#52341a] transition">
                       LIHAT LAYANAN
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
