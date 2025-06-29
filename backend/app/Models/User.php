<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'organization_id',
        'position',
        'avatar_url',
        'is_active',
        'last_login_at',
        'metadata',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access to admin panel for users with admin roles
        return $this->hasAnyRole(['super-admin', 'organization-admin', 'reviewer', 'publisher']);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function datasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'created_by');
    }

    public function updatedDatasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'updated_by');
    }

    public function approvedDatasets(): HasMany
    {
        return $this->hasMany(Dataset::class, 'approved_by');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(DatasetDownload::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(DatasetView::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // Methods
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    public function canManageDataset(Dataset $dataset): bool
    {
        // Super admin can manage all datasets
        if ($this->hasRole('super-admin')) {
            return true;
        }

        // Organization admin can manage datasets in their organization
        if ($this->hasRole('organization-admin') && $this->organization_id === $dataset->organization_id) {
            return true;
        }

        // Dataset creator can manage their own datasets
        if ($dataset->created_by === $this->id) {
            return true;
        }

        return false;
    }

    public function canApproveDataset(Dataset $dataset): bool
    {
        // Super admin can approve all datasets
        if ($this->hasRole('super-admin')) {
            return true;
        }

        // Organization admin can approve datasets in their organization
        if ($this->hasRole('organization-admin') && $this->organization_id === $dataset->organization_id) {
            return true;
        }

        // Reviewer can approve datasets
        if ($this->hasRole('reviewer')) {
            return true;
        }

        return false;
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}