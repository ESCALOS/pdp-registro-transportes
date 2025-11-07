<?php

namespace App\Filament\Resources\Chassis;

use App\Filament\Resources\Chassis\Pages\CreateChassis;
use App\Filament\Resources\Chassis\Pages\EditChassis;
use App\Filament\Resources\Chassis\Pages\ListChassis;
use App\Filament\Resources\Chassis\Pages\ViewChassis;
use App\Filament\Resources\Chassis\Schemas\ChassisForm;
use App\Filament\Resources\Chassis\Schemas\ChassisInfolist;
use App\Filament\Resources\Chassis\Tables\ChassisTable;
use App\Models\Chassis as ChassisModel;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChassisResource extends Resource
{
    protected static ?string $model = ChassisModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Chassis';

    protected static ?string $modelLabel = 'Chassis';

    protected static ?string $pluralModelLabel = 'Chassis';

    protected static string|UnitEnum|null $navigationGroup = 'Gestión de Transporte';

    protected static ?string $recordTitleAttribute = 'license_plate';

    public static function form(Schema $schema): Schema
    {
        return ChassisForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChassisInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChassisTable::configure($table);
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
            'index' => ListChassis::route('/'),
            'create' => CreateChassis::route('/create'),
            'view' => ViewChassis::route('/{record}'),
            'edit' => EditChassis::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
