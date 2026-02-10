<?php

// if (! function_exists('dd')) {
//     function dd($data)
//     {
//         echo '<pre>';
//         print_r($data);
//         echo '</pre>';
//         die();
//     }
// }

if (! function_exists('first_date_of_year')) {
    function first_date_of_year()
    {
        return date('Y-01-01');
    }
}


if (! function_exists('first_date_of_month')) {
    function first_date_of_month()
    {
        return date('Y-m-01');
    }
}
if (! function_exists('last_date_of_month')) {
    function last_date_of_month()
    {
        return date('Y-m-t');
    }
}
if (! function_exists('current_date_of_month')) {
    function current_date_of_month()
    {
        return date('Y-m-d');
    }
}
if (! function_exists('yesterday_date')) {
    function yesterday_date()
    {
        return date('Y-m-d', strtotime("-1 days"));
    }
}
if (! function_exists('first_day_of_month')) {
    function first_day_of_month()
    {
        return date('l', strtotime(first_date_of_month()));
    }
}
if (! function_exists('last_day_of_month')) {
    function last_day_of_month()
    {
        return date('l', strtotime(last_date_of_month()));
    }
}
if (! function_exists('current_day_of_month')) {
    function current_day_of_month()
    {
        return date('l', strtotime(current_date_of_month()));
    }
}
if (! function_exists('first_date_of_last_month')) {
    function first_date_of_last_month()
    {
        return date('Y-m-d', strtotime('first day of last month'));
    }
}
if (! function_exists('last_date_of_last_month')) {
    function last_date_of_last_month()
    {
        return date('Y-m-d', strtotime('last day of last month'));
    }
}
/*if ( ! function_exists('current_date_of_last_month')){
    function current_date_of_last_month(){
        return date('Y-m-d');
    }
}*/
if (! function_exists('first_day_of_last_month')) {
    function first_day_of_last_month()
    {
        return date('l', strtotime(first_date_of_last_month()));
    }
}
if (! function_exists('last_day_of_last_month')) {
    function last_day_of_last_month()
    {
        return date('l', strtotime(last_date_of_last_month()));
    }
}
if (! function_exists('first_date_2_months_ago')) {
    function first_date_2_months_ago()
    {
        return date('Y-m-d', strtotime('first day of 2 months ago'));
    }
}



if (! function_exists('date_range_between')) {
    function date_range_between($date1, $date2, $format = 'Y-m-d')
    {
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while ($current <= $date2) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        return $dates;
    }
}

if (! function_exists('number_of_days_between')) {
    function number_of_days_between($start_date, $end_date)
    {
        if (!empty($start_date) && !empty($end_date)) {
            $start_datetime = new DateTime($start_date);
            $end_datetime = new DateTime($end_date);
            $interval = $start_datetime->diff($end_datetime);
            $days_between = $interval->days + 1;
            return $days_between;
        }
        return false;
    }
}

if (! function_exists('getDateWithSuffix')) {
    function getDateWithSuffix($date)
    {
        #Convert the date to a timestamp
        $timestamp = strtotime($date);

        #Get the day of the month
        $day = date('d', $timestamp);

        #Add the appropriate suffix for the day
        if ($day == '01' || $day == '21' || $day == '31') {
            $day .= '<sup>st</sup>';
        } elseif ($day == '02' || $day == '22') {
            $day .= '<sup>nd</sup>';
        } elseif ($day == '03' || $day == '23') {
            $day .= '<sup>rd</sup>';
        } else {
            $day .= '<sup>th</sup>';
        }
        #Format the date with the custom day format
        return $day . date(' F Y', $timestamp);
    }
}

if (! function_exists('formatToIndianCurrency')) {
    function formatToIndianCurrency($datavalue)
    {
        $fmt = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
        return $fmt->format($datavalue);
    }
}

if (! function_exists('save_raw_punching_data')) {
    function save_raw_punching_data($employee_id = 'ALL', $from_date = '', $to_date = '', $return = false)
    {
        //set_time_limit(60);
        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime(first_date_of_month()));
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d', strtotime(current_date_of_month()));
        }
        $RawPunchingData = json_decode(get_raw_punching_data($employee_id, $from_date, $to_date), true)['InOutPunchData'];

        if (!empty($RawPunchingData)) {
            foreach ($RawPunchingData as $dataRow) {
                $Empcode = $dataRow['Empcode'];
                $DateString_2 = $dataRow['DateString_2'];
                $RawPunchingDataModel = new \App\Models\RawPunchingDataModel;
                $existing = $RawPunchingDataModel->where('Empcode =', $dataRow['Empcode'])->where('DateString_2 =', $dataRow['DateString_2'])->first();
                if (!empty($existing)) {
                    $dataRow['id'] = $existing['id'];
                }
                $saved = $RawPunchingDataModel->save($dataRow);
                if ($saved && $return === true) {
                    return true;
                }
            }
            // echo '<pre>';
            // print_r($RawPunchingData);
            // echo '</pre>';
            // echo "updated";
            // die();
        } else {

            // $RawPunchingDataModel = new \App\Models\RawPunchingDataModel;
            // $existing = $RawPunchingDataModel->where('Empcode =', $employee_id)->where('DateString_2 =', $from_date)->first();
            // if( empty($existing) ){

            // }
            // session()->setFlashdata('etime_office_erroe', 'There is an error in punching machine kindly contact developer');
            // echo "Something is wrong, Please contact developer";
            // #this could be because Machine override is set for an employee who's Punch id is not in that Machine
            // die();
        }
    }
}

if (! function_exists('get_punching_data')) {
    function get_punching_data($empCode = 'ALL', $from_date = '', $to_date = '')
    {
        $EmployeeModel = new \App\Models\EmployeeModel;
        $employeeData = $EmployeeModel->where('internal_employee_id =', $empCode)->first();

        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime(current_date_of_month()));
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d', strtotime(current_date_of_month()));
        }
        $RawPunchingDataModel = new \App\Models\RawPunchingDataModel;
        if ($empCode !== 'ALL') {
            $RawPunchingDataModel->where('Empcode =', $empCode);
        }
        $RawPunchingDataModel->where('(DateString_2 between "' . $from_date . '" and "' . $to_date . '")');
        $RawPunchingData = $RawPunchingDataModel->findAll();

        if (!empty($RawPunchingData)) {
            return json_encode(['InOutPunchData' => $RawPunchingData]);
        } else {
            return json_encode(['InOutPunchData' => complete_raw_data($employeeData, $from_date, $to_date)]);
        }
    }
}

if (! function_exists('get_punching_data_with_override')) {
    function get_punching_data_with_override($empCode = null, $from_date = '', $to_date = '')
    {
        if (empty($empCode)) {
            return json_encode(['InOutPunchData' => []]);
        }

        $EmployeeModel = new \App\Models\EmployeeModel;
        $employeeData = $EmployeeModel->where('internal_employee_id =', $empCode)->first();
        $employee_id = $employeeData['id'];

        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime(current_date_of_month()));
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d', strtotime(current_date_of_month()));
        }
        $RawPunchingDataModel = new \App\Models\RawPunchingDataModel;
        $RawPunchingDataModel->where('Empcode =', $empCode);
        $RawPunchingDataModel->where('(DateString_2 between "' . $from_date . '" and "' . $to_date . '")');
        // $RawPunchingData = $RawPunchingDataModel->findAll() ?? complete_raw_data($employeeData, $from_date, $to_date);
        $RawPunchingData = $RawPunchingDataModel->findAll() ?? [];
        $RawPunchingData = !empty($RawPunchingData) ? $RawPunchingData : complete_raw_data($employeeData, $from_date, $to_date);

        // if ($from_date == '2025-04-01') {
        //     dd($RawPunchingData);
        // }

        foreach ($RawPunchingData as $key => $val) {
            $date = $val['DateString_2'];
            $ManualPunchModel = new \App\Models\ManualPunchModel();
            $manualPunchData = $ManualPunchModel->where('employee_id =', $employee_id)->where('punch_date =', $date)->first();

            if (!empty($manualPunchData)) {
                $RawPunchingData[$key]['INTime'] = $manualPunchData['punch_in'];
                $RawPunchingData[$key]['OUTTime'] = $manualPunchData['punch_out'];
                $RawPunchingData[$key]['machine'] = $val['machine'] ?? $employeeData['machine'];
            }
        }

        return json_encode(['InOutPunchData' => $RawPunchingData]);
    }
}

