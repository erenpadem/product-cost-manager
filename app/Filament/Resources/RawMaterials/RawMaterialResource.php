<?php

namespace App\Filament\Resources\RawMaterials;

use App\Filament\Resources\RawMaterials\Pages\CreateRawMaterial;
use App\Filament\Resources\RawMaterials\Pages\EditRawMaterial;
use App\Filament\Resources\RawMaterials\Pages\ListRawMaterials;
use App\Filament\Resources\RawMaterials\Schemas\RawMaterialForm;
use App\Filament\Resources\RawMaterials\Tables\RawMaterialsTable;
use App\Models\RawMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RawMaterialResource extends Resource
{

    protected static ?string $modelLabel = 'Ham Madde';

    protected static string | UnitEnum | null $navigationGroup = 'Ham Madde Yönetimi';
    protected static ?string $pluralLabel = 'Ham Maddeler';
    protected static ?string $model = RawMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Hammade';

    public static function form(Schema $schema): Schema
    {
        return RawMaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RawMaterialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRawMaterials::route('/'),
            'create' => CreateRawMaterial::route('/create'),
            'edit' => EditRawMaterial::route('/{record}/edit'),
        ];
    }
}
