<?php

namespace App\Filament\Resources\PayWithQr\Pages;

use App\Filament\Resources\PayWithQr\PayWithQrResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPayWithQr extends EditRecord
{
    protected static string $resource = PayWithQrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
