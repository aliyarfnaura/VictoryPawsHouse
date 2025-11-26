<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Ulasan;
use App\Models\Produk;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    // --- DASHBOARD & BOOKING (Tetap Sama) ---
    public function dashboard()
    {
        $totalOrders = Booking::count();
        $totalRevenue = Booking::where('status', 'dibayar')->sum('total_harga');
        $totalPending = Booking::where('status', 'pending')->count();
        $totalCanceled = Booking::where('status', 'ditolak')->count();

        $earnings = Booking::where('status', 'dibayar')
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, SUM(total_harga) as total')
            ->groupBy('month')->pluck('total', 'month')->toArray();

        $monthlyEarnings = [];
        for ($i = 1; $i <= 12; $i++) $monthlyEarnings[] = $earnings[$i] ?? 0;

        $statusCounts = Booking::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status')->toArray();
        $statusCounts = array_change_key_case($statusCounts, CASE_LOWER);
        
        $pieData = [$statusCounts['pending'] ?? 0, $statusCounts['dibayar'] ?? 0, $statusCounts['ditolak'] ?? 0];

        $latestTransactions = Booking::with('pengguna')->latest()->limit(5)->get();

        return view('admin.dashboard.grafik', compact('totalOrders', 'totalRevenue', 'totalPending', 'totalCanceled', 'monthlyEarnings', 'pieData', 'latestTransactions'));
    }

    public function manageBooking()
    {
        $bookings = Booking::with(['details.layanan', 'pengguna'])->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard.manage_booking', compact('bookings'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,dibayar,ditolak']);
        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();
        return back()->with('success', 'Status diperbarui.');
    }

    public function printPDF($id)
    {
        $booking = Booking::with(['details.layanan', 'pengguna'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.pdf.invoice', compact('booking'));
        return $pdf->download('invoice-booking-'.$id.'.pdf');
    }

    public function printAllPDF()
    {
        $bookings = Booking::with(['details.layanan', 'pengguna'])->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('admin.pdf.laporan_semua', compact('bookings'));
        return $pdf->download('laporan-booking-semua.pdf');
    }

    // --- MANAJEMEN PEMBAYARAN (UPDATE FITUR CASH) ---

    public function managePembayaran()
    {
        // 1. Ambil Data Pembayaran
        $payments = Pembayaran::with('booking.pengguna')->orderBy('tanggal_pembayaran', 'desc')->get();
        
        // 2. Ambil Booking Tunai yg BELUM LUNAS (Untuk Dropdown Create Cash)
        $unpaidCashBookings = Booking::whereIn('metode_pembayaran', ['Tunai', 'tunai', 'Cash', 'cash'])
            ->where('status', 'pending') // Pastikan statusnya masih pending
            ->doesntHave('pembayaran')   // Pastikan belum ada di tabel pembayaran
            ->get();

        return view('admin.dashboard.manage_pembayaran', compact('payments', 'unpaidCashBookings'));
    }

    public function verifyPayment(Request $request, $id)
    {
        $payment = Pembayaran::with('booking')->findOrFail($id);
        $action = $request->input('action');

        if ($action === 'accept') {
            $payment->booking->update(['status' => 'dibayar']);
            $msg = 'Pembayaran Diterima.';
        } elseif ($action === 'reject') {
            $payment->booking->update(['status' => 'ditolak']);
            $msg = 'Pembayaran Ditolak.';
        }
        return back()->with('success', $msg ?? 'Aksi berhasil.');
    }

    public function adminUploadBukti(Request $request)
    {
        $request->validate([
            'id_pembayaran' => 'required|exists:pembayaran,id_pembayaran',
            'bukti_gambar_admin' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $pembayaran = Pembayaran::findOrFail($request->id_pembayaran);

            if ($request->hasFile('bukti_gambar_admin')) {
                $file = $request->file('bukti_gambar_admin');
                $namaFile = 'admin_' . time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/pembayaran'), $namaFile);

                if ($pembayaran->bukti_gambar && file_exists(public_path('uploads/pembayaran/' . $pembayaran->bukti_gambar))) {
                    unlink(public_path('uploads/pembayaran/' . $pembayaran->bukti_gambar));
                }

                $pembayaran->update([
                    'bukti_gambar' => $namaFile,
                    'tanggal_pembayaran' => now(),
                ]);

                return back()->with('success', 'Bukti pembayaran berhasil diupload oleh Admin.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    /**
     * [BARU] Simpan Pembayaran Tunai Manual.
     */
    public function storeCashPayment(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|exists:booking,id_booking',
            'bukti_struk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $file = $request->file('bukti_struk');
            $namaFile = 'cash_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pembayaran'), $namaFile);

            Pembayaran::create([
                'id_booking' => $request->id_booking,
                'metode'     => 'Tunai',
                'bukti_gambar' => $namaFile,
                'tanggal_pembayaran' => now(),
            ]);

            Booking::where('id_booking', $request->id_booking)->update(['status' => 'dibayar']);

            return back()->with('success', 'Pembayaran Tunai berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * [BARU] Hapus Pembayaran (Khusus Tunai).
     */
    public function destroyPayment($id)
    {
        try {
            $payment = Pembayaran::findOrFail($id);
            
            if ($payment->bukti_gambar && file_exists(public_path('uploads/pembayaran/' . $payment->bukti_gambar))) {
                unlink(public_path('uploads/pembayaran/' . $payment->bukti_gambar));
            }

            // Reset status booking ke pending
            $payment->booking()->update(['status' => 'pending']);

            $payment->delete();

            return back()->with('success', 'Data pembayaran berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // --- METHOD LAIN (Katalog, Event, Ulasan) ---
        public function manageKatalog($id_produk = null)
    {
        $products = Produk::orderBy('created_at', 'desc')->get();
        
        $productToEdit = null;
        if ($id_produk) {
            $productToEdit = Produk::findOrFail($id_produk);
        }
        return view('admin.dashboard.manage_katalog', compact('products', 'productToEdit'));
    }
    
    /**
     * Menyimpan Produk Baru atau Mengupdate Produk yang Sudah Ada.
     */
    public function storeUpdateKatalog(Request $request, $id_produk = null)
{
    $isUpdate = (bool)$id_produk;
    
    $request->validate([
        'nama_produk' => ['required', 'string', 'max:255'],
        'deskripsi' => ['required', 'string'],
        'harga' => ['required', 'numeric', 'min:0'],
        'gambar' => [$isUpdate ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ]);

    $data = $request->only(['nama_produk', 'deskripsi', 'harga']);
    
    if ($request->hasFile('gambar')) {
        // Simpan ke storage dengan disk 'public'
        $gambarPath = $request->file('gambar')->store('produk', 'public');
        $data['gambar'] = $gambarPath; // Hasil: 'produk/filename.jpg'
    }

    if ($isUpdate) {
        $product = Produk::findOrFail($id_produk);
        
        // Hapus gambar lama jika upload gambar baru
        if (isset($data['gambar']) && $product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        
        $product->update($data);
        $message = 'Produk berhasil diperbarui!';
    } else {
        $data['id_admin'] = Auth::user()->id_pengguna; 
        Produk::create($data);
        $message = 'Produk baru berhasil ditambahkan!';
    }

    return redirect()->route('admin.katalog.index')->with('success', $message);
}

public function destroyKatalog($id_produk)
{
    $product = Produk::findOrFail($id_produk);
    
    // Hapus file gambar dari storage
    if ($product->gambar) {
        Storage::disk('public')->delete($product->gambar);
    }
    
    $product->delete();
    
    return redirect()->route('admin.katalog.index')->with('success', 'Produk berhasil dihapus!');
}
    
    /**
     * Menampilkan Manajemen Event (Halaman 5 - CRUD Event).
     */
    public function manageEvent($id_event = null)
    {
        $events = Event::orderBy('tanggal', 'asc')->get();
        
        $eventToEdit = null;
        if ($id_event) {
            $eventToEdit = Event::findOrFail($id_event);
        }

        return view('admin.dashboard.manage_event', compact('events', 'eventToEdit'));
    }

    public function storeUpdateEvent(Request $request, $id_event = null)
    {
        $isUpdate = (bool)$id_event;
        
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'lokasi'     => 'required|string|max:255',
            'deskripsi'  => 'required|string',
        ]);

        $data = $request->only(['nama_event', 'tanggal', 'lokasi', 'deskripsi']);

        $data['tanggal'] = \Carbon\Carbon::parse($request->tanggal);

        if ($isUpdate) {
            $event = Event::findOrFail($id_event);
            $event->update($data);
            $message = 'Event berhasil diperbarui!';
        } else {
            $data['id_admin'] = Auth::user()->id_pengguna ?? null;
            Event::create($data);
            $message = 'Event baru berhasil ditambahkan!';
        }

        return redirect()->route('admin.event.index')->with('success', $message);
    }

    public function destroyEvent($id_event)
    {
        $event = Event::findOrFail($id_event);
        $event->delete();
        
        return redirect()->route('admin.event.index')->with('success', 'Event berhasil dihapus!');
    }
    public function manageUlasan()
    {
        $reviews = Ulasan::orderBy('created_at', 'desc')->with(['pengguna', 'booking.details.layanan'])->get();
        return view('admin.dashboard.manage_ulasan', compact('reviews'));
    }

    // 1. Balas Ulasan (Reply/Edit Reply)
    public function replyUlasan(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string|max:500',
        ]);

        $review = Ulasan::findOrFail($id);
        $review->update(['balasan' => $request->balasan]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    // 2. Hapus Ulasan (Delete Review)
    public function destroyUlasan($id)
    {
        $review = Ulasan::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}