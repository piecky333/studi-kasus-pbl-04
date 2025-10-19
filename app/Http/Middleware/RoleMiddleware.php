<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah sudah login?
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil data user yang login
        $user = Auth::user();

        // pengecekan role
        if (!in_array($user->role, $roles)) {
            //  Jika tidak ada, tolak aksesnya
            abort(403, 'Unauthorized');
        }

        // Jika rolenya cocok, izinkan lanjut
        return $next($request);
    }
}