<?php

namespace App\Pipes;

use App\Models\AttendanceOverrideModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;
use Closure;

class RecalculateForHeuer
{
    public function handle($data, Closure $next)
    {
        $punching_data_sorted = $data['punching_data'];

        foreach ($punching_data_sorted as $index => $punching_row) {
            // if ($punching_row['employee_id'] == '337' && $punching_row['date'] == '2025-12-01') {
            //     dd($punching_row);
            // }
            if ($punching_row['shift_type'] == 'reduce') {

                $is_night_shift = strtotime($punching_row['shift_start']) > strtotime($punching_row['shift_end']) ? true : false;
                if ($is_night_shift) {
                    ///apply new formula for night shift
                    $shift_start_original                                       = date('H:i', strtotime($punching_row['shift_start']));
                    $shift_end_original                                         = date('H:i', strtotime($punching_row['shift_end']));
                    $INTime_original                                            = $punching_row['INTime'];
                    $OUTTime_original                                           = $punching_row['OUTTime'];
                    $early_going_minutes_original                               = $punching_row['early_going_minutes'];
                    $late_leaving_miutes_original                               = $punching_row['ExtraWorkMinutes'];
                    $absent_for_work_hours_minutes_current_user_data_original   = $punching_row['absent_for_work_hours_minutes_current_user_data'];
                    $half_day_for_work_hours_minutes_current_user_data_original = $punching_row['half_day_for_work_hours_minutes_current_user_data'];
                    $late_coming_rule_original                                  = $punching_row['late_coming_rule'];
                    $attendance_rule_original                                   = $punching_row['attendance_rule'];
                    $absent_for_work_hours_minutes_original                     = $punching_row['absent_for_work_hours_minutes'];
                    $half_day_for_work_hours_minutes_original                   = $punching_row['half_day_for_work_hours_minutes'];
                    $shift_original                                             = $punching_row['shift'];
                    $in_time__Raw_original                                      = $punching_row['in_time__Raw'];
                    $out_time__Raw_original                                     = $punching_row['out_time__Raw'];
                    $punching_time_between_shift_including_od_original          = $punching_row['punching_time_between_shift_including_od'];
                    $punch_time_including_od_original                           = $punching_row['punch_time_including_od'];
                    $in_time_including_od_original                              = $punching_row['in_time_including_od'];
                    $out_time_including_od_original                             = $punching_row['out_time_including_od'];
                    $in_time_original                                           = $punching_row['in_time'];
                    $out_time_original                                          = $punching_row['out_time'];
                    $late_coming_minutes_original                               = $punching_row['late_coming_minutes'];
                    $early_coming_minutes_original                              = $late_coming_minutes_original <= 0 ? ProcessorHelper::get_time_difference($INTime_original, $shift_start_original, 'minutes') : 0;
                    $early_going_minutes_original                               = $punching_row['early_going_minutes'];
                    $comp_off_minutes_original                                  = $punching_row['comp_off_minutes'];
                    $wave_off_minutes_original                                  = $punching_row['wave_off_minutes'];
                    $deduction_minutes_original                                 = $punching_row['deduction_minutes'];
                    $late_coming_plus_early_going_minutes_original              = $punching_row['late_coming_plus_early_going_minutes'];
                    $od_hours_between_shifts_original                           = $punching_row['od_hours_between_shifts'];
                    $late_coming_plus_early_going_minutes_adjustable_original   = $punching_row['late_coming_plus_early_going_minutes_adjustable'];
                    $work_minutes_between_shifts_including_od_original          = $punching_row['work_minutes_between_shifts_including_od'];
                    $work_hours_between_shifts_including_od_original            = $punching_row['work_hours_between_shifts_including_od'];
                    $in_time_between_shift_with_od_original                     = $punching_row['in_time_between_shift_with_od'];
                    $out_time_between_shift_with_od_original                    = $punching_row['out_time_between_shift_with_od'];
                    $punch_in_time_original                                     = $punching_row['punch_in_time'];
                    $punch_out_time_original                                    = $punching_row['punch_out_time'];
                    $ExtraWorkMinutes_original                                  = $punching_row['ExtraWorkMinutes'];
                    $LateSittingMinutes_original                                = $punching_row['LateSittingMinutes'];
                    $OverTimeMinutes_original                                   = $punching_row['OverTimeMinutes'];
                    $grace_original                                             = $punching_row['grace'];




                    // convertToMinutes($time)
                    // convertToTime($totalMinutes)
                    // twoByThree(float $value)

                    $shift_duration_original = ProcessorHelper::get_time_difference($shift_start_original, $shift_end_original, 'minutes');
                    $shift_duration_reduced = round($shift_duration_original * ((100 - 30.43) / 100));
                    $shift_end_reduced_timestamp = strtotime($shift_start_original) + $shift_duration_reduced * 60;
                    $shift_end = date('H:i', $shift_end_reduced_timestamp);

                    $shift_start = date('H:i', strtotime($shift_start_original));

                    // if ($punching_row['employee_id'] == '400' && $punching_row['date'] == '2025-12-01') {
                    //     dd(
                    //         [
                    //             'shift_duration_original' => $shift_duration_original,
                    //             'shift_duration_reduced' => $shift_duration_reduced,
                    //             'shift_start_original' => $shift_start_original,
                    //             'shift_end_original' => $shift_end_original,
                    //             'shift_end_punching_row' => $punching_row['shift_end'],
                    //             'shift_start' => $shift_start,
                    //             'shift_end' => $shift_end,
                    //         ]
                    //     );
                    // }



                    $early_coming_minutes = round($early_coming_minutes_original * ((100 - 30.43) / 100), 0);
                    $late_coming_minutes = round($late_coming_minutes_original * ((100 - 30.43) / 100), 0);

                    if (!empty($INTime_original) && $INTime_original != '--:--') {
                        $INTime_timestamp = strtotime($shift_start) + ($late_coming_minutes * 60) - ($early_coming_minutes * 60);
                        $INTime = date('H:i', $INTime_timestamp);
                    } else {
                        $INTime = $INTime_original;
                    }

                    $early_going_minutes = round($early_going_minutes_original * ((100 - 30.43) / 100), 0);
                    $late_going_minutes = round($ExtraWorkMinutes_original * ((100 - 30.43) / 100), 0);
                    if (!empty($OUTTime_original) && $OUTTime_original != '--:--') {
                        $OUTTime_timestamp = strtotime($shift_end) + ($late_going_minutes * 60) - ($early_going_minutes * 60);
                        $OUTTime = date('H:i', $OUTTime_timestamp);
                    } else {
                        $OUTTime = $OUTTime_original;
                    }



                    if (!empty($punch_in_time_original) && $punch_in_time_original != '--:--') {
                        $punch_in_time_timestamp = strtotime($shift_start) + ($late_coming_minutes * 60) - ($early_coming_minutes * 60);
                        $punch_in_time = date('H:i', $punch_in_time_timestamp);
                    } else {
                        $punch_in_time = $punch_in_time_original;
                    }

                    if (!empty($punch_out_time_original) && $punch_out_time_original != '--:--') {
                        $punch_out_time_timestamp = strtotime($shift_end) + ($late_going_minutes * 60) - ($early_going_minutes * 60);
                        $punch_out_time = date('H:i', $punch_out_time_timestamp);
                    } else {
                        $punch_out_time = $punch_out_time_original;
                    }






                    // Begin::calclualte in time between shift including od
                    $late_coming_between_shift_including_od_original = ProcessorHelper::get_time_difference($shift_start_original, $punching_time_between_shift_including_od_original[0], 'minutes');
                    $late_coming_between_shift_including_od = round($late_coming_between_shift_including_od_original * ((100 - 30.43) / 100), 0);

                    if (!empty($in_time_between_shift_with_od_original) && $in_time_between_shift_with_od_original != '--:--') {
                        $in_time_between_shift_with_od_timestamp = strtotime($shift_start) + ($late_coming_between_shift_including_od * 60);
                        $in_time_between_shift_with_od = date('H:i', $in_time_between_shift_with_od_timestamp);
                    } else {
                        $in_time_between_shift_with_od = $in_time_between_shift_with_od_original;
                    }

                    // End::calclualte in time between shift including od

                    // Begin::calclualte out time between shift including od
                    $early_going_between_shift_including_od_original = ProcessorHelper::get_time_difference($punching_time_between_shift_including_od_original[1], $shift_end_original, 'minutes');
                    $early_going_between_shift_including_od = round($early_going_between_shift_including_od_original * ((100 - 30.43) / 100), 0);
                    if (!empty($out_time_between_shift_with_od_original) && $out_time_between_shift_with_od_original != '--:--') {
                        $out_time_between_shift_with_od_timestamp = strtotime($shift_end) - ($early_going_between_shift_including_od * 60);
                        $out_time_between_shift_with_od = date('H:i', $out_time_between_shift_with_od_timestamp);
                    } else {
                        $out_time_between_shift_with_od = $out_time_between_shift_with_od_original;
                    }
                    // End::calclualte out time between shift including od

                    $punching_time_between_shift_including_od = [$in_time_between_shift_with_od, $out_time_between_shift_with_od];


                    // Begin::calclualte in time including od outside shift
                    //Test here once again

                    if (strtotime($in_time_including_od_original) >= strtotime($shift_start_original)) {
                        $late_coming_including_od_original = ProcessorHelper::get_time_difference($shift_start_original, $in_time_including_od_original, 'minutes');
                        $late_coming_including_od = round($late_coming_including_od_original * ((100 - 30.43) / 100), 0);
                        $early_coming_including_od_original = 0;
                        $early_coming_including_od = 0;
                    } else {
                        $late_coming_including_od_original = 0;
                        $late_coming_including_od = 0;
                        $early_coming_including_od_original = ProcessorHelper::get_time_difference($in_time_including_od_original, $shift_start_original, 'minutes');
                        $early_coming_including_od = round($early_coming_including_od_original * ((100 - 30.43) / 100), 0);
                    }

                    if (!empty($in_time_including_od_original) && $in_time_including_od_original != '--:--') {
                        $in_time_including_od_timestamp = strtotime($shift_start) + ($late_coming_including_od * 60) - ($early_coming_including_od * 60);
                        $in_time_including_od = date('H:i', $in_time_including_od_timestamp);
                    } else {
                        $in_time_including_od = $in_time_including_od_original;
                    }

                    // End::calclualte in time including od outside shift

                    // Begin::calclualte out time including od outside shift
                    //Test here once again

                    if (strtotime($out_time_including_od_original) >= strtotime($shift_end_original)) {
                        $early_going_including_od_original = 0;
                        $early_going_including_od = 0;
                        $late_going_including_od_original = ProcessorHelper::get_time_difference($shift_end_original, $out_time_including_od_original, 'minutes');
                        $late_going_including_od = round($late_going_including_od_original * ((100 - 30.43) / 100), 0);
                    } else {
                        $early_going_including_od_original = ProcessorHelper::get_time_difference($out_time_including_od_original, $shift_end_original, 'minutes');
                        $early_going_including_od = round($early_going_including_od_original * ((100 - 30.43) / 100), 0);
                        $late_going_including_od_original = 0;
                        $late_going_including_od = 0;
                    }
                    if (!empty($out_time_including_od_original) && $out_time_including_od_original != '--:--') {
                        $out_time_including_od_timestamp = strtotime($shift_end) - ($early_going_including_od * 60) + ($late_going_including_od * 60);
                        $out_time_including_od = date('H:i', $out_time_including_od_timestamp);
                    } else {
                        $out_time_including_od = $out_time_including_od_original;
                    }
                    $punch_time_including_od = [$in_time_including_od, $out_time_including_od];
                    // End::calclualte out time including od outside shift

                    // $punching_time_between_shift_including_od = [$in_time_between_shift_including_od, $out_time_between_shift_including_od];

                    // $grace = round($grace_original * ((100 - 30.43) / 100), 0);
                    $grace = floor($grace_original * ((100 - 30.43) / 100));
                    $comp_off_minutes = round($comp_off_minutes_original * ((100 - 30.43) / 100), 0);
                    $wave_off_minutes = round($wave_off_minutes_original * ((100 - 30.43) / 100), 0);
                    $deduction_minutes = round($deduction_minutes_original * ((100 - 30.43) / 100), 0);
                    $ExtraWorkMinutes = round($ExtraWorkMinutes_original * ((100 - 30.43) / 100), 0);
                    $LateSittingMinutes = round($LateSittingMinutes_original * ((100 - 30.43) / 100), 0);
                    $OverTimeMinutes = round($OverTimeMinutes_original * ((100 - 30.43) / 100), 0);
                    // $wave_off_remarks_original = $punching_row['wave_off_remarks'];
                    // $wave_off_remarks_original_array = explode(" ", $wave_off_remarks_original);



                    // if ($punching_row['employee_id'] == '316' && $punching_row['date'] == '2025-12-03') {

                    // dd($punching_row);
                    // dd(
                    //     [
                    //         'date' => $punching_row['date'],
                    //         'shift_duration_original' => $shift_duration_original,
                    //         'shift_duration_reduced' => $shift_duration_reduced,
                    //         'shift_start' => $shift_start,
                    //         'shift_end_reduced_timestamp' => $shift_end_reduced_timestamp,
                    //         'shift_end' => $shift_end,
                    //         'early_coming_minutes_original' => $early_coming_minutes_original,
                    //         'early_coming_minutes' => $early_coming_minutes,
                    //         'late_coming_minutes_original' => $late_coming_minutes_original,
                    //         'late_coming_minutes' => $late_coming_minutes,
                    //         'INTime_original' => $INTime_original,
                    //         'INTime' => $INTime,
                    //         'OUTTime_original' => $OUTTime_original,
                    //         'OUTTime' => $OUTTime,
                    //         'late_coming_between_shift_including_od_original' => $late_coming_between_shift_including_od_original,
                    //         'late_coming_between_shift_including_od' => $late_coming_between_shift_including_od,
                    //         // 'in_time_between_shift_including_od' => $in_time_between_shift_including_od,
                    //         'in_time_between_shift_with_od' => $in_time_between_shift_with_od,
                    //         'early_going_between_shift_including_od_original' => $early_going_between_shift_including_od_original,
                    //         'early_going_between_shift_including_od' => $early_going_between_shift_including_od,
                    //         // 'out_time_between_shift_including_od' => $out_time_between_shift_including_od,
                    //         'out_time_between_shift_with_od' => $out_time_between_shift_with_od,
                    //         'punching_time_between_shift_including_od' => $punching_time_between_shift_including_od,

                    //         'late_coming_including_od_original' => $late_coming_including_od_original,
                    //         'late_coming_including_od' => $late_coming_including_od,
                    //         'early_coming_including_od_original' => $early_coming_including_od_original,
                    //         'early_coming_including_od' => $early_coming_including_od,
                    //         'in_time_including_od' => $in_time_including_od,

                    //         'out_time_including_od_original' => $out_time_including_od_original,
                    //         'early_going_including_od_original' => $early_going_including_od_original,
                    //         'early_going_including_od' => $early_going_including_od,
                    //         'late_going_including_od_original' => $late_going_including_od_original,
                    //         'late_going_including_od' => $late_going_including_od,
                    //         'out_time_including_od' => $out_time_including_od,

                    //         'punch_time_including_od' => $punch_time_including_od,

                    //         'grace_original' => $grace_original,
                    //         'grace' => $grace,
                    //     ]
                    // );
                    // }

                    $punching_row['shift_start'] = $shift_start;
                    $punching_row['shift_end'] = $shift_end;
                    $punching_row['INTime'] = $INTime;
                    $punching_row['OUTTime'] = $OUTTime;
                    $punching_row['in_time_between_shift_with_od'] = $in_time_between_shift_with_od;
                    $punching_row['out_time_between_shift_with_od'] = $out_time_between_shift_with_od;
                    $punching_row['punching_time_between_shift_including_od'] = $punching_time_between_shift_including_od;
                    $punching_row['in_time_including_od'] = $in_time_including_od;
                    $punching_row['out_time_including_od'] = $out_time_including_od;
                    $punching_row['punch_time_including_od'] = $punch_time_including_od;

                    $punching_row['grace'] = $grace;
                    $punching_row['comp_off_minutes'] = $comp_off_minutes;
                    $punching_row['wave_off_minutes'] = $wave_off_minutes;
                    $punching_row['deduction_minutes'] = $deduction_minutes;
                    $punching_row['ExtraWorkMinutes'] = $ExtraWorkMinutes;
                    $punching_row['LateSittingMinutes'] = $LateSittingMinutes;
                    $punching_row['OverTimeMinutes'] = $OverTimeMinutes;

                    $punching_row['punch_in_time'] = $punch_in_time;
                    $punching_row['punch_out_time'] = $punch_out_time;

                    $punching_row['late_coming_minutes'] = $late_coming_minutes;
                    $punching_row['early_going_minutes'] = $early_going_minutes;
                    // $punching_row['late_coming_plus_early_going_minutes'] = (string)($late_coming_minutes + $early_going_minutes + $deduction_minutes);

                    $punching_data_sorted[$index] = $punching_row;
                } else {
                    $shift_start_original                                       = date('H:i', strtotime($punching_row['shift_start']));
                    $shift_end_original                                         = date('H:i', strtotime($punching_row['shift_end']));
                    $INTime_original                                            = $punching_row['INTime'];
                    $OUTTime_original                                           = $punching_row['OUTTime'];
                    $early_going_minutes_original                               = $punching_row['early_going_minutes'];
                    $late_leaving_miutes_original                               = $punching_row['ExtraWorkMinutes'];
                    $absent_for_work_hours_minutes_current_user_data_original   = $punching_row['absent_for_work_hours_minutes_current_user_data'];
                    $half_day_for_work_hours_minutes_current_user_data_original = $punching_row['half_day_for_work_hours_minutes_current_user_data'];
                    $late_coming_rule_original                                  = $punching_row['late_coming_rule'];
                    $attendance_rule_original                                   = $punching_row['attendance_rule'];
                    $absent_for_work_hours_minutes_original                     = $punching_row['absent_for_work_hours_minutes'];
                    $half_day_for_work_hours_minutes_original                   = $punching_row['half_day_for_work_hours_minutes'];
                    $shift_original                                             = $punching_row['shift'];
                    $in_time__Raw_original                                      = $punching_row['in_time__Raw'];
                    $out_time__Raw_original                                     = $punching_row['out_time__Raw'];
                    $punching_time_between_shift_including_od_original          = $punching_row['punching_time_between_shift_including_od'];
                    $punch_time_including_od_original                           = $punching_row['punch_time_including_od'];
                    $in_time_including_od_original                              = $punching_row['in_time_including_od'];
                    $out_time_including_od_original                             = $punching_row['out_time_including_od'];
                    $in_time_original                                           = $punching_row['in_time'];
                    $out_time_original                                          = $punching_row['out_time'];
                    $late_coming_minutes_original                               = $punching_row['late_coming_minutes'];
                    $early_coming_minutes_original                              = $late_coming_minutes_original <= 0 ? ProcessorHelper::get_time_difference($INTime_original, $shift_start_original, 'minutes') : 0;
                    $early_going_minutes_original                               = $punching_row['early_going_minutes'];
                    $comp_off_minutes_original                                  = $punching_row['comp_off_minutes'];
                    $wave_off_minutes_original                                  = $punching_row['wave_off_minutes'];
                    $deduction_minutes_original                                 = $punching_row['deduction_minutes'];
                    $late_coming_plus_early_going_minutes_original              = $punching_row['late_coming_plus_early_going_minutes'];
                    $od_hours_between_shifts_original                           = $punching_row['od_hours_between_shifts'];
                    $late_coming_plus_early_going_minutes_adjustable_original   = $punching_row['late_coming_plus_early_going_minutes_adjustable'];
                    $work_minutes_between_shifts_including_od_original          = $punching_row['work_minutes_between_shifts_including_od'];
                    $work_hours_between_shifts_including_od_original            = $punching_row['work_hours_between_shifts_including_od'];
                    $in_time_between_shift_with_od_original                     = $punching_row['in_time_between_shift_with_od'];
                    $out_time_between_shift_with_od_original                    = $punching_row['out_time_between_shift_with_od'];
                    $punch_in_time_original                                     = $punching_row['punch_in_time'];
                    $punch_out_time_original                                    = $punching_row['punch_out_time'];
                    $ExtraWorkMinutes_original                                  = $punching_row['ExtraWorkMinutes'];
                    $LateSittingMinutes_original                                = $punching_row['LateSittingMinutes'];
                    $OverTimeMinutes_original                                   = $punching_row['OverTimeMinutes'];
                    $grace_original                                             = $punching_row['grace'];




                    // convertToMinutes($time)
                    // convertToTime($totalMinutes)
                    // twoByThree(float $value)

                    $shift_duration_original = ProcessorHelper::get_time_difference($shift_start_original, $shift_end_original, 'minutes');
                    $shift_duration_reduced = $shift_duration_original * 2 / 3;
                    $shift_end_reduced_timestamp = strtotime($shift_start_original) + $shift_duration_reduced * 60;
                    $shift_end = date('H:i', $shift_end_reduced_timestamp);

                    $shift_start = date('H:i', strtotime($shift_start_original));



                    $early_coming_minutes = round(ProcessorHelper::twoByThree($early_coming_minutes_original), 0);
                    $late_coming_minutes = round(ProcessorHelper::twoByThree($late_coming_minutes_original), 0);

                    if (!empty($INTime_original) && $INTime_original != '--:--') {
                        $INTime_timestamp = strtotime($shift_start) + ($late_coming_minutes * 60) - ($early_coming_minutes * 60);
                        $INTime = date('H:i', $INTime_timestamp);
                    } else {
                        $INTime = $INTime_original;
                    }

                    $early_going_minutes = round(ProcessorHelper::twoByThree($early_going_minutes_original), 0);
                    $late_going_minutes = round(ProcessorHelper::twoByThree($ExtraWorkMinutes_original), 0);
                    if (!empty($OUTTime_original) && $OUTTime_original != '--:--') {
                        $OUTTime_timestamp = strtotime($shift_end) + ($late_going_minutes * 60) - ($early_going_minutes * 60);
                        $OUTTime = date('H:i', $OUTTime_timestamp);
                    } else {
                        $OUTTime = $OUTTime_original;
                    }



                    if (!empty($punch_in_time_original) && $punch_in_time_original != '--:--') {
                        $punch_in_time_timestamp = strtotime($shift_start) + ($late_coming_minutes * 60) - ($early_coming_minutes * 60);
                        $punch_in_time = date('H:i', $punch_in_time_timestamp);
                    } else {
                        $punch_in_time = $punch_in_time_original;
                    }

                    if (!empty($punch_out_time_original) && $punch_out_time_original != '--:--') {
                        $punch_out_time_timestamp = strtotime($shift_end) + ($late_going_minutes * 60) - ($early_going_minutes * 60);
                        $punch_out_time = date('H:i', $punch_out_time_timestamp);
                    } else {
                        $punch_out_time = $punch_out_time_original;
                    }






                    // Begin::calclualte in time between shift including od
                    $late_coming_between_shift_including_od_original = ProcessorHelper::get_time_difference($shift_start_original, $punching_time_between_shift_including_od_original[0], 'minutes');
                    $late_coming_between_shift_including_od = round(ProcessorHelper::twoByThree($late_coming_between_shift_including_od_original), 0);

                    if (!empty($in_time_between_shift_with_od_original) && $in_time_between_shift_with_od_original != '--:--') {
                        $in_time_between_shift_with_od_timestamp = strtotime($shift_start) + ($late_coming_between_shift_including_od * 60);
                        $in_time_between_shift_with_od = date('H:i', $in_time_between_shift_with_od_timestamp);
                    } else {
                        $in_time_between_shift_with_od = $in_time_between_shift_with_od_original;
                    }

                    // End::calclualte in time between shift including od

                    // Begin::calclualte out time between shift including od
                    $early_going_between_shift_including_od_original = ProcessorHelper::get_time_difference($punching_time_between_shift_including_od_original[1], $shift_end_original, 'minutes');
                    $early_going_between_shift_including_od = round(ProcessorHelper::twoByThree($early_going_between_shift_including_od_original), 0);
                    if (!empty($out_time_between_shift_with_od_original) && $out_time_between_shift_with_od_original != '--:--') {
                        $out_time_between_shift_with_od_timestamp = strtotime($shift_end) - ($early_going_between_shift_including_od * 60);
                        $out_time_between_shift_with_od = date('H:i', $out_time_between_shift_with_od_timestamp);
                    } else {
                        $out_time_between_shift_with_od = $out_time_between_shift_with_od_original;
                    }
                    // End::calclualte out time between shift including od

                    $punching_time_between_shift_including_od = [$in_time_between_shift_with_od, $out_time_between_shift_with_od];


                    // Begin::calclualte in time including od outside shift
                    //Test here once again

                    if (strtotime($in_time_including_od_original) >= strtotime($shift_start_original)) {
                        $late_coming_including_od_original = ProcessorHelper::get_time_difference($shift_start_original, $in_time_including_od_original, 'minutes');
                        $late_coming_including_od = round(ProcessorHelper::twoByThree($late_coming_including_od_original), 0);
                        $early_coming_including_od_original = 0;
                        $early_coming_including_od = 0;
                    } else {
                        $late_coming_including_od_original = 0;
                        $late_coming_including_od = 0;
                        $early_coming_including_od_original = ProcessorHelper::get_time_difference($in_time_including_od_original, $shift_start_original, 'minutes');
                        $early_coming_including_od = round(ProcessorHelper::twoByThree($early_coming_including_od_original), 0);
                    }

                    if (!empty($in_time_including_od_original) && $in_time_including_od_original != '--:--') {
                        $in_time_including_od_timestamp = strtotime($shift_start) + ($late_coming_including_od * 60) - ($early_coming_including_od * 60);
                        $in_time_including_od = date('H:i', $in_time_including_od_timestamp);
                    } else {
                        $in_time_including_od = $in_time_including_od_original;
                    }

                    // End::calclualte in time including od outside shift

                    // Begin::calclualte out time including od outside shift
                    //Test here once again

                    if (strtotime($out_time_including_od_original) >= strtotime($shift_end_original)) {
                        $early_going_including_od_original = 0;
                        $early_going_including_od = 0;
                        $late_going_including_od_original = ProcessorHelper::get_time_difference($shift_end_original, $out_time_including_od_original, 'minutes');
                        $late_going_including_od = round(ProcessorHelper::twoByThree($late_going_including_od_original), 0);
                    } else {
                        $early_going_including_od_original = ProcessorHelper::get_time_difference($out_time_including_od_original, $shift_end_original, 'minutes');
                        $early_going_including_od = round(ProcessorHelper::twoByThree($early_going_including_od_original), 0);
                        $late_going_including_od_original = 0;
                        $late_going_including_od = 0;
                    }
                    if (!empty($out_time_including_od_original) && $out_time_including_od_original != '--:--') {
                        $out_time_including_od_timestamp = strtotime($shift_end) - ($early_going_including_od * 60) + ($late_going_including_od * 60);
                        $out_time_including_od = date('H:i', $out_time_including_od_timestamp);
                    } else {
                        $out_time_including_od = $out_time_including_od_original;
                    }
                    $punch_time_including_od = [$in_time_including_od, $out_time_including_od];
                    // End::calclualte out time including od outside shift

                    // $punching_time_between_shift_including_od = [$in_time_between_shift_including_od, $out_time_between_shift_including_od];

                    $grace = round(ProcessorHelper::twoByThree($grace_original), 0);
                    $comp_off_minutes = round(ProcessorHelper::twoByThree($comp_off_minutes_original), 0);
                    $wave_off_minutes = round(ProcessorHelper::twoByThree($wave_off_minutes_original), 0);
                    $deduction_minutes = round(ProcessorHelper::twoByThree($deduction_minutes_original), 0);
                    $ExtraWorkMinutes = round(ProcessorHelper::twoByThree($ExtraWorkMinutes_original), 0);
                    $LateSittingMinutes = round(ProcessorHelper::twoByThree($LateSittingMinutes_original), 0);
                    $OverTimeMinutes = round(ProcessorHelper::twoByThree($OverTimeMinutes_original), 0);
                    // $wave_off_remarks_original = $punching_row['wave_off_remarks'];
                    // $wave_off_remarks_original_array = explode(" ", $wave_off_remarks_original);




                    /* if ($punching_row['employee_id'] == '337' && $punching_row['date'] == '2025-12-09') {

                        // dd($punching_row);
                        print_r(
                            [
                                'date' => $punching_row['date'],
                                //         'shift_duration_original' => $shift_duration_original,
                                //         'shift_duration_reduced' => $shift_duration_reduced,
                                //         'shift_start' => $shift_start,
                                //         'shift_end_reduced_timestamp' => $shift_end_reduced_timestamp,
                                //         'shift_end' => $shift_end,
                                //         'early_coming_minutes_original' => $early_coming_minutes_original,
                                //         'early_coming_minutes' => $early_coming_minutes,
                                //         'late_coming_minutes_original' => $late_coming_minutes_original,
                                //         'late_coming_minutes' => $late_coming_minutes,
                                //         'INTime_original' => $INTime_original,
                                //         'INTime' => $INTime,
                                //         'OUTTime_original' => $OUTTime_original,
                                //         'OUTTime' => $OUTTime,
                                //         'late_coming_between_shift_including_od_original' => $late_coming_between_shift_including_od_original,
                                //         'late_coming_between_shift_including_od' => $late_coming_between_shift_including_od,
                                //         // 'in_time_between_shift_including_od' => $in_time_between_shift_including_od,
                                //         'in_time_between_shift_with_od' => $in_time_between_shift_with_od,
                                //         'early_going_between_shift_including_od_original' => $early_going_between_shift_including_od_original,
                                //         'early_going_between_shift_including_od' => $early_going_between_shift_including_od,
                                //         // 'out_time_between_shift_including_od' => $out_time_between_shift_including_od,
                                //         'out_time_between_shift_with_od' => $out_time_between_shift_with_od,
                                //         'punching_time_between_shift_including_od' => $punching_time_between_shift_including_od,

                                'late_coming_including_od_original' => $late_coming_including_od_original,
                                'late_coming_including_od' => $late_coming_including_od,
                                //         'early_coming_including_od_original' => $early_coming_including_od_original,
                                //         'early_coming_including_od' => $early_coming_including_od,
                                //         'in_time_including_od' => $in_time_including_od,

                                //         'out_time_including_od_original' => $out_time_including_od_original,
                                'early_going_including_od_original' => $early_going_including_od_original,
                                'early_going_including_od' => $early_going_including_od,
                                //         'late_going_including_od_original' => $late_going_including_od_original,
                                //         'late_going_including_od' => $late_going_including_od,
                                //         'out_time_including_od' => $out_time_including_od,

                                //         'punch_time_including_od' => $punch_time_including_od,

                                //         'grace_original' => $grace_original,
                                //         'grace' => $grace,
                            ]
                        );
                        die();
                    } */

                    $punching_row['shift_start'] = $shift_start;
                    $punching_row['shift_end'] = $shift_end;
                    $punching_row['INTime'] = $INTime;
                    $punching_row['OUTTime'] = $OUTTime;
                    $punching_row['in_time_between_shift_with_od'] = $in_time_between_shift_with_od;
                    $punching_row['out_time_between_shift_with_od'] = $out_time_between_shift_with_od;
                    $punching_row['punching_time_between_shift_including_od'] = $punching_time_between_shift_including_od;
                    $punching_row['in_time_including_od'] = $in_time_including_od;
                    $punching_row['out_time_including_od'] = $out_time_including_od;
                    $punching_row['punch_time_including_od'] = $punch_time_including_od;

                    $punching_row['grace'] = $grace;
                    $punching_row['comp_off_minutes'] = $comp_off_minutes;
                    $punching_row['wave_off_minutes'] = $wave_off_minutes;
                    $punching_row['deduction_minutes'] = $deduction_minutes;
                    $punching_row['ExtraWorkMinutes'] = $ExtraWorkMinutes;
                    $punching_row['LateSittingMinutes'] = $LateSittingMinutes;
                    $punching_row['OverTimeMinutes'] = $OverTimeMinutes;

                    $punching_row['punch_in_time'] = $punch_in_time;
                    $punching_row['punch_out_time'] = $punch_out_time;

                    $punching_row['late_coming_minutes'] = $late_coming_minutes;
                    $punching_row['early_going_minutes'] = $early_going_minutes;
                    // $punching_row['late_coming_plus_early_going_minutes'] = (string)($late_coming_minutes + $early_going_minutes + $deduction_minutes);
                    $punching_row['late_coming_plus_early_going_minutes'] = round(ProcessorHelper::twoByThree($late_coming_plus_early_going_minutes_original), 0);

                    $punching_data_sorted[$index] = $punching_row;
                }
            }

            // if ($punching_row['employee_id'] == '316' && $punching_row['date'] == '2025-12-03') {
            //     dd($punching_row);
            // }
        }
        $data['punching_data'] = $punching_data_sorted;
        return $next($data);
    }
}
