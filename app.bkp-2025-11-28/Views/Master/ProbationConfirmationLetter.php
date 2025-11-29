<html>
	<head>
		<meta http-equiv="Content-Type" content="charset=utf-8" />
		<style type="text/css">
			html{
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
			table tr{
				border: 0;
			}
			table td, table th {
				font-size: inherit;
				border: 0;
			}
			table.bordered tr td{
				padding: 0.25rem 0.5rem;
				border: 1px solid grey;
			}
			table.annexure tr td{
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
			p, h1, h2, h3, h4, h5, h6 {
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
			.header-top{
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
			.text-white{
				color: white;
			}
			.text-center{
				text-align: center;
			}
			.text-justify{
				text-align: justify;
			}
			.d-flex{
				display: flex;
			}
			.align-items-center{
				align-items: center;
			}
			.justify-content-center{
				justify-content: center;
			}
			.justify-content-between{
				justify-content: space-between;
			}
			.justify-content-end{
				justify-content: end;
			}
			ol, ul {
				margin-top: 0.5rem;
			}
			ol > li {
				list-style: none;
			}
			ol > li,
			ul > li {
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
			ol.extended-counter > li {
				counter-increment: section;
			}
			ol.extended-counter > li::before {
				content: "1." counter(section) ". ";
				font-weight: bold;
				position: absolute;
				/* left: -30px; */
				left: -2.35rem;
			}
			ol.extended-counter > li > ol.extended-counter {
				counter-reset: subsection;
				/* padding-left: 40px; */
				padding-left: 3.5rem;
				list-style-type: none;
			}
			ol.extended-counter > li > ol.extended-counter > li {
				counter-increment: subsection;
			}
			ol.extended-counter > li > ol.extended-counter > li::before {
				content: "1." counter(section) "." counter(subsection) ". ";
				font-weight: bold;
				position: absolute;
				/* left: -40px; */
				left: -3.5rem;
			}
			ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter {
				counter-reset: subsubsection;
				padding-left: 4.5rem;
				list-style-type: none;
			}
			ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter > li {
				counter-increment: subsubsection;
			}
			ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter > li::before {
				content: "1." counter(section) "." counter(subsection) "." counter(subsubsection) ". ";
				font-weight: bold;
				position: absolute;
				left: -4.5rem;
			}
			ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter {
				counter-reset: subsubsubsection;
				padding-left: 5rem;
				list-style-type: none;
			}
			ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter > li {
				counter-increment: subsubsubsection;
			}
			ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter > li > ol.extended-counter > li::before {
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
			ol.roman > li {
				counter-increment: romanCounter;
			}
			ol.roman > li::before {
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
			ol.lower-alpha-with-bracket > li {
				counter-increment: alphabetCounterWithBracket;
			}
			ol.lower-alpha-with-bracket > li::before {
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
			ol.lower-alpha-without-bracket > li {
				counter-increment: alphabetCounterNoBracket;
			}
			ol.lower-alpha-without-bracket > li::before {
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
			ol.decimal > li {
				counter-increment: decimalCounter;
			}
			ol.decimal > li::before {
				content: counter(decimalCounter) ". ";
				font-weight: bold;
				position: absolute;
				left: -1.5rem;
			}
			.page_break { page-break-before: always; }
			.page-number:before { content: "Page " counter(page); }
			/* .highlighted{ */
				/* background-color: yellow; */
				/* padding: 0 0.25rem; */
			/* } */
		</style>
	</head>
	<body>

		<section class="page page-1">
			<div>
				<br>
				<br>
				<h3 style="text-align: center; font-size: 1.5rem; text-decoration: underline;">Confirmation Letter</h3>
				<br>
				<br>
				<br>
				<p><?= @getDateWithSuffix($confirmation_date) ?></p>
				<br>
				<br>
				<br>
				<p>
					<strong class="highlighted">Dear <?= $employee_name ?></strong>,
				</p>
				<br>
				<br>
				<br>
				<p class="text-justify">We are pleased to inform you that you have successfully completed your probation period with <strong><?= $company_name ?></strong>. and hereby confirmed as a permanent employee effective from <strong><?= @getDateWithSuffix($confirmation_date) ?></strong>.</p>
				<br>
				<p class="text-justify">All the terms of your employment, including benefits and responsibilities, will now be fully applicable as per the company’s policy. We are confident that you will continue to contribute effectively to the success of the company, and we look forward to your continued success and growth within the organization.</p>
				<br>
				<p class="text-justify"><strong>Once again, congratulations on this important milestone in your career with us.</strong></p>
				<br>
				<br>
				<p class="text-justify">Wishing you continued success!</p>
				<br>
				<br>
				<br>
			</div>

			<table style="width: 100%; margin-bottom: 20px;">
				<tr>
					<td>
						<p>
							<strong>Authorized Signatory</strong>
							<br>
							<strong>Praveen Kumar Sinha</strong>
							<br>
							<strong>HR Manager</strong>
						</p>
					</td>
					<td style="vertical-align: top;"></td>
				</tr>
			</table>
		</section>

	</body>
</html>