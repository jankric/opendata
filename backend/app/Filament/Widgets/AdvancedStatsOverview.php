<?php

namespace App\Filament\Widgets;

use App\Models\Dataset;
use App\Models\User;
use App\Models\DatasetDownload;
use App\Models\DatasetView;
use App\Models\Category;
use App\Models\Organization;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdvancedStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalDatasets = Dataset::count();
        $publishedDatasets = Dataset::where('status', 'published')->count();
        $totalUsers = User::count();
        $totalDownloads = DatasetDownload::count();
        $totalViews = DatasetView::count();
        $totalCategories = Category::active()->count();
        $totalOrganizations = Organization::active()->count();

        // Calculate growth percentages
        $lastMonthDatasets = Dataset::where('created_at', '>=', now()->subMonth())->count();
        $lastMonthDownloads = DatasetDownload::where('downloaded_at', '>=', now()->subMonth())->count();
        $lastMonthViews = DatasetView::where('viewed_at', '>=', now()->subMonth())->count();
        $lastMonthUsers = User::where('created_at', '>=', now()->subMonth())->count();

        return [
            Stat::make('Total Datasets', $totalDatasets)
                ->description($lastMonthDatasets . ' dataset baru bulan ini')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Published Datasets', $publishedDatasets)
                ->description(round(($publishedDatasets / max($totalDatasets, 1)) * 100, 1) . '% dari total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary')
                ->chart([2, 10, 3, 15, 4, 17, 7]),
            
            Stat::make('Total Downloads', number_format($totalDownloads))
                ->description($lastMonthDownloads . ' unduhan bulan ini')
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('info')
                ->chart([15, 4, 17, 7, 2, 10, 3]),
            
            Stat::make('Total Views', number_format($totalViews))
                ->description($lastMonthViews . ' views bulan ini')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning')
                ->chart([4, 17, 7, 2, 10, 3, 15]),

            Stat::make('Active Users', $totalUsers)
                ->description($lastMonthUsers . ' user baru bulan ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([10, 3, 15, 4, 17, 7, 2]),

            Stat::make('Categories', $totalCategories)
                ->description('Kategori aktif')
                ->descriptionIcon('heroicon-m-tag')
                ->color('gray'),

            Stat::make('Organizations', $totalOrganizations)
                ->description('Organisasi aktif')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('gray'),

            Stat::make('Avg Downloads/Dataset', $totalDatasets > 0 ? round($totalDownloads / $totalDatasets, 1) : 0)
                ->description('Rata-rata unduhan per dataset')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}