<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\RawAttendance;
use App\Models\Employee;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Attendance Processing Service
 *
 * Handles all attendance calculation logic including:
 * - Work hours calculation
 * - Reduction application (for reduce shift types)
 * - Deduction calculations (late coming, early going)
 * - Attendance status determination (present/absent/half-day)
 *
 * This is the core service that implements the unified API approach where
 * ONE endpoint handles BOTH regular and reduce shift types automatically.
 */
class AttendanceProcessingService
{
    /**
     * Process attendance for a single day
     *
     * INPUT: Minimal (employee_id, shift_id, date)
     * OUTPUT: Complete (all 18+ fields needed by HRM)
     *
     * @param int $employeeId Employee ID
     * @param int $shiftId Shift ID
     * @param string $date Date (Y-m-d format)
     * @return array Complete attendance data
     * @throws Exception
     */
    public function processSingleDay(int $employeeId, int $shiftId, string $date): array
    {
        // 1. Fetch shift details from database
        $shift = Shift::find($shiftId);
        if (!$shift) {
            throw new Exception("Shift not found: {$shiftId}");
        }

        // 2. Fetch employee details
        $employee = Employee::find($employeeId);
        if (!$employee) {
            throw new Exception("Employee not found: {$employeeId}");
        }

        // 3. Fetch punching data from raw_attendance
        $punching = RawAttendance::where('Empcode', $employee->emp_code ?? $employeeId)
            ->where('DateString_2', $date)
            ->first();

        if (!$punching) {
            // Return absent record if no punching data
            return $this->createAbsentRecord($employeeId, $shiftId, $date, 'No punching data found');
        }

        // 4. Validate punch times
        if (empty($punching->INTime) || empty($punching->OUTTime)) {
            return $this->createAbsentRecord($employeeId, $shiftId, $date, 'Incomplete punching data');
        }

        // 5. Calculate work minutes (original)
        $workMinutesOriginal = $this->calculateWorkMinutes(
            $punching->INTime,
            $punching->OUTTime
        );

        // 6. Calculate deductions (late coming, early going)
        $deductions = $this->calculateDeductions($punching, $shift);
        $workMinutesAfterDeductions = $workMinutesOriginal - $deductions['total_deduction_minutes'];

        // 7. Apply reduction based on shift type
        $reductionData = $this->applyReduction(
            $shift,
            $workMinutesAfterDeductions,
            $date
        );

        $workMinutesAdjusted = $reductionData['adjusted_minutes'];
        $reductionApplied = $reductionData['reduction_applied'];
        $reductionPercentage = $reductionData['reduction_percentage'];
        $minutesReduced = $reductionData['minutes_reduced'];

        // 8. Determine attendance status (present/absent/half-day)
        $status = $this->determineAttendanceStatus($workMinutesAdjusted, $shift);

        // 9. Calculate adjusted punch times (for reduce shift)
        $punchTimes = $this->calculateAdjustedPunchTimes(
            $punching->INTime,
            $punching->OUTTime,
            $workMinutesAdjusted,
            $reductionApplied
        );

        // 10. Build complete response
        return [
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'date' => $date,

            // Punch times
            'punch_in_original' => $punching->INTime,
            'punch_out_original' => $punching->OUTTime,
            'punch_in_adjusted' => $punchTimes['in_adjusted'],
            'punch_out_adjusted' => $punchTimes['out_adjusted'],

            // Work hours
            'work_minutes_original' => $workMinutesOriginal,
            'work_minutes_adjusted' => $workMinutesAdjusted,
            'work_hours_original' => $this->formatMinutesToHours($workMinutesOriginal),
            'work_hours_adjusted' => $this->formatMinutesToHours($workMinutesAdjusted),

            // Reduction metadata
            'reduction_applied' => $reductionApplied,
            'reduction_percentage' => $reductionPercentage,
            'minutes_reduced' => $minutesReduced,

            // Deductions
            'late_coming_minutes' => $deductions['late_coming_minutes'],
            'early_going_minutes' => $deductions['early_going_minutes'],
            'deduction_minutes' => $deductions['total_deduction_minutes'],

            // Status flags
            'is_present' => $status['is_present'],
            'is_absent' => $status['is_absent'],
            'is_half_day' => $status['is_half_day'],
            'absent_because_of_work_hours' => $status['absent_because_of_work_hours'],
            'half_day_because_of_work_hours' => $status['half_day_because_of_work_hours'],

            // Additional metadata
            'shift_type' => $shift->shift_type,
            'shift_code' => $shift->shift_code,
            'machine' => $punching->machine,
        ];
    }

