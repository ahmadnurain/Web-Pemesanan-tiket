<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Tables\Table;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class DahsboardWidgetTabel extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

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
                TextColumn::make('name')->label('Name')->sortable(),
                TextColumn::make('destination.name')->label('Destination')->sortable(),
                TextColumn::make('amount')->label('Amount')->sortable(),
                BadgeColumn::make('payment_status')
                    ->label('Payment Status')
                    ->colors([
                        'pending' => 'warning',
                        'succeeded' => 'success',
                    ])
                    ->getStateUsing(fn($record) => $record->payment_status),
                TextColumn::make('total_tickets')->label('Total Tickets')->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'succeeded' => 'Succeeded',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
