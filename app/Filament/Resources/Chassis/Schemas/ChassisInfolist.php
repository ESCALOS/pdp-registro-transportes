<?php

namespace App\Filament\Resources\Chassis\Schemas;

use App\Models\Chassis;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ChassisInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('company_id')
                    ->label(__('Company Id'))
                    ->numeric(),
                TextEntry::make('license_plate')
                    ->label(__('License Plate')),
                TextEntry::make('vehicle_type')
                    ->label(__('Tipo de Vehículo'))
                    ->placeholder('-'),
                TextEntry::make('axle_count')
                    ->label(__('Número de Ejes'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tare')
                    ->label(__('Tara (ton)'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' ton')
                    ->placeholder('-'),
                TextEntry::make('safe_weight')
                    ->label(__('Peso Seguro (ton)'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' ton')
                    ->placeholder('-'),
                TextEntry::make('height')
                    ->label(__('Alto (m)'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' m')
                    ->placeholder('-'),
                TextEntry::make('length')
                    ->label(__('Largo (m)'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' m')
                    ->placeholder('-'),
                TextEntry::make('width')
                    ->label(__('Ancho (m)'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' m')
                    ->placeholder('-'),
                TextEntry::make('material')
                    ->label(__('Material'))
                    ->placeholder('-'),
                IconEntry::make('is_insulated')
                    ->label(__('¿Está Aislado?'))
                    ->boolean(),
                IconEntry::make('has_bonus')
                    ->label(__('¿Tiene Bonificación?'))
                    ->boolean(),
                IconEntry::make('accepts_20ft')
                    ->label(__('¿Acepta 20\'?'))
                    ->boolean(),
                IconEntry::make('accepts_40ft')
                    ->label(__('¿Acepta 40\'?'))
                    ->boolean(),
                TextEntry::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextEntry::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted At'))
                    ->dateTime()
                    ->visible(fn (Chassis $record): bool => $record->trashed()),
            ]);
    }
}
