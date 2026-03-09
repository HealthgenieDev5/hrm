<?php

namespace App\Pipes\AttendanceProcessor;


use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use Closure;



class ApplyStatusCodeAndRemarks
{

    public function handle($data, Closure $next)
    {

        $shift_start = $data['shift_start'];
        $shift_end = $data['shift_end'];
        $punch_out_time = $data['punch_out_time'];
        $in_time_including_od = $data['in_time_including_od'];
        $out_time_including_od = $data['out_time_including_od'];
        $out_time_between_shift_with_od = $data['out_time_between_shift_with_od'];
        $in_time_between_shift_with_od = $data['in_time_between_shift_with_od'];
        $data['ExtraWorkMinutes'] = 0;
        $isNightShift = strtotime($shift_start) > strtotime($shift_end);
        /* if (
            // $data['shift_id'] != 107
            $isNightShift == false
            && !empty($out_time_including_od)
            && !empty($in_time_including_od)
            && (strtotime($out_time_including_od) < strtotime($in_time_including_od))
        ) {
            $ExtraWorkMinutes_day1 = ProcessorHelper::get_time_difference($shift_end, '23:59', 'minutes');
            $ExtraWorkMinutes_day2 = ProcessorHelper::get_time_difference('00:00', $out_time_including_od, 'minutes');
            $data['ExtraWorkMinutes'] = $ExtraWorkMinutes_day1 + $ExtraWorkMinutes_day2 + 1;
        }else {
            $data['ExtraWorkMinutes'] = (strtotime($out_time_including_od) > strtotime($shift_end)) ? ProcessorHelper::get_time_difference($shift_end, $out_time_including_od, 'minutes') : 0;
        } */

        // if ($data['DateString_2'] == '2025-04-12') {
        //     dd(
        //         $shift_start,
        //         $in_time_including_od,
        //         $shift_end,
        //         $out_time_including_od,
        //     );
        // }

        if (!empty($out_time_including_od) && !empty($in_time_including_od)) {
            if (strtotime($out_time_including_od) < strtotime($in_time_including_od)) {
                if ($isNightShift) {
                    $ExtraWorkMinutes = (strtotime($out_time_including_od) > strtotime($shift_end)) ? ProcessorHelper::get_time_difference($shift_end, $out_time_including_od, 'minutes') : 0;
                    $data['ExtraWorkMinutes'] = $ExtraWorkMinutes;
                } else {
                    $ExtraWorkMinutes_day1 = ProcessorHelper::get_time_difference($shift_end, '23:59', 'minutes');
                    $ExtraWorkMinutes_day2 = ProcessorHelper::get_time_difference('00:00', $out_time_including_od, 'minutes');
                    $ExtraWorkMinutes = $ExtraWorkMinutes_day1 + $ExtraWorkMinutes_day2 + 1;
                    $data['ExtraWorkMinutes'] = $ExtraWorkMinutes > 0 ? $ExtraWorkMinutes : 0;
                }
            } else {
                $data['ExtraWorkMinutes'] = (strtotime($out_time_including_od) > strtotime($shift_end)) ? ProcessorHelper::get_time_difference($shift_end, $out_time_including_od, 'minutes') : 0;
            }
        } else {
            $data['ExtraWorkMinutes'] = 0;
        }


        /*if( $data['employee_id'] == '137' ){
            $data['LateSittingMinutes'] = ($data['late_sitting_allowed'] == 'yes' && $data['ExtraWorkMinutes'] > 0) ? round($data['ExtraWorkMinutes']/5) : 0;
        }elseif( in_array($data['employee_id'], [20,25,76]) ) {
            $data['LateSittingMinutes'] = ($data['late_sitting_allowed'] == 'yes' && $data['ExtraWorkMinutes'] > 0) ? round($data['ExtraWorkMinutes']/3) : 0;
        }*/

        #updated on 31 oct 2023
        if (
            $data['late_sitting_allowed'] == 'yes'
            && !empty($data['late_sitting_formula'])
            && $data['ExtraWorkMinutes'] > 0
            && strtotime($data['date']) >= strtotime($data['late_sitting_formula_effective_from'])
        ) {
            $late_sitting_formula = 0;
            eval('$late_sitting_formula = ' . $data["late_sitting_formula"] . ';');
            #if( $data['employee_id'] == '52' ){
            #$late_sitting_formula = 1;
            #}
            $data['LateSittingMinutes'] = round($data['ExtraWorkMinutes'] * $late_sitting_formula);
        } else {
            $data['LateSittingMinutes'] = 0;
        }
        #updated on 31 oct 2023


        $data['OverTimeMinutes'] = ($data['over_time_allowed'] == 'yes' && $data['ExtraWorkMinutes'] > 0) ? round($data['ExtraWorkMinutes'] / 1) : 0;
        #setting grace minutes to 0 intially
        $grace_minutes = 0;
        if ($data['is_present'] == 'yes') {
            $late_coming_rule = $data['late_coming_rule'];
            if ($data['is_missed_punch'] == 'yes') {
                $data['status']          = "M/P";
                $data['status_remarks']  = "Missed Punching";
                $data['paid']            = '0';
                $data['grace']           = '0';
            } elseif ($data['is_weekoff'] == 'yes' || $data['is_holiday'] == 'yes' || $data['is_special_holiday'] == 'yes' || $data['is_fixed_off'] == 'yes' || $data['is_RH'] == 'yes') {
                if ($data['is_weekoff'] == 'yes') {
                    $data['status']          = "W/O";
                    $data['status_remarks']  = "Week Off";
                } elseif ($data['is_holiday'] == 'yes') {
                    $HolidayData = ProcessorHelper::is_holiday($data['date'], 'data', $data['machine'], $data['employee_data']['company_id']);
                    $data['status']         = $HolidayData['holiday_code'];
                    $data['status_remarks'] = $HolidayData['holiday_name'] . " (" . $HolidayData['holiday_type'] . ")";
                } elseif ($data['is_special_holiday'] == 'yes') {
                    $HolidayData = ProcessorHelper::is_special_holiday($data['date'], $data['employee_id']);
                    $data['status']         = $HolidayData['holiday_code'];
                    $data['status_remarks'] = $HolidayData['holiday_name'] . " (" . $HolidayData['holiday_type'] . ")";
                } elseif ($data['is_fixed_off'] == 'yes') {
                    $data['status']         = "F/O";
                    $data['status_remarks'] = "Fixed Off";
                } elseif ($data['is_RH'] == 'yes') {
                    $RH_DATA = $data['RH_DATA'];
                    $data['status']         = $RH_DATA['holiday_code'];
                    $data['status_remarks'] = $RH_DATA['holiday_name'] . " (" . $RH_DATA['holiday_type'] . ")";
                }

                $data['paid']            = '1';
                $data['grace']           = '0';
            } elseif (!empty($late_coming_rule)) {
                foreach ($late_coming_rule as $rule) {
                    $name = $rule['name'];
                    $grace_allowed = $rule['hours'];
                    $grace_hours_allowed = date('H', strtotime($grace_allowed));
                    $grace_minutes_allowed = date('i', strtotime($grace_allowed));
                    $grace_minutes = $grace_minutes_allowed + ($grace_hours_allowed * 60);
                    $applicable = $rule['applicable'];
                    $count = $rule['count'];
                    if ($applicable == 'Daily') {
                        if ($count == 'Half Day Present') {
                            if ($data['absent_because_of_work_hours'] == 'yes') {
                                $data['status']         = 'A';
                                $data['status_remarks'] = 'Work hour is not enough to make it half day or full day';
                                $data['paid']           = '0';
                                $data['grace']          = '0';
                            } elseif ($data['half_day_because_of_work_hours'] == 'yes') {
                                $data['status']         = 'H/D';
                                $data['status_remarks'] = 'Work hour is not enough to make it full day1';
                                $data['paid']           = '0.5';
                                $data['grace']          = $grace_minutes;
                            } else {
                                $data['status']         = 'P';
                                $data['status_remarks'] = 'Present';
                                $data['paid']           = '1';
                                $data['grace']          = $grace_minutes;
                            }
                        }
                    }
                }
            } else {
                $data['status']         = 'P';
                $data['status_remarks'] = 'Present';
                $data['paid']           = '1';
                $data['grace']          = $grace_minutes;
            }
        } elseif ($data['is_onOD'] == 'yes') {
            $late_coming_rule = $data['late_coming_rule'];
            if ($data['is_missed_punch'] == 'yes') {
                $data['status']          = "M/P";
                $data['status_remarks']  = "Missed Punching";
                $data['paid']            = '0';
                $data['grace']           = '0';
            } elseif ($data['is_weekoff'] == 'yes' || $data['is_holiday'] == 'yes' || $data['is_special_holiday'] == 'yes' || $data['is_fixed_off'] == 'yes' || $data['is_RH'] == 'yes') {
                if ($data['is_weekoff'] == 'yes') {
                    $data['status']          = "W/O";
                    $data['status_remarks']  = "Week Off";
                } elseif ($data['is_holiday'] == 'yes') {
                    $HolidayData = ProcessorHelper::is_holiday($data['date'], 'data', $data['machine'], $data['employee_data']['company_id']);
                    $data['status']         = $HolidayData['holiday_code'];
                    $data['status_remarks'] = $HolidayData['holiday_name'] . " (" . $HolidayData['holiday_type'] . ")";
                } elseif ($data['is_special_holiday'] == 'yes') {
                    $HolidayData = ProcessorHelper::is_special_holiday($data['date'], $data['employee_id']);
                    $data['status']         = $HolidayData['holiday_code'];
                    $data['status_remarks'] = $HolidayData['holiday_name'] . " (" . $HolidayData['holiday_type'] . ")";
                } elseif ($data['is_fixed_off'] == 'yes') {
                    $data['status']         = "F/O";
                    $data['status_remarks'] = "Fixed Off";
                } elseif ($data['is_RH'] == 'yes') {
                    $RH_DATA = $data['RH_DATA'];
                    $data['status']         = $RH_DATA['holiday_code'];
                    $data['status_remarks'] = $RH_DATA['holiday_name'] . " (" . $RH_DATA['holiday_type'] . ")";
                }
                $data['paid']            = '1';
                $data['grace']           = '0';
            } elseif (!empty($late_coming_rule)) {
                foreach ($late_coming_rule as $rule) {
                    $name = $rule['name'];
                    $grace_allowed = $rule['hours'];
                    $grace_hours_allowed = date('H', strtotime($grace_allowed));
                    $grace_minutes_allowed = date('i', strtotime($grace_allowed));
                    $grace_minutes = $grace_minutes_allowed + ($grace_hours_allowed * 60);
                    $applicable = $rule['applicable'];
                    $count = $rule['count'];
                    if ($applicable == 'Daily') {
                        if ($count == 'Half Day Present') {
                            if ($data['absent_because_of_work_hours'] == 'yes') {
                                $data['status']         = 'A';
                                $data['status_remarks'] = 'Work hour is not enough to make it half day or full day';
                                $data['paid']           = '0';
                                $data['grace']          = '0';
                            } elseif ($data['half_day_because_of_work_hours'] == 'yes') {
                                $data['status']         = 'OD/2';
                                $data['status_remarks'] = 'Work hour is not enough to make it full day2';
                                $data['paid']           = '0.5';
                                $data['grace']          = $grace_minutes;
                            } else {
                                $data['status']         = 'OD';
                                $data['status_remarks'] = 'On OD With Late Coming Rule';
                                $data['paid']           = '1';
                                $data['grace']          = $grace_minutes;
                            }
                        }
                    }
                }
            } else {
                $data['status']         = 'OD';
                $data['status_remarks'] = 'On OD No Late Coming Rule Present';
                $data['paid']           = '1';
                $data['grace']          = $grace_minutes;
            }
        } elseif ($data['is_missed_punch'] == 'yes') {
            $data['status']         = 'M/P';
            $data['status_remarks'] = 'Missed Punching';
            $data['paid']           = ($data['is_weekoff'] == 'yes' || $data['is_holiday'] == 'yes' || $data['is_special_holiday'] == 'yes' || $data['is_fixed_off'] == 'yes' || $data['is_RH'] == 'yes') ? '1' : '0';
            $data['grace']          = '0';
        } elseif ($data['is_onLeave'] == 'yes') {
            $LeaveRequestData = ProcessorHelper::is_onLeave($data['date'], $data['employee_id'], $data['in_time_between_shift_with_od']);
            if ($LeaveRequestData['type_of_leave'] == 'UL') {
                $data['status']          = ($LeaveRequestData['number_of_days'] == '0.5') ? "UL/2" : 'UL';
                $data['status_remarks']  = $data['status'] . " " . $LeaveRequestData['status'] . " by " . $LeaveRequestData['approved_by'];
                $data['paid']            = '0';
                $data['grace']           = '0';
            } else {
                $data['status']          = ($LeaveRequestData['number_of_days'] == '0.5') ? $LeaveRequestData['type_of_leave'] . "/2" : $LeaveRequestData['type_of_leave'];
                $data['status_remarks']  = $data['status'] . " " . $LeaveRequestData['status'] . " by " . $LeaveRequestData['approved_by'];
                $data['paid']            = ($LeaveRequestData['number_of_days'] == '0.5') ? '0.5' : '1';
                $data['grace']           = '0';
            }

            if ($LeaveRequestData['sick_leave'] == 'yes') {
                $data['status_remarks']  = "SICK LEAVE " . $LeaveRequestData['status'] . " by " . $LeaveRequestData['approved_by'];
            }
        } elseif ($data['is_holiday'] == 'yes') {
            if ($data['is_sandwitch'] == 'yes') {
                $data['status']          = "S/W";
                $data['status_remarks']  = "Sandwitch11";
                $data['paid']            = '0';
                $data['grace']           = '0';
            } else {
                $HolidayData = ProcessorHelper::is_holiday($data['date'], 'data', $data['machine'], $data['employee_data']['company_id']);
                $data['status']         = $HolidayData['holiday_code'];
                $data['status_remarks'] = $HolidayData['holiday_name'] . " (" . $HolidayData['holiday_type'] . ")";
                $data['paid']           = '1';
                $data['grace']          = '0';
            }
        } elseif ($data['is_special_holiday'] == 'yes') {
            if ($data['is_sandwitch'] == 'yes') {
                $data['status']          = "S/W";
                $data['status_remarks']  = "Sandwitch22";
                $data['paid']            = '0';
                $data['grace']           = '0';
            } else {
                $HolidayData = ProcessorHelper::is_special_holiday($data['date'], $data['employee_id']);
                $data['status']         = $HolidayData['holiday_code'];
                $data['status_remarks'] = $HolidayData['holiday_name'] . " (" . $HolidayData['holiday_type'] . ")";
                $data['paid']           = '1';
                $data['grace']          = '0';
            }
        } elseif ($data['is_RH'] == 'yes') {
            #Begin::commented on 18 Nov 2024 to correct 03 Nov Sandwich over RH
            // if( $data['is_sandwitch'] == 'yes' ){
            //     $data['status']          = "S/W";
            //     $data['status_remarks']  = "Sandwitch33";
            //     $data['paid']            = '0';
            //     $data['grace']           = '0';
            // }else{
            $RH_DATA = $data['RH_DATA'];
            $data['status']         = $RH_DATA['holiday_code'];
            $data['status_remarks'] = $RH_DATA['holiday_name'] . " (" . $RH_DATA['holiday_type'] . ")";
            $data['paid']           = '1';
            $data['grace']          = '0';
            // }
            #Begin::commented on 18 Nov 2024 to correct 03 Nov Sandwich over RH
        } elseif ($data['is_sandwitch'] == 'yes') {
            $data['status']          = "S/W";
            $data['status_remarks']  = "Sandwitch44";
            $data['paid']            = '0';
            $data['grace']           = '0';
        } elseif ($data['is_fixed_off'] == 'yes') {
            $data['status']          = "F/O";
            $data['status_remarks']  = "Fixed Off";
            $data['paid']            = '1';
            $data['grace']           = '0';
        } elseif ($data['is_weekoff'] == 'yes') {
            $data['status']          = "W/O";
            $data['status_remarks']  = "Week Off";
            $data['paid']            = '1';
            $data['grace']           = '0';
        } else {
            $data['status']          = "A";
            $data['status_remarks']  = "Leave application not received!";
            $data['paid']            = '0';
            $data['grace']           = '0';
        }


        return $next($data);
    }
}
