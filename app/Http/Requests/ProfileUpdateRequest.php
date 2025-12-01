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
            'username' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z\s]+$/'],
            'no_telp' => ['nullable', 'numeric', 'digits_between:10,15', 'regex:/^08[0-9]+$/'],
        ];
    }
    /**
     * Custom message for validation errors.
     */
    public function messages(): array
    {
        return [
            // Pesan Error Username
            'username.regex' => '*Username tidak boleh mengandung simbol dan angka (hanya huruf ).',
            'username.required' => '*Nama pengguna wajib diisi.',

            // Pesan Error No Telp
            'no_telp.regex' => '*Nomor HP harus diawali 08 dan hanya boleh berisi angka (tidak boleh ada simbol).',
            'no_telp.digits_between' => '*Panjang Nomor HP minimal 10 digit dan maksimal 15 digit.',
        ];
    }
}