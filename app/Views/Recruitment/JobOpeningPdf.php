<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>F-HR-39-01 Job Opening Form</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        @page {
            size: A4;
            margin: 8mm;
            /* was bigger; reduce margins */
        }

        body {
            margin: 0 auto;
            font-family: 'DejaVu Sans', sans-serif;
            /* keeps ₹ visible */
            font-size: 12px;
            /* was 14px */
            line-height: 1.2;
            /* tighter lines */
            max-width: 794px;
            /* A4 width @ 96dpi */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            table-layout: fixed;
            /* prevents layout reflow */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            /* was 6px 10px */
            vertical-align: top;
            word-wrap: break-word;
            /* tighter wrapping */
            overflow-wrap: anywhere;
        }

        img.logo {
            max-width: 100px;
            max-height: 40px;
            display: block;
            margin: 0 auto;
            vertical-align: middle;
        }

        /* Avoid page break inside header tables */
        .header-table {
            page-break-inside: avoid;
        }

        /* Optional: Signature box height a bit lower */
        .sig-cell {
            height: 70px;
        }

        .sig-cell {
            position: relative;
            overflow: hidden;
        }

        .sig-watermark {
            position: absolute;
            top: 35px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 24px;
            color: rgba(0, 128, 0, 0.2);
            font-weight: bold;
            transform: rotate(-25deg);
            z-index: -1;
            letter-spacing: 2px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 30px;
            color: rgba(0, 128, 0, 0.15);
            font-weight: bold;
            z-index: -1000;
            text-align: center;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 10px;
        }

        /* was 85px */
    </style>
</head>

