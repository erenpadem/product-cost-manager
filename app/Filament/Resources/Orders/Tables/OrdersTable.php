<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Kullanıcı Adı')
                    ->numeric()
                    ->sortable(),
                    TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->getStateUsing(fn($record) => match ($record->status) {
                        'verildi' => 'Sipariş Verildi',
                        'onaylandi' => 'Onaylandı',
                        'uretimde' => 'Üretimde',
                        'yolda' => 'Yolda',
                        'teslim_edildi' => 'Teslim Edildi',
                        'fatura_gonderildi' => 'Fatura Gönderildi',
                        default => $record->status,
                    })
                    ->colors([
                        'primary'   => fn($state) => $state === 'Sipariş Verildi',
                        'success'   => fn($state) => $state === 'Teslim Edildi',
                        'warning'   => fn($state) => $state === 'Üretimde',
                        'info'      => fn($state) => $state === 'Yolda',
                        'secondary' => fn($state) => $state === 'Onaylandı',
                        'success'    => fn($state) => $state === 'Fatura Gönderildi',
                    ])
                    ->searchable(),
                TextColumn::make('delivery_date')
                    ->label('Teslim Tarihi')
                    ->date()
                    ->sortable(),
                TextColumn::make('invoice_path')
                    ->label('Fatura Yolu')
                    ->searchable(),
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
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'verildi'          => 'Verildi',
                        'onaylandi'        => 'Onaylandı',
                        'uretimde'         => 'Üretimde',
                        'yolda'            => 'Yolda',
                        'teslim_edildi'    => 'Teslim Edildi',
                        'fatura_gonderildi' => 'Fatura Gönderildi',
                    ]),
            ])
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
