<?php

namespace App\Repositories\Contracts;

use App\Models\Result;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ResultRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function upsertByStudentAndExam(array $payload): Result;
}
