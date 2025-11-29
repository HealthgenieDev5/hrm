<?php

namespace App\Services;

use App\Models\RawAttendance;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

/**
 * eTime Office Integration Service
 *
 * Handles fetching attendance data from eTime Office APIs across multiple locations.
 * This replaces the HRM portal's eTime integration and runs as part of the Laravel API.
 *
 * Locations Supported:
 * - del (Delhi)
 * - ggn (Gurgaon)
 * - hn (Noida/Heuer)
 * - skbd (Sikandrabad/Bangalore)
 */
class ETimeOfficeService
{
    /**
     * eTime Office location configurations
     * Loaded from environment variables
     */
    private array $locations = [
        'del' => [],
        'ggn' => [],
        'hn' => [],
        'skbd' => [],
    ];

    public function __construct()
    {
        // Load configurations from .env for all locations
        foreach (['del', 'ggn', 'hn', 'skbd'] as $location) {
            $this->locations[$location] = [
                'url' => env("ETIME_{$location}_API_URL"),
                'corporate_id' => env("ETIME_{$location}_CORPORATE_ID"),
                'username' => env("ETIME_{$location}_USERNAME"),
                'password' => env("ETIME_{$location}_PASSWORD"),
            ];
        }
    }

    /**
     * Sync attendance data from all eTime Office locations
     *
     * @param string $employeeCode Employee code or 'ALL' for all employees
     * @param string|null $fromDate Start date (d/m/Y format) or null for start of month
     * @param string|null $toDate End date (d/m/Y format) or null for today
     * @return int Total number of records synced
     */
    public function syncAllLocations(string $employeeCode = 'ALL', ?string $fromDate = null, ?string $toDate = null): int
    {
        $fromDate = $fromDate ?? Carbon::now()->startOfMonth()->format('d/m/Y');
        $toDate = $toDate ?? Carbon::now()->format('d/m/Y');

        $totalSynced = 0;

        foreach (['del', 'ggn', 'hn', 'skbd'] as $location) {
            try {
                Log::info("Starting eTime Office sync for location: {$location}", [
                    'employee_code' => $employeeCode,
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                ]);

                $synced = $this->syncLocation($location, $employeeCode, $fromDate, $toDate);
                $totalSynced += $synced;

                Log::info("Completed sync for {$location}: {$synced} records");
            } catch (Exception $e) {
                Log::error("Failed to sync location {$location}: " . $e->getMessage(), [
                    'exception' => $e,
                ]);
                // Continue with other locations even if one fails
            }
        }

        return $totalSynced;
    }

    /**
     * Sync attendance data from a specific eTime Office location
     *
     * @param string $location Location code (del, ggn, hn, skbd)
     * @param string $employeeCode Employee code or 'ALL'
     * @param string $fromDate Start date (d/m/Y format)
     * @param string $toDate End date (d/m/Y format)
     * @return int Number of records synced
     * @throws Exception If configuration is missing or API call fails
     */
    private function syncLocation(string $location, string $employeeCode, string $fromDate, string $toDate): int
    {
        $config = $this->locations[$location];

        // Validate configuration
        if (empty($config['url']) || empty($config['corporate_id'])) {
            Log::warning("eTime Office configuration missing for location: {$location}");
            return 0;
        }

        // Build Basic Authentication token
        $authString = "{$config['corporate_id']}:{$config['username']}:{$config['password']}:true";
        $authToken = base64_encode($authString);

        try {
            // Call eTime Office API
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Basic ' . $authToken,
                    'Accept' => 'application/json',
                ])
                ->get($config['url'], [
                    'Empcode' => $employeeCode,
                    'FromDate' => $fromDate,
                    'ToDate' => $toDate,
                ]);

            if (!$response->successful()) {
                throw new Exception("API returned status {$response->status()}: {$response->body()}");
            }

            $data = $response->json();

            if (empty($data)) {
                Log::info("No data returned from {$location} for date range");
                return 0;
            }

            // Save punching data to database
            return $this->savePunchingData($data, $location);

        } catch (Exception $e) {
            Log::error("eTime Office API call failed for {$location}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Save punching data to raw_attendance table
     *
     * @param array $records Array of punching records from eTime Office
     * @param string $machine Machine/location identifier
     * @return int Number of records saved
     */
    private function savePunchingData(array $records, string $machine): int
    {
        $savedCount = 0;

        foreach ($records as $record) {
            try {
                // Skip records without essential data
                if (empty($record['Empcode']) || empty($record['DateString'])) {
                    Log::warning('Skipping record with missing Empcode or DateString', ['record' => $record]);
                    continue;
                }

                // Convert DD/MM/YYYY to YYYY-MM-DD for database storage
                $dateString2 = Carbon::createFromFormat('d/m/Y', $record['DateString'])->format('Y-m-d');

                // Use updateOrCreate to prevent duplicates
                RawAttendance::updateOrCreate(
                    [
                        'Empcode' => $record['Empcode'],
                        'DateString_2' => $dateString2,
                    ],
                    [
                        'INTime' => $record['INTime'] ?? null,
                        'OUTTime' => $record['OUTTime'] ?? null,
                        'Remark' => $record['Remark'] ?? null,
                        'DateString' => $record['DateString'],
                        'machine' => $machine,
                        'default_machine' => $machine,
                        'override_machine' => null,
                    ]
                );

                $savedCount++;

            } catch (Exception $e) {
                Log::error("Failed to save punching record: " . $e->getMessage(), [
                    'record' => $record,
                    'exception' => $e,
                ]);
                // Continue with next record
            }
        }

        return $savedCount;
    }

    /**
     * Sync today's attendance data for all employees
     *
     * @return int Number of records synced
     */
    public function syncToday(): int
    {
        $today = Carbon::now()->format('d/m/Y');
        return $this->syncAllLocations('ALL', $today, $today);
    }

    /**
     * Sync current month's attendance data
     *
     * @return int Number of records synced
     */
    public function syncCurrentMonth(): int
    {
        $fromDate = Carbon::now()->startOfMonth()->format('d/m/Y');
        $toDate = Carbon::now()->format('d/m/Y');
        return $this->syncAllLocations('ALL', $fromDate, $toDate);
    }

    /**
     * Sync attendance for a specific employee and date range
     *
     * @param string $employeeCode Employee code
     * @param string $fromDate Start date (d/m/Y format)
     * @param string $toDate End date (d/m/Y format)
     * @return int Number of records synced
     */
    public function syncEmployee(string $employeeCode, string $fromDate, string $toDate): int
    {
        return $this->syncAllLocations($employeeCode, $fromDate, $toDate);
    }

    /**
     * Get punching data for an employee on a specific date
     *
     * @param string $employeeCode Employee code
     * @param string $date Date (Y-m-d format)
     * @return array|null Punching data or null if not found
     */
    public function getPunchingData(string $employeeCode, string $date): ?array
    {
        $record = RawAttendance::where('Empcode', $employeeCode)
            ->where('DateString_2', $date)
            ->first();

        return $record ? $record->toArray() : null;
    }
}
