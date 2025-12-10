<!-- Appraisal Sheet (HTML Table) -->
<style>
    .appraisal {
        font-family: Arial, Helvetica, sans-serif;
        /* max-width: 1280px; */
    }

    .appraisal table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
    }

    .appraisal th,
    .appraisal td {
        border: 1px solid #999;
        padding: 6px 8px;
        text-align: center;
    }

    .appraisal thead th {
        background: #efefef;
        text-align: center;
    }

    .label {
        background: #f7f7f7;
        font-weight: 600;
        width: 240px;
    }

    .section {
        background: #e6e6e6;
        font-weight: 700;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    table {
        /* table-layout: fixed; */
        /* width: 100%; */
        /* max-width: 100%; */
        /* border-collapse: collapse; */
        /* font-family: Arial, sans-serif; */
        /* margin: 0px; */
    }

    .appraisal table tr:last-child td {
        border-bottom: 1px solid #999 !important;
    }
</style>

<div class="appraisal" style="background-color: #f9f9f9; ">
    <!-- Employee / Header Info -->
    <table>
        <tbody>
            <tr>
                <th class="label" colspan="4">
                    <img src="<?= $arrAppraisalData[$employee_id]['logo_url'] ?>" alt="logo" style="width:100%; max-width:200px; object-fit: contain;">
                </th>
            </tr>
            <tr>
                <th class="label">Employee Name</th>
                <td>
                    <?php echo $arrAppraisalData[$employee_id]['employee_name']; ?>
                </td>
                <th class="label">Notice Period</th>
                <td>
                    <?php echo $arrAppraisalData[$employee_id]['notice_period']; ?>
                </td>
            </tr>
            <tr>
                <th class="label">EMP Code</th>
                <td>
                    <?php echo $arrAppraisalData[$employee_id]['employee_id']; ?>
                </td>
                <th class="label">PDC for NP</th>
                <td>
                    <?= esc(ucfirst($arrAppraisalData[$employee_id]['pdc_for_np'])) ?>
                </td>
            </tr>
            <tr>
                <th class="label">Date of Joining</th>
                <td>
                    <?php echo $arrAppraisalData[$employee_id]['joining_date']; ?>
                </td>
                <th class="label">Probation</th>
                <td>
                    <?php echo ucfirst($arrAppraisalData[$employee_id]['probation']); ?>
                </td>
            </tr>
            <tr>
                <th class="label">Employment Tenure</th>
                <td>
                    <?= esc($arrAppraisalData[$employee_id]['tenure']) ?>
                </td>
                <th class="label">Gratuity Eligibility</th>
                <td>
                    <?= esc(ucfirst($arrAppraisalData[$employee_id]['gratuity_eligible'])) ?>
                </td>
            </tr>
            <tr>
                <th class="label">Department</th>
                <td>
                    <?php echo ucfirst($arrAppraisalData[$employee_id]['department_name']); ?>
                </td>
                <th class="label">Bonus</th>
                <td>
                    <?php echo ucfirst($arrAppraisalData[$employee_id]['enable_bonus']); ?>
                </td>
            </tr>
            <tr>
                <th class="label">Designation</th>
                <td>
                    <?php echo ucfirst($arrAppraisalData[$employee_id]['designation']); ?>
                </td>
                <th class="label">DOB</th>
                <td>
                    <?= esc($arrAppraisalData[$employee_id]['date_of_birth']) ?>
                </td>
            </tr>
            <tr>
                <th class="label">Total relevant experience</th>
                <td>
                    <?= esc($arrAppraisalData[$employee_id]['relevant_experience']) ?>
                </td>
                <th class="label">Age</th>
                <td>
                    <?= esc($arrAppraisalData[$employee_id]['age']) ?>
                </td>
            </tr>
        </tbody>
    </table>

    <br />

    <!-- Appraisal Details -->
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th rowspan="2"> </th>
                <th colspan="3">Totals</th>
                <th colspan="2"> </th>
                <th colspan="8">Salary Bifurcation</th>
                <th colspan="3">Deduction (Employee Side)</th>
                <th rowspan="2">Net Salary (In Hand)</th>
                <th colspan="3">Contribution (Employer Side)</th>
                <th colspan="4">Other Benefits</th>
                <th rowspan="2" style="max-width: 150px;">Remarks</th>
            </tr>
            <tr>
                <th>wef</th>
                <th><strong>CTC</strong></th>
                <th><strong>Gross Salary</strong></th>
                <th>ESI</th>
                <th>PF</th>
                <th>Basic</th>
                <th>HRA</th>
                <th>Conv</th>
                <th>Med Alw</th>
                <th>Spl Alw</th>
                <th>Fuel Alw</th>
                <th>Vac Alw</th>
                <th>Oth Alw</th>
                <th>EPF</th>
                <th>ESI</th>
                <th>LWF</th>
                <th>EPF</th>
                <th>ESI</th>
                <th>LWF</th>
                <th>LI</th>
                <th>NCL</th>
                <th>Bond</th>
                <th>Others</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($arrAppraisalData[$employee_id]['appraisals'] as $appraisal) : ?>
                <tr>
                    <td <?php echo ($i == 0) ? 'style="border-top-style: hidden;"' : ''; ?>><?php echo ($i == 0) ? 'Joining Salary' : $i . 'st Appraisal'; ?></td>
                    <td><?= esc($appraisal['appraisal_date']) ?></td>
                    <td><strong><?= esc($appraisal['ctc']) ?></strong></td>
                    <td><strong><?= esc($appraisal['gross_salary']) ?></strong></td>
                    <td><?= esc($appraisal['esi']) ?></td>
                    <td><?= esc($appraisal['pf']) ?></td>
                    <td><?= esc($appraisal['basic_salary']) ?></td>
                    <td><?= esc($appraisal['house_rent_allowance']) ?></td>
                    <td><?= esc($appraisal['conveyance']) ?></td>
                    <td><?= esc($appraisal['medical_allowance']) ?></td>
                    <td><?= esc($appraisal['special_allowance']) ?></td>
                    <td><?= esc($appraisal['fuel_allowance']) ?></td>
                    <td><?= esc($appraisal['vacation_allowance']) ?></td>
                    <td><?= esc($appraisal['other_allowance']) ?></td>
                    <td><?= esc($appraisal['pf_employee_contribution']) ?></td>
                    <td><?= esc($appraisal['esi_employee_contribution']) ?></td>
                    <td><?= esc($appraisal['lwf_employee_contribution']) ?></td>
                    <td><?= esc($appraisal['in_hand_salary']) ?></td>
                    <td><?= esc($appraisal['pf_employer_contribution']) ?></td>
                    <td><?= esc($appraisal['esi_employer_contribution']) ?></td>
                    <td><?= esc($appraisal['lwf_employer_contribution']) ?></td>
                    <td><?= esc($appraisal['loyalty_incentive_amount_per_month']) ?></td>
                    <td><?= esc($appraisal['non_compete_loan_amount_per_month']) ?></td>
                    <td><?= esc($appraisal['bonus']) ?></td>
                    <td><?= esc($appraisal['other_benefits']) ?></td>
                    <td><?= esc($appraisal['appraisal_remarks']) ?></td>
                </tr>
                <?php if ($i > 0) : ?>
                    <tr style="font-weight: bold;  background-color:#211da22b;">
                        <td colspan="2">Total</td>
                        <td><strong><?= esc($appraisal['ctc_total']) ?></strong></td>
                        <td><strong><?= esc($appraisal['gross_salary_total']) ?></strong></td>
                        <td></td>
                        <td></td>
                        <td><?= esc($appraisal['basic_salary_total']) ?></td>
                        <td><?= esc($appraisal['house_rent_allowance_total']) ?></td>
                        <td><?= esc($appraisal['conveyance_total']) ?></td>
                        <td><?= esc($appraisal['medical_allowance_total']) ?></td>
                        <td><?= esc($appraisal['special_allowance_total']) ?></td>
                        <td><?= esc($appraisal['fuel_allowance_total']) ?></td>
                        <td><?= esc($appraisal['vacation_allowance_total']) ?></td>
                        <td><?= esc($appraisal['other_allowance_total']) ?></td>
                        <td><?= esc($appraisal['pf_employee_contribution_total']) ?></td>
                        <td><?= esc($appraisal['esi_employee_contribution_total']) ?></td>
                        <td><?= esc($appraisal['lwf_employee_contribution_total']) ?></td>
                        <td><?= esc($appraisal['in_hand_salary_total']) ?></td>
                        <td><?= esc($appraisal['pf_employer_contribution_total']) ?></td>
                        <td><?= esc($appraisal['esi_employer_contribution_total']) ?></td>
                        <td><?= esc($appraisal['lwf_employer_contribution_total']) ?></td>
                        <td><?= esc($appraisal['loyalty_incentive_total']) ?></td>
                        <td><?= esc($appraisal['non_compete_loan_amount_per_month_total']) ?></td>
                        <td><?= esc($appraisal['bonus_total']) ?></td>
                        <td><?= esc($appraisal['other_benefits_total']) ?></td>
                        <td></td>
                    </tr>
                <?php endif; ?>
            <?php
                $i++;
            endforeach; ?>
        </tbody>
    </table>



</div>