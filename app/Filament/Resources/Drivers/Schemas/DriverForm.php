<?php

namespace App\Filament\Resources\Drivers\Schemas;

use App\Enums\DriverDocumentTypeEnum;
use App\Enums\DriverStatusEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_id')
                    ->label(__('Company Id'))
                    ->required()
                    ->numeric(),
                Select::make('document_type')
                    ->label(__('Document Type'))
                    ->options(DriverDocumentTypeEnum::class)
                    ->required(),
                TextInput::make('document_number')
                    ->label(__('Document Number'))
                    ->required()
                    ->unique('drivers', 'document_number', ignoreRecord: true, modifyRuleUsing: function ($rule, $get) {
                        return $rule->where('company_id', $get('company_id'));
                    })
                    ->validationMessages([
                        'unique' => 'Ya existe un conductor registrado con este número de documento en esta empresa.',
                    ]),
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('lastname')
                    ->label(__('Lastname'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->nullable(),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel()
                    ->nullable(),
                Select::make('status')
                    ->label(__('Status'))
                    ->options(DriverStatusEnum::class)
                    ->default(1)
                    ->required(),
            ]);
    }
}
