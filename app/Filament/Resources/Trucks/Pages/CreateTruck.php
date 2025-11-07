<?php

namespace App\Filament\Resources\Trucks\Pages;

use App\Enums\TruckStatusEnum;
use App\Filament\Resources\Trucks\TruckResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTruck extends CreateRecord
{
    protected static string $resource = TruckResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Establecer estado inicial como INACTIVE (valor 1)
        $data['status'] = TruckStatusEnum::INACTIVE;

        return $data;
    }
}
