<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Victory Paws House - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#F8F4E1] overflow-x-hidden">

    <!-- HEADER -->
    <header class="bg-[#6b4423] text-white text-sm py-2">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center px-4 gap-3">

            <!-- LEFT: IG & WA -->
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-1">
                    <img src="{{ asset('images/logo_ig.png') }}" alt="IG" class="w-5 h-5">
                    <span class="font-semibold text-sm">victorypawshouse</span>
                </div>

                <div class="flex items-center space-x-1">
                    <img src="{{ asset('images/logo_wa.png') }}" alt="WA" class="w-5 h-5">
                    <span class="text-sm">08111511050</span>
                </div>
            </div>

            <!-- RIGHT: LOGIN PAGE -->
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('login') }}" class="hover:underline font-bold uppercase whitespace-nowrap">LOGIN PAGE</a>
                <span>|</span>
                <a href="{{ route('register') }}" class="hover:underline whitespace-nowrap">SIGN UP</a>
            </nav>
        </div>
    </header>

    <!-- MAIN -->
    <main class="min-h-screen flex flex-col md:flex-row">

        <!-- LEFT COLUMN -->
        <section class="md:w-2/5 bg-[#F8F4E1] flex flex-col items-center justify-center p-10">

            <!-- BRANDING: LOGO KIRI, TEKS KANAN -->
            <a href="/" class="flex items-center space-x-4 mb-12">
                <img src="{{ asset('images/paw.png') }}" alt="paw" class="w-16 h-16 md:w-20 md:h-20">

                <div class="flex flex-col leading-none">
                    <span class="text-3xl md:text-4xl font-extrabold text-gray-800">VICTORY</span>
                    <span class="text-3xl md:text-4xl font-extrabold text-gray-800 -mt-1">PAWSHOUSE</span>
                    <span class="text-xs md:text-sm text-gray-600 mt-2">GROOMING & PET CARE BANJARMASIN</span>
                </div>
            </a>

            <img src="{{ asset('images/kucing_anjing.png') }}"
                 class="w-full max-w-xs sm:max-w-sm rounded-xl object-contain"
                 alt="pets">
        </section>

        <!-- RIGHT COLUMN -->
        <section class="md:w-3/5 bg-[#AF8F6F] flex items-center justify-center p-10">
            <div class="w-full max-w-md">

                <h1 class="text-4xl sm:text-5xl font-extrabold text-[#F8F4E1] text-center mb-10 tracking-wide">
                    LOG IN
                </h1>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- EMAIL -->
                    <div class="mb-6">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               placeholder="Email"
                               class="w-full bg-[#F8F4E1]/80 rounded-full p-4 text-gray-800 text-lg shadow focus:ring-2 focus:ring-[#6b4423] outline-none">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-8">
                        <input id="password" type="password" name="password" required
                               placeholder="Password"
                               class="w-full bg-[#F8F4E1]/80 rounded-full p-4 text-gray-800 text-lg shadow focus:ring-2 focus:ring-[#6b4423] outline-none">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- SUBMIT -->
                    <button type="submit"
                            class="w-full bg-[#6b4423] text-white font-bold py-3 rounded-full shadow hover:bg-[#4a3719] transition">
                        Let's Start!
                    </button>

                    <!-- SIGN UP -->
                    <p class="text-center mt-6 text-[#F8F4E1]">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="underline hover:text-gray-200 font-bold">
                            Sign Up
                        </a>
                    </p>
                </form>

            </div>
        </section>
    </main>

</body>
</html>
