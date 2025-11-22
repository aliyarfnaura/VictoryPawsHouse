<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
// use App\Models\TransaksiProduk; // <-- HAPUS MODEL INI
use App\Models\Booking; // <-- GUNAKAN MODEL INI
use App\Models\Ulasan;
use App\Models\Pengguna;

class ProfileController extends Controller
{
    /**
     * Menampilkan Dashboard/Profile Pelanggan dengan konten dinamis (3 Tabs).
     */
    public function index(Request $request, $tab = 'profile'): View
    {
        $user = $request->user();
        $data = [];

        // Ambil data yang dibutuhkan berdasarkan tab
        if ($tab === 'riwayat' || $tab === 'ulasan') {
            // KOREKSI UTAMA: Ambil riwayat dari tabel BOOKING
            $transactions = Booking::where('id_pengguna', $user->id_pengguna)
                                                    ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu dibuat
                                                    ->get();
                                                    
            $data['transactions'] = $transactions;
            
            // Asumsi status 'Selesai' dan 'Menunggu Pembayaran'
            $data['completed_transactions'] = $transactions->where('status', 'Selesai');
        }

        if ($tab === 'ulasan') {
            // Ambil ulasan yang pernah dibuat pengguna (jika diperlukan)
            $data['reviews'] = Ulasan::where('id_pengguna', $user->id_pengguna)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        }
        
        // Kirim semua data ke View utama
        return view('customer.profile.index', [
            'user' => $user,
            'tab' => $tab,
            'data' => $data
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validasi data yang masuk (disesuaikan dengan skema tabel 'pengguna' Anda)
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            // Menggunakan PK kustom id_pengguna untuk menghindari error unique email
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 
                        Rule::unique('pengguna', 'email')->ignore($user->id_pengguna, 'id_pengguna')], 
            'nomor_hp' => ['nullable', 'string', 'max:15'], 
            'alamat' => ['nullable', 'string', 'max:255'], 
            'kota' => ['nullable', 'string', 'max:100'], 
        ]);
        
        // Simpan perubahan
        $user->fill($request->only('username', 'email', 'nomor_hp', 'alamat', 'kota'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Redirect kembali ke tab profile setelah update
        return Redirect::route('profile.index', ['tab' => 'profile'])->with('success', 'Profile berhasil diperbarui!');
    }
}