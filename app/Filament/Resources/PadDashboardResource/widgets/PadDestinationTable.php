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

        // Ambil daftar tipe tiket unik yang ada transaksi di range ini (untuk header atau info)
        // (Optional, kalau mau kolom dinamis banget. Disini kita gabung jadi satu kolom "Rincian")

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Destinasi')
                    ->description(fn(Destinations $r) => $r->location ?? '')
                    ->searchable(),

                Tables\Columns\TextColumn::make('trx_success')->label('Order')->numeric()->sortable(),

                Tables\Columns\TextColumn::make('tickets_issued')->label('Total Tiket')->numeric()->sortable()
                    ->color('primary')
                    ->weight('bold'),

                // Kolom Breakdown Dinamis (Tipe Tiket)
                Tables\Columns\TextColumn::make('breakdown')
                    ->label('Rincian Tiket')
                    ->state(function (Destinations $record) use ($from, $to) {
                        // Ambil items dari transaksi sukses destinasi ini
                        $breakdown = \App\Models\TransactionItem::query()
                            ->whereHas('transaction', function ($q) use ($record, $from, $to) {
                                $q->where('destination_id', $record->id)
                                    ->where('payment_status', 'succeeded')
                                    ->whereBetween('created_at', [$from, $to]);
                            })
                            ->selectRaw('name, sum(quantity) as total')
                            ->groupBy('name')
                            ->pluck('total', 'name');

                        if ($breakdown->isEmpty()) return '-';

                        // Format: "Dewasa: 10, Anak: 5"
                        return $breakdown->map(fn($qty, $name) => "$name: $qty")->join(', ');
                    })
                    ->size('xs')
                    ->wrap(),

                Tables\Columns\TextColumn::make('tickets_used')->label('Dipakai')->numeric()->sortable(),

                Tables\Columns\TextColumn::make('pad_gross')->label('Pendapatan')->money('idr')->sortable()
                    ->weight('bold')
                    ->color('success'),
            ])
            ->paginated([10, 25, 50]);
    }
}
