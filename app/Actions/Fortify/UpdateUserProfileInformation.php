<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function update($user, array $input)
    {
        // -------------------------------
        // VALIDASI INPUT
        // -------------------------------
        Validator::make($input, [
            'nama' => ['required', 'string', 'max:255'],      // ← SESUAI FORM & DATABASE
            'email' => ['required', 'email', 'max:255'],
            'no_telpon' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048'],     // ← foto profil
        ])->validate();

        // -------------------------------
        // HANDLE UPLOAD FOTO PROFIL
        // -------------------------------
        if (isset($input['photo'])) {

            // hapus foto lama kalau ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // upload foto baru ke storage/app/public/profile-photos
            $path = $input['photo']->store('profile-photos', 'public');

            // simpan path baru ke database
            $user->profile_photo_path = $path;
        }

        // -------------------------------
        // UPDATE DATA USER LAINNYA
        // -------------------------------
        $user->nama = $input['nama'];                         // ← WAJIB karena pakai kolom 'nama'
        $user->email = $input['email'];
        $user->no_telpon = $input['no_telpon'] ?? null;

        // simpan perubahan user
        $user->save();
    }
}
