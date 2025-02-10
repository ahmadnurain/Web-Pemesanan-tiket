<?php

namespace App\Filament\Resources\TicketTransactionResource\Pages;

use App\Filament\Resources\TicketTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketTransaction extends CreateRecord
{
    protected static string $resource = TicketTransactionResource::class;
}
