<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk'; 
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_admin',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
    ];
}