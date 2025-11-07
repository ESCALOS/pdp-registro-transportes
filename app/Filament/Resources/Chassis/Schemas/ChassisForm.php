<?php

namespace App\Filament\Resources\Chassis\Schemas;

use App\Enums\ChassisStatusEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChassisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_id')
                    ->label(__('Company Id'))
                    ->required()
                    ->numeric(),
                TextInput::make('license_plate')
                    ->label(__('License Plate'))
                    ->required()
                    ->maxLength(10)
                    ->unique('chassis', 'license_plate', ignoreRecord: true, modifyRuleUsing: function ($rule, $get) {
                        return $rule->where('company_id', $get('company_id'));
                    })
                    ->validationMessages([
                        'unique' => 'Ya existe un chassis registrado con esta placa en esta empresa.',
                    ]),
                Select::make('status')
                    ->label(__('Status'))
                    ->options(ChassisStatusEnum::class)
                    ->default(1)
                    ->required(),
            ]);
    }
}
