<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('İsim') // Türkçeleştirildi
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-posta Adresi') // Türkçeleştirildi
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label('E-posta Doğrulama Tarihi') // Türkçeleştirildi
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi') // Türkçeleştirildi
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Güncellenme Tarihi') // Türkçeleştirildi
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label('Düzenle'), // Türkçeleştirildi
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Sil'), // Türkçeleştirildi
                ]),
            ]);
    }
}
