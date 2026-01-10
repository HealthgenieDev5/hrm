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
    <?php if (empty($date_of_leaving) || $date_of_leaving == '0000-00-00') : ?>
        <div style="color: red; border: 2px solid red; padding: 20px; margin-top: 50px; text-align: center; font-family: Arial, sans-serif;">
            <h1 style="margin-bottom: 15px;">Warning!</h1>
            <p>The 'Date of Leaving' has not been set for this employee.</p>
            <p>Please go back to the employee's profile, set the leaving date, and then regenerate this letter.</p>
        </div>
    <?php else : ?>
        <section class="page page-1">

            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td style="width:70%">
                        <h3><strong class="highlighted"><?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong></h3>
                        <p><strong class="highlighted"><?= ($gender == 'female' ? 'D/O. ' : 'S/O. ') . $fathers_name ?></strong></p>
                        <p><strong class="highlighted" style="padding-right:1.5 rem;"><?= $permanent_address ?></strong></p>
                    </td>
                    <td style="vertical-align: top; width:30%">
                        <p style="text-align: right;">Date: <strong class="highlighted"><?= getDateWithSuffix($date_of_leaving) ?></strong> </p>
                    </td>
                </tr>
            </table>



            <hr style="border: none; border-bottom: 1px solid grey; margin: 20px 0px;">

            <div>
                <br><br>
                <p class="text-justify h3"><strong>Subject: Termination of Employment During Probation Period</strong></p>
                <br><br>
                <p><strong class="highlighted">Dear <?= ($gender == 'female' ? 'Ms. ' : 'Mr. ') . $employee_name ?></strong>,</p><br>
                <p class="text-justify">This is to inform you that your employment with <strong class="highlighted"><?= $company_name ?></strong> which commenced on <strong class="highlighted"><?= getDateWithSuffix($joining_date) ?></strong>, was subject to the successful completion of the probationary period, as specified in your appointment letter. </p><br>
                <p class="text-justify">During the probationary period, your performance, conduct, and overall suitability for the role were regularly evaluated. Despite multiple instances of guidance and support extended to help you meet the required performance standards, it has been observed that your overall performance has not met the expectations set for the role. </p><br>
                <p class="text-justify">In view of the above, and in accordance with the terms outlined in your appointment letter, the management has decided to terminate your employment with immediate effect, on grounds of unsatisfactory performance during the probation period. As this termination is being effected during probation due to non-suitability, no notice period or notice pay shall be applicable.</p><br><br><br>



            </div>

            <table style="width: 100%; margin-bottom: 40px;">
                <tr>
                    <td>
                        <p><strong class="highlighted">For & on behalf of </strong><br><strong><?= trim($company_name) ?></strong></p>
                    </td>


                </tr>

            </table>

            <table style="width: 100%; margin-bottom: 20px;">

                <tr>
                    <td>
                        <p>
                            <strong>Authorized Signatory</strong>
                            <br>
                            <br>
                            <strong>Praveen Kumar Sinha</strong>
                            <br>
                            <strong>HR Manager</strong>
                        </p>
                    </td>
                </tr>
            </table>







        </section>
    <?php endif; ?>

</body>

</html>