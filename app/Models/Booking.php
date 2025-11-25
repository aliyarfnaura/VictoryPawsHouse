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
        'nama',
        'nama_hewan',
        'nomor_hp',
        'jenis_hewan',
        'gender_hewan',
        'jadwal',
        'tanggal_checkout',
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
    
    // --- [TAMBAHKAN BAGIAN INI] ---

    // Relasi ke Pembayaran (Penyebab Error Anda)
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_booking', 'id_booking');
    }

    // Relasi ke Detail Booking (Penting untuk Admin melihat layanan apa yang dipesan)
    public function details()
    {
        return $this->hasMany(DetailBooking::class, 'id_booking', 'id_booking');
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'id_booking', 'id_booking');
    }
}