<html>

<head>
	<meta http-equiv="Content-Type" content="charset=utf-8" />
	<style type="text/css">
		html {
			/* font-size: 12px; */
			/* font-size: 16px; */
			font-size: 14px;
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

		/* .highlighted {
			background-color: yellow;
		} */
	</style>
</head>

<body>

	<footer class="d-flex align-items-center" style="padding: 0cm 2cm; height: 4cm">
		<table style="width: 100%;">
			<tr>
				<td style="vertical-align: bottom; text-align: left; width: 50%;"><small class="page-number" style="color: #353535; font-size:0.8rem;"></small></td>
				<td style="vertical-align: middle; text-align: right; width: 50%;">
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
					<p><strong class="highlighted" style="padding-right:1.5 rem;">Designation: <?= $designation_name ?></strong></p>
				</td>
				<td style="vertical-align: top; width:30%">
					<p style="text-align: right;">Date: <strong class="highlighted"><?= getDateWithSuffix($loyalty_incentive_from) ?></strong> </p>
				</td>
			</tr>
		</table>

		<h3 style="text-align: center; width: 100%; margin-bottom: 20px;">Subject: Agreement Letter</h3>

		<div>
			<p><strong class="highlighted">Dear <?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong>,</p><br>
			<p class="text-justify">As per the term & conditions of your appointment with the company you agreed upon, we offer you the Loyalty incentive, particulars are given below:</p><br>

			<p class="text-justify">Loyalty Incentive of Rs. <strong class="highlighted"><?= $loyalty_incentive_amount_per_month ?> ( <?= AmountInWords($loyalty_incentive_amount_per_month) ?>)</strong> per month w.e.f. <strong class="highlighted"><?= getDateWithSuffix($loyalty_incentive_from) ?>.</strong> <strong class="highlighted">1<sup>st</sup></strong> to <strong class="highlighted"><?= $loyalty_incentive_mature_after_month ?><sup>th</sup> month</strong> payable on <strong class="highlighted"><?= ($loyalty_incentive_mature_after_month + $loyalty_incentive_pay_after_month) ?><sup>th</sup> month</strong> & so on.</p><br>

			<p class="text-justify">As you understand that the Incentive which is being offered to you, is beyond the company norms. You have assured that you will continue to work with the company for a minimum period of <strong class="highlighted"><?= $loyalty_incentive_years ?></strong> from the date of <strong class="highlighted"><?= getDateWithSuffix($loyalty_incentive_from) ?></strong> Loyalty Incentive applicable on. The company has accepted your offer and you shall continue & be liable to work satisfactorily with the company for a minimum period of <strong class="highlighted"><?= $loyalty_incentive_years ?></strong> from the date of <strong class="highlighted"><?= getDateWithSuffix($loyalty_incentive_from) ?></strong> Loyalty Incentive applicable on. However, in case you discontinue your services with the company, before completion of <strong class="highlighted"><?= $loyalty_incentive_years ?></strong> from the date of <strong class="highlighted"><?= getDateWithSuffix($loyalty_incentive_from) ?></strong>, due to whatsoever reason, you shall agree to pay a sum of amount to the company as compensation being an amount you received as an Incentive From <strong class="highlighted"><?= getDateWithSuffix($loyalty_incentive_from) ?></strong> to last month of your working with the company, which is a reasonable estimate of actual damages and shall constitute liquidated damages to which the company is entitled to upon the breach of this clause. the company can adjust & recover the incentive amount from your Salary & other dues with the company at the time of separation or may recover by any other legal way.</p><br>

			<p class="text-justify">All other terms and conditions of employment with the company will remain the same.</p><br>

			<p class="text-justify">Best Wishes</p><br>
			<p class="text-justify">For <strong class="highlighted">M/S <?= $company_name ?></strong></p><br>
			<p class="text-justify"><strong class="highlighted">Praveen Kumar Sinha</strong></p>
			<p class="text-justify"><strong class="highlighted">Manager-Human Resource</strong></p><br>
			<p class="text-justify"><strong class="highlighted">Acceptance</strong></p><br>
			<p class="text-justify">I, <?= $employee_name ?> have read, and understood & agreed on the terms and conditions mentioned in the agreement letter, and hereby signify my acceptance of the same.</p><br>

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

	</section>

</body>

</html>