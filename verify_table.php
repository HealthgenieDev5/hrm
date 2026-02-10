<?php
require 'vendor/autoload.php';

$db = \Config\Database::connect();

echo "Checking if resignation_hod_response table exists...\n\n";

try {
    $result = $db->query('DESCRIBE resignation_hod_response');
    $fields = $result->getResultArray();

    echo "✓ Table 'resignation_hod_response' exists!\n\n";
    echo "Table Structure:\n";
    echo str_repeat("-", 80) . "\n";
    printf("%-30s %-20s %-10s\n", "Field", "Type", "Null");
    echo str_repeat("-", 80) . "\n";

    foreach($fields as $field) {
        printf("%-30s %-20s %-10s\n",
            $field['Field'],
            $field['Type'],
            $field['Null']
        );
    }

    echo str_repeat("-", 80) . "\n\n";

    // Check if there are any records
    $count = $db->query('SELECT COUNT(*) as count FROM resignation_hod_response')->getRow();
    echo "Current records in table: " . $count->count . "\n\n";

    echo "✓ Database setup complete!\n";

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nPlease ensure you've run the SQL file to create the table.\n";
}
