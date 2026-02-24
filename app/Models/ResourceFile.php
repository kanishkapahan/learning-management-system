<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceFile extends Model
{
    use HasFactory;

    protected $table = 'resources';

    protected $fillable = [
        'course_id', 'batch_id', 'title', 'file_path', 'mime_type', 'uploaded_by',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(ResourceDownloadLog::class, 'resource_id');
    }
}
