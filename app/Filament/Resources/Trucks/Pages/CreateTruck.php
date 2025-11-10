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
        // Establecer estado inicial como DOCUMENT_REVIEW (valor 5)
        $data['status'] = TruckStatusEnum::DOCUMENT_REVIEW;

        return $data;
    }
}
