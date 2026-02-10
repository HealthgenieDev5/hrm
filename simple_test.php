<?php
/**
 * Simple Database Test for Resignation HOD Workflow
 * Run: php simple_test.php
 */

// Colors for CLI output
define('GREEN', "\033[32m");
define('RED', "\033[31m");
define('YELLOW', "\033[33m");
define('BLUE', "\033[34m");
define('CYAN', "\033[36m");
define('RESET', "\033[0m");
define('BOLD', "\033[1m");

function printHeader($text) {
    $line = str_repeat('=', strlen($text) + 4);
    echo "\n" . BOLD . CYAN . "$line" . RESET . "\n";
    echo BOLD . CYAN . "  $text  " . RESET . "\n";
    echo BOLD . CYAN . "$line" . RESET . "\n\n";
}

function success($text) {
    echo GREEN . "✓" . RESET . " $text\n";
}

function error($text) {
    echo RED . "✗" . RESET . " $text\n";
}

function info($text) {
    echo BLUE . "ℹ" . RESET . " $text\n";
}

printHeader('🧪 RESIGNATION HOD WORKFLOW - DATABASE TEST');

// Read database configuration from .env
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    error(".env file not found!");
    exit(1);
}

$envContent = file_get_contents($envFile);
preg_match('/database\.default\.hostname\s*=\s*(.+)/', $envContent, $hostMatch);
preg_match('/database\.default\.database\s*=\s*(.+)/', $envContent, $dbMatch);
preg_match('/database\.default\.username\s*=\s*(.+)/', $envContent, $userMatch);
preg_match('/database\.default\.password\s*=\s*(.+)/', $envContent, $passMatch);

$host = trim($hostMatch[1] ?? 'localhost');
$database = trim($dbMatch[1] ?? '');
$username = trim($userMatch[1] ?? 'root');
$password = trim($passMatch[1] ?? '');

echo "Connecting to database: $database@$host\n\n";

