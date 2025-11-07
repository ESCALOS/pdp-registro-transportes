<?php

namespace App\Filament\Resources\Trucks\Schemas;

use App\Models\Truck;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Grid;
use Filament\Schemas\Schema;

class TruckInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextEntry::make('company_id')
                            ->label(__('Company Id'))
                            ->numeric(),
                        TextEntry::make('license_plate')
                            ->label(__('Placa')),
                        TextEntry::make('nationality')
                            ->label(__('Nacionalidad'))
                            ->placeholder('-'),
                        TextEntry::make('truck_type')
                            ->label(__('Tipo de Camión'))
                            ->placeholder('-'),
                        TextEntry::make('tare')
                            ->label(__('Tara'))
                            ->suffix(' t')
                            ->placeholder('-'),
                        IconEntry::make('is_internal')
                            ->label(__('¿Es Interno?'))
                            ->boolean(),
                        IconEntry::make('has_bonus')
                            ->label(__('¿Tiene Bonificación?'))
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
                            ->visible(fn (Truck $record): bool => $record->trashed())
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
