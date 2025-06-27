<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'logo_url' => $this->logo_url,
            'is_active' => $this->is_active,
            'metadata' => $this->metadata ?? [],
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Counts
            'datasets_count' => $this->when(isset($this->datasets_count), $this->datasets_count),
            'users_count' => $this->when(isset($this->users_count), $this->users_count),
        ];
    }
}