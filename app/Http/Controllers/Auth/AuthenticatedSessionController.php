<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses permintaan login.
     * Autentikasi pengguna dan redirect berdasarkan role.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        //authenticated, redirect to intended page
        \Illuminate\Support\Facades\Log::info('Login User Role: ' . Auth::user()->role . ' | Email: ' . Auth::user()->email);

        if (Auth::user()->role === 'admin') {
            return redirect(route('admin.dashboard', absolute: false));
        } elseif (Auth::user()->role === 'pengurus') {
            return redirect(route('pengurus.dashboard', absolute: false));
        } elseif (Auth::user()->role === 'mahasiswa') {
            return redirect(route('mahasiswa.dashboard', absolute: false));
        }
        
        return redirect(route('user.dashboard', absolute: false));
    }

    /**
     * Keluar (logout) pengguna.
     * Invalidasi sesi dan regenerasi token.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}