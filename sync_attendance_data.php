<?php

/**
 * Attendance Data Sync Script
 *
 * Fetches attendance data from hrm-attendance-api (JSON API)
 * and syncs to local HRM database
 *
 * Run this script via CRON every 5 minutes
 *
 * Usage:
 *   php sync_attendance_data.php                          # Sync last 30 days
 *   php sync_attendance_data.php --from=2025-11-01        # Sync from specific date
 *   php sync_attendance_data.php --date=2025-11-15        # Sync single date
 *   php sync_attendance_data.php --from=2025-11-01 --to=2025-11-15  # Sync date range
 *   php sync_attendance_data.php --employee=123           # Sync specific employee
 *   php sync_attendance_data.php --days=7                 # Sync last 7 days
 */

// Parse command-line arguments
$options = getopt('', ['from:', 'to:', 'date:', 'employee:', 'days:']);

// Configuration
$apiUrl = 'http://hrm-attendance-api.test/api/v1';
$dbConfig = [
    'host' => 'localhost',
    'database' => 'hrm.healthgenie.in_bkp_2025_11_05',
    'username' => 'root',
    'password' => 'mysql',
    'port' => 3306
];

try {
    echo "[" . date('Y-m-d H:i:s') . "] Starting attendance data sync...\n";

    // Determine date range based on arguments
    if (isset($options['date'])) {
        // Single date sync
        $fromDate = $options['date'];
        $toDate = $options['date'];
        echo "[" . date('Y-m-d H:i:s') . "] Mode: Single date sync ($fromDate)\n";
    } elseif (isset($options['days'])) {
        // Last N days
        $days = (int)$options['days'];
        $fromDate = date('Y-m-d', strtotime("-{$days} days"));
        $toDate = date('Y-m-d');
        echo "[" . date('Y-m-d H:i:s') . "] Mode: Last {$days} days sync\n";
    } else {
        // Date range or default (last 30 days)
        $fromDate = isset($options['from']) ? $options['from'] : date('Y-m-d', strtotime('-30 days'));
        $toDate = isset($options['to']) ? $options['to'] : date('Y-m-d');
        echo "[" . date('Y-m-d H:i:s') . "] Mode: Date range sync\n";
    }

    // Employee filter
    $employeeCode = isset($options['employee']) ? $options['employee'] : null;
    if ($employeeCode) {
        echo "[" . date('Y-m-d H:i:s') . "] Filter: Employee code = $employeeCode\n";
    }

    // Build API URL with parameters
    $params = [
        'from_date' => $fromDate,
        'to_date' => $toDate
    ];

    if ($employeeCode) {
        $params['employee_code'] = $employeeCode;
    }

    $url = $apiUrl . '/attendance/raw?' . http_build_query($params);

    echo "[" . date('Y-m-d H:i:s') . "] Fetching data from API: $url\n";

    // Make API request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || $response === false) {
        throw new Exception("API request failed with HTTP code: $httpCode");
    }

    $apiData = json_decode($response, true);
    $records = $apiData['data'] ?? [];

    echo "[" . date('Y-m-d H:i:s') . "] Fetched " . count($records) . " records from API\n";

    if (empty($records)) {
        echo "[" . date('Y-m-d H:i:s') . "] No records to sync. Exiting.\n";
        exit(0);
    }

    // Connect to HRM database
    $db = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};port={$dbConfig['port']}",
        $dbConfig['username'],
        $dbConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Sync records
    $inserted = 0;
    $updated = 0;
    $errors = 0;

    foreach ($records as $record) {
        try {
            // Transform NULL values to "--:--"
            $inTime = $record['INTime'] ?? '--:--';
            if (is_null($record['INTime']) || $record['INTime'] === '') {
                $inTime = '--:--';
            }

            $outTime = $record['OUTTime'] ?? '--:--';
            if (is_null($record['OUTTime']) || $record['OUTTime'] === '') {
                $outTime = '--:--';
            }

            // Handle NULL values for machine fields (set to empty string if NULL)
            $machine = $record['machine'] ?? '';
            $defaultMachine = $record['default_machine'] ?? '';
            $overrideMachine = $record['override_machine'] ?? '';
            $remark = $record['Remark'] ?? '--';

            // Check if record exists
            $checkStmt = $db->prepare("
                SELECT id FROM raw_attendance
                WHERE Empcode = :empcode
                AND DateString_2 = :date
                LIMIT 1
            ");

            $checkStmt->execute([
                'empcode' => $record['Empcode'],
                'date' => $record['DateString_2']
            ]);

            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update existing record
                $updateStmt = $db->prepare("
                    UPDATE raw_attendance SET
                        INTime = :intime,
                        OUTTime = :outtime,
                        DateString = :datestring,
                        Remark = :remark,
                        machine = :machine,
                        default_machine = :default_machine,
                        override_machine = :override_machine
                    WHERE id = :id
                ");

                $updateStmt->execute([
                    'intime' => $inTime,
                    'outtime' => $outTime,
                    'datestring' => $record['DateString'],
                    'remark' => $remark,
                    'machine' => $machine,
                    'default_machine' => $defaultMachine,
                    'override_machine' => $overrideMachine,
                    'id' => $existing['id']
                ]);

                $updated++;
            } else {
                // Insert new record
                $insertStmt = $db->prepare("
                    INSERT INTO raw_attendance (
                        Empcode, INTime, OUTTime, DateString, DateString_2,
                        Remark, machine, default_machine, override_machine
                    ) VALUES (
                        :empcode, :intime, :outtime, :datestring, :datestring2,
                        :remark, :machine, :default_machine, :override_machine
                    )
                ");

                $insertStmt->execute([
                    'empcode' => $record['Empcode'],
                    'intime' => $inTime,
                    'outtime' => $outTime,
                    'datestring' => $record['DateString'],
                    'datestring2' => $record['DateString_2'],
                    'remark' => $remark,
                    'machine' => $machine,
                    'default_machine' => $defaultMachine,
                    'override_machine' => $overrideMachine
                ]);

                $inserted++;
            }
        } catch (Exception $e) {
            $errors++;
            echo "[ERROR] Failed to sync record for Employee {$record['Empcode']} on {$record['DateString_2']}: {$e->getMessage()}\n";
        }
    }

    echo "[" . date('Y-m-d H:i:s') . "] Sync completed:\n";
    echo "  - Inserted: $inserted records\n";
    echo "  - Updated: $updated records\n";
    echo "  - Errors: $errors records\n";
    echo "  - Total processed: " . ($inserted + $updated + $errors) . " records\n";
} catch (Exception $e) {
    echo "[CRITICAL ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
