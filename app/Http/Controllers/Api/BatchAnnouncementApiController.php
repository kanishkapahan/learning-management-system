<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class BatchAnnouncementApiController extends Controller
{
    public function index(int $id)
    {
        $rows = Announcement::query()
            ->where(function ($q) use ($id): void {
                $q->whereNull('batch_id')->orWhere('batch_id', $id);
            })
            ->where(function ($q): void {
                $q->whereNull('publish_at')->orWhere('publish_at', '<=', now());
            })
            ->where(function ($q): void {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->latest()
            ->get();

        return response()->json($rows);
    }
}
