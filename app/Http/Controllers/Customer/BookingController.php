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
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index()
    {
        $layanan = Layanan::all();
        $existingBookings = Booking::whereNotNull('tanggal_checkout')
            ->whereIn('status', ['pending', 'Pending', 'dibayar', 'Dibayar', 'menunggu_konfirmasi'])
            ->get();

        $fullyBookedDates = [];

        foreach ($existingBookings as $book) {
            $period = \Carbon\CarbonPeriod::create($book->jadwal, $book->tanggal_checkout);

            foreach ($period as $date) {
                $fullyBookedDates[] = $date->format('Y-m-d');
            }
        }
        return view('customer.booking.form', compact('layanan', 'fullyBookedDates'));
    }

    public function checkSlots(Request $request)
    {
        $date = $request->query('date');
        if (!$date) return response()->json([]);

        $startHour = 9;
        $endHour = 16;
        $allSlots = [];

        for ($i = $startHour; $i <= $endHour; $i++) {
            $time = sprintf('%02d:00:00', $i);
            $allSlots[] = $time;
        }

        $bookedSlots = Booking::where('jadwal', $date)
            ->whereNotNull('jam_booking')
            ->whereIn('status', ['pending', 'Pending', 'dibayar', 'Dibayar', 'menunggu_konfirmasi'])
            ->pluck('jam_booking')
            ->toArray();

        $availableSlots = [];
        foreach ($allSlots as $slot) {
            if (in_array($slot, $bookedSlots)) {
                $availableSlots[] = [
                    'time' => date('H:i', strtotime($slot)),
                    'available' => false
                ];
            } else {
                $availableSlots[] = [
                    'time' => date('H:i', strtotime($slot)),
                    'available' => true
                ];
            }
        }
        return response()->json($availableSlots);
    }

    public function store(Request $request)
    {
        $isHotel = false;
        $isGrooming = false;

        if ($request->has('id_layanan')) {
            $layananDipilih = Layanan::whereIn('id_layanan', $request->id_layanan)->get();
            foreach ($layananDipilih as $l) {
                if (str_contains(strtolower($l->nama_layanan), 'hotel')) {
                    $isHotel = true;
                } else {
                    $isGrooming = true;
                }
            }
        }
        $ruleCheckout = $isHotel ? 'required|date|after:jadwal' : 'nullable|date';
        $ruleJam = $isGrooming ? 'required' : 'nullable';
        
        $request->validate([
            'id_layanan'        => ['required', 'array'],
            'id_layanan.*'      => ['exists:layanan,id_layanan'],
            'nama_anda'         => ['required', 'string', 'max:20', 'regex:/^(?!.*(.)\1{3,}).+$/'],         
            'nama_hewan'        => ['required', 'string', 'max:20', 'regex:/^(?!.*(.)\1{3,}).+$/'],
            'nomor_hp'          => ['required', 'numeric', 'digits_between:10,15', 'regex:/^08[0-9]+$/', 'not_regex:/^0+$/'],
            'jenis_hewan'       => ['required', 'string'],
            'gender_hewan'      => ['required', 'in:Jantan,Betina'],
            'jadwal'            => ['required', 'date'],
            'jadwal_checkout'   => $ruleCheckout,
            'jam_booking'       => $ruleJam,
            'metode_pembayaran' => ['required', 'string'],
            'total_harga'       => ['required', 'numeric'],
            'catatan'           => ['nullable', 'string'],
        ], [
            'nama_anda.regex'       => 'Nama Anda mengandung huruf berulang yang tidak wajar (Spam).',
            'nama_hewan.regex'      => 'Nama Hewan mengandung huruf berulang yang tidak wajar (Spam).',           
            'nomor_hp.required'     => 'Nomor HP wajib diisi.',
            'nomor_hp.numeric'      => 'Nomor HP harus berupa angka saja.',
            'nomor_hp.regex'        => 'Nomor HP harus diawali dengan 08.',
            'nomor_hp.not_regex'    => 'Nomor HP tidak valid (tidak boleh angka 0 semua).',
            'nomor_hp.digits_between' => 'Panjang Nomor HP harus antara 10 sampai 15 digit.',
            'jadwal_checkout.after' => 'Tanggal checkout harus setelah tanggal check-in.',
            'jam_booking.required'  => 'Jam booking wajib dipilih untuk layanan ini.',
        ]);

        try {
            DB::beginTransaction();
            $totalHargaFix = 0;
            $layananDipilih = Layanan::whereIn('id_layanan', $request->id_layanan)->get();
            $detailItems = [];

            foreach ($layananDipilih as $item) {
                $hargaItem = $item->harga;
                $subTotal = $hargaItem;
                $namaDetail = $item->nama_layanan;

                if (str_contains(strtolower($item->nama_layanan), 'hotel')) {
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
                $detailItems[] = [
                    'nama' => $namaDetail,
                    'harga' => $subTotal
                ];
            }

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
            return redirect()->route('booking.index')->with([
                'success_popup' => true,
                'booking_id'    => $booking->id_booking,
                'total_bayar'   => $totalHargaFix,
                'detail_items'  => $detailItems
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function showPayment($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return redirect()->route('booking.index')->with('error', 'Data booking tidak ditemukan');
        }
        return view('customer.payment.show', compact('booking'));
    }

    public function uploadBukti(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_booking'   => 'required|exists:booking,id_booking',
            'bukti_gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_gambar.required' => 'Wajib upload gambar bukti pembayaran.',
            'bukti_gambar.image'    => 'File yang diupload harus berupa gambar.',
            'bukti_gambar.mimes'    => 'Format gambar harus JPEG, PNG, atau JPG.',
            'bukti_gambar.max'      => 'Ukuran gambar terlalu besar! Maksimal 2MB ya.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first('bukti_gambar')
            ], 422);
        }

        try {
            if ($request->hasFile('bukti_gambar')) {
                $file = $request->file('bukti_gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                
                $path = public_path('uploads/pembayaran');
                if(!file_exists($path)){
                    mkdir($path, 0755, true);
                }
                $file->move($path, $namaFile);

                Pembayaran::create([
                    'id_booking'         => $request->id_booking,
                    'bukti_gambar'       => $namaFile,
                    'metode'             => 'Transfer',
                    'tanggal_pembayaran' => now(),
                ]);
                Booking::where('id_booking', $request->id_booking)->update(['status' => 'dibayar']);
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