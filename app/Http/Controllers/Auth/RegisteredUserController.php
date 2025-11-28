<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:20'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/',
                'max:255', 
                'unique:'.Pengguna::class,
                
                // --- VALIDASI TAMBAHAN  ---
                // 1. Tidak boleh ada Huruf Besar
                function ($attribute, $value, $fail) {
                    if (preg_match('/[A-Z]/', $value)) {
                        $fail('*Email harus menggunakan huruf kecil semua.');
                    }
                // 2. Cek Karakter Terlarang (Selain a-z, 0-9, . dan @)
                    // Jika ada karakter SELAIN yang diizinkan, maka error.
                    if (!preg_match('/^[a-z0-9.@]+$/', $value)) {
                        $fail('*Email tidak boleh mengandung simbol (seperti -, _, +, dll) selain titik.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Pengguna::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect ke Dashboard User (Sesuai alur aplikasi Anda)
        return redirect()->route('dashboard'); 
    }
}