<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Victory Paws House - Wujudkan Peliharaan Sehat dan Ceria!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">

    <div class="bg-[#6b4423] text-white text-xs sm:text-sm py-2">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 gap-2">
            <div class="flex flex-wrap items-center gap-2">
                <img src="{{ asset('images/logo_ig.png') }}" alt="paw" class="w-5 h-5">
                <a href="https://instagram.com/victorypawshouse" target="_blank" class="hover:underline">@victorypawshouse</a>
                <img src="{{ asset('images/logo_wa.png') }}" alt="wa" class="w-5 h-5">
                <span>08111511050</span>
            </div>
            <div class="flex flex-wrap items-center gap-2 font-bold uppercase">
                @auth
                    <a href="{{ route('profile.index', ['tab' => 'profile']) }}" class="flex items-center gap-1 hover:underline">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        <span>HELLO, {{ strtoupper(Auth::user()->username) }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-1">
                        @csrf
                        <span>|</span>
                        <button type="submit" class="hover:underline">LOGOUT</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">LOGIN</a>
                    <span>|</span>
                    <a href="{{ route('register') }}" class="hover:underline">SIGN UP</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Navbar utama -->
    <nav class="bg-[#F8F4E1] shadow" style="border-bottom: 5px solid #543310;">
        <div class="max-w-7xl mx-auto px-4 flex flex-wrap md:flex-nowrap justify-between items-center py-4">
            <div class="flex items-start space-x-3 w-full md:w-auto">
                <img src="{{ asset('images/paw.png') }}" 
                    alt="paw" 
                    class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 mt-1">
                <div class="leading-tight">
                    <div class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">
                        VICTORY PAWSHOUSE
                    </div>

                    <p class="text-[10px] sm:text-xs md:text-xs text-gray-700 leading-tight">
                        GROOMING & PET CARE BANJARMASIN <br>
                        <b>Jl. Veteran no.11,13,15,17, RT.7/RW.1, Kota Banjarmasin, Kalimantan Selatan</b>
                    </p>
                </div>
            </div>

            <!-- Menu navigasi -->
            <div class="flex flex-wrap md:flex-nowrap gap-4 md:gap-6 text-base font-medium mt-3 md:mt-0">
                @php $isActive = Request::is('/') || Request::routeIs('home'); @endphp
                <a href="/"  
                class="@if ($isActive) text-gray-900 border-b-2 border-[#6b4423] pb-1 @else text-gray-700 hover:text-gray-900 @endif">
                Home
                </a>

                @php $isActive = Request::routeIs('layanan.publik.index'); @endphp
                <a href="{{ route('layanan.publik.index') }}"
                class="@if ($isActive) text-gray-900 border-b-2 border-[#6b4423] pb-1 @else text-gray-700 hover:text-gray-900 @endif">
                Layanan
                </a>

                <a href="{{ route('katalog.index') }}" class="text-gray-700 hover:text-gray-900">Katalog</a>
                <a href="{{ route('booking.index') }}" class="text-gray-700 hover:text-gray-900">Booking</a>
                <a href="{{ route('event.index') }}" class="text-gray-700 hover:text-gray-900">Event</a>
            </div>
        </div>
    </nav>

    <div class="relative hero-bg">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-6 py-24 flex items-center">
            <div class="max-w-2xl text-left">
                <h1 class="text-3xl font-extrabold text-black mb-4">
                    Wujudkan Peliharaan Sehat dan Ceria!
                </h1>
                <p class="text-gray-600 text-base mb-6">
                    Mari berikan yang terbaik untuk hewan kesayangan Anda di Victory Paws House.
                    Kami menawarkan perawatan grooming, penginapan, dan aktivitas menyenangkan.
                    Dapatkan kemudahan booking dan belanja kebutuhan hewan hanya dengan satu klik.
                </p>

                <div class="flex space-x-4">
                    <a href="{{ route('review.index') }}"
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