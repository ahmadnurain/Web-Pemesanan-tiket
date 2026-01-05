<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TicketScan;
use App\Models\TicketTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketScanController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate(['payload' => ['required', 'string']]);
        $user = Auth::user() ?? abort(401);
        $ip = $request->ip();
        $ua = (string) $request->userAgent();

        try {
            [$uuid, $sig] = array_pad(explode('|', $request->payload, 2), 2, null);
            if (!$uuid || !$sig) {
                $this->log(null, $user->id, 'invalid_sig', $ip, $ua);
                return response()->json(['status' => 'invalid', 'reason' => 'bad_format'], 422);
            }

            $tx = TicketTransaction::with('destination')->where('uuid', $uuid)->first();
            if (!$tx) {
                $this->log(null, $user->id, 'not_found', $ip, $ua);
                return response()->json(['status' => 'invalid', 'reason' => 'not_found'], 404);
            }

            // super_admin bebas, admin hanya destinasi miliknya
            $isSuper = $user->role === 'super_admin';
            $isOwner = (int) optional($tx->destination)->user_id === (int) $user->id;
            if (!($isSuper || $isOwner)) {
                $this->log($tx->id, $user->id, 'unauthorized', $ip, $ua);
                return response()->json(['status' => 'invalid', 'reason' => 'unauthorized'], 403);
            }

            $expected = hash_hmac('sha256', $uuid, $tx->qr_secret ?? '');
            if (!hash_equals($expected, $sig)) {
                $this->log($tx->id, $user->id, 'invalid_sig', $ip, $ua);
                return response()->json(['status' => 'invalid', 'reason' => 'invalid_sig'], 422);
            }

            $result = DB::transaction(function () use ($tx, $user) {
                $already = (bool) $tx->used_at;
                $tx->markUsedBy($user->id);
                return $already ? 'already_used' : 'valid';
            });

            $this->log($tx->id, $user->id, $result, $ip, $ua);

            return response()->json([
                'status' => $result,
                'ticket' => [
                    'name' => $tx->name,
                    'ticket_code' => $tx->ticket_code,
                    'total_tickets' => $tx->total_tickets,
                    'amount' => $tx->amount,
                    'used_at' => optional($tx->used_at)->toDateTimeString(),
                    'destination' => optional($tx->destination)->name,
                ],
            ]);
        } catch (\Throwable $e) {
            report($e);
            $this->log(null, $user->id, 'error', $ip, $ua);
            return response()->json(['status' => 'invalid', 'reason' => 'error'], 500);
        }
    }

    private function log(?int $ticketId, ?int $userId, string $result, ?string $ip, ?string $ua): void
    {
        TicketScan::create([
            'ticket_transaction_id' => $ticketId,
            'user_id' => $userId,
            'result' => $result,
            'ip' => (string)$ip,
            'user_agent' => (string) Str::limit($ua, 255),
        ]);
    }
}
