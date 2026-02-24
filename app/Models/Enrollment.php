<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Enrollment extends Pivot
{
    protected $table = 'enrollments';

    protected $fillable = ['student_id', 'batch_id', 'course_id', 'enrolled_at', 'enrollment_status'];

    protected function casts(): array
    {
        return ['enrolled_at' => 'datetime'];
    }
}