if (!function_exists('complete_raw_data')) {
    function complete_raw_data($employeeData, $from_date, $to_date)
    {
        $RawPunchingData = [];
        $dateCursor = strtotime($from_date);
        $dateTo = strtotime($to_date);
        while ($dateCursor <= $dateTo) {
            $dateISO = date('Y-m-d', $dateCursor);

            $row = [];
            $row['Empcode'] = $employeeData['internal_employee_id'];
            $row['INTime'] = '--:--';
            $row['OUTTime'] = '--:--';
            $row['Remark'] = '';
            $row['DateString'] = date('d-m-Y', $dateCursor);
            $row['DateString_2'] = $dateISO;
            $row['machine'] = $employeeData['machine'];
            $row['default_machine'] = $employeeData['machine'];
            $row['override_machine'] = '';

            $RawPunchingData[] = $row;
            $dateCursor = strtotime('+1 day', $dateCursor);
        }
        return $RawPunchingData;
    }
}


if (! function_exists('get_raw_punching_data')) {
    function get_raw_punching_data($employee_id = 'ALL', $from_date = '', $to_date = '')
    {
        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime(current_date_of_month()));
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d', strtotime(current_date_of_month()));
        }
        #######Find overridden Machines#######
        $MachineOverrideModel = new \App\Models\MachineOverrideModel;
        $MachineOverrideModel->select('machine_override.*');
        $MachineOverrideModel->select('machine_override.machine as override_machine');
        $MachineOverrideModel->select('employees.internal_employee_id as internal_employee_id');
        $MachineOverrideModel->select('employees.machine as default_machine');
        $MachineOverrideModel->join('employees', 'employees.id=machine_override.employee_id', 'left');
        $MachineOverrideModel->groupStart();
        $MachineOverrideModel->where("machine_override.from_date between '" . $from_date . "' and '" . $to_date . "'");
        $MachineOverrideModel->orWhere("machine_override.to_date between '" . $from_date . "' and '" . $to_date . "'");
        $MachineOverrideModel->orWhere("'" . $from_date . "' between machine_override.from_date and machine_override.to_date");
        $MachineOverrideModel->orWhere("'" . $to_date . "' between machine_override.from_date and machine_override.to_date");
        $MachineOverrideModel->groupEnd();
        $ExistingMachineOverrideEntries = $MachineOverrideModel->findAll();

        #######Find default Machines#######
        $EmployeeModel = new \App\Models\EmployeeModel;
        $EmployeeModel->select('employees.internal_employee_id as internal_employee_id');
        $EmployeeModel->select('employees.machine as default_machine');
        $EmployeeModel->select('employees.id as emp_id');
        if ($employee_id !== 'ALL') {
            $EmployeeModel->where('employees.internal_employee_id =', $employee_id);
        }
        $DefaultMachineEntries_raw = $EmployeeModel->findAll();

        $DefaultMachineEntries = array();
        foreach ($DefaultMachineEntries_raw as $key => $machinerow) {
            $i = $machinerow['internal_employee_id'];
            $m = $machinerow['default_machine'];
            $DefaultMachineEntries[$i] = $m;
        }

        $EmployeeModel = new \App\Models\EmployeeModel;
        $EmployeeModel->select('employees.internal_employee_id as internal_employee_id');
        $EmployeeModel->select('employees.joining_date as joining_date');
        $EmployeeModel->select('employees.date_of_leaving as date_of_leaving');
        if ($employee_id !== 'ALL') {
            $EmployeeModel->where('employees.internal_employee_id =', $employee_id);
        }
        $JoiningDates_raw = $EmployeeModel->findAll();
        $JoiningDates = array();
        $DateOfLeavings = array();
        foreach ($JoiningDates_raw as $key => $joiningdaterow) {
            $JoiningDates[$joiningdaterow['internal_employee_id']] = $joiningdaterow['joining_date'];
            $DateOfLeavings[$joiningdaterow['internal_employee_id']] = $joiningdaterow['date_of_leaving'];
        }

        $date_range_between_from_date_and_to_date = date_range_between($from_date, $to_date);


        $PunchingDataDel = json_decode(get_punching_data_del($employee_id, $from_date, $to_date), true)['InOutPunchData'];
        // echo '<pre>';
        // print_r( $PunchingDataDel );
        // echo '</pre>';
        // die();
        $PunchingDataGGN = json_decode(get_punching_data_ggn($employee_id, $from_date, $to_date), true)['InOutPunchData'];
        $PunchingDataNOIDA = json_decode(get_punching_data_noida($employee_id, $from_date, $to_date), true)['InOutPunchData'];
        $PunchingDataSKBD = json_decode(get_punching_data_skbd($employee_id, $from_date, $to_date), true)['InOutPunchData'];



        #Removed on 2024-08-02
        // $InOutPunchData__Del__GGN = ( !empty($PunchingDataDel) && !empty($PunchingDataGGN) ) ? array_merge($PunchingDataDel, $PunchingDataGGN) : null;
        // $InOutPunchData__Del__GGN_Noida = ( !empty($InOutPunchData__Del__GGN) && !empty($PunchingDataNOIDA) ) ? array_merge($InOutPunchData__Del__GGN, $PunchingDataNOIDA) : null;

        #added on 2024-08-02
        $InOutPunchData__Del__GGN_Noida = [];
        #merge del
        $InOutPunchData__Del__GGN_Noida = !empty($PunchingDataDel) ? array_merge($InOutPunchData__Del__GGN_Noida, $PunchingDataDel) : $InOutPunchData__Del__GGN_Noida;
        #merge ggn
        $InOutPunchData__Del__GGN_Noida = !empty($PunchingDataGGN) ? array_merge($InOutPunchData__Del__GGN_Noida, $PunchingDataGGN) : $InOutPunchData__Del__GGN_Noida;
        #merge hn
        $InOutPunchData__Del__GGN_Noida = !empty($PunchingDataNOIDA) ? array_merge($InOutPunchData__Del__GGN_Noida, $PunchingDataNOIDA) : $InOutPunchData__Del__GGN_Noida;
        #merge skbd
        $InOutPunchData__Del__GGN_Noida = !empty($PunchingDataSKBD) ? array_merge($InOutPunchData__Del__GGN_Noida, $PunchingDataSKBD) : $InOutPunchData__Del__GGN_Noida;

        if (!empty($InOutPunchData__Del__GGN_Noida)) {

            foreach ($InOutPunchData__Del__GGN_Noida as $x => $y) {
                if (!in_array($y['Empcode'], array_keys($DefaultMachineEntries))) {
                    unset($InOutPunchData__Del__GGN_Noida[$x]);
                }
            }

            foreach ($InOutPunchData__Del__GGN_Noida as $i => $d) {
                $InOutPunchData__Del__GGN_Noida[$i]['DateString'] = str_replace('/', '-', $d['DateString']);
                $InOutPunchData__Del__GGN_Noida[$i]['DateString_2'] = date('Y-m-d', strtotime($d['DateString']));
                //$InOutPunchData__Del__GGN_Noida[$i]['default_machine'] = $DefaultMachineEntries[$d['Empcode']];
                $InOutPunchData__Del__GGN_Noida[$i]['default_machine'] = $DefaultMachineEntries[$d['Empcode']] ?? '';

                $InOutPunchData__Del__GGN_Noida[$i]['override_machine'] = '';
                if (strtotime($InOutPunchData__Del__GGN_Noida[$i]['DateString_2']) < strtotime($JoiningDates[$d['Empcode']] ?? '')) {
                    // unset($InOutPunchData__Del__GGN_Noida[$i]);
                    $InOutPunchData__Del__GGN_Noida[$i]['INTime'] = '--:--';
                    $InOutPunchData__Del__GGN_Noida[$i]['OUTTime'] = '--:--';
                } elseif (!empty($DateOfLeavings[$d['Empcode']]) && strtotime($InOutPunchData__Del__GGN_Noida[$i]['DateString_2']) > strtotime($DateOfLeavings[$d['Empcode']])) {
                    // unset($InOutPunchData__Del__GGN_Noida[$i]);
                    $InOutPunchData__Del__GGN_Noida[$i]['INTime'] = '--:--';
                    $InOutPunchData__Del__GGN_Noida[$i]['OUTTime'] = '--:--';
                }
            }

            #######Remove default machines for overridden date range#######
            foreach ($InOutPunchData__Del__GGN_Noida as $punchingDataIndex => $punchingDataRow) {
                foreach ($ExistingMachineOverrideEntries as $ExistingMachineOverrideIndex => $ExistingMachineOverrideEntry) {
                    if (strtotime($punchingDataRow['DateString_2']) >= strtotime($ExistingMachineOverrideEntry['from_date']) && strtotime($punchingDataRow['DateString_2']) <= strtotime($ExistingMachineOverrideEntry['to_date'])) {
                        if ($punchingDataRow['Empcode'] == $ExistingMachineOverrideEntry['internal_employee_id']) {
                            $InOutPunchData__Del__GGN_Noida[$punchingDataIndex]['override_machine'] = $ExistingMachineOverrideEntry['override_machine'];
                        }
                    }
                }
            }

            // echo '<pre>';
            // print_r($InOutPunchData__Del__GGN_Noida);
            // echo '</pre>';
            // die();

            foreach ($InOutPunchData__Del__GGN_Noida as $punchingDataIndex => $punchingDataRow) {

                if (isset($punchingDataRow['override_machine']) && !empty($punchingDataRow['override_machine'])) {
                    if ($punchingDataRow['override_machine'] != $punchingDataRow['machine']) {
                        unset($InOutPunchData__Del__GGN_Noida[$punchingDataIndex]);
                    }
                } else {
                    if ($punchingDataRow['default_machine'] != $punchingDataRow['machine']) {
                        unset($InOutPunchData__Del__GGN_Noida[$punchingDataIndex]);
                    }
                }
            }

            // echo '<pre>';
            // print_r($InOutPunchData__Del__GGN_Noida);
            // echo '</pre>';
            // die();

        } else {
            // session()->setFlashdata('etime_office_error', 'eTimeOffice Error:: Fresh data was not received from eTimeOffice Server.<br>This is a temporary error, please wait for a while or, can contact developer');
        }




        $data = array();
        $data['InOutPunchData'] = !empty($InOutPunchData__Del__GGN_Noida) ? $InOutPunchData__Del__GGN_Noida : [];
        return json_encode($data);
    }
}

