<header>
    <nav class="bg-[#F8F4E1] shadow" style="border-bottom: 5px solid #543310;">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-start sm:items-center h-auto py-3 sm:h-20 gap-3">

            <div class="flex items-start space-x-3">
                <img src="{{ asset('images/paw.png') }}" alt="paw" class="w-10 h-10 mt-1">

                <div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">
                        VICTORY PAWSHOUSE
                    </div>

                    <p class="text-xs text-gray-700 leading-tight">
                        GROOMING & PET CARE BANJARMASIN
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 font-bold uppercase text-sm">
                <span>HELLO, {{ strtoupper(Auth::user()->username) }}</span>

                <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-2">
                    @csrf
                    <span>|</span>
                    <button type="submit" class="hover:underline">
                        LOGOUT
                    </button>
                </form>
            </div>

        </div>
    </nav>
</header>
