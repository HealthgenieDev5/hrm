<?php

// Simple script to run employee_attachments migration
require 'vendor/autoload.php';

use CodeIgniter\Database\Config;

// Get database configuration
$db = Config::connect();

// Check if table exists
$tableExists = $db->tableExists('employee_attachments');

if ($tableExists) {
    echo "Table 'employee_attachments' already exists!\n";
    exit(0);
}

// Create the table
$sql = "CREATE TABLE `employee_attachments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id` INT UNSIGNED NOT NULL COMMENT 'Foreign key to employees table',
  `title` VARCHAR(255) NOT NULL COMMENT 'Document title provided by user',
  `file_path` VARCHAR(500) NOT NULL COMMENT 'Relative path to uploaded file',
  `file_name` VARCHAR(255) NOT NULL COMMENT 'Original filename',
  `file_extension` VARCHAR(10) NOT NULL COMMENT 'File extension (pdf, jpg, doc, etc.)',
  `file_size` INT UNSIGNED NULL COMMENT 'File size in bytes',
  `uploaded_by` INT UNSIGNED NULL COMMENT 'Employee ID who uploaded the file',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

try {
    $db->query($sql);
    echo "✓ Table 'employee_attachments' created successfully!\n";

    // Update migrations table
    $db->query("INSERT INTO migrations (version, class, `group`, namespace, time, batch)
                VALUES ('2025-12-20-180849', 'App\\\\Database\\\\Migrations\\\\CreateEmployeeAttachmentsTable', 'default', 'App', ?, 2)",
                [time()]);

    echo "✓ Migration record added successfully!\n";
    echo "\nDone! The employee_attachments table is ready to use.\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
