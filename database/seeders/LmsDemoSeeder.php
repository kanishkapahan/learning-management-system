<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamComponent;
use App\Models\Lecturer;
use App\Models\ResourceFile;
use App\Models\Result;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LmsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $superAdmin = User::query()->firstOrCreate(
            ['email' => 'superadmin@lms.test'],
            ['name' => 'Super Admin', 'password' => Hash::make('Admin@12345'), 'email_verified_at' => now(), 'status' => 'active']
        );
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@lms.test'],
            ['name' => 'Academic Admin', 'password' => Hash::make('Admin@12345'), 'email_verified_at' => now(), 'status' => 'active']
        );

        $superAdmin->assignRole('SUPER_ADMIN');
        $admin->assignRole('ADMIN');

        $lecturerUsers = collect();
        for ($i = 1; $i <= 3; $i++) {
            $user = User::query()->firstOrCreate(
                ['email' => "lecturer{$i}@lms.test"],
                ['name' => "Lecturer {$i}", 'password' => Hash::make('Lecturer@12345'), 'email_verified_at' => now(), 'status' => 'active']
            );
            $user->assignRole('LECTURER');
            $lecturerUsers->push($user);
        }

        $lecturers = $lecturerUsers->map(function (User $user, int $idx) use ($faker) {
            return Lecturer::query()->firstOrCreate(
                ['email' => $user->email],
                [
                    'user_id' => $user->id,
                    'employee_no' => 'EMP-' . now()->year . '-' . str_pad((string) ($idx + 1), 4, '0', STR_PAD_LEFT),
                    'name' => $user->name,
                    'phone' => $faker->phoneNumber(),
                    'department' => $faker->randomElement(['Computing', 'Business', 'Engineering']),
                    'specialization' => $faker->word(),
                    'status' => 'active',
                ]
            );
        });

        $studentUsers = collect();
        for ($i = 1; $i <= 30; $i++) {
            $user = User::query()->firstOrCreate(
                ['email' => "student{$i}@lms.test"],
                ['name' => "Student {$i}", 'password' => Hash::make('Student@12345'), 'email_verified_at' => now(), 'status' => 'active']
            );
            $user->assignRole('STUDENT');
            $studentUsers->push($user);
        }

        $students = $studentUsers->map(function (User $user, int $idx) use ($faker) {
            return Student::query()->firstOrCreate(
                ['email' => $user->email],
                [
                    'user_id' => $user->id,
                    'student_no' => 'STU-' . now()->year . '-' . str_pad((string) ($idx + 1), 5, '0', STR_PAD_LEFT),
                    'first_name' => 'Student' . ($idx + 1),
                    'last_name' => $faker->lastName(),
                    'phone' => $faker->phoneNumber(),
                    'dob' => $faker->date(),
                    'address' => $faker->address(),
                    'gender' => $faker->randomElement(['male', 'female']),
                    'status' => 'active',
                ]
            );
        });

        $courses = collect();
        for ($i = 1; $i <= 6; $i++) {
            $courses->push(Course::query()->firstOrCreate(
                ['course_code' => 'CS' . str_pad((string) (100 + $i), 3, '0', STR_PAD_LEFT)],
                [
                    'title' => $faker->randomElement(['Web Engineering', 'Database Systems', 'Software Architecture', 'Cloud Computing', 'Data Analytics', 'UI Engineering']) . " {$i}",
                    'description' => $faker->sentence(10),
                    'credits' => $faker->numberBetween(2, 4),
                    'level' => $faker->numberBetween(1, 4),
                    'semester' => $faker->numberBetween(1, 2),
                    'lecturer_id' => $lecturers[$i % $lecturers->count()]->id,
                    'status' => 'active',
                ]
            ));
        }

        $batches = collect();
        foreach ($courses as $idx => $course) {
            $batches->push(Batch::query()->firstOrCreate(
                ['batch_code' => 'B' . now()->year . '-' . ($idx + 1) . '-JAN'],
                [
                    'course_id' => $course->id,
                    'year' => (int) now()->year,
                    'intake' => 'Jan',
                    'start_date' => now()->startOfYear()->addDays($idx * 5),
                    'end_date' => now()->startOfYear()->addMonths(6)->addDays($idx * 5),
                    'status' => 'active',
                ]
            ));
        }

        foreach ($students as $idx => $student) {
            $batch = $batches[$idx % $batches->count()];
            DB::table('enrollments')->updateOrInsert(
                ['student_id' => $student->id, 'batch_id' => $batch->id, 'course_id' => $batch->course_id],
                ['enrolled_at' => now()->subDays(rand(1, 90)), 'enrollment_status' => 'active', 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $exams = collect();
        foreach ($batches->take(5) as $i => $batch) {
            $exam = Exam::query()->firstOrCreate(
                ['exam_title' => 'Midterm ' . ($i + 1), 'batch_id' => $batch->id],
                [
                    'course_id' => $batch->course_id,
                    'exam_date' => now()->subDays(10 - $i),
                    'start_time' => '09:00:00',
                    'duration_minutes' => 120,
                    'total_marks' => 100,
                    'pass_marks' => 50,
                    'status' => 'held',
                    'created_by' => $lecturerUsers[$i % $lecturerUsers->count()]->id,
                ]
            );
            ExamComponent::query()->updateOrCreate(['exam_id' => $exam->id], ['mcq_marks' => 30, 'theory_marks' => 50, 'practical_marks' => 20]);
            $exams->push($exam);
        }

        $thresholds = json_decode(Setting::query()->firstOrCreate(['key' => 'grade_thresholds'], ['value' => json_encode([
            ['grade' => 'A', 'min' => 80], ['grade' => 'B', 'min' => 70], ['grade' => 'C', 'min' => 60], ['grade' => 'D', 'min' => 50], ['grade' => 'F', 'min' => 0]
        ]), 'type' => 'json'])->value, true);

        $resultCount = 0;
        foreach ($exams as $exam) {
            $enrolledStudents = DB::table('enrollments')->where('batch_id', $exam->batch_id)->pluck('student_id')->take(30);
            foreach ($enrolledStudents as $studentId) {
                if ($resultCount >= 150) {
                    break 2;
                }
                $marks = rand(35, 95);
                $grade = 'F';
                foreach ($thresholds as $t) {
                    if ($marks >= $t['min']) { $grade = $t['grade']; break; }
                }
                Result::query()->updateOrCreate(
                    ['student_id' => $studentId, 'exam_id' => $exam->id],
                    [
                        'marks' => $marks,
                        'grade' => $grade,
                        'pass_fail' => $marks >= $exam->pass_marks,
                        'remarks' => $faker->sentence(4),
                        'entered_by' => $lecturerUsers->first()->id,
                        'approved_by' => $admin->id,
                        'published_at' => now()->subDays(rand(0, 5)),
                        'status' => 'published',
                    ]
                );
                $resultCount++;
            }
        }

        foreach ($students->take(30) as $student) {
            $enrollment = DB::table('enrollments')->where('student_id', $student->id)->first();
            if (! $enrollment) {
                continue;
            }
            for ($d = 1; $d <= 10; $d++) {
                Attendance::query()->updateOrCreate(
                    ['student_id' => $student->id, 'batch_id' => $enrollment->batch_id, 'date' => now()->subDays($d)->toDateString()],
                    ['status' => $faker->randomElement(['present', 'present', 'late', 'absent']), 'marked_by' => $lecturerUsers->first()->id]
                );
            }
        }

        for ($i = 1; $i <= 30; $i++) {
            Announcement::query()->firstOrCreate(
                ['title' => "Announcement {$i}"],
                [
                    'body' => $faker->paragraph(),
                    'target_role' => $faker->randomElement(['all', 'students', 'lecturers']),
                    'batch_id' => $faker->boolean(40) ? $batches->random()->id : null,
                    'publish_at' => now()->subDays(rand(0, 15)),
                    'expires_at' => now()->addDays(rand(7, 30)),
                    'created_by' => $admin->id,
                ]
            );
        }

        foreach ($courses->take(4) as $i => $course) {
            ResourceFile::query()->firstOrCreate(
                ['title' => "Sample Resource {$i}"],
                [
                    'course_id' => $course->id,
                    'batch_id' => $batches[$i]->id ?? null,
                    'file_path' => 'resources/sample-' . $i . '.pdf',
                    'mime_type' => 'application/pdf',
                    'uploaded_by' => $lecturerUsers->first()->id,
                ]
            );
        }

        Setting::updateOrCreate(['key' => 'academic_year'], ['value' => now()->year . '/' . (now()->year + 1), 'type' => 'string']);
        Setting::updateOrCreate(['key' => 'enable_self_registration'], ['value' => '1', 'type' => 'boolean']);
        Setting::updateOrCreate(['key' => 'system_logo'], ['value' => null, 'type' => 'file']);
    }
}
