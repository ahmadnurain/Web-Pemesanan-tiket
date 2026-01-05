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

        return [
            Stat::make('PAD (Gross)', 'Rp ' . number_format($padGross, 0, ',', '.'))
                ->description('Total transaksi sukses')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Transaksi Sukses', number_format($trxSucc))
                ->description('Order berstatus succeeded')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Pengunjung (Dipakai)', number_format($usedTickets))
                ->description('Tiket digunakan (used_at)')
                ->icon('heroicon-o-user-group'),

            Stat::make('Scan Rate', number_format($scanRate * 100, 2) . ' %')
                ->description('Used / Issued dari tiket sukses')
                ->icon('heroicon-o-chart-pie'),
        ];
    }
}
