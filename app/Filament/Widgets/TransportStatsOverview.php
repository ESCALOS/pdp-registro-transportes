<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Drivers\DriverResource;
use App\Filament\Resources\Trucks\TruckResource;
use App\Filament\Resources\Chassis\ChassisResource;
use App\Filament\Resources\Companies\CompanyResource;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\Chassis;
use App\Enums\{CompanyStatusEnum, DriverStatusEnum, TruckStatusEnum, ChassisStatusEnum};
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransportStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Company stats merged here
            Stat::make('Empresas Pendientes', Company::where('status', CompanyStatusEnum::PENDIENTE)->count())
                ->description('En espera de validación')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->url(CompanyResource::getUrl('index')),

            Stat::make('Empresas Aprobadas', Company::where('status', CompanyStatusEnum::APROBADO)->count())
                ->description('Documentos validados')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([3, 5, 7, 9, 11, 13, 15, 17])
                ->url(CompanyResource::getUrl('index') . '?tab=aprobados'),

            Stat::make('Empresas Rechazadas', Company::where('status', CompanyStatusEnum::RECHAZADO)->count())
                ->description('Requieren correcciones')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->chart([1, 2, 1, 3, 2, 4, 3, 2])
                ->url(CompanyResource::getUrl('index') . '?tab=rechazados'),

            // totals moved to TotalsOverview

            Stat::make('Conductores Pendientes', Driver::whereIn('status', [DriverStatusEnum::PENDING_APPROVAL, DriverStatusEnum::DOCUMENT_REVIEW])->count())
                ->description('En espera de validación')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([2, 1, 3, 2, 4, 3, 2, 1])
                ->url(DriverResource::getUrl('index') . '?tab=pendientes'),

            Stat::make('Conductores Aprobados', Driver::where('status', DriverStatusEnum::ACTIVE)->count())
                ->description('Documentos validados')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([1, 3, 5, 6, 8, 9, 10, 12])
                ->url(DriverResource::getUrl('index') . '?tab=aprobados'),

            Stat::make('Conductores Rechazados', Driver::whereIn('status', [DriverStatusEnum::INACTIVE, DriverStatusEnum::NEEDS_UPDATE, DriverStatusEnum::INFECTED_DOCUMENTS])->count())
                ->description('Requieren correcciones')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->chart([0, 1, 0, 2, 1, 1, 0, 1])
                ->url(DriverResource::getUrl('index') . '?tab=rechazados'),

            // totals moved to TotalsOverview

            Stat::make('Vehículos Pendientes', Truck::whereIn('status', [TruckStatusEnum::PENDING_APPROVAL, TruckStatusEnum::DOCUMENT_REVIEW])->count())
                ->description('En espera de validación')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([1, 0, 2, 1, 3, 2, 1, 0])
                ->url(TruckResource::getUrl('index') . '?tab=pendientes'),

            Stat::make('Vehículos Aprobados', Truck::where('status', TruckStatusEnum::ACTIVE)->count())
                ->description('Documentos validados')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([2, 4, 6, 7, 9, 11, 12, 14])
                ->url(TruckResource::getUrl('index') . '?tab=aprobados'),

            Stat::make('Vehículos Rechazados', Truck::whereIn('status', [TruckStatusEnum::INACTIVE, TruckStatusEnum::NEEDS_UPDATE, TruckStatusEnum::INFECTED_DOCUMENTS])->count())
                ->description('Requieren correcciones')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->chart([0, 1, 1, 0, 2, 1, 0, 1])
                ->url(TruckResource::getUrl('index') . '?tab=rechazados'),

            // totals moved to TotalsOverview

            Stat::make('Chassis Pendientes', Chassis::whereIn('status', [ChassisStatusEnum::PENDING_APPROVAL, ChassisStatusEnum::DOCUMENT_REVIEW])->count())
                ->description('En espera de validación')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([0, 1, 0, 1, 0, 2, 1, 0])
                ->url(ChassisResource::getUrl('index') . '?tab=pendientes'),

            Stat::make('Chassis Aprobados', Chassis::where('status', ChassisStatusEnum::ACTIVE)->count())
                ->description('Documentos validados')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([1, 2, 3, 4, 5, 6, 7, 8])
                ->url(ChassisResource::getUrl('index') . '?tab=aprobados'),

            Stat::make('Chassis Rechazados', Chassis::whereIn('status', [ChassisStatusEnum::INACTIVE, ChassisStatusEnum::NEEDS_UPDATE, ChassisStatusEnum::INFECTED_DOCUMENTS])->count())
                ->description('Requieren correcciones')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->chart([0, 0, 1, 0, 1, 0, 0, 1])
                ->url(ChassisResource::getUrl('index') . '?tab=rechazados'),

            // totals moved to TotalsOverview
        ];
    }
}
