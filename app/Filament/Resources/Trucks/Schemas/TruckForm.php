<?php

namespace App\Filament\Resources\Trucks\Schemas;

use App\Enums\TruckStatusEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TruckForm
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
                    ->label(__('Placa'))
                    ->required()
                    ->maxLength(10)
                    ->unique('trucks', 'license_plate', ignoreRecord: true, modifyRuleUsing: function ($rule, $get) {
                        return $rule->where('company_id', $get('company_id'));
                    })
                    ->validationMessages([
                        'unique' => 'Ya existe un vehículo registrado con esta placa en esta empresa.',
                    ]),
                TextInput::make('nationality')
                    ->label(__('Nacionalidad'))
                    ->required()
                    ->maxLength(50),
                Select::make('truck_type')
                    ->label(__('Tipo de Camión'))
                    ->required()
                    ->options([
                        'T3' => 'T3',
                        'T-Especial' => 'T-Especial',
                        'T2' => 'T2',
                        'Otro' => 'Otro',
                    ]),
                TextInput::make('tare')
                    ->label(__('Tara (Toneladas)'))
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->suffix('t'),
                Toggle::make('is_internal')
                    ->label(__('¿Es Interno?'))
                    ->default(false)
                    ->inline(false),
                Toggle::make('has_bonus')
                    ->label(__('¿Tiene Bonificación?'))
                    ->default(false)
                    ->inline(false),
                Select::make('status')
                    ->label(__('Status'))
                    ->options(TruckStatusEnum::class)
                    ->default(1)
                    ->required(),
            ]);
    }
}
