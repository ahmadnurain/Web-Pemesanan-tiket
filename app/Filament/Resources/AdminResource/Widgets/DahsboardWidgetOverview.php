<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\User;
use App\Models\TicketTransaction;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DahsboardWidgetOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Users', User::count())
                ->description('Total registered users')
                ->color('primary')
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before),

            // Widget kedua: Total Transactions
            Stat::make('Total Transactions', TicketTransaction::count())
                ->description('Total completed transactions')
                ->color('success')
                ->descriptionIcon('heroicon-o-credit-card', IconPosition::Before)
        ];
    }
}
