<?php

namespace App\Support;

use App\Models\Destinations;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PadAccess
{
    /**
     * Return:
     * - null  -> super_admin: boleh semua destinasi
     * - []    -> user/role tanpa destinasi
     * - [ids] -> admin: hanya destinasi miliknya
     */
    public static function allowedDestinationIds(): ?array
    {
        $u = Auth::user();
        if (!$u) return [];

        if ($u->role === 'super_admin') {
            return null;
        }

        if ($u->role === 'admin') {
            return Destinations::where('user_id', $u->id)->pluck('id')->all();
        }

        // role 'user' (kalau ada di sistem Filament) tidak boleh lihat
        return [];
    }

    /** Ambil rentang tanggal dari filter Page */
    public static function fromTo(array $filters): array
    {
        $from = Carbon::parse($filters['from'] ?? now()->startOfMonth())->startOfDay();
        $to   = Carbon::parse($filters['to']   ?? now())->endOfDay();
        return [$from, $to];
    }
}
