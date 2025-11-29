# Quick Start Guide: Sub-Shift Types API

## 🚀 5-Minute Setup

### Step 1: Run Migrations (1 min)
```bash
cd /path/to/hrm.healthgenie
php spark migrate
```

### Step 2: Create API Key (1 min)
```bash
# Generate key
openssl rand -hex 32

# Save output, then insert into database
```

```sql
INSERT INTO api_keys (api_key, name, permissions, is_active, created_at)
VALUES ('YOUR_KEY_HERE', 'Test API', '["*"]', 'yes', NOW());
```

### Step 3: Configure a Reduce Shift (1 min)
```sql
UPDATE shifts
SET shift_type = 'reduce',
    reduction_percentage = 33.33,
    reduction_remarks = '12h counted as 8h'
WHERE shift_code = 'NS';  -- Replace with your shift code
```

### Step 4: Test the API (2 min)
```bash
# Set your API key
export API_KEY="your_key_here"
export BASE_URL="http://localhost:8080/api/v1"

# Test: List all shifts
curl -H "X-API-Key: $API_KEY" "$BASE_URL/shifts"

# Test: Get reduce shifts
curl -H "X-API-Key: $API_KEY" "$BASE_URL/shifts/reduce"

# Test: Get employee shift (replace 123)
curl -H "X-API-Key: $API_KEY" "$BASE_URL/employees/123/shift"
```

### Step 5: Process Attendance
```bash
# Process current month
php spark attendance:process --month=2025-01
```

---

## 📊 Verify Reduction is Working

```sql
-- Check if reduction applied
SELECT
    employee_id,
    date,
    shift_reduction_applied,
    shift_reduction_original_minutes / 60 as original_hours,
    work_hours as reduced_hours,
    reduction_percentage
FROM pre_final_paid_days
WHERE shift_reduction_applied = 'yes'
LIMIT 5;
```

Expected output:
```
employee_id | date       | reduction | original_hours | reduced_hours | reduction_%
123         | 2025-01-15 | yes       | 12.00          | 08:00         | 33.33
```

---

## 🎯 Common Use Cases

### Use Case 1: Get All Reduce Shifts
```bash
curl -H "X-API-Key: $API_KEY" \
     "$BASE_URL/shifts/reduce"
```

### Use Case 2: Check Employee's Shift Type
```bash
curl -H "X-API-Key: $API_KEY" \
     "$BASE_URL/employees/123/shift" | jq '.data.shift.shift_type'
```

### Use Case 3: Get Monthly Attendance with Reduction
```bash
curl -H "X-API-Key: $API_KEY" \
     "$BASE_URL/attendance/123?from_date=2025-01-01&to_date=2025-01-31"
```

### Use Case 4: Get Quick Summary
```bash
curl -H "X-API-Key: $API_KEY" \
     "$BASE_URL/attendance/summary/123?from_date=2025-01-01&to_date=2025-01-31"
```

---

## 🔧 Troubleshooting

### Problem: 401 Unauthorized
**Solution**: Check API key header
```bash
# Make sure header is correct
curl -v -H "X-API-Key: your_key" "$BASE_URL/shifts"
# Look for: X-API-Key: your_key in request headers
```

### Problem: Reduction not applied
**Solution**: Verify shift configuration
```sql
SELECT shift_code, shift_type, reduction_percentage
FROM shifts
WHERE id = YOUR_SHIFT_ID;
```

### Problem: API returns empty data
**Solution**: Process attendance first
```bash
php spark attendance:process --month=2025-01
```

---

## 📚 Documentation Links

- **Full API Reference**: [SUB_SHIFT_API_DOCUMENTATION.md](SUB_SHIFT_API_DOCUMENTATION.md)
- **Implementation Details**: [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md)
- **Attendance Processing Logic**: [ATTENDANCE_PROCESSING_LOGIC.md](ATTENDANCE_PROCESSING_LOGIC.md)

---

## 💡 Quick Tips

1. **Default Shift Type**: All existing shifts are 'regular' by default (no reduction)
2. **Only Show Reduced**: API only shows reduced hours (not original)
3. **Zero Portal Impact**: HRM portal continues to work unchanged
4. **Re-process Needed**: Existing attendance must be re-processed to apply reduction
5. **Batch Processing**: Use `--employee=40,41,42` to process multiple employees

---

## 🎓 Example Scenarios

### Scenario: 12-hour Night Shift → 8-hour Equivalent

```sql
-- Configure shift
UPDATE shifts
SET shift_type = 'reduce',
    reduction_percentage = 33.33
WHERE shift_name = 'Night Shift';

-- Assign employees
UPDATE employees
SET shift_id = (SELECT id FROM shifts WHERE shift_name = 'Night Shift')
WHERE department_id = 3;  -- Production department

-- Process attendance
php spark attendance:process --month=2025-01

-- Verify
SELECT
    e.emp_code,
    COUNT(*) as days,
    SUM(pfd.paid) as total_paid_days,
    SUM(pfd.shift_reduction_original_minutes) / 60 as original_hours,
    SUM(TIME_TO_SEC(pfd.work_hours)) / 3600 as reduced_hours
FROM employees e
JOIN pre_final_paid_days pfd ON e.id = pfd.employee_id
WHERE e.shift_id = (SELECT id FROM shifts WHERE shift_name = 'Night Shift')
  AND pfd.date BETWEEN '2025-01-01' AND '2025-01-31'
GROUP BY e.emp_code;
```

---

## 🚦 Health Check

Run this to verify everything is working:

```bash
#!/bin/bash

echo "=== Sub-Shift API Health Check ==="

# 1. Check migrations
echo "1. Checking migrations..."
php spark migrate:status | grep "2025-01-07"

# 2. Check API key
echo "2. Checking API key..."
mysql -u root -p -e "SELECT name, is_active FROM api_keys LIMIT 1;"

# 3. Check reduce shifts
echo "3. Checking reduce shifts..."
mysql -u root -p -e "SELECT shift_code, shift_type, reduction_percentage FROM shifts WHERE shift_type='reduce';"

# 4. Test API
echo "4. Testing API..."
curl -s -H "X-API-Key: $API_KEY" "$BASE_URL/shifts/reduce" | jq '.status'

echo "=== Health Check Complete ==="
```

Expected output:
```
1. Checking migrations... ✓ Found 3 migrations
2. Checking API key... ✓ Found 1 active key
3. Checking reduce shifts... ✓ Found 1 reduce shift
4. Testing API... ✓ "success"
```

---

## 🎉 You're Ready!

If all health checks pass, your sub-shift API is ready to use!

**Next Steps**:
1. Integrate API with your payroll system
2. Set up automated attendance processing (cron job)
3. Monitor API usage and performance
4. Add more reduce shifts as needed

---

**Need Help?** Check the full documentation or run the troubleshooting queries above.
