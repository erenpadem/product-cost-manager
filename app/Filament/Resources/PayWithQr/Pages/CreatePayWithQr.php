<?php

namespace App\Filament\Resources\PayWithQr\Pages;

use App\Filament\Resources\PayWithQr\PayWithQrResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayWithQr extends CreateRecord
{
    protected static string $resource = PayWithQrResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
