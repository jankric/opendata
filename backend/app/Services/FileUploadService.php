<?php

namespace App\Services;

use App\Models\Resource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private array $allowedMimeTypes = [
        'text/csv',
        'application/json',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/pdf',
        'application/xml',
        'text/xml',
        'application/geo+json',
        'text/plain',
    ];

    private int $maxFileSize = 52428800; // 50MB

    public function uploadFile(UploadedFile $file, Resource $resource): array
    {
        $this->validateFile($file);

        $disk = config('opendata.uploads.disk', 'local');
        $path = config('opendata.uploads.path', 'datasets');
        
        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);
        $filePath = $path . '/' . $resource->dataset_id . '/' . $filename;
        
        // Store file
        $storedPath = Storage::disk($disk)->putFileAs(
            $path . '/' . $resource->dataset_id,
            $file,
            $filename
        );

        if (!$storedPath) {
            throw new \Exception('Failed to store file');
        }

        // Update resource with file information
        $resource->update([
            'file_path' => $storedPath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'format' => strtolower($file->getClientOriginalExtension()),
        ]);

        return [
            'file_path' => $storedPath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'format' => strtolower($file->getClientOriginalExtension()),
            'original_name' => $file->getClientOriginalName(),
        ];
    }

    public function deleteFile(Resource $resource): bool
    {
        if (!$resource->file_path) {
            return true;
        }

        $disk = config('opendata.uploads.disk', 'local');
        
        if (Storage::disk($disk)->exists($resource->file_path)) {
            return Storage::disk($disk)->delete($resource->file_path);
        }

        return true;
    }

    public function getFileUrl(Resource $resource): ?string
    {
        if (!$resource->file_path) {
            return null;
        }

        $disk = config('opendata.uploads.disk', 'local');
        
        if ($disk === 'public') {
            return Storage::disk($disk)->url($resource->file_path);
        }

        // For private storage, return download route
        return route('api.v1.resources.download', $resource->id);
    }

    public function validateFile(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            throw new \Exception('File size exceeds maximum allowed size of ' . ($this->maxFileSize / 1024 / 1024) . 'MB');
        }

        // Check MIME type
        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \Exception('File type not allowed. Allowed types: ' . implode(', ', $this->getAllowedExtensions()));
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = $this->getAllowedExtensions();
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception('File extension not allowed. Allowed extensions: ' . implode(', ', $allowedExtensions));
        }
    }

    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $basename = Str::slug($basename);
        
        return $basename . '_' . time() . '_' . Str::random(8) . '.' . $extension;
    }

    private function getAllowedExtensions(): array
    {
        return explode(',', config('opendata.uploads.allowed_types', 'csv,json,xlsx,xls,pdf,xml,geojson'));
    }

    public function getFilePreview(Resource $resource, int $limit = 100): ?array
    {
        if (!$resource->file_path || !in_array($resource->format, ['csv', 'json'])) {
            return null;
        }

        $disk = config('opendata.uploads.disk', 'local');
        
        if (!Storage::disk($disk)->exists($resource->file_path)) {
            return null;
        }

        try {
            $content = Storage::disk($disk)->get($resource->file_path);
            
            if ($resource->format === 'csv') {
                return $this->parseCsvPreview($content, $limit);
            } elseif ($resource->format === 'json') {
                return $this->parseJsonPreview($content, $limit);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate file preview', [
                'resource_id' => $resource->id,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    private function parseCsvPreview(string $content, int $limit): array
    {
        $lines = explode("\n", $content);
        $headers = str_getcsv(array_shift($lines));
        
        $rows = [];
        $count = 0;
        
        foreach ($lines as $line) {
            if ($count >= $limit || empty(trim($line))) {
                break;
            }
            
            $rows[] = str_getcsv($line);
            $count++;
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
            'total_rows' => count($lines),
            'preview_rows' => count($rows),
        ];
    }

    private function parseJsonPreview(string $content, int $limit): array
    {
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
                'preview_rows' => count($rows),
            ];
        }

        // Single object or other structure
        return [
            'data' => $data,
            'type' => gettype($data),
        ];
    }
}