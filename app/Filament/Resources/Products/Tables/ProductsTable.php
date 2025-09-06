<?php

namespace App\Filament\Resources\Products\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make(name: 'product')
                ->label('Görsel')
                ->collection('products'),
                TextColumn::make('name')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('total_grams')
                    ->label('Toplam Gramaj')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('cost')
                    ->label('Toplam Maliyet')
                    ->money('TRY')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Ürün Türü')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'final' => 'success',
                        'semi'  => 'gray',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'final' => 'Tam Ürün',
                        'semi'  => 'Yarı Ürün',
                        default => $state,
                    })
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Güncellenme Tarihi')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Ürün Türü')
                    ->options([
                        'semi' => 'Yarı Ürün',
                        'final' => 'Final Ürün',
                    ]),




                Filter::make('cost')
                    ->label('Maliyet Aralığı')
                    ->form([
                        TextInput::make('min')
                            ->label('Minimum Fiyat')
                            ->numeric()
                            ->reactive(), // Livewire ile anlık güncelleme

                        TextInput::make('max')
                            ->label('Maksimum Fiyat')
                            ->numeric()
                            ->reactive(),
                    ])
                    ->query(function ($query, array $data) {
                        $min = $data['min'] ?? null;
                        $max = $data['max'] ?? null;

                        if ($min !== null) {
                            $query->where('cost', '>=', $min);
                        }
                        if ($max !== null) {
                            $query->where('cost', '<=', $max);
                        }
                    })
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Düzenle'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
