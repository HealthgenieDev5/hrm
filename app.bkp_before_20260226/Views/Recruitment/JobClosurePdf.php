<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F-HR-22 Job Closure Form</title>
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }

        body {
            margin: 0 auto;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.2;
            max-width: 794px;
            color: #000;
        }

        .container {
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            table-layout: fixed;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: anywhere;
            text-align: left;
        }

        img.logo {
            max-width: 100px;
            max-height: 40px;
            display: block;
            margin: 0 auto;
            vertical-align: middle;
        }

        .header-table {
            page-break-inside: avoid;
        }

        .header-table th,
        .header-table td {
            text-align: center;
            font-weight: bold;
        }

        .header-table .label-cell {
            text-align: left;
            font-weight: bold;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            background-color: #e8e8e8;
            font-size: 13px;
            padding: 8px;
        }

        .field-label {
            font-weight: bold;
            width: 25%;
        }

        .field-input {
            min-height: 20px;
            /* border-bottom: 1px solid #ccc; */
        }

        .sig-cell {
            /*  height: 80px;
            text-align: center;*/
            font-weight: bold;
            vertical-align: bottom;
            padding-bottom: 10px;
        }

        .question-cell {
            font-weight: bold;
            padding: 10px 8px;
        }

        .answer-space {
            min-height: 40px;
            padding: 10px 8px;
        }

        .no-border {
            border: none;
        }

        @media print {
            .container {
                padding: 0;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td rowspan="4" style="width:20%; text-align:center; vertical-align:middle;">
                    <img src="<?= base_url('assets/media/company-logo/gstc_logo_1.png') ?>" alt="GSTC" class="logo">
                </td>
                <td rowspan="2" colspan="2" style="text-align:center; font-weight:bold;">FORMAT</td>
                <td style="font-weight:bold;">Doc. No.</td>
                <td>F/HR/22</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Effective Date</td>
                <td><?= date('d/m/Y') ?></td>
            </tr>
            <tr>
                <td rowspan="2" colspan="2" style="text-align:center; font-weight:bold;">JOB CLOSURE FORM</td>
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

        <!-- HR Closure Section -->
        <table>
            <thead>
                <tr>
                    <th colspan="4" class="section-title">Job Closure Form (Assessment by HR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="field-label">Name:</td>
                    <td colspan="3" class="field-input"><?= esc($closure->selected_candidate_name ?? '') ?></td>
                </tr>
                <tr>
                    <td class="field-label">Replacement of:</td>
                    <td colspan="3" class="field-input"><?= !empty($closure->replacement_employee_name) ? esc($closure->replacement_employee_name) : 'N/A' ?></td>
                </tr>
                <tr>
                    <td class="field-label">Recruited by:</td>
                    <td colspan="3" class="field-input"><?= esc($closure->hr_approver_name ?? '') ?></td>
                </tr>
                <tr>
                    <td class="field-label">Job Name:</td>
                    <td colspan="3" class="field-input"><?= esc($job->job_title ?? '') ?></td>
                </tr>
                <tr>
                    <td class="field-label">Date of Joining:</td>
                    <td colspan="3" class="field-input"><?= !empty($closure->selected_candidate_joining_date) ? date('d-M-Y', strtotime($closure->selected_candidate_joining_date)) : 'N/A' ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Reporting Manager Section -->
        <table>
            <thead>
                <tr>
                    <th colspan="4" class="section-title">Assessment by Reporting Manager</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="field-label">Strengths:</td>
                    <td colspan="3" class="field-input"><?= esc($closure->strengths ?? '') ?></td>
                </tr>
                <tr>
                    <td class="field-label">Weaknesses:</td>
                    <td colspan="3" class="field-input"><?= esc($closure->weaknesses ?? '') ?></td>
                </tr>
                <tr>
                    <td class="field-label">Keep Job Posting Open:</td>
                    <td colspan="3" class="field-input"><?= ucfirst($closure->keep_posting_open ?? 'N/A') ?></td>
                </tr>



            </tbody>
        </table>

        <!-- Team Performance Analysis -->
        <table>
            <thead>
                <tr>
                    <th colspan="4" class="section-title">Team Performance Analysis</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="question-cell">
                        How many people you have in <strong><?= esc($job->department_name ?? '________') ?></strong> & who is the best & who is the worst & do we need to find replacement of the worst?
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="answer-space">
                        We have <b><?= esc($closure->current_team_size ?? 'an unspecified number of') ?></b> people in our team.
                        <?php if (!empty($closure->best_performer_name)): ?>
                            The best performer is <b><?= esc($closure->best_performer_name) ?></b><?php if (!empty($closure->worst_performer_name)): ?> and the worst performer is <b><?= esc($closure->worst_performer_name) ?></b>.<?php else: ?> and there are no significant performance concerns with other team members<?php endif; ?>
                            <?php else: ?>
                                Performance evaluation details are not specified.
                            <?php endif; ?>

                            <?php if ($closure->need_replacement == 'yes'): ?>
                                We need to find a replacement for the underperforming member
                            <?php else: ?>
                                We do not need to find any replacement at this time.
                            <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="question-cell">
                        Will all your team members serve 3 months of working Notice Period? If any doubtful please mention their name.
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="answer-space">
                        <?php
                        $complianceText = '';
                        switch ($closure->notice_period_compliance ?? '') {
                            case 'no':
                                $complianceText = 'Yes, all team members will serve the 3-month notice period.';
                                break;
                            case 'yes':
                                $complianceText = 'Some team members are doubtful about serving the full 3-month notice period.';
                                if (!empty($closure->doubtful_notice_members)) {
                                    $complianceText .= "\n" . ' Doubtful members: ' . esc($closure->doubtful_notice_members);
                                }
                                break;
                            default:
                                $complianceText = 'Notice period compliance status not specified.';
                        }
                        echo $complianceText;
                        ?>
                    </td>
                </tr>
                <!-- <?php if (!empty($closure->manager_comments)): ?>
                    <tr>
                        <td colspan="4" class="question-cell">Additional Manager Comments:</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="answer-space"><?= esc($closure->manager_comments) ?></td>
                    </tr>
                <?php endif; ?> -->
            </tbody>
        </table>

        <!-- Approval Signatures -->
        <table>
            <thead>
                <tr>
                    <th colspan="6" class="section-title">Approvals & Signatures</th>
                </tr>
            </thead>
            <tbody>
                <!-- <tr>
                    <td class="field-label">Manager Approved By:</td>
                    <td class="field-input"><?= esc($closure->manager_approver_name ?? '') ?></td>
                    <td class="field-label">Manager Approval Date:</td>
                    <td class="field-input"><?= !empty($closure->manager_approved_at) ? date('d-M-Y H:i', strtotime($closure->manager_approved_at)) : 'N/A' ?></td>
                </tr>
                <tr>
                    <td class="field-label">Final Status:</td>
                    <td class="field-input"><?= ucfirst($job->status ?? '') ?></td>
                    <td class="field-label">Closure Completed:</td>
                    <td class="field-input"><?= !empty($closure->final_closure_date) ? date('d-M-Y H:i', strtotime($closure->final_closure_date)) : 'N/A' ?></td>
                </tr> -->
                <tr>
                    <td colspan="2" class="sig-cell">
                        <div>Reporting Manager</div>
                        <div style="margin-top: 5px; font-size: 8px;"><?= esc($job->reporting_to_name ?? '') ?></div>
                        <br><br><br><strong>Date:</strong>
                    </td>
                    <td colspan="2" class="sig-cell">
                        <div>HR Executive</div>
                        <div style="margin-top: 5px; font-size: 8px;"><?= esc($closure->hr_approver_name ?? '') ?></div>
                        <br><br><br><strong>Date:</strong>
                    </td>
                    <td colspan="2" class="sig-cell">
                        <div>Plant Head/HOD</div>
                        <div style="margin-top: 5px; font-size: 8px;"><?= esc($closure->manager_approver_name ?? '') ?></div>
                        <br><br><br><strong>Date:</strong>

                        <!-- <strong>HR Head Sign.</strong>
                        <?php if (!empty($job->hr_manager_approver_name)): ?>
                            <div class="sig-watermark">Approved</div>
                            <br>
                            <span style="font-size: 10px; text-transform: uppercase;"><?= esc($job->hr_manager_approver_name) ?></span><br><br><br>
                            <strong>Date:</strong>
                        <?php else: ?>
                            <br><br><br><br><strong>Date:</strong>
                        <?php endif; ?> -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>