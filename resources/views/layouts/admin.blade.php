<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - @yield('title', 'Dashboard') | Victory Paws House</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    @include('includes.adminHeader')

    <div class="min-h-screen flex">

        <div id="sidebar" 
             class="w-64 bg-[#AF8F6F] text-white flex flex-col py-6 px-4
             fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 z-50
             lg:static lg:translate-x-0">

            <div class="p-6 text-center border-b border-gray-700 flex justify-between items-center">
                <h1 class="text-xl font-extrabold tracking-wide">DASHBOARD ADMIN</h1>

                <button id="closeSidebar" class="lg:hidden text-white text-2xl">
                    &times;
                </button>
            </div>

            <nav class="flex-grow p-4 space-y-2">
                @php
                    $navItems = [
                        'grafik' => ['Grafik & KPI', 'admin.dashboard'],
                        'booking' => ['Manajemen Booking', 'admin.booking.index'],
                        'pembayaran' => ['Manajemen Pembayaran', 'admin.pembayaran.index'],
                        'katalog' => ['Manajemen Katalog', 'admin.katalog.index'],
                        'event' => ['Manajemen Event', 'admin.event.index'],
                        'ulasan' => ['Manajemen Ulasan', 'admin.ulasan.index'],
                    ];
                    $currentRoute = Route::currentRouteName();
                @endphp

                @foreach ($navItems as $key => $item)
                    @php
                        $isActive = (Str::contains($currentRoute, $key) && $key !== 'grafik') 
                                || ($currentRoute === 'admin.dashboard' && $key === 'grafik');
                    @endphp
                    
                    <a href="{{ route($item[1]) }}"
                       class="flex items-center space-x-3 p-3 rounded-lg transition duration-200
                       @if ($isActive)
                            !bg-[#543310] text-white font-semibold
                       @else
                            hover:bg-[#8b6a4b]
                       @endif">
                        <span>{{ $item[0] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden z-40 lg:hidden"></div>

        <div class="flex-1 flex flex-col overflow-hidden">

            <button id="openSidebar"
                class="lg:hidden p-3 absolute top-4 right-4 bg-[#AF8F6F] text-white rounded-md shadow-md">
                &#9776;
            </button>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#F8F4E1] p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
    </script>
</body>
</html>
