<?php

namespace App\Filament\Resources\Chassis\Pages;

use App\Enums\ChassisStatusEnum;
use App\Filament\Resources\Chassis\ChassisResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChassis extends CreateRecord
{
    protected static string $resource = ChassisResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Establecer estado inicial como INACTIVE (valor 1)
        $data['status'] = ChassisStatusEnum::INACTIVE;

        return $data;
    }
}
