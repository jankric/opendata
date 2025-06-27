<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->position,
            'avatar_url' => $this->avatar_url,
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at?->toISOString(),
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relationships
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            
            // Roles and permissions (only for authenticated user or admins)
            'roles' => $this->when(
                $request->user()?->id === $this->id || $request->user()?->hasRole('super-admin'),
                $this->roles->pluck('name')
            ),
            'permissions' => $this->when(
                $request->user()?->id === $this->id || $request->user()?->hasRole('super-admin'),
                $this->getAllPermissions()->pluck('name')
            ),
        ];
    }
}