if (! function_exists('get_punching_data_del')) {
    function get_punching_data_del($employee_id = 'ALL', $from_date = '', $to_date = '')
    {
        if (empty($from_date)) {
            $from_date = date('d/m/Y', strtotime(current_date_of_month()));
        } else {
            $from_date = date('d/m/Y', strtotime($from_date));
        }
        if (empty($to_date)) {
            $to_date = date('d/m/Y', strtotime(current_date_of_month()));
        } else {
            $to_date = date('d/m/Y', strtotime($to_date));
        }


        $postData = "Empcode=" . $employee_id . "&FromDate=" . $from_date . "&ToDate=" . $to_date;

        $url = env('del.API_URL');
        $corporate_id = env('del.CORPORATE_ID');
        $username = env('del.USERNAME');
        $password = env('del.PASSWORD');

        $auth = base64_encode($corporate_id . ":" . $username . ":" . $password . ":true");
        $headers = array("Authorization: Basic " . $auth,);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url . '?' . $postData);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        $resp = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($resp, true);
        $InOutPunchData = $data['InOutPunchData'] ?? null;

        if (!empty($InOutPunchData)) {
            foreach ($InOutPunchData as $i => $d) {
                $InOutPunchData[$i]['DateString'] = str_replace('/', '-', $d['DateString']);
                $InOutPunchData[$i]['machine'] = 'del';
            }
            $data['InOutPunchData'] = $InOutPunchData;
        } else {
            $data['InOutPunchData'] = $InOutPunchData;
        }
        return json_encode($data);
    }
}

if (! function_exists('get_punching_data_ggn')) {
    function get_punching_data_ggn($employee_id = 'ALL', $from_date = '', $to_date = '')
    {
        if (empty($from_date)) {
            $from_date = date('d/m/Y', strtotime(current_date_of_month()));
        } else {
            $from_date = date('d/m/Y', strtotime($from_date));
        }
        if (empty($to_date)) {
            $to_date = date('d/m/Y', strtotime(current_date_of_month()));
        } else {
            $to_date = date('d/m/Y', strtotime($to_date));
        }

        $postData = "Empcode=" . $employee_id . "&FromDate=" . $from_date . "&ToDate=" . $to_date;

        $url = env('ggn.API_URL');
        $corporate_id = env('ggn.CORPORATE_ID');
        $username = env('ggn.USERNAME');
        $password = env('ggn.PASSWORD');

        $auth = base64_encode($corporate_id . ":" . $username . ":" . $password . ":true");
        $headers = array("Authorization: Basic " . $auth,);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url . '?' . $postData);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        $resp = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($resp, true);
        $InOutPunchData = $data['InOutPunchData'] ?? null;
        if (!empty($InOutPunchData)) {
            foreach ($InOutPunchData as $i => $d) {
                $InOutPunchData[$i]['DateString'] = str_replace('/', '-', $d['DateString']);
                $InOutPunchData[$i]['machine'] = 'ggn';
            }
            $data['InOutPunchData'] = $InOutPunchData;
        } else {
            $data['InOutPunchData'] = $InOutPunchData;
        }

        /*foreach( $data as $i => $d ){
            if( $d['Empcode'] == '588' && $d['DateString'] == '04-11-2022'){
                $data[$i]['INTime'] = '--:--';
                $data[$i]['OUTTime'] = '--:--';
            }
        }*/
        return json_encode($data);
    }
}

if (! function_exists('get_punching_data_noida')) {
    function get_punching_data_noida($employee_id = 'ALL', $from_date = '', $to_date = '')
    {
        if (empty($from_date)) {
            $from_date = date('d/m/Y', strtotime(current_date_of_month()));
        } else {
            $from_date = date('d/m/Y', strtotime($from_date));
        }
        if (empty($to_date)) {
            $to_date = date('d/m/Y', strtotime(current_date_of_month()));
        } else {
            $to_date = date('d/m/Y', strtotime($to_date));
        }

        $postData = "Empcode=" . $employee_id . "&FromDate=" . $from_date . "&ToDate=" . $to_date;

        $url = env('hn.API_URL');
        $corporate_id = env('hn.CORPORATE_ID');
        $username = env('hn.USERNAME');
        $password = env('hn.PASSWORD');

        $auth = base64_encode($corporate_id . ":" . $username . ":" . $password . ":true");
        $headers = array("Authorization: Basic " . $auth,);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url . '?' . $postData);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        $resp = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($resp, true);
        $InOutPunchData = $data['InOutPunchData'] ?? null;
        if (!empty($InOutPunchData)) {
            foreach ($InOutPunchData as $i => $d) {
                $InOutPunchData[$i]['DateString'] = str_replace('/', '-', $d['DateString']);
                $InOutPunchData[$i]['machine'] = 'hn';
            }
            $data['InOutPunchData'] = $InOutPunchData;
        } else {
            $data['InOutPunchData'] = $InOutPunchData;
        }

        /*foreach( $data as $i => $d ){
            if( $d['Empcode'] == '588' && $d['DateString'] == '04-11-2022'){
                $data[$i]['INTime'] = '--:--';
                $data[$i]['OUTTime'] = '--:--';
            }
        }*/
        return json_encode($data);
    }
}

