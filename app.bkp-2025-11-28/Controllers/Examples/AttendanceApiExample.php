<?php

namespace App\Controllers\Examples;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Example: How to fetch attendance data using the Laravel Attendance API
 *
 * This controller demonstrates different ways to fetch raw attendance data
 * from the external Laravel Attendance API.
 *
 * API Base URL: http://hrm-attendance-api.test/api/v1
 * Documentation: See ATTENDANCE_API_USAGE.md in project root
 */
class AttendanceApiExample extends BaseController
{
    private string $apiBaseUrl;

    public function __construct()
    {
        // Load from .env
        $this->apiBaseUrl = getenv('ATTENDANCE_API_URL') ?: 'http://hrm-attendance-api.test/api/v1';
    }

    /**
     * Example 1: Fetch attendance for a single day
     *
     * URL: /examples/attendance-api/single
     */
    public function fetchSingleDay(): ResponseInterface
    {
        $employeeId = 2;
        $shiftId = 1;
        $date = '2025-11-15';

        $result = $this->callApi('/attendance/process/single', [
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'date' => $date,
        ]);

        if ($result['success']) {
            $attendance = $result['data'];

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Attendance fetched successfully',
                'data' => [
                    'employee_id' => $attendance['employee_id'],
                    'date' => $attendance['date'],
                    'punch_in' => $attendance['punch_in_adjusted'],
                    'punch_out' => $attendance['punch_out_adjusted'],
                    'work_hours' => $attendance['work_hours_adjusted'],
                    'is_present' => $attendance['is_present'],
                    'is_absent' => $attendance['is_absent'],
                    'late_coming' => $attendance['late_coming_minutes'],
                    'reduction_applied' => $attendance['reduction_applied'],
                    'reduction_percentage' => $attendance['reduction_percentage'] ?? 0,
                ],
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $result['error'],
            ]);
        }
    }

    /**
     * Example 2: Fetch attendance for a date range (bulk)
     *
     * URL: /examples/attendance-api/bulk
     */
    public function fetchBulk(): ResponseInterface
    {
        $employeeId = 2;
        $dateFrom = '2025-11-01';
        $dateTo = '2025-11-15';

        $result = $this->callApi('/attendance/process/bulk', [
            'employee_id' => $employeeId,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        if ($result['success']) {
            $attendanceRecords = $result['data'];

            // Process the bulk data
            $summary = [
                'total_days' => count($attendanceRecords),
                'present_days' => 0,
                'absent_days' => 0,
                'total_work_hours' => 0,
            ];

            foreach ($attendanceRecords as $record) {
                if ($record['is_present'] === 'yes') {
                    $summary['present_days']++;
                } else {
                    $summary['absent_days']++;
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Bulk attendance fetched successfully',
                'summary' => $summary,
                'records' => $attendanceRecords,
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $result['error'],
            ]);
        }
    }

    /**
     * Example 3: Get raw punching data only
     *
     * URL: /examples/attendance-api/raw?employee_id=2&from=2025-11-01&to=2025-11-15
     */
    public function getRawPunchingData(): ResponseInterface
    {
        $employeeId = $this->request->getGet('employee_id');
        $dateFrom = $this->request->getGet('from');
        $dateTo = $this->request->getGet('to');

        if (!$employeeId || !$dateFrom || !$dateTo) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Missing required parameters: employee_id, from, to',
            ]);
        }

        $url = "/attendance/raw?employee_id={$employeeId}&date_from={$dateFrom}&date_to={$dateTo}";
        $result = $this->callApi($url, null, 'GET');

        if ($result['success']) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $result['data'],
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $result['error'],
            ]);
        }
    }

    /**
     * Example 4: Check API health
     *
     * URL: /examples/attendance-api/health
     */
    public function checkHealth(): ResponseInterface
    {
        $result = $this->callApi('/health', null, 'GET');

        if ($result['success']) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'API is healthy',
                'api_status' => $result['data'],
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'API is down or unreachable',
                'error' => $result['error'],
            ]);
        }
    }

    /**
     * Example 5: Process attendance for current month
     *
     * URL: /examples/attendance-api/current-month?employee_id=2
     */
    public function processCurrentMonth(): ResponseInterface
    {
        $employeeId = $this->request->getGet('employee_id');

        if (!$employeeId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Missing required parameter: employee_id',
            ]);
        }

        // Get current month date range
        $dateFrom = date('Y-m-01'); // First day of current month
        $dateTo = date('Y-m-d');    // Today

        $result = $this->callApi('/attendance/process/bulk', [
            'employee_id' => $employeeId,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        if ($result['success']) {
            $records = $result['data'];

            // Calculate summary
            $summary = $this->calculateSummary($records);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Current month attendance processed',
                'period' => [
                    'from' => $dateFrom,
                    'to' => $dateTo,
                ],
                'summary' => $summary,
                'records' => $records,
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $result['error'],
            ]);
        }
    }

    /**
     * Helper: Make API call
     *
     * @param string $endpoint API endpoint (e.g., '/attendance/process/single')
     * @param array|null $data POST data (null for GET requests)
     * @param string $method HTTP method (POST or GET)
     * @return array ['success' => bool, 'data' => array|null, 'error' => string|null]
     */
    private function callApi(string $endpoint, ?array $data = null, string $method = 'POST'): array
    {
        $url = $this->apiBaseUrl . $endpoint;
        $timeout = (int) (getenv('ATTENDANCE_API_TIMEOUT') ?: 30);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        if ($method === 'POST' && $data !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
            ]);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
            ]);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            log_message('error', "Attendance API CURL Error: {$curlError}");
            return [
                'success' => false,
                'data' => null,
                'error' => "Connection failed: {$curlError}",
            ];
        }

        if ($httpCode !== 200) {
            log_message('error', "Attendance API returned HTTP {$httpCode}: {$response}");
            return [
                'success' => false,
                'data' => null,
                'error' => "API returned HTTP {$httpCode}",
            ];
        }

        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', "Attendance API returned invalid JSON: {$response}");
            return [
                'success' => false,
                'data' => null,
                'error' => 'Invalid JSON response',
            ];
        }

        // Check if response has 'status' field
        if (isset($result['status']) && $result['status'] === 'success') {
            return [
                'success' => true,
                'data' => $result['data'] ?? $result,
                'error' => null,
            ];
        }

        // For endpoints that don't have 'status' field (like /health)
        if (!isset($result['status'])) {
            return [
                'success' => true,
                'data' => $result,
                'error' => null,
            ];
        }

        return [
            'success' => false,
            'data' => null,
            'error' => $result['message'] ?? 'Unknown error',
        ];
    }

    /**
     * Helper: Calculate summary from attendance records
     */
    private function calculateSummary(array $records): array
    {
        $summary = [
            'total_days' => count($records),
            'present_days' => 0,
            'absent_days' => 0,
            'half_days' => 0,
            'total_work_minutes' => 0,
            'total_late_coming_minutes' => 0,
            'total_early_going_minutes' => 0,
            'days_with_reduction' => 0,
        ];

        foreach ($records as $record) {
            if ($record['is_present'] === 'yes') {
                $summary['present_days']++;
            } elseif ($record['is_absent'] === 'yes') {
                $summary['absent_days']++;
            }

            if ($record['is_half_day'] === 'yes') {
                $summary['half_days']++;
            }

            $summary['total_work_minutes'] += $record['work_minutes_adjusted'] ?? 0;
            $summary['total_late_coming_minutes'] += $record['late_coming_minutes'] ?? 0;
            $summary['total_early_going_minutes'] += $record['early_going_minutes'] ?? 0;

            if ($record['reduction_applied'] ?? false) {
                $summary['days_with_reduction']++;
            }
        }

        // Convert minutes to hours
        $summary['total_work_hours'] = round($summary['total_work_minutes'] / 60, 2);

        return $summary;
    }
}
