<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Booking;
use App\Models\Ulasan;

class ProfileController extends Controller
{
    public function index(Request $request, $tab = 'profile'): View
    {
        $user = $request->user();
        $data = [];

        // TAB RIWAYAT: Hanya tampilkan booking
        if ($tab === 'riwayat') {
            $transactions = Booking::where('id_pengguna', $user->id_pengguna)
                                   ->with(['details.layanan', 'pembayaran']) 
                                   ->orderBy('created_at', 'desc')
                                   ->get();
            $data['transactions'] = $transactions;
        }

        // TAB ULASAN: Pisahkan yang belum diulas dan sudah diulas
        if ($tab === 'ulasan') {
            // 1. Menunggu Ulasan (Booking Lunas TAPI Belum Ada di Tabel Ulasan)
            $data['pending_reviews'] = Booking::where('id_pengguna', $user->id_pengguna)
                ->whereIn('status', ['dibayar', 'selesai']) // Status Lunas
                ->doesntHave('ulasan') // Belum punya ulasan
                ->with(['details.layanan'])
                ->orderBy('created_at', 'desc')
                ->get();

            // 2. Riwayat Ulasan (Yang sudah ada di tabel Ulasan)
            $data['history_reviews'] = Ulasan::where('id_pengguna', $user->id_pengguna)
                ->with(['booking.details.layanan'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('customer.profile.index', [
            'user' => $user,
            'tab' => $tab,
            'data' => $data
        ]);
    }

    /**
     * Method Edit (Opsional, untuk kompatibilitas route).
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->save();
        return Redirect::route('profile.index', ['tab' => 'profile'])->with('status', 'profile-updated');
    }

    /**
     * Hapus Akun.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // --- CRUD ULASAN ---

    public function storeReview(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|exists:booking,id_booking',
            'rating'     => 'required|integer|min:1|max:5',
            'komentar'   => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('id_booking', $request->id_booking)
                          ->where('id_pengguna', Auth::id())
                          ->firstOrFail();

        if ($booking->ulasan) {
            return back()->with('error', 'Anda sudah memberikan ulasan.');
        }

        Ulasan::create([
            'id_booking'  => $booking->id_booking,
            'id_pengguna' => Auth::id(),
            'rating'      => $request->rating,
            'komentar'    => $request->komentar,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim.');
    }

    public function updateReview(Request $request, $id)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        $review = Ulasan::where('id_ulasan', $id)->where('id_pengguna', Auth::id())->firstOrFail();
        $review->update([
            'rating'   => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroyReview($id)
    {
        $review = Ulasan::where('id_ulasan', $id)->where('id_pengguna', Auth::id())->firstOrFail();
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}