if (! function_exists('get_punching_data_skbd')) {
    function get_punching_data_skbd($employee_id = 'ALL', $from_date = '', $to_date = '')
    {
        if (empty($from_date)) {
            $from_date = date('d/m/Y', strtotime(current_date_of_month()));
        } else {
            $from_date = date('d/m/Y', strtotime($from_date));
        }
        if (empty($to_date)) {
            $to_date = date('d/m/Y', strtotime(current_date_of_month()));
        } else {
            $to_date = date('d/m/Y', strtotime($to_date));
        }

        $postData = "Empcode=" . $employee_id . "&FromDate=" . $from_date . "&ToDate=" . $to_date;

        $url = env('skbd.API_URL');
        $corporate_id = env('skbd.CORPORATE_ID');
        $username = env('skbd.USERNAME');
        $password = env('skbd.PASSWORD');

        $auth = base64_encode($corporate_id . ":" . $username . ":" . $password . ":true");
        $headers = array("Authorization: Basic " . $auth,);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url . '?' . $postData);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        $resp = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($resp, true);
        $InOutPunchData = $data['InOutPunchData'] ?? null;
        if (!empty($InOutPunchData)) {
            foreach ($InOutPunchData as $i => $d) {
                $InOutPunchData[$i]['DateString'] = str_replace('/', '-', $d['DateString']);
                $InOutPunchData[$i]['machine'] = 'skbd';
            }
            $data['InOutPunchData'] = $InOutPunchData;
        } else {
            $data['InOutPunchData'] = $InOutPunchData;
        }

        /*foreach( $data as $i => $d ){
            if( $d['Empcode'] == '588' && $d['DateString'] == '04-11-2022'){
                $data[$i]['INTime'] = '--:--';
                $data[$i]['OUTTime'] = '--:--';
            }
        }*/
        return json_encode($data);
    }
}

#Sort array by value using key
if (! function_exists('array_sort_by_key')) {
    function array_sort_by_key($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            $excluded = array();
            foreach ($sortable_array as $k => $v) {
                if ($v > 0) {
                    $new_array[$k] = $array[$k];
                } else {
                    $excluded[$k] = $array[$k];
                }
            }
            $new_array = array_merge($new_array, $excluded);
        }

        return $new_array;
    }
}

if (! function_exists('array_sort_by_key2')) {
    function array_sort_by_key2($array, $on)
    {
        usort($array, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });
    }
}

function orderResultSet($result_set, $column, $reverse = FALSE)
{
    $order = $reverse ? -1 : 1;
    usort($result_set, function ($a, $b) use ($column, $order) {
        return $order * ($a[$column] <=> $b[$column]);
    });
    return $result_set;
}

if (! function_exists('getBalanceGrace')) {
    function getBalanceGrace()
    {
        $employee_id = session()->get('current_user')['employee_id'];
        $GraceBalanceModel = new \App\Models\GraceBalanceModel();
        $balance_grace_row = $GraceBalanceModel->where('employee_id', $employee_id)->where('year_month', date('Y-m'))->first();
        $balance_grace = !empty($balance_grace_row) ? $balance_grace_row['minutes'] : 0;
        return $balance_grace;
    }
}

if (! function_exists('getLateMinutes')) {
    function getLateMinutes($from, $to)
    {
        $employee_id = session()->get('current_user')['employee_id'];
        $PreFinalPaidDaysModel = new \App\Models\PreFinalPaidDaysModel;
        $get_punching_data = $PreFinalPaidDaysModel
            ->select('pre_final_paid_days.late_coming_minutes')
            ->select('pre_final_paid_days.in_time_between_shift_with_od ')
            ->where('pre_final_paid_days.employee_id =', $employee_id)
            ->where("(pre_final_paid_days.date between '" . $from . "' and '" . $to . "')")
            ->orderBy('date', 'desc')
            ->findAll();

        // print_r($get_punching_data);
        // die;


        $late_comings = array_column($get_punching_data, 'late_coming_minutes');
        $in_time_between_shift_with_od_array = array_column($get_punching_data, 'in_time_between_shift_with_od');
        $in_time_between_shift_with_od_array =  array_filter($in_time_between_shift_with_od_array);
        $total_late_minutes = array_sum($late_comings);
        $total_present_days = count($in_time_between_shift_with_od_array);
        $late_minutes_avg = ($total_present_days > 0) ? round($total_late_minutes / $total_present_days) : '0';
        return array('total' => $total_late_minutes, 'average' => $late_minutes_avg);
    }
}

// if (! function_exists('getLateMinutes')) {
//     function getLateMinutes($employee_data, $from, $to)
//     {

//         // print_r($employee_data);
//         // die;
//         // $get_punching_data = json_decode(get_punching_data($employee_data['internal_employee_id'], $from, $to), true)['InOutPunchData'];

//         $PreFinalPaidDaysModel = new \App\Models\PreFinalPaidDaysModel;
//         $get_punching_data = $PreFinalPaidDaysModel
//             ->select('pre_final_paid_days.*')
//             ->select('trim(concat(settler.first_name, " ", settler.last_name)) as settled_by_name')
//             ->join('employees as settler', 'settler.id = pre_final_paid_days.settled_by', 'left')
//             ->where('pre_final_paid_days.employee_id =', $employee_data['id'])
//             ->where("(pre_final_paid_days.date between '" . $from . "' and '" . $to . "')")
//             ->orderBy('pre_final_paid_days.date', 'ASC')
//             ->findAll();

//         // echo $PreFinalPaidDaysModel->getLastQuery()->getQuery();
//         // echo "\n\nCount: " . count($get_punching_data) . "\n\n";
//         // if (!empty($get_punching_data)) {
//         //     echo "First row:\n";
//         //     print_r($get_punching_data[0]);
//         // }
//         // echo "\n\nEmployee data:\n";
//         // print_r(['id' => $employee_data['id'], 'internal_id' => $employee_data['internal_employee_id'] ?? 'N/A']);
//         // die;

//         foreach ($get_punching_data as $punching_data_index => $punching_data_row) {
//             $day = date('l', strtotime($punching_data_row['date']));
//             $date_time = $punching_data_row['date'];
//             $get_punching_data[$punching_data_index]['date_time'] = $date_time;
//             $get_punching_data[$punching_data_index]['day'] = $day;
//         }
//         $total_late_minutes = 0;
//         $total_present_days = 0;
//         foreach ($get_punching_data as $punching_row) {
//             if ($punching_row['employee_id'] == $employee_data['id'] && $punching_row['punch_in_time'] !== '--:--' && !empty($punching_row['punch_in_time'])) {
//                 #make shift time array of current date
//                 $shift = array(
//                     !empty($punching_row['shift_start']) ? $punching_row['shift_start'] : '',
//                     !empty($punching_row['shift_end']) ? $punching_row['shift_end'] : ''
//                 );

//                 #make shift time array of current date
//                 $late_minutes_of_the_day = 0;
//                 $INTime = !empty($punching_row['punch_in_time']) ? $punching_row['punch_in_time'] : null;
//                 if (!empty($shift[0]) && !empty($INTime)) {
//                     if (strtotime($INTime) > strtotime($shift[0])) {
//                         $shift_start = date_create($shift[0]);
//                         $in_time = date_create($INTime);
//                         $timediff = $shift_start->diff($in_time);
//                         $late_minutes   = (int)$timediff->format('%r%i');
//                         $late_hours     = (int)$timediff->format('%r%h');
//                         if ($late_minutes > 0 || $late_hours > 0) {
//                             $late_minutes_of_the_day = $late_minutes + ($late_hours * 60);
//                         }
//                     }
//                 }

