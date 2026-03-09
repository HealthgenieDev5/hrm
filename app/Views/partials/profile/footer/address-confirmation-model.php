<div class="modal fade address-modal" id="addressConfirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="probationNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="probationNotificationModalLabel">
                    <i class="fas fa-home me-2"></i>Address Confirmation Required
                </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Please confirm or update your current address. This is required every 6 months as per company policy.
                </div>
                <form id="addressForm" enctype="multipart/form-data">
                    <!-- Address Text -->
                    <div class="mb-4">
                        <label for="address_text" class="form-label required-field">Current Address</label>
                        <textarea class="form-control" id="address_text" name="address_text" rows="4"
                            placeholder="Enter your complete current address..." required></textarea>
                        <div class="error-text" id="address_text_error"></div>
                    </div>

                    <!-- Document Type -->
                    <div class="mb-4">
                        <label for="document_type" class="form-label required-field">Document Type</label>
                        <select class="form-select" id="document_type" name="document_type" required>
                            <option value="">Select document type...</option>
                            <option value="rent_agreement">Rent Agreement</option>
                            <option value="aadhaar">Aadhaar Card</option>
                            <option value="other">Other Address Proof</option>
                        </select>
                        <div class="error-text" id="document_type_error"></div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label class="form-label required-field">Upload Address Document</label>
                        <div class="file-upload-area" id="fileUploadArea">
                            <input type="file" class="form-control" id="address_document" name="address_document"
                                accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                        <div class="error-text" id="address_document_error"></div>
                    </div>

                    <!-- Important Note -->
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> The address entered above must match the address on the uploaded document.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="snoozeBtn">
                    <i class="fas fa-clock me-1"></i>Snooze for 1 Day
                </button>
                <button type="submit" form="addressForm" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-paper-plane me-1"></i>Submit for Review
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Processing your request...</p>
            </div>
        </div>
    </div>
</div>

<?= $this->section('javascript') ?>
<script type="text/javascript">
    $(document).on('click', '#snoozeBtn', function() {
        handleSnooze();
    });

    function checkAddressPopup() {
        $.ajax({
            url: '/address-confirmation/check-popup',
            method: 'GET',
            success: function(response) {
                if (response.show_popup) {
                    showAddressConfirmationModal();
                }
            }
        });
    }

    function showAddressConfirmationModal() {
        $("#addressConfirmationModal").modal('show');
    }

    function handleSnooze() {
        $('#snoozeBtn').prop('disabled', true);

        $.ajax({
            url: '/address-confirmation/snooze',
            method: 'POST',
            success: function(response) {
                if (response.status === 'success') {
                    $("#addressConfirmationModal").modal('hide');
                    alert('Address confirmation snoozed for 1 day');
                }
            },
            error: function() {
                alert('Failed to snooze. Please try again.');
            },
            complete: function() {
                $('#snoozeBtn').prop('disabled', false);
            }
        });
    }

    $('#addressForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '/address-confirmation/submit',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    $('#addressConfirmationModal').modal('hide');
                    alert('Address confirmation submitted successfully');
                } else {
                    alert('Failed to submit the form');
                }
            }
        });
    });

    function snoozePopup() {
        $.ajax({
            url: '/address-confirmation/snooze',
            method: 'POST',
            success: function(response) {
                $('#addressConfirmationModal').modal('hide');
            }
        });
    }
</script>
<?= $this->endSection() ?>