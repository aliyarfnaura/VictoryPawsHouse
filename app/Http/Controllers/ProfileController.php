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
        /** @var \App\Models\User $user */
        $user = $request->user(); 
        $data = [];

        if (!in_array($tab, ['profile', 'riwayat', 'ulasan'])) {
            $tab = 'profile'; 
        }

        if ($tab === 'riwayat') {
            $transactions = Booking::where('id_pengguna', $user->id_pengguna)
                                   ->with(['details.layanan', 'pembayaran']) 
                                   ->orderBy('created_at', 'desc')
                                   ->get();
            $data['transactions'] = $transactions;
        }

        if ($tab === 'ulasan') {
            $data['pending_reviews'] = Booking::where('id_pengguna', $user->id_pengguna)
                ->whereIn('status', ['dibayar', 'selesai']) 
                ->doesntHave('ulasan')
                ->with(['details.layanan'])
                ->orderBy('created_at', 'desc')
                ->get();

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

    //edit
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $user = $request->user();

        $user->fill($validatedData);
        
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.index', ['tab' => 'profile'])->with('success', 'Profile berhasil diperbarui!');
    }
    
    //review
    public function storeReview(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|exists:booking,id_booking',
            'rating'     => 'required|integer|min:1|max:5',
            'komentar'   => 'nullable|string|max:500',
        ]);
        
        $userId = $request->user()->id_pengguna; 

        $booking = Booking::where('id_booking', $request->id_booking)
                          ->where('id_pengguna', $userId) 
                          ->firstOrFail();

        if ($booking->ulasan) {
            return back()->with('error', 'Anda sudah memberikan ulasan.');
        }

        Ulasan::create([
            'id_booking'  => $booking->id_booking,
            'id_pengguna' => $userId, 
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
        
        $userId = $request->user()->id_pengguna;

        $review = Ulasan::where('id_ulasan', $id)->where('id_pengguna', $userId)->firstOrFail();
        $review->update([
            'rating'   => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroyReview(Request $request, $id)
    {
        $userId = $request->user()->id_pengguna;
        $review = Ulasan::where('id_ulasan', $id)->where('id_pengguna', $userId)->firstOrFail();
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
        }
}