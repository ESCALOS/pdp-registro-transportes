<?php

namespace App\Livewire\Drivers;

use App\Models\Driver;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListDrivers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    protected $listeners = ['driver-created' => '$refresh'];

    public function table(Table $table): Table
    {
        return $table
            ->query(Driver::query()->where('company_id', Auth::user()->company_id))
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('full_name')->label('Nombre Completo')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('document_number')->label('Número de Documento')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('license_number')->label('Número de Licencia')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('status')->label('Estado')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Creado En')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }

    public function render(): View
    {
        return view('livewire.drivers.list-drivers');
    }
}
