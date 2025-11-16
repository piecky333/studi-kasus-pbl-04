<?php

namespace App\Http\Requests;

// ===============================================
// == TAMBAHAN WAJIB: Untuk 'Rule' (Email Unik) ==
// ===============================================
use App\Models\User; // Ganti jika model User-mu beda
use Illuminate\Validation\Rule; 

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // Ini sudah ada di kodingan standarmu
            'name' => ['required', 'string', 'max:255'],
            
            // ==========================================================
            // == INI PERBAIKANNYA (Rule::unique...): ==
            // ==========================================================
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id) // <-- INI YANG BIKIN BERES
            ],
            // ==========================================================

            // ==========================================================
            // == TAMBAHAN: Validasi untuk field baru dari form-mu ==
            // ==========================================================
            'no_telpon' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}