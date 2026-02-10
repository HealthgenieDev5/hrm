<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Job Opening Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .form-section-title {
            font-weight: bold;
            background-color: #f1f1f1;
            padding: 8px;
            margin-top: 20px;
            border-left: 5px solid #007bff;
        }

        .label {
            font-weight: 500;
            color: #333;
        }

        .value-box {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            min-height: 38px;
        }

        .signature-box {
            height: 60px;
            border: 1px solid #ccc;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Job Opening Form</h2>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="label">Job Name:</div>
                <div class="value-box">{{job_name}}</div>
            </div>
            <div class="col-md-6">
                <div class="label">Date of Job Opening:</div>
                <div class="value-box">{{job_opening_date}}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="label">Type of Job:</div>
                <div class="value-box">{{job_type}}</div>
            </div>
            <div class="col-md-4">
                <div class="label">Salary:</div>
                <div class="value-box">{{salary}}</div>
            </div>
            <div class="col-md-4">
                <div class="label">Experience:</div>
                <div class="value-box">{{experience}}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="label">Interview Location:</div>
                <div class="value-box">{{interview_location}}</div>
            </div>
            <div class="col-md-6">
                <div class="label">Seating Location:</div>
                <div class="value-box">{{seating_location}}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="label">Shift Timing:</div>
                <div class="value-box">{{shift_timing}}</div>
            </div>
            <div class="col-md-6">
                <div class="label">System Required:</div>
                <div class="value-box">{{system_required}}</div>
            </div>
        </div>

        <div class="mb-3">
            <div class="label">Reporting To:</div>
            <div class="value-box">{{reporting_to}}</div>
        </div>

        <div class="mb-3">
            <div class="label">Salient Points:</div>
            <div class="value-box">{{salient_points}}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="label">Educational Qualification:</div>
                <div class="value-box">{{education}}</div>
            </div>
            <div class="col-md-6">
                <div class="label">Technical Test Required:</div>
                <div class="value-box">{{technical_test}}</div>
            </div>
        </div>

        <div class="mb-3">
            <div class="label">Job Description / Requirement:</div>
            <div class="value-box" style="white-space: pre-line;">{{job_description}}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="label">IQ Test Required:</div>
                <div class="value-box">{{iq_test}}</div>
            </div>
            <div class="col-md-3">
                <div class="label">Eng Test Required:</div>
                <div class="value-box">{{eng_test}}</div>
            </div>
            <div class="col-md-3">
                <div class="label">Operation Test Required:</div>
                <div class="value-box">{{operation_test}}</div>
            </div>
            <div class="col-md-3">
                <div class="label">Other Test:</div>
                <div class="value-box">{{other_test}}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="label">No. of Vacancy:</div>
                <div class="value-box">{{vacancy_count}}</div>
            </div>
            <div class="col-md-8">
                <div class="label">Any Specific Industry:</div>
                <div class="value-box">{{industry}}</div>
            </div>
        </div>

        <div class="form-section-title">KRA & Review</div>
        <p>If job type is new, attach distribution of KRA's (Review Frequency)</p>
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="label">Reviewed at 3 months by:</div>
                <div class="value-box">{{review_3_months}}</div>
            </div>
            <div class="col-md-4">
                <div class="label">Reviewed at 6 months by:</div>
                <div class="value-box">{{review_6_months}}</div>
            </div>
            <div class="col-md-4">
                <div class="label">Reviewed at 12 months by:</div>
                <div class="value-box">{{review_12_months}}</div>
            </div>
        </div>

        <div class="form-section-title">Signatures</div>
        <div class="row text-center mb-3">
            <div class="col-md-3">
                <div class="label">Requisition Sign</div>
                <div class="signature-box"></div>
                <div>{{sign_date_requisition}}</div>
            </div>
            <div class="col-md-3">
                <div class="label">HR Executive</div>
                <div class="signature-box"></div>
                <div>{{sign_date_hr_exec}}</div>
            </div>
            <div class="col-md-3">
                <div class="label">HR Head</div>
                <div class="signature-box"></div>
                <div>{{sign_date_hr_head}}</div>
            </div>
            <div class="col-md-3">
                <div class="label">Plant Head</div>
                <div class="signature-box"></div>
                <div>{{sign_date_plant_head}}</div>
            </div>
        </div>
    </div>
</body>

</html>