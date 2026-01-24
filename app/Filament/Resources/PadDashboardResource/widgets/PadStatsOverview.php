<?php

namespace App\Filament\Resources\PadDashboardResource\Widgets;

use App\Support\PadAccess;
use App\Models\TicketTransaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
// 1. HAPUS 'InteractsWithPageFilters'
// use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Livewire\Attributes\On; // <-- 2. TAMBAHKAN IMPORT INI

class PadStatsOverview extends BaseWidget
{
    // 3. HAPUS TRAIT INI
    // use InteractsWithPageFilters;

    protected static bool $isLazy = false;

    // 4. TAMBAHKAN PROPERTI INI SECARA MANUAL
    public ?array $filters = [];

    /**
     * 5. TAMBAHKAN LISTENER INI
     * Ini akan "mendengar" event dari tombol 'Terapkan' di dashboard
     * dan akan menerima data awal saat halaman di-load.
     */
    #[On('updateFilters')]
    public function onFiltersUpdated(array $filters): void
    {
        $this->filters = $filters;
    }

    protected function getStats(): array
    {
        // 6. Kode ini sekarang akan mengambil dari '$this->filters'
        $f = $this->filters ?? [];
        [$from, $to] = PadAccess::fromTo($f);
        $allowed = PadAccess::allowedDestinationIds();
        $destIds = $f['destination_ids'] ?? null;

        $base = TicketTransaction::query()
            ->whereBetween('created_at', [$from, $to])
            ->when($allowed !== null, fn($q) => $q->whereIn('destination_id', $allowed))
            ->when(!empty($destIds), fn($q) => $q->whereIn('destination_id', $destIds));

        $padGross = (clone $base)->where('payment_status', 'succeeded')->sum('amount');
        $trxSucc  = (clone $base)->where('payment_status', 'succeeded')->count('id');
        $refunds  = (clone $base)->where('payment_status', 'refunded')->sum('amount');

        $issuedTickets = (clone $base)->where('payment_status', 'succeeded')->sum('total_tickets');
        $usedTickets   = (clone $base)->whereNotNull('used_at')->sum('total_tickets');
        $scanRate = $issuedTickets > 0 ? ($usedTickets / $issuedTickets) : 0;

        // Breakdown per Ticket Type (from transaction_items relation)
        // Hitung total item terjual per nama tipe
        $ticketTypes = \App\Models\TransactionItem::query()
            ->whereHas('transaction', function ($q) use ($from, $to, $allowed, $destIds) {
                $q->whereBetween('created_at', [$from, $to])
                    ->where('payment_status', 'succeeded')
                    ->when($allowed !== null, fn($qq) => $qq->whereIn('destination_id', $allowed))
                    ->when(!empty($destIds), fn($qq) => $qq->whereIn('destination_id', $destIds));
            })
            ->selectRaw('name, sum(quantity) as total_qty')
            ->groupBy('name')
            ->pluck('total_qty', 'name');

        // Asumsi nama tiket mengandung kata "Anak" atau "Dewasa"
        // Atau kita list semua yang muncul.
        // Untuk ringkas di Overview, kita ambil Top 2 (biasanya Dewasa & Anak)

        $stats = [
            Stat::make('PAD (Gross)', 'Rp ' . number_format($padGross, 0, ',', '.'))
                ->description('Total transaksi sukses')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Transaksi Sukses', number_format($trxSucc))
                ->description('Total Order')
                ->icon('heroicon-o-shopping-bag'),

            Stat::make('Tiket Terjual (Total)', number_format($issuedTickets))
                ->description('Total lembar tiket')
                ->icon('heroicon-o-ticket'),
        ];

        // Tambahkan breakdown stat dinamis
        foreach ($ticketTypes as $typeName => $qty) {
            $stats[] = Stat::make('Tiket: ' . $typeName, number_format($qty))
                ->icon('heroicon-o-user')
                ->color('info');
        }

        // Tambahkan stat pengunjung scan
        $stats[] = Stat::make('Pengunjung Masuk', number_format($usedTickets))
            ->description('Tiket discan (used)')
            ->icon('heroicon-o-check-circle')
            ->color('warning');

        return $stats;
    }
}
