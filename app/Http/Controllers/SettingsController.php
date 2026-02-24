<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsUpdateRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key');
        $gradeThresholds = json_decode($settings['grade_thresholds'] ?? '[]', true) ?: [
            ['grade' => 'A', 'min' => 80],
            ['grade' => 'B', 'min' => 70],
            ['grade' => 'C', 'min' => 60],
            ['grade' => 'D', 'min' => 50],
            ['grade' => 'F', 'min' => 0],
        ];

        return view('settings.edit', compact('settings', 'gradeThresholds'));
    }

    public function update(SettingsUpdateRequest $request)
    {
        $payload = $request->validated();

        Setting::updateOrCreate(['key' => 'academic_year'], ['value' => $payload['academic_year'], 'type' => 'string']);
        Setting::updateOrCreate(['key' => 'enable_self_registration'], ['value' => $request->boolean('enable_self_registration') ? '1' : '0', 'type' => 'boolean']);
        Setting::updateOrCreate(['key' => 'grade_thresholds'], ['value' => json_encode($payload['grade_thresholds']), 'type' => 'json']);

        if ($request->hasFile('system_logo')) {
            $path = $request->file('system_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'system_logo'], ['value' => $path, 'type' => 'file']);
        }

        app(\App\Services\ActivityLogService::class)->log('settings.update', 'System settings updated', null, $request->user());
        return back()->with('success', 'Settings updated.');
    }
}