    /**
     * Apply reduction to work minutes based on shift type
     *
     * This method handles BOTH regular and reduce shifts:
     * - Regular shifts: Returns original minutes unchanged
     * - Reduce shifts: Applies percentage-based reduction
     *
     * @param Shift $shift Shift object
     * @param int $workMinutes Original work minutes calculated
     * @param string $date Date being processed
     * @return array Reduction data
     */
    private function applyReduction(Shift $shift, int $workMinutes, string $date): array
    {
        // Check if shift type is 'reduce'
        if ($shift->shift_type !== 'reduce') {
            // Regular shift - no reduction
            return [
                'adjusted_minutes' => $workMinutes,
                'reduction_applied' => false,
                'original_minutes' => $workMinutes,
                'reduction_percentage' => 100.00,
                'minutes_reduced' => 0,
            ];
        }

        // Check effective date (if set)
        if ($shift->effective_from_date) {
            if (Carbon::parse($date)->lt(Carbon::parse($shift->effective_from_date))) {
                // Effective date not reached yet - treat as regular
                return [
                    'adjusted_minutes' => $workMinutes,
                    'reduction_applied' => false,
                    'original_minutes' => $workMinutes,
                    'reduction_percentage' => 100.00,
                    'minutes_reduced' => 0,
                    'reason' => 'Effective date not reached',
                ];
            }
        }

        // Apply reduction calculation
        $reductionPercentage = $shift->reduction_percentage ?? 66.67;
        $adjustedMinutes = (int) round($workMinutes * ($reductionPercentage / 100));
        $minutesReduced = $workMinutes - $adjustedMinutes;

        return [
            'adjusted_minutes' => $adjustedMinutes,
            'reduction_applied' => true,
            'original_minutes' => $workMinutes,
            'reduction_percentage' => $reductionPercentage,
            'minutes_reduced' => $minutesReduced,
        ];
    }

    /**
     * Calculate work minutes from punch times
     *
     * @param string $inTime IN time (H:i:s)
     * @param string $outTime OUT time (H:i:s)
     * @return int Work minutes
     */
    private function calculateWorkMinutes(string $inTime, string $outTime): int
    {
        $in = Carbon::createFromFormat('H:i:s', $inTime);
        $out = Carbon::createFromFormat('H:i:s', $outTime);

        // Handle next day OUT time
        if ($out->lt($in)) {
            $out->addDay();
        }

        return $in->diffInMinutes($out);
    }

    /**
     * Calculate deductions (late coming, early going)
     *
     * @param RawAttendance $punching Punching record
     * @param Shift $shift Shift record
     * @return array Deduction data
     */
    private function calculateDeductions($punching, Shift $shift): array
    {
        $lateComingMinutes = 0;
        $earlyGoingMinutes = 0;

        // Calculate late coming
        $shiftStart = Carbon::createFromFormat('H:i:s', $shift->shift_start);
        $actualIn = Carbon::createFromFormat('H:i:s', $punching->INTime);

        if ($actualIn->gt($shiftStart)) {
            $lateComingMinutes = $shiftStart->diffInMinutes($actualIn);
        }

        // Calculate early going
        $shiftEnd = Carbon::createFromFormat('H:i:s', $shift->shift_end);
        $actualOut = Carbon::createFromFormat('H:i:s', $punching->OUTTime);

        if ($actualOut->lt($shiftEnd)) {
            $earlyGoingMinutes = $actualOut->diffInMinutes($shiftEnd);
        }

        $totalDeduction = $lateComingMinutes + $earlyGoingMinutes;

        return [
            'late_coming_minutes' => $lateComingMinutes,
            'early_going_minutes' => $earlyGoingMinutes,
            'total_deduction_minutes' => $totalDeduction,
        ];
    }

