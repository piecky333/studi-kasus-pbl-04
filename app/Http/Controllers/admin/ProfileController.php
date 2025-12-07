<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Class ProfileController
 * 
 * Controller ini menangani manajemen profil pengguna yang sedang login (Admin).
 * Fitur mencakup:
 * 1. Menampilkan form edit profil.
 * 2. Memperbarui informasi profil (Nama, Email).
 * 3. Mengelola upload dan penggantian foto profil.
 * 
 * @package App\Http\Controllers\Admin
 */
class ProfileController extends Controller
{
    /**
     * Menampilkan formulir edit profil pengguna.
     * 
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        return view('pages.admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     * 
     * Menangani logika:
     * 1. Update data dasar (fill).
     * 2. Reset verifikasi email jika email diubah.
     * 3. Upload foto profil baru dan hapus foto lama.
     * 
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        // Reset status verifikasi email jika email diubah.
        // Ini fitur keamanan standar untuk memastikan email baru valid.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Manajemen Foto Profil:
        // Jika ada upload foto baru, hapus foto lama dari storage untuk menghemat ruang.
        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }
}
