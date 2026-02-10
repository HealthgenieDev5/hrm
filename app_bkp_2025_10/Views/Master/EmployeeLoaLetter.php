<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
			padding-top: 2cm;
			padding-left: 2cm;
			padding-right: 2cm;
			padding-bottom: 2cm;
		}


		p,
		h1,
		h2,
		h3,
		h4,
		h5,
		h6 {
			margin: 0;
			line-height: 1.2;
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

		<table style="width: 100%; margin-bottom: 10px;">
			<tr>
				<td style="width:70%">
					<p><strong class="highlighted"><?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong></p>
					<p><strong class="highlighted"><?= ($gender == 'female' ? 'D/O. ' : 'S/O. ') . $fathers_name ?></strong></p>
					<p><strong class="highlighted"><?= $permanent_address ?></strong></p>
				</td>
				<td style="vertical-align: top; width:30%">
					<p style="text-align: right;">Date: <strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong></p>
				</td>
			</tr>
		</table>

		<hr style="border: none; border-bottom: 1px solid grey; margin: 10px 0px;">

		<div style="margin-top: 10px; line-height: 1.3;">
			<p><strong class="highlighted">Dear <?= ($gender == 'female' ? 'Mrs. ' : 'Mr. ') . $employee_name ?></strong>,</p><br>

			<p class="text-justify">We are pleased to inform you that you have been selected for the position of <strong class="highlighted"><?= $designation_name ?></strong> at <strong class="highlighted"><?= $company_name ?></strong>, effective from <strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong>.</p><br>

			<p class="text-justify">As <?= preg_match('/\bintern\b/i', $designation_name) ? 'an ' . $designation_name : 'a ' . $designation_name ?>, your key responsibilities will include assisting in relevant tasks, learning professional skills, and supporting the overall operations of the company. Your performance and contributions will be evaluated regularly to ensure personal and professional development.</p><br>

			<p><strong>Details of your appointment are as follows:</strong></p><br>

			<p><strong>Position:</strong> <strong class="highlighted"><?= $designation_name ?></strong></p>

			<p><strong>Start Date:</strong> <strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong></p>

			<?php if (isset($monthly_stipend) && $monthly_stipend > 0) { ?>
				<p><strong>Stipend:</strong> <strong class="highlighted"><?= number_format($monthly_stipend) ?> per month</strong></p>
			<?php } ?>
			<br>

			<p class="text-justify">Please note that this is an internship position, and as such, it is designed to provide you with hands-on experience and learning in your field. The internship will last for a period determined by company policy, with the possibility of an extension or permanent employment depending on performance and business needs.</p><br>

			<p class="text-justify">You are expected to adhere to the company's rules, regulations, and policies during the course of your internship. We trust you will make the most of this opportunity and look forward to seeing your contributions to the company.</p><br>

			<p>Thanking You<br>Yours truly,</p><br>
		</div>

		<table style="width: 100%; margin-top: 20px;">
			<tr>
				<td style="width: 50%; vertical-align: top;">
					<p><strong>For <?= $company_name ?></strong></p><br><br>
					<p><strong>Authorized Signatory</strong><br><strong>Praveen Kumar Sinha</strong><br><strong>HR Manager</strong></p>
				</td>
				<td style="width: 50%; text-align: center; vertical-align: top;">
					<p><strong class="highlighted"><?= $employee_name ?></strong><br><strong>Signature</strong></p>
					<!-- <br><br>
					<p><strong>Read, Understood, Agreed, Accepted</strong></p><br>
					<p><strong class="highlighted"><?= $employee_name ?></strong></p> -->
				</td>
			</tr>
		</table>
	</section>

</body>

</html>