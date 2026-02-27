# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

CodeIgniter 4-based Human Resources Management (HRM) system for Healthgenie/GSTC. Covers employee management, attendance processing, leave/approval workflows, salary, recruitment, appraisals, and resignations.

## Essential Commands

### Development
```bash
php spark serve --port 8080   # Dev server
php -l path/to/file           # Lint a PHP file
composer dump-autoload        # Regenerate autoloader after namespace changes
```

### Database
```bash
php spark migrate
php spark migrate:refresh --seed
```

### Custom Spark Commands
```bash
php spark attendance:process [--month=YYYY-MM] [--employees=1,2,3]  # Batch attendance processing
php spark attendance:parallel                                          # Parallel attendance (spawns workers)
php spark salary:process [--month=YYYY-MM]                            # Batch salary processing
php spark cron:birthday-anniversary [--month=YYYY-MM]                 # Birthday/anniversary notifications
php spark import:salary-to-appraisals                                 # Sync salary → appraisals
php spark address:check-due                                            # Address confirmation check
```

### Testing
```bash
composer test                        # All tests (runs phpunit)
phpunit --filter ModuleNameTest      # Single test module
php spark migrate:refresh --seed     # Reset DB before integration tests
```

## Architecture Overview

### Directory Structure
```
app/
  Commands/          # Spark CLI commands (attendance, salary, cron jobs)
  Config/
    CustomRoutes/    # Per-module route files, auto-loaded by Routes.php glob
  Controllers/
    Approval/        # Workflow approvals: Leave, Od, GatePass, Loan, CompOff, AdvanceSalary, DeductionMinute
    Attendance/      # Processor.php — main attendance calculation entry point
    Auth/            # Login/logout, password reset
    Cron/            # Controllers called by CLI commands (ServerCron, FinalSalary, etc.)
    Dashboards/      # Multiple dashboard views (general, historical, miss-punch, two-month)
    Master/          # CRUD for master data: Company, Department, Designation, Employee, Shift, Holiday, etc.
    Recruitment/     # Job listings, candidate management
    Reports/         # FinalPaidDays, FinalSalary, Leave, Punching, WagesRegister, etc.
    User/            # Profile.php (employee self-service), AttendanceHistory
    Additional/      # Contact, FileController (auth-gated file serving), Email
  Helpers/
    Config_defaults_helper.php  # Date helpers (first_date_of_month, etc.) + save_raw_punching_data()
    Form_helper.php
    AttendanceCacheHelper.php
  Libraries/
    AttendanceProcessor.php     # Orchestrates bulk attendance via Pipeline
    Pipeline.php                # Laravel-style pipeline: send()->through()->then()
    CustomEmail.php
    Hash.php
  Models/            # CI4 models, one per DB table
  Pipes/             # Pipeline stages for attendance calculation
    AttendanceProcessor/        # Per-row stages: RefactorPunchingRow, PunchTimeCleanup, CheckFraudPunches, etc.
    BasicDetails.php
    FetchFreshAttendance.php     # Pulls from eTimeOffice API
    ShiftRulesAndDetails.php
    GetAttendanceClean.php
    ApplyAttendanceOverride.php
    ProcessAttendance.php
    LateComingAdjustment.php
    SandwichSecondPass.php
    AdjustLastWorkingDate.php
    RecalculateForHeuer.php
    DashboardPipes/             # Lighter pipeline variant for dashboard display
  Services/
    ResignationAutoCompleteService.php
  Views/
    Templates/       # DashboardLayout.php, AsideMenu.php, PageHeader.php (shared layout)
    Auth/, Recruitment/, User/, Master/, Dashboards/, Reports/, etc.
```

### Routing Pattern
`app/Config/Routes.php` uses a glob loop to auto-load every file in `app/Config/CustomRoutes/`. Add new route files there — one per module. No manual include needed.

### Attendance Processing Pipeline
Attendance is calculated through a `Pipeline` (see `app/Libraries/Pipeline.php`) that chains `Pipe` classes. Each pipe's `handle($payload, $next)` method transforms the shared data array and calls `$next`. The main pipeline stages in order:
1. `BasicDetails` → employee/shift info
2. `FetchFreshAttendance` → pull raw punches from eTimeOffice API
3. `ShiftRulesAndDetails` → apply shift rules
4. `GetAttendanceClean` → normalise punch data
5. `ApplyAttendanceOverride` → apply HR overrides
6. `ProcessAttendance` → calculate paid/unpaid days
7. `LateComingAdjustment`, `SandwichSecondPass`, `AdjustLastWorkingDate`, `RecalculateForHeuer`

Result is saved to `pre_final_paid_days` table. `Processor.php` is the HTTP controller entry point; `AttendanceProcessor.php` (Library) is the batch CLI entry point.

### Role-Based Access
Session key `current_user` contains `employee_id`, `role`, etc. Roles used in access checks:
- `superuser`, `admin`, `hr` — full access
- `hod` / `HOD`, `manager`, `tl` — department-level access
- `employee` — self-service only

Access checks use `in_array($this->session->get('current_user')['role'], ['hr', 'superuser'])`.

### Key Configuration
- **Routes**: `app/Config/Routes.php` + `app/Config/CustomRoutes/*.php`
- **Database**: `app/Config/Database.php` (use `.env` for credentials)
- **Custom `.env` keys**: `app.resignationHrManagerIds` (comma-separated employee IDs)
- **Email**: sent from `app.hrm@healthgenie.in` via `\Config\Services::email()`
- **File uploads**: served through `FileController` (auth-gated), stored in `writable/uploads/`
- **eTimeOffice API**: external attendance machine API — called via `save_raw_punching_data()` helper

### Backup Files
The repo contains many backup files with suffixes like `_bkp`, `_bkp_2025_11_29`, `copy.php`, etc. **Ignore these** when reading or modifying code; always work on the canonical file without a date/bkp suffix.

## Naming Conventions
- Controllers: `PascalCase` (e.g., `RecruitmentController`)
- Models: `PascalCaseModel` matching table in snake_case
- Views: `PascalCase.php` in subdirectory matching controller module
- Methods: `camelCase`
- Database columns: `snake_case`
- Always use `use` statements at the top of files — never inline `\Full\Class\Name()` without a `use`.

## Important Notes
- **Entry point**: `public/index.php`
- **PHP 8.1+** required, with `intl` and `mbstring` extensions
- **Timezone**: `Asia/Kolkata` set in `BaseController::initController()`
- **Legacy front-end modules** (`appraisal/`, `recruitment/`, `stats/`, `demo1/` at project root): old UI bundles — all new logic goes through `app/Controllers/`
- **`CustomModel`** (`app/Models/CustomModel.php`): use for raw SQL queries when CI4 query builder is insufficient
- **`Config_defaults_helper`**: always loaded in controllers — provides date helpers and attendance API helpers globally
