<?php

namespace App\Filament\Resources\Chassis\Pages;

use App\Filament\Resources\Chassis\ChassisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChassis extends ListRecords
{
    protected static string $resource = ChassisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
