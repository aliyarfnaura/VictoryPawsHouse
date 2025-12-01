<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Victory Paws House - Sign Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#F8F4E1] overflow-x-hidden">
    <header class="bg-[#6b4423] text-white text-sm py-2">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center px-4 gap-3">

            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-1">
                    <img src="{{ asset('images/logo_ig.png') }}" alt="IG" class="w-5 h-5">
                    <span class="font-semibold text-sm">@victorypawshouse</span>
                </div>

                <div class="flex items-center space-x-1">
                    <img src="{{ asset('images/logo_wa.png') }}" alt="WA" class="w-5 h-5">
                    <span class="text-sm">08111511050</span>
                </div>
            </div>

            <nav class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hover:underline text-sm whitespace-nowrap">LOGIN</a>
                <span>|</span>
                <a href="{{ route('register') }}" class="hover:underline text-sm font-bold uppercase whitespace-nowrap">SIGN UP PAGE</a>
            </nav>
        </div>
    </header>

    <main class="min-h-screen flex flex-col md:flex-row">
        <section class="md:w-2/5 flex flex-col items-center justify-center bg-[#F8F4E1] p-10">
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

        <section class="md:w-3/5 bg-[#AF8F6F] flex items-center justify-center p-10">
            <div class="w-full max-w-md">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-[#F8F4E1] text-center mb-10 tracking-wide">
                    SIGN UP
                </h1>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-6">
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required
                               placeholder="Username"
                               class="w-full bg-[#F8F4E1]/80 rounded-full p-4 text-gray-800 text-lg shadow outline-none focus:ring-2 focus:ring-[#6b4423]">
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               placeholder="Email"
                               class="w-full bg-[#F8F4E1]/80 rounded-full p-4 text-gray-800 text-lg shadow outline-none focus:ring-2 focus:ring-[#6b4423]">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <input id="password" type="password" name="password" required
                               placeholder="Password"
                               class="w-full bg-[#F8F4E1]/80 rounded-full p-4 text-gray-800 text-lg shadow outline-none focus:ring-2 focus:ring-[#6b4423]">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-8">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               placeholder="Konfirmasi Password"
                               class="w-full bg-[#F8F4E1]/80 rounded-full p-4 text-gray-800 text-lg shadow outline-none focus:ring-2 focus:ring-[#6b4423]">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <input type="hidden" name="role" value="pelanggan">

                    <button type="submit"
                            class="w-full bg-[#6b4423] text-white font-bold py-3 rounded-full shadow hover:bg-[#4a3719] transition">
                        Let's Start!
                    </button>

                    <p class="text-center mt-6 text-[#F8F4E1]">
                        Already have an account?
                        <a href="{{ route('login') }}" class="underline hover:text-gray-200 font-bold">
                            Log In
                        </a>
                    </p>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
