<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use Laravel\Socialite\Facades\Socialite;
use Exception; 

class GoogleAuthController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman login Google (OAuth).
     * 
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback dari Google setelah proses otentikasi.
     * 
     * Method ini menangani logika:
     * 1. Mendapatkan data user dari Google.
     * 2. Menentukan role user baru (misal: 'mahasiswa' jika email domain sesuai).
     * 3. Membuat user baru atau mengupdate user yang sudah ada.
     * 4. Login otomatis dan redirect ke dashboard yang sesuai (Admin/Pengurus/User).
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Tentukan role berdasarkan domain email (HANYA UNTUK USER BARU)
            $email = $googleUser->getEmail();
            
            $user = User::where('email', $email)->first();

            if ($user) {
                // Update data user yang ada (kecuali role)
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'nama' => $googleUser->getName(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Buat user baru
                $role = 'user'; // Default role
                if (str_ends_with($email, '@mhs.politala.ac.id')) {
                    $role = 'mahasiswa';
                }

                $user = User::create([
                    'google_id' => $googleUser->getId(),
                    'nama' => $googleUser->getName(),
                    'email' => $email,
                    'username' => Str::before($email, '@'),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => $role,
                    'password' => null
                ]);
            }

            Auth::login($user);

             if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } elseif ($user->role === 'pengurus') {
                return redirect()->intended(route('pengurus.dashboard', absolute: false));
            } elseif ($user->role === 'mahasiswa') {
                return redirect()->intended(route('mahasiswa.dashboard', absolute: false));
            } else {
                return redirect()->intended(route('user.dashboard', absolute: false));
            }
            
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Terjadi masalah saat login dengan Google: ' . $e->getMessage());
        }
    }
}