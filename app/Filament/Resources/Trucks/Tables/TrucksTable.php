<?php

namespace App\Filament\Resources\Trucks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TrucksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.business_name')
                    ->label(__('Company'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('license_plate')
                    ->label(__('Placa'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('truck_type')
                    ->label(__('Tipo'))
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_internal')
                    ->label(__('Interno'))
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('has_bonus')
                    ->label(__('Bonificación'))
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('documents_count')
                    ->label(__('Documents'))
                    ->counts('documents')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('Deleted At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->poll('5s');
    }
}
