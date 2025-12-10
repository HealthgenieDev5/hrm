<?php

namespace App\Pipes\AttendanceProcessor;

use App\Controllers\Attendance\Processor;
// use App\Controllers\ProcessedPunchingData\ProcessedPunchingData;
use App\Models\CompOffCreditModel;
use App\Models\CompOffMinutesUtilizedModel;
use App\Models\DeductionModel;
use App\Models\EmployeeModel;
use App\Models\FixedRhModel;
use App\Models\HolidayModel;
use App\Models\LeaveBalanceModel;
use App\Models\LeaveRequestsModel;
use App\Models\OdRequestsModel;
use App\Models\PreFinalPaidDaysModel;
use App\Models\ShiftModel;
use App\Models\ShiftOverrideModel;
use App\Models\SpecialHolidayEmployeesModel;
use App\Models\WaveOffHalfDayWhoDidNotWorkForHalfDayModel;
use App\Models\WaveOffModel;
use PhpParser\Node\Stmt\TryCatch;
use DateTime;

class ProcessorHelper
{
    public static function is_holiday($date, $returnType = 'data', $machine = null, $company_id = null)
    {
        $reject_array = array(
            array(
                'machine' => 'hn',
                'company_id' => '4',
                'date' => '2023-09-07',
            ),
        );

        foreach ($reject_array as $reject) {
            if (
                isset($machine) && !empty($machine)
                && isset($company_id) && !empty($company_id)
                && $reject['machine'] == $machine && $reject['company_id'] == $company_id && $reject['date'] == $date
            ) {
                if ($returnType !== 'data') {
                    return 'no';
                } else {
                    return null;
                }
            }
        }

        $HolidayModel = new HolidayModel();
        $TheHoliday = $HolidayModel->where('holiday_date =', $date)->where('holiday_code !=', 'RH')->where('holiday_code !=', 'SPL HL')->first();
        if (!empty($TheHoliday)) {
            if ($returnType !== 'data') {
                return 'yes';
            } else {
                return $TheHoliday;
            }
        } else {
            if ($returnType !== 'data') {
                return 'no';
            } else {
                return null;
            }
        }
    }

    public static function get_punch_time_between_shift_including_od($in_time__Raw, $out_time__Raw, $shift_start__Raw, $shift_end__Raw, $date, $employee_id)
    {
        // $start_times = array();
        // $end_times = array();

        // if (!empty($in_time__Raw) && !empty($out_time__Raw)) {

        //     $in_time__Raw = $date . ' ' . $in_time__Raw;
        //     $out_time__Raw = $date . ' ' . $out_time__Raw;
        //     $shift_start__Raw = $date . ' ' . $shift_start__Raw;
        //     $shift_end__Raw = $date . ' ' . $shift_end__Raw;

        //     if (strtotime($out_time__Raw) < strtotime($in_time__Raw)) {
        //         $out_time__Raw = date('Y-m-d H:i:s', strtotime($out_time__Raw . ' +1 days'));
        //     }


        //     $start_times[] = $in_time__Raw;
        //     $end_times[] = $out_time__Raw;
        // }



        // $OdRequestsModel = new OdRequestsModel();
        // $ods    = $OdRequestsModel
        //     ->where('employee_id =', $employee_id)
        //     ->where('status =', 'approved')
        //     ->where("
        //             (
        //                 ( date(estimated_from_date_time) between '" . $date . "' and '" . $date . "')
        //                 or ( date(estimated_to_date_time) between '" . $date . "' and '" . $date . "')
        //                 or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
        //                 or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
        //                 or ( date(actual_from_date_time) between '" . $date . "' and '" . $date . "')
        //                 or ( date(actual_to_date_time) between '" . $date . "' and '" . $date . "')
        //                 or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
        //                 or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
        //             )
        //         ")
        //     ->findAll();

        // foreach ($ods as $od) {
        //     $od_start_date_time = (!empty($od['actual_from_date_time']) && !empty($od['actual_to_date_time'])) ? $od['actual_from_date_time'] : $od['estimated_from_date_time'];
        //     $od_end_date_time = (!empty($od['actual_from_date_time']) && !empty($od['actual_to_date_time'])) ? $od['actual_to_date_time'] : $od['estimated_to_date_time'];

        //     $start_times[] = (date('Y-m-d', strtotime($od_start_date_time)) == $date) ? date('Y-m-d H:i:s', strtotime($od_start_date_time)) : date('Y-m-d H:i:s', strtotime($shift_start__Raw));
        //     $end_times[] = (date('Y-m-d', strtotime($od_end_date_time)) == $date) ? date('Y-m-d H:i:s', strtotime($od_end_date_time)) : date('Y-m-d H:i:s', strtotime($shift_end__Raw));
        // }

        // $start_times_unfiltered = [];
        // foreach ($start_times as $a) {
        //     $start_times_unfiltered[] = !empty($a) ? strtotime($a) : $a;
        // }

        // $end_times_unfiltered = [];
        // foreach ($end_times as $b) {
        //     $end_times_unfiltered[] = !empty($b) ? strtotime($b) : $b;
        // }

        // $start_times_filtered = array_filter($start_times_unfiltered);
        // $end_times_filtered = array_filter($end_times_unfiltered);
        // $start_time = !empty($start_times_filtered) ? date('Y-m-d H:i:s', min($start_times_filtered)) : null;
        // $end_time = !empty($end_times_filtered) ? date('Y-m-d H:i:s', max($end_times_filtered)) : null;

        $punch_time_including_od = self::get_punch_time_including_od(
            $in_time__Raw,
            $out_time__Raw,
            $shift_start__Raw,
            $shift_end__Raw,
            $date,
            $employee_id
        );
        $start_time = $punch_time_including_od[0];
        $end_time = $punch_time_including_od[1];

        if (!empty($start_time) && !empty($end_time) && strtotime($end_time) < strtotime($start_time)) {
            $end_time = date('Y-m-d H:i:s', strtotime($end_time . ' +1 days'));
        }

        if (strtotime($shift_end__Raw) < strtotime($shift_start__Raw)) {
            $shift_end__Raw = date('Y-m-d H:i:s', strtotime($shift_end__Raw . ' +1 days'));
        }

        if (!empty($start_time) && strtotime($start_time) < strtotime($shift_start__Raw)) {
            $startTimeNew = date('H:i:s', strtotime($shift_start__Raw));
        } elseif (!empty($start_time)) {
            $startTimeNew = date('H:i:s', strtotime($start_time));
        } else {
            $startTimeNew = null;
        }

        if (!empty($end_time) && strtotime($end_time) > strtotime($shift_end__Raw)) {
            $endTimeNew = date('H:i:s', strtotime($shift_end__Raw));
        } elseif (!empty($end_time)) {
            $endTimeNew = date('H:i:s', strtotime($end_time));
        } else {
            $endTimeNew = null;
        }

        // if ($date == '2025-04-25') {
        //     dd(
        //         $start_time,
        //         $shift_start__Raw,
        //         $startTimeNew,
        //         $end_time,
        //         $shift_end__Raw,
        //         $endTimeNew
        //     );
        // }

        // if ($date == '2025-04-25') {
        //     dd($startTimeNew, $endTimeNew);
        // }

        return [$startTimeNew, $endTimeNew];
    }


