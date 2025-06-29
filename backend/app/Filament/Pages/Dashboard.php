<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AdvancedStatsOverview::class,
            \App\Filament\Widgets\DatasetChart::class,
            \App\Filament\Widgets\PopularDatasetsChart::class,
            \App\Filament\Widgets\LatestDatasets::class,
            \App\Filament\Widgets\RecentActivity::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }
}