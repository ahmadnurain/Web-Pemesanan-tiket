<?php

namespace App\Filament\Resources\PadDashboardResource\Widgets;

use Filament\Tables;
use App\Support\PadAccess;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
// HAPUS 'InteractsWithPageFilters'
use App\Models\Destinations;
use Livewire\Attributes\On; // <-- TAMBAHKAN INI

class PadDestinationTable extends BaseWidget
{
    // HAPUS 'use InteractsWithPageFilters;'

    protected static ?string $heading = 'Ringkasan per Destinasi';
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
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        // Kode ini sekarang akan mengambil dari '$this->filters'
        $f = $this->filters ?? [];
        [$from, $to] = PadAccess::fromTo($f);
        $allowed = PadAccess::allowedDestinationIds();
        $destIds = $f['destination_ids'] ?? null;

        $query = Destinations::query()
            ->when($allowed !== null, fn($q) => $q->whereIn('id', $allowed))
            ->when(!empty($destIds), fn($q) => $q->whereIn('id', $destIds))
            ->select('destinations.*')
            ->withSum(['transactions as pad_gross' => function (Builder $q) use ($from, $to) {
                $q->where('payment_status', 'succeeded')->whereBetween('created_at', [$from, $to]);
            }], 'amount')
            ->withCount(['transactions as trx_success' => function (Builder $q) use ($from, $to) {
                $q->where('payment_status', 'succeeded')->whereBetween('created_at', [$from, $to]);
            }])
            ->withSum(['transactions as tickets_issued' => function (Builder $q) use ($from, $to) {
                $q->where('payment_status', 'succeeded')->whereBetween('created_at', [$from, $to]);
            }], 'total_tickets')
            ->withSum(['transactions as tickets_used' => function (Builder $q) use ($from, $to) {
                $q->whereNotNull('used_at')->whereBetween('created_at', [$from, $to]);
            }], 'total_tickets')
            ->withSum(['transactions as refunds' => function (Builder $q) use ($from, $to) {
                $q->where('payment_status', 'refunded')->whereBetween('created_at', [$from, $to]);
            }], 'amount')
            ->orderByDesc('pad_gross');

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Destinasi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('trx_success')->label('Transaksi')->numeric()->default(0)->sortable(),
                Tables\Columns\TextColumn::make('tickets_issued')->label('Tiket Terbit')->numeric()->default(0)->sortable(),
                Tables\Columns\TextColumn::make('tickets_used')->label('Tiket Dipakai')->numeric()->default(0)->sortable(),

                Tables\Columns\TextColumn::make('scan_rate')
                    ->label('Scan Rate')
                    ->state(function ($record) {
                        $issued = (int) ($record->tickets_issued ?? 0);
                        $used   = (int) ($record->tickets_used ?? 0);
                        return $issued > 0 ? round(($used / $issued) * 100, 2) : 0;
                    })
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pad_gross')->label('PAD (Gross)')->money('idr')->default(0)->sortable(),
                // Tables\Columns\TextColumn::make('refunds')->label('Refund')->money('idr')->sortable(),
            ])
            ->defaultSort('pad_gross', 'desc')
            ->paginated([10, 25, 50]);
    }
}
