<?php

namespace App\Filament\Resources\Chassis\Schemas;

use App\Models\Chassis;
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
