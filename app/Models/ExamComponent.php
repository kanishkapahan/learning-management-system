<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamComponent extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'mcq_marks', 'theory_marks', 'practical_marks'];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
