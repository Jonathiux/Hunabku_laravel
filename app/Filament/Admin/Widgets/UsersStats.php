<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make(
                User::where('is_active', true)->count(), // valor
                'Usuarios Activos' // label
            )->description('Actualmente en línea')
             ->color('success'),

            Stat::make(
                User::where('is_active', false)->count(),
                'Usuarios Inactivos'
            )->description('No conectados')
             ->color('danger'),

            Stat::make(
                User::count(),
                'Total Usuarios'
            )->description('Registrados en la plataforma')
             ->color('primary'),
        ];
    }
}
