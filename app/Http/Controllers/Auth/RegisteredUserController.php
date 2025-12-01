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
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:'.Pengguna::class, 

                function ($attribute, $value, $fail) {
                    if (preg_match('/[A-Z]/', $value)) {
                        $fail('*Email harus menggunakan huruf kecil semua (tidak boleh ada huruf kapital).');
                        return;
                    }
                    if (!preg_match('/^[a-z0-9.]+@gmail\.com$/', $value)) {
                        $fail('*Email wajib menggunakan domain @gmail.com dan tidak boleh mengandung simbol selain titik.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'username.required'  => '*Nama pengguna wajib diisi.',
            'username.regex'     => '*Username tidak boleh mengandung simbol dan angka (hanya huruf ).',
            'username.max'       => '*Username maksimal 20 karakter.',
            
            'email.required'     => '*Email wajib diisi.',
            'email.email'        => '*Format email tidak valid.',
            'email.unique'       => '*Email ini sudah terdaftar, silakan gunakan email lain atau login.',
            
            'password.required'  => '*Password wajib diisi.',
            'password.confirmed' => '*Konfirmasi password tidak cocok.',
        ]);

        $user = Pengguna::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pelanggan',
        ]);

        event(new Registered($user));
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login menggunakan akun baru Anda.');
    }
}