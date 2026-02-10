-- Fix existing resignations by creating HOD response records
-- This creates response records for resignations that were added before the workflow was implemented

INSERT INTO resignation_hod_response (resignation_id, employee_id, hod_id, hod_response, created_at, updated_at)
SELECT
    r.id as resignation_id,
    r.employee_id,
    e.reporting_manager_id as hod_id,
    'pending' as hod_response,
    NOW() as created_at,
    NOW() as updated_at
FROM resignations r
LEFT JOIN employees e ON e.id = r.employee_id
LEFT JOIN resignation_hod_response rhr ON rhr.resignation_id = r.id
WHERE r.status = 'active'
AND e.reporting_manager_id IS NOT NULL
AND e.reporting_manager_id > 0
AND rhr.id IS NULL;  -- Only insert if record doesn't already exist

-- View the results
SELECT
    rhr.id,
    r.resignation_date,
    CONCAT(e.first_name, ' ', e.last_name) as employee_name,
    CONCAT(hod.first_name, ' ', hod.last_name) as hod_name,
    rhr.hod_response
FROM resignation_hod_response rhr
LEFT JOIN resignations r ON r.id = rhr.resignation_id
LEFT JOIN employees e ON e.id = rhr.employee_id
LEFT JOIN employees hod ON hod.id = rhr.hod_id
ORDER BY rhr.id DESC;
