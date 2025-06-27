<?php

namespace App\Services;

use App\Models\Dataset;
use App\Models\DatasetDownload;
use App\Models\DatasetView;
use App\Models\User;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getPortalStats(): array
    {
        return Cache::remember('portal_stats', config('opendata.cache.ttl.stats', 3600), function () {
            return [
                'total_datasets' => Dataset::published()->count(),
                'total_downloads' => DatasetDownload::count(),
                'total_views' => DatasetView::count(),
                'total_categories' => Category::active()->count(),
                'total_organizations' => Organization::active()->count(),
                'last_update' => Dataset::published()->latest('updated_at')->value('updated_at'),
            ];
        });
    }

    public function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats', 300, function () {
            $now = now();
            $lastMonth = $now->copy()->subMonth();

            return [
                'datasets' => [
                    'total' => Dataset::count(),
                    'published' => Dataset::published()->count(),
                    'draft' => Dataset::where('status', 'draft')->count(),
                    'review' => Dataset::where('status', 'review')->count(),
                    'this_month' => Dataset::where('created_at', '>=', $lastMonth)->count(),
                ],
                'downloads' => [
                    'total' => DatasetDownload::count(),
                    'this_month' => DatasetDownload::where('downloaded_at', '>=', $lastMonth)->count(),
                    'today' => DatasetDownload::whereDate('downloaded_at', $now->toDateString())->count(),
                ],
                'views' => [
                    'total' => DatasetView::count(),
                    'this_month' => DatasetView::where('viewed_at', '>=', $lastMonth)->count(),
                    'today' => DatasetView::whereDate('viewed_at', $now->toDateString())->count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'active' => User::active()->count(),
                    'this_month' => User::where('created_at', '>=', $lastMonth)->count(),
                ],
                'organizations' => [
                    'total' => Organization::count(),
                    'active' => Organization::active()->count(),
                ],
                'categories' => [
                    'total' => Category::count(),
                    'active' => Category::active()->count(),
                ],
            ];
        });
    }

    public function getDownloadStats(): array
    {
        return Cache::remember('download_stats', 600, function () {
            return [
                'total' => DatasetDownload::count(),
                'by_month' => $this->getDownloadsByMonth(),
                'by_dataset' => $this->getTopDownloadedDatasets(),
                'by_organization' => $this->getDownloadsByOrganization(),
                'by_category' => $this->getDownloadsByCategory(),
            ];
        });
    }

    public function getViewStats(): array
    {
        return Cache::remember('view_stats', 600, function () {
            return [
                'total' => DatasetView::count(),
                'by_month' => $this->getViewsByMonth(),
                'by_dataset' => $this->getTopViewedDatasets(),
                'unique_visitors' => $this->getUniqueVisitors(),
            ];
        });
    }

    public function getTrendStats(): array
    {
        return Cache::remember('trend_stats', 1800, function () {
            $last30Days = collect(range(0, 29))->map(function ($i) {
                $date = now()->subDays($i)->toDateString();
                return [
                    'date' => $date,
                    'datasets_created' => Dataset::whereDate('created_at', $date)->count(),
                    'downloads' => DatasetDownload::whereDate('downloaded_at', $date)->count(),
                    'views' => DatasetView::whereDate('viewed_at', $date)->count(),
                ];
            })->reverse()->values();

            return [
                'daily_trends' => $last30Days,
                'growth_rates' => [
                    'datasets' => $this->calculateGrowthRate('datasets', 'created_at'),
                    'downloads' => $this->calculateGrowthRate('dataset_downloads', 'downloaded_at'),
                    'views' => $this->calculateGrowthRate('dataset_views', 'viewed_at'),
                ],
            ];
        });
    }

    private function getDownloadsByMonth(): \Illuminate\Support\Collection
    {
        return DatasetDownload::select(
            DB::raw('DATE_TRUNC(\'month\', downloaded_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('downloaded_at', '>=', now()->subYear())
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    }

    private function getViewsByMonth(): \Illuminate\Support\Collection
    {
        return DatasetView::select(
            DB::raw('DATE_TRUNC(\'month\', viewed_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('viewed_at', '>=', now()->subYear())
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    }

    private function getTopDownloadedDatasets(int $limit = 10): \Illuminate\Support\Collection
    {
        return Dataset::withCount('downloads')
            ->orderBy('downloads_count', 'desc')
            ->limit($limit)
            ->get(['id', 'title', 'downloads_count']);
    }

    private function getTopViewedDatasets(int $limit = 10): \Illuminate\Support\Collection
    {
        return Dataset::withCount('views')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get(['id', 'title', 'views_count']);
    }

    private function getDownloadsByOrganization(int $limit = 10): \Illuminate\Support\Collection
    {
        return Organization::withCount(['datasets as downloads_count' => function ($query) {
            $query->join('dataset_downloads', 'datasets.id', '=', 'dataset_downloads.dataset_id');
        }])
        ->orderBy('downloads_count', 'desc')
        ->limit($limit)
        ->get(['id', 'name', 'downloads_count']);
    }

    private function getDownloadsByCategory(int $limit = 10): \Illuminate\Support\Collection
    {
        return Category::withCount(['datasets as downloads_count' => function ($query) {
            $query->join('dataset_downloads', 'datasets.id', '=', 'dataset_downloads.dataset_id');
        }])
        ->orderBy('downloads_count', 'desc')
        ->limit($limit)
        ->get(['id', 'name', 'downloads_count']);
    }

    private function getUniqueVisitors(): int
    {
        return DatasetView::distinct('ip_address')
            ->where('viewed_at', '>=', now()->subMonth())
            ->count('ip_address');
    }

    private function calculateGrowthRate(string $table, string $dateColumn): float
    {
        $thisMonth = DB::table($table)
            ->where($dateColumn, '>=', now()->startOfMonth())
            ->count();

        $lastMonth = DB::table($table)
            ->whereBetween($dateColumn, [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ])
            ->count();

        if ($lastMonth === 0) {
            return $thisMonth > 0 ? 100 : 0;
        }

        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    public function getDatasetAnalytics(Dataset $dataset): array
    {
        return Cache::remember("dataset_analytics_{$dataset->id}", 1800, function () use ($dataset) {
            $last30Days = collect(range(0, 29))->map(function ($i) use ($dataset) {
                $date = now()->subDays($i)->toDateString();
                return [
                    'date' => $date,
                    'downloads' => DatasetDownload::where('dataset_id', $dataset->id)
                        ->whereDate('downloaded_at', $date)
                        ->count(),
                    'views' => DatasetView::where('dataset_id', $dataset->id)
                        ->whereDate('viewed_at', $date)
                        ->count(),
                ];
            })->reverse()->values();

            return [
                'total_downloads' => $dataset->downloads()->count(),
                'total_views' => $dataset->views()->count(),
                'daily_trends' => $last30Days,
                'top_resources' => $dataset->resources()
                    ->withCount('downloads')
                    ->orderBy('downloads_count', 'desc')
                    ->get(['id', 'name', 'downloads_count']),
            ];
        });
    }
}