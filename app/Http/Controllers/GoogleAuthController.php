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
     * Redirect ke halaman login Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Proses callback dari Google.
     * Login atau registrasi user baru.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                [
                    'google_id' => $googleUser->getId()
                ],
                [
                    'nama' => $googleUser->getName(),         
                    'email' => $googleUser->getEmail(),       
                    'username' => Str::before($googleUser->getEmail(), '@'), 
                    'avatar' => $googleUser->getAvatar(),    
                    'role' => 'user',                     
                    'password' => null                    
                ]
            );


            Auth::login($user);

          
             if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } elseif ($user->role === 'pengurus') {
                return redirect()->intended(route('pages.pengurus.dashboard', absolute: false));
            } else {
                return redirect()->intended(route('user.dashboard', absolute: false));
            }
            
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Terjadi masalah saat login dengan Google: ' . $e->getMessage());
        }
    }
}