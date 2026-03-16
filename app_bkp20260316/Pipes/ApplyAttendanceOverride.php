<?php

namespace App\Pipes;

use App\AttendanceProcessor\ProcessorHelper;
use App\Models\AttendanceOverrideModel;
use Closure;

class ApplyAttendanceOverride
{
    public function handle($data, Closure $next)
    {
        $punching_data_sorted = $data['punching_data'];

        $AttendanceOverrideModel = new AttendanceOverrideModel();
        $AllAttendanceOverrides = $AttendanceOverrideModel->where('employee_id =', $data['employee_id'])->findAll();
        if (!empty($AllAttendanceOverrides) && !empty($punching_data_sorted)) {
            foreach ($AllAttendanceOverrides as $AttendanceOverride) {
                $attendance = $AttendanceOverride['attendance'];
                $attendance_date = $AttendanceOverride['attendance_date'];
                $attendance_remarks = $AttendanceOverride['remarks'];

                $status = $attendance;
                $status_remarks = "Override Remarks:<br>" . $attendance_remarks;

                if (in_array($attendance, ['P', 'CL', 'COMP OFF', 'ML', 'H/D+CL/2', 'H/D+COMP OFF/2', 'INC'])) {
                    $paid = '1';
                } elseif (in_array($attendance, ['H/D', 'CL/2', 'COMP OFF/2'])) {
                    $paid = '0.5';
                } elseif (in_array($attendance, ['A'])) {
                    $paid = '0';
                } else {
                    $status = '';
                    $status_remarks = '';
                    $paid = '';
                }


                foreach ($punching_data_sorted as $index => $row) {
                    if ($AttendanceOverride['attendance_date'] == $row['DateString_2']) {
                        $punching_data_sorted[$index]['status'] = !empty($status) ? $status : $row['status'];
                        $punching_data_sorted[$index]['status_remarks'] = !empty($status) ? $status_remarks . " <br><br> Original remarks:<br>" . $row['status_remarks'] : $row['status_remarks'];
                        $punching_data_sorted[$index]['paid'] = !empty($status) ? $paid : $row['paid'];
                        //added by sunny 2025-03-29 becouse of when override attendance mark as absent grace should be 0
                        $punching_data_sorted[$index]['late_coming_minutes'] = 0;
                        $punching_data_sorted[$index]['early_going_minutes'] = 0;
                        $punching_data_sorted[$index]['late_coming_plus_early_going_minutes'] = 0;

                        $punching_data_sorted[$index]['is_attendance_overridden'] = true;
                        if ($punching_data_sorted[$index]['status'] == 'A' || $punching_data_sorted[$index]['status'] == 'ML') {
                            $punching_data_sorted[$index]['grace'] = 0;
                        }

                        //end code added by sunny 2025-03-29
                    }

                    // if ($punching_data_sorted[$index]['employee_id'] == '252' && $punching_data_sorted[$index]['DateString_2'] == '2026-02-01') {
                    //     echo '<pre>-----#####---punching_data_sorted---######';
                    //     // $d = array_column($punching_data_sorted, 'hn_late_coming_minutes', 'date_time');
                    //     print_r($punching_data_sorted[$index]);
                    //     die();
                    // }
                }
            }
        }
        $data['punching_data'] = $punching_data_sorted;


        return $next($data);
    }
}
