<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'user_id']);
        });

        Schema::create('permission_role', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['permission_id', 'role_id']);
        });

        Schema::create('permission_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['permission_id', 'user_id']);
        });

        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_no')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('status')->default('active');
            $table->string('profile_picture_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'last_name']);
        });

        Schema::create('lecturers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_no')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->string('specialization')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'department']);
        });

        Schema::create('courses', function (Blueprint $table): void {
            $table->id();
            $table->string('course_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('credits')->default(3);
            $table->unsignedTinyInteger('level')->default(1);
            $table->unsignedTinyInteger('semester')->default(1);
            $table->foreignId('lecturer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['lecturer_id', 'status']);
        });

        Schema::create('batches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('batch_code')->unique();
            $table->unsignedSmallInteger('year');
            $table->enum('intake', ['Jan', 'May', 'Sep']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->index(['course_id', 'year', 'intake']);
        });

        Schema::create('enrollments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamp('enrolled_at');
            $table->enum('enrollment_status', ['active', 'completed', 'dropped'])->default('active');
            $table->timestamps();
            $table->unique(['student_id', 'batch_id', 'course_id']);
            $table->index(['batch_id', 'enrollment_status']);
        });

        Schema::create('exams', function (Blueprint $table): void {
            $table->id();
            $table->string('exam_title');
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->date('exam_date');
            $table->time('start_time');
            $table->unsignedSmallInteger('duration_minutes');
            $table->decimal('total_marks', 8, 2);
            $table->decimal('pass_marks', 8, 2);
            $table->enum('status', ['draft', 'scheduled', 'held'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['course_id', 'batch_id', 'exam_date']);
        });

        Schema::create('exam_components', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->decimal('mcq_marks', 8, 2)->default(0);
            $table->decimal('theory_marks', 8, 2)->default(0);
            $table->decimal('practical_marks', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('results', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->decimal('marks', 8, 2);
            $table->string('grade', 5)->nullable();
            $table->boolean('pass_fail')->default(false);
            $table->text('remarks')->nullable();
            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'approved', 'published'])->default('draft');
            $table->timestamps();
            $table->unique(['student_id', 'exam_id']);
            $table->index(['exam_id', 'status']);
        });

        Schema::create('attendances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late'])->default('present');
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['student_id', 'batch_id', 'date']);
            $table->index(['batch_id', 'date']);
        });

        Schema::create('announcements', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->enum('target_role', ['all', 'students', 'lecturers']);
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['target_role', 'publish_at']);
        });

        Schema::create('resources', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['course_id', 'batch_id']);
        });

        Schema::create('resource_download_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('downloaded_at');
            $table->timestamps();
            $table->index(['user_id', 'downloaded_at']);
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('event');
            $table->string('description');
            $table->nullableMorphs('subject');
            $table->nullableMorphs('causer');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['event', 'created_at']);
        });

        DB::statement("CREATE OR REPLACE VIEW vw_exam_pass_rates AS
            SELECT r.exam_id,
                   e.batch_id,
                   COUNT(r.id) AS total_results,
                   SUM(CASE WHEN r.pass_fail = 1 THEN 1 ELSE 0 END) AS passed_count,
                   ROUND((SUM(CASE WHEN r.pass_fail = 1 THEN 1 ELSE 0 END) / NULLIF(COUNT(r.id),0)) * 100, 2) AS pass_rate
            FROM results r
            INNER JOIN exams e ON e.id = r.exam_id
            WHERE r.status IN ('approved', 'published')
            GROUP BY r.exam_id, e.batch_id");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_exam_pass_rates');

        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('resource_download_logs');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('results');
        Schema::dropIfExists('exam_components');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('batches');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('lecturers');
        Schema::dropIfExists('students');
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
