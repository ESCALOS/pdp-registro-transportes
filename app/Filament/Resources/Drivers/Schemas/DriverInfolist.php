<?php

namespace App\Filament\Resources\Drivers\Schemas;

use App\Models\Driver;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DriverInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('company_id')
                    ->label(__('Company Id'))
                    ->numeric(),
                TextEntry::make('document_type')
                    ->label(__('Document Type'))
                    ->badge(),
                TextEntry::make('document_number')
                    ->label(__('Document Number')),
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('lastname')
                    ->label(__('Lastname')),
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
                    ->visible(fn (Driver $record): bool => $record->trashed()),
            ]);
    }
}
