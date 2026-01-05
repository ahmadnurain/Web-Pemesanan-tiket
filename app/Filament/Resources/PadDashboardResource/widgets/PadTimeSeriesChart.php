<?php

namespace App\Filament\Resources\PadDashboardResource\Widgets;

use Filament\Widgets\ChartWidget;
// HAPUS 'InteractsWithPageFilters'
use App\Support\PadAccess;
use App\Models\TicketTransaction;
use Carbon\Carbon;
use Livewire\Attributes\On; // <-- TAMBAHKAN INI

class PadTimeSeriesChart extends ChartWidget
{
    // HAPUS 'use InteractsWithPageFilters;'

    protected static ?string $heading = 'PAD & Pengunjung per Hari';
    protected int|string|array $columnSpan = 'full'; // 12/12
    protected static bool $isLazy = false;

    // TAMBAHKAN PROPERTI INI
    public ?array $filters = [];

    /**
     * TAMBAHKAN LISTENER INI
     */
    #[On('updateFilters')]
    public function onFiltersUpdated(array $filters): void
    {
        $this->filters = $filters;
        // Untuk ChartWidget, panggil 'updateChartData'
        $this->updateChartData();
    }

    protected function getData(): array
    {
        // Kode ini sekarang akan mengambil dari '$this->filters'
        $f = $this->filters ?? [];
        [$from, $to] = PadAccess::fromTo($f);
        $allowed = PadAccess::allowedDestinationIds();
        $destIds = $f['destination_ids'] ?? null;

        $rows = TicketTransaction::query()
            ->selectRaw("DATE(created_at) as d")
            ->selectRaw("SUM(CASE WHEN payment_status='succeeded' THEN amount ELSE 0 END) as pad")
            ->selectRaw("SUM(CASE WHEN used_at IS NOT NULL THEN total_tickets ELSE 0 END) as visitors")
            ->whereBetween('created_at', [$from, $to])
            ->when($allowed !== null, fn($q) => $q->whereIn('destination_id', $allowed))
            ->when(!empty($destIds), fn($q) => $q->whereIn('destination_id', $destIds))
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $labels = $rows->pluck('d')->map(fn($d) => Carbon::parse($d)->format('d M'))->all();
        $pad = $rows->pluck('pad')->map(fn($v) => (float) $v)->all();
        $vis = $rows->pluck('visitors')->map(fn($v) => (int) $v)->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'PAD (IDR)',
                    'data' => $pad,
                    'yAxisID' => 'y',
                    'borderColor' => '#f97316',
                    'backgroundColor' => 'rgba(249,115,22,.25)',
                    'pointBackgroundColor' => '#f97316',
                    'pointBorderColor' => '#f97316',
                    'tension' => 0.3,
                    'fill' => true,
                ],
                [
                    'label' => 'Pengunjung',
                    'data' => $vis,
                    'yAxisID' => 'y1',
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34,197,94,.25)',
                    'pointBackgroundColor' => '#22c55e',
                    'pointBorderColor' => '#22c55e',
                    'tension' => 0.3,
                    'fill' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'interaction' => ['mode' => 'index', 'intersect' => false],
            'stacked' => false,
            'scales' => [
                'y'  => ['type' => 'linear', 'display' => true, 'position' => 'left',  'title' => ['display' => true, 'text' => 'IDR']],
                'y1' => ['type' => 'linear', 'display' => true, 'position' => 'right', 'grid' => ['drawOnChartArea' => false], 'title' => ['display' => true, 'text' => 'Orang']],
            ],
        ];
    }
}
