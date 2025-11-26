<h2 class="text-3xl font-extrabold text-[#6b4423] mb-8">PROFILE PENGGUNA</h2>

<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="grid grid-cols-2 gap-x-8 gap-y-4">

        {{-- Nama Pengguna (Username) --}}
        <div class="col-span-2">
            <label for="username" class="block text-sm font-semibold text-gray-700">Nama Pengguna (Username)</label>
            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                class="w-full border-gray-300 rounded-lg shadow-sm p-3 mt-1">
            @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                class="w-full border-gray-300 rounded-lg shadow-sm p-3 mt-1">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        
        {{-- Phone Number --}}
        <div>
            <label for="no_telp" class="block text-sm font-semibold text-gray-700">Nomor HP</label>
            <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $user->no_telp) }}" 
                   class="w-full border-gray-300 rounded-lg shadow-sm p-3 mt-1">
            @error('no_telp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-8 flex justify-end space-x-4">
        <button type="submit" class="bg-[#6b4423] text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:bg-[#4a3719]">
            Update Profile
        </button>
    </div>
</form>