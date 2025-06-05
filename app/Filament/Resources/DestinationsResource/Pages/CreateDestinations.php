<?php

namespace App\Filament\Resources\DestinationsResource\Pages;

use Filament\Panel;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DestinationsResource;

class CreateDestinations extends CreateRecord
{

    public static function getRouteMiddleware(Panel $panel): array|string
    {
        // Panggil middleware bawaan
        $parentMiddleware = parent::getRouteMiddleware($panel);

        // Bisa return array gabungan
        if (is_array($parentMiddleware)) {
            return array_merge($parentMiddleware, [
                'super_admin',
            ]);
        }

        // Jika parent return string, jadikan array dulu
        return [$parentMiddleware, 'super_admin'];
    }
    protected static string $resource = DestinationsResource::class;
}
