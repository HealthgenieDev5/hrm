<html>

<head>
	<meta http-equiv="Content-Type" content="charset=utf-8" />
	<style type="text/css">
		@page {
			margin: 0;
		}

		* {
			padding: 0;
			margin: 0;
		}

		@font-face {
			font-family: "source_sans_proregular";
			src: local("Source Sans Pro"), url("fonts/sourcesans/sourcesanspro-regular-webfont.ttf") format("truetype");
			font-weight: normal;
			font-style: normal;

		}

		body {
			font-family: "source_sans_proregular", Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			font-size: 12px;
		}
	</style>
</head>

<body>

	<div style="background: #fff; margin:0px auto; padding: 50px 25px;">
		<table style="width: 100%;">
			<tr>
				<td style="vertical-align: middle; width:15%">
					<?php
					if (!empty(json_decode(@$FinalSalary['employee_data'])?->company_logo_url)) {
					?>
						<img src="<?php echo base_url(json_decode(@$FinalSalary['employee_data'])?->company_logo_url); ?>" style="width:100px;" />
					<?php
					}
					?>
				</td>
				<td style="padding: 10px 10px; text-align: center; vertical-align: bottom;">
					<h3><?= json_decode(@$FinalSalary['employee_data'])?->company_name ?></h3>
					<p><?= json_decode(@$FinalSalary['employee_data'])?->company_address ?></p>
				</td>
				<td style="width:15%; vertical-align:bottom">
					Downloaded Date: <?php echo date('d M, Y'); ?>
				</td>
			</tr>
		</table>

		<hr style="border: none; border-bottom: 1px solid grey; margin: 20px 0px;">

		<table style="width: 100%; margin-bottom: 20px;">
			<tr>
				<th>
					<strong>Payslip for <?= @$salary_month ?>, <?= @$salary_year ?></strong>
				</th>
			</tr>
		</table>

		<table style="width: 100%; margin-bottom: 20px;">
			<tr>
				<td style="width: 50%; padding-right: 15px; vertical-align: top;">
					<table style="width: 100%; padding-right: 15px; border-collapse: collapse;">
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Name</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['employee_data'])?->employee_name ?></strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Employee ID</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['employee_data'])?->internal_employee_id ?></strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Joining Date</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?php echo !empty(json_decode(@$FinalSalary['employee_data'])?->joining_date) ? date('d M, Y', strtotime(json_decode(@$FinalSalary['employee_data'])?->joining_date)) : ''; ?></strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Designation</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['employee_data'])?->designation_name ?></strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Department</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['employee_data'])?->department_name ?></strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; padding: 5px;">
								<strong>UAN Number</strong>
							</th>
							<td style="text-align: right; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;">
									<?= json_decode(@$FinalSalary['salary_structure'])?->pf_number ?? 'N/A'; ?>
								</strong>
							</td>
						</tr>
					</table>
				</td>
				<td style="width: 50%;  padding-left: 15px; vertical-align: top;">
					<?php
					// $attachment = json_decode($FinalSalary['employee_data'])?->attachment;
					// print_r(json_decode(json_decode($FinalSalary['employee_data'])?->attachment)?->bank_account?->number);
					?>
					<table style="width: 100%; padding-left: 15px; border-collapse: collapse;">
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Bank Name</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(json_decode($FinalSalary['employee_data'])?->attachment)?->bank_account?->name ?></strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Bank Account Number</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;">
									<?php
									$bank_account_number = json_decode(json_decode($FinalSalary['employee_data'])?->attachment)?->bank_account?->number;
									echo $bank_account_number;
									// if( !empty($bank_account_number) ){
									//     $replaceCount = max(0, 16 - strlen($bank_account_number));
									//     $maskedString = str_repeat('x', $replaceCount) . substr($bank_account_number, -min(6, strlen($bank_account_number)));
									//     $maskedString = str_pad($maskedString, 16, 'x', STR_PAD_LEFT);
									//     echo $maskedString;
									// }
									?>
								</strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Adhar Number</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(json_decode($FinalSalary['employee_data'])?->attachment)?->adhar?->number ?></strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Pan Number</strong>
							</th>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(json_decode($FinalSalary['employee_data'])?->attachment)?->pan?->number ?></strong>
							</td>
						</tr>
						<tr>
							<th style="text-align: left; padding: 5px;">
								<strong>ESI Number</strong>
							</th>
							<td style="text-align: right; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;">
									<?= json_decode(@$FinalSalary['salary_structure'])?->esi_number ?? 'N/A'; ?>
								</strong>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<hr style="border: none; border-bottom: 1px solid grey; margin: 20px 0px;">

		<table style="width: 100%; margin-bottom: 20px;">
			<tr>
				<th style="text-align: right;">
					<p>
						<strong style="margin-right: 30px">Attendance</strong>
						<span style="padding:3px 15px; padding-right: 0px; border: 1px dashed grey; border-radius: 8px;">
							<span>
								Final Paid Days:
							</span>
							<span style="color: #009ef7; opacity: 0.75; padding-right: 15px;">
								<?= @$FinalSalary['final_paid_days'] ?>/<?= @$FinalSalary['month_days'] ?>
							</span>
						</span>
					</p>
				</th>
			</tr>
		</table>

		<hr style="border: none; border-bottom: 1px solid grey; margin: 20px 0px;">

		<table style="width: 100%; margin-bottom: 20px;">
			<tr>
				<td style="width: 60%; padding-right: 15px; vertical-align: top;">
					<table style="width: 100%; padding-right: 15px; border-collapse: collapse; border: 1px solid grey">
						<tr>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<span>Particulars</span>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<span>Structure Monthly</span>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<span>Earned Amount Monthly</span>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Basic Salary</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['salary_structure'])?->basic_salary ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['basic_salary'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>HRA</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['salary_structure'])?->house_rent_allowance ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['house_rent_allowance'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Conveyance</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['salary_structure'])?->conveyance ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['conveyance'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Medical Allowance</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['salary_structure'])?->medical_allowance ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['medical_allowance'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Special Allowance</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['salary_structure'])?->special_allowance ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['special_allowance'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Fuel Allowance</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['salary_structure'])?->fuel_allowance ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['fuel_allowance'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Vacation Allowance</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode($FinalSalary['salary_structure'])?->vacation_allowance ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['vacation_allowance'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Other Allowance</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['salary_structure'])?->other_allowance ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['other_allowance'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; padding: 5px;">
								<strong>Total Gross</strong>
							</td>
							<td style="text-align: center; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= json_decode(@$FinalSalary['salary_structure'])?->gross_salary ?? 0 ?></strong>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['gross_salary'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-left: 1px solid grey; border-right: 1px solid grey; border-bottom: 1px solid grey; padding: 5px;" colspan="3">
								<strong><?= AmountInWords(round(@$FinalSalary['net_salary'])) ?> Only /-</strong>
							</td>
						</tr>
					</table>
				</td>
				<td style="width: 40%; padding-left: 15px; vertical-align: top;">
					<table style="width: 100%; border-collapse: collapse; border: 1px solid grey;">
						<tr>
							<td style="text-align: center; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<span>Deductions</span>
							</td>
							<td style="text-align: center; border-bottom: 1px solid grey; padding: 5px;">
								<span>Amount</span>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>PF</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['pf_employee_contribution'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>ESI</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['esi_employee_contribution'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>LWF</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['lwf_employee_contribution'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>Loan</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['loan_emi'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>Advance</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['advance'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>Imprest</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['imprest'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>TDS</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['tds'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>Phone</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['phone_bill'], 2) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>Total Deductions</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round((@$FinalSalary['gross_salary'] - @$FinalSalary['net_salary'])) ?></strong>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; border-bottom: 1px solid grey; border-right: 1px solid grey; padding: 5px;">
								<strong>Net Amount in hand</strong>
							</td>
							<td style="text-align: right; border-bottom: 1px solid grey; padding: 5px;">
								<strong style="color: #009ef7; opacity: 0.75;"><?= round(@$FinalSalary['net_salary']) ?></strong>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table style="width: 100%; margin-bottom: 20px;">
			<tr>
				<td style="font-size:0.6rem">
					*This payslip is computer-generated and does not require a signature.
				</td>
			</tr>
		</table>

	</div>

</body>

</html>