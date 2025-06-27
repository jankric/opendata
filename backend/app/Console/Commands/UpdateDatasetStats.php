<?php

namespace App\Console\Commands;

use App\Models\Dataset;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateDatasetStats extends Command
{
    protected $signature = 'datasets:update-stats';
    protected $description = 'Update dataset statistics and clear relevant caches';

    public function handle(): int
    {
        $this->info('Updating dataset statistics...');

        // Update tag usage counts
        $this->updateTagUsageCounts();

        // Clear relevant caches
        $this->clearCaches();

        $this->info('Dataset statistics updated successfully.');

        return Command::SUCCESS;
    }

    private function updateTagUsageCounts(): void
    {
        $this->info('Updating tag usage counts...');

        // Reset all tag usage counts
        Tag::query()->update(['usage_count' => 0]);

        // Count tag usage from published datasets
        $datasets = Dataset::published()->whereNotNull('tags')->get(['tags']);
        
        $tagCounts = [];
        foreach ($datasets as $dataset) {
            if (is_array($dataset->tags)) {
                foreach ($dataset->tags as $tagName) {
                    $tagCounts[strtolower($tagName)] = ($tagCounts[strtolower($tagName)] ?? 0) + 1;
                }
            }
        }

        // Update tag usage counts
        foreach ($tagCounts as $tagName => $count) {
            Tag::where('name', $tagName)->update(['usage_count' => $count]);
        }

        $this->info("Updated usage counts for " . count($tagCounts) . " tags.");
    }

    private function clearCaches(): void
    {
        $this->info('Clearing caches...');

        $cacheKeys = [
            'portal_stats',
            'dashboard_stats',
            'download_stats',
            'view_stats',
            'trend_stats',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear dataset-specific caches
        for ($i = 5; $i <= 20; $i += 5) {
            Cache::forget("popular_datasets_{$i}");
            Cache::forget("recent_datasets_{$i}");
            Cache::forget("featured_datasets_{$i}");
        }

        $this->info('Caches cleared.');
    }
}