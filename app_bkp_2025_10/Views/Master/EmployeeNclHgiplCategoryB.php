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
			font-size: 0.90rem;
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

		ol>li,
		ul>li {
			margin-bottom: 0.35rem;
			position: relative;
			text-align: justify;
		}

		.page_break {
			page-break-before: always;
		}

		.page-number:before {
			content: "Page " counter(page);
		}

		/*.highlighted {
			background-color: yellow;
		}*/

		strong.competitor-category {
			padding: 2px 5px;
			/* font-size: 0.7rem; */
			/* border: 1px dotted rgba(0, 0, 0, 0.45); */
			/* border-radius: 8px; */
			display: inline-block;
			margin-right: 5px;
			margin-bottom: 5px;
		}

		small.competitor-category {
			padding: 2px 5px;
			font-size: 0.7rem;
			border: 1px dotted rgba(0, 0, 0, 0.45);
			border-radius: 8px;
			display: inline-block;
			margin-right: 5px;
			margin-bottom: 5px;
		}
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
					<p style="text-align: right;">Date: <strong class="highlighted"><?= getDateWithSuffix($non_compete_loan_from) ?></strong> </p>
				</td>
			</tr>
		</table>

		<h3 style="text-align: center; width: 100%; margin-bottom: 20px;">Subject : Non Compete Incentive agreement</h3>

		<div>
			<p><strong class="highlighted">Dear <?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong>,</p><br>

			<p class="text-justify">You are important and critical to the business of M/s <strong class="highlighted"><?= $company_name ?></strong>. During your service with M/s <strong class="highlighted"><?= $company_name ?></strong>, you have and would be privy to the detailed optimized processes for the business operations of M/s <strong class="highlighted"><?= $company_name ?></strong> in terms of E- Retail, marketing, exports, product purchase, logistics, shipping, product introduction, product development and achieving sales / overall management and in addition to the same a lot of resources of the Company has been utilized on you.</p><br>

			<p class="text-justify">In addition to the above, the Employee may also be deputized in the M/s <strong class="highlighted"><?= $company_name ?></strong> for the work assigned or for gaining knowledge, at that time.</p><br>

			<p class="text-justify">In order to secure company’s confidential data, intellectual properties, research and safeguard company’s interest and to honor the loyalty of employees, who do not compete directly or indirectly with the company’s business, the company is introducing a Non-Compete Incentive.</p><br>

			<p class="text-justify">We believe that you are a loyal employee of ours and agree to the Non-Compete Terms as mentioned below. Thus we are pleased to add a Non Compete Incentive i.e. Rs <strong class="highlighted"><?= $non_compete_loan_amount_per_month ?></strong> per month, from <strong class="highlighted"><?= getDateWithSuffix($non_compete_loan_from) ?></strong>.</p><br>

			<p class="text-justify">Non Compete Incentive is a special loan automatically convertible into an Incentive payout, on the successful completion of the agreement. This is being offered to you, so that we can share critical and confidential information with you, so that you can grow and the company can grow and later you do not compete directly or indirectly with the company, neither by your own business/consultancy nor by joining any competitor company of <strong class="highlighted"><?= $company_name ?></strong> for next at least 24 months from the last date of your employment/ association with the Company.</p><br>

			<div class="page_break"></div>

			<p class="text-justify">The Incentive is being given to the employee on successful completion of below terms and conditions.</p><br>

			<p class="text-justify">Following are terms and conditions of the Non Compete Incentive.</p>
			<ol class="extended-counter">
				<li>
					That, you shall not compete directly or indirectly with the company neither by your own business/consultancy nor by joining any competitor company of <strong class="highlighted"><?= $company_name ?></strong> for export business, for next 24 months from the last date of your employment/ association with <strong class="highlighted"><?= $company_name ?></strong>.
				</li>
				<li>
					That, For a period of 24 months following your departure from <strong class="highlighted"><?= $company_name ?></strong>, you shall refrain from entering into employment, ownership, advisory, or consultancy roles with any of the entities specified in the list of competitors for <strong class="highlighted"><?= $company_name ?></strong>, or any other company engaged in similar product categories as outlined in 'Annexure-A,' in which the company is currently involved or has prospective interests
				</li>
				<li>
					That you shall not work with any vendor, supplier, prospective supplier, buyer, vendor or agents associated with <strong class="highlighted"><?= $company_name ?></strong> India Private Limited or its associate or sister companies for a period of 24 months, post your exit from <strong class="highlighted"><?= $company_name ?></strong>. Definition of Prospective Supplier/Vendor/Agent/Buyer is who have made any correspondence for sampling, quotation, purchase, with <strong class="highlighted"><?= $company_name ?></strong> in the last 5 years.
				</li>
				<li>
					That, you, shall not sell or start your own export business into the similar product categories as mentioned in “Annexure-A”, for a period of 48 months, post your exit from the company.
				</li>
				<li>
					That neither in present nor within 24 months, of your exit from the company, <strong class="highlighted"><?= $company_name ?></strong>, any family member of yours, that may be your spouse, parents, siblings, shall not have any association / business relations with any of the listed competing companies as mentioned in “Annexure-A”, existing supplier/s, associates, clients of the company, nor they may directly or indirectly sell any such similar product categories, as mentioned in Annexure-A, or may help or advise or assist to any person or a business entity in selling such similar product categories as mentioned in “Annexure-A”, in which the company is presently involved or have prospects.
				</li>
				<li>
					That, the above Non compete clauses are applicable for Export business only and not applicable to domestic business. Trading, sale to an exporter, merchant exporter is also not allowed.
				</li>
				<li>
					That if you join any of these firms, or associate with them, as an employee, adviser, consultant or try to circumvent, through your spouse/parents/siblings etc, then this Incentive, will cease to exist and the loan amount received by you from the Company, you shall be liable to pay back same to the Company along with interest at the rate of 1.5% per month compounded monthly.
				</li>
				<li>
					That you shall pay back the entire amount, paid to you in the name of Non-Compete Incentive, within 15 days of deviation observed from terms and conditions.
				</li>
				<li>
					That you further agree that the said repayment of the amount would be made by you voluntarily and in-case you do not repay this amount, then you further agree to pay additional penalty of Rs 1000 per day along with 1.5% interest monthly, compounded monthly.
				</li>
				<li>
					That if you do not return this money immediately, post your association with the competing company or breach of any of above mentioned clause, it shall be construed as cheating and fraud.
				</li>
				<li>
					That, the list of the companies, in “Annexure-A”, as example, may be modified with time and the applicable list to you, would be considered as the last updated list, before your exit with <strong class="highlighted"><?= $company_name ?></strong> and similar products and covers there associated companies.
				</li>
				<li>
					That, the Non compete Incentive for any month, will be calculated as below. It will be a fixed Incentive, for an employee and would be paid to him, if his/her attendance is greater than or equal to 18 working days in a month. Employee, whose attendance is less than 18 working days and more than 12 days in a month, will get 50% of the amount, employee with less than and equal to 12 working days attendance do not get any amount. The Incentive is not related to the working hours of the employee. This convertible incentive does not constitutes, as a part of salary.
				</li>
				<li>
					That, at any time, if you do not agree with the terms of Non compete Incentive, you need to provide a written information of the same to HR Team. In such condition, the Non-Compete Incentive, being paid to you, would be stopped.
				</li>
				<li>
					Post the period of 24 months, you are requested to share your bank statements and letter of appointment of organization worked in, so as to verify the credentials and the same shall be communicated in written to convert the loan to incentive, closing any or all liability of the employee.
				</li>
				<li>
					That, the non compete incentive would not be more than 40% of the total salary plus incentives upto year 1 from the date of joining, not more than 30% of the total salary plus incentives, for tenure between 1st year and 2nd year from the date of joining and not more than 25% of the total salary plus incentives for employees with tenure between 2nd & 3rd year from the date of joining and onwards.
				</li>
				<li>
					That, It is the duty and responsibility of the employee to inform about the terms and conditions of this Non compete agreement/Incentive, to the new/future employer, upon their exit from <strong class="highlighted"><?= $company_name ?></strong>.
				</li>
				<li>
					That,the future/new employer would also come under the purview of the agreement and shall have to immediately terminate his/her employment and stop any and all communication with the person.
				</li>
				<li>
					The Jurisdiction will be of Delhi Courts only.
				</li>

			</ol>
			<br>
			<br>

			<p class="text-justify"><strong>Acceptance</strong></p><br>
			<p class="text-justify">I, <?= $employee_name ?> have read, understood the terms and conditions mentioned for Non compete Incentive agreement, and hereby signify my acceptance for the same.</p><br>

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

			<div class="page_break"></div>

			<h5 style="text-align: center; width: 100%; margin-bottom: 20px;">Annexure-A</h5>
			<p style="display: flex; gap: 8px; flex-wrap: wrap; align-items: baseline;">
				<strong class="highlighted competitor-category">Product Categories comprises of : </strong>
				<small class="competitor-category">Surgical and Medical devices like.</small>
				<small class="competitor-category">Orthopaedic Implants.</small>
				<small class="competitor-category">Orthopaedic Aids.</small>
				<small class="competitor-category">Surgical Instruments.</small>
				<small class="competitor-category">Autoclave.</small>
				<small class="competitor-category">Diagnostics.</small>
				<small class="competitor-category">Devices for home healthcare.</small>
				<small class="competitor-category">Medical Disposable.</small>
				<small class="competitor-category">Medical Consumables.</small>
				<small class="competitor-category">Lab Chemicals.</small>
				<small class="competitor-category">Hospital Garments.</small>
				<small class="competitor-category">School & College Teaching Aids.</small>
				<small class="competitor-category">Laboratory Products.</small>
				<small class="competitor-category">Health & Personal care.</small>
				<small class="competitor-category">Home & Kitchen.</small>
				<small class="competitor-category">Baby Products.</small>
				<small class="competitor-category">Homecare products.</small>
				<small class="competitor-category">Nutrition Products.</small>
				<small class="competitor-category">Supplements.</small>
				<small class="competitor-category">Nuetraceuticals.</small>
				<small class="competitor-category">Sports & Fitness Products.</small>
				<small class="competitor-category">Grocery & Gourmet Food.</small>
				<small class="competitor-category">Tea & Coffee.</small>
				<small class="competitor-category">Medical Devices.</small>
				<small class="competitor-category">Hospital Products.</small>
			</p>
			<p>
				<small style="font-size: 0.7rem;">All Online/E-Commerce sellers, selling in above mentioned Product Categories are not to be compete with.</small>
			</p><br>

			<table class="annexure" style="width: 100%;">
				<tr>
					<td>1</td>
					<td>Aditya Dispomed Products Pvt. Ltd.</td>
					<td>30</td>
					<td>United Poly Engineering Pvt Ltd.</td>
				</tr>
				<tr>
					<td>2</td>
					<td>AOV International</td>
					<td>31</td>
					<td>Wellmed International Industries Pvt Ltd.</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Apothecaries Sundries Manufacturing Co (ASCO)</td>
					<td>32</td>
					<td>Quality Needles Pvt Ltd</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Arihant Biopharma Corporation</td>
					<td>33</td>
					<td>RFB LATEX Ltd.</td>
				</tr>
				<tr>
					<td>5</td>
					<td>BI Life Sciences Pvt Ltd.</td>
					<td>34</td>
					<td>Sharda Healthcare Pvt Ltd</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Bio-Med International Pvt Ltd</td>
					<td>35</td>
					<td>Shagun Cares Inc Internal Fixation Systems</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Blue Neem Medical devices Pvt Ltd</td>
					<td>36</td>
					<td>Sharma Orthopedic (India) Pvt Ltd</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Deluxe Scientific Surgico Pvt Ltd</td>
					<td>37</td>
					<td>Shiv Dayal Sud & Sons</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Disposafe Health And Life Care Limited</td>
					<td>38</td>
					<td>Siora Surgical Pvt Ltd</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Global Medikit Ltd</td>
					<td>39</td>
					<td>Sisco Latex Pvt Ltd</td>
				</tr>
				<tr>
					<td>11</td>
					<td>GPC Medical Ltd.</td>
					<td>40</td>
					<td>SURU International Pvt Ltd</td>
				</tr>
				<tr>
					<td>12</td>
					<td>Green Surgical Pvt Ltd</td>
					<td>41</td>
					<td>Sutures India Pvt Ltd</td>
				</tr>
				<tr>
					<td>13</td>
					<td>Hardik International</td>
					<td>42</td>
					<td>Tarsons Products Private Limited</td>
				</tr>
				<tr>
					<td>14</td>
					<td>Harsoria Healthcare Pvt. Ltd.</td>
					<td>43</td>
					<td>Udaipur Healthcare Pvt Ltd.</td>
				</tr>
				<tr>
					<td>15</td>
					<td>Hindustan Syringes & Medical Devices Ltd.</td>
					<td>44</td>
					<td>Safe Shield Rubber Products (P) Ltd</td>
				</tr>
				<tr>
					<td>16</td>
					<td>Hospital Equipment Manufacturing Co.Ltd</td>
					<td>45</td>
					<td>Samay Surgical Pvt Ltd</td>
				</tr>
				<tr>
					<td>17</td>
					<td>J.Mitra & Co Pvt Ltd</td>
					<td>46</td>
					<td>Sceptre Medical Pvt Ltd.</td>
				</tr>
				<tr>
					<td>18</td>
					<td>Jambu Pershad & Sons</td>
					<td>47</td>
					<td>Medical Engineers India Ltd</td>
				</tr>
				<tr>
					<td>19</td>
					<td>Kay & Company</td>
					<td>48</td>
					<td>Medico Electrodes International Ltd</td>
				</tr>
				<tr>
					<td>20</td>
					<td>La-med Healthcare Pvt. Ltd</td>
					<td>49</td>
					<td>Mediplus (India)</td>
				</tr>
				<tr>
					<td>21</td>
					<td>Lars Medicare Pvt Ltd</td>
					<td>50</td>
					<td>Medisafe International</td>
				</tr>
				<tr>
					<td>22</td>
					<td>Mais India Medical Devices Pvt Ltd.</td>
					<td>51</td>
					<td>Medisafe Global Solutions</td>
				</tr>
				<tr>
					<td>23</td>
					<td>Mecmaan Healthcare Pvt. Ltd.</td>
					<td>52</td>
					<td>Narang Medical Ltd</td>
				</tr>
				<tr>
					<td>24</td>
					<td>Phoenix Medical System Pvt Ltd.</td>
					<td>53</td>
					<td>Narula Exports</td>
				</tr>
				<tr>
					<td>25</td>
					<td>Poly Medicure Ltd</td>
					<td>54</td>
					<td>Narula Udyog India Pvt Ltd</td>
				</tr>
				<tr>
					<td>26</td>
					<td>Polybond India Pvt Ltd.</td>
					<td>55</td>
					<td>Newtech Medical Devices</td>
				</tr>
				<tr>
					<td>27</td>
					<td>Primus Gloves Pvt Ltd.</td>
					<td>56</td>
					<td>Orthocare & Cure (I)</td>
				</tr>
				<tr>
					<td>28</td>
					<td>Ribbel International Ltd.</td>
					<td>57</td>
					<td>Paramount Surgimed Ltd.</td>
				</tr>
				<tr>
					<td>29</td>
					<td>Romsons International</td>
					<td></td>
					<td></td>
				</tr>
			</table>

			<p><small style="font-size: 0.7rem;">Example to name a few Organisation/Brands, as a reference only, cover some of the above category, but not all.</small></p>
			<p><small style="font-size: 0.7rem;">Example to name a few organisation, as a reference only, cover some of the above category, but not all.</small></p>
			<p><small style="font-size: 0.7rem;">There could be existing, newer organisation/startup in similar field, they all are automatically part of the reference list, not to compete with.</small></p>

		</div>

	</section>

</body>

</html>