try {
    $mysqli = new mysqli($host, $username, $password, $database);

    if ($mysqli->connect_error) {
        throw new Exception($mysqli->connect_error);
    }

    success("Connected to database successfully");

    // TEST 1: Check table exists
    printHeader('TEST 1: Check resignation_hod_response Table');

    $result = $mysqli->query("SHOW TABLES LIKE 'resignation_hod_response'");
    if ($result && $result->num_rows > 0) {
        success("Table 'resignation_hod_response' exists");

        // Get table structure
        $structure = $mysqli->query("DESCRIBE resignation_hod_response");
        $columns = [];
        while ($row = $structure->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        info("Columns: " . implode(', ', $columns));
    } else {
        error("Table 'resignation_hod_response' does NOT exist");
        echo "\nRun this SQL to create it:\n";
        echo "mysql -u $username -p$password $database < create_resignation_hod_response_table.sql\n\n";
        exit(1);
    }

    // TEST 2: Check employees with reporting managers
    printHeader('TEST 2: Find Test Employees');

    $query = "
        SELECT
            e.id,
            CONCAT(e.first_name, ' ', e.last_name) as employee_name,
            e.reporting_manager_id,
            CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
        FROM employees e
        LEFT JOIN employees hod ON hod.id = e.reporting_manager_id
        WHERE e.status = 'active'
        AND e.reporting_manager_id IS NOT NULL
        AND e.reporting_manager_id > 0
        ORDER BY e.id DESC
        LIMIT 5
    ";

    $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
        success("Found " . $result->num_rows . " employees with reporting managers");
        echo "\n";
        printf("%-8s %-30s %-8s %-30s\n", "Emp ID", "Employee Name", "HOD ID", "HOD Name");
        echo str_repeat('-', 85) . "\n";

        $firstEmployee = null;
        while ($row = $result->fetch_assoc()) {
            if (!$firstEmployee) $firstEmployee = $row;
            printf("%-8s %-30s %-8s %-30s\n",
                $row['id'],
                $row['employee_name'],
                $row['reporting_manager_id'],
                $row['hod_name']
            );
        }

        echo "\n" . CYAN . "→ Recommended test employee:" . RESET . "\n";
        echo "  Employee: {$firstEmployee['employee_name']} (ID: {$firstEmployee['id']})\n";
        echo "  HOD: {$firstEmployee['hod_name']} (ID: {$firstEmployee['reporting_manager_id']})\n";
    } else {
        error("No employees found with reporting managers");
        echo "  Cannot proceed with testing without employees having reporting_manager_id\n";
    }

    // TEST 3: Check active resignations
    printHeader('TEST 3: Check Active Resignations');

    $result = $mysqli->query("SELECT COUNT(*) as count FROM resignations WHERE status = 'active'");
    $row = $result->fetch_assoc();
    $activeCount = $row['count'];

    if ($activeCount > 0) {
        info("Found $activeCount active resignation(s)");

        $query = "
            SELECT
                r.id,
                r.resignation_date,
                r.last_working_date,
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
            FROM resignations r
            LEFT JOIN employees e ON e.id = r.employee_id
            LEFT JOIN employees hod ON hod.id = e.reporting_manager_id
            WHERE r.status = 'active'
            ORDER BY r.id DESC
            LIMIT 5
        ";

        $result = $mysqli->query($query);
        echo "\n";
        printf("%-6s %-30s %-15s %-15s %-30s\n", "ID", "Employee", "Resign Date", "Last Date", "HOD");
        echo str_repeat('-', 100) . "\n";

        while ($row = $result->fetch_assoc()) {
            printf("%-6s %-30s %-15s %-15s %-30s\n",
                $row['id'],
                $row['employee_name'],
                $row['resignation_date'],
                $row['last_working_date'],
                $row['hod_name'] ?? 'No HOD'
            );
        }
    } else {
        info("No active resignations (this is normal for fresh setup)");
    }

    // TEST 4: Check HOD response records
    printHeader('TEST 4: Check HOD Response Records');

    $result = $mysqli->query("SELECT COUNT(*) as count FROM resignation_hod_response");
    $row = $result->fetch_assoc();
    $responseCount = $row['count'];

    if ($responseCount > 0) {
        info("Found $responseCount HOD response record(s)");

        $query = "
            SELECT
                rhr.id,
                rhr.hod_response,
                rhr.manager_viewed,
                rhr.hod_response_date,
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
            FROM resignation_hod_response rhr
            LEFT JOIN employees e ON e.id = rhr.employee_id
            LEFT JOIN employees hod ON hod.id = rhr.hod_id
            ORDER BY rhr.id DESC
            LIMIT 5
        ";

        $result = $mysqli->query($query);
        echo "\n";
        printf("%-6s %-25s %-25s %-12s %-10s %-20s\n", "ID", "Employee", "HOD", "Response", "Viewed", "Response Date");
        echo str_repeat('-', 110) . "\n";

        while ($row = $result->fetch_assoc()) {
            printf("%-6s %-25s %-25s %-12s %-10s %-20s\n",
                $row['id'],
                $row['employee_name'],
                $row['hod_name'],
                $row['hod_response'],
                $row['manager_viewed'] ?? 'N/A',
                $row['hod_response_date'] ?? 'N/A'
            );
        }
    } else {
        info("No HOD response records (records are created when resignations are added)");
    }

    // TEST 5: Check configuration
    printHeader('TEST 5: Check Configuration');

    preg_match('/app\.resignationHrManagerIds\s*=\s*(.+)/', $envContent, $configMatch);
    if (isset($configMatch[1])) {
        $hrManagerIds = trim($configMatch[1]);
        success("HR Manager IDs configured: $hrManagerIds");

        $ids = explode(',', $hrManagerIds);
        foreach ($ids as $id) {
            $id = trim($id);
            $result = $mysqli->query("SELECT CONCAT(first_name, ' ', last_name) as name FROM employees WHERE id = $id");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                info("  Manager ID $id: {$row['name']}");
            } else {
                error("  Manager ID $id: NOT FOUND in employees table");
            }
        }
    } else {
        error("HR Manager IDs not configured in .env");
        echo "  Add this line to .env:\n";
        echo "  app.resignationHrManagerIds = 52,40,93\n";
    }

    // SUMMARY
    printHeader('📊 TEST SUMMARY');

    echo BOLD . "System Status:" . RESET . "\n";
    success("Database connection working");
    success("resignation_hod_response table exists");
    success("Configuration loaded from .env");

    if ($activeCount > 0) {
        info("$activeCount active resignation(s) found");
    }
    if ($responseCount > 0) {
        info("$responseCount HOD response record(s) found");
    }

    // NEXT STEPS
    printHeader('🚀 NEXT STEPS - Manual Testing');

    if (isset($firstEmployee)) {
        echo CYAN . "Step 1: Create Test Resignation" . RESET . "\n";
        echo "  1. Login to web interface as HR user\n";
        echo "  2. Navigate to: Resignation Dashboard\n";
        echo "  3. Create resignation for: {$firstEmployee['employee_name']} (ID: {$firstEmployee['id']})\n";
        echo "  4. Expected: Success message + record in resignation_hod_response table\n\n";

        echo CYAN . "Step 2: Test HOD Modal" . RESET . "\n";
        echo "  1. Login as: {$firstEmployee['hod_name']} (ID: {$firstEmployee['reporting_manager_id']})\n";
        echo "  2. Navigate to Profile page\n";
        echo "  3. Expected: SweetAlert2 modal appears with resignation\n";
        echo "  4. Try actions: Remind Me / Accept / Reject\n\n";

        if (isset($hrManagerIds)) {
            echo CYAN . "Step 3: Test Manager Modal" . RESET . "\n";
            echo "  1. Login as HR Manager (ID: $hrManagerIds)\n";
            echo "  2. Navigate to Profile page after HOD responds\n";
            echo "  3. Expected: Bootstrap modal appears after 2.5 seconds\n";
            echo "  4. Click Acknowledge\n\n";
        }
    }

    echo CYAN . "For detailed web-based test report:" . RESET . "\n";
    echo "  Open in browser: https://hrm.healthgenie.test/comprehensive_test.php\n\n";

    echo CYAN . "Or start local server:" . RESET . "\n";
    echo "  Run: run_tests.bat\n";
    echo "  Then open: http://localhost:8000/comprehensive_test.php\n\n";

    printHeader('✅ Test Complete');

    $mysqli->close();

} catch (Exception $e) {
    error("Database connection failed: " . $e->getMessage());
    echo "\nCheck your .env file configuration.\n";
    exit(1);
}
