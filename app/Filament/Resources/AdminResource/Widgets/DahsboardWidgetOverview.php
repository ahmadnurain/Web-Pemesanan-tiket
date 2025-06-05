<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\User;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DahsboardWidgetOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [];

        if (Auth::user()?->role === 'admin') {
            $stats[] = Stat::make('Total Users', User::count())
                ->description('Total registered users')
                ->color('primary')
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before);
        }

        // Stat kedua: Total Transactions
        $stats[] = Stat::make('Total Transactions', function () {
            if (Auth::user()?->role === 'super_admin') {
                return TicketTransaction::count();
            }
            return TicketTransaction::whereHas('destination', function ($query) {
                $query->where('user_id', Auth::id());
            })->count();
        })
            ->description('Total completed transactions')
            ->color('success')
            ->descriptionIcon('heroicon-o-credit-card', IconPosition::Before);

        return $stats;
    }
}
