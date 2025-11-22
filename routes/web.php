<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayananPublikController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\BookingController;
// use App\Http\Controllers\Admin\LayananController; // <-- Hapus atau komen dulu jika belum dibuat

// Halaman Home
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/layanan', [LayananPublikController::class, 'index'])->name('layanan.publik.index');

// Login/Sign Up (Route di dalam auth.php)
require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    // API untuk cek slot waktu yang tersedia
    Route::get('/booking/check-slots', [BookingController::class, 'checkSlots'])->name('booking.checkSlots');

    Route::get('/dashboard-user', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Form Booking Grooming
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index'); // Tampilkan form
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store'); // Proses submit
    Route::get('/booking/check-slots', [BookingController::class, 'checkSlots'])->name('booking.checkSlots');
    Route::get('/payment/{id}', [BookingController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/upload-bukti', [BookingController::class, 'uploadBukti'])->name('payment.uploadBukti');
});

Route::middleware(['auth', 'is.admin'])->prefix('admin')->group(function () {

    // ADMIN DASHBOARD
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Nanti kita tambahkan CRUD Layanan Admin di sini
});