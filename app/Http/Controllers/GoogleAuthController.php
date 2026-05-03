<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $provider */
            $provider = Socialite::driver('google');
            $googleUser = $provider->stateless()->user();
            $email      = $googleUser->getEmail();
            
            // 1. Cari berdasarkan google_id terlebih dahulu
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if (!$user) {
                // 2. Jika tidak ada, cari berdasarkan email
                $user = User::where('email', $email)->first();
                
                if ($user) {
                    // Update user yang sudah ada dengan google_id
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                    ]);
                } else {
                    // 3. Jika benar-benar baru, buat user baru
                    $role = str_ends_with($email, '@mhs.politala.ac.id') ? 'mahasiswa' : 'user';
                    
                    // Generate username unik jika perlu
                    $baseUsername = Str::before($email, '@');
                    $username = $baseUsername;
                    $counter = 1;
                    while (User::where('username', $username)->exists()) {
                        $username = $baseUsername . $counter;
                        $counter++;
                    }

                    $user = User::create([
                        'google_id' => $googleUser->getId(),
                        'nama'      => $googleUser->getName(),
                        'email'     => $email,
                        'username'  => $username,
                        'avatar'    => $googleUser->getAvatar(),
                        'role'      => $role,
                        'password'  => null,
                    ]);
                }
            } else {
                // Update data profil terbaru dari Google
                $user->update([
                    'nama'   => $googleUser->getName(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user);

            // Redirect berdasarkan role
            return match ($user->role) {
                'admin'    => redirect()->intended(route('admin.dashboard')),
                'pengurus' => redirect()->intended(route('pengurus.dashboard')),
                'mahasiswa'=> redirect()->intended(route('mahasiswa.dashboard')),
                default    => redirect()->intended(route('user.dashboard')),
            };

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Login Google gagal. Pastikan akun Anda valid.');
        }
    }
}