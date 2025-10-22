<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Penting!
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'username' => Str::before($googleUser->getEmail(), '@'), // Membuat username dari email
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'mahasiswa', 
                    'password' => null
                ]
            );

            Auth::login($user);

            return redirect('/dashboard');

        } catch (\Throwable $th) {
            // dd($th->getMessage()); // Aktifkan ini jika ada error untuk debug
            return redirect('/login')->with('error', 'Terjadi masalah saat login dengan Google.');
        }
    }
}