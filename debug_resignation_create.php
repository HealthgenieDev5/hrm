<?php
/**
 * Debug script to check resignation creation data
 */

// Read database configuration from .env
$envFile = __DIR__ . '/.env';
$envContent = file_get_contents($envFile);

preg_match('/database\.default\.hostname\s*=\s*(.+)/', $envContent, $hostMatch);
preg_match('/database\.default\.database\s*=\s*(.+)/', $envContent, $dbMatch);
preg_match('/database\.default\.username\s*=\s*(.+)/', $envContent, $userMatch);
preg_match('/database\.default\.password\s*=\s*(.+)/', $envContent, $passMatch);

$host = trim($hostMatch[1] ?? 'localhost');
$database = trim($dbMatch[1] ?? '');
$username = trim($userMatch[1] ?? 'root');
$password = trim($passMatch[1] ?? '');

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== DEBUGGING RESIGNATION CREATION ISSUE ===\n\n";

// Check recent resignations with HOD response records
echo "Recent Resignations with HOD Response Records:\n";
echo str_repeat('-', 120) . "\n";

$query = "
    SELECT
        r.id as resignation_id,
        r.employee_id,
        CONCAT(emp.first_name, ' ', emp.last_name) as employee_name,
        emp.reporting_manager_id as actual_reporting_manager_id,
        CONCAT(rm.first_name, ' ', rm.last_name) as actual_reporting_manager_name,
        rhr.id as response_id,
        rhr.hod_id as recorded_hod_id,
        CONCAT(hod.first_name, ' ', hod.last_name) as recorded_hod_name,
        rhr.manager_id,
        r.resignation_date,
        r.created_at
    FROM resignations r
    LEFT JOIN employees emp ON emp.id = r.employee_id
    LEFT JOIN employees rm ON rm.id = emp.reporting_manager_id
    LEFT JOIN resignation_hod_response rhr ON rhr.resignation_id = r.id
    LEFT JOIN employees hod ON hod.id = rhr.hod_id
    WHERE r.status = 'active'
    ORDER BY r.id DESC
    LIMIT 10
";

$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    printf("%-6s %-10s %-25s %-6s %-25s %-10s %-6s %-25s %-12s\n",
        "Res ID", "Emp ID", "Employee", "RM ID", "Actual RM", "Resp ID", "HOD ID", "Recorded HOD", "Mgr ID");
    echo str_repeat('-', 120) . "\n";

    while ($row = $result->fetch_assoc()) {
        $mismatch = '';
        if ($row['actual_reporting_manager_id'] != $row['recorded_hod_id'] && !is_null($row['recorded_hod_id'])) {
            $mismatch = " ⚠️ MISMATCH!";
        }

        printf("%-6s %-10s %-25s %-6s %-25s %-10s %-6s %-25s %-12s%s\n",
            $row['resignation_id'],
            $row['employee_id'],
            substr($row['employee_name'], 0, 25),
            $row['actual_reporting_manager_id'] ?? 'NULL',
            substr($row['actual_reporting_manager_name'] ?? 'NULL', 0, 25),
            $row['response_id'] ?? 'NULL',
            $row['recorded_hod_id'] ?? 'NULL',
            substr($row['recorded_hod_name'] ?? 'NULL', 0, 25),
            $row['manager_id'] ?? 'NULL',
            $mismatch
        );
    }
} else {
    echo "No resignations found.\n";
}

echo "\n\n";

// Check if there are employees with missing reporting_manager_id
echo "Check Employee Data (sample employees):\n";
echo str_repeat('-', 100) . "\n";

$query = "
    SELECT
        e.id,
        CONCAT(e.first_name, ' ', e.last_name) as employee_name,
        e.reporting_manager_id,
        CONCAT(rm.first_name, ' ', rm.last_name) as reporting_manager_name
    FROM employees e
    LEFT JOIN employees rm ON rm.id = e.reporting_manager_id
    WHERE e.status = 'active'
    ORDER BY e.id DESC
    LIMIT 10
";

$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    printf("%-10s %-40s %-10s %-40s\n", "Emp ID", "Employee Name", "RM ID", "Reporting Manager");
    echo str_repeat('-', 100) . "\n";

    while ($row = $result->fetch_assoc()) {
        printf("%-10s %-40s %-10s %-40s\n",
            $row['id'],
            $row['employee_name'],
            $row['reporting_manager_id'] ?? 'NULL',
            $row['reporting_manager_name'] ?? 'NULL'
        );
    }
}

echo "\n\n";

// Check the most recent resignation_hod_response record
echo "Most Recent HOD Response Record Details:\n";
echo str_repeat('-', 100) . "\n";

$query = "
    SELECT
        rhr.*,
        CONCAT(emp.first_name, ' ', emp.last_name) as employee_name,
        emp.reporting_manager_id as employee_actual_rm_id,
        CONCAT(hod.first_name, ' ', hod.last_name) as hod_name,
        CONCAT(mgr.first_name, ' ', mgr.last_name) as manager_name
    FROM resignation_hod_response rhr
    LEFT JOIN employees emp ON emp.id = rhr.employee_id
    LEFT JOIN employees hod ON hod.id = rhr.hod_id
    LEFT JOIN employees mgr ON mgr.id = rhr.manager_id
    ORDER BY rhr.id DESC
    LIMIT 1
";

$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    echo "Record ID: " . $row['id'] . "\n";
    echo "Resignation ID: " . $row['resignation_id'] . "\n";
    echo "Employee ID: " . $row['employee_id'] . " (" . $row['employee_name'] . ")\n";
    echo "Employee's Actual Reporting Manager ID: " . ($row['employee_actual_rm_id'] ?? 'NULL') . "\n";
    echo "Recorded HOD ID: " . $row['hod_id'] . " (" . ($row['hod_name'] ?? 'NULL') . ")\n";
    echo "Manager ID: " . ($row['manager_id'] ?? 'NULL') . " (" . ($row['manager_name'] ?? 'NULL') . ")\n";
    echo "HOD Response: " . $row['hod_response'] . "\n";
    echo "Created At: " . $row['created_at'] . "\n";

    if ($row['employee_actual_rm_id'] != $row['hod_id']) {
        echo "\n⚠️  PROBLEM DETECTED!\n";
        echo "The recorded HOD ID (" . $row['hod_id'] . ") does NOT match\n";
        echo "the employee's actual reporting_manager_id (" . ($row['employee_actual_rm_id'] ?? 'NULL') . ")\n";
    } else {
        echo "\n✓ HOD ID is correct!\n";
    }

    if (is_null($row['manager_id'])) {
        echo "\n⚠️  Manager ID is NULL (this is normal initially - it gets set after HOD responds)\n";
    }
} else {
    echo "No HOD response records found.\n";
}

echo "\n\n=== END DEBUG ===\n";

$mysqli->close();
