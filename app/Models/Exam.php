<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_title',
        'course_id',
        'batch_id',
        'exam_date',
        'start_time',
        'duration_minutes',
        'total_marks',
        'pass_marks',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return ['exam_date' => 'date'];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(ExamComponent::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
