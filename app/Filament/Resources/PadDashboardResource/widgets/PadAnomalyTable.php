<?php

namespace App\Filament\Resources\PadDashboardResource\Widgets;

use Filament\Tables;
use App\Support\PadAccess;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use App\Models\TicketTransaction;
use Filament\Widgets\TableWidget as BaseWidget;
// HAPUS 'use Filament\Widgets\Concerns\InteractsWithPageFilters;'

class PadAnomalyTable extends BaseWidget
{
    // HAPUS 'use InteractsWithPageFilters;'

    protected static ?string $heading = 'Transaksi Anomali';
    protected static bool $isLazy = false;

    // Properti ini untuk menyimpan filter
    public ?array $filters = [];

    // HAPUS SELURUH METHOD mount() DARI SINI
    // public function mount(): void { ... }

    public function table(Table $table): Table
    {
        // Kode ini akan mengambil data dari '$this->filters'
        $f = $this->filters ?? [];
        [$from, $to] = PadAccess::fromTo($f);
        $allowed = PadAccess::allowedDestinationIds();
        $destIds = $f['destination_ids'] ?? null;

        $q = TicketTransaction::query()
            ->whereBetween('created_at', [$from, $to])
            ->when($allowed !== null, fn($qq) => $qq->whereIn('destination_id', $allowed))
            ->when(!empty($destIds), fn($qq) => $qq->whereIn('destination_id', $destIds))
            ->where(function ($qq) {
                $qq->where(function ($x) {
                    $x->where('payment_status', 'succeeded')
                        ->whereNull('used_at')
                        ->where('created_at', '<', now()->subDays(2));
                })
                    ->orWhere(function ($x) {
                        $x->where('payment_status', 'pending')
                            ->where('created_at', '<', now()->subDay());
                    });
            })
            ->latest('created_at');

        return $table
            ->query($q)
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
                Tables\Columns\TextColumn::make('destination.name')->label('Destinasi')->toggleable(),
                Tables\Columns\TextColumn::make('order_id')->label('Order ID')->copyable(),
                Tables\Columns\TextColumn::make('name')->label('Nama'),
                Tables\Columns\TextColumn::make('total_tickets')->label('Tiket'),
                Tables\Columns\TextColumn::make('amount')->label('Jumlah')->money('idr'),
                Tables\Columns\BadgeColumn::make('payment_status')->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'succeeded',
                        'danger'  => 'failed',
                        'gray'    => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('used_at')->label('Dipakai')->since()->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')->options([
                    'succeeded' => 'succeeded',
                    'pending'   => 'pending',
                    'failed'    => 'failed',
                    'refunded'  => 'refunded',
                ]),
            ])
            ->paginated([10, 25, 50]);
    }

    /**
     * Listener ini akan menerima event 'updateFilters' dari PadDashboard
     * saat halaman pertama kali di-load DAN saat filter diubah.
     */
    #[On('updateFilters')]
    public function onFiltersUpdated(array $filters): void
    {
        $this->filters = $filters;
        $this->resetTable();
        $this->dispatch('$refresh');
    }
}
