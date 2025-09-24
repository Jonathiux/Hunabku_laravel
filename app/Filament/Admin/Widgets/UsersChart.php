<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UsersChart extends ChartWidget
{
    protected ?string $heading = 'Users Chart';

    protected function getData(): array
    {
        $active = User::where('is_active', true)->count();
        $inactive = User::where('is_active', false)->count();
        return [
            //
            'datasets' => [
                [
                    'label' => 'Usuarios',
                    'data' => [$active, $inactive],
                    'backgroundColor' => ['#10B981', '#EF4444'],
                    'size' => 50
                ],
            ],
            'labels' => ['Activos', 'Inactivos'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
