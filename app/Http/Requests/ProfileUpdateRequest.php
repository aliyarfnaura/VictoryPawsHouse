<?php

namespace App\Http\Requests;

use App\Models\Pengguna;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:20'],
            
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/',
                'max:255', 
                // Cek unik di tabel 'pengguna', abaikan ID user yang sedang login
                Rule::unique(Pengguna::class)->ignore($this->user()->id_pengguna, 'id_pengguna'),
            ],

            // Validasi No Telp: Boleh kosong, Maks 20 digit, Format angka/plus/spasi
            'no_telp' => ['nullable', 'string', 'digits_between:10,15','regex:/^08[0-9]{8,13}$/'],
        ];
    }
}