<?php
/**
 * Quick Employee Lookup Tool
 * Check an employee's reporting manager before creating resignation
 * Usage: https://hrm.healthgenie.test/check_employee.php?id=385
 */

// Read database configuration
$envFile = __DIR__ . '/../.env';
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

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Lookup - Reporting Manager Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #2c3e50; }
        .search-box { margin: 20px 0; }
        input[type="number"] { padding: 10px; width: 200px; font-size: 16px; }
        button { padding: 10px 20px; font-size: 16px; background: #3498db; color: white; border: none; cursor: pointer; border-radius: 4px; }
        button:hover { background: #2980b9; }
        .result { margin-top: 20px; padding: 15px; border: 2px solid #3498db; border-radius: 5px; background: #ecf0f1; }
        .label { font-weight: bold; color: #2c3e50; }
        .value { color: #34495e; }
        .warning { background: #fff3cd; border-color: #ffc107; padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; border-color: #28a745; padding: 10px; margin: 10px 0; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Employee Reporting Manager Lookup</h1>
        <p>Use this tool to check an employee's reporting manager before creating their resignation.</p>

        <div class="search-box">
            <form method="GET">
                <input type="number" name="id" placeholder="Enter Employee ID" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>" required>
                <button type="submit">Look Up</button>
            </form>
        </div>

        <?php
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $employeeId = intval($_GET['id']);

            $query = "
                SELECT
                    e.id,
                    e.internal_employee_id,
                    CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                    e.reporting_manager_id,
                    CONCAT(rm.first_name, ' ', rm.last_name) as reporting_manager_name,
                    rm.internal_employee_id as rm_internal_id,
                    d.department_name,
                    des.designation_name,
                    e.status
                FROM employees e
                LEFT JOIN employees rm ON rm.id = e.reporting_manager_id
                LEFT JOIN departments d ON d.id = e.department_id
                LEFT JOIN designations des ON des.id = e.designation_id
                WHERE e.id = $employeeId
            ";

            $result = $mysqli->query($query);

            if ($result && $result->num_rows > 0) {
                $employee = $result->fetch_assoc();

                echo '<div class="result">';
                echo '<h2>📋 Employee Details</h2>';
                echo '<p><span class="label">Employee ID:</span> <span class="value">' . $employee['id'] . '</span></p>';
                echo '<p><span class="label">Internal ID:</span> <span class="value">' . $employee['internal_employee_id'] . '</span></p>';
                echo '<p><span class="label">Name:</span> <span class="value">' . $employee['employee_name'] . '</span></p>';
                echo '<p><span class="label">Department:</span> <span class="value">' . $employee['department_name'] . '</span></p>';
                echo '<p><span class="label">Designation:</span> <span class="value">' . $employee['designation_name'] . '</span></p>';
                echo '<p><span class="label">Status:</span> <span class="value">' . strtoupper($employee['status']) . '</span></p>';

                echo '<hr>';

                if (!empty($employee['reporting_manager_id'])) {
                    echo '<div class="success">';
                    echo '<h3>✓ Reporting Manager Found</h3>';
                    echo '<p><span class="label">Reporting Manager ID:</span> <span class="value" style="font-size: 18px; font-weight: bold;">' . $employee['reporting_manager_id'] . '</span></p>';
                    echo '<p><span class="label">Reporting Manager Name:</span> <span class="value">' . $employee['reporting_manager_name'] . '</span></p>';
                    echo '<p><span class="label">RM Internal ID:</span> <span class="value">' . $employee['rm_internal_id'] . '</span></p>';
                    echo '</div>';

                    echo '<div class="warning">';
                    echo '<h4>📌 Important Note:</h4>';
                    echo '<p>When you create a resignation for <strong>' . $employee['employee_name'] . '</strong>,<br>';
                    echo 'the system will automatically set:</p>';
                    echo '<ul>';
                    echo '<li><strong>HOD ID:</strong> ' . $employee['reporting_manager_id'] . ' (' . $employee['reporting_manager_name'] . ')</li>';
                    echo '<li><strong>Manager ID:</strong> NULL initially (will be set after HOD responds)</li>';
                    echo '</ul>';
                    echo '</div>';
                } else {
                    echo '<div class="warning">';
                    echo '<h3>⚠️ No Reporting Manager</h3>';
                    echo '<p>This employee does not have a reporting_manager_id set in the database.</p>';
                    echo '<p><strong>Result:</strong> No HOD response record will be created when resignation is submitted.</p>';
                    echo '</div>';
                }

                echo '</div>';

                // Check if employee has existing resignations
                $resignQuery = "
                    SELECT
                        r.id,
                        r.resignation_date,
                        r.status,
                        rhr.hod_id,
                        rhr.hod_response,
                        CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
                    FROM resignations r
                    LEFT JOIN resignation_hod_response rhr ON rhr.resignation_id = r.id
                    LEFT JOIN employees hod ON hod.id = rhr.hod_id
                    WHERE r.employee_id = $employeeId
                    ORDER BY r.id DESC
                ";

                $resignResult = $mysqli->query($resignQuery);

                if ($resignResult && $resignResult->num_rows > 0) {
                    echo '<h3>📄 Existing Resignations</h3>';
                    echo '<table>';
                    echo '<tr><th>Res ID</th><th>Date</th><th>Status</th><th>HOD ID</th><th>HOD Name</th><th>HOD Response</th></tr>';

                    while ($resign = $resignResult->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $resign['id'] . '</td>';
                        echo '<td>' . $resign['resignation_date'] . '</td>';
                        echo '<td>' . $resign['status'] . '</td>';
                        echo '<td>' . ($resign['hod_id'] ?? 'NULL') . '</td>';
                        echo '<td>' . ($resign['hod_name'] ?? 'N/A') . '</td>';
                        echo '<td>' . ($resign['hod_response'] ?? 'N/A') . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                }

            } else {
                echo '<div class="warning">';
                echo '<h3>⚠️ Employee Not Found</h3>';
                echo '<p>No employee found with ID: ' . $employeeId . '</p>';
                echo '</div>';
            }
        } else {
            // Show some sample employees
            echo '<h3>Sample Employees (for testing)</h3>';

            $query = "
                SELECT
                    e.id,
                    CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                    e.reporting_manager_id,
                    CONCAT(rm.first_name, ' ', rm.last_name) as reporting_manager_name
                FROM employees e
                LEFT JOIN employees rm ON rm.id = e.reporting_manager_id
                WHERE e.status = 'active'
                AND e.reporting_manager_id IS NOT NULL
                ORDER BY e.id DESC
                LIMIT 10
            ";

            $result = $mysqli->query($query);

            if ($result && $result->num_rows > 0) {
                echo '<table>';
                echo '<tr><th>Emp ID</th><th>Employee Name</th><th>RM ID</th><th>Reporting Manager</th><th>Action</th></tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['employee_name'] . '</td>';
                    echo '<td>' . $row['reporting_manager_id'] . '</td>';
                    echo '<td>' . $row['reporting_manager_name'] . '</td>';
                    echo '<td><a href="?id=' . $row['id'] . '">Check</a></td>';
                    echo '</tr>';
                }

                echo '</table>';
            }
        }
        ?>

    </div>
</body>
</html>
<?php
$mysqli->close();
?>
