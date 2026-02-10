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

    </footer>

    <section class="page page-1">

        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td>
                    <h3>
                        <p class="text-center "><strong>Extension of Probation Period</strong></p>
                    </h3>


                </td>

            </tr>
            <!-- <tr>
                <td>
                    <p style="text-align: right;">Date: <strong class="highlighted"><?= getDateWithSuffix($currentDate) ?></strong> </p>
                </td>
            </tr> -->
        </table>



        <!-- <hr style="border: none; border-bottom: 1px solid grey; margin: 20px 0px;"> -->

        <div>
            <br><br>
            <p>Date: <strong class="highlighted"><?= getDateWithSuffix($currentDate) ?></strong> </p>


            <br><br>

            <p><strong class="highlighted">Dear <?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong>,</p><br>
            <p class="text-justify">We refer to your appointment with <strong class="highlighted"><?= $company_name ?></strong> dated <strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong>, wherein you were placed on a probation period of 45 days. </p><br>
            <p class="text-justify">Following a review of your performance and overall conduct during this period, it has been observed that certain aspects require improvement to meet the standards expected for your role. In view of this, and in line with company policy, your probation period is hereby extended by a further 45 days, with effect from <strong><?= getDateWithSuffix($newProbationStartDate) ?></strong> to <strong><?= getDateWithSuffix($newProbationEndDate) ?></strong>. </p><br>
            <p class="text-justify">This extension is intended to provide you with an opportunity to strengthen your performance, demonstrate a positive approach, and align with organizational expectations. During this extended period, your performance and conduct will continue to be closely monitored.</p><br>
            <p class="text-justify">We encourage you to make consistent efforts towards improvement and utilize this period constructively. A final decision regarding your confirmation will be communicated at the end of the extended probation period, based on a comprehensive review.</p><br>
            <p class="text-justify">We look forward to your positive contribution.</p><br><br>

            <table style="width: 100%; margin-bottom: 20px;">

                <tr>
                    <td>
                        <p class="text-justify">Best regards,</p><br>
                        <p>
                            <strong>Praveen Kumar Sinha</strong>
                            <br>
                            <br>
                            <strong>HR Manager</strong>



                        </p>
                        <p><strong><?= trim($company_name) ?></strong></p>
                    </td>
                </tr>
            </table>
            <br><br>




        </div>










    </section>

</body>

</html>