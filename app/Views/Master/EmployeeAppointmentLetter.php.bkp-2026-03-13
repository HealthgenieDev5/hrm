<html>

<head>
	<meta http-equiv="Content-Type" content="charset=utf-8" />
	<style type="text/css">
		html {
			/* font-size: 12px; */
			/* font-size: 16px; */
			font-size: 15px;
			padding: 0;
			margin: 0;
		}

		table {
			font-size: inherit;
			border: 0;
			border-collapse: collapse;
		}

		table tr {
			border: 0;
		}

		table td,
		table th {
			font-size: inherit;
			border: 0;
		}

		table.bordered tr td {
			padding: 0.25rem 0.5rem;
			border: 1px solid grey;
		}

		table.annexure tr td {
			padding: 0.10rem 0.5rem;
			/* padding: 0.25rem 0.5rem; */
			border: 1px solid grey;
		}

		@font-face {
			font-family: "source_sans_proregular";
			src: local("Source Sans Pro"), url("fonts/sourcesans/sourcesanspro-regular-webfont.ttf") format("truetype");
			font-weight: normal;
			font-style: normal;
		}

		@page {
			/* margin: 5cm 0cm 5.5cm 0cm; */
		}

		body {
			padding-top: 4.25cm;
			padding-left: 2cm;
			padding-right: 2cm;
			padding-bottom: 4cm;
		}

		p,
		h1,
		h2,
		h3,
		h4,
		h5,
		h6 {
			margin: 0;
			line-height: 1.25;
		}

		header {
			position: fixed;
			top: 0cm;
			left: 0cm;
			right: 0cm;
			height: 2cm;
		}

		.header-top {
			height: 2cm;
			color: white;
			text-align: center;
		}

		footer {
			position: fixed;
			bottom: 0cm;
			left: 0cm;
			right: 0cm;
		}

		.text-white {
			color: white;
		}

		.text-center {
			text-align: center;
		}

		.text-justify {
			text-align: justify;
		}

		.d-flex {
			display: flex;
		}

		.align-items-center {
			align-items: center;
		}

		.justify-content-center {
			justify-content: center;
		}

		.justify-content-between {
			justify-content: space-between;
		}

		.justify-content-end {
			justify-content: end;
		}

		ol,
		ul {
			margin-top: 0.5rem;
		}

		ol>li {
			list-style: none;
		}

		ol>li,
		ul>li {
			margin-bottom: 0.35rem;
			position: relative;
			text-align: justify;
		}

		ol.extended-counter {
			counter-reset: section;
			/* padding-left: 30px; */
			padding-left: 2.35rem;
			list-style-type: none;
		}

		ol.extended-counter>li {
			counter-increment: section;
		}

		ol.extended-counter>li::before {
			content: "1." counter(section) ". ";
			font-weight: bold;
			position: absolute;
			/* left: -30px; */
			left: -2.35rem;
		}

		ol.extended-counter>li>ol.extended-counter {
			counter-reset: subsection;
			/* padding-left: 40px; */
			padding-left: 3.5rem;
			list-style-type: none;
		}

		ol.extended-counter>li>ol.extended-counter>li {
			counter-increment: subsection;
		}

		ol.extended-counter>li>ol.extended-counter>li::before {
			content: "1." counter(section) "." counter(subsection) ". ";
			font-weight: bold;
			position: absolute;
			/* left: -40px; */
			left: -3.5rem;
		}

		ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter {
			counter-reset: subsubsection;
			padding-left: 4.5rem;
			list-style-type: none;
		}

		ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter>li {
			counter-increment: subsubsection;
		}

		ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter>li::before {
			content: "1." counter(section) "." counter(subsection) "." counter(subsubsection) ". ";
			font-weight: bold;
			position: absolute;
			left: -4.5rem;
		}

		ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter {
			counter-reset: subsubsubsection;
			padding-left: 5rem;
			list-style-type: none;
		}

		ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter>li {
			counter-increment: subsubsubsection;
		}

		ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter>li>ol.extended-counter>li::before {
			content: "1." counter(section) "." counter(subsection) "." counter(subsubsection) "." counter(subsubsubsection) ". ";
			font-weight: bold;
			position: absolute;
			left: -5rem;
		}

		ol.roman {
			list-style-type: none;
			padding-left: 1.85rem;
			counter-reset: romanCounter;
		}

		ol.roman>li {
			counter-increment: romanCounter;
		}

		ol.roman>li::before {
			content: counter(romanCounter, upper-roman) ". ";
			font-weight: bold;
			position: absolute;
			left: -1.85rem;
		}

		ol.lower-alpha-with-bracket {
			list-style-type: none;
			padding-left: 1.5rem;
			counter-reset: alphabetCounterWithBracket;
		}

		ol.lower-alpha-with-bracket>li {
			counter-increment: alphabetCounterWithBracket;
		}

		ol.lower-alpha-with-bracket>li::before {
			content: counter(alphabetCounterWithBracket, lower-alpha) ") ";
			font-weight: bold;
			position: absolute;
			left: -1.5rem;
		}

		ol.lower-alpha-without-bracket {
			list-style-type: none;
			padding-left: 1.5rem;
			counter-reset: alphabetCounterNoBracket;
		}

		ol.lower-alpha-without-bracket>li {
			counter-increment: alphabetCounterNoBracket;
		}

		ol.lower-alpha-without-bracket>li::before {
			content: counter(alphabetCounterNoBracket, lower-alpha) ". ";
			font-weight: bold;
			position: absolute;
			left: -1.5rem;
		}

		ol.decimal {
			list-style-type: none;
			padding-left: 1.5rem;
			counter-reset: decimalCounter;
		}

		ol.decimal>li {
			counter-increment: decimalCounter;
		}

		ol.decimal>li::before {
			content: counter(decimalCounter) ". ";
			font-weight: bold;
			position: absolute;
			left: -1.5rem;
		}

		.page_break {
			page-break-before: always;
		}

		.page-number:before {
			content: "Page " counter(page);
		}
	</style>
