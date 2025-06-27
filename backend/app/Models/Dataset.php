<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Dataset extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia, LogsActivity;

    const STATUS_DRAFT = 'draft';
    const STATUS_REVIEW = 'review';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'notes',
        'category_id',
        'organization_id',
        'license',
        'status',
        'visibility',
        'featured',
        'tags',
        'metadata',
        'schema',
        'published_at',
        'created_by',
        'updated_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'tags' => 'array',
        'metadata' => 'array',
        'schema' => 'array',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'visibility'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(DatasetDownload::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(DatasetView::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'dataset_groups');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                    ->where('visibility', 'public')
                    ->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function scopeByOrganization($query, $organizationSlug)
    {
        return $query->whereHas('organization', function ($q) use ($organizationSlug) {
            $q->where('slug', $organizationSlug);
        });
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'ILIKE', "%{$term}%")
              ->orWhere('description', 'ILIKE', "%{$term}%")
              ->orWhere('notes', 'ILIKE', "%{$term}%")
              ->orWhereJsonContains('tags', $term);
        });
    }

    // Accessors
    public function getDownloadsCountAttribute(): int
    {
        return $this->downloads()->count();
    }

    public function getViewsCountAttribute(): int
    {
        return $this->views()->count();
    }

    public function getResourcesCountAttribute(): int
    {
        return $this->resources()->count();
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED && 
               $this->visibility === 'public' && 
               $this->published_at !== null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Methods
    public function publish(): bool
    {
        return $this->update([
            'status' => self::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function unpublish(): bool
    {
        return $this->update([
            'status' => self::STATUS_DRAFT,
            'published_at' => null,
        ]);
    }

    public function approve(User $approver): bool
    {
        return $this->update([
            'status' => self::STATUS_PUBLISHED,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'published_at' => now(),
        ]);
    }

    public function recordView(?User $user = null, ?string $ipAddress = null): void
    {
        $this->views()->create([
            'user_id' => $user?->id,
            'ip_address' => $ipAddress,
            'viewed_at' => now(),
        ]);
    }

    public function recordDownload(?User $user = null, ?string $ipAddress = null, ?Resource $resource = null): void
    {
        $this->downloads()->create([
            'user_id' => $user?->id,
            'resource_id' => $resource?->id,
            'ip_address' => $ipAddress,
            'downloaded_at' => now(),
        ]);
    }
}