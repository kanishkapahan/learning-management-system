# Learning Management System (LMS) - Laravel (Final Year Project)

## Architecture Diagram (Text)

```text
[Blade + Bootstrap + jQuery + DataTables/Select2/Chart.js]
              |
              v
        [Web/API Routes]
              |
              v
 [Controllers (thin, auth + request mapping)]
              |
              v
 [FormRequests] -> validation + authorization
              |
              v
 [Services] -> business rules, workflows, audit logging
              |
              v
 [Repositories] -> Eloquent query/data access
              |
              v
 [Models + Migrations + MySQL 8]
              |
              v
 [Storage(public) / Activity Logs / Reports / CSV Exports]
```

## Tech Stack
- Backend: Laravel 12 (compatible with Laravel 10/11 patterns)
- DB: MySQL 8+
- Frontend: Blade + Bootstrap 5 + jQuery
- Charts: Chart.js
- Table UI: DataTables
- Select UI: Select2
- API Auth: Sanctum (install command included)
- Auth UI: Breeze (install command included)

## Folder Structure (Custom Additions)

```text
app/
  Http/
    Controllers/
      Api/
      AttendanceController.php
      BatchController.php
      CourseController.php
      DashboardController.php
      EnrollmentController.php
      ExamController.php
      LecturerController.php
      ReportController.php
      ResourceController.php
      ResultController.php
      SearchController.php
      SettingsController.php
      StudentController.php
    Middleware/
      EnsurePermission.php
    Requests/
      AttendanceMarkRequest.php
      BulkEnrollmentRequest.php
      ExamStoreRequest.php
      ExamUpdateRequest.php
      ResultStoreRequest.php
      SettingsUpdateRequest.php
      StudentStoreRequest.php
      StudentUpdateRequest.php
  Listeners/
    LogAuthFailed.php
    LogAuthLogin.php
    LogAuthLogout.php
  Models/
    Concerns/HasAppRoles.php
    ActivityLog.php
    Announcement.php
    Attendance.php
    Batch.php
    Course.php
    Enrollment.php
    Exam.php
    ExamComponent.php
    Lecturer.php
    Permission.php
    ResourceDownloadLog.php
    ResourceFile.php
    Result.php
    Role.php
    Setting.php
    Student.php
    User.php
  Providers/
    AppServiceProvider.php
    AuthServiceProvider.php
    EventServiceProvider.php
  Repositories/
    Contracts/
    Eloquent/
  Services/
    ActivityLogService.php
    AttendanceService.php
    DashboardService.php
    EnrollmentService.php
    ExamService.php
    GradeService.php
    ReportService.php
    ResultService.php
    StudentService.php

database/
  migrations/
    2026_02_23_000100_add_lms_fields_to_users_table.php
    2026_02_23_000200_create_lms_core_tables.php
  seeders/
    DatabaseSeeder.php
    LmsDemoSeeder.php
    PermissionSeeder.php

resources/views/
  layouts/app.blade.php
  dashboard/index.blade.php
  students/*
  lecturers/*
  courses/*
  batches/*
  enrollments/*
  exams/*
  results/*
  attendance/*
  announcements/*
  resources/*
  reports/*
  settings/*
  partials/*

public/js/lms.js
routes/web.php
routes/api.php
```

## Setup Commands (Copy/Paste)

### 1) Create project (if starting fresh)
```bash
composer create-project laravel/laravel lms
cd lms
```

### 2) Install Breeze (Auth + email verification + password reset)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
php artisan migrate
```

### 3) Install Sanctum (API token auth)
```bash
composer require laravel/sanctum
php artisan install:api
php artisan migrate
```

### 4) (Preferred Option) Install Spatie Permission
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```
Note: This project already includes a custom role/permission implementation using Gates + middleware (`permission:*`). You can keep it or migrate to Spatie later.

### 5) Environment + DB
```bash
cp .env.example .env
php artisan key:generate
# set DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env
php artisan storage:link
php artisan migrate:fresh --seed
```

### 6) Run app
```bash
php artisan serve
```

## Default Logins (Seeded)
- `superadmin@lms.test` / `Admin@12345` (SUPER_ADMIN)
- `admin@lms.test` / `Admin@12345` (ADMIN)
- `lecturer1@lms.test` / `Lecturer@12345` (LECTURER)
- `student1@lms.test` / `Student@12345` (STUDENT)

## Coursework Mapping
1. High-quality UI/UX: `resources/views/layouts/app.blade.php`, dashboard cards/charts, responsive sidebar, toasts.
2. Client-side programming: `public/js/lms.js` (DataTables, Select2, live email validation, marks constraints, confirmations, loading states, search).
3. Architecture + DB connectivity: MVC + Services + Repositories + FormRequests + Eloquent + migrations.
4. Frontend + backend techniques: Blade, REST API, controllers, services, query/reporting.
5. Normalization + relations: students, lecturers, courses, batches, enrollments pivot, exams, results, attendance, resources, logs.
6. Auth + authorization: Laravel auth (Breeze install), email verification interface, role/permission gates + middleware.
7. Responsive design: Bootstrap 5 + mobile collapsible sidebar.

