<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    const CREATED_AT = 'tanggal_pembayaran';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_booking',
        'metode',      
        'bukti_gambar',
        'tanggal_pembayaran',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }
}