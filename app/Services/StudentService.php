<?php

namespace App\Services;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly ActivityLogService $activityLogService
    ) {
    }

    public function paginate(array $filters = [], int $perPage = 15)
    {
        return $this->students->paginate($filters, $perPage);
    }

    public function create(array $data, ?UploadedFile $profilePicture = null, $actor = null): Student
    {
        return DB::transaction(function () use ($data, $profilePicture, $actor): Student {
            $data['student_no'] = $this->generateStudentNo();
            if ($profilePicture) {
                $data['profile_picture_path'] = $profilePicture->store('students', 'public');
            }

            $student = $this->students->create($data);
            $this->activityLogService->log('students.create', 'Student created', $student, $actor);
            return $student;
        });
    }

    public function update(Student $student, array $data, ?UploadedFile $profilePicture = null, $actor = null): Student
    {
        return DB::transaction(function () use ($student, $data, $profilePicture, $actor): Student {
            if ($profilePicture) {
                if ($student->profile_picture_path) {
                    Storage::disk('public')->delete($student->profile_picture_path);
                }
                $data['profile_picture_path'] = $profilePicture->store('students', 'public');
            }

            $student = $this->students->update($student, $data);
            $this->activityLogService->log('students.update', 'Student updated', $student, $actor);
            return $student;
        });
    }

    public function importCsv(string $path, $actor = null): array
    {
        $handle = fopen($path, 'r');
        $headers = null;
        $success = 0;
        $errors = [];
        $lineNo = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;
            if ($headers === null) {
                $headers = array_map('trim', $row);
                continue;
            }

            $data = array_combine($headers, $row);
            try {
                if (! is_array($data) || empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                    throw new \RuntimeException('Required CSV columns missing.');
                }

                $this->create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'dob' => $data['dob'] ?? null,
                    'address' => $data['address'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'status' => $data['status'] ?? 'active',
                ], null, $actor);

                $success++;
            } catch (\Throwable $e) {
                $errors[] = ['line' => $lineNo, 'error' => $e->getMessage()];
            }
        }

        fclose($handle);

        return compact('success', 'errors');
    }

    private function generateStudentNo(): string
    {
        $count = Student::withTrashed()->count() + 1;
        return 'STU-' . now()->format('Y') . '-' . str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
