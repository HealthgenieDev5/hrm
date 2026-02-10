<?php

namespace App\Pipes;

use App\Pipes\AttendanceProcessor\ProcessorHelper;
use Closure;

class LateComingAdjustment
{
    public function handle($data, Closure $next)
    {
        $punchingDataUnsorted = $data['punching_data'];
        $punchingDataSorted = orderResultSet($punchingDataUnsorted, 'late_coming_plus_early_going_minutes_adjustable', FALSE);
        $Grace = array_sum(array_column($punchingDataSorted, 'grace')) ?? 0;
        $WaveOffMinutes = array_sum(array_column($punchingDataSorted, 'wave_off_minutes')) ?? 0;

        $CompOffMinutes = array_sum(array_column($punchingDataSorted, 'comp_off_minutes')) ?? 0;

        $LateSitting = array_sum(array_column($punchingDataSorted, 'LateSittingMinutes')) ?? 0;
        $OverTime = array_sum(array_column($punchingDataSorted, 'OverTimeMinutes')) ?? 0;
        // $totalGrace = $Grace+$LateSitting+$WaveOffMinutes-$DeductionMinutes;
        $totalGrace = $Grace + $LateSitting + $WaveOffMinutes + $CompOffMinutes;

        foreach ($punchingDataSorted as $index => $dataRow) {

            $dataRow['is_halfDayLeave'] = 'no';
            $dataRow['halfDayStatus'] = '';
            $dataRow['halfDayStatusRemarks'] = '';
            $dataRow['halfDayPaid'] = '0';

            $dataRow['is_fullDayLeave'] = 'no';
            $dataRow['fullDayStatus'] = '';
            $dataRow['fullDayStatusRemarks'] = '';
            $dataRow['fullDayPaid'] = '0';
            if ($dataRow['is_onLeave'] == 'yes') {
                $LeaveRequestData = ProcessorHelper::is_onLeave($dataRow['date'], $dataRow['employee_id'], $dataRow['in_time_between_shift_with_od']);
                if ($LeaveRequestData['number_of_days'] == '0.5') {
                    $dataRow['is_halfDayLeave'] = 'yes';
                    $dataRow['halfDayStatus'] = $LeaveRequestData['type_of_leave'] . "/2";
                    $dataRow['halfDayStatusRemarks'] = $dataRow['halfDayStatus'] . " " . $LeaveRequestData['status'] . " by " . $LeaveRequestData['approved_by'];
                    $dataRow['halfDayPaid'] = $LeaveRequestData['type_of_leave'] == 'UL' ? '0' : '0.5';
                } elseif ($LeaveRequestData['number_of_days'] > '0.5') {
                    $dataRow['is_fullDayLeave'] = 'yes';
                    $dataRow['fullDayStatus'] = $LeaveRequestData['type_of_leave'];
                    $dataRow['fullDayStatusRemarks'] = $dataRow['fullDayStatus'] . " " . $LeaveRequestData['status'] . " by " . $LeaveRequestData['approved_by'];
                    $dataRow['fullDayPaid'] = $LeaveRequestData['type_of_leave'] == 'UL' ? '0' : '1';
                }
            }

            // if ($dataRow['DateString_2'] == '2025-10-10') {
            //     dd(
            //         $dataRow['half_day_because_of_work_hours'],
            //         $dataRow['absent_because_of_work_hours'],
            //         $dataRow['is_present'],
            //         $dataRow['is_onOD'],
            //         $dataRow['is_weekoff'],
            //         $dataRow['is_missed_punch'],
            //         $dataRow['is_holiday'],
            //         $dataRow['is_special_holiday'],
            //         $dataRow['is_RH'],
            //         $dataRow['is_fixed_off'],
            //         $dataRow['shift_type'],
            //     );
            // }

            if (
                ($dataRow['is_present'] == 'yes' || $dataRow['is_onOD'] == 'yes')
                && $dataRow['is_weekoff'] !== 'yes'
                && $dataRow['is_missed_punch'] !== 'yes'
                && $dataRow['is_holiday'] !== 'yes'
                && $dataRow['is_special_holiday'] !== 'yes'
                && $dataRow['is_RH'] !== 'yes'
                && $dataRow['is_sandwitch'] !== 'yes'
                && $dataRow['is_fixed_off'] !== 'yes'
            ) {

                $late_coming_plus_early_going_minutes_adjustable = $dataRow['late_coming_plus_early_going_minutes_adjustable'];

                #add status in sort_by_late_coming_row array
                if ($dataRow['half_day_because_of_work_hours'] == 'yes') {
                    if ($dataRow['is_fullDayLeave'] == 'yes') {
                        $dataRow['status']  = $dataRow['fullDayStatus'];
                        $dataRow['status_remarks']  = $dataRow['fullDayStatusRemarks'] . " <br> But you were present for half day, <br>please contact Developer/HR if your data is incorrect";
                        $dataRow['paid']  = '1';
                        #######late coming or early going remove#########
                        $dataRow['late_coming_minutes'] = 0;
                        $dataRow['early_going_minutes'] = 0;
                        $dataRow['early_going_minutes'] = 0;
                        $dataRow['late_coming_plus_early_going_minutes'] = 0;
                        #######late coming or early going remove#########
                    } elseif ($totalGrace >= $dataRow['minutes_required_for_full_day']) {
                        if ($dataRow['date'] == '2023-03-07') {
                            if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                                $dataRow['status']  = 'OD/2';
                            } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                                $dataRow['status']  = 'H/D';
                            } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                                $dataRow['status']  = 'OD/2';
                            }
                            if ($dataRow['shift_type'] == 'reduce') {
                                $dataRow['status_remarks']  = 'Late minutes cannot be adjusted <br>Balance minute: ' . round($totalGrace * 2 / 3) . '<br>Required minutes: ' . round($dataRow["minutes_required_for_full_day"] * 2 / 3);
                            } else {
                                $dataRow['status_remarks']  = 'Late minutes cannot be adjusted <br>Balance minute: ' . $totalGrace . '<br>Required minutes: ' . $dataRow["minutes_required_for_full_day"];
                            }
                            $dataRow['paid']  = '0.5';

                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['status'] . "+" . $dataRow['halfDayStatus'];
                                $paid  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Full day working But you were present for full day please contact Developer (adjsted ' . round($dataRow['minutes_required_for_full_day'] * 2 / 3) . ' minutes) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Full day working But you were present for full day please contact Developer (adjsted ' . $dataRow['minutes_required_for_full_day'] . ' minutes) ';
                                }
                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                #######late coming or early going remove#########
                            } else {
                                $dataRow['status'] = $dataRow['status'] . "+HL/2";
                                $paid  = $dataRow['paid'] + 0.5;
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                $dataRow['status_remarks']  = 'Half Day Working + Second half was company Holiday hence Status is present';
                                $dataRow['late_coming_plus_early_going_minutes'] = $dataRow['late_coming_plus_early_going_minutes'] - $dataRow['early_going_minutes'];
                                $dataRow['early_going_minutes'] = 0;
                            }
                        } else {

                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['status'] . "+" . $dataRow['halfDayStatus'];
                                $paid  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                // $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (adjsted '.$dataRow['minutes_required_for_full_day'].' minutes) ';

                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                $totalGrace = $totalGrace - $min;

                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (only adjsted ' . round($max * 2 / 3) . ' minutes with leave request, ' . round($min * 2 / 3) . ' minutes also deducted from grace) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (only adjsted ' . $max . ' minutes with leave request, ' . $min . ' minutes also deducted from grace) ';
                                }
                                #######late coming or early going remove#########
                            } else {
                                $totalGrace = $totalGrace - $dataRow['minutes_required_for_full_day'];
                                if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                                    $dataRow['status']  = 'OD';
                                } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                                    $dataRow['status']  = 'P';
                                } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                                    $dataRow['status']  = 'OD';
                                }
                                /*$dataRow['status_remarks']  = 'Remaining minutes adjusted to make it full day 1<br>Balance minute: '.$totalGrace.'<br>Required minutes: '.$dataRow["minutes_required_for_full_day"];*/
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = round($dataRow["minutes_required_for_full_day"] * 2 / 3) . ' minutes adjusted ';
                                } else {
                                    $dataRow['status_remarks']  = $dataRow["minutes_required_for_full_day"] . ' minutes adjusted ';
                                }



                                $dataRow['paid']  = '1';
                            }
                        }
                    } else {
                        #if( $dataRow['employee_id'] == '50' && $dataRow['date'] == '2023-03-07' ){
                        if ($dataRow['date'] == '2023-03-07') {
                            if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                                $dataRow['status']  = 'OD/2';
                            } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                                $dataRow['status']  = 'H/D';
                            } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                                $dataRow['status']  = 'OD/2';
                            }
                            if ($dataRow['shift_type'] == 'reduce') {
                                $dataRow['status_remarks']  = 'Late minutes cannot be adjusted <br>Balance minute: ' . round($totalGrace * 2 / 3) . '<br>Required minutes: ' . round($dataRow["minutes_required_for_full_day"] * 2 / 3);
                            } else {
                                $dataRow['status_remarks']  = 'Late minutes cannot be adjusted <br>Balance minute: ' . $totalGrace . '<br>Required minutes: ' . $dataRow["minutes_required_for_full_day"];
                            }
                            $dataRow['paid']  = '0.5';

                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['status'] . "+" . $dataRow['halfDayStatus'];
                                $paid  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Full day working But you were present for full day please contact Developer (adjsted ' . round($dataRow['minutes_required_for_full_day'] * 2 / 3) . ' minutes) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Full day working But you were present for full day please contact Developer (adjsted ' . $dataRow['minutes_required_for_full_day'] . ' minutes) ';
                                }
                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Full day working But you were present for full day please contact Developer (adjsted ' . round($dataRow['minutes_required_for_full_day'] * 2 / 3) . ' minutes) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Full day working But you were present for full day please contact Developer (adjsted ' . $dataRow['minutes_required_for_full_day'] . ' minutes) ';
                                }

                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                #######late coming or early going remove#########
                            } else {
                                $dataRow['status'] = $dataRow['status'] . "+HL/2";
                                $paid  = $dataRow['paid'] + 0.5;
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;

                                $dataRow['status_remarks']  = 'Half Day Working + Second half was company Holiday hence Status is present';
                                $dataRow['late_coming_plus_early_going_minutes'] = $dataRow['late_coming_plus_early_going_minutes'] - $dataRow['early_going_minutes'];
                                $dataRow['early_going_minutes'] = 0;
                            }
                        } else {

                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['status'] . '+' . $dataRow['halfDayStatus'];
                                $dataRow['paid']  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                                // $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (adjsted '.$dataRow['minutes_required_for_full_day'].' minutes) ';

                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                // $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (adjsted '.$min.' minutes) ';
                                $totalGrace = $totalGrace - $min;
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (only adjsted ' . round($max * 2 / 3) . ' minutes with leave request, ' . round($min * 2 / 3) . ' minutes also deducted from grace) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (only adjsted ' . $max . ' minutes with leave request, ' . $min . ' minutes also deducted from grace) ';
                                }

                                #######late coming or early going remove#########
                            } else {
                                if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                                    $dataRow['status']  = 'OD/2';
                                } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                                    $dataRow['status']  = 'H/D';
                                } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                                    $dataRow['status']  = 'OD/2';
                                }
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Your work hours between the shift timing is less than minimum required, see attendance rule in your shift <br>Balance minute: ' . round($totalGrace * 2 / 3) . '<br>Required minutes: ' . round($dataRow["minutes_required_for_full_day"] * 2 / 3);
                                } else {
                                    $dataRow['status_remarks']  = 'Your work hours between the shift timing is less than minimum required, see attendance rule in your shift <br>Balance minute: ' . $totalGrace . '<br>Required minutes: ' . $dataRow["minutes_required_for_full_day"];
                                }

                                $dataRow['paid']  = '0.5';
                            }
                        }
                    }
                } elseif ($dataRow['absent_because_of_work_hours'] == 'yes') {

                    if ($dataRow['is_fullDayLeave'] == 'yes') {
                        $dataRow['status']  = $dataRow['fullDayStatus'];
                        $dataRow['status_remarks']  = $dataRow['fullDayStatusRemarks'] . " <br> But you were present, <br>please contact Developer/HR if your data is incorrect";
                        $dataRow['paid']  = '1';
                        #######late coming or early going remove######### Added on 2025-01-15 by Nazrul
                        $dataRow['late_coming_minutes'] = 0;
                        $dataRow['early_going_minutes'] = 0;
                        $dataRow['early_going_minutes'] = 0;
                        $dataRow['late_coming_plus_early_going_minutes'] = 0;
                        #######late coming or early going remove#########
                    } elseif ($totalGrace >= $dataRow['minutes_required_for_full_day']) {
                        if ($dataRow['date'] == '2023-03-07') {
                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['halfDayStatus'];
                                $paid  = $dataRow['halfDayPaid'];
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                $dataRow['status_remarks']  = 'Approved Half day Leave Request';

                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                #######late coming or early going remove#########
                            } else {
                                $dataRow['status'] = "HL/2";
                                $paid  = 0.5;
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                $dataRow['status_remarks']  = 'Second half was company Holiday and you were not present for minimum required hours for First half hence HL/2';
                                $dataRow['late_coming_plus_early_going_minutes'] = $dataRow['late_coming_plus_early_going_minutes'] - $dataRow['early_going_minutes'];
                                $dataRow['early_going_minutes'] = 0;
                            }
                        } else {

                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['status'] . "+" . $dataRow['halfDayStatus'];
                                $paid  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                // $dataRow['status_remarks']  = 'Approved Half day Leave Request (adjsted '.$dataRow['minutes_required_for_full_day'].' minutes) ';

                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                $totalGrace = $totalGrace - $min;
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request (only adjsted ' . round($max * 2 / 3) . ' minutes with leave request, ' . round($min * 2 / 3) . ' minutes also deducted from grace) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request (only adjsted ' . $max . ' minutes with leave request, ' . $min . ' minutes also deducted from grace) ';
                                }

                                #######late coming or early going remove#########
                            } else {
                                $totalGrace = $totalGrace - $dataRow['minutes_required_for_full_day'];
                                if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                                    $dataRow['status']  = 'OD';
                                } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                                    $dataRow['status']  = 'P';
                                } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                                    $dataRow['status']  = 'OD';
                                }
                                // $dataRow['status_remarks']  = 'Remaining minutes adjusted to make it full day <br>Balance minute: '.$totalGrace.'<br>Required minutes: '.$dataRow["minutes_required_for_full_day"];
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = round($dataRow["minutes_required_for_full_day"] * 2 / 3) . ' minutes adjusted ';
                                } else {
                                    $dataRow['status_remarks']  = $dataRow["minutes_required_for_full_day"] . ' minutes adjusted ';
                                }

                                $dataRow['paid']  = '1';
                            }
                        }
                    } elseif ($dataRow['wave_off_half_day_who_did_not_work_for_half_day'] == 'yes' && $totalGrace >= $dataRow['minutes_required_for_half_day']) {
                        if ($dataRow['date'] == '2023-03-07') {
                            $totalGrace = $totalGrace - $dataRow['minutes_required_for_half_day'];
                            if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                                $dataRow['status']  = 'OD/2';
                            } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                                $dataRow['status']  = 'H/D';
                            } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                                $dataRow['status']  = 'OD/2';
                            }
                            // $dataRow['status_remarks']  = 'Remaining minutes adjusted to make it half day <br>Balance minute: '.$totalGrace.'<br>Required minutes: '.$dataRow["minutes_required_for_half_day"];
                            if ($dataRow['shift_type'] == 'reduce') {
                                $dataRow['status_remarks']  = round($dataRow["minutes_required_for_half_day"] * 2 / 3) . ' minutes adjusted ';
                            } else {
                                $dataRow['status_remarks']  = $dataRow["minutes_required_for_half_day"] . ' minutes adjusted ';
                            }

                            $dataRow['paid']  = '0.5';

                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['status'] . "+" . $dataRow['halfDayStatus'];
                                $paid  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (adjsted ' . round($dataRow['minutes_required_for_half_day'] * 2 / 3) . ' minutes) ' . '.....';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (adjsted ' . $dataRow['minutes_required_for_half_day'] . ' minutes) ' . '.....';
                                }


                                ######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                #######late coming or early going remove#########
                            } else {
                                $dataRow['status'] = $dataRow['status'] . "+HL/2";
                                $paid  = $dataRow['paid'] + 0.5;
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                $dataRow['status_remarks']  = 'Half Day Working + Second half was company Holiday hence Status is present';
                                $dataRow['late_coming_plus_early_going_minutes'] = $dataRow['late_coming_plus_early_going_minutes'] - $dataRow['early_going_minutes'];
                                $dataRow['early_going_minutes'] = 0;
                            }
                        } else {
                            $totalGrace = $totalGrace - $dataRow['minutes_required_for_half_day'];
                            if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                                $dataRow['status']  = 'OD/2';
                            } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                                $dataRow['status']  = 'H/D';
                            } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                                $dataRow['status']  = 'OD/2';
                            }
                            if ($dataRow['shift_type'] == 'reduce') {
                                $dataRow['status_remarks']  = 'Remaining minutes adjusted to make it half day <br>Balance minute: ' . round($totalGrace * 2 / 3) . '<br>Required minutes: ' . round($dataRow["minutes_required_for_half_day"] * 2 / 3);
                            } else {
                                $dataRow['status_remarks']  = 'Remaining minutes adjusted to make it half day <br>Balance minute: ' . $totalGrace . '<br>Required minutes: ' . $dataRow["minutes_required_for_half_day"];
                            }

                            $dataRow['paid'] = '0.5';

                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['status'] . "+" . $dataRow['halfDayStatus'];
                                $paid  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;

                                ######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                $totalGrace = $totalGrace - $min;
                                if ($dataRow['shift_type'] == 'reduce') {

                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (only adjsted ' . round($max * 2 / 3) . ' minutes with leave request, ' . round($min * 2 / 3) . ' minutes also deducted from grace) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (only adjsted ' . $max . ' minutes with leave request, ' . $min . ' minutes also deducted from grace) ';
                                }

                                #######late coming or early going remove#########
                            }
                        }
                    } else {
                        if ($dataRow['date'] == '2023-03-07') {
                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['halfDayStatus'];
                                $paid  = $dataRow['halfDayPaid'];
                                $dataRow['paid']  = $paid;
                                $dataRow['status_remarks']  = 'Approved Half day Leave Request, Work hour is not enough to make full day';

                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                // $totalGrace = $totalGrace - $min;
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (adjsted ' . round($min * 2 / 3) . ' minutes) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (adjsted ' . $min . ' minutes) ';
                                }

                                #######late coming or early going remove#########

                            } else {
                                $dataRow['status'] = "HL/2";
                                $paid  = 0.5;
                                $dataRow['paid']  = $paid > 1 ? 1 : $paid;
                                $dataRow['status_remarks'] = 'Second half was company Holiday and you were not present for minimum required hours for First half Hence HL/2';
                                $dataRow['late_coming_plus_early_going_minutes'] = $dataRow['late_coming_plus_early_going_minutes'] - $dataRow['early_going_minutes'];
                                $dataRow['early_going_minutes'] = 0;
                            }
                        } else {

                            if ($dataRow['is_halfDayLeave'] == 'yes') {
                                $dataRow['status'] = $dataRow['halfDayStatus'];
                                $dataRow['paid']  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Your work hours between the shift timing is less than minimum required, see attendance rule in your shift <br>Balance minute: ' . round($totalGrace * 2 / 3) . '<br>Required minutes: ' . round($dataRow["minutes_required_for_half_day"] * 2 / 3);
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request + Your work hours between the shift timing is less than minimum required, see attendance rule in your shift <br>Balance minute: ' . $totalGrace . '<br>Required minutes: ' . $dataRow["minutes_required_for_half_day"];
                                }


                                #######late coming or early going remove#########
                                $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                                $dataRow['late_coming_plus_early_going_minutes'] = $min;
                                if ($min == $dataRow['late_coming_minutes']) {
                                    $dataRow['early_going_minutes'] = 0;
                                } elseif ($min == $dataRow['early_going_minutes']) {
                                    $dataRow['late_coming_minutes'] = 0;
                                }
                                $totalGrace = $totalGrace - $min;
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request (only adjsted ' . round($max * 2 / 3) . ' minutes with leave request, ' . round($min * 2 / 3) . ' minutes also deducted from grace) ';
                                } else {
                                    $dataRow['status_remarks']  = 'Approved Half day Leave Request (only adjsted ' . $max . ' minutes with leave request, ' . $min . ' minutes also deducted from grace) ';
                                }

                                #######late coming or early going remove#########
                            } else {
                                if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                                    $dataRow['status']  = 'A';
                                } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                                    $dataRow['status']  = 'A';
                                } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                                    $dataRow['status']  = 'A';
                                }
                                $fraud_remarks  = $dataRow['fraud_remarks'] ?? '';
                                if ($dataRow['shift_type'] == 'reduce') {
                                    $dataRow['status_remarks']  = $fraud_remarks . 'Your work hours between the shift timing is less than minimum required, see attendance rule in your shift <br>Balance minute: ' . round($totalGrace * 2 / 3) . '<br>Required minutes: ' . $dataRow["minutes_required_for_half_day"] . ' work_minutes_between_shifts_including_od=' . round($dataRow['work_minutes_between_shifts_including_od'] * 2 / 3);
                                } else {
                                    $dataRow['status_remarks']  = $fraud_remarks . 'Your work hours between the shift timing is less than minimum required, see attendance rule in your shift <br>Balance minute: ' . $totalGrace . '<br>Required minutes: ' . $dataRow["minutes_required_for_half_day"] . ' work_minutes_between_shifts_including_od=' . $dataRow['work_minutes_between_shifts_including_od'];
                                }

                                $dataRow['paid']  = '0';
                            }
                        }
                    }
                } else {
                    if ($dataRow['is_fullDayLeave'] == 'yes') {
                        $dataRow['status']  = $dataRow['fullDayStatus'];
                        $dataRow['status_remarks']  = $dataRow['fullDayStatusRemarks'] . " <br> But you were present for half day, <br>please contact Developer/HR if your data is incorrect";
                        $dataRow['paid']  = '1';
                    } elseif ($dataRow['is_halfDayLeave'] == 'yes') {
                        if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                            $dataRow['status']  = 'OD/2';
                        } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                            $dataRow['status']  = 'H/D';
                        } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                            $dataRow['status']  = 'OD/2';
                        }
                        $dataRow['paid']  = '0.5';

                        $dataRow['status'] = $dataRow['status'] . "+" . $dataRow['halfDayStatus'];
                        $dataRow['paid']  = $dataRow['paid'] + $dataRow['halfDayPaid'];
                        $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working';

                        #######late coming or early going remove#########
                        $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                        $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                        $dataRow['late_coming_plus_early_going_minutes'] = $min;
                        if ($min == $dataRow['late_coming_minutes']) {
                            $dataRow['early_going_minutes'] = 0;
                        } elseif ($min == $dataRow['early_going_minutes']) {
                            $dataRow['late_coming_minutes'] = 0;
                        }
                        $totalGrace = $totalGrace - $min;
                        if ($dataRow['shift_type'] == 'reduce') {
                            $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (only adjsted ' . round($max * 2 / 3) . ' minutes with leave request, ' . round($min * 2 / 3) . ' minutes also deducted from grace) ';
                        } else {
                            $dataRow['status_remarks']  = 'Approved Half day Leave Request + Half day working (only adjsted ' . $max . ' minutes with leave request, ' . $min . ' minutes also deducted from grace) ';
                        }

                        #######late coming or early going remove#########
                    } else {
                        if ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] == 'yes') {
                            $dataRow['status']  = 'OD';
                            #$dataRow['status_remarks'] = $late_coming_plus_early_going_minutes_adjustable.' Late coming minutes adjusted remarks: P+OD';
                            $dataRow['status_remarks'] = 'Present remarks: P+OD';
                        } elseif ($dataRow['is_present'] == 'yes' && $dataRow['is_onOD'] !== 'yes') {
                            $dataRow['status']  = 'P';
                            #$dataRow['status_remarks'] = $late_coming_plus_early_going_minutes_adjustable.' Late coming minutes adjusted remarks: P';
                            $dataRow['status_remarks'] = 'Present remarks: P';
                            // if (strtotime($dataRow["in_time"]) > strtotime($dataRow["out_time"]) && $dataRow["shift_id"] != 107) {
                            /* if (strtotime($dataRow["in_time"]) > strtotime($dataRow["out_time"])) {
                                // if( strtotime($dataRow["in_time"]) > strtotime($dataRow["out_time"]) ){
                                $dataRow['status_remarks'] = 'Present remarks: P <br> <span class="text-danger" style="font-size:1.5rem">Worked continue for 2 days</span><br><span class="text-danger">if you think this is a mistake, please contact developer or HR</span>';
                            } */

                            #$dataRow['status_remarks'] = 'Present remarks......: P<br> absent_for_work_hours_minutes'.$dataRow["absent_for_work_hours_minutes"].'<br> work_minutes_between_shifts_including_od'.$dataRow["work_minutes_between_shifts_including_od"].'<br> in_time'.$dataRow["in_time"].'<br> out_time'.$dataRow["out_time"];

                        } elseif ($dataRow['is_present'] !== 'yes' && $dataRow['is_onOD'] == 'yes') {
                            $dataRow['status']  = 'OD';
                            #$dataRow['status_remarks'] = $late_coming_plus_early_going_minutes_adjustable.' Late coming minutes adjusted remarks: OD';
                            $dataRow['status_remarks'] = 'Present remarks: OD';
                        }
                        $dataRow['paid']  = '1';
                    }
                }
            } elseif (
                $dataRow['is_absent'] == 'yes'
                && $dataRow['is_weekoff'] !== 'yes'
                && $dataRow['is_missed_punch'] !== 'yes'
                && $dataRow['is_holiday'] !== 'yes'
                && $dataRow['is_special_holiday'] !== 'yes'
                && $dataRow['is_RH'] !== 'yes'
                && $dataRow['is_onLeave'] !== 'yes'
                && $dataRow['is_sandwitch'] !== 'yes'
                && $dataRow['is_fixed_off'] !== 'yes'
            ) {
                if ($dataRow['is_fullDayLeave'] == 'yes') {
                    $dataRow['status']  = $dataRow['fullDayStatus'];
                    $dataRow['status_remarks']  = $dataRow['fullDayStatusRemarks'];
                    $dataRow['paid']  = $dataRow['fullDayPaid'];
                } elseif ($dataRow['is_halfDayLeave'] == 'yes') {
                    $dataRow['paid']  = $dataRow['halfDayPaid'];
                    $dataRow['status'] = $dataRow['halfDayStatus'];
                    $dataRow['status_remarks']  = $dataRow['halfDayStatusRemarks'];

                    #######late coming or early going remove#########
                    $min = min($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                    $max = max($dataRow['late_coming_minutes'], $dataRow['early_going_minutes']);
                    $dataRow['late_coming_plus_early_going_minutes'] = $min;
                    if ($min == $dataRow['late_coming_minutes']) {
                        $dataRow['early_going_minutes'] = 0;
                    } elseif ($min == $dataRow['early_going_minutes']) {
                        $dataRow['late_coming_minutes'] = 0;
                    }
                    #######late coming or early going remove#########

                } else {
                    if ($totalGrace >= $dataRow['late_coming_plus_early_going_minutes_adjustable']) {
                        $late_coming_plus_early_going_minutes_adjustable = $dataRow['late_coming_plus_early_going_minutes_adjustable'];
                        $totalGrace = $totalGrace - $late_coming_plus_early_going_minutes_adjustable;
                        $dataRow['status']  = 'INC';
                        #$dataRow['status_remarks'] = $late_coming_plus_early_going_minutes_adjustable.' Late coming minutes adjusted remarks: A';
                        if ($dataRow['shift_type'] == 'reduce') {
                            $dataRow['status_remarks'] = "Absent but adjusted " . round($late_coming_plus_early_going_minutes_adjustable * 2 / 3) . " Remaining grace minutes to make it full day paid";
                        } else {
                            $dataRow['status_remarks'] = "Absent but adjusted " . $late_coming_plus_early_going_minutes_adjustable . " Remaining grace minutes to make it full day paid";
                        }

                        $dataRow['paid']  = '1';

                        /*$dataRow['late_coming_plus_early_going_minutes'] = $dataRow['late_coming_plus_early_going_minutes_adjustable'];
                        $dataRow['late_coming_minutes'] = $dataRow['late_coming_plus_early_going_minutes'];
                        $dataRow['early_going_minutes'] = 0;*/
                    } else {
                        $dataRow['status']  = 'A';
                        $fraud_remarks  = $dataRow['fraud_remarks'] ?? "";
                        if ($dataRow['shift_type'] == 'reduce') {
                            $dataRow['status_remarks']  = $fraud_remarks . "Already absent Late minutes cannot be adjusted,,, <br> Balance Minutes: [" . round($totalGrace * 2 / 3) . "]<br>Required: " . round($dataRow['late_coming_plus_early_going_minutes_adjustable'] * 2 / 3);
                        } else {
                            $dataRow['status_remarks']  = $fraud_remarks . "Already absent Late minutes cannot be adjusted,,, <br> Balance Minutes: [" . $totalGrace . "]<br>Required: " . $dataRow['late_coming_plus_early_going_minutes_adjustable'];
                        }
                        $dataRow['paid']  = '0';
                    }
                }
            } elseif (($dataRow['is_present'] == 'yes' || $dataRow['is_onOD'] == 'yes') && $dataRow['is_weekoff'] == 'yes') {
                $data['status']          = "W/O";
                $data['status_remarks']  = "Week Off";
                $data['paid']            = '1';
            }

            if (
                isset($dataRow["in_time"]) && !empty($dataRow["in_time"]) && $dataRow["in_time"] != '--:--'
                && isset($dataRow["out_time"]) && !empty($dataRow["out_time"]) && $dataRow["out_time"] != '--:--'
                && strtotime($dataRow["in_time"]) > strtotime($dataRow["out_time"])
            ) {
                // if( strtotime($dataRow["in_time"]) > strtotime($dataRow["out_time"]) ){
                $dataRow['status_remarks'] = $dataRow['status_remarks'] . '<br> <span class="text-danger" style="font-size:1.5rem">Worked continue for 2 days</span><br><span class="text-danger">if you think this is a mistake, please contact developer or HR</span>';
            }
            $punchingDataSorted[$index] = $dataRow;

            // if ($dataRow['employee_id'] == '316' && $dataRow['date'] == '2025-12-03') {
            //     dd($dataRow);
            // }
        }

        $data['balance_grace'] = $totalGrace;
        $data['punching_data'] = $punchingDataSorted;

        return $next($data);
    }
}
