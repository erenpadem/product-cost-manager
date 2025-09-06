<?php

namespace App\Filament\Resources\RawMaterials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RawMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Ham Madde Adı')       // Türkçe label
                    ->required()
                    ->placeholder('Ham maddenin adını girin'),

                TextInput::make('price_per_kg')
                    ->label('Birim Fiyat')         // Türkçe label
                    ->required()
                    ->numeric()
                    ->prefix('₺')                  // Türk Lirası işareti
                    ->placeholder('Ham maddenin birim fiyatını girin'),
                    Select::make('unit')
                    ->label('Birim')
                    ->options([
                        'kg' => 'Kilogram',
                        'g'  => 'Gram',
                        'l'  => 'Litre',
                        'ml' => 'Mililitre',
                        'kw' => 'Kilowatt',
                    ])
                    ->default('kg')
                    ->required(),
            ]);
    }
}