//                 $early_going_minutes_of_the_day = 0;
//                 if (!empty($punching_row['punch_out_time']) && $punching_row['punch_out_time'] != '--:--') {
//                     $OUTTime = $punching_row['punch_out_time'];
//                     if (!empty($shift[1])) {
//                         if (strtotime($OUTTime) < strtotime($shift[1])) {
//                             $shift_end = date_create($shift[1]);
//                             $out_time = date_create($OUTTime);
//                             $timediff = $out_time->diff($shift_end);
//                             $early_going_minutes   = (int)$timediff->format('%r%i');
//                             $early_going_hours     = (int)$timediff->format('%r%h');
//                             if ($early_going_minutes > 0 || $early_going_hours > 0) {
//                                 $minutes_per_day[$punching_row['date_time']]['early'] = $early_going_minutes + ($early_going_hours * 60);
//                                 $early_going_minutes_of_the_day = $early_going_minutes + ($early_going_hours * 60);
//                             }
//                         }
//                     }
//                 }


//                 // Check leave data from pre_final_paid_days
//                 $leave_amount = !empty($punching_row['leave_request_amount']) ? $punching_row['leave_request_amount'] : 0;
//                 $leave_type = !empty($punching_row['leave_request_type']) ? $punching_row['leave_request_type'] : '';

//                 if (!empty($leave_type)) {
//                     if ($leave_amount == '0.5') {
//                         if ($late_minutes_of_the_day > $early_going_minutes_of_the_day) {
//                             $late_minutes_of_the_day = '0';
//                         }
//                     } else {
//                         $late_minutes_of_the_day = $late_minutes_of_the_day;
//                     }
//                 } elseif ($punching_row['date_time'] == '2023-03-07') {
//                     #$early_going_minutes_of_the_day = '0';          #company half day is in second half only
//                     $late_minutes_of_the_day = $late_minutes_of_the_day;
//                 } else {
//                     $late_minutes_of_the_day = $late_minutes_of_the_day;
//                 }

//                 $total_late_minutes += $late_minutes_of_the_day;
//                 $total_present_days++;
//             }
//         }
//         $late_minutes_avg = ($total_present_days > 0) ? round($total_late_minutes / $total_present_days) : '-';
//         // print_r(array('total' => $total_late_minutes, 'average' => $late_minutes_avg));
//         // die;
//         return array('total' => $total_late_minutes, 'average' => $late_minutes_avg);
//     }
// }

// if (! function_exists('getLateMinutes')) {
//     function getLateMinutes($employee_data, $from, $to)
//     {
//         $get_punching_data = json_decode(get_punching_data($employee_data['internal_employee_id'], $from, $to), true)['InOutPunchData'];
//         foreach ($get_punching_data as $punching_data_index => $punching_data_row) {
//             $day = date('l', strtotime($punching_data_row['DateString']));
//             $date_time = date('Y-m-d', strtotime($punching_data_row['DateString']));
//             $get_punching_data[$punching_data_index]['date_time'] = $date_time;
//             $get_punching_data[$punching_data_index]['day'] = $day;
//         }
//         $total_late_minutes = 0;
//         $total_present_days = 0;
//         foreach ($get_punching_data as $punching_row) {
//             if ($punching_row['Empcode'] == $employee_data['internal_employee_id'] && $punching_row['INTime'] !== '--:--') {
//                 #make shift time array of current date
//                 $shift_override = App\Pipes\AttendanceProcessor\ProcessorHelper::get_shift_override($employee_data['id'], $punching_row['date_time']);
//                 if (!empty($shift_override)) {
//                     $shift = array_values($shift_override);
//                 } else {
//                     if (isset($employee_data[$punching_row['day']]) && !empty($employee_data[$punching_row['day']])) {
//                         $shift = explode(',', $employee_data[$punching_row['day']]);
//                     } else {
//                         $shift = array('', '');
//                     }
//                 }

//                 #make shift time array of current date
//                 $late_minutes_of_the_day = 0;
//                 $INTime = date('H:i:s', strtotime($punching_row['INTime']));
//                 if (!empty($shift[0])) {
//                     if (strtotime($INTime) > strtotime($shift[0])) {
//                         $shift_start = date_create($shift[0]);
//                         $in_time = date_create($punching_row['INTime']);
//                         $timediff = $shift_start->diff($in_time);
//                         $late_minutes   = (int)$timediff->format('%r%i');
//                         $late_hours     = (int)$timediff->format('%r%h');
//                         if ($late_minutes > 0 || $late_hours > 0) {
//                             $late_minutes_of_the_day = $late_minutes + ($late_hours * 60);
//                         }
//                     }
//                 }

//                 $early_going_minutes_of_the_day = 0;
//                 if ($punching_row['OUTTime'] != '--:--') {
//                     $OUTTime = date('H:i:s', strtotime($punching_row['OUTTime']));
//                     if (!empty($shift[1])) {
//                         if (strtotime($OUTTime) < strtotime($shift[1])) {
//                             $shift_end = date_create($shift[1]);
//                             $out_time = date_create($punching_row['OUTTime']);
//                             $timediff = $out_time->diff($shift_end);
//                             $early_going_minutes   = (int)$timediff->format('%r%i');
//                             $early_going_hours     = (int)$timediff->format('%r%h');
//                             if ($early_going_minutes > 0 || $early_going_hours > 0) {
//                                 $minutes_per_day[$punching_row['date_time']]['early'] = $early_going_minutes + ($early_going_hours * 60);
//                                 $early_going_minutes_of_the_day = $early_going_minutes + ($early_going_hours * 60);
//                             }
//                         }
//                     }
//                 }


//                 $leave_data = App\Pipes\AttendanceProcessor\ProcessorHelper::is_onLeave($punching_row['date_time'], $employee_data['id'], $punching_row['INTime']);
//                 if (!empty($leave_data)) {
//                     if ($leave_data['number_of_days'] == '0.5') {
//                         if ($late_minutes_of_the_day > $early_going_minutes_of_the_day) {
//                             $late_minutes_of_the_day = '0';
//                         }
//                     } else {
//                         $late_minutes_of_the_day = $late_minutes_of_the_day;
//                     }
//                 } elseif ($punching_row['date_time'] == '2023-03-07') {
//                     #$early_going_minutes_of_the_day = '0';          #company half day is in second half only
//                     $late_minutes_of_the_day = $late_minutes_of_the_day;
//                 } else {
//                     $late_minutes_of_the_day = $late_minutes_of_the_day;
//                 }

//                 $total_late_minutes += $late_minutes_of_the_day;
//                 $total_present_days++;
//             }
//         }
//         $late_minutes_avg = ($total_present_days > 0) ? round($total_late_minutes / $total_present_days) : '-';
//         return array('total' => $total_late_minutes, 'average' => $late_minutes_avg);
//     }
// }

/*if ( ! function_exists('getAvgLateMinutes')){
    function getAvgLateMinutes($employee_data, $from, $to){
        $get_punching_data = json_decode( get_punching_data( $employee_data['internal_employee_id'], $from, $to ), true )['InOutPunchData'];
        foreach( $get_punching_data as $punching_data_index => $punching_data_row ){
            $day = date('l', strtotime($punching_data_row['DateString']));
            $date_time = date('Y-m-d', strtotime($punching_data_row['DateString']));
            $get_punching_data[$punching_data_index]['date_time'] = $date_time;
            $get_punching_data[$punching_data_index]['day'] = $day;
        }
        $total_late_minutes = 0;
        $total_present_days = 0;
        foreach( $get_punching_data as $punching_row ){
            if( $punching_row['Empcode'] == $employee_data['internal_employee_id'] && $punching_row['INTime'] !== '--:--'){
                #make shift time array of current date
                if( isset($employee_data[$punching_row['day']]) && !empty($employee_data[$punching_row['day']]) ){
                    $shift = explode(',', $employee_data[$punching_row['day']]);
                }else{
                    $shift = array('', '');
                }

                #make shift time array of current date
                $shift_start = date_create($shift[0]);

                $in_time = date_create($punching_row['INTime']);
                $timediff = $shift_start->diff($in_time);
                $late_minutes   = (int)$timediff->format('%r%i');
                $late_hours     = (int)$timediff->format('%r%h');
                if( $late_minutes > 0 || $late_hours > 0 ){
                    $total_late_minutes += $late_minutes+($late_hours*60);
                }

                $total_present_days++;
            }
        }
        $late_minutes_avg = ( $total_present_days > 0 ) ? round($total_late_minutes / $total_present_days) : '-';
        return $late_minutes_avg;
    }
}*/

