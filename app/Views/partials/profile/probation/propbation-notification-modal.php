<div class="modal fade" id="probationNotificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="probationNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="probationNotificationModalLabel">
                    🎉 Probation Completion 🎉
                </h5>
            </div>
            <div class="modal-body">
                <div class="celebration-icon">🎊</div>
                <p><strong>Congratulations!</strong><br> You are now a confirmed employee of <strong><?= $current_user_data['company_name'] ?></strong>.</p>
                <p class="text-muted">Please collect your confirmation letter from the HR Department.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-success" id="acknowledgeProbationBtn">
                    <i class="fa-solid fa-check"></i> Acknowledge
                </button>
            </div>
        </div>
    </div>
</div>


<?= $this->section('javascript') ?>




<script>
    $(document).ready(function() {

        // Check for probation completion on page load
        $.ajax({
            url: "<?= base_url('/ajax/profile/probation-completed-notification') ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === 'show_modal') {
                    var myModal = new bootstrap.Modal(document.getElementById('probationNotificationModal'), {});
                    myModal.show();
                }
            }
        });

        // Handle acknowledgment
        $('#acknowledgeProbationBtn').on('click', function() {
            $.ajax({
                url: "<?= base_url('/ajax/profile/acknowledge-probation') ?>",
                type: "POST",
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        var myModal = bootstrap.Modal.getInstance(document.getElementById('probationNotificationModal'));
                        myModal.hide();
                        Swal.fire('Success', 'Probation status acknowledged.', 'success');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>