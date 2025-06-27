<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Category;
use App\Models\Organization;
use App\Models\DatasetDownload;
use App\Models\DatasetView;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        $stats = Cache::remember('portal_stats', config('opendata.cache.ttl.stats'), function () {
            return [
                'total_datasets' => Dataset::published()->count(),
                'total_downloads' => DatasetDownload::count(),
                'total_views' => DatasetView::count(),
                'total_categories' => Category::active()->count(),
                'total_organizations' => Organization::active()->count(),
                'last_update' => Dataset::published()->latest('updated_at')->value('updated_at'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Portal statistics retrieved successfully',
        ]);
    }

    public function dashboard(): JsonResponse
    {
        $this->authorize('view analytics');

        $stats = Cache::remember('dashboard_stats', 300, function () { // 5 minutes cache
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

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Dashboard statistics retrieved successfully',
        ]);
    }

    public function downloads(): JsonResponse
    {
        $this->authorize('view analytics');

        $stats = Cache::remember('download_stats', 600, function () { // 10 minutes cache
            return [
                'total' => DatasetDownload::count(),
                'by_month' => DatasetDownload::select(
                    DB::raw('DATE_TRUNC(\'month\', downloaded_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('downloaded_at', '>=', now()->subYear())
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
                'by_dataset' => Dataset::withCount('downloads')
                    ->orderBy('downloads_count', 'desc')
                    ->limit(10)
                    ->get(['id', 'title', 'downloads_count']),
                'by_organization' => Organization::withCount(['datasets as downloads_count' => function ($query) {
                    $query->join('dataset_downloads', 'datasets.id', '=', 'dataset_downloads.dataset_id');
                }])
                ->orderBy('downloads_count', 'desc')
                ->limit(10)
                ->get(['id', 'name', 'downloads_count']),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Download statistics retrieved successfully',
        ]);
    }

    public function views(): JsonResponse
    {
        $this->authorize('view analytics');

        $stats = Cache::remember('view_stats', 600, function () { // 10 minutes cache
            return [
                'total' => DatasetView::count(),
                'by_month' => DatasetView::select(
                    DB::raw('DATE_TRUNC(\'month\', viewed_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('viewed_at', '>=', now()->subYear())
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
                'by_dataset' => Dataset::withCount('views')
                    ->orderBy('views_count', 'desc')
                    ->limit(10)
                    ->get(['id', 'title', 'views_count']),
                'unique_visitors' => DatasetView::distinct('ip_address')
                    ->where('viewed_at', '>=', now()->subMonth())
                    ->count('ip_address'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'View statistics retrieved successfully',
        ]);
    }

    public function trends(): JsonResponse
    {
        $this->authorize('view analytics');

        $trends = Cache::remember('trend_stats', 1800, function () { // 30 minutes cache
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

        return response()->json([
            'success' => true,
            'data' => $trends,
            'message' => 'Trend statistics retrieved successfully',
        ]);
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
}