<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Resource extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    const TYPE_FILE = 'file';
    const TYPE_API = 'api';
    const TYPE_LINK = 'link';

    protected $fillable = [
        'dataset_id',
        'name',
        'description',
        'type',
        'format',
        'url',
        'file_path',
        'file_size',
        'mime_type',
        'encoding',
        'schema',
        'metadata',
        'is_preview_available',
        'preview_data',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_preview_available' => 'boolean',
        'schema' => 'array',
        'metadata' => 'array',
        'preview_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(DatasetDownload::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files')
            ->acceptsMimeTypes([
                'text/csv',
                'application/json',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/pdf',
                'application/xml',
                'text/xml',
                'application/geo+json',
            ]);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Add any media conversions if needed
    }

    // Accessors
    public function getDownloadsCountAttribute(): int
    {
        return $this->downloads()->count();
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->file_size;
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsFileTypeAttribute(): bool
    {
        return $this->type === self::TYPE_FILE;
    }

    public function getIsApiTypeAttribute(): bool
    {
        return $this->type === self::TYPE_API;
    }

    public function getIsLinkTypeAttribute(): bool
    {
        return $this->type === self::TYPE_LINK;
    }

    // Methods
    public function generatePreview(): bool
    {
        if (!$this->is_file_type || !in_array($this->format, ['csv', 'json'])) {
            return false;
        }

        try {
            $previewData = $this->extractPreviewData();
            
            $this->update([
                'is_preview_available' => true,
                'preview_data' => $previewData,
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to generate preview for resource ' . $this->id, [
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    private function extractPreviewData(): array
    {
        $media = $this->getFirstMedia('files');
        if (!$media) {
            throw new \Exception('No file found for resource');
        }

        $filePath = $media->getPath();
        $previewLimit = config('opendata.datasets.preview_limit', 100);

        if ($this->format === 'csv') {
            return $this->extractCsvPreview($filePath, $previewLimit);
        }

        if ($this->format === 'json') {
            return $this->extractJsonPreview($filePath, $previewLimit);
        }

        throw new \Exception('Unsupported format for preview: ' . $this->format);
    }

    private function extractCsvPreview(string $filePath, int $limit): array
    {
        $csv = \League\Csv\Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        
        $headers = $csv->getHeader();
        $records = iterator_to_array($csv->getRecords());
        
        return [
            'headers' => $headers,
            'rows' => array_slice($records, 0, $limit),
            'total_rows' => count($records),
            'preview_rows' => min(count($records), $limit),
        ];
    }

    private function extractJsonPreview(string $filePath, int $limit): array
    {
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format');
        }

        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            // Array of objects
            $headers = array_keys($data[0]);
            $rows = array_slice($data, 0, $limit);
            
            return [
                'headers' => $headers,
                'rows' => $rows,
                'total_rows' => count($data),
                'preview_rows' => min(count($data), $limit),
            ];
        }

        // Single object or other structure
        return [
            'data' => $data,
            'type' => gettype($data),
        ];
    }
}