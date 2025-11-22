<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    
    protected $fillable = [
        'id_booking', 
        'id_transaksi',
        'bukti_gambar',
        'tanggal_pembayaran',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }
    
    public function transaksi()
    {
        // Relasi ke TransaksiProduk (meski namanya ambigu, ini mewakili transaksi umum)
        return $this->belongsTo(TransaksiProduk::class, 'id_transaksi', 'id_transaksi');
    }
}