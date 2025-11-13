<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// ===============================================
// == TAMBAHAN WAJIB: Untuk 'Storage' (Foto) ==
// ===============================================
use Illuminate\Support\Facades\Storage; 

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Ambil data user
        $user = $request->user();

        // Mengisi data user (name, email, no_telpon) dari validasi
        // $request->validated() SUDAH diatur oleh ProfileUpdateRequest.php
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // ==========================================================
        // == TAMBAHAN: LOGIKA SIMPAN FOTO PROFIL ==
        // ==========================================================
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Simpan foto baru ke 'storage/app/public/profile-photos'
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }
        // ===============================================
        
        // Simpan semua perubahan (termasuk foto)
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();   

        // ==========================================================
        // == TAMBAHAN: LOGIKA HAPUS FOTO SAAT HAPUS AKUN ==
        // ==========================================================
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        // ===============================================

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}