<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function index(Request $request)
    {
        $data = [
            'summary' => $this->dashboardService->summary(),
            'charts' => $this->dashboardService->charts(),
            'activities' => $this->dashboardService->recentActivities(),
            'announcements' => Announcement::query()->latest()->limit(5)->get(),
            'topPerformers' => $this->dashboardService->topPerformers(),
            'systemStats' => $this->dashboardService->systemStats(),
        ];

        return view('dashboard.index', $data);
    }
}
