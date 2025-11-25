<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;
    
    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';
    
    protected $fillable = [
        'id_booking',   // <--- WAJIB DITAMBAHKAN DI SINI
        'id_pengguna',
        // 'id_layanan', // (Opsional: Hapus jika sudah tidak dipakai, karena ikut booking)
        // 'id_produk',  // (Opsional: Hapus jika sudah tidak dipakai)
        'rating', 
        'komentar',
        'balasan',
    ];
    
    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
    
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }
    
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}