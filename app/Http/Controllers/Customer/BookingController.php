<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan form booking dengan Kalender Ketersediaan.
     */
    public function index()
    {
        // 1. Ambil semua layanan
        $layanan = Layanan::all();

        // 2. LOGIKA KALENDER: Cari tanggal yang sudah dibooking (Full Booked)
        // Kita cari booking yang statusnya Masih Aktif dan memiliki tanggal checkout (Hotel)
        $existingBookings = Booking::whereNotNull('tanggal_checkout')
            ->whereIn('status', ['pending', 'Pending', 'dibayar', 'Dibayar', 'menunggu_konfirmasi'])
            ->get();

        $fullyBookedDates = [];

        foreach ($existingBookings as $book) {
            // Buat periode dari Check-in sampai Check-out
            $period = \Carbon\CarbonPeriod::create($book->jadwal, $book->tanggal_checkout);

            foreach ($period as $date) {
                // Format Y-m-d untuk dikirim ke Flatpickr
                /** @var \Carbon\Carbon $date */
                $fullyBookedDates[] = $date->format('Y-m-d');
            }
        }

        return view('customer.booking.form', compact('layanan', 'fullyBookedDates'));
    }

    /**
     * API AJAX: Cek Slot Jam yang tersedia pada tanggal tertentu.
     */
    public function checkSlots(Request $request)
    {
        $date = $request->query('date');
        if (!$date) return response()->json([]);

        // 1. Tentukan Jam Operasional (Misal 09:00 - 16:00)
        $startHour = 9;
        $endHour = 16;
        $allSlots = [];

        for ($i = $startHour; $i <= $endHour; $i++) {
            $time = sprintf('%02d:00:00', $i); // Format "09:00:00"
            $allSlots[] = $time;
        }

        // 2. Cari jam yang SUDAH DIBOOKING pada tanggal tersebut
        $bookedSlots = Booking::where('jadwal', $date)
            ->whereNotNull('jam_booking') // Hanya yang punya jam
            ->whereIn('status', ['pending', 'Pending', 'dibayar', 'Dibayar', 'menunggu_konfirmasi'])
            ->pluck('jam_booking')
            ->toArray();

        // 3. Filter Slot
        $availableSlots = [];
        foreach ($allSlots as $slot) {
            if (in_array($slot, $bookedSlots)) {
                $availableSlots[] = [
                    'time' => date('H:i', strtotime($slot)),
                    'available' => false // Penuh
                ];
            } else {
                $availableSlots[] = [
                    'time' => date('H:i', strtotime($slot)),
                    'available' => true // Kosong
                ];
            }
        }

        return response()->json($availableSlots);
    }

    /**
     * Memproses penyimpanan data booking.
     */
    public function store(Request $request)
    {
        // --- TAHAP 1: ANALISIS LAYANAN ---
        $isHotel = false;
        $isGrooming = false;

        if ($request->has('id_layanan')) {
            $layananDipilih = Layanan::whereIn('id_layanan', $request->id_layanan)->get();
            foreach ($layananDipilih as $l) {
                if (str_contains(strtolower($l->nama_layanan), 'hotel')) {
                    $isHotel = true;
                } else {
                    $isGrooming = true; // Asumsi selain hotel butuh jam
                }
            }
        }

        // --- TAHAP 2: VALIDASI DINAMIS ---
        $ruleCheckout = $isHotel ? 'required|date|after:jadwal' : 'nullable|date';
        $ruleJam = $isGrooming ? 'required' : 'nullable';
        

        $request->validate([
            'id_layanan'        => 'required|array',
            'id_layanan.*'      => 'exists:layanan,id_layanan',
            'nama_anda'         => 'required|string|max:255',
            'nama_hewan'        => 'required|string|max:255',
            'nomor_hp'          => 'required|string|max:20',
            'jenis_hewan'       => 'required|string',
            'gender_hewan'      => 'required|in:Jantan,Betina',
            'jadwal'            => 'required|date',
            'jadwal_checkout'   => $ruleCheckout,
            'jam_booking'       => $ruleJam,
            'metode_pembayaran' => 'required|string',
            'total_harga'       => 'required|numeric', // Kita validasi ada, tapi hitung ulang di bawah
            'catatan'           => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // --- TAHAP 3: HITUNG HARGA (SERVER SIDE) ---
            // Hitung ulang agar aman dari manipulasi frontend
            $totalHargaFix = 0;
            // Ambil data lagi untuk perhitungan
            $layananDipilih = Layanan::whereIn('id_layanan', $request->id_layanan)->get();

            // Array detail untuk dikirim ke Session Pop-up
            $detailItems = [];

            foreach ($layananDipilih as $item) {
                $hargaItem = $item->harga;
                $subTotal = $hargaItem;
                $namaDetail = $item->nama_layanan;

                if (str_contains(strtolower($item->nama_layanan), 'hotel')) {
                    // Logika Hotel
                    if ($request->jadwal && $request->jadwal_checkout) {
                        $checkin = Carbon::parse($request->jadwal);
                        $checkout = Carbon::parse($request->jadwal_checkout);
                        $durasi = $checkin->diffInDays($checkout);
                        $durasi = $durasi < 1 ? 1 : $durasi;

                        $subTotal = $hargaItem * $durasi;
                        $namaDetail .= " (" . $durasi . " Malam)";
                    }
                }

                $totalHargaFix += $subTotal;

                // Simpan info detail untuk popup
                $detailItems[] = [
                    'nama' => $namaDetail,
                    'harga' => $subTotal
                ];
            }

            // --- TAHAP 4: SIMPAN DATABASE ---

            // A. Simpan Booking Induk
            $booking = Booking::create([
                'id_pengguna'       => Auth::user()->id_pengguna,
                'nama'              => $request->nama_anda,
                'nama_hewan'        => $request->nama_hewan,
                'nomor_hp'          => $request->nomor_hp,
                'jenis_hewan'       => $request->jenis_hewan,
                'gender_hewan'      => $request->gender_hewan,
                'jadwal'            => $request->jadwal,
                'jam_booking'       => $request->jam_booking,
                'tanggal_checkout'  => $request->jadwal_checkout,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan'           => $request->catatan,
                'total_harga'       => $totalHargaFix,
                'status'            => 'pending',
                'durasi'            => $request->jadwal_checkout ? 'Checkout: ' . $request->jadwal_checkout : null,
            ]);

            // B. Simpan Detail Layanan
            foreach ($layananDipilih as $item) {
                DB::table('detail_booking')->insert([
                    'id_booking'     => $booking->id_booking,
                    'id_layanan'     => $item->id_layanan,
                    'harga_saat_ini' => $item->harga,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            DB::commit();

            // --- TAHAP 5: REDIRECT DENGAN DATA POPUP ---
            return redirect()->route('booking.index')->with([
                'success_popup' => true,
                'booking_id'    => $booking->id_booking,
                'total_bayar'   => $totalHargaFix,
                'detail_items'  => $detailItems // Data rincian untuk ditampilkan di modal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan halaman pembayaran (Opsional / Dipanggil dari Pop-up).
     */
    public function showPayment($id)
    {
        // Pastikan Anda sudah membuat view 'customer.payment.show'
        // Dan pastikan relasi di Model Booking sudah benar (metode 'layanan' via detail_booking mungkin butuh penyesuaian di Model)
        $booking = Booking::find($id);

        if (!$booking) {
            return redirect()->route('booking.index')->with('error', 'Data booking tidak ditemukan');
        }

        return view('customer.payment.show', compact('booking'));
    }
    public function uploadBukti(Request $request)
    {
        // 1. Validasi File
        $request->validate([
            'id_booking'   => 'required|exists:booking,id_booking',
            'bukti_gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        try {
            // 2. Simpan Gambar ke Folder 'public/uploads/pembayaran'
            if ($request->hasFile('bukti_gambar')) {
                $file = $request->file('bukti_gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();

                // Pindahkan file
                $file->move(public_path('uploads/pembayaran'), $namaFile);

                // 4. Simpan ke Tabel Pembayaran
                Pembayaran::create([
                    'id_booking'         => $request->id_booking,
                    'bukti_gambar'       => $namaFile,
                    'tanggal_pembayaran' => now(),
                ]);

                // Opsional: Update status booking jika perlu
                // $booking->update(['status' => 'menunggu_konfirmasi']);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Gambar berhasil diupload! Mohon tunggu verifikasi admin ya.'
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'File tidak ditemukan.'], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
