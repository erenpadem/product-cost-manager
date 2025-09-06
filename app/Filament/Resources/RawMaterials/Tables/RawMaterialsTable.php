<?php

namespace App\Filament\Resources\RawMaterials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RawMaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ham Madde Adı') // Türkçe label
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Birim Fiyat') // Türkçe label
                    ->money('TRY', true)   // Türk Lirası olarak göster
                    ->sortable(),
                    TextColumn::make('unit')
                    ->label('Birim') // Türkçe label
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Güncellenme Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Düzenle'), // Türkçe
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'), // Türkçe
                ]),
            ]);
    }
}

