<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    // Konfigurasi Created At custom (Sesuai diskusi kita sebelumnya)
    const CREATED_AT = 'tanggal_pembayaran';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_booking',
        'metode',       // <--- PASTIKAN INI ADA
        'bukti_gambar',
        'tanggal_pembayaran',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }
}