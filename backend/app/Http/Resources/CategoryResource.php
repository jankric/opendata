<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'metadata' => $this->metadata ?? [],
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relationships
            'parent' => new CategoryResource($this->whenLoaded('parent')),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            
            // Counts
            'datasets_count' => $this->when(isset($this->datasets_count), $this->datasets_count),
        ];
    }
}