if (! function_exists('getEarlyGoingMinutes')) {
    function getEarlyGoingMinutes($from, $to)
    {
        $employee_id = session()->get('current_user')['employee_id'];
        $PreFinalPaidDaysModel = new \App\Models\PreFinalPaidDaysModel;
        $get_punching_data = $PreFinalPaidDaysModel
            ->select('pre_final_paid_days.early_going_minutes')
            ->select('pre_final_paid_days.in_time_between_shift_with_od')
            ->where('pre_final_paid_days.employee_id =', $employee_id)
            ->where("(pre_final_paid_days.date between '" . $from . "' and '" . $to . "')")
            ->findAll();

        $early_going = array_column($get_punching_data, 'early_going_minutes');
        $in_time_between_shift_with_od_array = array_column($get_punching_data, 'in_time_between_shift_with_od');
        $in_time_between_shift_with_od_array =  array_filter($in_time_between_shift_with_od_array);
        $total_early_going_minutes = array_sum($early_going);
        $total_present_days = count($in_time_between_shift_with_od_array);
        $early_going_minutes_avg = ($total_present_days > 0) ? round($total_early_going_minutes / $total_present_days) : '0';
        return array('total' => $total_early_going_minutes, 'average' => $early_going_minutes_avg);
    }
}

// if (! function_exists('getEarlyGoingMinutes')) {
//     function getEarlyGoingMinutes($employee_data, $from, $to)
//     {
//         $get_punching_data = json_decode(get_punching_data($employee_data['internal_employee_id'], $from, $to), true)['InOutPunchData'];
//         foreach ($get_punching_data as $punching_data_index => $punching_data_row) {
//             $day = date('l', strtotime($punching_data_row['DateString']));
//             $date_time = date('Y-m-d', strtotime($punching_data_row['DateString']));
//             $get_punching_data[$punching_data_index]['date_time'] = $date_time;
//             $get_punching_data[$punching_data_index]['day'] = $day;
//         }
//         $total_late_minutes = 0;
//         $total_early_going_minutes = 0;
//         $total_present_days = 0;
//         $minutes_per_day = array();

//         foreach ($get_punching_data as $punching_row) {
//             if ($punching_row['Empcode'] == $employee_data['internal_employee_id'] && $punching_row['OUTTime'] !== '--:--') {
//                 #make shift time array of current date
//                 /*if( $employee_data['internal_employee_id'] == 588 ){*/
//                 $shift_override = App\Pipes\AttendanceProcessor\ProcessorHelper::get_shift_override($employee_data['id'], $punching_row['date_time']);
//                 if (!empty($shift_override)) {
//                     $shift = array_values($shift_override);
//                 } else {
//                     if (isset($employee_data[$punching_row['day']]) && !empty($employee_data[$punching_row['day']])) {
//                         $shift = explode(',', $employee_data[$punching_row['day']]);
//                     } else {
//                         $shift = array('', '');
//                     }
//                 }
//                 /*}else{
//                     if( isset($employee_data[$punching_row['day']]) && !empty($employee_data[$punching_row['day']]) ){
//                         $shift = explode(',', $employee_data[$punching_row['day']]);
//                     }else{
//                         $shift = array('', '');
//                     }
//                 }*/

//                 #make shift time array of current date
//                 $early_going_minutes_of_the_day = 0;
//                 $OUTTime = date('H:i:s', strtotime($punching_row['OUTTime']));
//                 if (!empty($shift[1])) {
//                     if (strtotime($OUTTime) < strtotime($shift[1])) {
//                         $shift_end = date_create($shift[1]);
//                         $out_time = date_create($punching_row['OUTTime']);
//                         $timediff = $out_time->diff($shift_end);
//                         $early_going_minutes   = (int)$timediff->format('%r%i');
//                         $early_going_hours     = (int)$timediff->format('%r%h');
//                         if ($early_going_minutes > 0 || $early_going_hours > 0) {
//                             $minutes_per_day[$punching_row['date_time']]['early'] = $early_going_minutes + ($early_going_hours * 60);
//                             $early_going_minutes_of_the_day = $early_going_minutes + ($early_going_hours * 60);
//                         }
//                     }
//                 }

//                 $late_minutes_of_the_day = 0;
//                 $INTime = date('H:i:s', strtotime($punching_row['INTime']));
//                 if (!empty($shift[0])) {
//                     if (strtotime($INTime) > strtotime($shift[0])) {
//                         $shift_start = date_create($shift[0]);
//                         $in_time = date_create($punching_row['INTime']);
//                         $timediff = $shift_start->diff($in_time);
//                         $late_minutes   = (int)$timediff->format('%r%i');
//                         $late_hours     = (int)$timediff->format('%r%h');
//                         if ($late_minutes > 0 || $late_hours > 0) {
//                             $late_minutes_of_the_day = $late_minutes + ($late_hours * 60);
//                         }
//                     }
//                 }

//                 $leave_data = App\Pipes\AttendanceProcessor\ProcessorHelper::is_onLeave($punching_row['date_time'], $employee_data['id'], $punching_row['INTime']);
//                 if (!empty($leave_data)) {
//                     if ($leave_data['number_of_days'] == '0.5') {
//                         if ($early_going_minutes_of_the_day >= $late_minutes_of_the_day) {
//                             $early_going_minutes_of_the_day = '0';
//                         }
//                     } else {
//                         $early_going_minutes_of_the_day = $early_going_minutes_of_the_day;
//                     }
//                 } elseif ($punching_row['date_time'] == '2023-03-07') {
//                     $early_going_minutes_of_the_day = '0';
//                 } else {
//                     $early_going_minutes_of_the_day = $early_going_minutes_of_the_day;
//                 }

//                 $total_early_going_minutes += $early_going_minutes_of_the_day;
//                 $total_present_days++;
//             }
//         }
//         $early_going_minutes_avg = ($total_present_days > 0) ? round($total_early_going_minutes / $total_present_days) : '-';
//         return array('total' => $total_early_going_minutes, 'average' => $early_going_minutes_avg);
//         #return array('minutes_per_day' => $minutes_per_day);
//     }
// }

/*if ( ! function_exists('getAvgEarlyGoingMinutes')){
    function getAvgEarlyGoingMinutes($employee_data, $from, $to){
        $get_punching_data = json_decode( get_punching_data( $employee_data['internal_employee_id'], $from, $to ), true )['InOutPunchData'];
        foreach( $get_punching_data as $punching_data_index => $punching_data_row ){
            $day = date('l', strtotime($punching_data_row['DateString']));
            $date_time = date('Y-m-d', strtotime($punching_data_row['DateString']));
            $get_punching_data[$punching_data_index]['date_time'] = $date_time;
            $get_punching_data[$punching_data_index]['day'] = $day;
        }
        $total_late_minutes = 0;
        $total_present_days = 0;
        foreach( $get_punching_data as $punching_row ){
            if( $punching_row['Empcode'] == $employee_data['internal_employee_id'] && $punching_row['INTime'] !== '--:--'){
                #make shift time array of current date
                if( isset($employee_data[$punching_row['day']]) && !empty($employee_data[$punching_row['day']]) ){
                    $shift = explode(',', $employee_data[$punching_row['day']]);
                }else{
                    $shift = array('', '');
                }
                #make shift time array of current date
                $shift_start = date_create($shift[0]);


                $in_time = date_create($punching_row['INTime']);
                $timediff = $shift_start->diff($in_time);
                $late_minutes   = (int)$timediff->format('%r%i');
                $late_hours     = (int)$timediff->format('%r%h');
                if( $late_minutes > 0 || $late_hours > 0 ){
                    $total_late_minutes += $late_minutes+($late_hours*60);
                }
                $total_present_days++;
            }
        }
        $late_minutes_avg = ( $total_present_days > 0 ) ? round($total_late_minutes / $total_present_days) : '-';
        return $late_minutes_avg;
    }
}*/


