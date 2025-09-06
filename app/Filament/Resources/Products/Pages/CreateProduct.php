<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Services\ProductService;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $ingredients = $data['ingredients'] ?? [];
        $data['total_grams'] = ProductService::calculateTotalGrams($ingredients);
        $data['cost'] = ProductService::calculateCost($ingredients);

        return $data;
    }

    protected function afterCreate(): void
    {
        $ingredients = $this->data['ingredients'] ?? [];
        ProductService::syncIngredients($this->record, $ingredients);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
