# Quick Reference Card

## Attendance API Implementation - Command Reference

---

## Installation Commands

### Laravel API Setup

```bash
# 1. Create Laravel project
cd D:/LOCALHOST
composer create-project laravel/laravel hrm-attendance-api
cd hrm-attendance-api

# 2. Install JWT
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret

# 3. Create database
mysql -u root -p
CREATE DATABASE hrm_attendance_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# 4. Run migrations
php artisan migrate

# 5. Generate keys
php artisan key:generate
php artisan tinker
>>> Str::random(32)  # For API_KEY
>>> Str::random(32)  # For API_SECRET

# 6. Start server
php artisan serve --host=0.0.0.0 --port=8001
```

### HRM Setup

```bash
# 1. Test sync command
cd D:/LOCALHOST/hrm.healthgenie
php spark sync:api-database

# 2. Test API client
php spark test-api-connection  # If you create this command
```

---

## Configuration Files

### Laravel API .env

```env
DB_DATABASE=hrm_attendance_api
DB_USERNAME=root
DB_PASSWORD=mysql

ETIME_DEL_CORPORATE_ID=your_value
ETIME_GGN_CORPORATE_ID=your_value
ETIME_HN_CORPORATE_ID=your_value
ETIME_SKBD_CORPORATE_ID=your_value

API_KEY_1=your_32_char_key
API_SECRET_1=your_32_char_secret
```

### HRM .env

```env
USE_ATTENDANCE_API=true
ATTENDANCE_API_URL=http://localhost:8001/api/v1
ATTENDANCE_API_KEY=same_32_char_key
ATTENDANCE_API_SECRET=same_32_char_secret
ATTENDANCE_API_FALLBACK_TO_LOCAL=true

database.api_database.hostname=localhost
database.api_database.database=hrm_attendance_api
database.api_database.username=root
database.api_database.password=mysql
```

---

## Cron Jobs

### Laravel API (every minute)

```bash
* * * * * cd /path/to/hrm-attendance-api && php artisan schedule:run >> /dev/null 2>&1
```

**Windows Task Scheduler:**
- Program: `php`
- Arguments: `artisan schedule:run`
- Start in: `D:\LOCALHOST\hrm-attendance-api`
- Trigger: Every 1 minute

### HRM Sync (every 5 minutes)

```bash
*/5 * * * * cd /path/to/hrm.healthgenie && php spark sync:api-database >> /var/log/hrm-sync.log 2>&1
```

**Windows Task Scheduler:**
- Program: `php`
- Arguments: `spark sync:api-database`
- Start in: `D:\LOCALHOST\hrm.healthgenie`
- Trigger: Every 5 minutes

---

## API Endpoints

### Health Check (Public)

```bash
curl http://localhost:8001/api/v1/health
```

### Get Token

```bash
curl -X POST http://localhost:8001/api/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{
    "api_key": "your_key",
    "secret": "your_secret"
  }'
```

### Process Single Day

```bash
curl -X POST http://localhost:8001/api/v1/attendance/process/single \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "employee_id": 123,
    "shift_id": 5,
    "date": "2025-11-10"
  }'
```

### Process Bulk

```bash
curl -X POST http://localhost:8001/api/v1/attendance/process/bulk \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "employee_id": 123,
    "date_from": "2025-10-01",
    "date_to": "2025-10-31"
  }'
```

---

## Testing Commands

### Test Database Connection

```bash
# Laravel API
php artisan tinker
>>> DB::connection()->getPdo()
>>> DB::table('employees')->count()

# HRM
php spark db:table employees --database api_database
```

### Test eTime Office Sync

```bash
# Laravel API
php artisan tinker
>>> $service = app(\App\Services\ETimeOfficeService::class);
>>> $service->syncToday()
```

### Test HRM Sync

```bash
# HRM
php spark sync:api-database
```

### View Logs

```bash
# Laravel API
tail -f storage/logs/laravel.log

# HRM
tail -f writable/logs/log-2025-11-10.log

# Filter for API calls
tail -f writable/logs/log-*.log | grep "API"
```

---

## File Copying Checklist

### Laravel API Files

