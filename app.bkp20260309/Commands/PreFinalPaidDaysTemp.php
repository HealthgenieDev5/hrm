<?php

namespace App\Commands;

use App\Models\EmployeeModel;
use App\Models\PreFinalPaidDaysModel;
use App\Models\PreFinalPaidDaysTempModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PreFinalPaidDaysTemp extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'pre-final-paid-days:update-work-hours';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Updates work_hours field in pre_final_paid_days table by calculating punch_out - punch_in';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'pre-final-paid-days:update-work-hours [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--month'    => 'Target month for processing (e.g. 2024-05). If not provided, processes all records.',
        '--year'    => 'Target year for processing (e.g. 2024). If not provided, processes all records.',
        '--employee' => 'Specific employee ID to process only that user',
        '--chunk'    => 'Chunk size per batch (default: 1000)',
    ];


    public function run(array $params)
    {
        $startTime = microtime(true);

        $options = [];
        foreach ($params as $param => $v) {
            if (strpos($param, '=') !== false) {
                [$key, $value] = explode('=', $param, 2);
                $options[$key] = $value;
            }
        }

        $month = $options['month'] ?? date('Y-m-d', strtotime('first day of last month'));
        $year = $options['year'] ?? null;
        $employeeIds = !empty($options['employee']) ? explode(',', $options['employee']) : null;
        $chunkSize = (int) ($options['chunk'] ?? 100);



        CLI::write('Starting work_hours update...', 'yellow');
        CLI::write('Month      : ' . ($month ?? 'All'), 'light_gray');
        CLI::write('year      : ' . ($year ?? 'All'), 'light_gray');
        CLI::write('Employee ID: ' . ($employeeIds ? implode(', ', $employeeIds) : 'All'), 'light_gray');
        CLI::write('Chunk Size : ' . $chunkSize, 'light_gray');
        CLI::newLine();


        if ($year) {
            $rangeFrom = $year . '-01-01';
            $rangeTo = $year . '-12-31';
        } else if ($month) {
            $rangeFrom = date('Y-m-01', strtotime($month));
            $rangeTo = date('Y-m-t', strtotime($month));
        }

        $EmployeeModel = new EmployeeModel();
        // $employeeIds = !empty($employeeIds) ? $employeeIds : $EmployeeModel->where('id =', 4)->findColumn('id');
        $employeeIds = !empty($employeeIds) ? $employeeIds : $EmployeeModel->findColumn('id');
        $employeeChunks = array_chunk($employeeIds, $chunkSize);



        foreach ($employeeChunks as $index => $employeeChunk) {

            CLI::write("Executing chunk id : {$index}", 'light_gray');
            CLI::newLine();



            $PreFinalPaidDaysModel = new PreFinalPaidDaysModel();
            $PreFinalPaidDaysModel
                ->select('*');
            if (!empty($employeeChunk)) {
                $PreFinalPaidDaysModel
                    ->whereIn('employee_id', $employeeChunk);
            }

            // $PreFinalPaidDaysModel
            //     ->where('pre_final_paid_days.date =', '2025-03-19');
            $PreFinalPaidDaysModel
                ->where('pre_final_paid_days.date >=', $rangeFrom)
                ->where('pre_final_paid_days.date <=', $rangeTo);

            $PreFinalPaidDaysModel->orderBy('pre_final_paid_days.date', 'ASC');

            $RecordChunks = $PreFinalPaidDaysModel->findAll();
            $RecordChunksCount = count($RecordChunks);



            foreach ($RecordChunks as $i => $recordRow) {

                // print_r($recordRow);
                // die();

                $shift_start_original = date('Y-m-d H:i:s', strtotime($recordRow['shift_start']));
                $shift_end_original = date('Y-m-d H:i:s', strtotime($recordRow['shift_end']));



                $shift_start = $shift_start_original;
                $shift_end = $shift_end_original;
                // Add a day if overnight shift
                if (strtotime($shift_start_original) > strtotime($shift_end_original)) {
                    $shift_end = date('Y-m-d H:i:s', strtotime($shift_end_original . " +1 days"));
                }

                // Modify shift end time if more than 08:30 Hours
                $shiftDuration = !empty($shift_start) && !empty($shift_end) ? ProcessorHelper::get_time_difference($shift_start, $shift_end, 'minutes') : 0;
                if ($shiftDuration > 510) {
                    $shiftDuration = 510;
                    $shift_end = date('Y-m-d H:i:s', strtotime($shift_start . " +" . $shiftDuration . " minutes"));
                }

                if (!empty($recordRow['in_time_including_od']) && empty($recordRow['in_time_between_shift_with_od'])) {
                    $recordRow['in_time_including_od'] = null;
                }
                if (!empty($recordRow['out_time_including_od']) && empty($recordRow['out_time_between_shift_with_od'])) {
                    $recordRow['out_time_including_od'] = null;
                }

                // print_r($recordRow);
                // die();

                $punchInTimeOriginal = !empty($recordRow['in_time_including_od']) ? date('Y-m-d H:i:s', strtotime($recordRow['in_time_including_od'])) : null;
                $punchOutTimeOriginal = !empty($recordRow['out_time_including_od']) ? date('Y-m-d H:i:s', strtotime($recordRow['out_time_including_od'])) : null;

                // print_r(
                //     [
                //         'in_time_including_od' => $recordRow['in_time_including_od'],
                //         'out_time_including_od' => $recordRow['out_time_including_od'],
                //         'punchInTimeOriginal' => $punchInTimeOriginal,
                //         'punchOutTimeOriginal' => $punchOutTimeOriginal,
                //     ]
                // );
                // die();




                if (!empty($punchInTimeOriginal) && !empty($punchOutTimeOriginal)) {
                    if (strtotime($punchInTimeOriginal) > strtotime($punchOutTimeOriginal)) {
                        if (strtotime($shift_start_original) > strtotime($shift_end_original)) {
                            $punchOutTimeOriginal = date('Y-m-d H:i:s', strtotime($punchOutTimeOriginal . " +1 days"));
                        } else {
                            $punchOutTimeOriginal = date('Y-m-d H:i:s', strtotime($punchInTimeOriginal . " +1 minutes"));
                        }
                    }
                }

                // print_r(
                //     [
                //         'in_time_including_od' => $recordRow['in_time_including_od'],
                //         'out_time_including_od' => $recordRow['out_time_including_od'],
                //         'punchInTimeOriginal' => $punchInTimeOriginal,
                //         'punchOutTimeOriginal' => $punchOutTimeOriginal,
                //     ]
                // );
                // die();


                // Allow punching 15 minutes before 
                // If punched in more than 15 minutes before then set the punch in time randomely 15 to 30 minutes before
                if (!empty($punchInTimeOriginal)) {
                    // $punchInTimeOriginal = date('Y-m-d H:i:s', strtotime($recordRow['in_time_including_od']));
                    $shift_start_with_offset = date('Y-m-d H:i:s', strtotime($shift_start . " -15 minutes"));
                    if (strtotime($punchInTimeOriginal) < strtotime($shift_start_with_offset)) {
                        $offset = rand(5, 15);
                        $punchInTime = date('Y-m-d H:i:s', strtotime($shift_start . " -" . $offset . " minutes"));
                    } else {
                        $punchInTime = $punchInTimeOriginal;
                    }
                } else {
                    $punchInTime = null;
                }


                // check if the punch out is before punch in 
                // if yes then add 1 day to punch out

                if (!empty($punchInTime) && !empty($punchOutTimeOriginal)) {
                    if (strtotime($punchInTime) > strtotime($punchOutTimeOriginal)) {
                        // if (strtotime($shift_start_original) > strtotime($shift_end_original)) {
                        //     $punchOutTimeOriginal = date('Y-m-d H:i:s', strtotime($punchOutTimeOriginal . " +1 days"));
                        // } else {
                        //     $punchOutTimeOriginal = date('Y-m-d H:i:s', strtotime($punchInTime . " +1 minutes"));
                        // }
                        $punchOutTimeOriginal = date('Y-m-d H:i:s', strtotime($punchOutTimeOriginal . " +1 days"));
                    }
                }




                // Allow punching out 15 minutes later 
                // If punched out more than 15 minutes later then set the punch out time randomely 15 to 30 minutes later
                if (!empty($punchOutTimeOriginal)) {
                    $shift_end_with_offset = date('Y-m-d H:i:s', strtotime($shift_end . " +15 minutes"));
                    if (strtotime($punchOutTimeOriginal) > strtotime($shift_end_with_offset)) {
                        $offset = rand(5, 15);
                        $punchOutTime = date('Y-m-d H:i:s', strtotime($shift_end . " +" . $offset . " minutes"));
                    } else {
                        $punchOutTime = $punchOutTimeOriginal;
                    }
                } else {
                    $punchOutTime = null;
                }

                // print_r(
                //     [
                //         'in_time_including_od' => $recordRow['in_time_including_od'],
                //         'out_time_including_od' => $recordRow['out_time_including_od'],
                //         'punchInTimeOriginal' => $punchInTimeOriginal,
                //         'punchOutTimeOriginal' => $punchOutTimeOriginal,
                //         'punchInTime' => $punchInTime,
                //         'punchOutTime' => $punchOutTime,
                //     ]
                // );
                // die();

                if (strtotime($punchInTime) > strtotime($punchOutTime)) {
                    $punchOutTimeTemp = $punchOutTime;
                    $punchOutTime = $punchInTime;
                    $punchInTime = $punchOutTimeTemp;
                }

                $WorkDuration = !empty($punchInTime) && !empty($punchOutTime) ? ProcessorHelper::get_time_difference($punchInTime, $punchOutTime, 'hours') : null;

                $RecordChunks[$i]['shift_start'] = date('H:i:s', strtotime($shift_start));
                $RecordChunks[$i]['shift_end'] = date('H:i:s', strtotime($shift_end));
                $RecordChunks[$i]['punch_in_time'] = !empty($punchInTime) ? date('H:i:s', strtotime($punchInTime)) : null;
                $RecordChunks[$i]['punch_out_time'] = !empty($punchOutTime) ? date('H:i:s', strtotime($punchOutTime)) : null;
                $RecordChunks[$i]['work_hours'] = $WorkDuration;
            }



            CLI::write("Updating chunk id : {$index}", 'yellow');
            CLI::newLine();

            $PreFinalPaidDaysTempModel = new PreFinalPaidDaysTempModel();
            $result = true;
            if (!empty($RecordChunks)) {
                $result = $PreFinalPaidDaysTempModel->insertBatch($RecordChunks);
            }

            if ($result !== false) {
                CLI::write("Updated chunk id : {$index}", 'yellow');
                CLI::newLine();
            } else {
                CLI::write("Failed chunk id : {$index}", 'red');
                CLI::newLine();
            }
        }






        $endTime = microtime(true);

        CLI::newLine();
        CLI::write("Update completed successfully!", 'green');
        $executionTime = round($endTime - $startTime, 2);
        CLI::write("Execution time: {$executionTime} seconds", 'light_gray');

        CLI::newLine();
        CLI::write('Done.', 'green');
    }
}
