<?php

namespace App\Filament\Widgets;

use App\Models\Dataset;
use App\Models\User;
use App\Models\DatasetDownload;
use App\Models\DatasetView;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Datasets', Dataset::count())
                ->description('Dataset yang tersedia')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            
            Stat::make('Published Datasets', Dataset::where('status', 'published')->count())
                ->description('Dataset yang dipublikasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),
            
            Stat::make('Total Users', User::count())
                ->description('Pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
            
            Stat::make('Total Downloads', DatasetDownload::count())
                ->description('Total unduhan')
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('info'),
        ];
    }
}