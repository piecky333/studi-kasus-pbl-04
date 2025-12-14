<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan form untuk mengedit profil pengguna.
     * 
     * Jika pengguna memiliki role 'admin' atau 'pengurus', mereka akan
     * diarahkan ke rute edit profil khusus masing-masing.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return Redirect::route('admin.profile.edit');
        }

        if ($user->hasRole('pengurus')) {
            return Redirect::route('pengurus.profile.edit');
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     * 
     * Menangani pembaruan data dasar user, reset status verifikasi email
     * jika email berubah, serta manajemen upload foto profil baru.
     * 
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Simpan data profil.
        $user->fill($request->validated());

        // Reset verifikasi email jika berubah.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Update foto profil jika ada.
        if ($request->hasFile('photo')) {

            // Hapus foto lama.
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Simpan foto baru.
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Simpan perubahan.
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna secara permanen.
     * 
     * Sebelum menghapus, sistem akan memvalidasi password saat ini untuk keamanan.
     * Foto profil (jika ada) juga akan dihapus dari penyimpanan.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Hapus foto profil.
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
