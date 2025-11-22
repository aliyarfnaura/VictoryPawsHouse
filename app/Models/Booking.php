<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $table = 'booking';
    protected $primaryKey = 'id_booking';
    
    protected $fillable = [
        'id_pengguna',
        // 'id_layanan', // <-- Hapus ini jika sudah tidak dipakai (karena pindah ke detail)
        'nama',
        'nama_hewan',
        'nomor_hp',
        'jenis_hewan',
        'gender_hewan',
        'jadwal',
        'tanggal_checkout', // <--- WAJIB ADA BARIS INI
        'durasi',
        'catatan',
        'total_harga',
        'status',
        'jam_booking',
        'metode_pembayaran',
    ];

    protected $casts = [
        'jadwal' => 'date',
        'tanggal_checkout' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
    
    // Karena id_layanan tidak lagi ada di tabel booking Anda, kita hapus relasi Layanan.
    // Relasi Transaksi tetap ada.
    public function transaksi()
    {
        return $this->belongsTo(TransaksiProduk::class, 'id_transaksi', 'id_transaksi');
    }
}