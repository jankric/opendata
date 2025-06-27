<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dataset_id' => $this->dataset_id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'format' => $this->format,
            'url' => $this->url,
            'file_size' => $this->file_size,
            'file_size_human' => $this->file_size_human,
            'mime_type' => $this->mime_type,
            'encoding' => $this->encoding,
            'schema' => $this->schema ?? [],
            'metadata' => $this->metadata ?? [],
            'is_preview_available' => $this->is_preview_available,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relationships
            'dataset' => new DatasetResource($this->whenLoaded('dataset')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'updater' => new UserResource($this->whenLoaded('updater')),
            
            // Counts
            'downloads_count' => $this->when(isset($this->downloads_count), $this->downloads_count),
            
            // URLs
            'download_url' => route('api.v1.resources.download', $this->id),
            'preview_url' => $this->when(
                $this->is_preview_available,
                route('api.v1.resources.preview', $this->id)
            ),
        ];
    }
}