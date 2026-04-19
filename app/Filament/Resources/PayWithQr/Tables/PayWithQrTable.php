<?php

namespace App\Filament\Resources\PayWithQr\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PayWithQrTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('toplam_tutar')
                    ->label('Toplam tutar')
                    ->numeric(2)
                    ->prefix('₺')
                    ->sortable(),
                TextColumn::make('indirim_oranı')
                    ->label('İndirim oranı')
                    ->numeric(2)
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('net_tutar')
                    ->label('Net tahsilat')
                    ->state(fn ($record): float => (float) $record->net_tutar)
                    ->numeric(2)
                    ->prefix('₺')
                    ->sortable(true, function (Builder $query, string $direction): void {
                        $dir = strtolower($direction) === 'desc' ? 'desc' : 'asc';
                        $query->orderByRaw('(toplam_tutar * (1 - (indirim_oranı / 100))) '.$dir);
                    }),
                TextColumn::make('created_at')
                    ->label('Kayıt tarihi')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make()->label('Düzenle'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Sil'),
                ]),
            ]);
    }
}
