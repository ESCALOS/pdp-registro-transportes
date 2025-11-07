<?php

namespace App\Livewire\Drivers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

class CreateDriver extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function createAction(): Action
    {
        return Action::make('createDriver')
            ->label('Crear Conductor')
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('lastname')
                    ->label('Apellido')
                    ->required(),
                TextInput::make('document_number')
                    ->label('Número de Documento')
                    ->required(),
                TextInput::make('license_number')
                    ->label('Número de Licencia')
                    ->required(),
                FileUpload::make('documents')
                    ->label('Documentos del Conductor')
                    ->multiple()
                    ->required(),
            ])
            ->action(function (array $data) {
                // Lógica para crear el conductor con los datos proporcionados
            });
    }

    public function render()
    {
        return view('livewire.drivers.create-driver');
    }
}