    public static function get_punch_time_including_od($in_time__Raw, $out_time__Raw, $shift_start__Raw, $shift_end__Raw, $date, $employee_id)
    {

        $in_time__Raw = !empty($in_time__Raw) ? $date . ' ' . $in_time__Raw : null;
        $out_time__Raw = !empty($out_time__Raw) ? $date . ' ' . $out_time__Raw : null;
        $shift_start__Raw = $date . ' ' . $shift_start__Raw;
        $shift_end__Raw = $date . ' ' . $shift_end__Raw;

        if (!empty($in_time__Raw) && !empty($out_time__Raw) && strtotime($out_time__Raw) < strtotime($in_time__Raw)) {
            $out_time__Raw = date('Y-m-d H:i:s', strtotime($out_time__Raw . ' +1 days'));
        }

        $start_times = array($in_time__Raw);
        $end_times = array($out_time__Raw);

        // Detect if this is a night shift (crosses midnight)
        $isNightShift = strtotime($shift_start__Raw) > strtotime($shift_end__Raw);

        // For night shifts, we need to include ODs from the next day as well
        $next_date = date('Y-m-d', strtotime($date . ' +1 day'));

        $OdRequestsModel = new OdRequestsModel();

        // Build the date condition based on shift type
        if ($isNightShift) {
            // For night shifts, include both current date and next date ODs
            $dateCondition = "
                    (
                        ( date(estimated_from_date_time) between '" . $date . "' and '" . $next_date . "')
                        or ( date(estimated_to_date_time) between '" . $date . "' and '" . $next_date . "')
                        or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                        or ( '" . $next_date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                        or ( date(actual_from_date_time) between '" . $date . "' and '" . $next_date . "')
                        or ( date(actual_to_date_time) between '" . $date . "' and '" . $next_date . "')
                        or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                        or ( '" . $next_date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                    )
                ";
        } else {
            // For day shifts, use original logic (current date only)
            $dateCondition = "
                    (
                        ( date(estimated_from_date_time) between '" . $date . "' and '" . $date . "')
                        or ( date(estimated_to_date_time) between '" . $date . "' and '" . $date . "')
                        or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                        or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                        or ( date(actual_from_date_time) between '" . $date . "' and '" . $date . "')
                        or ( date(actual_to_date_time) between '" . $date . "' and '" . $date . "')
                        or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                        or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                    )
                ";
        }

        $ods = $OdRequestsModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'approved')
            ->where($dateCondition)
            ->findAll();

        foreach ($ods as $od) {

            $od_start_date_time = (!empty($od['actual_from_date_time']) && !empty($od['actual_to_date_time'])) ? $od['actual_from_date_time'] : $od['estimated_from_date_time'];
            $od_end_date_time = (!empty($od['actual_from_date_time']) && !empty($od['actual_to_date_time'])) ? $od['actual_to_date_time'] : $od['estimated_to_date_time'];

            // For night shifts, filter and cap ODs based on working day period
            if ($isNightShift) {
                // For night shifts, the "working day" extends from shift start on current date
                // until the next shift starts on the next day
                $current_shift_start = strtotime($shift_start__Raw);

                // Calculate when the next shift starts (same time on next day)
                $next_day = date('Y-m-d', strtotime($date . ' +1 day'));
                $next_shift_start = strtotime($next_day . ' ' . date('H:i:s', strtotime($shift_start__Raw)));

                $od_start_timestamp = strtotime($od_start_date_time);
                $od_end_timestamp = strtotime($od_end_date_time);

                // Check if OD belongs to the current working day
                // Include if OD starts within working day period OR OD spans into working day
                $od_starts_in_working_day = ($od_start_timestamp >= $current_shift_start && $od_start_timestamp < $next_shift_start);
                $od_spans_into_working_day = ($od_start_timestamp < $current_shift_start && $od_end_timestamp > $current_shift_start);

                if (!$od_starts_in_working_day && !$od_spans_into_working_day) {
                    // This OD doesn't belong to current working day, skip it
                    continue;
                }

                // Cap OD end time if it extends beyond next shift start
                if ($od_end_timestamp >= $next_shift_start) {
                    // Cap at 1 second before next shift starts (19:59:59)
                    $od_end_date_time = date('Y-m-d H:i:s', $next_shift_start - 1);
                }
            }

            if ($od['international'] !== 'yes') {
                $od_start_is_on_date = strtotime(date('Y-m-d', strtotime($od_start_date_time))) == strtotime($date);
                $od_end_is_on_date = strtotime(date('Y-m-d', strtotime($od_end_date_time))) == strtotime($date);

                // For night shifts, allow OD to extend to next day naturally
                if ($isNightShift) {
                    // OD starts and ends on the same date (current date being processed)
                    if ($od_start_is_on_date && $od_end_is_on_date) {
                        // Keep actual OD times as-is
                        $od_start_date_time = $od_start_date_time;
                        $od_end_date_time = $od_end_date_time;
                    }
                    // OD starts on the date but ends on next day - keep the actual end time
                    elseif ($od_start_is_on_date && !$od_end_is_on_date) {
                        $od_start_date_time = $od_start_date_time;
                        $od_end_date_time = $od_end_date_time; // Keep actual end time for night shift
                    }
                    // OD starts before the date but ends on the date
                    elseif (!$od_start_is_on_date && $od_end_is_on_date) {
                        $od_start_date_time = date('Y-m-d H:i:s', strtotime($date . ' ' . date('H:i:s', strtotime($shift_start__Raw))));
                        $od_end_date_time = $od_end_date_time;
                    }
                    // OD spans multiple days, neither start nor end is on the date
                    // Current date falls within a multi-day OD period
                    elseif (!$od_start_is_on_date && !$od_end_is_on_date) {
                        // Check if this OD is from next day (14th) when processing current date (13th)
                        $od_start_date = date('Y-m-d', strtotime($od_start_date_time));
                        $next_day = date('Y-m-d', strtotime($date . ' +1 day'));

                        if ($od_start_date == $next_day) {
                            // This OD starts on the next day - use actual start time
                            $od_start_date_time = $od_start_date_time;
                            $od_end_date_time = $od_end_date_time;
                        } else {
                            // OD started before current date, use shift start time for this date
                            $od_start_date_time = date('Y-m-d H:i:s', strtotime($date . ' ' . date('H:i:s', strtotime($shift_start__Raw))));

                            // For end time, check if OD actually ends before the next shift end
                            // If OD ends on next day (date + 1), use the actual OD end time
                            // Otherwise, OD extends beyond next day, so cap at shift end time
                            $od_end_date = date('Y-m-d', strtotime($od_end_date_time));

                            if ($od_end_date == $next_day) {
                                // OD ends on the next day - use actual OD end time
                                $od_end_date_time = $od_end_date_time;
                            } else {
                                // OD extends beyond next day - use shift end time
                                $od_end_date_time = date('Y-m-d H:i:s', strtotime($date . ' +1 day ' . date('H:i:s', strtotime($shift_end__Raw))));
                            }
                        }
                    }
                } else {
                    // For day shifts, cap at 23:59:59 as before
                    if ($od_start_is_on_date && !$od_end_is_on_date) {
                        $od_start_date_time = $od_start_date_time;
                        $od_end_date_time = date('Y-m-d 23:59:59', strtotime($date));
                    }
                    if (!$od_start_is_on_date && $od_end_is_on_date) {
                        $od_start_date_time = date('Y-m-d 00:00:00', strtotime($date));
                        $od_end_date_time = $od_end_date_time;
                    }
                    if (!$od_start_is_on_date && !$od_end_is_on_date) {
                        $od_start_date_time = date('Y-m-d 00:00:00', strtotime($date));
                        $od_end_date_time = date('Y-m-d 23:59:59', strtotime($date));
                    }
                }
            }

            $start_times[] = (date('Y-m-d', strtotime($od_start_date_time)) == $date) ? date('Y-m-d H:i:s', strtotime($od_start_date_time)) : date('Y-m-d H:i:s', strtotime($shift_start__Raw));
            $end_times[] = (date('Y-m-d', strtotime($od_end_date_time)) == $date || $isNightShift) ? date('Y-m-d H:i:s', strtotime($od_end_date_time)) : date('Y-m-d H:i:s', strtotime($shift_end__Raw));
        }



        $start_times_unfiltered = [];
        foreach ($start_times as $a) {
            $start_times_unfiltered[] = strtotime($a);
        }

        $end_times_unfiltered = [];
        foreach ($end_times as $b) {
            $end_times_unfiltered[] = strtotime($b);
        }


        $start_times_filtered = array_filter($start_times_unfiltered);
        $end_times_filtered = array_filter($end_times_unfiltered);


        $start_time = !empty($start_times_filtered) ? date('H:i:s', min($start_times_filtered)) : null;
        $end_time = !empty($end_times_filtered) ? date('H:i:s', max($end_times_filtered)) : null;



        return [$start_time, $end_time];
    }

    public static function get_late_coming_minutes($shift_start__Raw, $in_time__Raw)
    {
        if (!empty($in_time__Raw) && !empty($shift_start__Raw)) {
            if (strtotime($in_time__Raw) > strtotime($shift_start__Raw)) {
                $late_coming_minutes = self::get_time_difference($shift_start__Raw, $in_time__Raw, 'minutes');
                // $late_coming_minutes = self::get_time_difference($in_time__Raw, $shift_start__Raw, 'minutes');
                // return $late_coming_minutes;
                return $late_coming_minutes > 0 ? $late_coming_minutes : 0;
            } else {
                return '0';
            }
        } else {
            return '0';
        }
    }

    public static function get_early_going_minutes($shift_end__Raw, $out_time__Raw, $in_time__Raw)
    {

        if (
            !empty($in_time__Raw) && !empty($out_time__Raw)
            && $in_time__Raw != '--:--' && $out_time__Raw != '--:--'
            && !empty($shift_end__Raw)
        ) {
            if (strtotime($out_time__Raw) < strtotime($shift_end__Raw)) {
                $early_going_minutes = self::get_time_difference($out_time__Raw, $shift_end__Raw, 'minutes');
                return $early_going_minutes > 0 ? $early_going_minutes : 0;
            } else {
                return '0';
            }
        } else {
            return '0';
        }
    }

    public static function get_comp_off_minutes($employee_id, $date)
    {
        $CompOffMinutesUtilizedModel = new CompOffMinutesUtilizedModel();
        $get_comp_off_minutes = $CompOffMinutesUtilizedModel
            ->where('employee_id =', $employee_id)
            ->where('type =', 'utilized')
            ->where('date =', $date)
            ->first();
        $comp_off_minutes = !empty($get_comp_off_minutes) ? $get_comp_off_minutes['minutes'] : 0;
        return $comp_off_minutes > 0 ? $comp_off_minutes : 0;
    }

    public static function get_wave_off_minutes($employee_id, $date)
    {
        $WaveOffModel = new WaveOffModel();
        $get_wave_off_minutes = $WaveOffModel->where('employee_id =', $employee_id)->where('date =', $date)->first();
        $wave_off_minutes = !empty($get_wave_off_minutes) ? $get_wave_off_minutes['minutes'] : 0;
        return $wave_off_minutes > 0 ? $wave_off_minutes : 0;
    }

    public static function get_wave_off_remarks($employee_id, $date)
    {
        $WaveOffModel = new WaveOffModel();
        $get_wave_off_remarks = $WaveOffModel->where('employee_id =', $employee_id)->where('date =', $date)->first();
        return !empty($get_wave_off_remarks) ? $get_wave_off_remarks['remarks'] : '';
    }

    public static function get_deduction_minutes($employee_id, $date)
    {
        $DeductionModel = new DeductionModel();
        $get_deduction_minutes = $DeductionModel->where('employee_id =', $employee_id)->where('date =', $date)->first();
        // $get_deduction_minutes = $DeductionModel->where('employee_id =', $employee_id)->where('date =', $date)->where('current_status =', 'approved')->first();
        $deduction_minutes = !empty($get_deduction_minutes) ? $get_deduction_minutes['minutes'] : 0;
        return $deduction_minutes > 0 ? $deduction_minutes : 0;
    }

    public static function get_deduction_remarks($employee_id, $date)
    {
        $DeductionModel = new DeductionModel();
        $get_deduction_remarks = $DeductionModel->where('employee_id =', $employee_id)->where('date =', $date)->first();
        // $get_deduction_remarks = $DeductionModel->where('employee_id =', $employee_id)->where('date =', $date)->where('current_status =', 'approved')->first();
        return !empty($get_deduction_remarks) ? $get_deduction_remarks['initial_remarks'] : '';
    }

    public static function get_wave_off_half_day_who_did_not_work_for_half_day($employee_id, $date)
    {
        $WaveOffHalfDayWhoDidNotWorkForHalfDayModel = new WaveOffHalfDayWhoDidNotWorkForHalfDayModel();
        $wave_off_half_day_who_did_not_work_for_half_day = $WaveOffHalfDayWhoDidNotWorkForHalfDayModel->where('employee_id =', $employee_id)->where('date =', $date)->first();
        return !empty($wave_off_half_day_who_did_not_work_for_half_day) ? 'yes' : 'no';
    }

    public static function get_wave_off_half_day_who_did_not_work_for_half_day_remarks($employee_id, $date)
    {
        $WaveOffHalfDayWhoDidNotWorkForHalfDayModel = new WaveOffHalfDayWhoDidNotWorkForHalfDayModel();
        $wave_off_half_day_who_did_not_work_for_half_day_remarks = $WaveOffHalfDayWhoDidNotWorkForHalfDayModel->where('employee_id =', $employee_id)->where('date =', $date)->first();
        return !empty($wave_off_half_day_who_did_not_work_for_half_day_remarks) ? $wave_off_half_day_who_did_not_work_for_half_day_remarks['remarks'] : '';
    }

    public static function get_od_hours_between_shifts($date, $shift_start__Raw, $shift_end__Raw, $employee_id, $returnType = 'hours')
    {
        $OdRequestsModel = new OdRequestsModel();
        $ods    = $OdRequestsModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'approved')
            ->where("
                        (
                            ( date(estimated_from_date_time) between '" . $date . "' and '" . $date . "')
                            or ( date(estimated_to_date_time) between '" . $date . "' and '" . $date . "')
                            or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                            or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                            or ( date(actual_from_date_time) between '" . $date . "' and '" . $date . "')
                            or ( date(actual_to_date_time) between '" . $date . "' and '" . $date . "')
                            or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                            or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                        )
                    ")
            ->findAll();

        $od_minutes = 0;
        foreach ($ods as $od) {
            if (!empty($od['actual_from_date_time']) && !empty($od['actual_to_date_time'])) {
                $from = $od['actual_from_date_time'];
                $to = $od['actual_to_date_time'];
            } else {
                $from = $od['estimated_from_date_time'];
                $to = $od['estimated_to_date_time'];
            }

            if (date('Y-m-d', strtotime($from)) != date('Y-m-d', strtotime($to))) {
                $from = $to = $date;
            }

            $time_from = date('H:i', strtotime($from));
            $time_to = date('H:i', strtotime($to));
            if (strtotime($time_from) > strtotime($shift_start__Raw)) {
                $od_from = date_create($time_from);
            } else {
                $od_from = date_create($shift_start__Raw);
            }

            if (strtotime($time_to) > strtotime($shift_end__Raw)) {
                $od_to = date_create($time_to);
            } else {
                $od_to = date_create($shift_end__Raw);
            }

            $diff    = $od_from->diff($od_to);
            $od_m    = (int)$diff->format('%r%i');
            $od_h    = (int)$diff->format('%r%h');
            $od_minutes    += $od_m + ($od_h * 60);
        }

        if ($od_minutes > 0) {
            if ($returnType == 'hours') {
                $hours = floor($od_minutes / 60);
                $minutes = $od_minutes % 60;
                return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
            } else {
                return $od_minutes;
            }
        } else {
            if ($returnType == 'hours') {
                return null;
            } else {
                return '0';
            }
        }
    }

    public static function is_weekoff($shift_id, $date)
    {
        $ShiftModel = new ShiftModel();
        $shiftData = $ShiftModel->find($shift_id);
        $WeekOffDays = array_values(json_decode($shiftData['weekoff'], true));
        $dayName = strtolower(date('l', strtotime($date)));
        if (in_array($dayName, $WeekOffDays)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public static function is_special_holiday($date, $employee_id, $returnType = 'data')
    {
        $HolidayModel = new HolidayModel();
        $TheHoliday = $HolidayModel->where('holiday_date =', $date)->where('holiday_code =', 'SPL HL')->first();
        if (!empty($TheHoliday)) {
            $SpecialHolidayEmployeesModel = new SpecialHolidayEmployeesModel();
            $query = $SpecialHolidayEmployeesModel->builder();
            $query
                ->where('special_holiday_employees.holiday_id=', $TheHoliday['id'])
                ->where("FIND_IN_SET('{$employee_id}', employee_id) > 0");
            $results = $query->get()->getResult();
            if (!empty($results)) {
                if ($returnType !== 'data') {
                    return 'yes';
                } else {
                    return $TheHoliday;
                }
            }
        }
        if ($returnType !== 'data') {
            return 'no';
        } else {
            return null;
        }
    }

    public static function is_RH($date, $employee_id)
    {
        $FixedRhModel = new FixedRhModel();
        $FixedRhModel
            ->select("rh_id as rh_id")
            ->where("fixed_rh.employee_id =", $employee_id)
            ->where("fixed_rh.year =", date('Y', strtotime($date)));
        $MYRHids = $FixedRhModel->findAll();
        $is_RH_DATA = null;
        if (!empty($MYRHids)) {
            foreach ($MYRHids as $rh_id) {
                if ($is_RH_DATA == null) {
                    $HolidayModel = new HolidayModel();
                    $HolidayModel
                        ->select("holidays.*")
                        ->where("holidays.id =", $rh_id);
                    $theRH = $HolidayModel->first();
                    if (!empty($theRH)) {
                        if ($theRH['holiday_date'] == $date) {
                            $is_RH_DATA = $theRH;
                        }
                    }
                }
            }
        } else {
            $is_RH_DATA = null;
        }
        return $is_RH_DATA;
    }

    public static function is_fixed_off($date, $employee_id)
    {
        $EmployeeModel = new EmployeeModel();
        $FixedSaturdayOff = $EmployeeModel
            ->select('employees.second_saturday_fixed_off as second_saturday_fixed_off')
            ->where('employees.id =', $employee_id)
            ->first();
        $year = date('Y', strtotime($date));
        $monthName = date('F', strtotime($date));
        $secondStaturdayDate = date('Y-m-d', strtotime("Second Saturday Of " . $monthName . " " . $year));

        if ($FixedSaturdayOff['second_saturday_fixed_off'] == 'yes' && $secondStaturdayDate == $date) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public static function is_onLeave($date, $employee_id, $in_time__Raw, $returnType = 'data')
    {
        $LeaveRequestsModel = new LeaveRequestsModel();
        $CheckLeaveRequestRow = $LeaveRequestsModel
            ->select('leave_requests.*')
            ->select('trim(concat(e.first_name, " ", e.last_name)) as approved_by')
            ->join('employees as e', 'e.id = leave_requests.reviewed_by', 'left')
            ->where('leave_requests.employee_id =', $employee_id)
            ->where('leave_requests.status =', 'approved')
            ->where(
                '(
                                       (leave_requests.from_date between "' . $date . '" and "' . $date . '")
                                    or (leave_requests.to_date between "' . $date . '" and "' . $date . '")
                                    or ("' . $date . '" between leave_requests.from_date and leave_requests.to_date )
                                    )'
            )
            ->first();
        if (!empty($CheckLeaveRequestRow)) {
            if ($returnType !== 'data') {
                return 'yes';
            } else {
                return $CheckLeaveRequestRow;
            }
        } else {
            if ($returnType !== 'data') {
                return 'no';
            } else {
                return null;
            }
        }
    }

    public static function is_onOD($date, $employee_id)
    {
        $OdRequestsModel = new OdRequestsModel();
        $ods    = $OdRequestsModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'approved')
            ->where("
                        (
                            ( date(estimated_from_date_time) between '" . $date . "' and '" . $date . "')
                            or ( date(estimated_to_date_time) between '" . $date . "' and '" . $date . "')
                            or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                            or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                            or ( date(actual_from_date_time) between '" . $date . "' and '" . $date . "')
                            or ( date(actual_to_date_time) between '" . $date . "' and '" . $date . "')
                            or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                            or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                        )
                    ")
            ->findAll();
        if (!empty($ods)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public static function is_on_InternationOD($date, $employee_id)
    {
        $OdRequestsModel = new OdRequestsModel();
        $ods    = $OdRequestsModel
            ->where('employee_id =', $employee_id)
            ->where('international =', 'yes')
            ->where('status =', 'approved')
            ->where("
                        (
                            ( date(estimated_from_date_time) between '" . $date . "' and '" . $date . "')
                            or ( date(estimated_to_date_time) between '" . $date . "' and '" . $date . "')
                            or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                            or ( '" . $date . "' between date(estimated_from_date_time) and date(estimated_to_date_time) )
                            or ( date(actual_from_date_time) between '" . $date . "' and '" . $date . "')
                            or ( date(actual_to_date_time) between '" . $date . "' and '" . $date . "')
                            or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                            or ( '" . $date . "' between date(actual_from_date_time) and date(actual_to_date_time) )
                        )
                    ")
            ->findAll();
        if (!empty($ods)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public static function is_sandwitch($shift_id, $date, $internal_employee_id, $employee_id)
    {

        $EmployeeModel = new EmployeeModel();
        // $company_id = $EmployeeModel->find($employee_id)->first()['company_id'];
        $company_id = $EmployeeModel->find($employee_id)['company_id'];

        // print_r($company_id);
        // die();

        $from_date = date('Y-m-d', strtotime("-1 days", strtotime($date)));
        $machine_from_1 = @json_decode(get_punching_data_with_override($internal_employee_id, $from_date, $from_date), true)['InOutPunchData'][0]['machine'];
        if (self::is_fixed_off($from_date, $employee_id) == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_holiday($from_date, 'bool', $machine_from_1, $company_id) == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_special_holiday($from_date, $employee_id, 'bool') == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (!empty(self::is_RH($from_date, $employee_id))) {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        }

        $to_date = date('Y-m-d', strtotime("+1 days", strtotime($date)));

        #$machine_to_1_json = get_punching_data_with_override( $internal_employee_id, $to_date, $to_date );
        #$machine_to_1_data = isset($machine_to_1_json) && !empty($machine_to_1_json) ? json_decode( $machine_to_1_json, true )['InOutPunchData'] : [];
        #$machine_to_1 = $machine_to_1_data[0]['machine'];

        $machine_to_1 = @json_decode(get_punching_data_with_override($internal_employee_id, $to_date, $to_date), true)['InOutPunchData'][0]['machine'];
        if (self::is_fixed_off($to_date, $employee_id) == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_holiday($to_date, 'bool', $machine_to_1, $company_id) == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_special_holiday($to_date, $employee_id, 'bool') == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (!empty(self::is_RH($to_date, $employee_id))) {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        }


        #Second Time Check
        $machine_from_2 = @json_decode(get_punching_data_with_override($internal_employee_id, $from_date, $from_date), true)['InOutPunchData'][0]['machine'];
        if (self::is_weekoff($shift_id, $from_date) == 'yes') {
            ###we are not checking shift override because we assume that any shift override will no override weekoff
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_fixed_off($from_date, $employee_id) == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_holiday($from_date, 'bool', $machine_from_2, $company_id) == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_special_holiday($from_date, $employee_id, 'bool') == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (!empty(self::is_RH($from_date, $employee_id))) {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        }

        $machine_to_2 = @json_decode(get_punching_data_with_override($internal_employee_id, $to_date, $to_date), true)['InOutPunchData'][0]['machine'];
        if (self::is_weekoff($shift_id, $to_date) == 'yes') {
            ###we are not checking shift override because we assume that any shift override will no override weekoff
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_fixed_off($to_date, $employee_id) == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_holiday($to_date, 'bool', $machine_to_2, $company_id) == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_special_holiday($to_date, $employee_id, 'bool') == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (!empty(self::is_RH($to_date, $employee_id))) {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        }

        #Third Time Check
        $machine_from_3 = @json_decode(get_punching_data_with_override($internal_employee_id, $from_date, $from_date), true)['InOutPunchData'][0]['machine'];
        if (self::is_weekoff($shift_id, $from_date) == 'yes') {
            ###we are not checking shift override because we assume that any shift override will no override weekoff
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_fixed_off($from_date, $employee_id) == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_holiday($from_date, 'bool', $machine_from_3, $company_id) == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_special_holiday($from_date, $employee_id, 'bool') == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (!empty(self::is_RH($from_date, $employee_id))) {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        }

        $machine_to_3 = @json_decode(get_punching_data_with_override($internal_employee_id, $to_date, $to_date), true)['InOutPunchData'][0]['machine'];
        if (self::is_weekoff($shift_id, $to_date) == 'yes') {
            ###we are not checking shift override because we assume that any shift override will no override weekoff
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_fixed_off($to_date, $employee_id) == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_holiday($to_date, 'bool', $machine_to_3, $company_id) == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_special_holiday($to_date, $employee_id, 'bool') == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (!empty(self::is_RH($to_date, $employee_id))) {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        }

        #Fourth Time Check
        $machine_from_4 = @json_decode(get_punching_data_with_override($internal_employee_id, $from_date, $from_date), true)['InOutPunchData'][0]['machine'];
        if (self::is_weekoff($shift_id, $from_date) == 'yes') {
            ###we are not checking shift override because we assume that any shift override will no override weekoff
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_fixed_off($from_date, $employee_id) == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_holiday($from_date, 'bool', $machine_from_4, $company_id) == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (self::is_special_holiday($from_date, $employee_id, 'bool') == 'yes') {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        } elseif (!empty(self::is_RH($from_date, $employee_id))) {
            $from_date = date('Y-m-d', strtotime("-1 days", strtotime($from_date)));
        }

        $machine_to_4 = @json_decode(get_punching_data_with_override($internal_employee_id, $to_date, $to_date), true)['InOutPunchData'][0]['machine'];
        if (self::is_weekoff($shift_id, $to_date) == 'yes') {
            ###we are not checking shift override because we assume that any shift override will no override weekoff
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_fixed_off($to_date, $employee_id) == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_holiday($to_date, 'bool', $machine_to_4, $company_id) == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (self::is_special_holiday($to_date, $employee_id, 'bool') == 'yes') {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        } elseif (!empty(self::is_RH($to_date, $employee_id))) {
            $to_date = date('Y-m-d', strtotime("+1 days", strtotime($to_date)));
        }

        $get_data_from_array = json_decode(get_punching_data_with_override($internal_employee_id, $from_date, $from_date), true)['InOutPunchData'];
        $get_data_from = array_values($get_data_from_array);
        $get_data_to_array = json_decode(get_punching_data_with_override($internal_employee_id, $to_date, $to_date), true)['InOutPunchData'];
        $get_data_to = array_values($get_data_to_array);
        $get_data_date_array = json_decode(get_punching_data_with_override($internal_employee_id, $date, $date), true)['InOutPunchData'];
        $get_data_date = array_values($get_data_date_array);

        if (self::is_weekoff($shift_id, $date) == 'yes' && (!isset($get_data_date[0]['INTime']) || $get_data_date[0]['INTime'] == '--:--')) {
            if ((!isset($get_data_from[0]['INTime']) || $get_data_from[0]['INTime'] == '--:--') && (!isset($get_data_to[0]['INTime']) || $get_data_to[0]['INTime'] == '--:--')) {
                if (self::is_onOD($from_date, $employee_id) == 'yes' || self::is_onOD($to_date, $employee_id) == 'yes') {
                    return 'no';
                } else {
                    return 'yes';
                }
            } else {
                return 'no';
            }
        } elseif (self::is_fixed_off($date, $employee_id) == 'yes' && (!isset($get_data_date[0]['INTime']) || $get_data_date[0]['INTime'] == '--:--')) {
            if ((!isset($get_data_from[0]['INTime']) || $get_data_from[0]['INTime'] == '--:--') && (!isset($get_data_to[0]['INTime']) || $get_data_to[0]['INTime'] == '--:--')) {
                if (self::is_onOD($from_date, $employee_id) == 'yes' || self::is_onOD($to_date, $employee_id) == 'yes') {
                    return 'no';
                } else {
                    return 'yes';
                }
            } else {
                return 'no';
            }
        } elseif (self::is_holiday($date, 'bool', $get_data_date[0]['machine'], $company_id) == 'yes' && (!isset($get_data_date[0]['INTime']) || $get_data_date[0]['INTime'] == '--:--')) {
            if ((!isset($get_data_from[0]['INTime']) || $get_data_from[0]['INTime'] == '--:--') && (!isset($get_data_to[0]['INTime']) || $get_data_to[0]['INTime'] == '--:--')) {
                if (self::is_onOD($from_date, $employee_id) == 'yes' || self::is_onOD($to_date, $employee_id) == 'yes') {
                    return 'no';
                } else {
                    return 'yes';
                }
            } else {
                return 'no';
            }
        } elseif (self::is_special_holiday($date, $employee_id, 'bool') == 'yes' && (!isset($get_data_date[0]['INTime']) || $get_data_date[0]['INTime'] == '--:--')) {
            if ((!isset($get_data_from[0]['INTime']) || $get_data_from[0]['INTime'] == '--:--') && (!isset($get_data_to[0]['INTime']) || $get_data_to[0]['INTime'] == '--:--')) {
                if (self::is_onOD($from_date, $employee_id) == 'yes' || self::is_onOD($to_date, $employee_id) == 'yes') {
                    return 'no';
                } else {
                    return 'yes';
                }
            } else {
                return 'no';
            }
        }
        /*elseif( !empty(self::is_RH($date, $employee_id)) && ( !isset($get_data_date[0]['INTime']) || $get_data_date[0]['INTime'] == '--:--' ) ){
            if( ( !isset($get_data_from[0]['INTime']) || $get_data_from[0]['INTime'] == '--:--' ) && ( !isset($get_data_to[0]['INTime']) || $get_data_to[0]['INTime'] == '--:--' ) ){
                if( self::is_onOD($from_date, $employee_id) == 'yes' || self::is_onOD($to_date, $employee_id) == 'yes' ){
                    return 'no';
                }else{
                    return 'yes';
                }
            }else{
                return 'no';
            }
        }*/ else {
            return 'no';
        }
    }

    public static function is_present($in_time__Raw, $out_time__Raw)
    {
        if (!empty($in_time__Raw) && !empty($out_time__Raw)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public static function is_absent($in_time__Raw, $out_time__Raw)
    {
        if (empty($in_time__Raw) && empty($out_time__Raw)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    // public static function get_time_difference($start_time__Raw, $end_time__Raw, $returnType = 'hours')
    // {

    //     if (!empty($start_time__Raw) && !empty($end_time__Raw)) {
    //         $start_time     = date_create($start_time__Raw);
    //         $end_time       = date_create($end_time__Raw);
    //         $Time       = $start_time->diff($end_time);
    //         $minutes   = (int)$Time->format('%r%i');
    //         $hrs       = (int)$Time->format('%r%h');
    //         $minutes   = $minutes + ($hrs * 60);
    //     } else {
    //         $minutes = 0;
    //     }
    //     if ($returnType == 'hours') {
    //         return str_pad(floor($minutes / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($minutes - floor($minutes / 60) * 60), 2, '0', STR_PAD_LEFT);
    //     } else {
    //         return $minutes;
    //     }
    // }


    public static function get_time_difference($start_time__Raw, $end_time__Raw, $returnType = 'hours')
    {
        if (!empty($start_time__Raw) && !empty($end_time__Raw)) {
            $start_time = new DateTime($start_time__Raw);
            $end_time = new DateTime($end_time__Raw);

            // Fix for overnight shift — add 1 day if end is earlier than start
            if ($end_time < $start_time) {
                $end_time->modify('+1 day');
            }

            $interval = $start_time->diff($end_time);
            $total_minutes = ($interval->h * 60) + $interval->i;
        } else {
            $total_minutes = 0;
        }

        if ($returnType == 'hours') {
            $hours = floor($total_minutes / 60);
            $minutes = $total_minutes % 60;
            return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
        } else {
            return $total_minutes;
        }
    }

    public static function is_missed_punch($in_time__Raw, $out_time__Raw)
    {
        if ((!empty($in_time__Raw) && empty($out_time__Raw)) || (empty($in_time__Raw) && !empty($out_time__Raw))) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public static function get_shift($ShiftData__Raw)
    {
        if (isset($ShiftData__Raw) && !empty($ShiftData__Raw)) {
            $shift =  explode(",", $ShiftData__Raw);
        } else {
            $shift = array('', '');
        }
        $shift_start    = !empty($shift[0]) ? date('H:i:s', strtotime($shift[0])) : '';
        $shift_end      = !empty($shift[1]) ? date('H:i:s', strtotime($shift[1])) : '';
        return array('shift_start' => $shift_start, 'shift_end' => $shift_end);
    }

    public static function get_shiftType($Shiftid)
    {
        if (empty($Shiftid)) {
            return null;
        }

        $ShiftModel = new ShiftModel();
        $shift = $ShiftModel->find($Shiftid);
        return $shift['shift_type'];
    }

    public static function get_shift_override($employee_id, $date)
    {
        $ShiftOverrideModel = new ShiftOverrideModel();
        $ShiftOverrideModel->select('shift_override.shift_id as shift_id');
        $ShiftOverrideModel->select('shifts.shift_name as shift_name');
        $ShiftOverrideModel->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "monday" and shift_id = shift_override.shift_id) as Monday');
        $ShiftOverrideModel->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "tuesday" and shift_id = shift_override.shift_id) as Tuesday');
        $ShiftOverrideModel->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "wednesday" and shift_id = shift_override.shift_id) as Wednesday');
        $ShiftOverrideModel->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "thursday" and shift_id = shift_override.shift_id) as Thursday');
        $ShiftOverrideModel->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "friday" and shift_id = shift_override.shift_id) as Friday');
        $ShiftOverrideModel->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "saturday" and shift_id = shift_override.shift_id) as Saturday');
        $ShiftOverrideModel->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "sunday" and shift_id = shift_override.shift_id) as Sunday');
        $ShiftOverrideModel->join('shifts', 'shifts.id = shift_override.shift_id', 'left');
        $ShiftOverrideModel->join('shift_per_day', 'shift_per_day.id = shifts.id', 'left');
        $ShiftOverrideModel->where('shift_override.employee_id =', $employee_id);
        $ShiftOverrideModel->where("'" . $date . "' between shift_override.from_date and shift_override.to_date");
        $ShiftOverrideEntry = $ShiftOverrideModel->first();
        if (!empty($ShiftOverrideEntry)) {
            $day = date('l', strtotime($date));
            $ShiftData__Raw = $ShiftOverrideEntry[$day];
            $shift =  explode(",", $ShiftData__Raw);
            $shift_start    = !empty($shift[0]) ? date('H:i:s', strtotime($shift[0])) : '';
            $shift_end      = !empty($shift[1]) ? date('H:i:s', strtotime($shift[1])) : '';
            $shift_override_id      = !empty($ShiftOverrideEntry['shift_id']) ? $ShiftOverrideEntry['shift_id'] : '';
            return  ['shift_start' => $shift_start, 'shift_end' => $shift_end, 'shift_override_id' => $shift_override_id];
        } else {
            return null;
        }
    }

    // public static function find_sandwich_second_pass($punchingData__LateComingAdjusted)
    // {
    //     $dates_for_sandwich = array();
    //     $ProcessedPunchingData = new ProcessedPunchingData();
    //     foreach ($punchingData__LateComingAdjusted as $index => $data) {
    //         if (
    //             (in_array($data['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'NH']))
    //             &&
    //             $data['is_absent'] == 'yes'
    //             &&
    //             $data['is_present'] == 'no'
    //             &&
    //             $data['is_onOD'] == 'no'
    //         ) {

    //             $current_key = $index;
    //             $prev_key = $current_key - 1;
    //             while (in_array($punchingData__LateComingAdjusted[$prev_key]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    //                 $prev_key--;
    //             }
    //             $next_key = $current_key + 1;
    //             while (in_array($punchingData__LateComingAdjusted[$next_key]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    //                 $next_key++;
    //             }

    //             if ($prev_key < 0) {
    //                 $firstDateOfCurrentMonth = date('Y-m-01', strtotime($data['DateString_2']));
    //                 $firstDateOfPrevMonth = date('Y-m-01', strtotime($firstDateOfCurrentMonth . " -1 days"));
    //                 $lastDateOfPrevMonth = date('Y-m-t', strtotime($firstDateOfPrevMonth));

    //                 #get last month attendance from hr sheet
    //                 $PreFinalPaidDaysModel = new PreFinalPaidDaysModel();
    //                 $prevMonthPunchingData = $PreFinalPaidDaysModel
    //                     ->select('pre_final_paid_days.*')
    //                     ->where('pre_final_paid_days.employee_id =', $data['employee_id'])
    //                     ->where("(pre_final_paid_days.date between '" . $firstDateOfPrevMonth . "' and '" . $lastDateOfPrevMonth . "')")
    //                     ->orderBy('pre_final_paid_days.date', 'ASC')
    //                     ->findAll();
    //                 if (!empty($prevMonthPunchingData)) {
    //                     $prev_key_prev_month = date('t', strtotime($lastDateOfPrevMonth)) - 1;
    //                     while (in_array($prevMonthPunchingData[$prev_key_prev_month]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    //                         $prev_key_prev_month--;
    //                     }
    //                     if (in_array($prevMonthPunchingData[$prev_key_prev_month]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF']) && in_array($punchingData__LateComingAdjusted[$next_key]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF'])) {
    //                         $punchingData__LateComingAdjusted[$current_key]['status'] = 'S/W';
    //                         $punchingData__LateComingAdjusted[$current_key]['status_remarks'] = 'Sandwich1';
    //                         $punchingData__LateComingAdjusted[$current_key]['paid'] = '0';
    //                     }
    //                 } else {

    //                     $prevMonthPunchingData = $ProcessedPunchingData->GetProcessedPunchingData($data['employee_id'], $firstDateOfPrevMonth, $lastDateOfPrevMonth, false);
    //                     $prevMonthPunchingData = orderResultSet($prevMonthPunchingData, 'date_time_ordering', FALSE);
    //                     $prev_key_prev_month = date('t', strtotime($lastDateOfPrevMonth)) - 1;
    //                     while (in_array($prevMonthPunchingData[$prev_key_prev_month]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    //                         $prev_key_prev_month--;
    //                     }
    //                     if (in_array($prevMonthPunchingData[$prev_key_prev_month]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF']) && in_array($punchingData__LateComingAdjusted[$next_key]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF'])) {
    //                         $punchingData__LateComingAdjusted[$current_key]['status'] = 'S/W';
    //                         $punchingData__LateComingAdjusted[$current_key]['status_remarks'] = 'Sandwich2';
    //                         $punchingData__LateComingAdjusted[$current_key]['paid'] = '0';
    //                     }
    //                 }
    //             } elseif ($next_key >= date('t', strtotime($data['DateString_2']))) {
    //                 $lastDateOfCurrentMonth = date('Y-m-t', strtotime($data['DateString_2']));
    //                 $firstDateOfNextMonth = date('Y-m-01', strtotime($lastDateOfCurrentMonth . " +1 days"));
    //                 $lastDateOfNextMonth = date('Y-m-t', strtotime($firstDateOfNextMonth));

    //                 #get last month attendance from hr sheet
    //                 $PreFinalPaidDaysModel = new PreFinalPaidDaysModel();
    //                 $nextMonthPunchingData = $PreFinalPaidDaysModel
    //                     ->select('pre_final_paid_days.*')
    //                     ->where('pre_final_paid_days.employee_id =', $data['employee_id'])
    //                     ->where("(pre_final_paid_days.date between '" . $firstDateOfNextMonth . "' and '" . $lastDateOfNextMonth . "')")
    //                     ->orderBy('pre_final_paid_days.date', 'ASC')
    //                     ->findAll();
    //                 if (!empty($nextMonthPunchingData)) {
    //                     $next_key_next_month = 0;
    //                     while (in_array($nextMonthPunchingData[$next_key_next_month]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    //                         $next_key_next_month++;
    //                     }
    //                     if (in_array($punchingData__LateComingAdjusted[$prev_key]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF']) && in_array($nextMonthPunchingData[$next_key_next_month]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF'])) {
    //                         $punchingData__LateComingAdjusted[$current_key]['status'] = 'S/W';
    //                         $punchingData__LateComingAdjusted[$current_key]['status_remarks'] = 'Sandwich3';
    //                         $punchingData__LateComingAdjusted[$current_key]['paid'] = '0';
    //                     }
    //                 } else {
    //                     $nextMonthPunchingData = $ProcessedPunchingData->GetProcessedPunchingData($data['employee_id'], $firstDateOfNextMonth, $lastDateOfNextMonth, false);
    //                     $nextMonthPunchingData = orderResultSet($nextMonthPunchingData, 'date_time_ordering', FALSE);
    //                     $next_key_next_month = 0;
    //                     while (in_array($nextMonthPunchingData[$next_key_next_month]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    //                         $next_key_next_month++;
    //                     }
    //                     if (in_array($punchingData__LateComingAdjusted[$prev_key]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF']) && in_array($nextMonthPunchingData[$next_key_next_month]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF'])) {
    //                         $punchingData__LateComingAdjusted[$current_key]['status'] = 'S/W';
    //                         $punchingData__LateComingAdjusted[$current_key]['status_remarks'] = 'Sandwich4';
    //                         $punchingData__LateComingAdjusted[$current_key]['paid'] = '0';
    //                     }
    //                 }
    //             } else {
    //                 if (in_array($punchingData__LateComingAdjusted[$prev_key]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF']) && in_array($punchingData__LateComingAdjusted[$next_key]['status'], ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF'])) {
    //                     $punchingData__LateComingAdjusted[$current_key]['status'] = 'S/W';
    //                     $punchingData__LateComingAdjusted[$current_key]['status_remarks'] = 'Sandwich5';
    //                     $punchingData__LateComingAdjusted[$current_key]['paid'] = '0';
    //                 }
    //             }
    //         }
    //     }
    //     return $punchingData__LateComingAdjusted;
    // }

    public static function find_sandwich_second_pass($punchingData)
    {
        $PreFinalPaidDaysModel = new PreFinalPaidDaysModel();

        foreach ($punchingData as $index => &$data) {
            if (!self::isSandwichCandidate($data)) {
                continue;
            }

            $prevKey = self::findPreviousWorkDay($punchingData, $index);
            $nextKey = self::findNextWorkDay($punchingData, $index);
            $date = $data['DateString_2'];

            if ($prevKey < 0) {
                self::checkPrevMonthSandwich($punchingData, $index, $data, $nextKey, $PreFinalPaidDaysModel);
            } elseif ($nextKey >= date('t', strtotime($date))) {
                self::checkNextMonthSandwich($punchingData, $index, $data, $prevKey, $PreFinalPaidDaysModel);
            } else {
                if (self::isWorkDay($punchingData[$prevKey]['status']) && self::isWorkDay($punchingData[$nextKey]['status'])) {
                    self::markSandwich($punchingData[$index], 'Sandwich5');
                }
            }
        }

        return $punchingData;
    }

    private static function isSandwichCandidate($data)
    {
        //RH Sandwich will applicable after november 2025
        $today = date('Y-m-d');
        $december_2025 = date('Y-m-d', strtotime('2025-12-01'));
        if ($today >= $december_2025) {
            return in_array($data['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'NH', 'RH']) &&
                $data['is_absent'] === 'yes' &&
                $data['is_present'] === 'no' &&
                $data['is_onOD'] === 'no';
        } else {
            return in_array($data['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'NH']) &&
                $data['is_absent'] === 'yes' &&
                $data['is_present'] === 'no' &&
                $data['is_onOD'] === 'no';
        }
    }

    private static function findPreviousWorkDay($data, $start)
    {
        $key = $start - 1;
        while ($key >= 0 && in_array($data[$key]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
            $key--;
        }
        return $key;
    }

    private static function findNextWorkDay($data, $start)
    {
        $key = $start + 1;
        while ($key < count($data) && in_array($data[$key]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
            $key++;
        }
        return $key;
    }

    private static function isWorkDay($status)
    {
        return in_array($status, ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF']);
    }

    private static function markSandwich(&$entry, $remark)
    {
        $entry['status'] = 'S/W';
        $entry['status_remarks'] = $remark;
        $entry['paid'] = '0';
    }

    private static function checkPrevMonthSandwich(&$dataSet, $index, $data, $nextKey, $PreFinalPaidDaysModel)
    {
        $date = $data['DateString_2'];
        $firstDateOfCurrentMonth = date('Y-m-01', strtotime($date));
        $firstDateOfPrevMonth = date('Y-m-01', strtotime($firstDateOfCurrentMonth . " -1 day"));
        $lastDateOfPrevMonth = date('Y-m-t', strtotime($firstDateOfPrevMonth));

        $prevMonthData = $PreFinalPaidDaysModel
            ->where('employee_id', $data['employee_id'])
            ->where("date BETWEEN '{$firstDateOfPrevMonth}' AND '{$lastDateOfPrevMonth}'")
            ->orderBy('date', 'ASC')
            ->findAll();

        if (empty($prevMonthData)) {
            $prevMonthData = Processor::GetProcessedPunchingData(
                $data['employee_id'],
                $firstDateOfPrevMonth,
                $lastDateOfPrevMonth,
                false
            );

            $prevMonthData = orderResultSet($prevMonthData, 'date_time_ordering', false);
        }

        $prevKey = count($prevMonthData) - 1;
        while ($prevKey >= 0 && in_array($prevMonthData[$prevKey]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
            $prevKey--;
        }

        if (
            $prevKey >= 0 &&
            self::isWorkDay($prevMonthData[$prevKey]['status']) &&
            self::isWorkDay($dataSet[$nextKey]['status'])
        ) {
            self::markSandwich($dataSet[$index], empty($prevMonthData) ? 'Sandwich2' : 'Sandwich1');
        }
    }


    private static function checkNextMonthSandwich(&$dataSet, $index, $data, $prevKey, $PreFinalPaidDaysModel)
    {
        $date = $data['DateString_2'];
        $lastDateOfCurrentMonth = date('Y-m-t', strtotime($date));
        $firstDateOfNextMonth = date('Y-m-01', strtotime($lastDateOfCurrentMonth . " +1 day"));
        $lastDateOfNextMonth = date('Y-m-t', strtotime($firstDateOfNextMonth));

        $nextMonthData = $PreFinalPaidDaysModel
            ->where('employee_id', $data['employee_id'])
            ->where("date BETWEEN '{$firstDateOfNextMonth}' AND '{$lastDateOfNextMonth}'")
            ->orderBy('date', 'ASC')
            ->findAll();

        if (empty($nextMonthData)) {
            $nextMonthData = Processor::GetProcessedPunchingData(
                $data['employee_id'],
                $firstDateOfNextMonth,
                $lastDateOfNextMonth,
                false
            );
            $nextMonthData = orderResultSet($nextMonthData, 'date_time_ordering', true);
        } else {
            print_r($nextMonthData);
            die("something is not working here");
        }

        $nextKey = 0;
        while ($nextKey < count($nextMonthData) && in_array($nextMonthData[$nextKey]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
            $nextKey++;
        }

        if (
            $nextKey < count($nextMonthData) &&
            self::isWorkDay($dataSet[$prevKey]['status']) &&
            self::isWorkDay($nextMonthData[$nextKey]['status'])
        ) {
            self::markSandwich($dataSet[$index], empty($nextMonthData) ? 'Sandwich4' : 'Sandwich3');
        }
    }








    public static function my_RH($date, $employee_id, $show_used_rh = false)
    {
        $FixedRhModel = new FixedRhModel();
        $FixedRhModel
            ->select("fixed_rh.rh_id as rh_id")
            ->select("holidays.holiday_date as holiday_date")
            ->select("holidays.holiday_code as holiday_code")
            ->select("holidays.holiday_name as holiday_name")
            ->select("holidays.holiday_type as holiday_type")
            ->join("holidays", "holidays.id=fixed_rh.rh_id", "left")
            ->where("fixed_rh.employee_id =", $employee_id)
            ->where("fixed_rh.year =", date('Y', strtotime($date)));
        // ->where("fixed_rh.year =", '2024');
        if ($show_used_rh == false) {
            $FixedRhModel->where("holidays.holiday_date >", $date);
        }
        $MYRH = $FixedRhModel->findAll();
        if (!empty($MYRH)) {
            return $MYRH;
        } else {
            return null;
        }
    }

    public static function convertToMinutes($time)
    {
        if (!preg_match('/^\d{1,2}:\d{2}$/', $time)) {
            return 0;
        }
        list($hours, $minutes) = explode(':', $time);
        return $hours * 60 + $minutes;
    }
    public static function convertToTime($totalMinutes)
    {
        if (!is_numeric($totalMinutes) || $totalMinutes < 0) {
            return false;
        }
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public static function getLeaveBalance($employee_id, $show_used_rh = false)
    {
        $LeaveBalanceModel = new LeaveBalanceModel();
        $LeaveBalanceNew = $LeaveBalanceModel
            ->select('leave_balance.*')
            ->select('ifnull(nullif(leave_balance.balance, ""), 0) as balance')
            ->where('leave_balance.year =', date('Y'))
            ->where('leave_balance.month =', date('m'))
            ->where('leave_balance.employee_id =', $employee_id)
            ->where('leave_balance.leave_code !=', 'RH')
            ->findAll();

        $CompOffCreditModel = new CompOffCreditModel();
        $comp_off_from = date('Y-m-d', strtotime('-90 days'));
        $comp_off_to = date('Y-m-d');

        $AllCompOffCredit = 0;
        $AllCompOffCredits = $CompOffCreditModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'approved')
            ->where("(working_date <= '" . $comp_off_to . "')")
            ->orderBy("working_date", "ASC")
            ->findAll();
        $AllCompOffCredit = !empty($AllCompOffCredits) ? array_sum(array_column($AllCompOffCredits, 'exchange')) : 0;

        $LeaveRequestsModel = new LeaveRequestsModel();
        $AllCompOffDebits = $LeaveRequestsModel
            ->where('(leave_requests.to_date <= "' . $comp_off_to . '")')
            ->where('employee_id =', $employee_id)
            ->where('type_of_leave =', 'COMP OFF')
            ->orderBy("leave_requests.from_date", "ASC")
            ->findAll();
        $pendingCOMP_OFF = $approvedCOMP_OFF = 0;

        if (!empty($AllCompOffDebits)) {
            foreach ($AllCompOffDebits as $CompOffRequest) {
                if (date('m', strtotime($CompOffRequest['from_date'])) !== date('m', strtotime($CompOffRequest['to_date']))) {
                    $date1 = date_create($CompOffRequest['from_date']);
                    $date2 = date_create($CompOffRequest['to_date']);
                    $diff = date_diff($date1, $date2);
                    $CompOffRequest['number_of_days'] = $diff->format("%a") + 1;
                }

                if ($CompOffRequest['status'] == 'pending') {
                    if ($CompOffRequest['type_of_leave'] == 'COMP OFF') {
                        $pendingCOMP_OFF += $CompOffRequest['number_of_days'];
                    }
                } elseif ($CompOffRequest['status'] == 'approved') {
                    if ($CompOffRequest['type_of_leave'] == 'COMP OFF') {
                        $approvedCOMP_OFF += $CompOffRequest['number_of_days'];
                    }
                }
            }
        }
        $BalanceCompOff = $AllCompOffCredit - ($pendingCOMP_OFF + $approvedCOMP_OFF);
        $BalanceCompOff = $BalanceCompOff < 0 ? 0 : $BalanceCompOff;

        ####################Begin::Find all credits from today backwards limit to $BalanceCompOff####################
        $CompOffCreditModel = new CompOffCreditModel();
        $CompOffCreditBalanceNewResults = $CompOffCreditModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'approved')
            ->where("(working_date between '" . $comp_off_from . "' and '" . $comp_off_to . "')")
            ->orderBy("working_date", "DESC")
            ->findAll();
        ####################End::Find all credits from today backwards limit to $BalanceCompOff####################
        ####################Begin::Update FinalCompOffBalance####################
        $FinalCompOffBalance = !empty($CompOffCreditBalanceNewResults) ? array_sum(array_column($CompOffCreditBalanceNewResults, 'exchange')) : '0';
        $FinalCompOffBalance = ($FinalCompOffBalance > $BalanceCompOff) ? $BalanceCompOff : $FinalCompOffBalance;

        $LeaveBalanceNew[] = array('leave_code' => 'COMP OFF', 'credited' => 0, 'balance' => 0);
        foreach ($LeaveBalanceNew as $i => $BalanceNewRow) {
            if ($BalanceNewRow['leave_code'] == 'COMP OFF') {
                $LeaveBalanceNew[$i]['balance'] = $FinalCompOffBalance < 0 ? 0 : $FinalCompOffBalance;
            }
        }
        ####################End::Update FinalCompOffBalance####################





        ####################Begin::CompOff Minutes####################
        ####################Begin::CompOff Minutes####################
        ####################Begin::CompOff Minutes####################
        ####################Begin::CompOff Minutes####################
        ####################Begin::CompOff Minutes####################
        ####################Begin::CompOff Minutes####################
        $CompOffCreditModel = new CompOffCreditModel();
        $comp_off_from = date('Y-m-d', strtotime('-90 days'));
        $comp_off_to = date('Y-m-d');

        $AllCompOffCredit_minute = 0;
        $AllCompOffCredit_minutes = $CompOffCreditModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'approved')
            ->where("(working_date <= '" . $comp_off_to . "')")
            ->orderBy("working_date", "ASC")
            ->findAll();

        if (!empty($AllCompOffCredit_minutes)) {
            foreach ($AllCompOffCredit_minutes as $entry) {
                $AllCompOffCredit_minute += self::convertToMinutes($entry['minutes']);
            }
        }

        $CompOffMinutesUtilizedModel = new CompOffMinutesUtilizedModel();
        $AllCompOffDebit_minutes = $CompOffMinutesUtilizedModel
            ->where('(comp_off_minutes_utilized.date <= "' . $comp_off_to . '")')
            ->where('employee_id =', $employee_id)
            ->where('type =', 'utilized')
            ->orderBy("comp_off_minutes_utilized.date", "ASC")
            ->findAll();
        $utilizedCOMP_OFF_minute = 0;

        if (!empty($AllCompOffDebit_minutes)) {
            foreach ($AllCompOffDebit_minutes as $CompOffDebitRequest) {
                $utilizedCOMP_OFF_minute += $CompOffDebitRequest['minutes'];
            }
        }
        $BalanceCompOff_minute = $AllCompOffCredit_minute - $utilizedCOMP_OFF_minute;
        $BalanceCompOff_minute = $BalanceCompOff_minute < 0 ? 0 : $BalanceCompOff_minute;

        ####################Begin::Find all credits from today backwards limit to $BalanceCompOff####################
        $CompOffCreditModel = new CompOffCreditModel();
        $CompOffCreditBalance_minute_NewResults = $CompOffCreditModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'approved')
            ->where("(working_date between '" . $comp_off_from . "' and '" . $comp_off_to . "')")
            ->orderBy("working_date", "DESC")
            ->findAll();
        ####################End::Find all credits from today backwards limit to $BalanceCompOff####################
        ####################Begin::Update FinalCompOffBalance####################
        $FinalCompOffBalance_minute = 0;
        if (!empty($CompOffCreditBalance_minute_NewResults)) {
            foreach ($CompOffCreditBalance_minute_NewResults as $entrynew) {
                $FinalCompOffBalance_minute += self::convertToMinutes($entrynew['minutes']);
            }
        }
        $FinalCompOffBalance_minute = ($FinalCompOffBalance_minute > $BalanceCompOff_minute) ? $BalanceCompOff_minute : $FinalCompOffBalance_minute;

        $LeaveBalanceNew[] = array('leave_code' => 'COMP OFF Minutes', 'credited' => 0, 'balance' => 0);
        foreach ($LeaveBalanceNew as $i => $BalanceNewRow) {
            if ($BalanceNewRow['leave_code'] == 'COMP OFF Minutes') {
                $LeaveBalanceNew[$i]['balance'] = $FinalCompOffBalance_minute < 0 ? '00:00' : self::convertToTime($FinalCompOffBalance_minute);
            }
        }
        ####################End::Update FinalCompOffBalance####################
        ####################End::CompOff Minutes####################
        ####################End::CompOff Minutes####################
        ####################End::CompOff Minutes####################
        ####################End::CompOff Minutes####################
        ####################End::CompOff Minutes####################
        ####################End::CompOff Minutes####################
        ####################End::CompOff Minutes####################


        $my_RH = self::my_RH(date('Y-m-01'), $employee_id, $show_used_rh);
        $rh_balance = 0;
        $rh_balance_string = "";
        $rh_dates = array();
        if (!empty($my_RH)) {
            $rh_balance = count($my_RH);
            $rh_string_array = array();
            foreach ($my_RH as $th_RH) {
                $rh_string_array[] = $th_RH['holiday_name'] . "<small class='ms-2'>(" . date('d M', strtotime($th_RH['holiday_date'])) . ")</small>";
                $rh_dates[] = $th_RH['holiday_date'];
            }
            $rh_balance_string = implode(', ', $rh_string_array);
        }
        $LeaveBalanceNew[] = [
            'leave_code' => 'RH',
            'balance' => $rh_balance_string,
            'rh_dates' => $rh_dates,
        ];
        return $LeaveBalanceNew;
    }

    public static function getLeaveBalanceNextMonth($employee_id)
    {
        $last_month_balance = ProcessorHelper::getLeaveBalance($employee_id);
        $NextMonthLeaveBalance = array();
        if (!empty($last_month_balance)) {
            foreach ($last_month_balance as $i => $LastMonthBalanceRow) {
                $NextMonthLeaveBalance[$i]['leave_code'] = $LastMonthBalanceRow['leave_code'];
                if ($LastMonthBalanceRow['leave_code'] == 'CL') {
                    if (date('Y') == date('Y', strtotime(date('Y-m-t') . ' +1 days'))) {
                        $next_month_credit_cl = 1;
                        $carry_forward_cl =  $LastMonthBalanceRow['balance'] > 1 ? 1 : $LastMonthBalanceRow['balance'];
                        $next_month_eligible_cl = $next_month_credit_cl;
                    } else {
                        $next_month_credit_cl = 1;
                        $carry_forward_cl =  0;
                        $next_month_eligible_cl = $next_month_credit_cl;
                    }
                    $NextMonthLeaveBalance[$i]['balance'] = $carry_forward_cl + $next_month_credit_cl > 2 ? 2 : $carry_forward_cl + $next_month_credit_cl;
                    $NextMonthLeaveBalance[$i]['eligible_balance'] = $next_month_eligible_cl;
                } elseif ($LastMonthBalanceRow['leave_code'] == 'EL') {

                    if (date('Y') == date('Y', strtotime(date('Y-m-t') . ' +1 days'))) {
                        $carry_forward_el =  $LastMonthBalanceRow['balance'];
                        $next_month_credit_el = 0;
                        $next_month_eligible_el = $carry_forward_el;
                    } else {
                        $carry_forward_el =  $LastMonthBalanceRow['balance'] > 15 ? 15 : $LastMonthBalanceRow['balance'];
                        #find out how many EL can be credited check in every month every date.
                        #let us assume averyone is getting 15 el from next year which is upcoming month
                        $next_month_credit_el = 0;

                        $joining_date = session()->get('current_user')['joining_date'];
                        $first_date_of_next_month = date('Y-m-01', strtotime(date('Y-m-t') . ' +1 days'));
                        $last_date_of_next_month_year = date('Y-12-31', strtotime($first_date_of_next_month));

                        $first_date_of_next_month_created = date_create($first_date_of_next_month);
                        $joining_date_created = date_create($joining_date);

                        $joining_date_diff = date_diff($joining_date_created, $first_date_of_next_month_created);
                        $yearInterval = $joining_date_diff->format("%y");
                        $monthInterval = $joining_date_diff->format("%m");
                        if ($yearInterval >= 1) {
                            $first_date_of_next_month_created = date_create($first_date_of_next_month);
                            $first_date_of_next_to_next_year_created = date_create(date('Y-m-01', strtotime($last_date_of_next_month_year . ' +1 days')));
                            $diffProRate = date_diff($first_date_of_next_month_created, $first_date_of_next_to_next_year_created);
                            $ProRateYears = $diffProRate->format("%R%y");
                            $ProRateMonths = $diffProRate->format("%R%m");
                            $next_month_credit_el = $ProRateMonths > 0 ? round(((15 / 12) * $ProRateMonths), 2) : round(((15 / 12) * ($ProRateYears * 12)), 2);
                        }
                        $next_month_eligible_el = $next_month_credit_el;
                    }

                    $next_month_el_balance = ($carry_forward_el + $next_month_credit_el) > 30 ? 30 : $carry_forward_el + $next_month_credit_el;

                    $first_date_of_next_year = date('Y-01-01', strtotime(date('Y-12-31') . ' +1 days'));
                    $last_date_of_next_year = date('Y-12-31', strtotime(date('Y-12-31') . ' +1 days'));


                    $LeaveRequestsModel = new LeaveRequestsModel();
                    $NextYearLeaveRquests = $LeaveRequestsModel
                        ->where('employee_id', $employee_id)
                        ->where('type_of_leave', 'EL')
                        ->where(
                            '(
                                                       (leave_requests.from_date between "' . $first_date_of_next_year . '" and "' . $last_date_of_next_year . '")
                                                    or (leave_requests.to_date between "' . $first_date_of_next_year . '" and "' . $last_date_of_next_year . '")
                                                    or ("' . $first_date_of_next_year . '" between leave_requests.from_date and leave_requests.to_date )
                                                    or ("' . $last_date_of_next_year . '" between leave_requests.from_date and leave_requests.to_date )
                                                    )'
                        )
                        ->where(
                            '(
                                                       (leave_requests.status = "pending")
                                                    or (leave_requests.status = "approved")
                                                    )'
                        )
                        ->findAll();

                    $NextYearLeaveRquestCount = 0;
                    if (!empty($NextYearLeaveRquests)) {
                        foreach ($NextYearLeaveRquests as $NextYearLeaveRquest) {
                            if (date('Y') == date('Y', strtotime($NextYearLeaveRquest['from_date']))) {
                                $date1 = date_create($first_date_of_next_month);
                                $date2 = date_create($NextYearLeaveRquest['to_date']);
                                $diff = date_diff($date1, $date2);
                                $NextYearLeaveRquestCount += $diff->format("%R%a") + 1;
                            } else {
                                $NextYearLeaveRquestCount += $NextYearLeaveRquest['number_of_days'];
                            }
                        }
                    }
                    #only if getting leave balance from next year
                    $NextMonthLeaveBalance[$i]['balance'] =  $next_month_el_balance - $NextYearLeaveRquestCount;
                    $NextMonthLeaveBalance[$i]['eligible_balance'] = $next_month_eligible_el - $NextYearLeaveRquestCount;
                } elseif ($LastMonthBalanceRow['leave_code'] == 'RH') {
                    $next_month_RH = ProcessorHelper::my_RH(date('Y-m-01', strtotime(date('Y-m-t') . ' + 1 days')), $employee_id);
                    $next_month_rh_balance = 0;
                    $next_month_rh_balance_string = "";
                    if (!empty($next_month_RH)) {
                        $next_month_rh_balance = count($next_month_RH);
                        $next_month_rh_string_array = array();
                        foreach ($next_month_RH as $th_RH) {
                            $next_month_rh_string_array[] = $th_RH['holiday_name'] . "<small class='ms-2'>(" . date('d M', strtotime($th_RH['holiday_date'])) . ")</small>";
                        }
                        $next_month_rh_balance_string = implode(', ', $next_month_rh_string_array);
                    }

                    $NextMonthLeaveBalance[$i]['balance'] = $next_month_rh_balance_string;
                    $NextMonthLeaveBalance[$i]['eligible_balance'] = '';
                } elseif ($LastMonthBalanceRow['leave_code'] == 'COMP OFF') {
                    $next_month_eligible_comp_off = 0;
                    $NextMonthLeaveBalance[$i]['balance'] = $LastMonthBalanceRow['balance'];
                    $NextMonthLeaveBalance[$i]['eligible_balance'] = $next_month_eligible_comp_off;
                }
            }
        }
        return $NextMonthLeaveBalance;
    }

    public static function getEnabledDateForCompOffCredit($current_user_data, $employee_id)
    {
        $DateForCompOffCredit = date_range_between(date('Y-m-d', strtotime('-90 days')), date('Y-m-d'));
        $EnabledDateForCompOffCredit = [];
        $internal_employee_id = $current_user_data['internal_employee_id'];
        $shift_id_gedfcod = $current_user_data['shift_id'];

        $get_data_date_array = json_decode(get_punching_data($current_user_data['internal_employee_id'], date('Y-m-d', strtotime('-90 days')), date('Y-m-d')), true)['InOutPunchData'];
        $get_data_date = array_values($get_data_date_array);
        $machines_array = [];
        if (!empty($get_data_date)) {
            foreach ($get_data_date as $data) {
                $machines_array[$data['DateString_2']] = $data['machine'];
            }
        }
        foreach ($DateForCompOffCredit as $date) {

            // if ($date == '2025-04-17') {
            //     print_r($machines_array);
            //     die();
            // }
            $eligible = 'no';

            $shift_override  = ProcessorHelper::get_shift_override($employee_id, $date);
            if (!empty($shift_override)) {
                $shift_id = $shift_override['shift_override_id'];
            } else {
                $shift_id = $shift_id_gedfcod;
            }
            if (ProcessorHelper::is_weekoff($shift_id, $date) == 'yes' && $eligible == 'no') {
                $eligible = 'yes';
            } elseif (ProcessorHelper::is_holiday($date, 'bool', $machines_array[$date] ?? null, $current_user_data['company_id']) == 'yes' && $eligible == 'no') {
                $eligible = 'yes';
            } elseif (ProcessorHelper::is_special_holiday($date, $employee_id, 'bool') == 'yes' && $eligible == 'no') {
                $eligible = 'yes';
            } elseif (ProcessorHelper::is_fixed_off($date, $employee_id) == 'yes' && $eligible == 'no') {
                $eligible = 'yes';
            } elseif (!empty(ProcessorHelper::is_RH($date, $employee_id)) && $eligible == 'no') {
                $eligible = 'yes';
            }

            if ($eligible == 'yes') {
                $day = date('l', strtotime($date));
                $shift          = !empty($shift_override) ? $shift_override : ProcessorHelper::get_shift($current_user_data[$day]);
                $shift_start    = !empty($shift['shift_start']) ? date('h:i A', strtotime($shift['shift_start'])) : null;
                $shift_end      = !empty($shift['shift_end']) ? date('h:i A', strtotime($shift['shift_end'])) : null;

                $get_punching_data = array_values(json_decode(get_punching_data($internal_employee_id, $date, $date), true)['InOutPunchData'])[0] ?? null;

                $in_time__Raw = (isset($get_punching_data['INTime']) && !empty($get_punching_data['INTime']) && $get_punching_data['INTime'] !== '--:--') ? date('h:i A', strtotime($get_punching_data['INTime'])) : null;
                $out_time__Raw = (isset($get_punching_data['OUTTime']) && !empty($get_punching_data['OUTTime']) && $get_punching_data['OUTTime'] !== '--:--') ? date('h:i A', strtotime($get_punching_data['OUTTime'])) : null;

                $is_on_InternationOD = ProcessorHelper::is_on_InternationOD($date, $employee_id);
                $is_WeekOff = ProcessorHelper::is_weekoff($shift_id, $date);

                $punch_time_including_od = ProcessorHelper::get_punch_time_including_od($in_time__Raw, $out_time__Raw, $shift['shift_start'], $shift['shift_end'], $date, $employee_id);
                $in_time_including_od = ($is_on_InternationOD == 'yes' && $is_WeekOff == 'yes') ? 'Not Allowed for Weekoffs on International OD' : (!empty($punch_time_including_od[0]) ? date('h:i A', strtotime($punch_time_including_od[0])) : "");
                $out_time_including_od = ($is_on_InternationOD == 'yes' && $is_WeekOff == 'yes') ? 'Not Allowed for Weekoffs on International OD' : (!empty($punch_time_including_od[1]) ? date('h:i A', strtotime($punch_time_including_od[1])) : "");

                $EnabledDateForCompOffCredit[] = [
                    'date' => $date,
                    'shift_start' => $shift_start,
                    'shift_end' => $shift_end,
                    'in_time__Raw' => $in_time__Raw,
                    'out_time__Raw' => $out_time__Raw,
                    'in_time_including_od' => $in_time_including_od,
                    'out_time_including_od' => $out_time_including_od,
                ];
            }
        }

        return $EnabledDateForCompOffCredit;
    }
}
