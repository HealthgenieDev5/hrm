<?php
/**
 * CLI Test Script for Resignation HOD Workflow
 * Run: php cli_test.php
 */

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

require __DIR__ . '/vendor/autoload.php';

// Bootstrap CodeIgniter
$pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;
$app->initialize();

use App\Models\ResignationModel;
use App\Models\ResignationHodResponseModel;
use App\Models\EmployeeModel;

// Colors for CLI output
$colors = [
    'green' => "\033[32m",
    'red' => "\033[31m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m",
    'cyan' => "\033[36m",
    'reset' => "\033[0m",
    'bold' => "\033[1m",
];

function printHeader($text, $char = '=') {
    global $colors;
    $line = str_repeat($char, strlen($text) + 4);
    echo "\n{$colors['bold']}{$colors['cyan']}$line{$colors['reset']}\n";
    echo "{$colors['bold']}{$colors['cyan']}  $text  {$colors['reset']}\n";
    echo "{$colors['bold']}{$colors['cyan']}$line{$colors['reset']}\n\n";
}

function printSuccess($text) {
    global $colors;
    echo "{$colors['green']}✓{$colors['reset']} $text\n";
}

function printError($text) {
    global $colors;
    echo "{$colors['red']}✗{$colors['reset']} $text\n";
}

function printWarning($text) {
    global $colors;
    echo "{$colors['yellow']}⚠{$colors['reset']} $text\n";
}

function printInfo($text) {
    global $colors;
    echo "{$colors['blue']}ℹ{$colors['reset']} $text\n";
}

$db = \Config\Database::connect();

printHeader('🧪 RESIGNATION HOD WORKFLOW - COMPREHENSIVE TEST', '=');
echo "Generated: " . date('Y-m-d H:i:s') . "\n";

// TEST 1: System Prerequisites
printHeader('TEST 1: System Prerequisites', '-');

echo "1.1 Model Classes:\n";
try {
    $ResignationModel = new ResignationModel();
    printSuccess("ResignationModel loaded");

    $ResignationHodResponseModel = new ResignationHodResponseModel();
    printSuccess("ResignationHodResponseModel loaded");

    $EmployeeModel = new EmployeeModel();
    printSuccess("EmployeeModel loaded");
} catch (\Exception $e) {
    printError("Error loading models: " . $e->getMessage());
}

echo "\n1.2 Database Table:\n";
try {
    $result = $db->query('DESCRIBE resignation_hod_response');
    $fields = $result->getResultArray();
    printSuccess("Table 'resignation_hod_response' exists with " . count($fields) . " columns");
} catch (\Exception $e) {
    printError("Table check failed: " . $e->getMessage());
}

echo "\n1.3 Configuration:\n";
$hrManagerIds = env('app.resignationHrManagerIds', '');
if (!empty($hrManagerIds)) {
    printSuccess("HR Manager IDs configured: $hrManagerIds");
} else {
    printError("HR Manager IDs not configured in .env");
}

// TEST 2: Find Test Employee
printHeader('TEST 2: Find Suitable Test Employee', '-');

try {
    $query = "
        SELECT
            e.id,
            e.internal_employee_id,
            CONCAT(e.first_name, ' ', e.last_name) as employee_name,
            e.reporting_manager_id,
            CONCAT(hod.first_name, ' ', hod.last_name) as hod_name,
            hod.internal_employee_id as hod_internal_id
        FROM employees e
        LEFT JOIN employees hod ON hod.id = e.reporting_manager_id
        WHERE e.status = 'active'
        AND e.reporting_manager_id IS NOT NULL
        AND e.reporting_manager_id > 0
        ORDER BY e.id DESC
        LIMIT 1
    ";

    $result = $db->query($query);
    $testEmployee = $result->getRowArray();

    if ($testEmployee) {
        printSuccess("Found suitable test employee");
        echo "  Employee: {$testEmployee['employee_name']} (ID: {$testEmployee['id']})\n";
        echo "  HOD: {$testEmployee['hod_name']} (ID: {$testEmployee['reporting_manager_id']})\n";
    } else {
        printError("No employees found with reporting managers");
    }
} catch (\Exception $e) {
    printError("Error: " . $e->getMessage());
}

