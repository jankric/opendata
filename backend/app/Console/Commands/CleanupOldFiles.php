<?php

namespace App\Console\Commands;

use App\Models\Resource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOldFiles extends Command
{
    protected $signature = 'cleanup:files {--dry-run : Show what would be deleted without actually deleting}';
    protected $description = 'Clean up orphaned files and old temporary uploads';

    public function handle(): int
    {
        $this->info('Starting file cleanup...');
        
        $dryRun = $this->option('dry-run');
        $disk = config('opendata.uploads.disk', 'local');
        
        // Find orphaned files
        $orphanedFiles = $this->findOrphanedFiles($disk);
        
        if ($orphanedFiles->isEmpty()) {
            $this->info('No orphaned files found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$orphanedFiles->count()} orphaned files:");
        
        foreach ($orphanedFiles as $file) {
            $this->line("  - {$file}");
            
            if (!$dryRun) {
                Storage::disk($disk)->delete($file);
                $this->info("    Deleted: {$file}");
            }
        }

        if ($dryRun) {
            $this->warn('Dry run mode - no files were actually deleted.');
            $this->info('Run without --dry-run to actually delete the files.');
        } else {
            $this->info("Cleanup completed. Deleted {$orphanedFiles->count()} files.");
        }

        return Command::SUCCESS;
    }

    private function findOrphanedFiles(string $disk): \Illuminate\Support\Collection
    {
        $allFiles = collect(Storage::disk($disk)->allFiles(config('opendata.uploads.path', 'datasets')));
        $usedFiles = Resource::whereNotNull('file_path')->pluck('file_path');
        
        return $allFiles->diff($usedFiles);
    }
}