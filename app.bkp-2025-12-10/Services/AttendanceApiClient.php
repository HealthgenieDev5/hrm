<?php

namespace App\Services;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;
use Exception;

/**
 * Attendance API Client Service
 *
 * Provides methods to interact with the Laravel Attendance API.
 * Handles authentication, request/response processing, and fallback logic.
 *
 * Usage:
 * $client = new AttendanceApiClient();
 * $result = $client->processSingleDay($employeeId, $shiftId, $date);
 */
class AttendanceApiClient
{
    protected string $apiBaseUrl;
    protected string $apiKey;
    protected string $apiSecret;
    protected ?string $token = null;
    protected CURLRequest $client;
    protected int $timeout;
    protected bool $fallbackToLocal;

    public function __construct()
    {
        $this->apiBaseUrl = getenv('ATTENDANCE_API_URL') ?: 'http://localhost:8001/api/v1';
        $this->apiKey = getenv('ATTENDANCE_API_KEY') ?: '';
        $this->apiSecret = getenv('ATTENDANCE_API_SECRET') ?: '';
        $this->timeout = (int) (getenv('ATTENDANCE_API_TIMEOUT') ?: 30);
        $this->fallbackToLocal = filter_var(
            getenv('ATTENDANCE_API_FALLBACK_TO_LOCAL'),
            FILTER_VALIDATE_BOOLEAN
        );

        $this->client = Services::curlrequest([
            'baseURI' => $this->apiBaseUrl,
            'timeout' => $this->timeout,
            'http_errors' => false,
        ]);
    }

    /**
     * Authenticate and get JWT token
     *
     * @return string JWT token
     * @throws Exception If authentication fails
     */
    protected function authenticate(): string
    {
        // Return cached token if available
        if ($this->token) {
            return $this->token;
        }

        try {
            $response = $this->client->post('/auth/token', [
                'json' => [
                    'api_key' => $this->apiKey,
                    'secret' => $this->apiSecret,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new Exception('Authentication failed: ' . $response->getBody());
            }

            $data = json_decode($response->getBody(), true);

            if (empty($data['token'])) {
                throw new Exception('No token received from API');
            }

            $this->token = $data['token'];
            return $this->token;

        } catch (Exception $e) {
            log_message('error', 'API Authentication failed: ' . $e->getMessage());
            throw new Exception('Failed to authenticate with Attendance API: ' . $e->getMessage());
        }
    }

    /**
     * Process single day attendance (MINIMAL API)
     *
     * INPUT: Only 3 fields (employee_id, shift_id, date)
     * OUTPUT: Complete attendance data (18+ fields) OR null if no punch data
     *
     * @param int $employeeId Employee ID
     * @param int $shiftId Shift ID
     * @param string $date Date (Y-m-d format)
     * @return array|null Complete attendance data, or null if no punch data exists
     * @throws Exception If API call fails (real error, not "no data")
     */
    public function processSingleDay(int $employeeId, int $shiftId, string $date): ?array
    {
        try {
            $token = $this->authenticate();

            $response = $this->client->post('/attendance/process/single', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'employee_id' => $employeeId,
                    'shift_id' => $shiftId,
                    'date' => $date,
                ],
            ]);

            // Handle 404 - No punch data (not an error)
            if ($response->getStatusCode() === 404) {
                $result = json_decode($response->getBody(), true);
                log_message('info', "No punch data for employee {$employeeId} on {$date}: " . ($result['message'] ?? 'No data'));
                return null; // Let HRM portal handle absent logic
            }

            // Handle other non-200 responses as errors
            if ($response->getStatusCode() !== 200) {
                throw new Exception('API returned status ' . $response->getStatusCode() . ': ' . $response->getBody());
            }

            $result = json_decode($response->getBody(), true);

            if (empty($result['data'])) {
                throw new Exception('No data received from API');
            }

            log_message('info', "Attendance processed via API for employee {$employeeId} on {$date}");

            return $result['data'];

        } catch (Exception $e) {
            log_message('error', "API call failed for employee {$employeeId}: " . $e->getMessage());

            // Fallback to local processing if enabled
            if ($this->fallbackToLocal) {
                log_message('warning', "Falling back to local processing for employee {$employeeId}");
                return $this->fallbackToLocalProcessing($employeeId, $shiftId, $date);
            }

            throw $e;
        }
    }

    /**
     * Process bulk attendance for date range
     *
     * @param int $employeeId Employee ID
     * @param string $dateFrom Start date (Y-m-d)
     * @param string $dateTo End date (Y-m-d)
     * @return array Bulk attendance data
     * @throws Exception If API call fails
     */
    public function processBulk(int $employeeId, string $dateFrom, string $dateTo): array
    {
        try {
            $token = $this->authenticate();

            $response = $this->client->post('/attendance/process/bulk', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'employee_id' => $employeeId,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new Exception('API returned status ' . $response->getStatusCode() . ': ' . $response->getBody());
            }

            $result = json_decode($response->getBody(), true);

            if (empty($result['data'])) {
                throw new Exception('No data received from API');
            }

            log_message('info', "Bulk attendance processed via API for employee {$employeeId}");

            return $result['data'];

        } catch (Exception $e) {
            log_message('error', "Bulk API call failed for employee {$employeeId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fallback to local processing if API is down
     *
     * Uses the existing HRM portal's ProcessorHelper logic
     *
     * @param int $employeeId Employee ID
     * @param int $shiftId Shift ID
     * @param string $date Date
     * @return array Attendance data (simplified)
     */
    protected function fallbackToLocalProcessing(int $employeeId, int $shiftId, string $date): array
    {
        // Load existing ProcessorHelper or similar logic
        // This is a placeholder - implement based on your existing HRM logic

        helper('processor'); // Load your existing helper

        try {
            // Call existing local calculation logic
            // $workMinutes = ProcessorHelper::calculateAttendance($employeeId, $shiftId, $date);

            // For now, return a basic structure
            return [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'date' => $date,
                'work_minutes_adjusted' => 0,
                'work_hours_adjusted' => '00:00',
                'reduction_applied' => false,
                'source' => 'fallback',
                'is_present' => 'no',
                'is_absent' => 'yes',
                'reason' => 'Fallback processing - API unavailable',
            ];

        } catch (Exception $e) {
            log_message('error', 'Fallback processing also failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check API health
     *
     * @return bool True if API is healthy
     */
    public function checkHealth(): bool
    {
        try {
            $response = $this->client->get('/health');
            return $response->getStatusCode() === 200;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get API status information
     *
     * @return array API status data
     */
    public function getStatus(): array
    {
        try {
            $response = $this->client->get('/health');

            if ($response->getStatusCode() !== 200) {
                return [
                    'status' => 'down',
                    'message' => 'API returned non-200 status',
                ];
            }

            return json_decode($response->getBody(), true);

        } catch (Exception $e) {
            return [
                'status' => 'down',
                'message' => $e->getMessage(),
            ];
        }
    }
}
