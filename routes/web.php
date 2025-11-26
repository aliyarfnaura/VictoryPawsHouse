<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LayananPublikController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\EventPublikController;
use App\Http\Controllers\UlasanPublikController;
use App\Http\Controllers\Admin\AdminController;


Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/layanan', [LayananPublikController::class, 'index'])->name('layanan.publik.index');
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/event', [EventPublikController::class, 'index'])->name('event.index');
Route::get('/review', [UlasanPublikController::class, 'index'])->name('review.index');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'is.customer'])->group(function () {

    Route::get('/dashboard-user', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard', ['user' => Auth::user()]);
    })->name('dashboard');

    // Booking
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/check-slots', [BookingController::class, 'checkSlots'])->name('booking.checkSlots');

    // Payment
    Route::get('/payment/{id}', [BookingController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/upload-bukti', [BookingController::class, 'uploadBukti'])->name('payment.uploadBukti');

    // Profile & Tabs
    Route::get('/profile/{tab?}', [ProfileController::class, 'index'])
        ->where('tab', 'profile|riwayat|ulasan')
        ->name('profile.index');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ulasan CRUD
    Route::post('/review/store', [ProfileController::class, 'storeReview'])->name('review.store');
    Route::put('/review/{id}', [ProfileController::class, 'updateReview'])->name('review.update');
    Route::delete('/review/{id}', [ProfileController::class, 'destroyReview'])->name('review.destroy');
});


// ================= ADMIN ROUTES =================
Route::middleware(['auth', 'is.admin'])->prefix('admin')->group(function () {

    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Booking
    Route::get('/booking', [AdminController::class, 'manageBooking'])->name('admin.booking.index');
    Route::put('/booking/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.booking.updateStatus');
    Route::get('/booking/{id}/pdf', [AdminController::class, 'printPDF'])->name('admin.booking.pdf');
    Route::get('/booking/pdf-all', [AdminController::class, 'printAllPDF'])->name('admin.booking.pdf_all');

    // Pembayaran
    Route::get('/pembayaran', [AdminController::class, 'managePembayaran'])->name('admin.pembayaran.index');
    Route::put('/pembayaran/{id}/verify', [AdminController::class, 'verifyPayment'])->name('admin.pembayaran.verify');
    Route::post('/pembayaran/admin-upload', [AdminController::class, 'adminUploadBukti'])->name('admin.pembayaran.upload');
    Route::post('/pembayaran/store-cash', [AdminController::class, 'storeCashPayment'])->name('admin.pembayaran.storeCash');
    Route::delete('/pembayaran/{id}', [AdminController::class, 'destroyPayment'])->name('admin.pembayaran.destroy');

    // Katalog
    Route::get('/katalog', [AdminController::class, 'manageKatalog'])->name('admin.katalog.index');
    Route::get('/katalog/edit/{id_produk}', [AdminController::class, 'manageKatalog'])->name('admin.katalog.edit');
    Route::post('/katalog', [AdminController::class, 'storeUpdateKatalog'])->name('admin.katalog.store');
    Route::put('/katalog/{id_produk}', [AdminController::class, 'storeUpdateKatalog'])->name('admin.katalog.update');
    Route::delete('/katalog/{id_produk}', [AdminController::class, 'destroyKatalog'])->name('admin.katalog.destroy');

    // Event
    Route::get('/event/{id_event?}', [AdminController::class, 'manageEvent'])->name('admin.event.index');
    Route::post('/event', [AdminController::class, 'storeUpdateEvent'])->name('admin.event.store');
    Route::put('/event/{id_event}', [AdminController::class, 'storeUpdateEvent'])->name('admin.event.update');
    Route::delete('/event/{id_event}', [AdminController::class, 'destroyEvent'])->name('admin.event.destroy');

    // Ulasan
    Route::get('/ulasan', [AdminController::class, 'manageUlasan'])->name('admin.ulasan.index');
    Route::put('/ulasan/{id}/reply', [AdminController::class, 'replyUlasan'])->name('admin.ulasan.reply');
    Route::delete('/ulasan/{id}', [AdminController::class, 'destroyUlasan'])->name('admin.ulasan.destroy');
});
