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
        'id_booking',
        'id_pengguna',
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