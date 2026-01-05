<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\TicketTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DahsboardWidgetTabel extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Transactions';

    protected function getTableQuery(): Builder
    {
        if (Auth::user()?->role === 'super_admin') {
            return TicketTransaction::query()->latest();
        }

        return TicketTransaction::query()
            ->whereHas('destination', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->heading('Latest Transactions')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('destination.name')
                    ->label('Destination')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->alignRight()
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->color(fn(string $state) => match ($state) {
                        'pending'   => 'warning', // kuning
                        'succeeded' => 'success', // hijau
                        default     => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('total_tickets')
                    ->label('Total Tickets')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i') // ubah format sesuai kebutuhan
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options([
                        'pending'   => 'Pending',
                        'succeeded' => 'Succeeded',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
