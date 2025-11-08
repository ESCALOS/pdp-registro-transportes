<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\Drivers\DriverResource;
use App\Filament\Resources\Trucks\TruckResource;
use App\Filament\Resources\Chassis\ChassisResource;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\Chassis;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Empresas', Company::count())
                ->description('Empresas registradas')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('primary')
                ->url(CompanyResource::getUrl('index') . '?tab=todos'),

            Stat::make('Total Conductores', Driver::count())
                ->description('Drivers registrados')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary')
                ->url(DriverResource::getUrl('index') . '?tab=todos'),

            Stat::make('Total Vehículos', Truck::count())
                ->description('Vehículos registrados')
                ->descriptionIcon('heroicon-o-truck')
                ->color('primary')
                ->url(TruckResource::getUrl('index') . '?tab=todos'),

            Stat::make('Total Chassis', Chassis::count())
                ->description('Chassis registrados')
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary')
                ->url(ChassisResource::getUrl('index') . '?tab=todos'),
        ];
    }
}
