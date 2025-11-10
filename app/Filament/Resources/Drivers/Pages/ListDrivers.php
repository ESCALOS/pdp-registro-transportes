<?php

namespace App\Filament\Resources\Drivers\Pages;

use App\Filament\Resources\Drivers\DriverResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\Driver;
use App\Enums\DriverStatusEnum;

class ListDrivers extends ListRecords
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos'),

            'pendientes' => Tab::make('Pendientes')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [DriverStatusEnum::PENDING_APPROVAL, DriverStatusEnum::DOCUMENT_REVIEW]))
                ->badge(fn () => Driver::whereIn('status', [DriverStatusEnum::PENDING_APPROVAL, DriverStatusEnum::DOCUMENT_REVIEW])->count())
                ->badgeColor('warning'),

            'aprobados' => Tab::make('Aprobados')
                ->modifyQueryUsing(fn ($query) => $query->where('status', DriverStatusEnum::ACTIVE))
                ->badge(fn () => Driver::where('status', DriverStatusEnum::ACTIVE)->count())
                ->badgeColor('success'),

            'rechazados' => Tab::make('Rechazados')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [DriverStatusEnum::INACTIVE, DriverStatusEnum::NEEDS_UPDATE, DriverStatusEnum::INFECTED_DOCUMENTS]))
                ->badge(fn () => Driver::whereIn('status', [DriverStatusEnum::INACTIVE, DriverStatusEnum::NEEDS_UPDATE, DriverStatusEnum::INFECTED_DOCUMENTS])->count())
                ->badgeColor('danger'),
        ];
    }

    public function getDefaultActiveTab(): string
    {
        return 'pendientes';
    }
}
