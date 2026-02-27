<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin-bottom: 15mm;
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
            font-size: 9px;
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
            /* table-layout: fixed; */
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 4px;
            vertical-align: middle;
            word-wrap: break-word;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .bg-success {
            background-color: #d4edda;
        }

        .bg-danger {
            background-color: #f8d7da;
        }

        .bg-warning {
            background-color: #fff3cd;
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
            height: 70px;
            text-align: center;
            /* background: blue; */
            /* border-bottom: 1px solid red; */
            /* padding-bottom: 5px; */
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
            bottom: 0px;
            left: 0;
            right: 0;
            height: 30px;
            /* background-color: green; */
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
            margin-top: 100px;
        }

        .employee-info {
            text-align: center;
            margin-bottom: 10px;
        }

        .employee-info p {
            margin: 3px 0;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <!-- Page number footer (appears on all pages) -->
    <div class="page-number"></div>

    <?php
    $totalEmployees = count($employees);
    // $currentIndex = 0;
    foreach ($employees as $employee) {
        // $currentIndex++;
    ?>
        <div class="employee-page">
            <!-- Header for this page -->
            <div class="page-header">
                <table style="width:100%; margin-bottom:10px; border:none;">
                    <tr>
                        <td colspan="3" style="text-align:center; border:none;">
                            <p style="margin-bottom:8px;">Overtime Register – Form IV (Rule 25(2))</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:80px; vertical-align:middle; border:none; text-align:left;">

                        </td>
                        <td style="text-align:center; vertical-align:middle; border:none;">
                            <h1 style="margin-bottom:0;"><?= $employee['company_name'] ?></h1>
                            <p style="margin-bottom:0;"><?= $employee['company_address'] ?></p>
                        </td>
                        <td style="width:80px; border:none;"></td>
                    </tr>
                </table>
            </div>

            <!-- Footer for this page -->
            <div class="page-footer">
                <table class="footer-table">
                    <tr>
                        <td style="width:70%;">

                        </td>
                        <td style="width: 30%;">
                            <strong>Employee Signature</strong>
                            <p style="height:50px;">&nbsp;</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Main Content -->
            <div class="content">
                <p style="margin-bottom:10px;"><strong>Month:</strong> <?= date('F Y', strtotime($month)) ?></p>
                <table>
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th style="white-space:nowrap;">Employee Name</th>
                            <th style="white-space:nowrap;">Designation</th>
                            <th style="white-space:nowrap;">Date</th>
                            <th>Normal Working Hours</th>
                            <th>Overtime Hours</th>
                            <th>Rate of Overtime</th>
                            <th>Overtime Earnings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 0;
                        foreach ($employee['records'] as $record):
                        ?>
                            <tr>
                                <td><?= ++$index ?></td>
                                <td style="white-space:nowrap;"><?= $employee['employee_name'] ?></td>
                                <td style="white-space:nowrap;"><?= $employee['designation_name'] ?></td>
                                <td style="white-space:nowrap;"><?= date('d M, Y', strtotime($record['date'])) ?></td>
                                <td><?= $record['shift_hours'] ?></td>
                                <td><?= !empty($record['overtime_hours']) ? $record['overtime_hours'] : 'NIL' ?></td>
                                <td><?= !empty($record['overtime_rate']) ? $record['overtime_rate'] : 'NIL' ?></td>
                                <td><?= !empty($record['overtime_earning']) ? $record['overtime_earning'] : 'NIL' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    ?>
</body>

</html>