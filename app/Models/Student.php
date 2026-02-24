<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_no',
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'address',
        'gender',
        'status',
        'profile_picture_path',
    ];

    protected function casts(): array
    {
        return ['dob' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'enrollments')
            ->withPivot(['course_id', 'enrolled_at', 'enrollment_status'])
            ->withTimestamps();
    }
}
