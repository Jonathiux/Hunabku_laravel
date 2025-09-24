<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Admin\Widgets\UsersStats;
use App\Filament\Admin\Widgets\UsersChart;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [    
            UsersStats::class,
            UsersChart::class,
        ];
    }
}
