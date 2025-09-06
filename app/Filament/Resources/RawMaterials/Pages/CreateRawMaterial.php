<?php

namespace App\Filament\Resources\RawMaterials\Pages;

use App\Filament\Resources\RawMaterials\RawMaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRawMaterial extends CreateRecord
{
    protected static string $resource = RawMaterialResource::class;

    protected function afterSave(): void
    {
        $this->record->recipeItems->each(function ($item) {
            $item->product?->recalculateCost();
        });
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