## Core Features Implemented (Summary)
- Auth-ready foundation: email verification interface, strong password defaults, auth activity logging listeners.
- Dashboard: summary cards + 3 Chart.js datasets + recent activity timeline + quick actions + global search.
- Master Data: students/lecturers/courses/batches CRUD (students soft delete + restore + CSV import).
- Enrollment: bulk enrollment with duplicate prevention via unique constraint and `updateOrInsert`.
- Exams: exam + component table, business rule validation, lecturer ownership enforcement.
- Results: draft -> approve -> publish workflow, grade calculation from DB settings, CSV bulk upload, recalculation tool.
- Attendance: batch/day attendance marking, low-attendance report (<80%), CSV export.
- Announcements + Resources: role/batch announcements, file uploads, download access checks + download audit logs.
- Reports: student list, pass rate, top performers, low attendance + CSV export.
- Settings (SUPER_ADMIN): academic year, grade thresholds, self-registration toggle, system logo upload.
- API (bonus required): `/api/me`, `/api/student/results`, `/api/student/attendance`, `/api/courses`, `/api/batches/{id}/announcements`.

## ERD Description (Tables + Relationships)
- `users` -> many-to-many `roles` (`role_user`)
- `roles` -> many-to-many `permissions` (`permission_role`)
- `users` -> many-to-many `permissions` (`permission_user`) optional direct grants
- `users` -> one-to-one `students` / `lecturers`
- `lecturers` -> one-to-many `courses`
- `courses` -> one-to-many `batches`
- `students` <-> `batches` via `enrollments` pivot (also stores `course_id`, `enrolled_at`, status)
- `courses` + `batches` -> one-to-many `exams`
- `exams` -> one-to-one/many `exam_components`
- `students` + `exams` -> `results` (unique student+exam)
- `students` + `batches` -> `attendances` (unique student+batch+date)
- `announcements` -> optional `batch`
- `resources` -> `course`, optional `batch`; `resource_download_logs` audit downloads
- `activity_logs` morphs to subject/causer for auditability
- `settings` stores system configs and grade thresholds

## Database Constraints / Optimization Notes
- Unique: `students.email`, `students.student_no`, `lecturers.email`, `courses.course_code`, `batches.batch_code`, `results(student_id,exam_id)`, `attendances(student_id,batch_id,date)`, `enrollments(student_id,batch_id,course_id)`
- Indexes on status/fk/date fields in core reporting tables
- Eager loading used in repository/controller listing queries
- SQL View: `vw_exam_pass_rates` created in migration for reporting aggregation

## Sample SQL Query/View Examples
```sql
SELECT * FROM vw_exam_pass_rates;

SELECT c.title, AVG(r.marks) AS avg_marks
FROM results r
JOIN exams e ON e.id = r.exam_id
JOIN courses c ON c.id = e.course_id
GROUP BY c.id, c.title;
```

## Screenshots (Placeholders)
- Dashboard: `docs/screenshots/dashboard.png`
- Students: `docs/screenshots/students.png`
- Exams: `docs/screenshots/exams.png`
- Results Workflow: `docs/screenshots/results-workflow.png`
- Reports: `docs/screenshots/reports.png`

## Important Notes
- This repository is scaffolded on Laravel 12 but follows Laravel 10/11-compatible architecture patterns.
- For complete auth pages/routes, run Breeze install commands above.
- For token issuing and `auth:sanctum`, run Sanctum install commands above.
- If you switch to Spatie Permission, replace the custom role middleware/gate usage gradually.

## Final Test Checklist
- [ ] Register/Login/Logout works (Breeze installed)
- [ ] Email verification required for dashboard access
- [ ] Password reset flow works
- [ ] Remember me works
- [ ] Login rate limiting works (Breeze login request)
- [ ] RBAC blocks unauthorized routes/actions
- [ ] Students CRUD + soft delete + restore + CSV import
- [ ] Lecturers/Courses/Batches CRUD
- [ ] Bulk enrollment prevents duplicates
- [ ] Exam rules enforced (date, pass marks, lecturer ownership)
- [ ] Result workflow draft -> approved -> published
- [ ] Bulk result CSV upload returns row-level errors
- [ ] Grade recalculation updates exam results after threshold change
- [ ] Attendance marking + low attendance report + export
- [ ] Announcements filtering by role/batch
- [ ] Resource upload/download + download logs
- [ ] Reports filter correctly + CSV exports download
- [ ] Dashboard charts load without JS errors
- [ ] Mobile sidebar collapses/opens correctly
- [ ] Storage link works for uploads (`php artisan storage:link`)
```
