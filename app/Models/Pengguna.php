<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna';       // Nama tabel
    protected $primaryKey = 'id_pengguna'; // Primary Key Custom

    /**
     * Daftar kolom yang boleh diisi (Mass Assignable)
     */
    protected $fillable = [
        'username', // Pastikan ini username, bukan name
        'email',
        'password',
        'role',
        'no_telp',  // <--- Tambahan baru
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}