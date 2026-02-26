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
            font-size: 6px;
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
            padding: 8px 8px;
            vertical-align: middle;
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
            height: 50px;
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
    </style>
</head>

<body>
    <!-- Page number footer (appears on all pages) -->
    <div class="page-number"></div>

    <!-- <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("DejaVu Sans", "normal");
                $size = 9;
                $pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
                $width = $fontMetrics->get_text_width($pageText, $font, $size);
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 20;
                $pdf->text($x, $y, $pageText, $font, $size);
            ');
        }
    </script> -->
    <?php
    foreach ($companies as $company) {
    ?>
        <div class="employee-page">
            <!-- Header for this page -->
            <div class="page-header">
                <table style="width:100%; margin-bottom:10px; border:none;">
                    <tr>
                        <td colspan="3" style="text-align:center; border:none;">
                            <p style="margin-bottom:8px;">Wages Register – Form X (Rule 26(1))</p>
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
                <!-- <table class="footer-table">
                    <tr>
                        <td style="width:70%;">

                        </td>
                        <td style="width: 30%;">
                            <strong>Employee Signature</strong>
                            <p style="height:50px;">&nbsp;</p>
                        </td>
                    </tr>
                </table> -->
            </div>
            <!-- Main Content -->
            <div class="content">
                <table>
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th style="white-space:nowrap;">Employee Name</th>
                            <th style="white-space:nowrap;">Designation</th>
                            <th style="white-space:nowrap;">D.O.J.</th>
                            <th style="white-space:nowrap;">Wage Period</th>
                            <th>Paid Days</th>
                            <th>Basic</th>
                            <th>HRA</th>
                            <th>Medical Allow.</th>
                            <th>Conv. Allow.</th>
                            <th>Special Allow.</th>
                            <th>Fuel Allow.</th>
                            <th>Vacation Allow.</th>
                            <th>Other Allow.</th>
                            <th>PF</th>
                            <th>ESI</th>
                            <th>LWF</th>
                            <th>TDS</th>
                            <th>Advance</th>
                            <th>Imprest</th>
                            <th>Loan</th>
                            <th>Net Wages Paid</th>
                            <th>Date of Payment</th>
                            <th>Bank A/C</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $employees = $company['employees'];
                        $currentIndex = 0;

                        // Initialize totals
                        $totalPaidDays = 0;
                        $totalBasic = 0;
                        $totalHRA = 0;
                        $totalMedical = 0;
                        $totalConveyance = 0;
                        $totalSpecial = 0;
                        $totalFuel = 0;
                        $totalVacation = 0;
                        $totalOther = 0;
                        $totalPF = 0;
                        $totalESI = 0;
                        $totalLWF = 0;
                        $totalTDS = 0;
                        $totalAdvance = 0;
                        $totalImprest = 0;
                        $totalLoan = 0;
                        $totalNetSalary = 0;

                        foreach ($employees as $employee) {
                            $currentIndex++;
                            $record = $employee['record'];

                            // Accumulate totals
                            $totalPaidDays += $record['final_paid_days'];
                            $totalBasic += $record['basic_salary'];
                            $totalHRA += $record['house_rent_allowance'];
                            $totalMedical += $record['medical_allowance'];
                            $totalConveyance += $record['conveyance'];
                            $totalSpecial += $record['special_allowance'];
                            $totalFuel += $record['fuel_allowance'];
                            $totalVacation += $record['vacation_allowance'];
                            $totalOther += $record['other_allowance'];
                            $totalPF += $record['pf_employee_contribution'];
                            $totalESI += $record['esi_employee_contribution'];
                            $totalLWF += $record['lwf_employee_contribution'];
                            $totalTDS += $record['tds'];
                            $totalAdvance += $record['advance'];
                            $totalImprest += $record['imprest'];
                            $totalLoan += $record['loan_emi'];
                            $totalNetSalary += $record['net_salary'];
                        ?>
                            <tr>
                                <td><?= $currentIndex ?></td>
                                <td style="white-space:nowrap;"><?= $employee['employee_name'] ?></td>
                                <td style="white-space:nowrap;"><?= $employee['designation_name'] ?></td>
                                <td style="white-space:nowrap;"><?= date('d M, Y', strtotime($employee['joining_date'])) ?></td>
                                <td style="white-space:nowrap;"><?= date('M, Y', strtotime($record['year'] . '-' . $record['month'] . '-01')) ?></td>
                                <td><?= $record['final_paid_days'] ?></td>
                                <td><?= round($record['basic_salary']) ?></td>
                                <td><?= round($record['house_rent_allowance']) ?></td>
                                <td><?= round($record['medical_allowance']) ?></td>
                                <td><?= round($record['conveyance']) ?></td>
                                <td><?= round($record['special_allowance']) ?></td>
                                <td><?= round($record['fuel_allowance']) ?></td>
                                <td><?= round($record['vacation_allowance']) ?></td>
                                <td><?= round($record['other_allowance']) ?></td>
                                <td><?= round($record['pf_employee_contribution']) ?></td>
                                <td><?= round($record['esi_employee_contribution']) ?></td>
                                <td><?= round($record['lwf_employee_contribution']) ?></td>
                                <td><?= round($record['tds']) ?></td>
                                <td><?= round($record['advance']) ?></td>
                                <td><?= round($record['imprest']) ?></td>
                                <td><?= round($record['loan_emi']) ?></td>
                                <td><?= round($record['net_salary']) ?></td>
                                <td><?= $disbursal_date ?></td>
                                <td><?php
                                    $bankAcc = $record['bank_account_number'] ?? '';
                                    if (!empty($bankAcc)) {
                                        $lastSix = substr($bankAcc, -6);
                                        // $maskLength = max(0, strlen($bankAcc) - 6);
                                        $maskLength = 10;
                                        echo str_repeat('x', $maskLength) . $lastSix;
                                    } else {
                                        echo 'BY CHEQUE';
                                    }
                                    ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <!-- Totals Row -->
                        <tr style="font-weight: bold; background-color: #e9ecef;">
                            <td colspan="5" class="text-right">Total</td>
                            <td><?= round($totalPaidDays, 2) ?></td>
                            <td><?= round($totalBasic) ?></td>
                            <td><?= round($totalHRA) ?></td>
                            <td><?= round($totalMedical) ?></td>
                            <td><?= round($totalConveyance) ?></td>
                            <td><?= round($totalSpecial) ?></td>
                            <td><?= round($totalFuel) ?></td>
                            <td><?= round($totalVacation) ?></td>
                            <td><?= round($totalOther) ?></td>
                            <td><?= round($totalPF) ?></td>
                            <td><?= round($totalESI) ?></td>
                            <td><?= round($totalLWF) ?></td>
                            <td><?= round($totalTDS) ?></td>
                            <td><?= round($totalAdvance) ?></td>
                            <td><?= round($totalImprest) ?></td>
                            <td><?= round($totalLoan) ?></td>
                            <td><?= round($totalNetSalary) ?></td>
                            <td colspan="2"></td>
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