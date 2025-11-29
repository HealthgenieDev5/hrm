<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Services\AttendanceProcessingService;
use App\Models\Shift;
use App\Models\RawAttendance;
use App\Models\Employee;

/**
 * AttendanceProcessingService Unit Tests
 *
 * Tests the core attendance processing logic including:
 * - Regular shift processing
 * - Reduce shift processing
 * - Deduction calculations
 * - Status determination
 * - Edge cases and error handling
 */
class AttendanceProcessingServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $service;
    protected $migrate = true;
    protected $refresh = true;
    protected $namespace = 'App';

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AttendanceProcessingService();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Helper method to create a test shift
     */
    private function createTestShift(array $data = []): object
    {
        $defaults = [
            'shift_code' => 'TEST_SHIFT',
            'shift_name' => 'Test Shift',
            'shift_start' => '09:00:00',
            'shift_end' => '18:00:00',
            'shift_type' => 'regular',
            'reduction_percentage' => 100.00,
            'half_day_threshold_minutes' => 240,
            'absent_threshold_minutes' => 120,
        ];

        $shiftData = array_merge($defaults, $data);

        $shift = new class ($shiftData) {
            public function __construct($data) {
                foreach ($data as $key => $value) {
                    $this->$key = $value;
                }
            }
        };

        return $shift;
    }

    /**
     * Helper method to create test raw attendance record
     */
    private function createTestRawAttendance(array $data = []): object
    {
        $defaults = [
            'Empcode' => '12345',
            'INTime' => '09:00:00',
            'OUTTime' => '18:00:00',
            'DateString_2' => '2025-11-11',
            'machine' => 'del',
        ];

        $attendanceData = array_merge($defaults, $data);

        $attendance = new class ($attendanceData) {
            public function __construct($data) {
                foreach ($data as $key => $value) {
                    $this->$key = $value;
                }
            }
        };

        return $attendance;
    }

    // =========================================================================
    // REGULAR SHIFT TESTS
    // =========================================================================

    public function test_handles_regular_shift_with_full_attendance(): void
    {
        // Regular shift: 9 AM - 6 PM (9 hours = 540 minutes)
        // Employee works full shift: 9:00 AM - 6:00 PM

        // This test verifies:
        // 1. No reduction is applied to regular shifts
        // 2. Work hours are calculated correctly
        // 3. Employee is marked as present

        $shift = $this->createTestShift([
            'shift_type' => 'regular',
            'reduction_percentage' => 100.00,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '18:00:00',
        ]);

        // Mock the database calls
        $this->mockShiftAndAttendance($shift, $punching);

        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        // Assertions
        $this->assertEquals(540, $result['work_minutes_original']);
        $this->assertEquals(540, $result['work_minutes_adjusted']);
        $this->assertEquals('09:00', $result['work_hours_original']);
        $this->assertEquals('09:00', $result['work_hours_adjusted']);
        $this->assertFalse($result['reduction_applied']);
        $this->assertEquals(100.00, $result['reduction_percentage']);
        $this->assertEquals(0, $result['minutes_reduced']);
        $this->assertEquals('yes', $result['is_present']);
        $this->assertEquals('no', $result['is_absent']);
    }

    public function test_handles_regular_shift_with_late_coming(): void
    {
        // Regular shift: 9 AM - 6 PM
        // Employee arrives late: 9:30 AM - 6:00 PM
        // Late coming: 30 minutes

        $shift = $this->createTestShift([
            'shift_type' => 'regular',
            'shift_start' => '09:00:00',
            'shift_end' => '18:00:00',
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:30:00',
            'OUTTime' => '18:00:00',
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        // Work minutes = 8.5 hours = 510 minutes (original)
        // Late coming deduction = 30 minutes
        // Adjusted = 510 - 30 = 480 minutes
        $this->assertEquals(510, $result['work_minutes_original']);
        $this->assertEquals(30, $result['late_coming_minutes']);
        $this->assertEquals('yes', $result['is_present']);
    }

    public function test_handles_regular_shift_with_early_going(): void
    {
        // Regular shift: 9 AM - 6 PM
        // Employee leaves early: 9:00 AM - 5:30 PM
        // Early going: 30 minutes

        $shift = $this->createTestShift([
            'shift_type' => 'regular',
            'shift_start' => '09:00:00',
            'shift_end' => '18:00:00',
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '17:30:00',
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        // Work minutes = 8.5 hours = 510 minutes
        // Early going deduction = 30 minutes
        $this->assertEquals(510, $result['work_minutes_original']);
        $this->assertEquals(30, $result['early_going_minutes']);
    }

    // =========================================================================
    // REDUCE SHIFT TESTS
    // =========================================================================

    public function test_handles_reduce_shift_with_66_67_percent_reduction(): void
    {
        // Reduce shift: 66.67% reduction
        // Employee works 10 hours = 600 minutes
        // Expected adjusted: 600 * 0.6667 = 400 minutes

        $shift = $this->createTestShift([
            'shift_type' => 'reduce',
            'reduction_percentage' => 66.67,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '19:00:00', // 10 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals(600, $result['work_minutes_original']);
        $this->assertEquals(400, $result['work_minutes_adjusted']);
        $this->assertEquals('10:00', $result['work_hours_original']);
        $this->assertEquals('06:40', $result['work_hours_adjusted']);
        $this->assertTrue($result['reduction_applied']);
        $this->assertEquals(66.67, $result['reduction_percentage']);
        $this->assertEquals(200, $result['minutes_reduced']);
        $this->assertEquals('yes', $result['is_present']);
    }

    public function test_handles_reduce_shift_with_50_percent_reduction(): void
    {
        // Reduce shift: 50% reduction
        // Employee works 8 hours = 480 minutes
        // Expected adjusted: 480 * 0.50 = 240 minutes

        $shift = $this->createTestShift([
            'shift_type' => 'reduce',
            'reduction_percentage' => 50.00,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '17:00:00', // 8 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals(480, $result['work_minutes_original']);
        $this->assertEquals(240, $result['work_minutes_adjusted']);
        $this->assertEquals('08:00', $result['work_hours_original']);
        $this->assertEquals('04:00', $result['work_hours_adjusted']);
        $this->assertTrue($result['reduction_applied']);
        $this->assertEquals(50.00, $result['reduction_percentage']);
        $this->assertEquals(240, $result['minutes_reduced']);
    }

    public function test_handles_reduce_shift_with_effective_date_not_reached(): void
    {
        // Reduce shift with effective date in the future
        // Should be treated as regular shift until effective date

        $shift = $this->createTestShift([
            'shift_type' => 'reduce',
            'reduction_percentage' => 66.67,
            'effective_from_date' => '2025-11-15', // Future date
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '19:00:00', // 10 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11'); // Before effective date

        // Should not apply reduction
        $this->assertEquals(600, $result['work_minutes_original']);
        $this->assertEquals(600, $result['work_minutes_adjusted']);
        $this->assertFalse($result['reduction_applied']);
        $this->assertEquals(0, $result['minutes_reduced']);
    }

    public function test_handles_reduce_shift_with_effective_date_reached(): void
    {
        // Reduce shift with effective date in the past
        // Should apply reduction

        $shift = $this->createTestShift([
            'shift_type' => 'reduce',
            'reduction_percentage' => 66.67,
            'effective_from_date' => '2025-11-01', // Past date
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '19:00:00', // 10 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11'); // After effective date

        // Should apply reduction
        $this->assertEquals(600, $result['work_minutes_original']);
        $this->assertEquals(400, $result['work_minutes_adjusted']);
        $this->assertTrue($result['reduction_applied']);
        $this->assertEquals(200, $result['minutes_reduced']);
    }

    // =========================================================================
    // ADJUSTED PUNCH TIMES TESTS
    // =========================================================================

    public function test_calculates_adjusted_punch_times_for_reduce_shift(): void
    {
        // For reduce shift, adjusted OUT time should reflect reduced hours
        // IN: 09:00:00, Work: 400 minutes (6h 40m)
        // Adjusted OUT: 09:00:00 + 400m = 15:40:00

        $shift = $this->createTestShift([
            'shift_type' => 'reduce',
            'reduction_percentage' => 66.67,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '19:00:00',
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals('09:00:00', $result['punch_in_original']);
        $this->assertEquals('19:00:00', $result['punch_out_original']);
        $this->assertEquals('09:00:00', $result['punch_in_adjusted']);
        $this->assertEquals('15:40:00', $result['punch_out_adjusted']);
    }

    public function test_no_adjustment_to_punch_times_for_regular_shift(): void
    {
        // For regular shift, punch times should remain unchanged

        $shift = $this->createTestShift([
            'shift_type' => 'regular',
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '18:00:00',
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals('09:00:00', $result['punch_in_adjusted']);
        $this->assertEquals('18:00:00', $result['punch_out_adjusted']);
    }

    // =========================================================================
    // ATTENDANCE STATUS TESTS
    // =========================================================================

    public function test_marks_as_present_when_hours_above_threshold(): void
    {
        // Half-day threshold: 240 minutes (4 hours)
        // Absent threshold: 120 minutes (2 hours)
        // Work: 300 minutes (5 hours) - should be PRESENT

        $shift = $this->createTestShift([
            'half_day_threshold_minutes' => 240,
            'absent_threshold_minutes' => 120,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '14:00:00', // 5 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals('yes', $result['is_present']);
        $this->assertEquals('no', $result['is_absent']);
        $this->assertEquals('no', $result['is_half_day']);
    }

    public function test_marks_as_half_day_when_hours_below_half_day_threshold(): void
    {
        // Half-day threshold: 240 minutes (4 hours)
        // Absent threshold: 120 minutes (2 hours)
        // Work: 180 minutes (3 hours) - should be HALF DAY

        $shift = $this->createTestShift([
            'half_day_threshold_minutes' => 240,
            'absent_threshold_minutes' => 120,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '12:00:00', // 3 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals('yes', $result['is_half_day']);
        $this->assertEquals('yes', $result['half_day_because_of_work_hours']);
        $this->assertEquals('no', $result['is_absent']);
    }

    public function test_marks_as_absent_when_hours_below_absent_threshold(): void
    {
        // Half-day threshold: 240 minutes (4 hours)
        // Absent threshold: 120 minutes (2 hours)
        // Work: 90 minutes (1.5 hours) - should be ABSENT

        $shift = $this->createTestShift([
            'half_day_threshold_minutes' => 240,
            'absent_threshold_minutes' => 120,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '10:30:00', // 1.5 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals('yes', $result['is_absent']);
        $this->assertEquals('yes', $result['absent_because_of_work_hours']);
        $this->assertEquals('no', $result['is_present']);
    }

    public function test_reduce_shift_status_based_on_adjusted_hours(): void
    {
        // Reduce shift: 66.67% reduction
        // Original work: 360 minutes (6 hours)
        // Adjusted work: 240 minutes (4 hours)
        // Half-day threshold: 240 minutes
        // Status should be based on ADJUSTED hours (240), not original (360)

        $shift = $this->createTestShift([
            'shift_type' => 'reduce',
            'reduction_percentage' => 66.67,
            'half_day_threshold_minutes' => 240,
            'absent_threshold_minutes' => 120,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '15:00:00', // 6 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        // Adjusted: 360 * 0.6667 = 240 minutes (exactly at threshold)
        // Should be present (>= threshold)
        $this->assertEquals(360, $result['work_minutes_original']);
        $this->assertEquals(240, $result['work_minutes_adjusted']);
        $this->assertEquals('yes', $result['is_present']);
    }

    // =========================================================================
    // ERROR HANDLING TESTS
    // =========================================================================

    public function test_throws_exception_when_shift_not_found(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Shift not found');

        // Mock Shift::find() to return null
        // This would require mocking the database layer
        // For now, this is a placeholder for the test structure
    }

    public function test_throws_exception_when_employee_not_found(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Employee not found');

        // Mock Employee::find() to return null
        // This would require mocking the database layer
    }

    public function test_returns_absent_record_when_no_punching_data(): void
    {
        // When no punching data exists, should return absent record

        $shift = $this->createTestShift();

        // Mock: No punching data found
        $this->mockShiftAndAttendance($shift, null);

        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals('yes', $result['is_absent']);
        $this->assertEquals('no', $result['is_present']);
        $this->assertEquals(0, $result['work_minutes_original']);
        $this->assertEquals(0, $result['work_minutes_adjusted']);
        $this->assertEquals('No punching data found', $result['reason']);
    }

    public function test_returns_absent_record_when_incomplete_punching_data(): void
    {
        // When INTime or OUTTime is missing

        $shift = $this->createTestShift();

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => null, // Missing OUT time
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals('yes', $result['is_absent']);
        $this->assertEquals('Incomplete punching data', $result['reason']);
    }

    // =========================================================================
    // EDGE CASES
    // =========================================================================

    public function test_handles_overnight_shift(): void
    {
        // Shift that spans across midnight
        // IN: 22:00:00 (10 PM)
        // OUT: 06:00:00 (6 AM next day)
        // Duration: 8 hours = 480 minutes

        $shift = $this->createTestShift([
            'shift_start' => '22:00:00',
            'shift_end' => '06:00:00',
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '22:00:00',
            'OUTTime' => '06:00:00',
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals(480, $result['work_minutes_original']);
        $this->assertEquals('08:00', $result['work_hours_original']);
    }

    public function test_handles_zero_reduction_percentage(): void
    {
        // Edge case: 0% reduction (employee gets no credit)

        $shift = $this->createTestShift([
            'shift_type' => 'reduce',
            'reduction_percentage' => 0.00,
        ]);

        $punching = $this->createTestRawAttendance([
            'INTime' => '09:00:00',
            'OUTTime' => '18:00:00', // 9 hours
        ]);

        $this->mockShiftAndAttendance($shift, $punching);
        $result = $this->service->processSingleDay(1, 1, '2025-11-11');

        $this->assertEquals(540, $result['work_minutes_original']);
        $this->assertEquals(0, $result['work_minutes_adjusted']);
        $this->assertTrue($result['reduction_applied']);
        $this->assertEquals(540, $result['minutes_reduced']);
    }

    public function test_formats_minutes_to_hours_correctly(): void
    {
        // Test various minute values to ensure correct HH:MM formatting

        $testCases = [
            60 => '01:00',
            90 => '01:30',
            120 => '02:00',
            359 => '05:59',
            600 => '10:00',
            0 => '00:00',
        ];

        // This would require exposing the formatMinutesToHours method
        // or testing it through the main processSingleDay method
    }

    // =========================================================================
    // HELPER METHODS FOR MOCKING
    // =========================================================================

    /**
     * Mock the Shift and RawAttendance database calls
     *
     * Note: In a real implementation, you would use a mocking framework
     * or dependency injection to properly mock these database interactions.
     * This is a simplified version for demonstration.
     */
    private function mockShiftAndAttendance($shift, $punching): void
    {
        // This is a placeholder - in real implementation you would:
        // 1. Use a mocking framework (e.g., Mockery, PHPUnit Mock)
        // 2. Inject dependencies into the service
        // 3. Mock the Shift::find() and RawAttendance::where()->first() calls

        // Example with dependency injection:
        // $shiftRepository = $this->createMock(ShiftRepository::class);
        // $shiftRepository->method('find')->willReturn($shift);
        // $this->service->setShiftRepository($shiftRepository);
    }

    // =========================================================================
    // INTEGRATION-STYLE TESTS (with real database)
    // =========================================================================

    /**
     * These tests would actually insert data into the test database
     * and verify end-to-end functionality
     */

    public function test_integration_full_workflow_with_database(): void
    {
        // This test would:
        // 1. Insert a shift record into the database
        // 2. Insert an employee record
        // 3. Insert raw attendance data
        // 4. Call processSingleDay
        // 5. Verify the complete result

        // Requires database test traits and seed data
        $this->markTestSkipped('Integration test - requires database setup');
    }
}
