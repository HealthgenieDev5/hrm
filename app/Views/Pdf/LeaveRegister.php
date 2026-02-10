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
            padding: 2px 3px;
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
            height: 120px;
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
            /* background: blue; */
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
            margin-top: 140px;
            /* background: green; */
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
    $currentIndex = 0;
    foreach ($employees as $employee) {
        $currentIndex++;
    ?>
        <div class="employee-page">
            <!-- Header for this page -->
            <div class="page-header">
                <table style="width:100%; margin-bottom:20px; border:none;">
                    <tr>
                        <td colspan="3" style="text-align:center; border:none;">
                            <p style="margin-bottom:8px;">Register of Leave (Rule 14)</p>
                        </td>
                    </tr>
                </table>
                <table style="border: none; width: 60%">
                    <tr>
                        <td style="border: none; text-align:left; width: 120px;">
                            <strong>Name of Establishment</strong>
                        </td>
                        <td style="border: none; text-align:left; border-bottom: 1px dotted grey;">
                            <?= $employee['company_name'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align:left; width: 120px;">
                            <strong>Name of Employee</strong>
                        </td>
                        <td style="border: none; text-align:left; border-bottom: 1px dotted grey;">
                            <?= $employee['employee_name'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align:left; width: 120px;">
                            <strong>Date of Joining</strong>
                        </td>
                        <td style="border: none; text-align:left; border-bottom: 1px dotted grey;">
                            <?= date('d M, Y', strtotime($employee['joining_date'])) ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align:left; width: 120px;">
                            <strong>Year</strong>
                        </td>
                        <td style="border: none; text-align:left; border-bottom: 1px dotted grey;">
                            <?= date('Y', strtotime($month)) ?>
                        </td>
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
                <table style="padding:0; border: none;">
                    <tr>
                        <td style="width:50%; vertical-align: top; padding:0; padding-right:10px; border: none;">
                            <!-- CL (Casual Leave) Table -->
                            <h2 style="margin-bottom:10px;">Casual Leave (CL) & Sick Leave - Balance: <?= $employee['cl_balance'] ?? 0 ?></h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="white-space:nowrap;">Request Date</th>
                                        <th style="white-space:nowrap;">From</th>
                                        <th style="white-space:nowrap;">To</th>
                                        <th>Leave Availed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($employee['cl_requests'])): ?>
                                        <?php $total_cl =  0; ?>
                                        <?php foreach ($employee['cl_requests'] as $record): ?>
                                            <tr>
                                                <td style="white-space:nowrap;"><?= !empty($record['date_time']) ? date('d M, Y', strtotime($record['date_time'])) : '-' ?></td>
                                                <td style="white-space:nowrap;"><?= date('d M, Y', strtotime($record['from_date'])) ?></td>
                                                <td style="white-space:nowrap;"><?= date('d M, Y', strtotime($record['to_date'])) ?></td>
                                                <td><?= $record['number_of_days'] ?? '-' ?></td>
                                            </tr>
                                            <?php $total_cl += $record['number_of_days']; ?>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td style="white-space:nowrap;" colspan='3'> Total CL & Sick Leave Availed</td>
                                            <td><?= $total_cl ?? '-' ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" style="text-align:center; padding:10px;">No CL records found for this year</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </td>
                        <td style="width:50%; vertical-align: top; padding:0; border: none;">
                            <!-- EL (Earned Leave) Table -->
                            <h2 style="margin-bottom:10px;">Earned Leave (EL) - Balance: <?= $employee['el_balance'] ?? 0 ?></h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Request Date</th>
                                        <th>Status</th>
                                        <th style="white-space:nowrap;">From</th>
                                        <th style="white-space:nowrap;">To</th>
                                        <th>Leave Availed</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($employee['el_requests'])): ?>
                                        <?php $total_el =  0; ?>
                                        <?php foreach ($employee['el_requests'] as $record): ?>
                                            <tr>
                                                <td style="white-space:nowrap;"><?= date('d M, Y', strtotime($record['date_time'])) ?></td>
                                                <td><?= $record['status'] ?></td>
                                                <td style="white-space:nowrap;"><?= date('d M, Y', strtotime($record['from_date'])) ?></td>
                                                <td style="white-space:nowrap;"><?= date('d M, Y', strtotime($record['to_date'])) ?></td>
                                                <td><?= $record['number_of_days'] ?? '-' ?></td>
                                            </tr>
                                            <?php $total_el += $record['number_of_days']; ?>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td style="white-space:nowrap;" colspan='4'> Total EL Availed</td>
                                            <td><?= $total_el ?? '-' ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" style="text-align:center; padding:10px;">No EL records found for this year</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>



            </div>
        </div>
    <?php
    }
    ?>
</body>

</html>