# CodeIgniter 4 to Laravel Migration Plan
## Health Genie HRM System

**Document Version:** 1.0
**Date:** December 27, 2025
**Project:** Human Resources Management System
**Current Framework:** CodeIgniter 4
**Target Framework:** Laravel 11.x

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Current System Analysis](#current-system-analysis)
3. [Laravel Architecture Design](#laravel-architecture-design)
4. [Migration Strategy](#migration-strategy)
5. [Database Migration Approach](#database-migration-approach)
6. [Code Structure Improvements](#code-structure-improvements)
7. [Implementation Phases](#implementation-phases)
8. [Risk Assessment & Mitigation](#risk-assessment--mitigation)
9. [Testing Strategy](#testing-strategy)
10. [Deployment Plan](#deployment-plan)
11. [Post-Migration Optimization](#post-migration-optimization)

---

## Executive Summary

### Current State
- **Framework:** CodeIgniter 4.x
- **Controllers:** 85+ controllers
- **Models:** 91+ models
- **Routes:** 51 custom route files
- **Size:** Large-scale enterprise HRM application
- **Key Modules:** Appraisals, Recruitment, Attendance, Leave Management, Salary Processing

### Migration Goals
1. **Modern Architecture:** Leverage Laravel's robust ecosystem
2. **Better Code Structure:** Implement SOLID principles and design patterns
3. **Enhanced Security:** Utilize Laravel's built-in security features
4. **Improved Performance:** Optimize database queries and caching
5. **Maintainability:** Cleaner, more testable code
6. **Scalability:** Prepare for future growth

### Estimated Timeline
- **Total Duration:** 6-9 months
- **Phase 1 (Setup):** 2-3 weeks
- **Phase 2 (Core Migration):** 3-4 months
- **Phase 3 (Testing):** 1-2 months
- **Phase 4 (Deployment):** 2-4 weeks

---

## Current System Analysis

### System Overview

```
Current Architecture:
┌─────────────────────────────────────────┐
│         CodeIgniter 4 HRM               │
├─────────────────────────────────────────┤
│ Controllers (85+)                       │
│  ├── Appraisals                        │
│  ├── Attendance Processing             │
│  ├── Leave Management                  │
│  ├── Recruitment                       │
│  ├── Approval Workflows                │
│  ├── Reports & Dashboards              │
│  └── User Management                   │
├─────────────────────────────────────────┤
│ Models (91+)                           │
│  ├── Employee & Organization           │
│  ├── Compensation & Benefits           │
│  ├── Attendance & Time                 │
│  ├── Leave Management                  │
│  ├── Deductions & Loans                │
│  └── Recruitment                       │
├─────────────────────────────────────────┤
│ Custom Libraries                        │
│  ├── AttendanceProcessor (Pipeline)    │
│  ├── Hash (Security)                   │
│  ├── CustomEmail                       │
│  └── Pipeline Pattern                  │
├─────────────────────────────────────────┤
│ Pipes (14 processing stages)           │
│  ├── FetchFreshAttendance              │
│  ├── ProcessAttendance                 │
│  ├── LateComingAdjustment              │
│  └── RecalculateForHeuer               │
└─────────────────────────────────────────┘
```

### Key Features Inventory

#### 1. **Attendance Management** (Most Complex)
- Raw punching data sync from eTimeOffice API
- 14-stage pipeline processing
- Shift-based calculations
- Late-coming grace management
- Week-off and holiday handling
- Manual punch adjustments
- Attendance overrides
- Machine overrides

#### 2. **Leave Management**
- Multiple leave types (CL, EL, CO, SL)
- Leave balance tracking
- Credit history
- Approval workflows
- Manual overrides
- Sandwich leave detection

#### 3. **Appraisal System**
- Appraisal creation and editing
- Salary revision tracking
- Bonus calculations
- Min wage compliance
- PDF report generation

#### 4. **Recruitment Module**
- Job listing management
- Candidate tracking
- Disposition system
- Comments and collaboration
- Job closure approvals

#### 5. **Approval Workflows**
- Leave requests
- Advance salary
- Loans with EMI
- OD (Overtime/Duty)
- CompOff credits
- Deduction minutes
- Gate passes
- Wave-off requests

#### 6. **Salary Processing**
- Pre-final salary calculations
- Final salary generation
- TDS calculations
- Special benefits
- Phone bills
- Imprest management
- Deductions tracking

#### 7. **Notification System**
- Birthday/Anniversary notifications
- Leave balance alerts
- Probation notifications
- Welcome emails
- Address confirmation

#### 8. **Reporting**
- Final attendance sheets
- Final salary reports
- Punching analysis
- Dashboard metrics
- Historical data views

### Technical Debt Identified

1. **Hard-coded Role Checks**
   ```php
   // Found in multiple controllers
   if(in_array($employee_id, [40, 93])) {
       // Special access
   }
   ```

2. **Mixed Legacy & Modern Code**
   - Legacy modules: `appraisal/`, `recruitment/`, `demo1/`
   - Inconsistent coding standards

3. **Large Controller Files**
   - `Master/Employee/Edit.php` (97KB)
   - `Approval/Leave.php` (81KB)
   - `Reports/FinalPaidDays.php` (67KB)

4. **Helper Function Overload**
   - `Config_defaults_helper.php` (69KB)
   - Mixed business logic in helpers

5. **Limited Test Coverage**
   - Few automated tests
   - Manual testing dependency

6. **Session-based Architecture**
   - No API authentication
   - Limited mobile support

---

## Laravel Architecture Design

### Proposed Laravel Structure

```
laravel-hrm/
├── app/
│   ├── Actions/                    # Single-purpose action classes
│   │   ├── Appraisal/
│   │   ├── Attendance/
│   │   ├── Leave/
│   │   └── Recruitment/
│   │
│   ├── Console/
│   │   ├── Commands/               # Artisan commands (Cron jobs)
│   │   │   ├── SyncAttendanceData.php
│   │   │   ├── ProcessMonthlyAttendance.php
│   │   │   ├── SendBirthdayNotifications.php
│   │   │   └── GenerateFinalSalary.php
│   │
│   ├── DataTransferObjects/        # DTOs for data passing
│   │   ├── EmployeeDTO.php
│   │   ├── AttendanceDTO.php
│   │   └── LeaveRequestDTO.php
│   │
│   ├── Domain/                     # Domain-driven design
│   │   ├── Attendance/
│   │   │   ├── Models/
│   │   │   ├── Services/
│   │   │   ├── Repositories/
│   │   │   ├── Policies/
│   │   │   └── Pipelines/
│   │   ├── Employee/
│   │   ├── Leave/
│   │   ├── Appraisal/
│   │   ├── Recruitment/
│   │   └── Salary/
│   │
│   ├── Enums/                      # PHP 8.1+ Enums
│   │   ├── LeaveType.php
│   │   ├── ApprovalStatus.php
│   │   ├── EmployeeStatus.php
│   │   └── DispositionType.php
│   │
│   ├── Events/                     # Event-driven architecture
│   │   ├── Employee/
│   │   │   ├── EmployeeHired.php
│   │   │   ├── EmployeeResigned.php
│   │   │   └── ProbationCompleted.php
│   │   ├── Attendance/
│   │   │   ├── AttendanceProcessed.php
│   │   │   └── LateComing.php
│   │   └── Leave/
│   │       ├── LeaveRequested.php
│   │       ├── LeaveApproved.php
│   │       └── LeaveRejected.php
│   │
│   ├── Exceptions/                 # Custom exceptions
│   │   ├── Attendance/
│   │   ├── Leave/
│   │   └── Appraisal/
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                # API controllers (RESTful)
│   │   │   │   ├── V1/
│   │   │   │   │   ├── AppraisalController.php
│   │   │   │   │   ├── AttendanceController.php
│   │   │   │   │   ├── LeaveController.php
│   │   │   │   │   └── EmployeeController.php
│   │   │   │
│   │   │   └── Web/                # Web controllers (thin)
│   │   │       ├── DashboardController.php
│   │   │       ├── AppraisalController.php
│   │   │       ├── AttendanceController.php
│   │   │       └── ProfileController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── CheckEmployeeStatus.php
│   │   │   ├── CheckApprovalPermission.php
│   │   │   └── LogUserActivity.php
│   │   │
│   │   ├── Requests/               # Form request validation
│   │   │   ├── Appraisal/
│   │   │   │   ├── StoreAppraisalRequest.php
│   │   │   │   └── UpdateAppraisalRequest.php
│   │   │   ├── Leave/
│   │   │   │   ├── StoreLeaveRequest.php
│   │   │   │   └── ApproveLeaveRequest.php
│   │   │   └── Employee/
│   │   │
│   │   └── Resources/              # API resources
│   │       ├── EmployeeResource.php
│   │       ├── AppraisalResource.php
│   │       └── AttendanceResource.php
│   │
│   ├── Jobs/                       # Queue jobs
│   │   ├── ProcessAttendanceBatch.php
│   │   ├── GenerateSalaryReport.php
│   │   ├── SendEmailNotification.php
│   │   └── SyncBiometricData.php
│   │
│   ├── Listeners/                  # Event listeners
│   │   ├── SendWelcomeEmail.php
│   │   ├── UpdateLeaveBalance.php
│   │   └── NotifyApprover.php
│   │
│   ├── Mail/                       # Mailable classes
│   │   ├── BirthdayGreeting.php
│   │   ├── LeaveApprovalNotification.php
│   │   └── SalarySlip.php
│   │
│   ├── Models/                     # Eloquent models
│   │   ├── Employee.php
│   │   ├── Company.php
│   │   ├── Department.php
│   │   ├── Appraisal.php
│   │   ├── Leave.php
│   │   ├── Attendance.php
│   │   └── ...
│   │
│   ├── Notifications/              # Notification classes
│   │   ├── LeaveRequestNotification.php
│   │   ├── ApprovalRequiredNotification.php
│   │   └── ProbationEndingNotification.php
│   │
│   ├── Observers/                  # Model observers
│   │   ├── EmployeeObserver.php
│   │   ├── AppraisalObserver.php
│   │   └── LeaveObserver.php
│   │
│   ├── Policies/                   # Authorization policies
│   │   ├── AppraisalPolicy.php
│   │   ├── LeavePolicy.php
│   │   └── EmployeePolicy.php
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── DomainServiceProvider.php
│   │
│   ├── Repositories/               # Repository pattern
│   │   ├── Contracts/
│   │   │   ├── EmployeeRepositoryInterface.php
│   │   │   ├── AppraisalRepositoryInterface.php
│   │   │   └── AttendanceRepositoryInterface.php
│   │   │
│   │   └── Eloquent/
│   │       ├── EmployeeRepository.php
│   │       ├── AppraisalRepository.php
│   │       └── AttendanceRepository.php
│   │
│   ├── Services/                   # Business logic services
│   │   ├── Attendance/
│   │   │   ├── AttendanceProcessingService.php
│   │   │   ├── GraceCalculationService.php
│   │   │   └── ShiftService.php
│   │   ├── Appraisal/
│   │   │   ├── AppraisalService.php
│   │   │   └── SalaryRevisionService.php
│   │   ├── Leave/
│   │   │   ├── LeaveBalanceService.php
│   │   │   ├── LeaveCreditService.php
│   │   │   └── LeaveApprovalService.php
│   │   └── Notification/
│   │       └── NotificationService.php
│   │
│   └── Traits/                     # Reusable traits
│       ├── HasApprovalWorkflow.php
│       ├── HasRevisionHistory.php
│       ├── HasSoftDeletes.php
│       └── Searchable.php
│
├── bootstrap/
├── config/
│   ├── hrm.php                     # Custom HRM config
│   ├── attendance.php              # Attendance settings
│   └── approval.php                # Approval workflow config
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── 2025_01_01_create_employees_table.php
│   │   ├── 2025_01_02_create_appraisals_table.php
│   │   └── ...
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php
│       ├── CompanySeeder.php
│       └── DepartmentSeeder.php
│
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   │   ├── app.js
│   │   └── components/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── admin.blade.php
│       │   └── dashboard.blade.php
│       ├── components/
│       ├── appraisals/
│       ├── attendance/
│       ├── leaves/
│       └── profile/
│
├── routes/
│   ├── web.php
│   ├── api.php
│   ├── console.php
│   └── channels.php
│
├── storage/
│   ├── app/
│   │   ├── uploads/
│   │   │   └── employees/
│   │   └── reports/
│   ├── framework/
│   └── logs/
│
└── tests/
    ├── Feature/
    │   ├── Appraisal/
    │   ├── Attendance/
    │   ├── Leave/
    │   └── Recruitment/
    └── Unit/
        ├── Services/
        ├── Repositories/
        └── Pipelines/
```

### Key Architecture Decisions

#### 1. **Domain-Driven Design (DDD)**
- Organize code by business domain
- Each domain is self-contained
- Clear boundaries between modules

#### 2. **Repository Pattern**
```php
// Interface
interface EmployeeRepositoryInterface {
    public function find(int $id): ?Employee;
    public function findByEmployeeNumber(string $number): ?Employee;
    public function getActive(): Collection;
    public function search(array $criteria): Collection;
}

// Implementation
class EmployeeRepository implements EmployeeRepositoryInterface {
    public function find(int $id): ?Employee {
        return Employee::with(['department', 'designation'])->find($id);
    }
}
```

#### 3. **Service Layer Pattern**
```php
class AppraisalService {
    public function __construct(
        private AppraisalRepository $repository,
        private SalaryRevisionService $salaryService,
        private NotificationService $notificationService
    ) {}

    public function createAppraisal(EmployeeDTO $employee, AppraisalDTO $data): Appraisal {
        DB::transaction(function() use ($employee, $data) {
            $appraisal = $this->repository->create($data);
            $this->salaryService->createRevision($appraisal);
            $this->notificationService->notifyHR($appraisal);
            return $appraisal;
        });
    }
}
```

#### 4. **Action Classes** (Single Responsibility)
```php
class ProcessEmployeeAttendance {
    public function execute(Employee $employee, Carbon $date): AttendanceDTO {
        // Single focused task
        return $this->processAttendance($employee, $date);
    }
}
```

#### 5. **Pipeline Pattern** (Existing, Enhanced)
```php
class AttendancePipeline {
    public function process(AttendanceDTO $data): AttendanceDTO {
        return Pipeline::send($data)
            ->through([
                FetchRawData::class,
                CleanData::class,
                ApplyShiftRules::class,
                CalculateGrace::class,
                ApplyOverrides::class,
                CalculateFinalHours::class,
            ])
            ->thenReturn();
    }
}
```

#### 6. **Event-Driven Architecture**
```php
// Event
class LeaveApproved {
    public function __construct(public Leave $leave) {}
}

// Listener
class UpdateLeaveBalance {
    public function handle(LeaveApproved $event): void {
        $this->leaveBalanceService->deduct(
            $event->leave->employee,
            $event->leave->days
        );
    }
}
```

#### 7. **API-First Approach**
- RESTful API for all operations
- Web UI consumes API
- Mobile app ready
- Versioned API (v1, v2)

#### 8. **Laravel Sanctum** (Authentication)
```php
// API tokens for SPA
// Session for web
// Multi-guard support
```

#### 9. **Spatie Laravel Permission** (Authorization)
```php
// Roles & Permissions
$user->givePermissionTo('approve-leaves');
$user->assignRole('manager');

// In controllers
$this->authorize('approve', $leave);
```

#### 10. **Queue System** (Redis/Database)
```php
// Long-running tasks
ProcessAttendanceBatch::dispatch($employees);
GenerateSalaryReport::dispatch($month)->onQueue('reports');
```

---

## Migration Strategy

### Approach: **Parallel Development with Gradual Migration**

```
Migration Phases:
┌─────────────────────────────────────────┐
│ Phase 1: Foundation (2-3 weeks)        │
│  - Laravel setup                        │
│  - Database migration                   │
│  - Authentication                       │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│ Phase 2: Core Modules (3-4 months)     │
│  - Employee Management                  │
│  - Attendance (Most Complex)            │
│  - Leave Management                     │
│  - Appraisals                          │
│  - Recruitment                         │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│ Phase 3: Advanced Features (1-2 months)│
│  - Approval workflows                   │
│  - Salary processing                    │
│  - Reports & Dashboards                │
│  - Notifications                       │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│ Phase 4: Testing & Deployment (1 month)│
│  - Comprehensive testing                │
│  - Data migration                       │
│  - Go-live                             │
└─────────────────────────────────────────┘
```

### Module Migration Priority

| Priority | Module | Complexity | Duration | Dependencies |
|----------|--------|------------|----------|--------------|
| 1 | Authentication & Users | Low | 1 week | None |
| 2 | Employee Management | Medium | 2 weeks | Auth |
| 3 | Company/Dept/Designation | Low | 1 week | Employee |
| 4 | Shift Management | Medium | 1 week | Employee |
| 5 | Attendance Processing | **High** | 4-6 weeks | Shift, Employee |
| 6 | Leave Management | Medium | 3 weeks | Employee, Attendance |
| 7 | Appraisals | Medium | 3 weeks | Employee |
| 8 | Recruitment | Medium | 2-3 weeks | Employee |
| 9 | Approval Workflows | Medium | 2 weeks | All modules |
| 10 | Salary Processing | High | 3-4 weeks | Appraisals, Attendance |
| 11 | Reports & Dashboards | Medium | 2-3 weeks | All modules |
| 12 | Notifications | Low | 1 week | All modules |

### Data Migration Strategy

#### Option 1: **Big Bang Migration** (NOT Recommended)
- Migrate all at once
- High risk
- Downtime required

#### Option 2: **Strangler Fig Pattern** (Recommended)
```
Step 1: Run both systems in parallel
┌────────────────┐     ┌────────────────┐
│  CodeIgniter   │ ←→  │    Laravel     │
│   (Legacy)     │     │     (New)      │
└────────────────┘     └────────────────┘
        ↓                      ↓
   Same Database (Initially)

Step 2: Route traffic gradually
┌────────────────┐     ┌────────────────┐
│  CodeIgniter   │     │    Laravel     │
│   30% Traffic  │     │   70% Traffic  │
└────────────────┘     └────────────────┘

Step 3: Full migration
┌────────────────┐     ┌────────────────┐
│  CodeIgniter   │     │    Laravel     │
│   RETIRED      │     │  100% Traffic  │
└────────────────┘     └────────────────┘
```

#### Option 3: **Module-by-Module** (Hybrid Approach)
- Migrate one module at a time
- Keep old modules running
- API-based communication between systems

---

## Database Migration Approach

### Step 1: Schema Analysis

**Current CI4 Database → Laravel Migration Files**

```php
// Example: employees table migration
Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->string('employee_number')->unique();
    $table->string('name');
    $table->string('email')->unique();
    $table->foreignId('company_id')->constrained();
    $table->foreignId('department_id')->constrained();
    $table->foreignId('designation_id')->constrained();
    $table->foreignId('shift_id')->nullable()->constrained();
    $table->date('date_of_joining');
    $table->date('date_of_birth');
    $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
    $table->enum('employment_type', ['permanent', 'contract', 'probation']);
    $table->decimal('current_salary', 10, 2)->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->index(['company_id', 'status']);
    $table->index('date_of_joining');
});
```

### Step 2: Revision/History Tables Pattern

**Audit Trail Architecture:**

```php
// Main table
Schema::create('appraisals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained();
    $table->decimal('current_salary', 10, 2);
    $table->decimal('revised_salary', 10, 2);
    $table->date('effective_from');
    $table->text('remarks')->nullable();
    $table->timestamps();
});

// Revision history (using Spatie Laravel Activitylog)
// Or custom revision table
Schema::create('appraisal_revisions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('appraisal_id')->constrained();
    $table->json('old_data');
    $table->json('new_data');
    $table->foreignId('revised_by')->constrained('users');
    $table->timestamp('revised_at');
});
```

### Step 3: Data Migration Commands

```php
// Artisan command for data migration
php artisan migrate:ci4-data employees
php artisan migrate:ci4-data appraisals --batch=100
php artisan migrate:ci4-data attendance --from=2024-01-01
```

### Database Changes & Improvements

| Table | CI4 Issues | Laravel Improvements |
|-------|-----------|---------------------|
| All | Inconsistent naming | snake_case standardization |
| employees | No soft deletes | Add `deleted_at` |
| All revision tables | Custom implementation | Use Spatie Activitylog |
| All | Mixed indexes | Optimize indexes |
| Attendance | Large single table | Consider partitioning |
| Raw punching | No cleanup | Add retention policy |

---

## Code Structure Improvements

### 1. **Replace Large Controllers with Actions**

**Before (CI4):**
```php
// Master/Employee/Edit.php (97KB)
class Edit extends BaseController {
    public function index() {
        // 2000+ lines of code
    }
}
```

**After (Laravel):**
```php
// Actions
class UpdateEmployeeBasicInfo {
    public function execute(Employee $employee, EmployeeDTO $data): Employee {
        $employee->update($data->toArray());
        return $employee;
    }
}

class UpdateEmployeeSalary {
    public function execute(Employee $employee, SalaryDTO $data): void {
        // Focused salary update logic
    }
}

// Controller (thin)
class EmployeeController extends Controller {
    public function update(UpdateEmployeeRequest $request, Employee $employee) {
        $this->authorize('update', $employee);

        $updated = app(UpdateEmployeeBasicInfo::class)->execute(
            $employee,
            EmployeeDTO::fromRequest($request)
        );

        return EmployeeResource::make($updated);
    }
}
```

### 2. **Extract Business Logic from Helpers to Services**

**Before (CI4):**
```php
// config_defaults_helper.php (69KB)
function save_raw_punching_data($data) {
    // 500 lines of logic
}
```

**After (Laravel):**
```php
// Service
class AttendanceDataService {
    public function saveRawPunchingData(array $data): RawPunchingData {
        return DB::transaction(function() use ($data) {
            return RawPunchingData::create([
                'employee_id' => $data['employee_id'],
                'punch_time' => Carbon::parse($data['time']),
                'machine_id' => $data['machine_id'],
            ]);
        });
    }
}
```

### 3. **Implement Repository Pattern**

**Before (CI4):**
```php
// Direct model calls in controllers
$employees = model(EmployeeModel::class)->where('status', 'active')->findAll();
```

**After (Laravel):**
```php
// Repository Interface
interface EmployeeRepositoryInterface {
    public function getActive(): Collection;
    public function findByCompany(int $companyId): Collection;
}

// Eloquent Implementation
class EmployeeRepository implements EmployeeRepositoryInterface {
    public function getActive(): Collection {
        return Employee::query()
            ->where('status', 'active')
            ->with(['department', 'designation'])
            ->get();
    }
}

// Controller
class EmployeeController {
    public function __construct(
        private EmployeeRepositoryInterface $employees
    ) {}

    public function index() {
        return EmployeeResource::collection(
            $this->employees->getActive()
        );
    }
}
```

### 4. **Replace Hard-coded Permissions with Policy**

**Before (CI4):**
```php
// Hard-coded in controller
if(in_array($employee_id, [40, 93])) {
    // Special access
}
```

**After (Laravel):**
```php
// Policy
class AppraisalPolicy {
    public function approve(User $user, Appraisal $appraisal): bool {
        return $user->hasPermissionTo('approve-appraisals')
            || $user->isManagerOf($appraisal->employee);
    }
}

// Controller
public function approve(Appraisal $appraisal) {
    $this->authorize('approve', $appraisal);
    // ...
}
```

### 5. **Use Enums Instead of String Constants**

**Before (CI4):**
```php
$leave->status = 'approved';
```

**After (Laravel - PHP 8.1+):**
```php
enum ApprovalStatus: string {
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function isPending(): bool {
        return $this === self::Pending;
    }
}

$leave->status = ApprovalStatus::Approved;
```

### 6. **Event-Driven for Side Effects**

**Before (CI4):**
```php
// All in one method
public function approve($id) {
    $leave = $this->model->find($id);
    $leave->status = 'approved';
    $leave->save();

    // Update balance
    $this->leaveBalance->deduct(...);

    // Send email
    $this->email->send(...);

    // Log activity
    $this->logger->log(...);
}
```

**After (Laravel):**
```php
// Event
event(new LeaveApproved($leave));

// Listeners
class UpdateLeaveBalance {
    public function handle(LeaveApproved $event) {
        $this->service->deductBalance($event->leave);
    }
}

class SendLeaveApprovalEmail {
    public function handle(LeaveApproved $event) {
        Mail::to($event->leave->employee)->send(
            new LeaveApprovalMail($event->leave)
        );
    }
}
```

### 7. **Queue Long-Running Tasks**

**Before (CI4):**
```php
// Synchronous processing
public function processAttendance() {
    foreach($employees as $employee) {
        $this->processor->process($employee); // 30 seconds per employee
    }
}
```

**After (Laravel):**
```php
// Queue job
foreach($employees->chunk(25) as $batch) {
    ProcessAttendanceBatch::dispatch($batch)->onQueue('attendance');
}

// Job
class ProcessAttendanceBatch implements ShouldQueue {
    use Dispatchable, Queueable;

    public function handle(AttendanceProcessingService $service) {
        foreach($this->employees as $employee) {
            $service->process($employee);
        }
    }
}
```

---

## Implementation Phases

### Phase 1: Foundation (Weeks 1-3)

#### Week 1: Laravel Setup
- [ ] Install Laravel 11.x
- [ ] Configure environment (.env)
- [ ] Setup database connection
- [ ] Install required packages:
  - `laravel/sanctum` (API auth)
  - `spatie/laravel-permission` (roles)
  - `spatie/laravel-activitylog` (audit)
  - `barryvdh/laravel-dompdf` (PDF)
  - `maatwebsite/excel` (Excel)
  - `laravel/horizon` (queue monitoring)
  - `spatie/laravel-query-builder` (API filters)

#### Week 2: Database Migration
- [ ] Create all migration files
- [ ] Define relationships
- [ ] Setup indexes
- [ ] Create seeders
- [ ] Test migrations on dev database

#### Week 3: Authentication & User Module
- [ ] Setup Laravel Sanctum
- [ ] Migrate User/Employee models
- [ ] Implement login/logout
- [ ] Role & permission setup
- [ ] Profile management

**Deliverable:** Working authentication system

---

### Phase 2: Core Modules (Months 2-5)

#### Month 2: Employee Management
- [ ] Employee CRUD operations
- [ ] Company/Department/Designation
- [ ] Employee search & filters
- [ ] Document uploads
- [ ] Address management
- [ ] Probation tracking

**Deliverable:** Complete employee management module

#### Month 3: Attendance System (Most Complex)
Week 1-2:
- [ ] Raw punching data model
- [ ] eTimeOffice API integration
- [ ] Data sync command

Week 3-4:
- [ ] Shift management
- [ ] Shift rules & overrides
- [ ] Holiday calendar

Week 5-6:
- [ ] Attendance processing pipeline
  - [ ] FetchRawData pipe
  - [ ] CleanData pipe
  - [ ] ApplyShiftRules pipe
  - [ ] CalculateGrace pipe
  - [ ] ApplyOverrides pipe

Week 7-8:
- [ ] Manual punch adjustments
- [ ] Attendance overrides
- [ ] Grace balance management
- [ ] Final paid days calculation

**Deliverable:** Working attendance processing system

#### Month 4: Leave & Appraisals
Week 1-2: Leave Management
- [ ] Leave types & balances
- [ ] Leave requests
- [ ] Leave approval workflow
- [ ] Leave credit system
- [ ] Leave reports

Week 3-4: Appraisal System
- [ ] Appraisal CRUD
- [ ] Salary revision tracking
- [ ] Bonus calculations
- [ ] Min wage compliance
- [ ] PDF report generation

**Deliverable:** Leave and appraisal modules

#### Month 5: Recruitment Module
- [ ] Job listing management
- [ ] Candidate tracking
- [ ] Disposition system
- [ ] Comments & collaboration
- [ ] Job closure approvals
- [ ] Candidate search

**Deliverable:** Recruitment module

---

### Phase 3: Advanced Features (Months 6-7)

#### Month 6: Approval Workflows & Salary
Week 1-2: Approval Workflows
- [ ] Leave approval
- [ ] Advance salary approval
- [ ] Loan approval (with EMI)
- [ ] OD approval
- [ ] CompOff approval
- [ ] Gate pass approval
- [ ] Wave-off approval

Week 3-4: Salary Processing
- [ ] Pre-final salary calculation
- [ ] Final salary generation
- [ ] TDS calculations
- [ ] Special benefits
- [ ] Deductions tracking
- [ ] Salary slip PDF

**Deliverable:** Complete approval workflows and salary system

#### Month 7: Reports & Dashboards
- [ ] Attendance reports
- [ ] Salary reports
- [ ] Leave reports
- [ ] Recruitment reports
- [ ] Dashboard metrics
- [ ] Historical data views
- [ ] Export to Excel/PDF

**Deliverable:** Comprehensive reporting system

---

### Phase 4: Testing & Deployment (Month 8-9)

#### Month 8: Testing
Week 1-2: Unit Testing
- [ ] Repository tests
- [ ] Service tests
- [ ] Action tests
- [ ] Pipeline tests

Week 3-4: Feature Testing
- [ ] Authentication flow
- [ ] Employee management
- [ ] Attendance processing
- [ ] Leave workflow
- [ ] Appraisal workflow
- [ ] Approval workflows

**Deliverable:** 80%+ test coverage

#### Month 9: Data Migration & Deployment
Week 1: Data Migration
- [ ] Export CI4 data
- [ ] Transform data
- [ ] Import to Laravel
- [ ] Verify data integrity
- [ ] Historical data preservation

Week 2: UAT (User Acceptance Testing)
- [ ] Internal testing
- [ ] User feedback
- [ ] Bug fixes
- [ ] Performance optimization

Week 3: Deployment Preparation
- [ ] Server setup (Laravel Forge/Vapor)
- [ ] Queue workers
- [ ] Cron jobs
- [ ] SSL certificates
- [ ] Database backups

Week 4: Go-Live
- [ ] Final data sync
- [ ] DNS cutover
- [ ] Monitor performance
- [ ] Support team ready

**Deliverable:** Live Laravel HRM system

---

## Risk Assessment & Mitigation

### High-Risk Areas

| Risk | Probability | Impact | Mitigation Strategy |
|------|------------|--------|-------------------|
| **Data loss during migration** | Medium | Critical | - Multiple backups<br>- Dry-run migrations<br>- Rollback plan |
| **Attendance calculation errors** | High | High | - Extensive testing<br>- Parallel run validation<br>- Keep CI4 as fallback |
| **User resistance to new UI** | Medium | Medium | - Training sessions<br>- Gradual rollout<br>- Feedback incorporation |
| **Performance degradation** | Medium | High | - Load testing<br>- Query optimization<br>- Caching strategy |
| **Integration failures (eTimeOffice)** | Low | High | - API mocking<br>- Comprehensive error handling<br>- Fallback mechanisms |
| **Approval workflow bugs** | Medium | High | - State machine testing<br>- Permission validation<br>- Audit logging |

### Mitigation Strategies

#### 1. **Parallel System Run**
```
Month 1-2: CI4 (100%), Laravel (0%)
Month 3-4: CI4 (100%), Laravel (Testing)
Month 5-6: CI4 (80%), Laravel (20%) - Selected modules
Month 7-8: CI4 (30%), Laravel (70%) - Gradual shift
Month 9: CI4 (0%), Laravel (100%) - Full cutover
```

#### 2. **Data Validation**
- Compare outputs from both systems
- Automated reconciliation reports
- Daily data sync verification

#### 3. **Rollback Plan**
```
If critical issues found:
1. Stop Laravel deployment
2. Route all traffic to CI4
3. Analyze issues
4. Fix and retest
5. Gradual re-deployment
```

---

## Testing Strategy

### Test Pyramid

```
           ┌─────────────┐
           │   E2E Tests │  (10% - Critical workflows)
           └─────────────┘
         ┌─────────────────┐
         │  Feature Tests  │  (30% - API/Web features)
         └─────────────────┘
      ┌──────────────────────┐
      │    Unit Tests        │  (60% - Services/Actions)
      └──────────────────────┘
```

### Test Coverage Goals

| Component | Coverage Target | Priority |
|-----------|----------------|----------|
| Services | 90% | High |
| Actions | 85% | High |
| Repositories | 80% | Medium |
| Controllers | 70% | Medium |
| Pipelines | 95% | Critical |
| Policies | 90% | High |

### Key Test Scenarios

#### Attendance Processing
```php
test('attendance pipeline processes employee correctly', function() {
    $employee = Employee::factory()->create();
    $rawData = RawPunchingData::factory()->create([
        'employee_id' => $employee->id,
        'punch_time' => '2025-01-15 09:00:00'
    ]);

    $result = app(AttendancePipeline::class)->process($employee, '2025-01-15');

    expect($result)
        ->toBeInstanceOf(AttendanceDTO::class)
        ->and($result->totalHours)->toBe(8.0)
        ->and($result->lateMinutes)->toBe(0);
});
```

#### Leave Approval Workflow
```php
test('manager can approve team member leave', function() {
    $manager = User::factory()->manager()->create();
    $employee = Employee::factory()->create(['manager_id' => $manager->id]);
    $leave = Leave::factory()->pending()->create(['employee_id' => $employee->id]);

    actingAs($manager)
        ->post("/api/leaves/{$leave->id}/approve")
        ->assertOk();

    expect($leave->fresh()->status)->toBe(ApprovalStatus::Approved);
});
```

---

## Deployment Plan

### Infrastructure Setup

#### Recommended Stack
```
┌─────────────────────────────────────┐
│  Load Balancer (Nginx/Cloudflare)  │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  Web Servers (PHP 8.2 + Laravel)    │
│  - Laravel Octane (for performance) │
│  - 2-4 instances (auto-scaling)     │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  Database (MySQL 8.0)               │
│  - Master-Slave replication         │
│  - Automated backups                │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  Cache (Redis)                      │
│  - Session storage                  │
│  - Query cache                      │
│  - Queue backend                    │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  Queue Workers (Supervisor)         │
│  - Attendance processing            │
│  - Email sending                    │
│  - Report generation                │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  File Storage (S3/MinIO)            │
│  - Employee documents               │
│  - Generated reports                │
│  - Salary slips                     │
└─────────────────────────────────────┘
```

#### Laravel Forge Deployment
```bash
# Server requirements
- PHP 8.2+
- MySQL 8.0+
- Redis 7+
- Supervisor (for queues)
- Node.js 20+ (for frontend builds)

# Forge setup
1. Create server on Forge
2. Install Laravel site
3. Configure environment
4. Setup queue workers
5. Configure scheduled tasks
6. Enable quick deploy (Git push auto-deploy)
```

### Deployment Checklist

#### Pre-Deployment
- [ ] All tests passing
- [ ] Code review completed
- [ ] Database migrations tested
- [ ] Environment variables configured
- [ ] Queue workers configured
- [ ] Cron jobs scheduled
- [ ] Error tracking setup (Sentry/Bugsnag)
- [ ] Performance monitoring (New Relic/DataDog)

#### Deployment Day
- [ ] Maintenance mode ON
- [ ] Database backup
- [ ] Deploy code
- [ ] Run migrations
- [ ] Clear caches
- [ ] Restart queue workers
- [ ] Smoke tests
- [ ] Maintenance mode OFF
- [ ] Monitor error logs

#### Post-Deployment
- [ ] Verify critical workflows
- [ ] Check queue processing
- [ ] Monitor performance metrics
- [ ] User feedback collection
- [ ] Incident response ready

---

## Post-Migration Optimization

### Performance Optimization

#### 1. **Database Query Optimization**
```php
// Eager loading to prevent N+1
$employees = Employee::with([
    'department',
    'designation',
    'shift',
    'currentAppraisal'
])->get();

// Database indexes
Schema::table('attendance', function (Blueprint $table) {
    $table->index(['employee_id', 'date']);
    $table->index(['company_id', 'date']);
});

// Query scopes for reusability
class Employee extends Model {
    public function scopeActive($query) {
        return $query->where('status', 'active');
    }
}
```

#### 2. **Caching Strategy**
```php
// Cache employee list
$employees = Cache::remember('employees.active', 3600, function() {
    return Employee::active()->get();
});

// Cache attendance calculations
$attendance = Cache::tags(['attendance', "employee:{$id}"])
    ->remember("attendance:{$id}:{$month}", 3600, function() {
        return $this->calculateAttendance($id, $month);
    });
```

#### 3. **Queue Optimization**
```php
// Batch processing
Bus::batch([
    new ProcessAttendanceBatch($employeesBatch1),
    new ProcessAttendanceBatch($employeesBatch2),
    new ProcessAttendanceBatch($employeesBatch3),
])->then(function (Batch $batch) {
    // All batches completed
})->dispatch();
```

#### 4. **API Response Caching**
```php
// Use Laravel ResponseCache
Route::get('/api/employees', [EmployeeController::class, 'index'])
    ->middleware('cacheResponse:3600');
```

### Code Quality Tools

```bash
# Install dev dependencies
composer require --dev laravel/pint phpstan/phpstan larastan/larastan

# Code formatting (Laravel Pint)
./vendor/bin/pint

# Static analysis (PHPStan)
./vendor/bin/phpstan analyse

# Testing with coverage
php artisan test --coverage --min=80
```

### Monitoring & Logging

```php
// Application monitoring
- Laravel Telescope (dev)
- Sentry (production errors)
- New Relic (APM)
- Laravel Horizon (queue monitoring)

// Custom metrics
Metric::track('attendance.processed', $count);
Metric::track('leaves.approved.today', $approved);
```

---

## Migration Checklist

### Pre-Migration
- [ ] Stakeholder approval obtained
- [ ] Budget allocated
- [ ] Team trained on Laravel
- [ ] Development environment setup
- [ ] CI/CD pipeline configured

### During Migration
- [ ] Weekly progress reports
- [ ] Bi-weekly demos to stakeholders
- [ ] Code review process enforced
- [ ] Documentation updated
- [ ] Test coverage maintained

### Post-Migration
- [ ] User training completed
- [ ] Documentation delivered
- [ ] Support team briefed
- [ ] Performance baselines established
- [ ] Monitoring alerts configured

---

## Success Metrics

| Metric | Current (CI4) | Target (Laravel) |
|--------|--------------|------------------|
| Page Load Time | 2-3s | <1s |
| API Response Time | 500ms | <200ms |
| Attendance Processing | 30s/employee | <10s/employee |
| Test Coverage | <20% | >80% |
| Code Maintainability | Low | High |
| Deployment Time | Manual (hours) | Automated (<5min) |
| Bug Resolution Time | Days | Hours |

---

## Conclusion

This migration from CodeIgniter 4 to Laravel represents a significant investment in the future of the Health Genie HRM system. The proposed architecture leverages Laravel's modern features while maintaining all existing functionality and improving code quality, performance, and maintainability.

**Key Takeaways:**
1. ✅ Use Domain-Driven Design for better organization
2. ✅ Implement Repository pattern for data access
3. ✅ Extract business logic into Services
4. ✅ Use Events for decoupling
5. ✅ Queue long-running tasks
6. ✅ Comprehensive testing strategy
7. ✅ Gradual migration approach
8. ✅ Strong focus on performance

**Next Steps:**
1. Get stakeholder approval
2. Assemble migration team
3. Setup development environment
4. Begin Phase 1 (Foundation)

---

**Document Prepared By:** Claude AI
**Date:** December 27, 2025
**Version:** 1.0
**Status:** Ready for Review
