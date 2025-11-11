<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events'; 
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'id_admin',
        'nama_event',
        'deskripsi',
        'lokasi',
        'tanggal',
        'gambar',
    ];
}