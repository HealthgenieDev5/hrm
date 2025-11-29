# Attendance API Implementation Guide

This directory contains all the files needed to implement the Laravel Attendance API as per the `attendance_api_integration_plan.md`.

## Directory Structure

```
attendance-api-implementation/
├── README.md (this file)
├── INSTALLATION.md (detailed setup instructions)
├── migrations/
│   ├── create_employees_table.php
│   ├── create_shifts_table.php
│   └── create_raw_attendance_table.php
├── models/
│   ├── Employee.php
│   ├── Shift.php
│   └── RawAttendance.php
├── services/
│   ├── ETimeOfficeService.php
│   ├── AttendanceProcessingService.php
│   └── AuthenticationService.php
├── controllers/
│   ├── AttendanceController.php
│   └── AuthController.php
├── routes/
│   └── api.php
├── config/
│   ├── .env.example
│   └── database.php
├── console/
│   └── Kernel.php (scheduled tasks)
└── hrm-integration/
    ├── AttendanceApiClient.php
    ├── SyncToApiDatabase.php
    └── .env.additions
```

## Quick Start

### Step 1: Install Laravel API

```bash
cd D:/LOCALHOST
composer create-project laravel/laravel hrm-attendance-api
cd hrm-attendance-api
```

### Step 2: Copy Files

Copy all files from this directory to your Laravel project:

```bash
# Migrations
cp migrations/*.php database/migrations/

# Models
cp models/*.php app/Models/

# Services
mkdir -p app/Services
cp services/*.php app/Services/

# Controllers
cp controllers/*.php app/Http/Controllers/

# Routes
cp routes/api.php routes/

# Console
cp console/Kernel.php app/Console/

# Config
cp config/.env.example .env
```

### Step 3: Configure Database

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrm_attendance_api
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Install JWT Authentication

```bash
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

### Step 6: Configure eTime Office

Add to `.env`:

```env
# eTime Office - Delhi
ETIME_DEL_API_URL=https://api.etimeoffice.com/api/DownloadInOutPunchData
ETIME_DEL_CORPORATE_ID=your_corporate_id
ETIME_DEL_USERNAME=your_username
ETIME_DEL_PASSWORD=your_password

# Repeat for ggn, hn, skbd
```

### Step 7: Setup Scheduler

Add to server crontab:

```bash
* * * * * cd /path/to/hrm-attendance-api && php artisan schedule:run >> /dev/null 2>&1
```

### Step 8: Start API Server

```bash
php artisan serve --port=8001
```

API will be available at: `http://localhost:8001`

## HRM Portal Integration

### Step 1: Create Services Directory

```bash
cd D:/LOCALHOST/hrm.healthgenie
mkdir -p app/Services
```

### Step 2: Copy HRM Integration Files

```bash
cp hrm-integration/AttendanceApiClient.php app/Services/
cp hrm-integration/SyncToApiDatabase.php app/Commands/
```

### Step 3: Update HRM .env

Add to `D:/LOCALHOST/hrm.healthgenie/.env`:

```env
# Attendance API Configuration
USE_ATTENDANCE_API=true
ATTENDANCE_API_URL=http://localhost:8001/api/v1
ATTENDANCE_API_KEY=your_api_key_here
ATTENDANCE_API_SECRET=your_secret_here
ATTENDANCE_API_TIMEOUT=30
ATTENDANCE_API_FALLBACK_TO_LOCAL=true

# API Database Connection
database.api.hostname=localhost
database.api.database=hrm_attendance_api
database.api.username=root
database.api.password=your_password
database.api.DBDriver=MySQLi
```

### Step 4: Setup HRM Sync Cron

Add to crontab:

```bash
*/5 * * * * cd /path/to/hrm.healthgenie && php spark sync:api-database >> /var/log/hrm-api-sync.log 2>&1
```

## Testing

### Test API Health

```bash
curl http://localhost:8001/api/v1/health
```

### Test Authentication

```bash
curl -X POST http://localhost:8001/api/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{"api_key":"your_key","secret":"your_secret"}'
```

### Test Attendance Processing

```bash
curl -X POST http://localhost:8001/api/v1/attendance/process/single \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"employee_id":123,"shift_id":5,"date":"2025-11-10"}'
```

## Architecture

The implementation follows the plan with:

1. **Separate Laravel API** - Handles all attendance calculations
2. **eTime Office Integration** - API fetches punch data directly
3. **HRM Sync** - Employees and shifts synced every 5 minutes
4. **Unified Endpoint** - Single endpoint handles both regular and reduce shifts
5. **JWT Authentication** - Secure API access
6. **Fallback Support** - HRM falls back to local processing if API is down

## Next Steps

1. Review `INSTALLATION.md` for detailed setup
2. Check individual file headers for documentation
3. Test with sample data before production
4. Monitor logs during initial rollout
5. Set up alerting for API failures

## Support Files

Each file in this package includes:
- Detailed inline documentation
- Type hints and return types
- Error handling
- Logging support
- PHPDoc comments

## Migration Path

Follow the 6-week rollout plan in the original documentation:
- Week 1-2: API Development
- Week 3: HRM Integration
- Week 4: Parallel Testing
- Week 5: Gradual Rollout
- Week 6: Full Migration
