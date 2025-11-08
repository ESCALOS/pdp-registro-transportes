<?php

namespace App\Filament\Resources\Trucks\Pages;

use App\Filament\Resources\Trucks\TruckResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\Truck;
use App\Enums\TruckStatusEnum;

class ListTrucks extends ListRecords
{
    protected static string $resource = TruckResource::class;

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
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [TruckStatusEnum::PENDING_APPROVAL, TruckStatusEnum::DOCUMENT_REVIEW]))
                ->badge(fn () => Truck::whereIn('status', [TruckStatusEnum::PENDING_APPROVAL, TruckStatusEnum::DOCUMENT_REVIEW])->count())
                ->badgeColor('warning'),

            'aprobados' => Tab::make('Aprobados')
                ->modifyQueryUsing(fn ($query) => $query->where('status', TruckStatusEnum::ACTIVE))
                ->badge(fn () => Truck::where('status', TruckStatusEnum::ACTIVE)->count())
                ->badgeColor('success'),

            'rechazados' => Tab::make('Rechazados')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [TruckStatusEnum::INACTIVE, TruckStatusEnum::NEEDS_UPDATE, TruckStatusEnum::INFECTED_DOCUMENTS]))
                ->badge(fn () => Truck::whereIn('status', [TruckStatusEnum::INACTIVE, TruckStatusEnum::NEEDS_UPDATE, TruckStatusEnum::INFECTED_DOCUMENTS])->count())
                ->badgeColor('danger'),
        ];
    }

    public function getDefaultActiveTab(): string
    {
        return 'pendientes';
    }
}