<body>
    <!-- <?php
            $isFullyApproved = !empty($job->approved_by_hr_executive) && !empty($job->approved_by_hod) && !empty($job->approved_by_hr_manager);
            if ($isFullyApproved): ?>
        <div class="watermark">Authorization granted</div>
    <?php endif; ?> -->
    <?php
    $jobOpeningDate = !empty($job->job_opening_date) && $job->job_opening_date !== '0000-00-00'
        ? date('d M, Y', strtotime($job->job_opening_date))
        : '';
    ?>
    <div class="container">

        <!-- Header -->
        <table>
            <tr>
                <td rowspan="4" style="width:20%; text-align:center; vertical-align:middle;">
                    <img src="<?= base_url('assets/media/company-logo/gstc_logo_1.png') ?>" alt="GSTC" class="logo">
                </td>

                <td rowspan="2" colspan="2" style="text-align:center; font-weight:bold;">FORMAT</td>
                <td style="font-weight:bold;">Doc. No.</td>
                <td>F/HR/39-01</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Effective Date</td>
                <td>01/07/2025</td>
            </tr>
            <tr>
                <td rowspan="2" colspan="2" style="text-align:center; font-weight:bold;">JOB OPENING FORM</td>
                <td style="font-weight:bold;">Rev. No.& Date</td>
                <td>01 & NIL</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Page</td>
                <td>1 of 1</td>
            </tr>
        </table>

        <!-- Spacing -->
        <div style="margin-top: 30px;"></div>

        <!-- Main Form Table -->
        <table>
            <tr>
                <td><strong>Job Name:</strong></td>
                <td colspan="3"><?= esc($job->job_title) ?></td>
            </tr>
            <tr>
                <td><strong>Date of Job Opening:</strong></td>
                <td colspan="3"><?= esc($jobOpeningDate) ?></td>
            </tr>
            <tr>
                <td><strong>Type of Job:</strong></td>
                <td colspan="3"><?= esc($job->type_of_job ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td><strong>Salary:</strong></td>
                <td colspan="3"> ₹<?= esc(number_format($job->min_budget)) ?> - ₹<?= esc(number_format($job->max_budget)) ?></td>
            </tr>
            <tr>
                <td><strong>Experience:</strong></td>
                <td colspan="3"><?= esc(number_format($job->min_experience)) ?> to <?= esc(number_format($job->max_experience)) ?> Years</td>
            </tr>
            <tr>
                <td><strong>Interview Location:</strong></td>
                <td colspan="3"><?= esc($job->interview_location) ?></td>
            </tr>
            <tr>
                <td><strong>Seating Location:</strong></td>
                <td colspan="3"><?= esc($job->seating_location) ?></td>
            </tr>
            <tr>
                <td><strong>Shift Timing:</strong></td>
                <td colspan="3"><?= esc($job->shift_timing) ?></td>
            </tr>
            <tr>
                <td><strong>System Required:</strong></td>
                <td colspan="3"><?= esc($job->system_required) ?></td>
            </tr>
            <tr>
                <td><strong>Reporting to:</strong></td>
                <td colspan="3"> <?= esc($job->reporting_to_name ?? 'N/A') ?>
                    <?php if (!empty($job->reporting_to_designation)): ?>
                        (<?= esc($job->reporting_to_designation) ?>)
                    <?php endif; ?></td>
            </tr>
            <tr>
                <td><strong>Salient Points:</strong></td>
                <td colspan="3"><?= esc($job->salient_points) ?></td>
            </tr>
            <tr>
                <td><strong>Educational Qualification:</strong></td>
                <td colspan="3"><?= esc($job->educational_qualification) ?></td>
            </tr>
            <tr>
                <td><strong>Technical Test Required:</strong></td>
                <td colspan="3"><?php
                                $technicalTest = json_decode($job->technical_test_required, true);
                                if (is_array($technicalTest) && isset($technicalTest['required'])) {
                                    echo esc($technicalTest['required']);
                                    if ($technicalTest['required'] === 'Yes' && isset($technicalTest['tests']) && is_array($technicalTest['tests'])) {
                                        echo ' - ' . esc(implode(' | ', $technicalTest['tests']));
                                    }
                                }
                                ?>
                </td>
            </tr>
            <tr>
                <td><strong>Job Description / Requirement</strong></td>
                <td colspan="3">
                    <!-- <textarea rows="6" placeholder="1.&#10;2.&#10;3.&#10;4.&#10;5.&#10;6."></textarea> -->
                    <?php echo nl2br(strip_tags($job->job_description)); ?>
                </td>

            </tr>
            <tr>
                <td><strong>IQ Test Required:</strong></td>

                <td colspan="3">
                    <?php
                    $iqTest = json_decode($job->iq_test_required, true);
                    echo $iqTest['required'];
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Eng Test Required:</strong></td>
                <td colspan="3">
                    <?php
                    $engTest = json_decode($job->eng_test_required, true);
                    echo $engTest['required'];
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Operation Test Required:</strong></td>
                <td colspan="3">
                    <?php
                    $operationTest = json_decode($job->operation_test_required, true);
                    echo $operationTest['required'];
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Any Other Test Required:</strong></td>
                <td colspan="3">
                    <?php
                    $otherTest = json_decode($job->other_test_required, true);
                    if (is_array($otherTest) && isset($otherTest['required'])) {
                        echo esc($otherTest['required']);
                        if ($otherTest['required'] === 'Yes' && isset($otherTest['tests']) && is_array($otherTest['tests'])) {
                            // Handle both old (string array) and new (object array) formats
                            $testNames = [];
                            foreach ($otherTest['tests'] as $test) {
                                if (is_array($test)) {
                                    // New format: {"name": "test", "file": "..."}
                                    $testNames[] = $test['name'] ?? '';
                                } else {
                                    // Old format: "test"
                                    $testNames[] = $test;
                                }
                            }
                            echo ' - ' . esc(implode(' | ', array_filter($testNames)));
                        }
                    }

                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>No. of Vacancy:</strong></td>
                <td colspan="3"><?= esc($job->no_of_vacancy ?? '0') ?></td>
            </tr>
            <tr>
                <td><strong>Any Specific Industry:</strong></td>
                <td colspan="3"><?= esc($job->specific_industry) ?></td>
            </tr>
            <tr>
                <td colspan="4">If type of job is new then attach distribution of KRA’s. (Review Frequency)</td>
            </tr>

        </table>

        <table style="width:100%; border:1px solid #000; border-collapse:collapse; font-family:Arial, sans-serif; font-size:14px; margin-top:30px;">
            <tr>
                <td class="sig-cell" style="border:1px solid #000; text-align:left; vertical-align:top; padding:8px;">
                    <strong>Requisition Sign.</strong>
                    <?php if (!empty($job->created_by_name)): ?>
                        <div class="sig-watermark">Created</div>
                        <br>
                        <span style="font-size: 10px; text-transform: uppercase;"> <?= esc($job->created_by_name) ?></span><br><br><br>
                        <strong>Date:</strong> <?= esc(date('d M, Y', strtotime($job->created_at))) ?>
                    <?php else: ?>
                        <br><br><br><br><strong>Date:</strong>
                    <?php endif; ?>
                </td>
                <td class="sig-cell" style="border:1px solid #000; text-align:left; vertical-align:top; padding:8px;">
                    <strong>HR Executive Sign</strong>
                    <?php if (!empty($job->hr_executive_approver_name)): ?>
                        <div class="sig-watermark">Approved</div>
                        <br>
                        <span style="font-size: 10px; text-transform: uppercase;"><?= esc($job->hr_executive_approver_name) ?></span><br><br><br>
                        <strong>Date:</strong>
                    <?php else: ?>
                        <br><br><br><br><strong>Date:</strong>
                    <?php endif; ?>
                </td>
                <td class="sig-cell" style="border:1px solid #000; text-align:left; vertical-align:top; padding:8px;">
                    <strong>HOD Sign.</strong>
                    <?php if (!empty($job->hod_approver_name)): ?>
                        <div class="sig-watermark">Approved</div>
                        <br>
                        <span style="font-size: 10px; text-transform: uppercase;"> <?= esc($job->hod_approver_name) ?></span><br><br><br>
                        <strong>Date:</strong>
                    <?php else: ?>
                        <br><br><br><br><strong>Date:</strong>
                    <?php endif; ?>
                </td>
                <td class="sig-cell" style="border:1px solid #000; text-align:left; vertical-align:top; padding:8px;">
                    <strong>HR Head Sign.</strong>
                    <?php if (!empty($job->hr_manager_approver_name)): ?>
                        <div class="sig-watermark">Approved</div>
                        <br>
                        <span style="font-size: 10px; text-transform: uppercase;"><?= esc($job->hr_manager_approver_name) ?></span><br><br><br>
                        <strong>Date:</strong>
                    <?php else: ?>
                        <br><br><br><br><strong>Date:</strong>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

    </div>

</body>

</html>