<?php

namespace App\Pipes;

use App\AttendanceProcessor\ProcessorHelper;
use App\Models\AttendanceOverrideModel;
use App\Models\PreFinalPaidDaysModel;
use Closure;

class AdjustLastWorkingDate
{
    public function handle($data, Closure $next)
    {
        $attendance_override_done = $data['punching_data'];

        
        
        $lastWorkingDate = $data['current_user_data']['date_of_leaving'];
        $joiningDate = $data['current_user_data']['joining_date'];
        $employee_id = $data['employee_id'];
        $month = null;
        if( isset($attendance_override_done[0]['DateString_2']) && !empty($attendance_override_done[0]['DateString_2']) ){
            $month = date('Y-m', strtotime($attendance_override_done[0]['DateString_2']));
        }

        // echo '<pre>';
        // print_r([
        //     $lastWorkingDate,
        //     $joiningDate,
        //     $employee_id,
        //     $month,
        //     // $data['current_user_data']
        // ]);
        // die();


        // Remove excess processed punchign data which is post last working date
        // Added by Nazrul on 10 January 2026
        if($month == date('Y-m', strtotime($lastWorkingDate))){
            $this->removeAfterLastWorkingDate($lastWorkingDate, $employee_id);
        }

        if($month == date('Y-m', strtotime($joiningDate))){
            $this->removeBeforeJoiningDate($joiningDate, $employee_id);
        }
        

        if (!empty($attendance_override_done) && !empty($lastWorkingDate)) {
            foreach ($attendance_override_done as $i => $itemRow) {

                // code added by sunny to remove everything after last working day
                // if (!empty($itemRow['is_attendance_overridden'])) {
                //     $attendance_override_done[$i]['grace'] = 0;
                // }

                if ($itemRow['date_time_ordering'] > strtotime($lastWorkingDate)) {
                    unset($attendance_override_done[$i]);
                }
            }
        }
        $data['punching_data'] = $attendance_override_done;

        return $next($data);
    }

    private function removeAfterLastWorkingDate($lastWorkingDate, $employee_id){
        if(!empty($lastWorkingDate) && !empty($employee_id) ){

            try {
                $PreFinalPaidDaysModel = new PreFinalPaidDaysModel();
                $PreFinalPaidDaysModel
                    ->where('employee_id', $employee_id)
                    ->where('date >', $lastWorkingDate)
                    ->delete();
            } catch (\Throwable $th) {
                throw $th;
            }
            
        }
    }

    private function removeBeforeJoiningDate($joiningDate, $employee_id){
        if(!empty($joiningDate) && !empty($employee_id) ){

            try {
                $PreFinalPaidDaysModel = new PreFinalPaidDaysModel();
                $PreFinalPaidDaysModel
                    ->where('employee_id', $employee_id)
                    ->where('date <', $joiningDate)
                    ->delete();
            } catch (\Throwable $th) {
                throw $th;
            }
            
        }
    }

   
}
