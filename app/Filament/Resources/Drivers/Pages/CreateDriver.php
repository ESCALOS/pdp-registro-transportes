<?php

namespace App\Filament\Resources\Drivers\Pages;

use App\Enums\DriverStatusEnum;
use App\Filament\Resources\Drivers\DriverResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDriver extends CreateRecord
{
    protected static string $resource = DriverResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Establecer estado inicial como DOCUMENT_REVIEW (valor 5)
        $data['status'] = DriverStatusEnum::DOCUMENT_REVIEW;

        return $data;
    }
}
