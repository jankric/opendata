<?php

namespace App\Services;

use App\Models\Dataset;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchService
{
    public function searchDatasets(string $query, array $filters = [], int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey($query, $filters, $perPage);
        
        return Cache::remember($cacheKey, 900, function () use ($query, $filters, $perPage) {
            $builder = Dataset::published()
                ->with(['category', 'organization', 'creator'])
                ->withCount(['downloads', 'views', 'resources']);

            // Apply full-text search
            if (!empty($query)) {
                $builder->where(function ($q) use ($query) {
                    $q->where('title', 'ILIKE', "%{$query}%")
                      ->orWhere('description', 'ILIKE', "%{$query}%")
                      ->orWhere('notes', 'ILIKE', "%{$query}%")
                      ->orWhereJsonContains('tags', $query);
                });
            }

            // Apply filters
            $this->applyFilters($builder, $filters);

            // Apply sorting
            $this->applySorting($builder, $filters);

            return $builder->paginate($perPage);
        });
    }

    public function getSearchSuggestions(string $query, int $limit = 10): array
    {
        $cacheKey = "search_suggestions_" . md5($query) . "_{$limit}";
        
        return Cache::remember($cacheKey, 3600, function () use ($query, $limit) {
            $suggestions = [];

            // Dataset title suggestions
            $datasetSuggestions = Dataset::published()
                ->where('title', 'ILIKE', "%{$query}%")
                ->limit($limit)
                ->pluck('title')
                ->toArray();

            // Tag suggestions
            $tagSuggestions = Tag::where('name', 'ILIKE', "%{$query}%")
                ->orderBy('usage_count', 'desc')
                ->limit($limit)
                ->pluck('name')
                ->toArray();

            $suggestions = array_merge($datasetSuggestions, $tagSuggestions);
            
            return array_slice(array_unique($suggestions), 0, $limit);
        });
    }

    public function getPopularSearchTerms(int $limit = 10): array
    {
        return Cache::remember("popular_search_terms_{$limit}", 3600, function () use ($limit) {
            // This would typically come from search logs
            // For now, return popular tags
            return Tag::orderBy('usage_count', 'desc')
                ->limit($limit)
                ->pluck('name')
                ->toArray();
        });
    }

    public function recordSearch(string $query, int $resultCount, ?int $userId = null): void
    {
        // Record search for analytics
        DB::table('search_logs')->insert([
            'query' => $query,
            'result_count' => $resultCount,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'searched_at' => now(),
        ]);

        // Update search term popularity
        $this->updateSearchTermPopularity($query);
    }

    private function applyFilters($builder, array $filters): void
    {
        if (!empty($filters['category'])) {
            if (is_array($filters['category'])) {
                $builder->whereIn('category_id', $filters['category']);
            } else {
                $builder->where('category_id', $filters['category']);
            }
        }

        if (!empty($filters['organization'])) {
            if (is_array($filters['organization'])) {
                $builder->whereIn('organization_id', $filters['organization']);
            } else {
                $builder->where('organization_id', $filters['organization']);
            }
        }

        if (!empty($filters['tags'])) {
            $tags = is_array($filters['tags']) ? $filters['tags'] : [$filters['tags']];
            foreach ($tags as $tag) {
                $builder->whereJsonContains('tags', $tag);
            }
        }

        if (!empty($filters['format'])) {
            $builder->whereHas('resources', function ($q) use ($filters) {
                $q->where('format', 'ILIKE', "%{$filters['format']}%");
            });
        }

        if (!empty($filters['date_from'])) {
            $builder->where('published_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('published_at', '<=', $filters['date_to']);
        }
    }

    private function applySorting($builder, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'relevance';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        switch ($sortBy) {
            case 'title':
                $builder->orderBy('title', $sortOrder);
                break;
            case 'date':
                $builder->orderBy('published_at', $sortOrder);
                break;
            case 'downloads':
                $builder->orderBy('downloads_count', $sortOrder);
                break;
            case 'views':
                $builder->orderBy('views_count', $sortOrder);
                break;
            case 'relevance':
            default:
                // For relevance, we could implement a scoring system
                $builder->orderBy('published_at', 'desc');
                break;
        }
    }

    private function generateCacheKey(string $query, array $filters, int $perPage): string
    {
        $filterString = http_build_query($filters);
        return 'search_' . md5($query . $filterString . $perPage);
    }

    private function updateSearchTermPopularity(string $query): void
    {
        // Extract meaningful terms from query
        $terms = $this->extractSearchTerms($query);
        
        foreach ($terms as $term) {
            // Update or create tag
            $tag = Tag::firstOrCreate(['name' => strtolower($term)]);
            $tag->increment('usage_count');
        }
    }

    private function extractSearchTerms(string $query): array
    {
        // Simple term extraction - could be enhanced with NLP
        $terms = explode(' ', strtolower($query));
        
        // Filter out common words
        $stopWords = ['dan', 'atau', 'di', 'ke', 'dari', 'untuk', 'dengan', 'pada', 'dalam', 'yang', 'adalah', 'ini', 'itu'];
        
        return array_filter($terms, function ($term) use ($stopWords) {
            return strlen($term) > 2 && !in_array($term, $stopWords);
        });
    }
}