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
        // Establecer estado inicial como DOCUMENT_REVIEW (valor 5)
        $data['status'] = ChassisStatusEnum::DOCUMENT_REVIEW;

        return $data;
    }
}