/* if (! function_exists('AmountInWords')) {
    function AmountInWords(float $amount)
    {
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
        #Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety'
        );
        $here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($x < $count_length) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                $string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
            } else {
                $string[] = null;
            }
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
    }
} */

if (! function_exists('AmountInWords')) {
    function AmountInWords(float $amount)
    {
        return convertCurrencyToWords($amount);
    }
}

function convertCurrencyToWords($amount)
{
    $fmt = new NumberFormatter("en", NumberFormatter::SPELLOUT);

    $amount = number_format($amount, 2, '.', '');
    $parts = explode('.', $amount);

    $rupees = (int)$parts[0];
    $paise = (int)$parts[1];

    // $words = ucfirst($fmt->format($rupees)) . ' Rupees';
    $words = ucfirst(str_replace('-', ' ', $fmt->format($rupees))) . ' Rupees';

    if ($paise > 0) {
        $words .= ' and ' . $fmt->format($paise) . ' Paise';
    }

    return $words;
}


if (!function_exists('get_pending_leaves_count')) {
    function get_pending_leaves_count()
    {
        $current_user = session()->get('current_user');
        $superuser = ["admin", "superuser"];
        $current_user_role = $current_user['role'];
        $LeaveRequestsModel = new \App\Models\LeaveRequestsModel;
        $LeaveRequestsModel
            ->select('count(leave_requests.id) as count')
            ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e.department_id', 'left')
            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->where('leave_requests.status =', 'pending')
            ->groupStart()
            ->where('e.reporting_manager_id =', $current_user['employee_id'])
            ->orWhere('d.hod_employee_id =', $current_user['employee_id'])
            ->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser'])
            ->groupEnd()
            ->where('e.id !=', $current_user['employee_id']);

        if (!in_array($current_user_role, ['hr', 'HR'])) {
            $LeaveRequestsModel->where('leave_requests.type_of_leave !=', 'COMP OFF');
        }

        $leave_requests = $LeaveRequestsModel->first();

        if (!empty($leave_requests)) {
            return $leave_requests['count'];
        } else {
            return null;
        }
    }
}

if (!function_exists('get_pending_ods_count')) {
    function get_pending_ods_count()
    {
        $current_user = session()->get('current_user');
        $superuser = ["admin", "superuser"];
        $current_user_role = $current_user['role'];
        // echo $current_user_role;
        $OdRequestsModel = new \App\Models\OdRequestsModel;
        $OdRequestsModel
            ->select('count(od_requests.id) as count')
            ->join('employees as e', 'e.id = od_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e.department_id', 'left')
            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->where('od_requests.status =', 'pending')
            ->groupStart()
            ->where('e.reporting_manager_id =', $current_user['employee_id'])
            ->orWhere('d.hod_employee_id =', $current_user['employee_id'])
            ->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser'])
            ->groupEnd()
            ->where('e.id !=', $current_user['employee_id']);
        $od_requests = $OdRequestsModel->first();
        if (!empty($od_requests)) {
            return $od_requests['count'];
        } else {
            return null;
        }
    }
}

if (!function_exists('get_pending_loans_count')) {
    function get_pending_loans_count()
    {
        $current_user = session()->get('current_user');
        $superuser = ["admin", "superuser"];
        $current_user_role = $current_user['role'];
        $UserLoanModel = new \App\Models\UserLoanModel;
        $UserLoanModel
            ->select('count(loan_requests.id) as count')
            ->join('employees as e', 'e.id = loan_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e.department_id', 'left')
            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->where('loan_requests.review_status =', 'pending')
            ->groupStart()
            ->where('e.reporting_manager_id =', $current_user['employee_id'])
            ->orWhere('d.hod_employee_id =', $current_user['employee_id'])
            ->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser'])
            ->groupEnd()
            ->where('e.id !=', $current_user['employee_id']);
        $loan_requests = $UserLoanModel->first();
        if (!empty($loan_requests)) {
            return $loan_requests['count'];
        } else {
            return null;
        }
    }
}

if (!function_exists('get_pending_advance_salary_count')) {
    function get_pending_advance_salary_count()
    {
        $current_user = session()->get('current_user');
        $superuser = ["admin", "superuser"];
        $current_user_role = $current_user['role'];
        $AdvanceSalaryModel = new \App\Models\AdvanceSalaryModel;
        $AdvanceSalaryModel
            ->select('count(advance_salary_requests.id) as count')
            ->join('employees as e', 'e.id = advance_salary_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e.department_id', 'left')
            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->where('advance_salary_requests.review_status =', 'pending')
            ->groupStart()
            ->where('e.reporting_manager_id =', $current_user['employee_id'])
            ->orWhere('d.hod_employee_id =', $current_user['employee_id'])
            ->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser'])
            ->groupEnd()
            ->where('e.id !=', $current_user['employee_id']);
        $advance_salary_requests = $AdvanceSalaryModel->first();
        if (!empty($advance_salary_requests)) {
            return $advance_salary_requests['count'];
        } else {
            return null;
        }
    }
}

if (!function_exists('get_pending_gate_pass_count')) {
    function get_pending_gate_pass_count()
    {
        $current_user = session()->get('current_user');
        $current_user_role = $current_user['role'];
        $GatePassRequestsModel = new \App\Models\GatePassRequestsModel;
        $GatePassRequestsModel
            ->select('count(gate_pass_requests.id) as count')
            ->join('employees as e', 'e.id = gate_pass_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e.department_id', 'left')
            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            // ->where('gate_pass_requests.status =', 'pending')
            // ->where('gate_pass_requests.gate_pass_date =', date('Y-m-d'))
            ->where('gate_pass_requests.gate_pass_date =', current_date_of_month())
            ->groupStart()
            ->where('e.reporting_manager_id =', $current_user['employee_id'])
            ->orWhere('d.hod_employee_id =', $current_user['employee_id'])
            ->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr'])
            ->groupEnd()
            ->where('e.id !=', $current_user['employee_id']);
        $pending_gate_pass_requests = $GatePassRequestsModel->first();
        if (!empty($pending_gate_pass_requests)) {
            return $pending_gate_pass_requests['count'];
        } else {
            return null;
        }
    }
}



// if (!function_exists('get_pending_comp_off_credit_request_count')) {
//     function get_pending_comp_off_credit_request_count()
//     {
//         $current_user = session()->get('current_user');
//         $current_user_role = $current_user['role'];
//         $ninty_days_ago = date('Y-m-d', strtotime("-90 days"));
//         $CompOffCreditModel = new \App\Models\CompOffCreditModel;
//         $CompOffCreditModel
//             ->select('count(comp_off_credit_requests.id) as count')
//             ->join('employees as e', 'e.id = comp_off_credit_requests.employee_id', 'left')
//             ->join('departments as d', 'd.id = e.department_id', 'left')
//             ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
//             ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
//             ->join('companies as c', 'c.id = e.company_id', 'left')
//             // ->where('comp_off_credit_requests.status =', 'pending')
//             ->whereIn('comp_off_credit_requests.status', ['pending', 'stage_1'])
//             // ->where('comp_off_credit_requests.working_date >=', $ninty_days_ago)
//             ->where("comp_off_credit_requests.working_date between '" . $ninty_days_ago . "' and '" . date('Y-m-d') . "'")
//             // ->groupStart()
//             // ->where('e.reporting_manager_id =', $current_user['employee_id'])
//             // ->orWhere('d.hod_employee_id =', $current_user['employee_id'])
//             // ->orWhereIn( "'".$current_user_role."'", ['admin', 'superuser', 'hr'] )
//             // ->groupEnd()
//             ->where('e.id !=', $current_user['employee_id']);

