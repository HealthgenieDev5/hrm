<?php

namespace App\Http\Controllers;

use App\Services\AttendanceProcessingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * Attendance API Controller
 *
 * Provides RESTful endpoints for attendance processing.
 * Implements the unified API approach where one endpoint handles
 * both regular and reduce shift types automatically.
 */
class AttendanceController extends Controller
{
    private AttendanceProcessingService $attendanceService;

    public function __construct(AttendanceProcessingService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Process single day attendance (UNIFIED ENDPOINT)
     *
     * Handles BOTH regular and reduce shift types.
     * The API automatically detects shift type and applies appropriate logic.
     *
     * Request (MINIMAL - only 3 fields):
     * {
     *   "employee_id": 123,
     *   "shift_id": 5,
     *   "date": "2025-11-10"
     * }
     *
     * Response (COMPLETE - all fields HRM needs):
     * {
     *   "status": "success",
     *   "data": {
     *     "employee_id": 123,
     *     "shift_id": 5,
     *     "date": "2025-11-10",
     *     "punch_in_original": "09:00:00",
     *     "punch_out_original": "19:00:00",
     *     "punch_in_adjusted": "09:00:00",
     *     "punch_out_adjusted": "19:00:00" or "15:55:00" (if reduced),
     *     "work_minutes_original": 600,
     *     "work_minutes_adjusted": 600 or 400 (if reduced),
     *     "work_hours_original": "10:00",
     *     "work_hours_adjusted": "10:00" or "06:40" (if reduced),
     *     "reduction_applied": false or true,
     *     "reduction_percentage": 100.00 or 66.67,
     *     "minutes_reduced": 0 or 200,
     *     "late_coming_minutes": 0,
     *     "early_going_minutes": 0,
     *     "deduction_minutes": 0,
     *     "is_present": "yes",
     *     "is_absent": "no",
     *     "is_half_day": "no",
     *     "absent_because_of_work_hours": "no",
     *     "half_day_because_of_work_hours": "no"
     *   }
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processSingle(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|integer',
                'shift_id' => 'required|integer',
                'date' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 400);
            }

            // Process attendance
            $result = $this->attendanceService->processSingleDay(
                $request->employee_id,
                $request->shift_id,
                $request->date
            );

            Log::info('Attendance processed successfully', [
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'reduction_applied' => $result['reduction_applied'],
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ], 200);

        } catch (Exception $e) {
            Log::error('Attendance processing failed', [
                'employee_id' => $request->employee_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process attendance',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process bulk attendance for date range
     *
     * Request:
     * {
     *   "employee_id": 123,
     *   "date_from": "2025-10-01",
     *   "date_to": "2025-10-31"
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processBulk(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|integer',
                'date_from' => 'required|date_format:Y-m-d',
                'date_to' => 'required|date_format:Y-m-d|after_or_equal:date_from',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 400);
            }

            // Process bulk attendance
            $result = $this->attendanceService->processBulk(
                $request->employee_id,
                $request->date_from,
                $request->date_to
            );

            Log::info('Bulk attendance processed', [
                'employee_id' => $request->employee_id,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'total_records' => $result['total_records'],
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ], 200);

        } catch (Exception $e) {
            Log::error('Bulk attendance processing failed', [
                'employee_id' => $request->employee_id ?? null,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process bulk attendance',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Health check endpoint
     *
     * @return JsonResponse
     */
    public function health(): JsonResponse
    {
        try {
            // Check database connection
            \DB::connection()->getPdo();
            $dbStatus = 'connected';
        } catch (Exception $e) {
            $dbStatus = 'disconnected';
        }

        return response()->json([
            'status' => 'healthy',
            'version' => '1.0.0',
            'uptime' => (int) (microtime(true) - LARAVEL_START),
            'database' => $dbStatus,
            'timestamp' => now()->toDateTimeString(),
        ], 200);
    }
}
