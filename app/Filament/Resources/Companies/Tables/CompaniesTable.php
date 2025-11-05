<?php

namespace App\Filament\Resources\Companies\Tables;

use App\Enums\CompanyStatusEnum;
use App\Enums\CompanyTypeEnum;
use App\Filament\Resources\Companies\CompanyResource;
use App\Models\Company;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
                TextColumn::make('ruc')
                    ->label('RUC')
                    ->searchable(),

                TextColumn::make('business_name')
                    ->label('Razón Social')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo de Empresa')
                    ->options(CompanyTypeEnum::class),
            ])
            ->recordActions([
                Action::make('validate')
                    ->label('Validar')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->url(fn (Company $record): string => CompanyResource::getUrl('view', ['record' => $record]))
                    ->visible(fn (Company $record): bool => $record->status !== CompanyStatusEnum::APROBADO),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
