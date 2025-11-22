<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Victory Paws House - Sign Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

 <!-- Header atas -->
<div class="bg-[#6b4423] text-white text-sm py-2">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4">
        <!-- Kiri: IG & WA -->
        <div class="flex items-center space-x-3">
            <!-- IG -->
            <div class="flex items-center space-x-1">
                <img src="{{ asset('images/logo_ig.png') }}" alt="IG" class="w-5 h-5">
                <a href="https://instagram.com/victorypawshouse" target="_blank" class="hover:underline">@victorypawshouse</a>
                <span class="font-semibold text-sm">victorypawshouse</span>
            </div>
            <!-- WA -->
            <div class="flex items-center space-x-1">
                <img src="{{ asset('images/logo_wa.png') }}" alt="WA" class="w-5 h-5">
                <span class="text-sm">08111511050</span>
            </div>
        </div>

        <!-- Kanan: LOGIN & SIGN UP PAGE -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="hover:underline text-sm">LOGIN</a>
            <span class="text-sm">|</span>
            <a href="/register" class="hover:underline text-sm font-bold uppercase">SIGN UP PAGE</a>
        </div>
    </div>
</div>


    <!-- MAIN CONTAINER (2 Kolom) -->
<div class="min-h-screen flex flex-col md:flex-row">
    <!-- KOLOM KIRI: Branding & Gambar -->
    <div class="md:w-2/5 flex flex-col justify-center items-center bg-[#F8F4E1] p-8">
        <!-- Logo & teks -->
        <a href="/" class="flex items-center space-x-4 mb-12">
            <img src="{{ asset('images/paw.png') }}" alt="paw" class="w-12 h-12">
            <div class="flex flex-col">
                <span class="text-4xl font-extrabold text-gray-800">VICTORY</span>
                <span class="text-4xl font-extrabold text-gray-800 -mt-2">PAWSHOUSE</span>
                <span class="text-sm text-gray-600 mt-2">GROOMING & PET CARE BANJARMASIN</span>
            </div>
        </a>
       <img src="{{ asset('images/kucing_anjing.png') }}" alt="dog" class="w-full max-w-sm rounded-xl">
    </div>

    <!-- KOLOM KANAN: Form Sign Up -->
    <div class="md:w-3/5 flex flex-col justify-center items-center p-8 bg-[#AF8F6F]">
        <div class="w-full max-w-md">
            <h1 class="text-4xl md:text-5xl font-extrabold text-[#F8F4E1] mb-10 text-center">SIGN UP</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-6">
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username"
                           placeholder="Username"
                           class="w-full bg-[#F8F4E1]/80 border-none rounded-full p-4 text-gray-800 text-lg shadow focus:ring-2 focus:ring-[#6b4423]">
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <div class="mb-6">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           placeholder="Email"
                           class="w-full bg-[#F8F4E1]/80 border-none rounded-full p-4 text-gray-800 text-lg shadow focus:ring-2 focus:ring-[#6b4423]">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-8">
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           placeholder="Password"
                           class="w-full bg-[#F8F4E1]/80 border-none rounded-full p-4 text-gray-800 text-lg shadow focus:ring-2 focus:ring-[#6b4423]">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mb-8">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           placeholder="Konfirmasi Password"
                           class="w-full bg-[#F8F4E1]/80 border-none rounded-full p-4 text-gray-800 text-lg shadow focus:ring-2 focus:ring-[#6b4423]">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <input type="hidden" name="role" value="pelanggan">

                <button type="submit"
                        class="w-full bg-[#6b4423] text-white font-bold py-3 rounded-full shadow hover:bg-[#4a3719] transition duration-300">
                    Let's Start!
                </button>

                <div class="text-center mt-6 text-gray-700">
                    Already have an account? 
                    <a class="underline text-[#F8F4E1] hover:text-gray-900 font-bold" href="{{ route('login') }}">
                        Log In
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