//         /*if( $current_user['employee_id'] == '1' ){
//             $CompOffCreditModel->groupStart();
//             $CompOffCreditModel->where('e.reporting_manager_id =', $current_user['employee_id']);
//             $CompOffCreditModel->groupEnd();
//         }elseif( $current_user['employee_id'] !== '40' && $current_user['role'] !== 'hr' ){
//             $CompOffCreditModel->groupStart();
//                 $CompOffCreditModel->where('c.company_hod =', $current_user['employee_id']);
//                 $CompOffCreditModel->orWhere('e.reporting_manager_id =', $current_user['employee_id']);
//             $CompOffCreditModel->groupEnd();
//         }*/


//         $pending_comp_off_credit_request = $CompOffCreditModel->first();
//         if (!empty($pending_comp_off_credit_request)) {
//             return $pending_comp_off_credit_request['count'];
//         } else {
//             return null;
//         }
//     }
// }

if (!function_exists('get_pending_comp_off_credit_request_count')) {
    function get_pending_comp_off_credit_request_count()
    {
        $current_user = session()->get('current_user');
        $current_user_role = $current_user['role'];
        $current_user_employee_id = $current_user['employee_id'];
        $ninty_days_ago = date('Y-m-d', strtotime("-90 days"));
        $CompOffCreditModel = new \App\Models\CompOffCreditModel;
        $CompOffCreditModel
            ->select('comp_off_credit_requests.*')
            ->select('e.machine as employee_machine_location')
            ->select('e2.id as reporting_manager_id')
            ->join('employees as e', 'e.id = comp_off_credit_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e.department_id', 'left')
            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('companies as c', 'c.id = e.company_id', 'left')
            ->whereIn('comp_off_credit_requests.status', ['pending', 'stage_1'])
            ->where("comp_off_credit_requests.working_date between '" . $ninty_days_ago . "' and '" . date('Y-m-d') . "'");

        // Apply filtering based on user role and employee ID
        $CompOffCreditModel->groupStart();
        $CompOffCreditModel->where('e.reporting_manager_id =', $current_user_employee_id);
        $CompOffCreditModel->orWhere('d.hod_employee_id =', $current_user_employee_id);
        $CompOffCreditModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
        $CompOffCreditModel->orWhereIn("'" . $current_user_employee_id . "'", ['54']);
        $CompOffCreditModel->groupEnd();

        if ($current_user_employee_id != '40') {
            $CompOffCreditModel->where('e.id !=', $current_user_employee_id);
        }

        $CompOffCreditRequests = $CompOffCreditModel->findAll();

        // Apply additional filtering based on employee ID and machine location
        $count = 0;
        foreach ($CompOffCreditRequests as $dataRow) {
            if ($current_user_employee_id == '1') {
                // Employee 1 (Manu) - exclude 'hn' machine employees and pending requests where he's not the reporting manager
                if ($dataRow['employee_machine_location'] == 'hn') {
                    continue;
                } elseif ($dataRow['status'] == 'pending' && $dataRow['reporting_manager_id'] != '1') {
                    continue;
                }
            } elseif ($current_user_employee_id == '54') {
                // Employee 54 (Aryan) - only see 'hn' machine employees OR his direct reports
                if ($dataRow['employee_machine_location'] != 'hn' && $dataRow['reporting_manager_id'] != '54') {
                    continue;
                }
            }
            $count++;
        }

        return $count > 0 ? $count : null;
    }
}


if (!function_exists('get_pending_deduction_request_count')) {
    function get_pending_deduction_request_count()
    {
        $current_user = session()->get('current_user');
        $current_user_role = $current_user['role'];
        $ninty_days_ago = date('Y-m-d', strtotime("-90 days"));
        $DeductionModel = new \App\Models\DeductionModel;
        $DeductionModel
            ->select('count(deduction_minutes.id) as count')
            ->join('employees as e', 'e.id = deduction_minutes.employee_id', 'left')
            ->join('departments as d', 'd.id = e.department_id', 'left')
            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('companies as c', 'c.id = e.company_id', 'left')
            ->where('deduction_minutes.current_status =', 'pending');

        if ($current_user['employee_id'] == 40) {
        } elseif (in_array($current_user_role, ['hr'])) {
            $DeductionModel->where('e.id !=', $current_user['employee_id']);
        } else {
            $DeductionModel->where('e.id !=', $current_user['employee_id']);
            $DeductionModel->where('e.reporting_manager_id =', $current_user['employee_id']);
        }

        $pending_deduction_request = $DeductionModel->first();
        if (!empty($pending_deduction_request)) {
            return $pending_deduction_request['count'];
        } else {
            return null;
        }
    }
}

if (!function_exists('get_active_resignation_count')) {
    function get_active_resignation_count()
    {
        $ResignationModel = new \App\Models\ResignationModel;
        $count = $ResignationModel->where('status', 'active')->countAllResults();
        return $count > 0 ? $count : null;
    }
}

if (!function_exists('getAllPunchData')) {
    function getAllPunchData($empcode, $fromDateTime, $toDateTime, $machine)
    {
        if (empty($empcode) || empty($fromDateTime) || empty($toDateTime) || empty($machine)) {
            return null;
        }

        switch ($machine) {
            case 'del':
                $corporate_id = env('del.CORPORATE_ID');
                $username = env('del.USERNAME');
                $password = env('del.PASSWORD');
                break;

            case 'ggn':
                $corporate_id = env('ggn.CORPORATE_ID');
                $username = env('ggn.USERNAME');
                $password = env('ggn.PASSWORD');
                break;

            case 'hn':
                $corporate_id = env('hn.CORPORATE_ID');
                $username = env('hn.USERNAME');
                $password = env('hn.PASSWORD');
                break;

            case 'skbd':
                $corporate_id = env('skbd.CORPORATE_ID');
                $username = env('skbd.USERNAME');
                $password = env('skbd.PASSWORD');
                break;

            default:
                $corporate_id = "";
                $username = "";
                $password = "";
                break;
        }
        if (!empty($corporate_id)) {
            $url = "https://api.etimeoffice.com/api/DownloadPunchData";
            $postData = "Empcode=" . $empcode . "&FromDate=" . $fromDateTime . "&ToDate=" . $toDateTime;
            $auth = base64_encode($corporate_id . ":" . $username . ":" . $password . ":true");
            $headers = array("Authorization: Basic " . $auth);
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url . '?' . $postData);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($resp, true);
            $punchData = $data['PunchData'] ?? [];
            return $punchData;
        }
        return null;
    }
}

if (!function_exists('getPendingApprovalCounts')) {
    function getPendingApprovalCounts()
    {
        $current_user = session()->get('current_user');
        $current_user_id = $current_user['employee_id'];
        $total_pending = 0;

        $jobListingModel = new \App\Models\Recruitment\RcJobListingModel();
        $baseQuery = function () use ($jobListingModel) {
            return $jobListingModel->whereNotIn('rc_job_listing.status', ['closed', 'rejected', 'draft']);
        };

        if ($current_user_id == 52) {
            $total_pending += $baseQuery()
                ->where('rc_job_listing.approved_by_hr_executive IS NULL')
                ->countAllResults();
        }

        $hod_pending_count = $baseQuery()
            ->join('departments', 'departments.id = rc_job_listing.department_id')
            ->where('departments.hod_employee_id', $current_user_id)
            ->where('rc_job_listing.approved_by_hr_executive IS NOT NULL')
            ->where('rc_job_listing.approved_by_hod IS NULL')
            ->countAllResults();
        $total_pending += $hod_pending_count;

        if ($current_user_id == 293) {
            $total_pending += $baseQuery()
                ->where('rc_job_listing.approved_by_hr_executive IS NOT NULL')
                ->where('rc_job_listing.approved_by_hod IS NOT NULL')
                ->where('rc_job_listing.approved_by_hr_manager IS NULL')
                ->countAllResults();
        }

        if ($total_pending > 0) {
            return $total_pending;
        }

        return null;
    }
}
