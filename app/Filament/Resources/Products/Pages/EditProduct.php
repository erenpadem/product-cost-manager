<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Services\ProductService;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function beforeSave(): void
    {
        $ingredients = $this->data['ingredients'] ?? [];
        $this->record->total_grams = ProductService::calculateTotalGrams($ingredients);
        $this->record->cost = ProductService::calculateCost($ingredients);
    }

    protected function afterSave(): void
    {
        $ingredients = $this->data['ingredients'] ?? [];
        ProductService::syncIngredients($this->record, $ingredients);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($this->record)) {
            $data['ingredients'] = ($this->record->recipeItems ?? collect())->map(fn($item) => [
                'type' => $item->component_type === \App\Models\RawMaterial::class ? 'raw' : 'product',
                'item_id' => $item->component_id,
                'amount' => $item->qty,
                'unit' => $item->unit,
                'price' => $item->component?->price_per_kg ?? 0,
                'total_cost' => $item->component ? \App\Services\ProductService::calculateCost([
                    [
                        'type' => $item->component_type === \App\Models\RawMaterial::class ? 'raw' : 'product',
                        'item_id' => $item->component_id,
                        'amount' => $item->qty,
                        'unit' => $item->unit,
                    ]
                ]) : 0,
            ])->toArray();
        }
    
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
