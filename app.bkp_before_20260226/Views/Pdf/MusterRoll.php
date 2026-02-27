<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 10mm 5mm 15mm 5mm;
        }

        /* Page number footer */
        .page-number {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
        }

        .page-number:before {
            content: "Page " counter(page);
        }


        body {
            margin: 0 auto;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            line-height: 1;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12px;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 2px 1px;
            vertical-align: middle;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }


        .employee-header {
            background-color: #e9ecef;
            font-weight: bold;
        }

        /* Fixed Header Styles */
        .page-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 14px;
        }

        .page-header p {
            margin: 2px 0;
            font-size: 10px;
        }

        /* Fixed Footer Styles */
        .page-footer {
            position: fixed;
            bottom: 80px;
            left: 0;
            right: 0;
            height: 30px;
        }

        .footer-table {
            font-size: 9px;
            margin: 0;
            border: none;
        }

        .footer-table th,
        .footer-table td {
            padding: 3px 5px;
            text-align: left;
            border: none;
        }

        .footer-table th {
            text-align: center;
        }

        /* Employee Page Container */
        .employee-page {
            page-break-after: always;
        }

        .employee-page:last-child {
            page-break-after: avoid;
        }

        /* Content area */
        .content {
            margin-top: 120px;
        }

        .employee-info {
            text-align: center;
            margin-bottom: 10px;
        }

        .employee-info p {
            margin: 3px 0;
            font-size: 11px;
        }

        /* Date columns styling */
        .date-col {
            width: 16px;
            font-size: 7px;
            line-height: 1.1;
            word-break: break-all;
        }

        .date-header {
            /* background-color: #c8e6c9; */
            font-weight: bold;
        }

        .summary-col {
            /* font-size: 7px; */
            /* padding: 2px 1px; */
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <!-- Page number footer (appears on all pages) -->
    <div class="page-number"></div>

    <?php
    foreach ($companies as $company) {
    ?>
        <div class="employee-page">
            <!-- Header for this page -->
            <div class="page-header">
                <table style="width:100%; margin-bottom:10px; border:none;">
                    <tr>
                        <td colspan="3" style="text-align:center; border:none;">
                            <p style="margin-bottom:8px;">Muster Roll – Form V (Rule 26(5))</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:80px; vertical-align:middle; border:none; text-align:left;">

                        </td>
                        <td style="text-align:center; vertical-align:middle; border:none;">
                            <h1 style="margin-bottom:0;"><?= $company['company_name'] ?></h1>
                            <p style="margin-bottom:0;"><?= $company['company_address'] ?></p>
                        </td>
                        <td style="width:80px; border:none;"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:center; border:none;">
                            <p style="margin-top:5px;"><strong>Month: <?= $month_name ?></strong></p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Footer for this page -->
            <div class="page-footer">
            </div>

            <!-- Main Content -->
            <div class="content">
                <table>
                    <thead>
                        <tr>
                            <th style="width:25px;">S.No.</th>
                            <th style="white-space:nowrap; ">Employee Name</th>
                            <th style="white-space:nowrap;">Department</th>
                            <th style="white-space:nowrap; ">Designation</th>
                            <?php
                            // Generate date headers
                            for ($day = 1; $day <= $days_in_month; $day++) {
                                $dateStr = date('Y-m', strtotime($month)) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                            ?>
                                <th class="date-col date-header"><?= $day ?></th>
                            <?php } ?>
                            <th class="summary-col">Present</th>
                            <th class="summary-col">W/O</th>
                            <th class="summary-col">CL</th>
                            <th class="summary-col">EL</th>
                            <th class="summary-col">HL</th>
                            <th class="summary-col">Absent</th>
                            <th class="summary-col">Total Paid Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $employees = $company['employees'];
                        $currentIndex = 0;

                        $totalPresent = 0;
                        $totalWeekOff = 0;
                        $totalCL = 0;
                        $totalEL = 0;
                        $totalHL = 0;
                        $totalAbsent = 0;
                        $totalPaidDays = 0;

                        foreach ($employees as $employee) {
                            $currentIndex++;
                            $attendance = $employee['attendance'] ?? [];
                            $summary = $employee['summary'] ?? [];

                            $totalPresent += $summary['present'] ?? 0;
                            $totalWeekOff += $summary['week_off'] ?? 0;
                            $totalCL += $summary['cl'] ?? 0;
                            $totalEL += $summary['el'] ?? 0;
                            $totalHL += $summary['hl'] ?? 0;
                            $totalAbsent += $summary['absent'] ?? 0;
                            $totalPaidDays += $summary['total_paid_days'] ?? 0;
                        ?>
                            <tr>
                                <td><?= $currentIndex ?></td>
                                <td style="white-space:nowrap; text-align:left;"><?= $employee['employee_name'] ?></td>
                                <td style="white-space:nowrap;"><?= $employee['department_name'] ?? '' ?></td>
                                <td style="white-space:nowrap;"><?= $employee['designation_name'] ?? '' ?></td>
                                <?php
                                // Generate date cells with attendance status
                                for ($day = 1; $day <= $days_in_month; $day++) {
                                    $dateStr = date('Y-m', strtotime($month)) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                                    $dayRecord = $attendance[$dateStr] ?? null;
                                    $status = '';
                                    $cellClass = 'date-col';

                                    if ($dayRecord) {
                                        $status = strtoupper($dayRecord['status'] ?? '');
                                        // Split long status into multiple lines at + sign
                                        if (strpos($status, '+') !== false) {
                                            $status = str_replace('+', '<br>+', $status);
                                        }
                                    }
                                ?>
                                    <td class="<?= $cellClass ?>"><?= $status /* allows HTML for line breaks */ ?></td>
                                <?php } ?>
                                <td class="summary-col"><?= round($summary['present'] ?? 0, 1) ?></td>
                                <td class="summary-col"><?= round($summary['week_off'] ?? 0, 1) ?></td>
                                <td class="summary-col"><?= round($summary['cl'] ?? 0, 1) ?></td>
                                <td class="summary-col"><?= round($summary['el'] ?? 0, 1) ?></td>
                                <td class="summary-col"><?= round($summary['hl'] ?? 0, 1) ?></td>
                                <td class="summary-col"><?= round($summary['absent'] ?? 0, 1) ?></td>
                                <td class="summary-col"><?= round($summary['total_paid_days'] ?? 0, 2) ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <!-- Summary/Total Row -->
                        <tr style="background-color: #f0f0f0; font-weight: bold;">
                            <td colspan="<?= 4 + $days_in_month ?>" style="text-align: right;">Total</td>
                            <td class="summary-col"><?= round($totalPresent, 1) ?></td>
                            <td class="summary-col"><?= round($totalWeekOff, 1) ?></td>
                            <td class="summary-col"><?= round($totalCL, 1) ?></td>
                            <td class="summary-col"><?= round($totalEL, 1) ?></td>
                            <td class="summary-col"><?= round($totalHL, 1) ?></td>
                            <td class="summary-col"><?= round($totalAbsent, 1) ?></td>
                            <td class="summary-col"><?= round($totalPaidDays, 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    ?>

</body>

</html>