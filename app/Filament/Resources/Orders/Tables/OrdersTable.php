<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                    ->colors([
                        'primary'   => 'verildi',
                        'success'   => 'teslim_edildi',
                        'warning'   => 'uretimde',
                        'info'      => 'yolda',
                        'secondary' => 'onaylandi',
                        'danger'    => 'fatura_gonderildi',
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
                    'fatura_gonderildi'=> 'Fatura Gönderildi',
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
