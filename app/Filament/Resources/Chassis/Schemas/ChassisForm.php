<?php

namespace App\Filament\Resources\Chassis\Schemas;

use App\Enums\ChassisStatusEnum;
use Filament\Forms\Components\Checkbox;
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
                TextInput::make('vehicle_type')
                    ->label(__('Tipo de Vehículo'))
                    ->maxLength(100)
                    ->placeholder('Ej: Tolva, Plataforma'),
                TextInput::make('axle_count')
                    ->label(__('Número de Ejes'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->placeholder('Ej: 2, 3, 4'),
                TextInput::make('tare')
                    ->label(__('Tara (toneladas)'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->placeholder('Ej: 8.5'),
                TextInput::make('safe_weight')
                    ->label(__('Peso Seguro (toneladas)'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->placeholder('Ej: 25.0'),
                TextInput::make('height')
                    ->label(__('Alto (metros)'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->placeholder('Ej: 2.5'),
                TextInput::make('length')
                    ->label(__('Largo (metros)'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->placeholder('Ej: 12.0'),
                TextInput::make('width')
                    ->label(__('Ancho (metros)'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->placeholder('Ej: 2.4'),
                TextInput::make('material')
                    ->label(__('Material'))
                    ->maxLength(100)
                    ->placeholder('Ej: Acero, Aluminio'),
                Checkbox::make('is_insulated')
                    ->label(__('¿Está Aislado?'))
                    ->default(false),
                Checkbox::make('has_bonus')
                    ->label(__('¿Tiene Bonificación?'))
                    ->default(false),
                Checkbox::make('accepts_20ft')
                    ->label(__('¿Acepta 20\'?'))
                    ->default(false),
                Checkbox::make('accepts_40ft')
                    ->label(__('¿Acepta 40\'?'))
                    ->default(false),
                Select::make('status')
                    ->label(__('Status'))
                    ->options(ChassisStatusEnum::class)
                    ->default(1)
                    ->required(),
            ]);
    }
}
