<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        return response()->json([
            'students' => Student::query()->where('student_no', 'like', "%{$q}%")->orWhere('first_name', 'like', "%{$q}%")->limit(5)->get(['id', 'student_no', 'first_name', 'last_name']),
            'courses' => Course::query()->where('course_code', 'like', "%{$q}%")->orWhere('title', 'like', "%{$q}%")->limit(5)->get(['id', 'course_code', 'title']),
            'batches' => Batch::query()->where('batch_code', 'like', "%{$q}%")->limit(5)->get(['id', 'batch_code']),
        ]);
    }
}
