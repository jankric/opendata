<?php

namespace App\Services;

use App\Models\Dataset;
use App\Models\User;
use App\Models\DatasetView;
use App\Models\DatasetDownload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\UploadedFile;

class DatasetService
{
    public function createDataset(array $data, User $user): Dataset
    {
        $dataset = Dataset::create([
            ...$data,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // Clear relevant caches
        $this->clearDatasetCaches();

        return $dataset->load(['category', 'organization', 'creator']);
    }

    public function updateDataset(Dataset $dataset, array $data, User $user): Dataset
    {
        $dataset->update([
            ...$data,
            'updated_by' => $user->id,
        ]);

        // Clear relevant caches
        $this->clearDatasetCaches();

        return $dataset->fresh(['category', 'organization', 'creator']);
    }

    public function publishDataset(Dataset $dataset, User $user): Dataset
    {
        $dataset->update([
            'status' => Dataset::STATUS_PUBLISHED,
            'published_at' => now(),
            'updated_by' => $user->id,
        ]);

        // Clear caches
        $this->clearDatasetCaches();

        return $dataset->fresh();
    }

    public function approveDataset(Dataset $dataset, User $user): Dataset
    {
        $dataset->update([
            'status' => Dataset::STATUS_PUBLISHED,
            'approved_by' => $user->id,
            'approved_at' => now(),
            'published_at' => now(),
            'updated_by' => $user->id,
        ]);

        // Clear caches
        $this->clearDatasetCaches();

        return $dataset->fresh();
    }

    public function recordView(Dataset $dataset, ?User $user = null, ?string $ipAddress = null): void
    {
        // Prevent duplicate views from same IP within 1 hour
        $cacheKey = "dataset_view_{$dataset->id}_{$ipAddress}";
        
        if (!Cache::has($cacheKey)) {
            DatasetView::create([
                'dataset_id' => $dataset->id,
                'user_id' => $user?->id,
                'ip_address' => $ipAddress,
                'user_agent' => request()->userAgent(),
                'viewed_at' => now(),
            ]);

            Cache::put($cacheKey, true, 3600); // 1 hour
        }
    }

    public function recordDownload(Dataset $dataset, ?User $user = null, ?string $ipAddress = null, $resource = null): void
    {
        DatasetDownload::create([
            'dataset_id' => $dataset->id,
            'resource_id' => $resource?->id,
            'user_id' => $user?->id,
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        // Clear stats cache
        Cache::forget('portal_stats');
        Cache::forget('dashboard_stats');
    }

    public function getPopularDatasets(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("popular_datasets_{$limit}", 3600, function () use ($limit) {
            return Dataset::published()
                ->with(['category', 'organization', 'creator'])
                ->withCount(['downloads', 'views', 'resources'])
                ->orderBy('downloads_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    public function getRecentDatasets(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("recent_datasets_{$limit}", 1800, function () use ($limit) {
            return Dataset::published()
                ->with(['category', 'organization', 'creator'])
                ->withCount(['downloads', 'views', 'resources'])
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    public function getFeaturedDatasets(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("featured_datasets_{$limit}", 3600, function () use ($limit) {
            return Dataset::published()
                ->featured()
                ->with(['category', 'organization', 'creator'])
                ->withCount(['downloads', 'views', 'resources'])
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    public function searchDatasets(string $query, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $builder = Dataset::published()
            ->with(['category', 'organization', 'creator'])
            ->withCount(['downloads', 'views', 'resources'])
            ->search($query);

        // Apply filters
        if (!empty($filters['category'])) {
            $builder->where('category_id', $filters['category']);
        }

        if (!empty($filters['organization'])) {
            $builder->where('organization_id', $filters['organization']);
        }

        if (!empty($filters['tags'])) {
            $tags = is_array($filters['tags']) ? $filters['tags'] : [$filters['tags']];
            foreach ($tags as $tag) {
                $builder->whereJsonContains('tags', $tag);
            }
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'published_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        switch ($sortBy) {
            case 'downloads':
                $builder->orderBy('downloads_count', $sortOrder);
                break;
            case 'views':
                $builder->orderBy('views_count', $sortOrder);
                break;
            case 'title':
                $builder->orderBy('title', $sortOrder);
                break;
            default:
                $builder->orderBy('published_at', $sortOrder);
        }

        return $builder->paginate($filters['per_page'] ?? 20);
    }

    private function clearDatasetCaches(): void
    {
        Cache::forget('portal_stats');
        Cache::forget('dashboard_stats');
        
        // Clear popular and recent datasets cache
        for ($i = 5; $i <= 20; $i += 5) {
            Cache::forget("popular_datasets_{$i}");
            Cache::forget("recent_datasets_{$i}");
            Cache::forget("featured_datasets_{$i}");
        }
    }
}