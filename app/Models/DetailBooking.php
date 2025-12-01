<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBooking extends Model
{
    use HasFactory;

    protected $table = 'detail_booking';    
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_booking',
        'id_layanan',
        'harga_saat_ini',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}