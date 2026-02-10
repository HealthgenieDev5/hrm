<?php
// Simple test to check resignation workflow setup
// Access via: https://hrm.healthgenie.test/test_resignation_workflow.php

require __DIR__ . '/public/index.php';

use App\Models\ResignationModel;
use App\Models\ResignationHodResponseModel;
use App\Models\EmployeeModel;

echo "<h1>Resignation HOD Workflow - Test Status</h1>";
echo "<hr>";

// Check if models exist
echo "<h2>1. Model Check</h2>";
try {
    $ResignationModel = new ResignationModel();
    echo "✓ ResignationModel loaded<br>";

    $ResignationHodResponseModel = new ResignationHodResponseModel();
    echo "✓ ResignationHodResponseModel loaded<br>";
} catch (\Exception $e) {
    echo "✗ Error loading models: " . $e->getMessage() . "<br>";
}

// Check table exists
echo "<h2>2. Database Table Check</h2>";
try {
    $db = \Config\Database::connect();
    $result = $db->query('SELECT COUNT(*) as count FROM resignation_hod_response');
    $count = $result->getRow()->count;
    echo "✓ Table 'resignation_hod_response' exists<br>";
    echo "Current records: " . $count . "<br>";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// Check active resignations
echo "<h2>3. Active Resignations</h2>";
try {
    $db = \Config\Database::connect();
    $query = "
        SELECT
            r.id,
            r.resignation_date,
            CONCAT(e.first_name, ' ', e.last_name) as employee_name,
            e.reporting_manager_id,
            CONCAT(hod.first_name, ' ', hod.last_name) as hod_name,
            r.status
        FROM resignations r
        LEFT JOIN employees e ON e.id = r.employee_id
        LEFT JOIN employees hod ON hod.id = e.reporting_manager_id
        WHERE r.status = 'active'
        ORDER BY r.resignation_date DESC
        LIMIT 5
    ";
    $result = $db->query($query);
    $resignations = $result->getResultArray();

    if (count($resignations) > 0) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Employee</th><th>Date</th><th>HOD</th><th>Status</th></tr>";
        foreach ($resignations as $r) {
            echo "<tr>";
            echo "<td>" . $r['id'] . "</td>";
            echo "<td>" . $r['employee_name'] . "</td>";
            echo "<td>" . $r['resignation_date'] . "</td>";
            echo "<td>" . ($r['hod_name'] ?? 'No HOD') . "</td>";
            echo "<td>" . $r['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No active resignations found.<br>";
        echo "<em>Create a test resignation to proceed with testing.</em><br>";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// Check HOD response records
echo "<h2>4. HOD Response Records</h2>";
try {
    $db = \Config\Database::connect();
    $query = "
        SELECT
            rhr.*,
            CONCAT(e.first_name, ' ', e.last_name) as employee_name,
            CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
        FROM resignation_hod_response rhr
        LEFT JOIN employees e ON e.id = rhr.employee_id
        LEFT JOIN employees hod ON hod.id = rhr.hod_id
        ORDER BY rhr.id DESC
        LIMIT 5
    ";
    $result = $db->query($query);
    $responses = $result->getResultArray();

    if (count($responses) > 0) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Employee</th><th>HOD</th><th>Response</th><th>Date</th></tr>";
        foreach ($responses as $r) {
            echo "<tr>";
            echo "<td>" . $r['id'] . "</td>";
            echo "<td>" . $r['employee_name'] . "</td>";
            echo "<td>" . $r['hod_name'] . "</td>";
            echo "<td><strong>" . $r['hod_response'] . "</strong></td>";
            echo "<td>" . ($r['hod_response_date'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No HOD response records found.<br>";
        echo "<em>Records will be created automatically when resignations are added.</em><br>";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// Check configuration
echo "<h2>5. Configuration Check</h2>";
$hrManagerIds = env('app.resignationHrManagerIds', '');
if (!empty($hrManagerIds)) {
    echo "✓ HR Manager IDs configured: " . $hrManagerIds . "<br>";

    $ids = array_map('intval', explode(',', $hrManagerIds));
    $db = \Config\Database::connect();
    foreach ($ids as $id) {
        $query = "SELECT CONCAT(first_name, ' ', last_name) as name FROM employees WHERE id = $id";
        $result = $db->query($query);
        if ($row = $result->getRow()) {
            echo "&nbsp;&nbsp;&nbsp;- Employee ID $id: " . $row->name . "<br>";
        } else {
            echo "&nbsp;&nbsp;&nbsp;- Employee ID $id: NOT FOUND<br>";
        }
    }
} else {
    echo "✗ HR Manager IDs not configured in .env<br>";
}

echo "<hr>";
echo "<h2>Next Steps</h2>";
echo "<ol>";
echo "<li><strong>Create a Test Resignation:</strong><br>";
echo "&nbsp;&nbsp;&nbsp;Navigate to: <a href='" . base_url('resignation') . "'>" . base_url('resignation') . "</a><br>";
echo "&nbsp;&nbsp;&nbsp;Create a resignation for an employee who has a reporting_manager_id</li>";
echo "<li><strong>Test HOD Modal:</strong><br>";
echo "&nbsp;&nbsp;&nbsp;Login as the HOD (reporting manager)<br>";
echo "&nbsp;&nbsp;&nbsp;Go to Profile page: <a href='" . base_url('profile') . "'>" . base_url('profile') . "</a><br>";
echo "&nbsp;&nbsp;&nbsp;You should see a SweetAlert2 modal with the resignation</li>";
echo "<li><strong>Test Manager Modal:</strong><br>";
echo "&nbsp;&nbsp;&nbsp;Login as HR Manager (ID: $hrManagerIds)<br>";
echo "&nbsp;&nbsp;&nbsp;After HOD responds, go to Profile page<br>";
echo "&nbsp;&nbsp;&nbsp;Wait 2.5 seconds for the Bootstrap modal to appear</li>";
echo "</ol>";

echo "<hr>";
echo "<p><em>For detailed testing instructions, see INSTALLATION_STEPS.md</em></p>";