- [ ] Copy migrations to `database/migrations/`
- [ ] Copy models to `app/Models/`
- [ ] Copy services to `app/Services/`
- [ ] Copy controller to `app/Http/Controllers/`
- [ ] Merge routes into `routes/api.php`
- [ ] Merge scheduler into `app/Console/Kernel.php`
- [ ] Configure `.env` from template

### HRM Files

- [ ] `app/Commands/SyncToApiDatabase.php` ✓ Already created
- [ ] `app/Services/AttendanceApiClient.php` ✓ Already created
- [ ] Update `app/Config/Database.php` (add api_database)
- [ ] Update `.env` (add API configuration)

---

## Troubleshooting Quick Fixes

### API Not Responding

```bash
# Check if API is running
curl http://localhost:8001/api/v1/health

# Check PHP processes
ps aux | grep artisan

# Restart API
cd /path/to/hrm-attendance-api
php artisan serve --port=8001 &
```

### Database Connection Failed

```bash
# Test MySQL connection
mysql -h localhost -u root -p

# Check Laravel database
php artisan tinker
>>> DB::connection()->getPdo()

# Check HRM connection to API database
php spark db:table employees --database api_database
```

### Sync Not Working

```bash
# Manual sync
cd /path/to/hrm.healthgenie
php spark sync:api-database

# Check if cron is running
crontab -l

# Check sync logs
tail -f /var/log/hrm-sync.log
```

### Authentication Failed

```bash
# Verify API keys match in both .env files
# Laravel API .env:
cat .env | grep API_KEY

# HRM .env:
cat .env | grep ATTENDANCE_API_KEY

# Test authentication
curl -X POST http://localhost:8001/api/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{"api_key":"YOUR_KEY","secret":"YOUR_SECRET"}'
```

---

## Production Deployment Steps

1. **Set Production Mode**
   ```bash
   # Laravel API .env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize Laravel**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Setup Supervisor (Linux)**
   ```ini
   [program:laravel-api]
   command=php /path/to/hrm-attendance-api/artisan serve --port=8001
   autostart=true
   autorestart=true
   ```

4. **Configure Nginx/Apache**
   - Point to `public/` directory
   - Enable SSL/TLS
   - Configure firewall

5. **Enable Monitoring**
   - UptimeRobot for API health
   - Log monitoring (Sentry, etc.)
   - Database backup schedule

---

## Key Endpoints Summary

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| `/health` | GET | No | Health check |
| `/auth/token` | POST | No | Get JWT token |
| `/attendance/process/single` | POST | Yes | Process one day |
| `/attendance/process/bulk` | POST | Yes | Process date range |

---

## Database Tables

### Laravel API Database: `hrm_attendance_api`

- `employees` - Employee master (synced from HRM)
- `shifts` - Shift configurations (synced from HRM)
- `raw_attendance` - Punch data (from eTime Office)

### HRM Database

- All existing tables
- No changes to existing schema
- Add `api_database` connection config only

---

## Important Notes

⚠️ **Never commit:**
- `.env` files with real credentials
- API keys or secrets
- Database passwords

✅ **Always:**
- Use environment variables
- Enable fallback in production
- Monitor API health
- Keep logs rotating
- Backup both databases

📝 **Remember:**
- API runs on port 8001
- HRM runs on port 8080 (or your configured port)
- Sync runs every 5 minutes (HRM → API)
- eTime sync runs every 10 minutes (eTime → API)

---

## Quick Start (TL;DR)

```bash
# 1. Install Laravel API
composer create-project laravel/laravel hrm-attendance-api
cd hrm-attendance-api
composer require tymon/jwt-auth
php artisan jwt:secret

# 2. Copy files from attendance-api-implementation/
# 3. Configure .env
# 4. Run migrations
php artisan migrate

# 5. Start API
php artisan serve --port=8001

# 6. In HRM, update .env and test sync
cd ../hrm.healthgenie
php spark sync:api-database

# Done!
```

---

## Support Files

- `README.md` - Overview and quick start
- `INSTALLATION_GUIDE.md` - Detailed step-by-step guide
- `IMPLEMENTATION_SUMMARY.md` - Complete architecture summary
- `QUICK_REFERENCE.md` - This file

---

**Last Updated**: 2025-11-10
**Version**: 1.0.0
