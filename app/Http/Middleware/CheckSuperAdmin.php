<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            // Redirect ke halaman login Filament supaya tidak looping
            return redirect(route('filament.admin.auth.login'))->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}
