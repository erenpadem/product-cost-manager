<?php

namespace App\Filament\Resources\PayWithQr\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayWithQrForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('toplam_tutar')
                    ->label('Toplam tutar')
                    ->required()
                    ->numeric()
                    ->prefix('₺')
                    ->minValue(0)
                    ->step(0.01),

                TextInput::make('indirim_oranı')
                    ->label('İndirim oranı')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0)
                    ->step(0.01)
                    ->helperText('0–100 arası yüzde. Net tahsilat brüt tutarın bu oran kadar indirilmiş halidir.'),
            ]);
    }
}