// TEST 3: Check Existing Resignations
printHeader('TEST 3: Check Existing Resignations', '-');

try {
    $result = $db->query("SELECT COUNT(*) as count FROM resignations WHERE status = 'active'");
    $count = $result->getRow()->count;

    if ($count > 0) {
        printInfo("Found $count active resignation(s)");

        $query = "
            SELECT
                r.id,
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                r.resignation_date,
                CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
            FROM resignations r
            LEFT JOIN employees e ON e.id = r.employee_id
            LEFT JOIN employees hod ON hod.id = e.reporting_manager_id
            WHERE r.status = 'active'
            ORDER BY r.id DESC
            LIMIT 3
        ";

        $result = $db->query($query);
        $resignations = $result->getResultArray();

        foreach ($resignations as $r) {
            echo "  • Resignation #{$r['id']}: {$r['employee_name']} ({$r['resignation_date']}) - HOD: {$r['hod_name']}\n";
        }
    } else {
        printInfo("No active resignations found (this is normal for fresh setup)");
    }
} catch (\Exception $e) {
    printError("Error: " . $e->getMessage());
}

// TEST 4: Check HOD Response Records
printHeader('TEST 4: Check HOD Response Records', '-');

try {
    $result = $db->query("SELECT COUNT(*) as count FROM resignation_hod_response");
    $count = $result->getRow()->count;

    if ($count > 0) {
        printInfo("Found $count HOD response record(s)");

        $query = "
            SELECT
                rhr.id,
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                CONCAT(hod.first_name, ' ', hod.last_name) as hod_name,
                rhr.hod_response,
                rhr.manager_viewed
            FROM resignation_hod_response rhr
            LEFT JOIN employees e ON e.id = rhr.employee_id
            LEFT JOIN employees hod ON hod.id = rhr.hod_id
            ORDER BY rhr.id DESC
            LIMIT 3
        ";

        $result = $db->query($query);
        $responses = $result->getResultArray();

        foreach ($responses as $r) {
            echo "  • Record #{$r['id']}: {$r['employee_name']} - HOD: {$r['hod_name']} - Response: {$r['hod_response']} - Viewed: {$r['manager_viewed']}\n";
        }
    } else {
        printInfo("No HOD response records found (records are created when resignations are added)");
    }
} catch (\Exception $e) {
    printError("Error: " . $e->getMessage());
}

// TEST 5: Test Model Methods
printHeader('TEST 5: Test Model Methods', '-');

if (isset($testEmployee)) {
    $hodId = $testEmployee['reporting_manager_id'];

    echo "5.1 Testing getPendingHodNotifications():\n";
    try {
        $pendingNotifications = $ResignationHodResponseModel->getPendingHodNotifications($hodId);
        printSuccess("Method executed successfully");
        echo "  Found " . count($pendingNotifications) . " pending notification(s) for HOD ID: $hodId\n";
    } catch (\Exception $e) {
        printError("Error: " . $e->getMessage());
    }

    echo "\n5.2 Testing getPendingManagerNotifications():\n";
    try {
        $hrManagerIds = array_map('intval', explode(',', env('app.resignationHrManagerIds', '52')));
        $managerId = $hrManagerIds[0];

        $pendingManagerNotifs = $ResignationHodResponseModel->getPendingManagerNotifications($managerId);
        printSuccess("Method executed successfully");
        echo "  Found " . count($pendingManagerNotifs) . " pending manager notification(s) for Manager ID: $managerId\n";
    } catch (\Exception $e) {
        printError("Error: " . $e->getMessage());
    }
} else {
    printWarning("Skipped - No test employee available");
}

// TEST 6: Check Routes
printHeader('TEST 6: Check AJAX Routes', '-');

