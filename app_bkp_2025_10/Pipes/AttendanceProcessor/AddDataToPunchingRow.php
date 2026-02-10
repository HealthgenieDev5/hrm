<?php

namespace App\Pipes\AttendanceProcessor;


use App\Libraries\Pipeline;
use App\Models\CompOffMinutesUtilizedModel;
use App\Models\DeductionModel;
use App\Models\EmployeeModel;
use App\Models\FixedRhModel;
use App\Models\HolidayModel;
use App\Models\LeaveRequestsModel;
use App\Models\OdRequestsModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftModel;
use App\Models\ShiftOverrideModel;
use App\Models\SpecialHolidayEmployeesModel;
use App\Models\WaveOffHalfDayWhoDidNotWorkForHalfDayModel;
use App\Models\WaveOffModel;
use Closure;

class AddDataToPunchingRow
{

    public function handle($punching_row, Closure $next)
    {

        $employee_id = $punching_row['current_user_data']['id'];
        if (!empty($punching_row['in_time'])) {
            $punching_row['late_coming_minutes']    = ProcessorHelper::get_late_coming_minutes($punching_row['shift_start'], $punching_row['in_time']);
        } else {
            $punching_row['late_coming_minutes']    = ProcessorHelper::get_late_coming_minutes($punching_row['shift_start'], $punching_row['in_time__Raw']);
        }


        if (!empty($punching_row['out_time'])) {
            $punching_row['early_going_minutes']    = ProcessorHelper::get_early_going_minutes($punching_row['shift_end'], $punching_row['out_time'], $punching_row['in_time']);
        } else {
            $punching_row['early_going_minutes']    = ProcessorHelper::get_early_going_minutes($punching_row['shift_end'], $punching_row['out_time__Raw'], $punching_row['in_time__Raw']);
            // $punching_row['early_going_minutes']    = 55;
        }
        // if ($punching_row['DateString_2'] == '2025-04-24') {
        //     dd(
        //         $punching_row['shift_start'],
        //         $punching_row['in_time'],
        //         $punching_row['in_time__Raw'],
        //         $punching_row['late_coming_minutes'],
        //     );
        // }

        $punching_row['comp_off_minutes']       = ProcessorHelper::get_comp_off_minutes($employee_id, $punching_row['date']);

        $punching_row['wave_off_minutes']       = ProcessorHelper::get_wave_off_minutes($employee_id, $punching_row['date']);
        $punching_row['wave_off_remarks']       = ProcessorHelper::get_wave_off_remarks($employee_id, $punching_row['date']);

        $punching_row['deduction_minutes']       = ProcessorHelper::get_deduction_minutes($employee_id, $punching_row['date']);
        $punching_row['deduction_remarks']       = ProcessorHelper::get_deduction_remarks($employee_id, $punching_row['date']);

        $punching_row['wave_off_half_day_who_did_not_work_for_half_day']       = ProcessorHelper::get_wave_off_half_day_who_did_not_work_for_half_day($employee_id, $punching_row['date']);
        $punching_row['wave_off_half_day_who_did_not_work_for_half_day_remarks']       = ProcessorHelper::get_wave_off_half_day_who_did_not_work_for_half_day_remarks($employee_id, $punching_row['date']);

        #$punching_row['late_coming_plus_early_going_minutes'] = (string)($punching_row['late_coming_minutes']+$punching_row['early_going_minutes']);
        #2024-05-21
        $punching_row['late_coming_plus_early_going_minutes'] = (string)($punching_row['late_coming_minutes'] + $punching_row['early_going_minutes'] + $punching_row['deduction_minutes']);

        $punching_row['od_hours_between_shifts'] = ProcessorHelper::get_od_hours_between_shifts($punching_row['date'], $punching_row['shift_start'], $punching_row['shift_end'], $punching_row['employee_id'], 'hours');

        $punching_row['is_weekoff']             = ProcessorHelper::is_weekoff($punching_row['shift_id'], $punching_row['date']);

        $punching_row['is_holiday']         = ProcessorHelper::is_holiday($punching_row['date'], 'bool', $punching_row['machine'], $punching_row['employee_data']['company_id']);

        $punching_row['is_special_holiday']     = ProcessorHelper::is_special_holiday($punching_row['date'], $employee_id, 'bool');

        $punching_row['RH_DATA']                = ProcessorHelper::is_RH($punching_row['date'], $employee_id);
        $punching_row['is_RH']                  = !empty($punching_row['RH_DATA']) ? 'yes' : 'no';

        $punching_row['is_fixed_off']           = ProcessorHelper::is_fixed_off($punching_row['date'], $employee_id);
        $punching_row['LeaveData']              = ProcessorHelper::is_onLeave($punching_row['date'], $employee_id, $punching_row['in_time__Raw']);
        $punching_row['is_onLeave']             = !empty($punching_row['LeaveData']) ? 'yes' : 'no';
        $punching_row['is_onOD']                = ProcessorHelper::is_onOD($punching_row['date'], $employee_id);

        $punching_row['is_on_InternationOD']    = ProcessorHelper::is_on_InternationOD($punching_row['date'], $employee_id);

        // if ($punching_row['employee_id'] == '385' && $punching_row['date'] == '2025-12-20') {
        //     print_r($punching_row);
        // }

        // if( $punching_row['is_on_InternationOD'] == 'yes' && $punching_row['is_weekoff'] == 'yes' ){
        //     $punching_row['is_onOD']            = 'no';
        // }else{
        // $punching_row['is_onOD']            = ProcessorHelper::is_onOD( $punching_row['date'], $employee_id );
        // }

        $punching_row['is_sandwitch']           = ProcessorHelper::is_sandwitch($punching_row['shift_id'], $punching_row['date'], $punching_row['current_user_data']['internal_employee_id'], $employee_id);
        $punching_row['is_present']             = ProcessorHelper::is_present($punching_row['in_time__Raw'], $punching_row['out_time__Raw']);

        $punching_row['is_absent']             = ProcessorHelper::is_absent($punching_row['in_time__Raw'], $punching_row['out_time__Raw']);
        if ($punching_row['is_absent'] == 'yes') {


            if (strtotime($punching_row['shift_start']) > strtotime($punching_row['shift_end'])) {
                $late_coming_plus_early_going_minutes_adjustable_day1 = ProcessorHelper::get_time_difference($punching_row['shift_start'], '23:59', 'minutes');
                $late_coming_plus_early_going_minutes_adjustable_day2 = ProcessorHelper::get_time_difference('00:00', $punching_row['shift_end'], 'minutes');

                // if( session()->get('current_user')['employee_id'] == '40' && $employee_id == '407' && $punching_row['date'] == '2025-01-20' ){
                //     echo $late_coming_plus_early_going_minutes_adjustable_day1 . '<br>'; 
                //     echo $late_coming_plus_early_going_minutes_adjustable_day2 . '<br>'; 
                //     die();
                // }

                $punching_row['late_coming_plus_early_going_minutes_adjustable'] = ($late_coming_plus_early_going_minutes_adjustable_day1 + $late_coming_plus_early_going_minutes_adjustable_day2 + 1) - 30;
            } else {
                $punching_row['late_coming_plus_early_going_minutes_adjustable'] = ProcessorHelper::get_time_difference($punching_row['shift_start'], $punching_row['shift_end'], 'minutes') - 30;
            }

            /*if( session()->get('current_user')['employee_id'] == '40' && $employee_id == '407' && $punching_row['date'] == '2025-01-20' ){
                echo $punching_row['late_coming_plus_early_going_minutes_adjustable'] . '<br>'; 
                echo $punching_row['shift_start'] . '<br>'; 
                echo $punching_row['shift_end'] . '<br>'; 
                echo $late_coming_plus_early_going_minutes_adjustable_day1 . '<br>'; 
                echo $late_coming_plus_early_going_minutes_adjustable_day2 . '<br>'; 
                die();
            }*/
        } else {
            $punching_row['late_coming_plus_early_going_minutes_adjustable'] = $punching_row['late_coming_plus_early_going_minutes'];
        }

        $punching_row['is_missed_punch']        = ProcessorHelper::is_missed_punch($punching_row['in_time__Raw'], $punching_row['out_time__Raw']);

        if (!empty($punching_row['out_time']) && !empty($punching_row['in_time']) && (strtotime($punching_row['out_time']) < strtotime($punching_row['in_time']))) {
            $work_minutes_between_shifts_including_od_day1 = ProcessorHelper::get_time_difference($punching_row['in_time'], '23:59', 'minutes');
            $work_minutes_between_shifts_including_od_day2 = ProcessorHelper::get_time_difference('00:00', $punching_row['out_time'], 'minutes');
            $punching_row['work_minutes_between_shifts_including_od_day1'] = $work_minutes_between_shifts_including_od_day1;
            $punching_row['work_minutes_between_shifts_including_od_day2'] = $work_minutes_between_shifts_including_od_day2;

            #$punching_row['work_minutes_between_shifts_including_od'] = $work_minutes_between_shifts_including_od_day1 + $work_minutes_between_shifts_including_od_day2 + 1;
            #2024-05-21

            $punching_row['work_minutes_between_shifts_including_od'] = $work_minutes_between_shifts_including_od_day1 + $work_minutes_between_shifts_including_od_day2 + 1 - $punching_row['deduction_minutes'];
        } else {
            #$punching_row['work_minutes_between_shifts_including_od'] = ProcessorHelper::get_time_difference( $punching_row['in_time'], $punching_row['out_time'], 'minutes');
            #2024-05-21
            $punching_row['work_minutes_between_shifts_including_od'] = ProcessorHelper::get_time_difference($punching_row['in_time'], $punching_row['out_time'], 'minutes') - $punching_row['deduction_minutes'];
        }

        $punching_row['half_day_because_of_work_hours'] = "no";
        $punching_row['absent_because_of_work_hours'] = "no";
        if ($punching_row['work_minutes_between_shifts_including_od'] < $punching_row['absent_for_work_hours_minutes']) {
            $punching_row['absent_because_of_work_hours'] = "yes";
            $punching_row['half_day_because_of_work_hours'] = "no";
            $punching_row['minutes_required_for_half_day'] = $punching_row['absent_for_work_hours_minutes'] - $punching_row['work_minutes_between_shifts_including_od'];
            $punching_row['minutes_required_for_full_day'] = $punching_row['half_day_for_work_hours_minutes'] - $punching_row['work_minutes_between_shifts_including_od'];
        } elseif ($punching_row['work_minutes_between_shifts_including_od'] < $punching_row['half_day_for_work_hours_minutes']) {
            $punching_row['absent_because_of_work_hours'] = "no";
            $punching_row['half_day_because_of_work_hours'] = "yes";
            $punching_row['minutes_required_for_half_day'] = $punching_row['absent_for_work_hours_minutes'] - $punching_row['work_minutes_between_shifts_including_od'];
            $punching_row['minutes_required_for_full_day'] = $punching_row['half_day_for_work_hours_minutes'] - $punching_row['work_minutes_between_shifts_including_od'];
        }

        $punching_row['work_hours_between_shifts_including_od'] = str_pad(floor($punching_row['work_minutes_between_shifts_including_od'] / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($punching_row['work_minutes_between_shifts_including_od'] - floor($punching_row['work_minutes_between_shifts_including_od'] / 60) * 60), 2, '0', STR_PAD_LEFT);

        $punching_row['shift_start']            = (!empty($punching_row['shift_start'])) ? date('h:i A', strtotime($punching_row['shift_start'])) : null;
        $punching_row['shift_end']              = (!empty($punching_row['shift_end'])) ? date('h:i A', strtotime($punching_row['shift_end'])) : null;

        $punching_row['in_time_between_shift_with_od']              = !empty($punching_row['in_time']) ? date('h:i A', strtotime($punching_row['in_time'])) : null;
        $punching_row['out_time_between_shift_with_od']              = !empty($punching_row['out_time']) ? date('h:i A', strtotime($punching_row['out_time'])) : null;
        $punching_row['punch_in_time']              = !empty($punching_row['in_time__Raw']) ? date('h:i A', strtotime($punching_row['in_time__Raw'])) : null;
        $punching_row['punch_out_time']             = !empty($punching_row['out_time__Raw']) ? date('h:i A', strtotime($punching_row['out_time__Raw'])) : null;

        $punching_row['in_time_including_od']              = !empty($punching_row['in_time_including_od']) ? date('h:i A', strtotime($punching_row['in_time_including_od'])) : null;
        $punching_row['out_time_including_od']             = !empty($punching_row['out_time_including_od']) ? date('h:i A', strtotime($punching_row['out_time_including_od'])) : null;

        if (
            $punching_row['is_weekoff'] == 'yes' || $punching_row['is_holiday'] == 'yes' || $punching_row['is_special_holiday'] == 'yes' || $punching_row['is_fixed_off'] == 'yes' || $punching_row['is_RH'] == 'yes'
            // || $punching_row['is_onLeave'] == 'yes'
        ) {
            $punching_row['late_coming_minutes'] = $punching_row['early_going_minutes'] = $punching_row['late_coming_plus_early_going_minutes'] = $punching_row['late_coming_plus_early_going_minutes_adjustable'] = $punching_row['minutes_required_for_half_day'] = $punching_row['minutes_required_for_full_day'] = 0;
            $punching_row['half_day_because_of_work_hours'] = "no";
            $punching_row['absent_because_of_work_hours'] = "no";
        }

        // if ($punching_row['employee_id'] == '385' && $punching_row['date'] == '2025-12-20') {
        //     print_r($punching_row);
        //     die;
        // }

        return $next($punching_row);
    }
}
