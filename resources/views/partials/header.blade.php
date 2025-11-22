<!-- FILE: resources/views/partials/_header_navbar.blade.php -->
<!-- Digunakan di dalam layout app.blade.php -->
<header>
    <!-- Header atas (Top Bar) -->
    <div class="bg-[#6b4423] text-white text-sm py-2">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo_ig.png') }}" alt="paw" class="w-5 h-5">
                <a href="https://instagram.com/victorypawshouse" target="_blank" class="hover:underline">@victorypawshouse</a>
                <img src="{{ asset('images/logo_wa.png') }}" alt="wa" class="w-5 h-5">
                <span> 08111511050</span>
            </div>

            <!-- Kanan: Auth Links -->
            <div class="flex items-center space-x-4 font-bold uppercase">
                @auth
                     <a href="{{ route('profile.index', ['tab' => 'profile']) }}" class="flex items-center space-x-2 hover:underline text-sm ml-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm">HELLO, {{ strtoupper(Auth::user()->username) }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                        @csrf
                        <span class="text-sm">|</span>
                        <button type="submit" class="hover:underline text-sm ml-2 font-bold uppercase">LOGOUT</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">LOGIN</a> 
                    <span> | </span> 
                    <a href="{{ route('register') }}" class="hover:underline">SIGN UP</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Navbar utama -->
    <nav class="bg-[#f5e8d0] shadow">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-20">
            <!-- Logo dan alamat -->
            <div class="flex items-start space-x-3">
                <img src="{{ asset('images/paw.png') }}" alt="paw" class="w-10 h-10 mt-1">
                <div>
                    <div class="text-2xl font-bold text-gray-900">VICTORY PAWSHOUSE</div>
                    <p class="text-xs text-gray-700 leading-tight">
                        GROOMING & PET CARE BANJARMASIN <br>
                    </p>
                </div>
            </div>
            <!-- Menu navigasi -->
            <div class="flex space-x-6 text-base font-medium">
                @php 
                    $navLinks = [
                        'Home' => 'home', 
                        'Layanan' => 'layanan.publik.index', 
                        'Katalog' => 'katalog.index', 
                        'Booking' => 'booking.index', 
                        'Event' => 'event.index',
                    ];
                @endphp
                
                @foreach ($navLinks as $text => $routeName)
                    @php 
                        $isActive = Request::routeIs($routeName) || (Request::is('/') && $routeName === 'home');
                    @endphp

                    <a href="{{ route($routeName) }}" 
                       class="@if ($isActive) 
                                text-gray-900 border-b-2 border-[#6b4423] pb-1 font-bold
                            @else 
                                text-gray-700 hover:text-gray-900 
                            @endif">
                        {{ $text }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>
</header>