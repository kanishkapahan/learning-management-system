<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceDownloadLog extends Model
{
    use HasFactory;

    protected $fillable = ['resource_id', 'user_id', 'downloaded_at'];

    protected function casts(): array
    {
        return ['downloaded_at' => 'datetime'];
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(ResourceFile::class, 'resource_id');
    }
}
