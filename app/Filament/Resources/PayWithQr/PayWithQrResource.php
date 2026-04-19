<?php

namespace App\Filament\Resources\PayWithQr;

use App\Filament\Resources\PayWithQr\Pages\CreatePayWithQr;
use App\Filament\Resources\PayWithQr\Pages\EditPayWithQr;
use App\Filament\Resources\PayWithQr\Pages\ListPayWithQr;
use App\Filament\Resources\PayWithQr\Schemas\PayWithQrForm;
use App\Filament\Resources\PayWithQr\Tables\PayWithQrTable;
use App\Models\PayWithQr;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PayWithQrResource extends Resource
{
    protected static ?string $model = PayWithQr::class;

    protected static string|UnitEnum|null $navigationGroup = 'Ödemeler';

    protected static ?string $navigationLabel = 'QR ödemeleri';

    protected static ?string $modelLabel = 'QR ödemesi';

    protected static ?string $pluralLabel = 'QR ödemeleri';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PayWithQrForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayWithQrTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayWithQr::route('/'),
            'create' => CreatePayWithQr::route('/create'),
            'edit' => EditPayWithQr::route('/{record}/edit'),
        ];
    }
}