</head>

<body>

	<footer class="d-flex align-items-center" style="padding: 0cm 2cm; height: 4cm">
		<table style="width: 100%;">
			<tr>
				<td style="vertical-align: bottom; text-align: left; width: 50%;"><small class="page-number" style="color: #353535; font-size:0.8rem;"></small></td>
				<td style="vertical-align: middle; text-align: right; width: 50%;">
					<!-- <div style="display: flex; align-items: center; justify-content: flex-end;">
							<p style="width: max-content;text-align: center;height: max-content;"><small style="color: #353535; font-size:0.8rem;">Read, Understood, Agreed, Accepted</small><br><strong class="highlighted"><?= $employee_name ?></strong></p> </div>-->

					<div style="display: inline-block; text-align: center;">
						<p style="width: max-content;text-align: center;height: max-content;">
							<small style="color: #353535; font-size:0.8rem;">
								Read, Understood, Agreed, Accepted
							</small><br>
							<strong class="highlighted"><?= $employee_name ?></strong>
						</p>
					</div>

				</td>
			</tr>
		</table>
	</footer>

	<section class="page page-1">

		<table style="width: 100%; margin-bottom: 20px;">
			<tr>
				<td style="width:70%">
					<h3><strong class="highlighted"><?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong></h3>
					<p><strong class="highlighted"><?= ($gender == 'female' ? 'D/O. ' : 'S/O. ') . $fathers_name ?></strong></p>
					<p><strong class="highlighted" style="padding-right:1.5 rem;"><?= $permanent_address ?></strong></p>
				</td>
				<td style="vertical-align: top; width:30%">
					<p style="text-align: right;">Date: <strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong> </p>
				</td>
			</tr>
		</table>

		<hr style="border: none; border-bottom: 1px solid grey; margin: 20px 0px;">

		<div>
			<br><br>
			<p><strong class="highlighted">Dear <?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong>,</p><br>
			<p class="text-justify">With reference to your application and subsequent meeting with us, we are pleased to offer you an appointment as “<strong class="highlighted"><?= $designation_name ?></strong>”. <?php echo !in_array($minimum_wages_category_name, ['Un-Skilled', 'Non-Matriculate']) ? "Please be informed that you hold a supervisory position / role within the organisational structure of the company and do not fall under the category of workmen. As such, the terms and conditions of your employment shall be governed by the terms of this agreement/letter of appointment." : ""; ?></p><br>
			<p class="text-justify">Your date of joining in the <strong class="highlighted"><?= $company_name ?></strong> is effective from <strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong>. Your Annual Cost to <strong class="highlighted"><?= $company_name ?></strong> (CTC) is INR <strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_ctc) ?></strong> <strong class="highlighted">(<?= AmountInWords(12 * $monthly_ctc) ?> Only)</strong> as per details in Annexure - A.</p><br>
			<p class="text-justify">The Terms and Conditions of Appointment (Annexure A) are enclosed. We request you to confirm to us your acceptance by returning the signed duplicate copy of Annexure A.<br>We welcome you, and look forward to a long and mutually beneficial association.</p><br><br><br>
			<p>Thanking You<br>Yours truly,<br><br><strong class="highlighted">For <?= $company_name ?></strong></p>
		</div>

		<table style="width: 100%; margin-bottom: 20px;">
			<tr>
				<td>
					<p><strong>Authorized Signatory</strong><br><strong>Praveen Kumar Sinha</strong><br><strong>HR Manager</strong></p>
				</td>
				<td style="vertical-align: top;">
					<p style="max-width: max-content; text-align: center; ">&nbsp;<br><strong class="highlighted"><?= trim($employee_name) ?></strong><br><strong>Signature</strong></p>
				</td>
			</tr>
		</table>

		<div class="page_break"></div>

		<h3>LETTER OF APPOINTMENT</h3>
		<ol class="extended-counter">
			<li><strong>Probation.</strong>
				<?php
				if ($probation == '45 Days Probation') {
				?>
					<p>In the first instance the employee will be on probation for a period of 45 days from the date of your joining. Where-after, the probation period may be either extended upto further 45 days at the discretion of the management or may be dispensed with either earlier or on completion or thereafter till confirmation. Unless confirmed in writing, the employee will be deemed as probationer after the expiry of the initial or extended period of probation. Employment services are liable to be terminated without any notice or wages in lieu thereof during the initial or extended period of probation.
					</p>
				<?php
				} elseif ($probation == '90 Days Probation') {
				?>
					<p>In the first instance the employee will be on probation for a period of 90 days from the date of your joining. Unless confirmed in writing, the employee will be deemed as probationer after the expiry of the initial or extended period of probation. Employment services are liable to be terminated without any notice or wages in lieu thereof during the initial or extended period of probation.
					</p>
				<?php
				}
				?>

			</li>
			<li>
				<strong>General Term and Condition :-</strong>
				<ol class="extended-counter">
					<li>
						The Employee has to provided the reliving letter of all your previous employments. The salary shall only be accrual & payable, post submission of reliving certificates.
					</li>
					<li>
						In case the reliving certificate is false / incorrect, then this contract of employment, shall stand null & void. Any salary paid, is voided & not payable. The undersigned employee undertakes to return all the payments received by employee till date, with 12% interests the undersigned employee does understand that he has falsified data & purposefully misrepresented, which also amounts to cheat & fraud and the <strong class="highlighted"><?= $company_name ?></strong> reserve the right criminal charges.
					</li>
					<li>
						The Employment contract is only valid, if the back ground check & verification are validated. If there is any misrepresentation fraud in your resume, certificates, reliving letters, then this contract of employment, shall stand null & void. Any salary paid, is voided & not payable. You undertake to return all the payments received by yourself, till date, with 12% interest, as you understand that you have falsified data & purposefully misrepresented, which also amounts to cheat & fraud.
					</li>
					<li>
						You declare that the information provided by you by verbal communication or in writing, regarding the status of your previous Gratuity, Bonus, Pension, membership with ESI / EPF schemes etc (all government schemes) are correct. Also, you declare that if at any time in future, the above information provided by you is found wrong, then you will be responsible for the legal and financial consequence, which may occurdue to wrong information provided by you. Further, absolve & indemnify the <strong class="highlighted"><?= $company_name ?></strong> for not being held liable / responsible for such payments / penalties, interest etc, which may occur in future due to wrong declarations / disclosures mad by Employee. Employee declare that If in future any takes, penalties interest or dues are raised against <strong class="highlighted"><?= $company_name ?></strong> by any government department, due to wrong information provided by you; you will be considered responsible for the same and will deposit all such dues to government yourselves from your side.
					</li>
					<li>
						<p>The <strong class="highlighted"><?= $company_name ?></strong> offers salaries in form of C.T.C. (Cost to <strong class="highlighted"><?= $company_name ?></strong>), as mentioned in Annexure A, are which is inclusive of all the sub-parts likeBasic, HRA, Travel allowance, E.S.I/ E.P.F. (both contributions of Employee's & Employer shares , If applicable). If in future E.S.I. or E.P.F. or any other government department, raises any dues against the <strong class="highlighted"><?= $company_name ?></strong> due to any incorrect information / data provided by you or due to even misunderstanding; you will be responsible for the same. Also, you absolve and indemnify the <strong class="highlighted"><?= $company_name ?></strong> for not being held liable / responsible for any such payments / penalties , interest etc. If in future, any payments, penalties interest has to be paid, because of the wrong information, provided by employee then employee will deposit the same to the government or to the <strong class="highlighted"><?= $company_name ?></strong>, or only adjusted form his salary/dues.</p>
					</li>
					<li>
						You, the undersigned employee understand, acknowledge & agree that <strong class="highlighted"><?= $company_name ?></strong> offered you to be a member of E.P.F. Schemes, regardless your previous membership status with E.P.F. schemes. Further you (employee) declare that you are well aware that the salaries being offered to you by <strong class="highlighted"><?= $company_name ?></strong> are more than the E.P.F. statutory mandatory limit. Also, you confirm that you have never been a member of the E.P.F. schemes in past nor you are willing to avail E.P.F. schemes with the <strong class="highlighted"><?= $company_name ?></strong> Hence the amount which should have been submitted to E.P.F. department as contribution of Employee's & Employer's shares is being paid to you, as a part of salary, upon your wish, request to <strong class="highlighted"><?= $company_name ?></strong> You have also submitted a Form-11 of E.P.F., which is declaration of yours that you are not willing to be a member of E.P.F. schemes / social welfare schemes of G.O.I., and you have got that equivalent amount added in this CTC package been offered.
					</li>
					<li>
						You understand, acknowledge, agree & declare that even if in future, if E.P.F. Department, due to any existing / new notification or clause or circumstances or due to any fact finds you to be an eligible member and rejects your voluntary exclusion from E.P.F. scheme, you the undersigned employee yourself will deposit the employee and employer share of E.P.F. contribution to E.P.F. department along with the interest and penalties, of the past & will indemnify the <strong class="highlighted"><?= $company_name ?></strong>, regardless you are in employment with <strong class="highlighted"><?= $company_name ?></strong> or not. In case you fail to pay such employee and employer contribution share to E.P.F. department along with interest and penalties and <strong class="highlighted"><?= $company_name ?></strong> has to pay such amount; <strong class="highlighted"><?= $company_name ?></strong> will be authorized to recover such losses from you along with legal charges and 18% compound interest, from the date of such membership contribution was due.
					</li>
					<li>
						You, the undersigned employee understand, acknowledge , agree & declare that even if in future, if E.P.F. Department, due to any existing / new notification or clause or circumstances or due to any fact finds you to be a eligible member and rejects your voluntary exclusion from E.P.F. scheme, then your CTC package will remain the same & the take home package may reduce, after reducing the government mandated dues.
					</li>
					<div class="page_break"></div>
					<li>
						In the case of a confirmed employee ( no longer on probation ) a person being absent for 1 or more day in the first 6 days of the month, then the salary will be distributed after 10 days of working, to ensure, no abandonment of work, after getting salary, as we have <strong class="highlighted"><?= $notice_period ?></strong> of notice periods.
					</li>
					<li>
						Imprest money against any work taken by the employee must be settled by the end of the respective month in which they were acquired. Failure to adhere to the timeline shall result in deductions from the employee’s salary of the imprest amount taken. In case the imprest amount is not settled for whatsoever reasons, the imprest amount then automatically gets converted to advance taken against Salary and the same needs to be settled in cash between 1st to 7th of every month.
					</li>
					<li>
						Bonus Eligibility: Every employee of this Company shall be eligible for a yearly bonus as per the Bonus Act 1965, subject to the following conditions:
						<ul>
							<li>
								Notice Period Compliance: All employees are required to serve a notice period of <strong class="highlighted"><?= @$notice_period_in_months_text ?></strong> (<strong class="highlighted"><?= @$notice_period_in_months ?></strong>) months after submitting their resignation. In the event that an employee fails to serve the entire notice period, he/she shall not be eligible for or entitled to any bonus in the full and final settlement.
							</li>
							<li>
								Breach of Letter of Appointment: Any employee found to be in breach of the terms outlined in their Letter of Appointment, including but not limited to acts of misconduct, violation of company policies, or any other form of breach, shall not be entitled to receive any bonus.
							</li>
						</ul>
					</li>
				</ol>
			</li>

			<!-- <div class="page_break"></div> -->

			<li><strong>General Guideline at Workplace :</strong>
				<ol class="extended-counter">
					<li>
						Employees have to follow all the <strong class="highlighted"><?= $company_name ?></strong> guidelines currently given below & the <strong class="highlighted"><?= $company_name ?></strong>, can amend change the same time to time . The same shall be informed to the employee, by email , letter or whats app;
						<ol class="roman">
							<li>
								Uses of personal social media is restricted in the office premises.
							</li>
							<li>
								Use of External hard drive or floppy are restricted in work place.
							</li>
							<li>
								Use of personal laptops, personal pen drives, USB or any electronic media without the strict written consent of the top management is strictly prohibited inside the premises of the <strong class="highlighted"><?= $company_name ?></strong>
							</li>
							<li>
								Access of personal mails in internet or outflow of any information either in soft or hard copy is strictly prohibited.
							</li>
							<li>
								Taking a screenshot or use the message of any whats-app group of the <strong class="highlighted"><?= $company_name ?></strong> for any reason whatsoever, is prohibited and for violating this, legal action can be taken against the employee.
							</li>
							<li>
								Sharing any information you have received in meetings, presentation, email, internal communication, <strong class="highlighted"><?= $company_name ?></strong> whats app groups for any reason whatsoever, is completely prohibited.
							</li>
						</ol>
					</li>
				</ol>
			</li>
			<div class="page_break"></div>
			<li><strong>Exit Formalities.</strong>
				<ol class="extended-counter">
					<li>
						Any employee willing to leave the services of the <strong class="highlighted"><?= $company_name ?></strong> will have to notify his/her immediate supervisor of his intention to leave the services in the form of resignation by stating the reasons for the same and confirmation of serving the full working notice period as mentioned in the appointment letter. Excess/Unutilized earned leaves & casual leave any shall be en-cashed/paid in Full and final settlement, as the Notice period, is the time required, for <strong class="highlighted"><?= $company_name ?></strong> to find an alternative replacement in 30 days & get clear 30 days to train & give handover to the new inducted employee.
					</li>
					<li>
						Payment of full & final settlement requires NO DUES / clearance from different departments of <strong class="highlighted"><?= $company_name ?></strong>, in per-specified format.
					</li>
					<li>
						Once the employee resign then the salary shall be paid at once go in full & final settlement after calculation the notice period served, deductions such as imprest / advances / loans or the amount equivalent to the unreturned <strong class="highlighted"><?= $company_name ?></strong> Property and asset.
					</li>
					<li>
						Exit interview with HR is mandatory & can be done 5 days prior to the last day of relieving & has to be a part of the no dues to release full & final settlement.
					</li>
					<li>
						Employee can initiate the no due process 5 days before the end of notice period and submit it to HR who will thereafter within 45 days process the full and final settlement .
					</li>
					<li>
						Employee needs to clear <strong class="highlighted"><?= $company_name ?></strong> data, available with him in any form such as whatsapp chat data / group at employees personal mobiles / laptop in front of I.T. team of <strong class="highlighted"><?= $company_name ?></strong> Also needs to remove <strong class="highlighted"><?= $company_name ?></strong> email access from his mobile and need to provide passwords of <strong class="highlighted"><?= $company_name ?></strong> email to I.T. team , to get the no dues from IT. Even so, the employee is strictly prohibited to delete any file from his/her system or from Google Drive at the time of his/her separation.
					</li>
				</ol>
			</li>
			<li><strong>Medical Fitness & Verification of Particulars.</strong>
				<ol class="extended-counter">
					<li>
						Your appointment is subject to you're being declared (and remaining) medically fit by a Medical Officer or by a Doctor specified by the <strong class="highlighted"><?= $company_name ?></strong> The Management has the right to get you medically examined by any certified medical practitioner during the period of your service. In case you are found medically unfit to continue with the job, the <strong class="highlighted"><?= $company_name ?></strong> shall be at liberty to terminate your services without notice.
					</li>
				</ol>
			</li>

			<div class="page_break"></div>

			<li><strong>Duties And Responsibilities.</strong>
				<ol class="extended-counter">
					<li>
						The duties and responsibilities are detailed as under:-
						<p>
							You are being deputed to perform the duties of “<strong class="highlighted"><?= $designation_name ?></strong>” at <strong class="highlighted"><?= $company_address ?></strong> Location
						</p>
						<ol class="lower-alpha-with-bracket">
							<li>
								The <strong class="highlighted"><?= $company_name ?></strong> will expect you to work with a high standard of work ethics , professionalism, initiative, efficiency and economy.
							</li>
							<li>
								You will devote your entire time to the work of the <strong class="highlighted"><?= $company_name ?></strong> and will not undertake any direct/ indirect business or work, honorary or remuneration except with the written permission of the management in each case.
							</li>
							<li>
								You shall not accept any gifts, cash or even favors from any associates , buyer , sellers , vendors or service provider of the <strong class="highlighted"><?= $company_name ?></strong>
							</li>
							<li>
								You shall not seek membership of any local or public bodies without first obtaining written permission from the management.
							</li>
							<li>
								You will be responsible for the safe keeping and return in good condition and order of all the properties of the <strong class="highlighted"><?= $company_name ?></strong> which may be in your use, custody, care or charge. For the loss of any property of the <strong class="highlighted"><?= $company_name ?></strong> in your possession, the <strong class="highlighted"><?= $company_name ?></strong> will have a right to assess on its own basis and recover the damages of all such material from you and to take such other action as it deems proper in the event of your failure to account for such material or property to its Satisfaction.
							</li>
							<li>
								You are entitled to following leaves in a year governed by various guidelines and circulars issued from time to time.
								<p>
									Casual Leave - 1 day per month
									<br>
									Earned Leave - 15 days in a year
									<br>
									You would be eligible for earned leave upon successful completion of one year ( post 1 year of completed service) with the <strong class="highlighted"><?= $company_name ?></strong> The Earned leaves are encash able only at the time of exit from the <strong class="highlighted"><?= $company_name ?></strong> In case of Earned leave, a 15 days per-approval is mandatory as per this emplacement contract, as <strong class="highlighted"><?= $company_name ?></strong> has to plan the handover of the work of these longer leaves. Approval of leaves less than 15 days , shall be treated as absent.
								</p>
							</li>
						</ol>
					</li>
				</ol>
			</li>

			<div class="page_break"></div>

			<li><strong>Termination on the following grounds:-</strong>
				<ol class="extended-counter">
					<li>
						You will automatically retire from the services of the <strong class="highlighted"><?= $company_name ?></strong> on attaining the superannuation at age of 58 years. After the expiry of the period of 58 years, the management reserves the right to extend your services for a period of one year provided you are physically and mentally fit to carry on the work in the organization subject to maximum up to the age of 60 years. The management is the sole judge of such extensions. Any appointment after which shall be subject to a new agreement.
					</li>
					<li>
						If you absent yourself without leave or remain absent beyond the period of leave originally granted or subsequently extended, you shall be considered as having voluntarily terminated your employment without giving any notice after deduction of notice period as per clause 4(e) unless you
						<ol class="roman">
							<li>
								Return to work within ten days of the commencement of such absence; and
							</li>
							<li>
								give a proof/explanation to the satisfaction of the <strong class="highlighted"><?= $company_name ?></strong> regarding such absence.
							</li>
						</ol>
					</li>
					<li>
						Your services are liable to be terminated without any notice or salary in lieu thereof in the case of continued ill health.
					</li>
					<li>
						Your services are liable to be terminated without any notice or salary in lieu there of for misconduct, without being exhaustive and without prejudice to the general meaning of the term ‘misconduct’ in the case of reasonable suspicion of misconduct, disloyalty, commission of any act involving moral turpitude, any act of in discipline or inefficiency as compared to other employees or lower performance as compared to other employees of your category.
					</li>
					<li>
						Your services are terminable either by the <strong class="highlighted"><?= $company_name ?></strong> or by you, by giving resignation & serving the working notice period or payment of notice period in lieu there of on either side. During the notice/handover period, you cannot take any leaves , however can encash your unutilized leaves but not permitted to avail/adjust leave in notice period as handover of work is required and is important, for the <strong class="highlighted"><?= $company_name ?></strong> and more important for all the other working employees, who is livelihood is still dependent on the <strong class="highlighted"><?= $company_name ?></strong>
					</li>
					<li>
						Your services are liable to be terminated without any notice or salary in lieu thereof in the case of the miss-declaration, wrong information or withheld information, given about your past history/reference, education/ Employment history. If at any time of the service it is found that you have given wrong information for the job or withheld information pertaining to any legal matters, civil/labour/criminal or any legal dispute civil/labour/criminal against your past employer, your services are liable to be terminated without any notice or salary in lieu.
					</li>
					<li>
						Our company has a strict policy against sexual harassment and misconduct in the workplace. We take such matters seriously and have established a Prevention of Sexual Harassment Committee to address any reported incidents promptly and impartially. In the event that you are found to have violated our company's policies on sexual harassment, and such violation is certified by the POSH committee, your employment with the company may be terminated immediately.
					</li>

					<!-- <li>
						The Candidate acknowledges and agrees that the consumption of alcohol, tobacco, or any other intoxicating substances while driving or riding during the course of employment is strictly prohibited. Operating any vehicle under the influence is hazardous and poses a serious risk to personal safety and the safety of others.

					</li>
					<li>
						The Candidate affirms that they do not consume alcohol. If it is found at any point that the Candidate has consumed alcohol or misrepresented this fact during the hiring process, it will be considered a case of false declaration, and disciplinary action—including immediate termination of employment—may be taken.


					</li>
					<li>
						Violation of this clause will result in immediate termination of employment without notice.

					</li> -->
				</ol>
			</li>



			<li><strong>Auto voiding / cancellation of this emplacement contract :</strong>
				<ol class="extended-counter">
					<li>
						If any employee takes unapproved leave, more than 5 days in a month.
					</li>
					<li>
						Cumulative 10, unapproved full or half day leaves in 2 months period.
					</li>
					<li>
						Late coming (of over 15 min) , more than 12 times in a month.
					</li>
					<li>
						Late coming (of over 15 min) , more than 18 times in 2 months (last 46 working days).
					</li>
					<li>
						Any POSH complaint received & posh commit, hold you even partially responsible.
					</li>
				</ol>
			</li>
			<li><strong>Notice Period Policy</strong>
				<ol class="extended-counter">
					<li>
						The <strong class="highlighted"><?= $company_name ?></strong> has a <strong class="highlighted"><?= $notice_period ?></strong> notice period, which comes into effect from your date of resigning. <?php if (@$attachment['pdc_cheque']['enable_pdc'] == 'yes') { ?>this notice period repayment , is indemnified by the employee, thru his Undated <strong class="highlighted"><?= $number_of_cheques ?></strong> cheque's each equal to its 1 month of salary ( total <strong class="highlighted"><?= $notice_period_in_months ?></strong> months).<?php } ?>
					</li>
					<?php if (@$attachment['pdc_cheque']['enable_pdc'] == 'yes') { ?>
						<li>
							The following <strong class="highlighted"><?= $number_of_cheques ?></strong> cheques are taken from the employee to safeguard the notice period and if the employee breaches the contract and leaves the organization without serving the notice period (for <strong class="highlighted"><?= $notice_period ?></strong>) as mutually agreed, the employer will reimburse the amount in lieu of unserved of notice by the employee through this cheque.
							<p>
								Details of cheques are:
							</p>
							<ol class="decimal">
								<?php
								if ($number_of_cheques >= 1) {
								?>
									<li><strong class="highlighted"><?= $attachment['pdc_cheque']['bank_name_1'] ?></strong> , Chq. ( <strong class="highlighted"><?= $attachment['pdc_cheque']['cheque_number_1'] ?></strong> )</li>
								<?php
								}
								?>
								<?php
								if ($number_of_cheques >= 2) {
								?>
									<li><strong class="highlighted"><?= $attachment['pdc_cheque']['bank_name_2'] ?></strong> , Chq. ( <strong class="highlighted"><?= $attachment['pdc_cheque']['cheque_number_2'] ?></strong> )</li>
								<?php
								}
								?>
								<?php
								if ($number_of_cheques >= 3) {
								?>
									<li><strong class="highlighted"><?= $attachment['pdc_cheque']['bank_name_3'] ?></strong> , Chq. ( <strong class="highlighted"><?= $attachment['pdc_cheque']['cheque_number_3'] ?></strong> )</li>
								<?php
								}
								?>
							</ol>
						</li>
					<?php } ?>
				</ol>
			</li>

			<div class="page_break"></div>

			<li><strong>General</strong>
				<p>
					The following general points are to be observed:-
				</p>
				<ol class="lower-alpha-without-bracket">
					<li>
						In respect of all matters related to Service conditions including those not specifically covered by this letter will be governed by Service Rules as applicable to personnel of your category and as per <strong class="highlighted"><?= $company_name ?></strong> policy as in force from time to time.
					</li>
					<li>
						In case of any dispute of any kind arising out of your employment or breach of contracts, Delhi Courts alone would have jurisdiction in the matter.
					</li>
					<li>
						This appointment is based on the information supplied to us by you in your application/personal resume form. If any information furnished by you is found to be incorrect or it is found at a later date that you have not revealed or have willfully withheld any relevant information, your services are liable to be terminated and you will not be entitled to any dues accrued from this illegal employment.
					</li>
					<li>
						It is mandatory for you to furnish the details of current employment of your family members. Also, you are required to submit the details in written about the previous employment of any of your family members, if they were associated in any manner/capacity with similar industry or similar product segment at any time. If you fail to declare the above mentioned details and in future at any time the <strong class="highlighted"><?= $company_name ?></strong> gets to know about any such conflict of interest, it will be considered as intentional breach of trust and your services may be terminated on immediate basis. Also, you may be prosecuted for the same or have to face other legal consequences.
					</li>
					<li>
						In case of termination or putting of the resignation from the <strong class="highlighted"><?= $company_name ?></strong>, salary & full and final settlement will be processed together as of full & final Settlement post the handover, no dues certificate and the HOD clearance, as per company procedures/policies.
					</li>
					<li>
						Your age mentioned in the Matriculation/Higher Secondary certificate will be deemed to be the conclusive proof of your date of birth.
					</li>
					<li>
						It is the duty of employees to intimate in writing to the Management any change of address within a week from the change of the same, failing which any communication sent on your last recorded address shall be deemed to have been served to you.
					</li>
					<li>
						you have to give an annual statement, of temporary address/Permanent Address and updated details of all the family member, proof of residence (temporary and permanent) and assets in possession. In case of change in between, it has to be automatically updated.
					</li>
					<li>
						The designation assigned to you is subject to change depending upon work assignments from time to time.
					</li>
					<li>
						The present employment is transferable from one permanent location to another. The management also has the right to transfer you to subsidiary/associated/Parent companies, offices/factories at any place existing at present or which may be established in future in the exigencies of work of the <strong class="highlighted"><?= $company_name ?></strong> In case the employee does not join within 7 days at new place , then he shall be treated as abandonment and notice period will be deducted and job terminated.
					</li>
					<li>
						Use of mobile phones, personal laptops or any electronic media without the consent of the management is strictly prohibited inside the premises of the <strong class="highlighted"><?= $company_name ?></strong>
					</li>
					<li>
						Access of personal mails in internet or outflow of any information either in soft or hard copy without the consent of the management is strictly prohibited.
					</li>
					<li>
						We have a policy to release relieving letter to each and every employees after their separation from the <strong class="highlighted"><?= $company_name ?></strong>
					</li>
					<li>
						Relieving -cum- Experience letter will be released after 60 days of completion of notice period after full and final settlement done.
					</li>
					<li>
						We have a link for further verification of the employees who leave work from here, on entering the Aadhar card number, all the details of that employee will be known to the next employer.
					</li>
					<li>
						You will hand over the charge and the property and material of the <strong class="highlighted"><?= $company_name ?></strong> in your possession at the time of cessation of your employment with the <strong class="highlighted"><?= $company_name ?></strong>
					</li>
					<li>
						You will be liable to pay damages to the <strong class="highlighted"><?= $company_name ?></strong> for the loss caused by you directly or indirectly in addition to other legal remedies which may be required for violating any of the provision of this appointment letter and for this the Courts at Delhi will have jurisdiction.
					</li>
				</ol>
				<br>
				<p>
					I have read and understood the terms and conditions stated above and in the earlier pages and hereby signify my acceptance of the same.
				</p>
				<br>
				<br>
				<br>
				<table style="width: 100%;">
					<tr>
						<td>
							<strong>Signature:</strong>
						</td>
						<td>
							<strong>Name :</strong>
						</td>
						<td>
							<strong>Date:</strong>
						</td>
					</tr>
				</table>
				<br>
				<br>
			</li>
		</ol>

		<div class="page_break"></div>

		<div style="font-size:12px;">
			<h2 class="text-center">ANNEXURE - A</h2>
			<br>
			<table style="width:100%;">
				<tr>
					<td style="width:48%; vertical-align: top;">
						<table>
							<tr>
								<td style="vertical-align: top; min-width: max-content;">
									<strong>Name:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= $employee_name ?></strong>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; min-width: max-content;">
									<strong>Department:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= $department_name ?></strong>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; min-width: max-content;">
									<strong>Designation:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= $designation_name ?></strong>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; min-width: max-content;">
									<strong>Location:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= $company_city ?></strong>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; min-width: 7rem;">
									<p style="min-width: max-content;"><strong>Date of Joining:</strong></p>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong>
								</td>
							</tr>
						</table>
					</td>
					<td style="width:4%">
					</td>
					<td style="width:48%; vertical-align: top;">
						<table class="" style="float: right; ">
							<tr>
								<td style="vertical-align: top; min-width: max-content;">
									<strong>LOA date:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; min-width: max-content;">
									<strong>State of appointment:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= $company_state ?></strong>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; min-width: max-content;">
									<strong>Workman:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= in_array($minimum_wages_category_name, ['Un-Skilled', 'Non-Matriculate']) ? "Yes" : "No"; ?></strong>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; min-width: max-content;">
									<strong>Status of Skill:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted"><?= $minimum_wages_category_name ?></strong>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; min-width: max-content; max-width: 7rem;">
									<strong>Minimum wage:</strong>
								</td>
								<td style="padding-left: 1rem; vertical-align: top;">
									<strong class="highlighted">INR <?= formatToIndianCurrency($minimum_wages_category_value) ?></strong>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<table class="annexure" style="width: 100%; ">
				<tr>
					<td><strong>Component</strong></td>
					<td style="text-align: center;"><strong>Amount Per Month</strong></td>
					<td style="text-align: center;"><strong>Amount Per Annum</strong></td>
				</tr>
				<tr>
					<td>Basic salary</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency($monthly_basic_salary) ?></strong>
					</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_basic_salary) ?></strong>
					</td>
				</tr>
				<tr>
					<td>HRA</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency($monthly_house_rent_allowance) ?></strong>
					</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_house_rent_allowance) ?></strong>
					</td>
				</tr>
				<tr>
					<td>Conveyance</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency($monthly_conveyance) ?></strong>
					</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_conveyance) ?></strong>
					</td>
				</tr>
				<tr>
					<td colspan="3"><strong>Additional benefits / perks</strong></td>
				</tr>
				<tr>
					<td>Medical Allowance</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency($monthly_medical_allowance) ?></strong>
					</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_medical_allowance) ?></strong>
					</td>
				</tr>
				<tr>
					<td>Special Allowance</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency($monthly_special_allowance) ?></strong>
					</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_special_allowance) ?></strong>
					</td>
				</tr>
				<tr>
					<td>Fuel Allowance</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency($monthly_fuel_allowance) ?></strong>
					</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_fuel_allowance) ?></strong>
					</td>
				</tr>
				<tr>
					<td>Vacation Allowance</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency($monthly_vacation_allowance) ?></strong>
					</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_vacation_allowance) ?></strong>
					</td>
				</tr>
				<tr>
					<td>Other Allowance</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency($monthly_other_allowance) ?></strong>
					</td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_other_allowance) ?></strong>
					</td>
				</tr>
				<tr>
					<td><strong>Gross Wages</strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency($monthly_gross_salary) ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(12 * $monthly_gross_salary) ?></strong></td>
				</tr>
				<tr>
					<td>Employee ESI</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($esi_employee_contribution)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($esi_employee_contribution * 12)); ?></strong></td>
				</tr>
				<tr>
					<td>Employee Epf</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($pf_employee_contribution)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($pf_employee_contribution * 12)); ?></strong></td>
				</tr>
				<tr>
					<td>LWF (If & When applicable)</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($lwf_employee_contribution)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($lwf_employee_contribution * 12)); ?></strong></td>
				</tr>
				<tr>
					<td><strong>Net Pay</strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($net_pay)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($net_pay * 12)); ?></strong></td>
				</tr>
				<tr>
					<td colspan="3"><strong>Other Benefits</strong></td>
				</tr>
				<tr>
					<td>Employer ESI</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($esi_employer_contribution)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($esi_employer_contribution * 12)); ?></strong></td>
				</tr>
				<tr>
					<td>Employer Epf</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($pf_employer_contribution)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($pf_employer_contribution * 12)); ?></strong></td>
				</tr>
				<tr>
					<td>15 EL’s from 2nd Year onward</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($monthly_el)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($monthly_el * 12)); ?></strong></td>
				</tr>
				<tr>
					<td>CL-1 Per Month</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($monthly_cl)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($monthly_cl * 12)); ?></strong></td>
				</tr>
				<tr>
					<td>Bonus (If & When Applicable)</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($bonus)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($bonus * 12)); ?></strong></td>
				</tr>
				<tr>
					<td>Gratuity (If & When Applicable)</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($gratuity)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($gratuity) * 12); ?></strong></td>
				</tr>
				<tr>
					<td>Non Compete Loan</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($non_compete_loan_amount_per_month)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($non_compete_loan_amount_per_month * 12)); ?></strong></td>
				</tr>
				<tr>
					<td>LWF (If & When applicable)</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($lwf_employer_contribution)); ?></strong></td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($lwf_employer_contribution * 12)); ?></strong></td>
				</tr>
				<tr>
					<td><strong>TOTAL CTC</strong></td>
					<td style="text-align: center;">
						<strong class="highlighted"><?= formatToIndianCurrency(round($ctc)); ?></strong>
					</td>
					<td style="text-align: center;"><strong class="highlighted"><?= formatToIndianCurrency(round($ctc * 12)); ?></strong></td>
				</tr>
			</table>
			<br>
			<p>
				<strong class="highlighted">For <?= $company_name ?></strong>
			</p>
			<br>
			<p>
				<strong>Authorized Signatory</strong>
				<br>
				<strong>Praveen Kumar Sinha</strong>
				<br>
				<strong>HR Manager</strong>
			</p>
			<br>
			<h3>Acceptance</h3>
			<br>
			<p>
				I have read and understood the above distribution of my salary details and the same is acceptable to me.
			</p>
			<br>
			<table style="width: 100%;">
				<tr>
					<td>
						<strong>Signature:</strong>
					</td>
					<td>
						<strong>Name :</strong>
					</td>
					<td>
						<strong>Date:</strong>
					</td>
				</tr>
			</table>
		</div>
		<div class="page_break"></div>

		<h2 class="text-center">NON DISCLOSURE/ SECRECY AGREEMENT</h2>
		<br>
		<p>
			You will not disclose any secrecy of manufacturing processes / suppliers / buyers / procedural information / security arrangements / administrative/ IT information / Technical information/Technical know how and or any organizational matters or processes, which you will learn and would be privy during your employment with M/s “<strong class="highlighted"><?= $company_name ?></strong>” and will not sell, transfer and alienate during your service, either by way of email or by any communicating ultra modern system of computers to anybody else regarding secrecy of the <strong class="highlighted"><?= $company_name ?></strong>
		</p>
		<ol class="lower-alpha-with-bracket">
			<li>
				All WhatsApp communications exchanged between <strong class="highlighted"><?= $company_name ?></strong>, its Directors, representative, employees and <strong class="highlighted"><?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong>, shall be deleted within 7 (seven) days from the date of receipt of such communications from the Mobile Device or any other electronic device being used by the employee. It is expressly understood and agreed that internal emails or WhatsApp communications shall be deemed to be proprietary and confidential information of the company and its working and cannot be shared with any third party or before any authority without prior written consent from the Company. Any informal discussion with the Employee in relation to his/her performance/ organizations growth plan etc. shall under no circumstance, be binding on the parties. Any feedback shared with an employee via official mode of communication shall be binding and reliance on any other form of communication shall be deemed to be breach of confidentiality. Parties undertake to maintain absolute confidentiality in respect of the information being received by him/her during the course of his/her employment with the Company.
			</li>
			<li>
				You shall hold in trust and confidence of all Confidential Information of the “<strong class="highlighted"><?= $company_name ?></strong>” and agrees not to disclose such information to any third party anywhere in the world or use such information for any purpose other than that for which such information has been disclosed to you by the “<strong class="highlighted"><?= $company_name ?></strong>”.
			</li>
			<li>
				You shall not make any copies of such Confidential Information of the <strong class="highlighted"><?= $company_name ?></strong> You agrees that all
			</li>
			<li>
				Confidential Information disclosed by the “<strong class="highlighted"><?= $company_name ?></strong>” shall remain the property of “<strong class="highlighted"><?= $company_name ?></strong>” and shall not be disclosed by you at all.
			</li>
			<li>
				Any Confidential Information coming to your knowledge by virtue of this employment or during course of the employment in the “<strong class="highlighted"><?= $company_name ?></strong>” is strictly confidential and that you shall not directly or indirectly associate yourself to any third party to compete in any way anywhere in the world, with the entire range of business, concepts, products, services and intellectual properties of <strong class="highlighted"><?= $company_name ?></strong> or its clients.
			</li>
			<li>
				That the <strong class="highlighted"><?= $company_name ?></strong> is predominantly into trading and marketing of products being developed in its OEM, hence the information relating to product sourcing and buyers, and the international optimized processes, is the technical know-how of the <strong class="highlighted"><?= $company_name ?></strong>, that the <strong class="highlighted"><?= $company_name ?></strong> has over a period of the time developed data base as well as contacts with several sources and all such information is a key confidential trade secret, and must be protected during your tenure of service and also upto 24 months post your employment with the <strong class="highlighted"><?= $company_name ?></strong>
			</li>
			<li>
				You shall under no circumstance, disclose Confidential information to any other third party
			</li>
			<li>
				without first obtaining written consent from the “<strong class="highlighted"><?= $company_name ?></strong>”, or its duly authorized representative.
			</li>
			<li>
				You acknowledges that the above information is material and confidential and that it affects the business operations of the <strong class="highlighted"><?= $company_name ?></strong>
			</li>
			<li>
				I understands that any breach of this provision, or of any other confidential and Non-Disclosure Agreement, is a material breach of this agreement.
			</li>
			<li>
				That in case of breach of any clause or the meaning of such clauses implied or written, the <strong class="highlighted"><?= $company_name ?></strong> shall have the right to recover any damages caused on account of such breach(s) from you.
			</li>
		</ol>
		<br>
		<h3>Acceptance</h3>
		<br>
		<p>
			I have read and understood the above NON DISCLOSURE/ SECRECY AGREEMENT and declare to abide by it in full.
		</p>
		<br>
		<table style="width: 100%;">
			<tr>
				<td>
					<strong>Signature:</strong>
				</td>
				<td>
					<strong>Name :</strong>
				</td>
				<td>
					<strong>Date:</strong>
				</td>
			</tr>
		</table>
	</section>

</body>

</html>