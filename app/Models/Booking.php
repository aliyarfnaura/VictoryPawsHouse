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
        'jam_booking',
        'tanggal_checkout',
        'durasi',
        'catatan',
        'total_harga',
        'status',
        'jam_booking',
        'metode_pembayaran',
    ];

    // ACCESSOR: Membuat virtual attribute 'tipe_layanan'
    public function getTipeLayananAttribute()
    {
        if ($this->durasi !== NULL && $this->durasi !== 'NULL' && $this->durasi > 0) {
            return 'Pet Hotel'; // Jika durasi terisi, pasti Pet Hotel
        }
        // Asumsi: Jika jam_booking terisi, itu adalah Grooming/Home Service
        if ($this->jam_booking !== NULL && $this->jam_booking !== 'NULL') {
            // Ini asumsi: Home Service dan Grooming menggunakan jam_booking
            // Karena tidak ada field spesifik, kita generalisasi ke Grooming
            if (str_contains(strtolower($this->catatan ?? ''), 'home service')) {
                return 'Home Service';
            }
            return 'Pet Grooming'; 
        }

        return 'Layanan Lain'; // Default jika logic tidak tercapai
    }

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