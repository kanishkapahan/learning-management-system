<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseApiController extends Controller
{
    public function index()
    {
        return response()->json(Course::query()->with('lecturer')->where('status', 'active')->get());
    }
}
