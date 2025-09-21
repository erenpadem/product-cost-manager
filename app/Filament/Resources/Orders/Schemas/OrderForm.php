<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Ürünler Repeater
            Repeater::make('items')
                ->addable(false)
                ->deletable(false)

                ->relationship('items') // Order modelinde hasMany OrderItem olmalı
                ->columns(2)
                ->schema([
                    ViewField::make('Ürün')
                        ->view('filament.orders.components.product-name')
                        ->extraAttributes([
                            'class' => 'text-gray-800 font-medium truncate',
                        ]),
                    TextInput::make('quantity')
                        ->label('Adet')
                        ->disabled(),
                ])->label('Ürünler')
                ->collapsible()
                ->columnSpanFull(),

            // Alt schema: müşteri, durum, teslim tarihi, not ve fatura
            Section::make('Sipariş Detayları')->schema([
                TextEntry::make('user.name')
                    ->label('Ad:')
                    ->disabled(),
                TextEntry::make('user.email')
                    ->label('E-posta:')
                    ->disabled(),

                Select::make('status')
                    ->label('Durum')
                    ->options([
                        'verildi' => 'Verildi',
                        'onaylandi' => 'Onaylandı',
                        'uretimde' => 'Üretimde',
                        'yolda' => 'Yolda',
                        'teslim_edildi' => 'Teslim Edildi',
                        'fatura_gonderildi' => 'Fatura Gönderildi',
                    ])
                    ->required(),

                DatePicker::make('delivery_date')
                    ->label('Teslim Tarihi'),

                RichEditor::make('note')
                    ->label('Not')
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('invoice_path')
                    ->label('Fatura')
                    ->collection('invoices')
                    ->disk(config('filesystems.default'))
                    ->acceptedFileTypes(['application/pdf'])
                    ->columnSpanFull(),
            ])->columnSpanFull(),

        ]);
    }
}
