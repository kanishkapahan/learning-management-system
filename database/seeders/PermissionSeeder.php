<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'SUPER_ADMIN' => 'Super Admin',
            'ADMIN' => 'Admin',
            'LECTURER' => 'Lecturer',
            'STUDENT' => 'Student',
        ];

        foreach ($roles as $name => $label) {
            Role::query()->firstOrCreate(['name' => $name], ['label' => $label]);
        }

        $permissions = [
            'students.view','students.create','students.update','students.delete','students.restore','students.import',
            'lecturers.view','lecturers.create','lecturers.update','lecturers.delete',
            'courses.view','courses.create','courses.update','courses.delete',
            'batches.view','batches.create','batches.update','batches.delete',
            'enrollments.create','enrollments.view',
            'exams.view','exams.create','exams.update','exams.delete',
            'results.view','results.create','results.approve','results.publish','results.import','results.recalculate',
            'attendance.view','attendance.mark',
            'announcements.view','announcements.create',
            'resources.view','resources.create','resources.download',
            'reports.view','reports.export',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission], ['label' => ucwords(str_replace(['.', '_'], ' ', $permission))]);
        }

        $allPermissions = Permission::all();
        $adminPermissions = $allPermissions->where('name', '!=', 'settings.manage')->values();
        $lecturerPermissions = $allPermissions->whereIn('name', [
            'exams.view','exams.create','exams.update',
            'results.view','results.create',
            'attendance.view','attendance.mark',
            'resources.view','resources.create','resources.download',
            'announcements.view','announcements.create',
            'courses.view','batches.view',
        ])->values();
        $studentPermissions = $allPermissions->whereIn('name', [
            'resources.view','resources.download','announcements.view',
        ])->values();

        Role::where('name', 'SUPER_ADMIN')->first()?->permissions()->sync($allPermissions->pluck('id'));
        Role::where('name', 'ADMIN')->first()?->permissions()->sync($adminPermissions->pluck('id'));
        Role::where('name', 'LECTURER')->first()?->permissions()->sync($lecturerPermissions->pluck('id'));
        Role::where('name', 'STUDENT')->first()?->permissions()->sync($studentPermissions->pluck('id'));
    }
}
