# Complete Installation Guide

## Attendance API Integration Implementation

This guide provides step-by-step instructions to implement the Laravel-based Attendance API as per `attendance_api_integration_plan.md`.

---

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Part A: Laravel API Setup](#part-a-laravel-api-setup)
3. [Part B: HRM Portal Integration](#part-b-hrm-portal-integration)
4. [Part C: Configuration](#part-c-configuration)
5. [Part D: Testing](#part-d-testing)
6. [Part E: Deployment](#part-e-deployment)
7. [Troubleshooting](#troubleshooting)

---

## Prerequisites

- PHP 8.1 or higher
- Composer 2.x
- MySQL 5.7 or higher
- Git
- Access to eTime Office API credentials (4 locations)
- Server with cron support

---

## Part A: Laravel API Setup

### Step 1: Install Laravel

```bash
cd D:/LOCALHOST
composer create-project laravel/laravel hrm-attendance-api
cd hrm-attendance-api
```

### Step 2: Copy Implementation Files

```bash
# From the attendance-api-implementation directory

# Copy Migrations
cp create_employees_table.php database/migrations/2025_01_01_000001_create_employees_table.php
cp create_shifts_table.php database/migrations/2025_01_01_000002_create_shifts_table.php
cp create_raw_attendance_table.php database/migrations/2025_01_01_000003_create_raw_attendance_table.php

# Copy Models
cp models_Employee.php app/Models/Employee.php
cp models_Shift.php app/Models/Shift.php
cp models_RawAttendance.php app/Models/RawAttendance.php

# Copy Services
mkdir -p app/Services
cp ETimeOfficeService.php app/Services/
cp AttendanceProcessingService.php app/Services/

# Copy Controllers
cp AttendanceController.php app/Http/Controllers/

# Copy Routes
# Merge api_routes.php content into routes/api.php

# Copy Scheduler
# Merge Kernel_scheduler.php content into app/Console/Kernel.php
```

### Step 3: Install JWT Authentication

```bash
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

### Step 4: Configure Database

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrm_attendance_api
DB_USERNAME=root
DB_PASSWORD=mysql
```

Create database:

```bash
mysql -u root -p
CREATE DATABASE hrm_attendance_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

### Step 6: Configure eTime Office

Edit `.env` and add eTime Office credentials:

```env
# Use the values from env_Laravel_API.txt
ETIME_DEL_API_URL=...
ETIME_DEL_CORPORATE_ID=...
# ... etc for all 4 locations
```

### Step 7: Setup Task Scheduler

Add to server crontab:

```bash
* * * * * cd /path/to/hrm-attendance-api && php artisan schedule:run >> /dev/null 2>&1
```

For Windows development (using Task Scheduler):

```powershell
# Create a batch file: scheduler.bat
cd D:\LOCALHOST\hrm-attendance-api
php artisan schedule:run

# Schedule it to run every minute via Task Scheduler
```

### Step 8: Start API Server

```bash
php artisan serve --host=0.0.0.0 --port=8001
```

API will be available at: `http://localhost:8001`

### Step 9: Test API Health

```bash
curl http://localhost:8001/api/v1/health
```

Expected response:

```json
{
  "status": "healthy",
  "version": "1.0.0",
  "uptime": 1234,
  "database": "connected",
  "timestamp": "2025-11-10 12:00:00"
}
```

---

## Part B: HRM Portal Integration

### Step 1: Files are Already Created

The HRM integration files have been created in your HRM project:

- `app/Commands/SyncToApiDatabase.php`
- `app/Services/AttendanceApiClient.php`

### Step 2: Update HRM Database Configuration

Edit `app/Config/Database.php` and add this array after the existing `$default` array:

```php
/**
 * API Database Connection
 * Used for syncing employee and shift data to the Attendance API database
 */
public array $api_database = [
    'DSN'          => '',
    'hostname'     => 'localhost',
    'username'     => 'root',
    'password'     => 'mysql',
    'database'     => 'hrm_attendance_api',
    'DBDriver'     => 'MySQLi',
    'DBPrefix'     => '',
    'pConnect'     => false,
    'DBDebug'      => true,
    'charset'      => 'utf8',
    'DBCollat'     => 'utf8_general_ci',
    'swapPre'      => '',
    'encrypt'      => false,
    'compress'     => false,
    'strictOn'     => false,
    'failover'     => [],
    'port'         => 3306,
    'numberNative' => false,
    'foundRows'    => false,
];
```

### Step 3: Update HRM .env

Add the content from `env_additions_HRM.txt` to your HRM `.env` file:

```bash
# Copy and paste from env_additions_HRM.txt
```

### Step 4: Test HRM Sync Command

```bash
cd D:/LOCALHOST/hrm.healthgenie
php spark sync:api-database
```

Expected output:

```
Starting sync to API database...
Database connections established
Syncing employees...
50 employees synced successfully
Syncing shifts...
10 shifts synced successfully
═══════════════════════════════════════
Sync completed successfully!
├─ Employees synced: 50
└─ Shifts synced: 10
═══════════════════════════════════════
```

### Step 5: Setup HRM Sync Cron

Add to crontab:

```bash
*/5 * * * * cd /path/to/hrm.healthgenie && php spark sync:api-database >> /var/log/hrm-sync.log 2>&1
```

For Windows (Task Scheduler):

```batch
REM Create sync-to-api.bat
cd D:\LOCALHOST\hrm.healthgenie
php spark sync:api-database
```

Schedule to run every 5 minutes.

---

## Part C: Configuration

### API Keys Setup

1. Generate secure API keys:

```bash
# On Laravel API server
php artisan tinker
>>> Str::random(32)
# Copy the output as API_KEY

>>> Str::random(32)
# Copy the output as API_SECRET
```

2. Add to Laravel API `.env`:

```env
API_KEY_1=<generated_key>
API_SECRET_1=<generated_secret>
```

3. Add to HRM `.env`:

```env
ATTENDANCE_API_KEY=<same_generated_key>
ATTENDANCE_API_SECRET=<same_generated_secret>
```

### eTime Office Credentials

Copy your existing eTime Office credentials from HRM `.env` to Laravel API `.env`:

```env
# In Laravel API .env
ETIME_DEL_CORPORATE_ID=<from_HRM_env>
ETIME_DEL_USERNAME=<from_HRM_env>
ETIME_DEL_PASSWORD=<from_HRM_env>
# Repeat for ggn, hn, skbd
```

---

## Part D: Testing

### Test 1: API Health Check

```bash
curl http://localhost:8001/api/v1/health
```

### Test 2: API Authentication

```bash
curl -X POST http://localhost:8001/api/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{
    "api_key": "your_api_key",
    "secret": "your_secret"
  }'
```

Expected response:

```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "expires_in": 3600
}
```

### Test 3: Attendance Processing

```bash
# Get token first
TOKEN=$(curl -s -X POST http://localhost:8001/api/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{"api_key":"your_key","secret":"your_secret"}' \
  | jq -r '.token')

# Process attendance
curl -X POST http://localhost:8001/api/v1/attendance/process/single \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "employee_id": 123,
    "shift_id": 5,
    "date": "2025-11-10"
  }'
```

### Test 4: HRM Integration

1. Enable API in HRM `.env`:

```env
USE_ATTENDANCE_API=true
```

2. Test via HRM portal by processing attendance for an employee

3. Check logs:

```bash
tail -f D:/LOCALHOST/hrm.healthgenie/writable/logs/log-*.log
tail -f D:/LOCALHOST/hrm-attendance-api/storage/logs/laravel.log
```

---

## Part E: Deployment

### Production Checklist

#### Laravel API

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate new `APP_KEY`: `php artisan key:generate`
- [ ] Generate new `JWT_SECRET`: `php artisan jwt:secret`
- [ ] Configure proper database credentials
- [ ] Setup SSL/TLS (HTTPS)
- [ ] Configure firewall to allow only HRM server
- [ ] Setup process manager (Supervisor) for `artisan serve`
- [ ] Configure log rotation
- [ ] Setup backup for API database
- [ ] Test cron jobs are running
- [ ] Setup monitoring (uptimerobot, etc.)

#### HRM Portal

- [ ] Update `ATTENDANCE_API_URL` to production URL
- [ ] Enable `ATTENDANCE_API_FALLBACK_TO_LOCAL=true`
- [ ] Test API connectivity from HRM server
- [ ] Test fallback to local processing
- [ ] Verify cron job is running
- [ ] Monitor initial rollout

### Gradual Rollout Plan

**Week 1-2: API Development** ✓ (Complete)

**Week 3: Integration Testing**

- Test with 10 employees
- Monitor for errors
- Verify data accuracy

**Week 4: Parallel Testing**

- Run both API and local calculations
- Compare results
- Log discrepancies

**Week 5: Gradual Rollout**

- Enable for 25% of employees
- Monitor performance
- Increase to 50%, then 75%

**Week 6: Full Migration**

- Enable for 100% of employees
- Keep fallback enabled
- Monitor closely

---

## Troubleshooting

### Issue: API Authentication Fails

**Solution:**

- Verify API_KEY and API_SECRET match in both `.env` files
- Check JWT_SECRET is generated in Laravel API
- View logs: `tail -f storage/logs/laravel.log`

### Issue: Database Connection Fails

**Solution:**

- Test MySQL connection: `mysql -h localhost -u root -p`
- Verify database exists: `SHOW DATABASES;`
- Check credentials in `.env`

### Issue: eTime Office Sync Not Working

**Solution:**

- Check eTime credentials are correct
- Test API manually with Postman
- View logs: `tail -f storage/logs/laravel.log | grep eTime`
- Verify cron is running: `crontab -l`

### Issue: HRM Sync Command Fails

**Solution:**

- Check `api_database` configuration in `Database.php`
- Verify API database is accessible from HRM server
- Test connection: `php spark db:table employees --database api_database`

### Issue: Attendance Calculation Wrong

**Solution:**

- Check shift configuration has correct `reduction_percentage`
- Verify shift_type is set correctly ('regular' or 'reduce')
- Check `effective_from_date` if reduction not applying
- View API response in HRM logs

---

## Support and Next Steps

### Documentation

- See `README.md` for overview
- See `attendance_api_integration_plan.md` for architecture details
- See inline code comments for implementation details

### Monitoring

- Setup API uptime monitoring
- Monitor error rates
- Track API response times
- Alert on fallback usage

### Optimization

After successful deployment:

- Add Redis caching for frequently accessed data
- Implement request rate limiting
- Setup API load balancing (if needed)
- Optimize database indexes

---

## Summary

You now have:

1. ✅ Laravel Attendance API with eTime Office integration
2. ✅ Database migrations and models
3. ✅ Attendance processing service with reduction logic
4. ✅ HRM sync command for employee/shift data
5. ✅ HRM API client with fallback support
6. ✅ Scheduled tasks for automated syncing
7. ✅ Complete configuration files
8. ✅ Testing procedures
9. ✅ Deployment checklist

**Next Action:** Follow the installation steps above to deploy!
