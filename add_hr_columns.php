<?php
require 'vendor/autoload.php';

// Load CodeIgniter configuration
$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();

echo "Connected to database: " . $db->getDatabase() . "\n\n";

// Check if columns already exist
$fields = $db->getFieldNames('probation_hod_response');
echo "Current columns in probation_hod_response:\n";
print_r($fields);
echo "\n";

if (in_array('hr_manager_id', $fields)) {
    echo "Columns already exist. Migration already applied.\n";
    exit(0);
}

echo "Adding new columns...\n";

try {
    // Add hr_manager_id column
    $db->query("ALTER TABLE probation_hod_response
        ADD COLUMN hr_manager_id INT NULL COMMENT 'HR Manager assigned to review (typically 52, 40, or 93)'");
    echo "✓ Added hr_manager_id column\n";

    // Add hr_response column
    $db->query("ALTER TABLE probation_hod_response
        ADD COLUMN hr_response ENUM('pending', 'remind_later', 'confirmed') NULL DEFAULT NULL COMMENT 'HR Manager action status'");
    echo "✓ Added hr_response column\n";

    // Add hr_response_date column
    $db->query("ALTER TABLE probation_hod_response
        ADD COLUMN hr_response_date DATETIME NULL COMMENT 'When HR Manager took action'");
    echo "✓ Added hr_response_date column\n";

    // Add indexes
    $db->query("ALTER TABLE probation_hod_response ADD INDEX idx_hr_response (hr_response)");
    echo "✓ Added index on hr_response\n";

    $db->query("ALTER TABLE probation_hod_response ADD INDEX idx_hr_manager_id (hr_manager_id)");
    echo "✓ Added index on hr_manager_id\n";

    echo "\n✅ Migration completed successfully!\n";

    // Show updated structure
    $fields = $db->getFieldNames('probation_hod_response');
    echo "\nUpdated columns in probation_hod_response:\n";
    print_r($fields);

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
