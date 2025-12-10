<?php

namespace App\Policies;

use App\Models\User;
use App\Models\komentar; // Sesuaikan jika nama model Anda 'Komentar'
use Illuminate\Auth\Access\HandlesAuthorization;

class KomentarPolicy
{
    use HandlesAuthorization;

    /**
     * Tentukan user bisa mengupdate komentar.
     */
    public function update(User $user, komentar $komentar)
    {
        // User Boleh update JIKA itu adalah komentarnya sendiri.
        return $user->id_user === $komentar->id_user;
    }

    /**
     * Tentukan user bisa menghapus komentar.
     */
    public function destroy(User $user, komentar $komentar)
    {
        // Admin/Pengurus boleh hapus komentar APAPUN.
        if ($user->role === 'admin' || $user->role === 'pengurus') {
            return true;
        }

        // Mahasiswa (User biasa) TIDAK BOLEH menghapus komentar, meskipun miliknya sendiri.
        return false;
    }
}