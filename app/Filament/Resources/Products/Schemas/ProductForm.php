<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\RawMaterial;
use App\Models\Product;
use App\Services\ProductService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ürün Görseli')->schema([
                SpatieMediaLibraryFileUpload::make('products')
                    ->label('Ürün Görseli')
                    ->multiple()
                    ->disk(config('filesystems.default'))  // Laravel default disk
                    ->conversionsDisk(config('filesystems.default')) // conversion dosyaları aynı diskte
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/webp'])
                    ->imageCropAspectRatio('16:9') // Kare oran, ihtiyacına göre değiştirilebilir
                    ->collection('products')      // collection adı modeldeki ile aynı olmalı
                    ->conversion('preview')        // modeldeki registerMediaConversions ile eşleşmeli
                    ->customProperties([
                        'custom_headers' => [
                            'ACL' => 'public-read',
                            'Cache-Control' => '31536000',
                        ],
                    ]),
            ])->columnSpanFull(),
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->label('Ürün Adı')
                    ->required()
                    ->placeholder('Ürünün adını girin'),
                Select::make('type')
                    ->label('Ürün Türü')
                    ->options([
                        'semi' => 'Yarı Ürün',
                        'final' => 'Final Ürün',
                    ])
                    ->default('final')
                    ->required(),
            ])->columnSpanFull(),
            
            RichEditor::make('description')
            ->label('Ürün Açıklaması')

            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                ['table', 'attachFiles'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                ['undo', 'redo'],
            ])->columnSpanFull(),
            
            RichEditor::make('notes')
                ->label('Ürün Notları')

                ->toolbarButtons([
                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                    ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                    ['table', 'attachFiles'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                    ['undo', 'redo'],
                ])->columnSpanFull(),



            Section::make('Tarif / Ham Maddeler')->schema([
                Repeater::make('main_ingredients')
                    ->label('Malzemeler')
                    ->minItems(1)
                    ->addActionLabel('Malzeme Ekle')
                    ->schema([
                        Grid::make(4)->schema([
                            Select::make('type')
                                ->label('Tür')
                                ->options([
                                    'raw' => 'Ham Madde',
                                    'product' => 'Ürün',
                                ])
                                ->reactive()
                                ->afterStateUpdated(fn($state, $set) => $set('item_id', null)),

                            Select::make('item_id')
                                ->label('Malzeme / Ürün Seç')
                                ->options(function ($get) {
                                    $type = $get('type');
                                    return ProductForm::getIngredientOptions($type);
                                })
                                ->searchable()
                                ->reactive()
                                ->required(),

                            TextInput::make('amount')
                                ->label('Miktar')
                                ->numeric()
                                ->reactive()
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    $ingredients = $get('main_ingredients') ?? [];
                                    $set('total_grams', ProductService::calculateTotalGrams($ingredients));
                                    $set('cost', ProductService::calculateCost($ingredients));
                                })
                                ->required(),

                            Select::make('unit')
                                ->label('Birim')
                                ->options([
                                    'g' => 'Gram',
                                    'kg' => 'Kilogram',
                                    'ml' => 'Mililitre',
                                    'l' => 'Litre',
                                    'kw' => 'KW',
                                ])
                                ->reactive()
                                ->required(),
                        ]),
                    ]),
            ])->collapsible()->columnSpanFull(),

            

            Repeater::make('ingredients')
            ->columnSpanFull()
            ->label('Kullanıcının göreceği tarif (zorunlu değil)')
                ->schema([
                    TextInput::make('value')
                        ->label('İçerik')
                        ->required(),
                ])
                ->addActionLabel('Yeni İçerik Ekle')
                ->nullable()
                ->collapsed()
                ->helperText('Bu alan sadece kullanıcının görmesi içindir.'),

            Section::make('Özet')->schema([
                Grid::make(2)->schema([
                    TextInput::make('total_grams')
                        ->label('Toplam Gram')
                        ->disabled()
                        ->reactive(),

                    TextInput::make('cost')
                        ->label('Toplam Maliyet')
                        ->disabled()
                        ->prefix('₺')
                        ->reactive(),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function getIngredientOptions(?string $type): array
    {
        if ($type === 'raw') {
            return RawMaterial::all()->pluck('name', 'id')->toArray();
        } elseif ($type === 'product') {
            return Product::all()->pluck('name', 'id')->toArray();
        }
        return [];
    }
}
