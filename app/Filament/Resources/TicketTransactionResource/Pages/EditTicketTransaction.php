<?php

namespace App\Filament\Resources\TicketTransactionResource\Pages;

use App\Filament\Resources\TicketTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketTransaction extends EditRecord
{
    protected static string $resource = TicketTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
