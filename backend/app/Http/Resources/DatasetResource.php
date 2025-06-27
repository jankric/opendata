<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatasetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'notes' => $this->notes,
            'license' => $this->license,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'featured' => $this->featured,
            'tags' => $this->tags ?? [],
            'metadata' => $this->metadata ?? [],
            'schema' => $this->schema ?? [],
            'published_at' => $this->published_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relationships
            'category' => new CategoryResource($this->whenLoaded('category')),
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'updater' => new UserResource($this->whenLoaded('updater')),
            'approver' => new UserResource($this->whenLoaded('approver')),
            'resources' => ResourceResource::collection($this->whenLoaded('resources')),
            'groups' => GroupResource::collection($this->whenLoaded('groups')),
            
            // Counts
            'downloads_count' => $this->when(isset($this->downloads_count), $this->downloads_count),
            'views_count' => $this->when(isset($this->views_count), $this->views_count),
            'resources_count' => $this->when(isset($this->resources_count), $this->resources_count),
            
            // Computed attributes
            'is_published' => $this->is_published,
            'approved_at' => $this->approved_at?->toISOString(),
        ];
    }
}