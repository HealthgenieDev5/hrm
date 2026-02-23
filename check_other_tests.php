<?php
// Simple script to check other_test_required data
// Load database config from .env file
$envFile = __DIR__ . '/.env';
$config = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $config[trim($key)] = trim($value);
    }
}

// Connect to database
$host = $config['database.default.hostname'] ?? 'localhost';
$username = $config['database.default.username'] ?? 'root';
$password = $config['database.default.password'] ?? '';
$database = $config['database.default.database'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, job_title, other_test_required, created_at FROM rc_job_listing ORDER BY id DESC LIMIT 5");
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo "Recent Job Listings - Other Test Required Field:\n";
    echo str_repeat("=", 80) . "\n\n";

    foreach ($results as $row) {
        echo "ID: {$row->id}\n";
        echo "Job Title: {$row->job_title}\n";
        echo "Created: {$row->created_at}\n";
        echo "Other Test Required:\n";

        if (!empty($row->other_test_required)) {
            $decoded = json_decode($row->other_test_required, true);
            echo json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            echo "(empty)";
        }

        echo "\n" . str_repeat("-", 80) . "\n\n";
    }

    echo "Total records found: " . count($results) . "\n";

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    echo "\nPlease check your database credentials in the .env file.\n";
}
