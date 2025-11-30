<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            // Validasi Username: Wajib, String, Maks 20, Regex (Hanya Huruf & Angka)
            'username' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z0-9]+$/'],
            
            // Validasi Email: Wajib, String, Format Email Valid, Maks 255, Unik di tabel Pengguna
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:'.Pengguna::class, // Pastikan Model dan Tabel sesuai
                
                // --- VALIDASI KHUSUS GMAIL & FORMAT KETAT ---
                function ($attribute, $value, $fail) {
                    // 1. Cek apakah ada Huruf Besar (Kapital)
                    if (preg_match('/[A-Z]/', $value)) {
                        $fail('*Email harus menggunakan huruf kecil semua (tidak boleh ada huruf kapital).');
                        return; // Berhenti jika gagal di sini
                    }

                    // 2. Cek Struktur: (hurufkecil/angka/titik) + @gmail.com
                    // Regex ini memastikan:
                    // ^ : Awal string
                    // [a-z0-9.]+ : Hanya boleh huruf kecil a-z, angka 0-9, dan titik (.)
                    // @gmail\.com$ : Harus diakhiri persis dengan @gmail.com
                    if (!preg_match('/^[a-z0-9.]+@gmail\.com$/', $value)) {
                        $fail('*Email wajib menggunakan domain @gmail.com dan tidak boleh mengandung simbol selain titik.');
                    }
                },
            ],

            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            // --- PESAN ERROR CUSTOM (BAHASA INDONESIA) ---
            'username.required'  => '*Nama pengguna wajib diisi.',
            'username.regex'     => '*Username tidak boleh mengandung simbol atau spasi (hanya huruf dan angka).',
            'username.max'       => '*Username maksimal 20 karakter.',
            
            'email.required'     => '*Email wajib diisi.',
            'email.email'        => '*Format email tidak valid.',
            'email.unique'       => '*Email ini sudah terdaftar, silakan gunakan email lain atau login.',
            
            'password.required'  => '*Password wajib diisi.',
            'password.confirmed' => '*Konfirmasi password tidak cocok.',
        ]);

        // Buat Data Pengguna Baru
        $user = Pengguna::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pelanggan', // Role default
        ]);

        // Trigger Event Registered (untuk verifikasi email jika diaktifkan nanti)
        event(new Registered($user));

        // --- ALUR SETELAH REGISTER ---
        
        // 1. HAPUS baris ini agar user TIDAK langsung login otomatis
        // Auth::login($user);

        // 2. REDIRECT ke Halaman Login dengan Pesan Sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login menggunakan akun baru Anda.');
    }
}