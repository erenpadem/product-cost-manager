<?php

namespace App\Filament\Resources\PayWithQr\Pages;

use App\Filament\Resources\PayWithQr\PayWithQrResource;
use App\Filament\Resources\PayWithQr\Widgets\PayWithQrChart;
use App\Filament\Resources\PayWithQr\Widgets\PayWithQrStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayWithQr extends ListRecords
{
    protected static string $resource = PayWithQrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PayWithQrStats::class,
            PayWithQrChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