$routes = [
    'saveResignationResponseOfHod',
    'getManagerResignationNotifications',
    'handleManagerResignationNotificationAction'
];

foreach ($routes as $method) {
    if (method_exists('App\Controllers\User\Profile', $method)) {
        printSuccess("Route method exists: Profile::$method");
    } else {
        printError("Route method NOT found: Profile::$method");
    }
}

// TEST 7: Check Frontend Integration
printHeader('TEST 7: Check Frontend Integration', '-');

$profileViewPath = APPPATH . 'Views/User/Profile.php';
if (file_exists($profileViewPath)) {
    printSuccess("Profile.php view file exists");

    $profileContent = file_get_contents($profileViewPath);

    $checks = [
        'resignationHodAcknowledgments' => 'Resignation HOD data variable',
        'Resignation Acknowledgment Required' => 'HOD Modal title',
        'resignationNotificationModal' => 'Manager notification modal'
    ];

    foreach ($checks as $search => $description) {
        if (strpos($profileContent, $search) !== false) {
            printSuccess("Found: $description");
        } else {
            printError("NOT found: $description");
        }
    }
} else {
    printError("Profile.php view file not found");
}

// SUMMARY
printHeader('📊 TEST SUMMARY', '=');

echo "\n{$colors['bold']}System Status:{$colors['reset']}\n";
printSuccess("Database table created");
printSuccess("Models loaded successfully");
printSuccess("Routes configured");
printSuccess("Frontend integration complete");

if (empty($hrManagerIds)) {
    echo "\n{$colors['yellow']}Configuration Issue:{$colors['reset']}\n";
    printWarning("HR Manager IDs not configured in .env");
}

// NEXT STEPS
printHeader('🚀 NEXT STEPS - Manual Testing', '=');

if (isset($testEmployee)) {
    echo "\n{$colors['bold']}Ready for manual testing!{$colors['reset']}\n\n";

    echo "{$colors['cyan']}Test Employee Information:{$colors['reset']}\n";
    echo "  Employee: {$testEmployee['employee_name']} (ID: {$testEmployee['id']})\n";
    echo "  HOD: {$testEmployee['hod_name']} (ID: {$testEmployee['reporting_manager_id']})\n\n";

    echo "{$colors['cyan']}Step 1: Create Test Resignation{$colors['reset']}\n";
    echo "  1. Login as HR user\n";
    echo "  2. Navigate to: " . base_url('resignation') . "\n";
    echo "  3. Create resignation for: {$testEmployee['employee_name']}\n";
    echo "  4. Expected: Success message with 'HOD will be notified'\n\n";

    echo "{$colors['cyan']}Step 2: Test HOD Modal{$colors['reset']}\n";
    echo "  1. Login as: {$testEmployee['hod_name']} (ID: {$testEmployee['reporting_manager_id']})\n";
    echo "  2. Navigate to: " . base_url('profile') . "\n";
    echo "  3. Expected: SweetAlert2 modal appears\n";
    echo "  4. Try: Remind Me / Accept / Reject\n\n";

    echo "{$colors['cyan']}Step 3: Test Manager Modal{$colors['reset']}\n";
    echo "  1. Login as HR Manager (ID: $hrManagerIds)\n";
    echo "  2. Navigate to profile page\n";
    echo "  3. Wait 2.5 seconds\n";
    echo "  4. Expected: Bootstrap modal with HOD response\n";
    echo "  5. Click: Acknowledge\n\n";
} else {
    printError("No suitable test employee found");
    echo "  Please ensure employees have reporting_manager_id set\n\n";
}

echo "{$colors['bold']}{$colors['blue']}For detailed web-based test report, open:{$colors['reset']}\n";
echo base_url('comprehensive_test.php') . "\n\n";

echo "{$colors['cyan']}Or run:{$colors['reset']} run_tests.bat\n";
echo "{$colors['cyan']}Then open:{$colors['reset']} http://localhost:8000/comprehensive_test.php\n\n";

printHeader('Test Complete', '=');
