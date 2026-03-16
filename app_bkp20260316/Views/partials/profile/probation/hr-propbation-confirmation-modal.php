<div class="modal fade" id="probationConfirmationModal" tabindex="-1" aria-labelledby="probationConfirmationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="probationConfirmationModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Probation Confirmation Required
                </h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pc-record-id" value="">

                <!-- Employee Details Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Employee Information</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Name:</strong></div>
                            <div class="col-md-8" id="pc-employee-name"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Department:</strong></div>
                            <div class="col-md-8" id="pc-department"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Designation:</strong></div>
                            <div class="col-md-8" id="pc-designation"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Joining Date:</strong></div>
                            <div class="col-md-8" id="pc-joining-date"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Probation Period:</strong></div>
                            <div class="col-md-8" id="pc-probation-period"></div>
                        </div>
                        <!-- <div class="row mb-2">
                            <div class="col-md-4"><strong>Probation End Date:</strong></div>
                            <div class="col-md-8" id="pc-probation-end-date"></div>
                        </div> -->
                    </div>
                </div>

                <!-- Confirmation Details Card -->
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Confirmation Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Confirmed By (HOD):</strong></div>
                            <div class="col-md-8" id="pc-hod-name"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Confirmation Date:</strong></div>
                            <div class="col-md-8" id="pc-confirmation-date"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="pc-remind-later-btn">
                    <i class="bi bi-clock-history me-1"></i>Remind me later
                </button>
                <button type="button" class="btn btn-success" id="pc-confirmed-btn">
                    <i class="bi bi-check-circle me-1"></i>Confirmed
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            checkForHrProbationConfirmations();
        }, 2500);

        function checkForHrProbationConfirmations() {
            $.ajax({
                url: '<?= base_url("/ajax/probation/hr-confirmations") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.confirmations.length > 0) {
                        showHrProbationModal(response.confirmations[0]);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching HR confirmations:', error);
                }
            });
        }

        function showHrProbationModal(confirmation) {
            $('#pc-record-id').val(confirmation.id);
            $('#pc-employee-name').text(confirmation.first_name + ' ' + confirmation.last_name);
            $('#pc-department').text(confirmation.department_name || 'N/A');
            $('#pc-designation').text(confirmation.designation_name || 'N/A');
            $('#pc-joining-date').text(formatDate(confirmation.joining_date));
            $('#pc-probation-period').text(confirmation.probation);
            //$('#pc-probation-end-date').text(calculateProbationEndDate(confirmation.joining_date, confirmation.probation_days));
            $('#pc-hod-name').text(confirmation.hod_first_name + ' ' + confirmation.hod_last_name);
            $('#pc-confirmation-date').text(formatDate(confirmation.date_time));

            $('#probationConfirmationModal').modal('show');
        }

        $('#pc-remind-later-btn').click(function() {
            handleHrAction('remind_later', null);
        });

        $('#pc-confirmed-btn').click(function() {
            Swal.fire({
                title: 'Confirm Probation',
                text: 'Are you sure you want to confirm this probation?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Confirm',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    handleHrAction('confirmed', null);
                }
            });
        });

        function handleHrAction(action, notes) {
            const recordId = $('#pc-record-id').val();
            const $remindBtn = $('#pc-remind-later-btn');
            const $confirmBtn = $('#pc-confirmed-btn');

            const remindBtnHtml = $remindBtn.html();
            const confirmBtnHtml = $confirmBtn.html();

            $remindBtn.prop('disabled', true);
            $confirmBtn.prop('disabled', true);

            if (action === 'remind_later') {
                $remindBtn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...');
            } else {
                $confirmBtn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...');
            }

            $.ajax({
                url: '<?= base_url("/ajax/probation/hr-action") ?>',
                method: 'POST',
                data: {
                    record_id: recordId,
                    action: action,
                    notes: notes
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#probationConfirmationModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        setTimeout(checkForHrProbationConfirmations, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process action. Please try again.'
                    });
                },
                complete: function() {
                    // Restore buttons
                    $remindBtn.html(remindBtnHtml).prop('disabled', false);
                    $confirmBtn.html(confirmBtnHtml).prop('disabled', false);
                }
            });
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB');
        }

        function calculateProbationEndDate(joiningDate, probationDays) {
            if (!joiningDate || !probationDays) return 'N/A';
            const date = new Date(joiningDate);
            date.setDate(date.getDate() + parseInt(probationDays));
            return date.toLocaleDateString('en-GB');
        }
    });
</script>
<?= $this->endSection() ?>