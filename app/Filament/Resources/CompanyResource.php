<?php

namespace App\Filament\Resources;

use App\Enums\CompanyStatusEnum;
use App\Enums\CompanyTypeEnum;
use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Empresas';

    protected static ?string $modelLabel = 'Empresa';

    protected static ?string $pluralModelLabel = 'Empresas';

    public static function schema(): array
    {
        return [
                Forms\Components\TextInput::make('ruc')
                    ->label('RUC')
                    ->required()
                    ->maxLength(11),

                Forms\Components\TextInput::make('business_name')
                    ->label('Razón Social')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_legal_entity')
                    ->label('Persona Jurídica')
                    ->default(false),

                Forms\Components\TextInput::make('legal_representative')
                    ->label('Representante Legal')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ruc')
                    ->label('RUC')
                    ->searchable(),

                Tables\Columns\TextColumn::make('business_name')
                    ->label('Razón Social')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo de Empresa')
                    ->options(CompanyTypeEnum::class),
            ])
            ->recordActions([
                Action::make('validate')
                    ->label('Validar')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->url(fn (Company $record): string => static::getUrl('view', ['record' => $record]))
                    ->visible(fn (Company $record): bool => $record->status !== CompanyStatusEnum::APROBADO),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
            'view' => Pages\ViewCompany::route('/{record}/validate'),
        ];
    }
}
