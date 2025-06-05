<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsAdminOrSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Cek apakah user sudah login dan memiliki role admin atau super_admin
        if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
            // Kalau bukan, arahkan ke halaman home atau login
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}