    /**
     * Determine attendance status based on adjusted work minutes
     *
     * @param int $adjustedMinutes Adjusted work minutes
     * @param Shift $shift Shift object
     * @return array Status flags
     */
    private function determineAttendanceStatus(int $adjustedMinutes, Shift $shift): array
    {
        $halfDayThreshold = $shift->half_day_threshold_minutes ?? 240; // 4 hours
        $absentThreshold = $shift->absent_threshold_minutes ?? 120; // 2 hours

        $status = [
            'is_present' => 'yes',
            'is_absent' => 'no',
            'is_half_day' => 'no',
            'absent_because_of_work_hours' => 'no',
            'half_day_because_of_work_hours' => 'no',
        ];

        if ($adjustedMinutes < $absentThreshold) {
            $status['absent_because_of_work_hours'] = 'yes';
            $status['is_absent'] = 'yes';
            $status['is_present'] = 'no';
        } elseif ($adjustedMinutes < $halfDayThreshold) {
            $status['half_day_because_of_work_hours'] = 'yes';
            $status['is_half_day'] = 'yes';
        }

        return $status;
    }

    /**
     * Calculate adjusted punch times (for reduce shift)
     *
     * @param string $inTime Original IN time
     * @param string $outTime Original OUT time
     * @param int $adjustedMinutes Adjusted work minutes
     * @param bool $reductionApplied Whether reduction was applied
     * @return array Adjusted punch times
     */
    private function calculateAdjustedPunchTimes(string $inTime, string $outTime, int $adjustedMinutes, bool $reductionApplied): array
    {
        if (!$reductionApplied) {
            // No reduction - times unchanged
            return [
                'in_adjusted' => $inTime,
                'out_adjusted' => $outTime,
            ];
        }

        // For reduce shift: adjust OUT time to reflect reduced hours
        $in = Carbon::createFromFormat('H:i:s', $inTime);
        $outAdjusted = $in->copy()->addMinutes($adjustedMinutes);

        return [
            'in_adjusted' => $inTime,
            'out_adjusted' => $outAdjusted->format('H:i:s'),
        ];
    }

    /**
     * Format minutes to HH:MM format
     *
     * @param int $minutes Minutes
     * @return string Formatted time
     */
    private function formatMinutesToHours(int $minutes): string
    {
        $hours = (int) floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }

    /**
     * Create absent record when no punching data found
     *
     * @param int $employeeId Employee ID
     * @param int $shiftId Shift ID
     * @param string $date Date
     * @param string $reason Reason for absence
     * @return array Absent record data
     */
    private function createAbsentRecord(int $employeeId, int $shiftId, string $date, string $reason): array
    {
        return [
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'date' => $date,
            'punch_in_original' => null,
            'punch_out_original' => null,
            'punch_in_adjusted' => null,
            'punch_out_adjusted' => null,
            'work_minutes_original' => 0,
            'work_minutes_adjusted' => 0,
            'work_hours_original' => '00:00',
            'work_hours_adjusted' => '00:00',
            'reduction_applied' => false,
            'reduction_percentage' => 100.00,
            'minutes_reduced' => 0,
            'late_coming_minutes' => 0,
            'early_going_minutes' => 0,
            'deduction_minutes' => 0,
            'is_present' => 'no',
            'is_absent' => 'yes',
            'is_half_day' => 'no',
            'absent_because_of_work_hours' => 'yes',
            'half_day_because_of_work_hours' => 'no',
            'reason' => $reason,
        ];
    }

    /**
     * Process bulk attendance for date range
     *
     * @param int $employeeId Employee ID
     * @param string $dateFrom Start date (Y-m-d)
     * @param string $dateTo End date (Y-m-d)
     * @return array Bulk attendance data
     */
    public function processBulk(int $employeeId, string $dateFrom, string $dateTo): array
    {
        $records = [];
        $currentDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);

        while ($currentDate->lte($endDate)) {
            try {
                // Fetch shift for this date (you may need to implement shift_per_day logic)
                // For now, assuming shift_id needs to be passed or fetched
                // This is a simplified version
                $dateStr = $currentDate->format('Y-m-d');

                // You would fetch the actual shift_id from shift_per_day table here
                // For demonstration, we'll skip dates without shift assignment

                Log::info("Processing bulk attendance for {$employeeId} on {$dateStr}");

                // Process each day
                // $dayRecord = $this->processSingleDay($employeeId, $shiftId, $dateStr);
                // $records[] = $dayRecord;

            } catch (Exception $e) {
                Log::error("Failed to process bulk date {$dateStr}: " . $e->getMessage());
            }

            $currentDate->addDay();
        }

        return [
            'employee_id' => $employeeId,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'records' => $records,
            'total_records' => count($records),
        ];
    }
}
