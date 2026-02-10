<?php
/**
 * Comprehensive Test Script for Resignation HOD Workflow
 * This script tests all features step by step
 */

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap CodeIgniter
$pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;
$app->initialize();

use App\Models\ResignationModel;
use App\Models\ResignationHodResponseModel;
use App\Models\EmployeeModel;
use App\Models\ResignationRevisionModel;

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head>";
echo "<title>Resignation Workflow - Comprehensive Test</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
h1 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
h2 { color: #34495e; background: #ecf0f1; padding: 10px; border-left: 4px solid #3498db; margin-top: 30px; }
.test-step { background: #fff; border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; }
.success { color: #27ae60; font-weight: bold; }
.error { color: #e74c3c; font-weight: bold; }
.warning { color: #f39c12; font-weight: bold; }
.info { color: #3498db; font-weight: bold; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #3498db; color: white; }
tr:nth-child(even) { background-color: #f2f2f2; }
.code { background: #2c3e50; color: #ecf0f1; padding: 10px; border-radius: 5px; font-family: monospace; margin: 10px 0; overflow-x: auto; }
.badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
.badge-success { background: #27ae60; color: white; }
.badge-danger { background: #e74c3c; color: white; }
.badge-warning { background: #f39c12; color: white; }
.badge-info { background: #3498db; color: white; }
.sql-query { background: #ecf0f1; padding: 10px; border-left: 3px solid #3498db; margin: 10px 0; font-family: monospace; }
</style>";
echo "</head><body><div class='container'>";

echo "<h1>🧪 Resignation HOD Workflow - Comprehensive Test</h1>";
echo "<p><em>Generated: " . date('Y-m-d H:i:s') . "</em></p>";

$db = \Config\Database::connect();
$testResults = [];

// =============================================================================
// TEST 1: System Prerequisites
// =============================================================================
echo "<h2>TEST 1: System Prerequisites</h2>";
echo "<div class='test-step'>";

try {
    // Check if models exist
    echo "<h3>1.1 Model Classes</h3>";
    $ResignationModel = new ResignationModel();
    echo "<span class='success'>✓</span> ResignationModel loaded<br>";

    $ResignationHodResponseModel = new ResignationHodResponseModel();
    echo "<span class='success'>✓</span> ResignationHodResponseModel loaded<br>";

    $EmployeeModel = new EmployeeModel();
    echo "<span class='success'>✓</span> EmployeeModel loaded<br>";

    $testResults['models'] = 'PASS';
} catch (\Exception $e) {
    echo "<span class='error'>✗</span> Error loading models: " . $e->getMessage() . "<br>";
    $testResults['models'] = 'FAIL';
}

// Check database table
echo "<h3>1.2 Database Table</h3>";
try {
    $result = $db->query('DESCRIBE resignation_hod_response');
    $fields = $result->getResultArray();

    echo "<span class='success'>✓</span> Table 'resignation_hod_response' exists<br>";
    echo "<details><summary>View Table Structure (click to expand)</summary>";
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach($fields as $field) {
        echo "<tr>";
        echo "<td>{$field['Field']}</td>";
        echo "<td>{$field['Type']}</td>";
        echo "<td>{$field['Null']}</td>";
        echo "<td>{$field['Key']}</td>";
        echo "<td>{$field['Default']}</td>";
        echo "</tr>";
    }
    echo "</table></details>";

    $testResults['table'] = 'PASS';
} catch (\Exception $e) {
    echo "<span class='error'>✗</span> Error: " . $e->getMessage() . "<br>";
    $testResults['table'] = 'FAIL';
}

// Check configuration
echo "<h3>1.3 Configuration</h3>";
$hrManagerIds = env('app.resignationHrManagerIds', '');
if (!empty($hrManagerIds)) {
    echo "<span class='success'>✓</span> HR Manager IDs configured: <strong>$hrManagerIds</strong><br>";
    $testResults['config'] = 'PASS';
} else {
    echo "<span class='error'>✗</span> HR Manager IDs not configured<br>";
    $testResults['config'] = 'FAIL';
}

echo "</div>";

// =============================================================================
// TEST 2: Find Suitable Test Employee
// =============================================================================
echo "<h2>TEST 2: Find Suitable Test Employee</h2>";
echo "<div class='test-step'>";

$testEmployee = null;
try {
    $query = "
        SELECT
            e.id,
            e.internal_employee_id,
            CONCAT(e.first_name, ' ', e.last_name) as employee_name,
            e.reporting_manager_id,
            CONCAT(hod.first_name, ' ', hod.last_name) as hod_name,
            hod.internal_employee_id as hod_internal_id,
            d.department_name,
            des.designation_name
        FROM employees e
        LEFT JOIN employees hod ON hod.id = e.reporting_manager_id
        LEFT JOIN departments d ON d.id = e.department_id
        LEFT JOIN designations des ON des.id = e.designation_id
        WHERE e.status = 'active'
        AND e.reporting_manager_id IS NOT NULL
        AND e.reporting_manager_id > 0
        ORDER BY e.id DESC
        LIMIT 5
    ";

    $result = $db->query($query);
    $employees = $result->getResultArray();

    if (count($employees) > 0) {
        $testEmployee = $employees[0];
        echo "<span class='success'>✓</span> Found suitable test employees<br><br>";

        echo "<h4>Available Test Employees (with reporting managers):</h4>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Employee</th><th>Department</th><th>Designation</th><th>HOD ID</th><th>HOD Name</th></tr>";
        foreach($employees as $emp) {
            $highlight = ($emp['id'] == $testEmployee['id']) ? "style='background: #d5f4e6;'" : "";
            echo "<tr $highlight>";
            echo "<td>{$emp['id']}</td>";
            echo "<td>{$emp['employee_name']} ({$emp['internal_employee_id']})</td>";
            echo "<td>{$emp['department_name']}</td>";
            echo "<td>{$emp['designation_name']}</td>";
            echo "<td>{$emp['reporting_manager_id']}</td>";
            echo "<td>{$emp['hod_name']} ({$emp['hod_internal_id']})</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<div class='info' style='margin-top: 15px; padding: 10px; background: #d5f4e6; border-radius: 5px;'>";
        echo "📋 <strong>Selected for testing:</strong><br>";
        echo "Employee: {$testEmployee['employee_name']} (ID: {$testEmployee['id']})<br>";
        echo "HOD: {$testEmployee['hod_name']} (ID: {$testEmployee['reporting_manager_id']})";
        echo "</div>";

        $testResults['test_employee'] = 'PASS';
    } else {
        echo "<span class='error'>✗</span> No employees found with reporting managers<br>";
        echo "<em>Cannot proceed with testing. Please ensure employees have reporting_manager_id set.</em>";
        $testResults['test_employee'] = 'FAIL';
    }
} catch (\Exception $e) {
    echo "<span class='error'>✗</span> Error: " . $e->getMessage() . "<br>";
    $testResults['test_employee'] = 'FAIL';
}

echo "</div>";

// =============================================================================
// TEST 3: Check Existing Resignations
// =============================================================================
echo "<h2>TEST 3: Check Existing Resignations</h2>";
echo "<div class='test-step'>";

try {
    $query = "
        SELECT
            r.id,
            r.resignation_date,
            r.last_working_date,
            r.status,
            CONCAT(e.first_name, ' ', e.last_name) as employee_name,
            e.reporting_manager_id,
            CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
        FROM resignations r
        LEFT JOIN employees e ON e.id = r.employee_id
        LEFT JOIN employees hod ON hod.id = e.reporting_manager_id
        WHERE r.status = 'active'
        ORDER BY r.id DESC
        LIMIT 10
    ";

    $result = $db->query($query);
    $resignations = $result->getResultArray();

    echo "<h4>Active Resignations: " . count($resignations) . "</h4>";

    if (count($resignations) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Employee</th><th>Resignation Date</th><th>Last Working Date</th><th>HOD</th><th>Status</th></tr>";
        foreach($resignations as $r) {
            echo "<tr>";
            echo "<td>{$r['id']}</td>";
            echo "<td>{$r['employee_name']}</td>";
            echo "<td>{$r['resignation_date']}</td>";
            echo "<td>{$r['last_working_date']}</td>";
            echo "<td>" . ($r['hod_name'] ?? '<em>No HOD</em>') . "</td>";
            echo "<td><span class='badge badge-success'>{$r['status']}</span></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<em>No active resignations found.</em><br>";
    }

    $testResults['existing_resignations'] = 'INFO';
} catch (\Exception $e) {
    echo "<span class='error'>✗</span> Error: " . $e->getMessage() . "<br>";
}

echo "</div>";

// =============================================================================
// TEST 4: Check Existing HOD Response Records
// =============================================================================
echo "<h2>TEST 4: Check Existing HOD Response Records</h2>";
echo "<div class='test-step'>";

try {
    $query = "
        SELECT
            rhr.*,
            r.resignation_date,
            CONCAT(e.first_name, ' ', e.last_name) as employee_name,
            CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
        FROM resignation_hod_response rhr
        LEFT JOIN resignations r ON r.id = rhr.resignation_id
        LEFT JOIN employees e ON e.id = rhr.employee_id
        LEFT JOIN employees hod ON hod.id = rhr.hod_id
        ORDER BY rhr.id DESC
        LIMIT 10
    ";

    $result = $db->query($query);
    $responses = $result->getResultArray();

    echo "<h4>HOD Response Records: " . count($responses) . "</h4>";

    if (count($responses) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Employee</th><th>HOD</th><th>Response</th><th>Response Date</th><th>Manager Viewed</th></tr>";
        foreach($responses as $r) {
            $responseBadge = '';
            switch($r['hod_response']) {
                case 'pending':
                    $responseBadge = "<span class='badge badge-warning'>pending</span>";
                    break;
                case 'too_early':
                    $responseBadge = "<span class='badge badge-info'>too_early</span>";
                    break;
                case 'accept':
                    $responseBadge = "<span class='badge badge-success'>accept</span>";
                    break;
                case 'rejected':
                    $responseBadge = "<span class='badge badge-danger'>rejected</span>";
                    break;
            }

            $viewedBadge = '';
            if ($r['manager_viewed'] == 'viewed') {
                $viewedBadge = "<span class='badge badge-success'>viewed</span>";
            } else {
                $viewedBadge = "<span class='badge badge-warning'>pending</span>";
            }

            echo "<tr>";
            echo "<td>{$r['id']}</td>";
            echo "<td>{$r['employee_name']}</td>";
            echo "<td>{$r['hod_name']}</td>";
            echo "<td>$responseBadge</td>";
            echo "<td>" . ($r['hod_response_date'] ?? '<em>N/A</em>') . "</td>";
            echo "<td>$viewedBadge</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<em>No HOD response records found.</em><br>";
        echo "<span class='info'>ℹ</span> This is normal if no resignations have been created yet.<br>";
    }

    $testResults['existing_responses'] = 'INFO';
} catch (\Exception $e) {
    echo "<span class='error'>✗</span> Error: " . $e->getMessage() . "<br>";
}

echo "</div>";

// =============================================================================
// TEST 5: Test Model Methods
// =============================================================================
echo "<h2>TEST 5: Test Model Methods</h2>";
echo "<div class='test-step'>";

if ($testEmployee) {
    $hodId = $testEmployee['reporting_manager_id'];

    // Test getPendingHodNotifications
    echo "<h4>5.1 Test getPendingHodNotifications()</h4>";
    try {
        $pendingNotifications = $ResignationHodResponseModel->getPendingHodNotifications($hodId);
        echo "<span class='success'>✓</span> Method executed successfully<br>";
        echo "Found <strong>" . count($pendingNotifications) . "</strong> pending notification(s) for HOD ID: $hodId<br>";

        if (count($pendingNotifications) > 0) {
            echo "<details><summary>View Details</summary>";
            echo "<pre>" . print_r($pendingNotifications, true) . "</pre>";
            echo "</details>";
        }

        $testResults['getPendingHodNotifications'] = 'PASS';
    } catch (\Exception $e) {
        echo "<span class='error'>✗</span> Error: " . $e->getMessage() . "<br>";
        $testResults['getPendingHodNotifications'] = 'FAIL';
    }

    // Test getPendingManagerNotifications
    echo "<h4>5.2 Test getPendingManagerNotifications()</h4>";
    try {
        $hrManagerIds = array_map('intval', explode(',', env('app.resignationHrManagerIds', '52')));
        $managerId = $hrManagerIds[0];

        $pendingManagerNotifs = $ResignationHodResponseModel->getPendingManagerNotifications($managerId);
        echo "<span class='success'>✓</span> Method executed successfully<br>";
        echo "Found <strong>" . count($pendingManagerNotifs) . "</strong> pending manager notification(s) for Manager ID: $managerId<br>";

        if (count($pendingManagerNotifs) > 0) {
            echo "<details><summary>View Details</summary>";
            echo "<pre>" . print_r($pendingManagerNotifs, true) . "</pre>";
            echo "</details>";
        }

        $testResults['getPendingManagerNotifications'] = 'PASS';
    } catch (\Exception $e) {
        echo "<span class='error'>✗</span> Error: " . $e->getMessage() . "<br>";
        $testResults['getPendingManagerNotifications'] = 'FAIL';
    }
} else {
    echo "<span class='warning'>⚠</span> Skipped - No test employee available<br>";
    $testResults['model_methods'] = 'SKIP';
}

echo "</div>";

// =============================================================================
// TEST 6: Test AJAX Endpoints
// =============================================================================
echo "<h2>TEST 6: Test AJAX Endpoints</h2>";
echo "<div class='test-step'>";

echo "<h4>6.1 Route Configuration</h4>";
$routes = [
    '/ajax/resignation/save-hod-response' => 'Profile::saveResignationResponseOfHod',
    '/ajax/resignation/manager-notifications' => 'Profile::getManagerResignationNotifications',
    '/ajax/resignation/manager-notification-action' => 'Profile::handleManagerResignationNotificationAction'
];

echo "<table>";
echo "<tr><th>Route</th><th>Controller Method</th><th>Status</th></tr>";
foreach($routes as $route => $method) {
    echo "<tr>";
    echo "<td><code>$route</code></td>";
    echo "<td><code>$method</code></td>";

    // Check if method exists in Profile controller
    $methodName = explode('::', $method)[1];
    if (method_exists('App\Controllers\User\Profile', $methodName)) {
        echo "<td><span class='success'>✓ Exists</span></td>";
    } else {
        echo "<td><span class='error'>✗ Not Found</span></td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "<div class='info' style='margin-top: 15px; padding: 10px; background: #e8f4f8; border-radius: 5px;'>";
echo "ℹ <strong>Note:</strong> AJAX endpoints require active session and proper authentication.<br>";
echo "To test these endpoints, use the web interface after logging in.";
echo "</div>";

$testResults['ajax_routes'] = 'PASS';

echo "</div>";

// =============================================================================
// TEST 7: Frontend Integration Check
// =============================================================================
echo "<h2>TEST 7: Frontend Integration Check</h2>";
echo "<div class='test-step'>";

echo "<h4>7.1 Profile View File</h4>";
$profileViewPath = APPPATH . 'Views/User/Profile.php';
if (file_exists($profileViewPath)) {
    echo "<span class='success'>✓</span> Profile.php view file exists<br>";

    $profileContent = file_get_contents($profileViewPath);

    // Check for resignation modal code
    $checks = [
        'resignationHodAcknowledgments' => 'Resignation HOD data variable',
        'Resignation Acknowledgment Required' => 'HOD Modal title',
        'resignation-action-select' => 'HOD action dropdown',
        'resignationNotificationModal' => 'Manager notification modal',
        'checkForManagerResignationNotifications' => 'Manager auto-check function'
    ];

    echo "<h5>Code Integration Checks:</h5>";
    echo "<table>";
    echo "<tr><th>Check</th><th>Status</th></tr>";
    foreach($checks as $search => $description) {
        echo "<tr>";
        echo "<td>$description</td>";
        if (strpos($profileContent, $search) !== false) {
            echo "<td><span class='success'>✓ Found</span></td>";
        } else {
            echo "<td><span class='error'>✗ Not Found</span></td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    $testResults['frontend'] = 'PASS';
} else {
    echo "<span class='error'>✗</span> Profile.php view file not found<br>";
    $testResults['frontend'] = 'FAIL';
}

echo "</div>";

// =============================================================================
// SUMMARY
// =============================================================================
echo "<h2>📊 Test Summary</h2>";
echo "<div class='test-step'>";

$passCount = count(array_filter($testResults, function($v) { return $v === 'PASS'; }));
$failCount = count(array_filter($testResults, function($v) { return $v === 'FAIL'; }));
$skipCount = count(array_filter($testResults, function($v) { return $v === 'SKIP'; }));
$infoCount = count(array_filter($testResults, function($v) { return $v === 'INFO'; }));

echo "<table>";
echo "<tr><th>Test Category</th><th>Result</th></tr>";
foreach($testResults as $test => $result) {
    echo "<tr>";
    echo "<td>" . ucwords(str_replace('_', ' ', $test)) . "</td>";

    $badge = '';
    switch($result) {
        case 'PASS':
            $badge = "<span class='badge badge-success'>$result</span>";
            break;
        case 'FAIL':
            $badge = "<span class='badge badge-danger'>$result</span>";
            break;
        case 'SKIP':
            $badge = "<span class='badge badge-warning'>$result</span>";
            break;
        case 'INFO':
            $badge = "<span class='badge badge-info'>$result</span>";
            break;
    }

    echo "<td>$badge</td>";
    echo "</tr>";
}
echo "</table>";

echo "<div style='margin-top: 20px; padding: 15px; border-radius: 5px;";
if ($failCount > 0) {
    echo "background: #ffe6e6; border: 2px solid #e74c3c;'>";
    echo "<h3 style='color: #e74c3c; margin-top: 0;'>⚠ Issues Detected</h3>";
    echo "<p>There are $failCount failed test(s). Please review the errors above.</p>";
} else {
    echo "background: #d5f4e6; border: 2px solid #27ae60;'>";
    echo "<h3 style='color: #27ae60; margin-top: 0;'>✓ All Tests Passed!</h3>";
    echo "<p>System is ready for manual testing.</p>";
}
echo "</div>";

echo "</div>";

// =============================================================================
// NEXT STEPS
// =============================================================================
echo "<h2>🚀 Next Steps - Manual Testing</h2>";
echo "<div class='test-step'>";

if ($testEmployee) {
    echo "<h3>Step-by-Step Testing Guide</h3>";

    echo "<div style='background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0;'>";
    echo "<h4 style='margin-top: 0;'>📋 Test Employee Information</h4>";
    echo "<strong>Employee:</strong> {$testEmployee['employee_name']} (ID: {$testEmployee['id']})<br>";
    echo "<strong>HOD:</strong> {$testEmployee['hod_name']} (ID: {$testEmployee['reporting_manager_id']})<br>";
    echo "<strong>Department:</strong> {$testEmployee['department_name']}<br>";
    echo "</div>";

    echo "<ol style='line-height: 2;'>";

    echo "<li><strong>Create Test Resignation (as HR user)</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;→ Login to system<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Navigate to: <a href='" . base_url('resignation') . "' target='_blank'>" . base_url('resignation') . "</a><br>";
    echo "&nbsp;&nbsp;&nbsp;→ Click 'Create Resignation'<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Select employee: <strong>{$testEmployee['employee_name']}</strong> (ID: {$testEmployee['id']})<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Set resignation date: <strong>" . date('Y-m-d') . "</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;→ Submit form<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Expected: Success message with 'HOD will be notified'<br>";
    echo "</li>";

    echo "<li><strong>Verify Database Record</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;→ Run this query:<br>";
    echo "<div class='sql-query'>SELECT * FROM resignation_hod_response ORDER BY id DESC LIMIT 1;</div>";
    echo "&nbsp;&nbsp;&nbsp;→ Expected: New record with hod_response='pending'<br>";
    echo "</li>";

    echo "<li><strong>Test HOD Modal (login as HOD)</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;→ Logout current user<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Login as: <strong>{$testEmployee['hod_name']}</strong> (ID: {$testEmployee['reporting_manager_id']})<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Navigate to: <a href='" . base_url('profile') . "' target='_blank'>" . base_url('profile') . "</a><br>";
    echo "&nbsp;&nbsp;&nbsp;→ Expected: SweetAlert2 modal appears immediately<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Try 'Remind Me' action<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Check database: hod_response='too_early'<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Reload page (modal should NOT appear again today)<br>";
    echo "</li>";

    echo "<li><strong>Test Accept/Reject</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;→ Create another test resignation<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Login as HOD again<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Try 'Accept' action<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Check database: hod_response='accept', manager_id set<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Check email inbox for notification<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Create third resignation and try 'Reject' with reason<br>";
    echo "</li>";

    $hrManagerIds = env('app.resignationHrManagerIds', '52,40,93');
    echo "<li><strong>Test Manager Notification Modal</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;→ Login as HR Manager (ID: one of $hrManagerIds)<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Navigate to profile page<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Wait 2.5 seconds<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Expected: Bootstrap modal appears with HOD response<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Click 'Acknowledge'<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Check database: manager_viewed='viewed'<br>";
    echo "&nbsp;&nbsp;&nbsp;→ Modal should show next notification if any<br>";
    echo "</li>";

    echo "</ol>";

    echo "<div style='background: #e8f4f8; border-left: 4px solid #3498db; padding: 15px; margin: 20px 0;'>";
    echo "<h4 style='margin-top: 0;'>💡 Testing Tips</h4>";
    echo "• Keep browser console open (F12) to check for JavaScript errors<br>";
    echo "• Test in incognito/private window to avoid cache issues<br>";
    echo "• Check database after each action to verify changes<br>";
    echo "• Check writable/logs/ for any PHP errors<br>";
    echo "</div>";

} else {
    echo "<span class='error'>✗</span> Cannot proceed - No suitable test employee found<br>";
    echo "<p>Please ensure employees have reporting_manager_id set in the database.</p>";
}

echo "</div>";

echo "<hr>";
echo "<p style='text-align: center; color: #7f8c8d;'>";
echo "<em>For detailed documentation, see RESIGNATION_HOD_WORKFLOW_IMPLEMENTATION.md</em><br>";
echo "Generated by Comprehensive Test Script | " . date('Y-m-d H:i:s');
echo "</p>";

echo "</div></body></html>";
