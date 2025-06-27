<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatasetDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'dataset_id',
        'resource_id',
        'user_id',
        'ip_address',
        'user_agent',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}