<!-- FILE: resources/views/partials/_header_navbar.blade.php -->
<!-- Digunakan di dalam layout app.blade.php -->
<header>
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
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <img src="{{ asset('images/paw.png') }}" 
                    alt="paw"
                    class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10">
                <div>
                    <div class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 leading-tight">
                        VICTORY PAWSHOUSE
                    </div>
                    <p class="text-[10px] sm:text-xs text-gray-700 leading-tight">
                        GROOMING & PET CARE BANJARMASIN
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-4 md:gap-6 text-base font-medium mt-3 md:mt-0">
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