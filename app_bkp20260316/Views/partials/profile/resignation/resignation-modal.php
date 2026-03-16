<!-- ==================== RESIGNATION HOD ACKNOWLEDGMENT MODALS ==================== -->
<?php
// HOD Resignation Acknowledgment Modal (Glass-Card Design)
if (!empty($resignationHodAcknowledgments)) {
?>
    <script type="text/javascript">
        // ── Modal queue — prevents multiple Bootstrap modals stacking ──
        window._modalQueue = window._modalQueue || [];
        window._modalQueueRunning = window._modalQueueRunning || false;

        function enqueueModal(showFn) {
            window._modalQueue.push(showFn);
            if (!window._modalQueueRunning) _processModalQueue();
        }

        function _processModalQueue() {
            if (window._modalQueue.length === 0) {
                window._modalQueueRunning = false;
                return;
            }
            window._modalQueueRunning = true;
            if ($('body').hasClass('modal-open')) {
                $(document).one('hidden.bs.modal', function() {
                    setTimeout(_processModalQueue, 400);
                });
            } else {
                var fn = window._modalQueue.shift();
                fn();
            }
        }

        $(document).ready(function() {
            const resignations = JSON.parse('<?php echo json_encode($resignationHodAcknowledgments); ?>');
            let currentIndex = 0;

            function showHodResignationModal(index) {
                if (index >= resignations.length) return;

                const r = resignations[index];
                $('#hod-record-id').val(r.id);

                // ── Left panel ──
                if (r.employee_image) {
                    $('#hod-emp-img').attr('src', r.employee_image);
                    $('#hod-photo-wrapper').show();
                    $('#hod-emoji-fallback').hide();
                } else {
                    $('#hod-photo-wrapper').hide();
                    $('#hod-emoji-fallback').show();
                }
                $('#hod-first-name').text(r.first_name || r.employee_name);

                // ── Right panel ──
                $('#hod-employee-name').text(r.employee_name + ' (' + r.internal_employee_id + ')');
                $('#hod-designation').text(r.designation_name || '');
                $('#hod-department').text(r.department_name || '');
                $('#hod-reason').text(r.resignation_reason || 'No reason provided');
                $('#hod-resignation-date').text(r.resignation_date_formatted);
                $('#hod-last-working-date').text(r.last_working_date_formatted);
                $('#hod-company').text(r.company_name || 'N/A');
                $('#hod-remaining-days').text(r.remaining_days + ' days');

                // Manager's response
                const managerResp = r.manager_response;
                const mgrLabels = {
                    accept: 'Accepted',
                    rejected: 'Rejected',
                    try_to_retain: 'Want to Retain',
                    too_early: 'Remind Later'
                };
                if (managerResp && managerResp !== 'pending') {
                    const $mgrBadge = $('#hod-manager-response-badge');
                    $mgrBadge.text(mgrLabels[managerResp] || managerResp);
                    $mgrBadge.removeClass('bg-success bg-danger bg-info bg-secondary text-dark');
                    if (managerResp === 'accept') $mgrBadge.addClass('bg-success');
                    else if (managerResp === 'rejected') $mgrBadge.addClass('bg-danger');
                    else if (managerResp === 'try_to_retain') $mgrBadge.addClass('bg-info');
                    else $mgrBadge.addClass('bg-secondary');

                    $('#hod-manager-peer-name').text(r.manager_name || 'Manager');
                    if (r.manager_remarks) {
                        $('#hod-manager-peer-remarks').text(r.manager_remarks);
                        $('#hod-manager-peer-remarks-row').show();
                    } else {
                        $('#hod-manager-peer-remarks-row').hide();
                    }
                    $('#hod-manager-peer-section').show();
                } else {
                    $('#hod-manager-peer-section').hide();
                }

                // Counter badge
                if (resignations.length > 1) {
                    $('#hod-counter').text((index + 1) + ' of ' + resignations.length).show();
                } else {
                    $('#hod-counter').hide();
                }

                // Urgent badge
                if (r.is_urgent) {
                    $('#hod-urgent-badge').show();
                } else {
                    $('#hod-urgent-badge').hide();
                }

                // Reset action form
                $('#hod-action-select').val('');
                $('#hod-rejection-reason-container').hide();
                $('#hod-rejection-reason').val('').attr('placeholder', 'Remarks');
                $('#hod-action-error').hide();
                $('#hod-rejection-error').hide();

                // Toggle normal vs acknowledge-only mode based on manager's response
                if (managerResp === 'try_to_retain') {
                    $('#hod-normal-action-area').hide();
                    $('#hod-acknowledge-area').show();
                } else {
                    $('#hod-normal-action-area').show();
                    $('#hod-acknowledge-area').hide();
                }

                // Hide "Remind Me Later" after 20 days from resignation date
                var hodDaysSinceResignation = Math.floor((new Date() - new Date(r.created_at)) / 86400000);
                $('#hod-action-select option[value="too_early"]').toggle(hodDaysSinceResignation <= 20);

                $('#hodResignationAckModal').modal('show');
            }

            // Show first resignation
            enqueueModal(function() {
                showHodResignationModal(0);
            });

            // Dynamic placeholder + show/hide remarks based on action
            $('#hod-action-select').on('change', function() {
                if ($(this).val() && $(this).val() !== 'too_early') {
                    const placeholders = {
                        accept: 'Remarks (optional)',
                        try_to_retain: 'Remarks — why retain? (optional)',
                        reject: 'Rejection reason (optional)',
                    };
                    $('#hod-rejection-reason').attr('placeholder', placeholders[$(this).val()] || 'Remarks');
                    $('#hod-rejection-reason-container').slideDown();
                } else {
                    $('#hod-rejection-reason-container').slideUp();
                    $('#hod-rejection-reason').val('');
                }
                $('#hod-action-error').hide();
                $('#hod-rejection-error').hide();
            });

            // Acknowledge button — delegate to existing submit flow
            $('#hod-acknowledge-btn').on('click', function() {
                $('#hod-action-select').val('acknowledge');
                $('#hod-submit-btn').trigger('click');
            });

            // Submit button
            $('#hod-submit-btn').on('click', function() {
                const recordId = $('#hod-record-id').val();
                const action = $('#hod-action-select').val();
                const rejectionReason = $('#hod-rejection-reason').val();

                $('#hod-action-error').hide();
                $('#hod-rejection-error').hide();

                if (!action) {
                    $('#hod-action-error').show();
                    return;
                }

                const $btn = $(this);
                const btnHtml = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Processing…');

                const responses = {};
                responses[recordId] = {
                    action: action,
                    rejection_reason: rejectionReason || null
                };

                $.ajax({
                    method: 'POST',
                    url: '<?php echo base_url('/ajax/resignation/save-hod-response'); ?>',
                    data: {
                        responses: responses
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.response_type === 'success') {
                            $('#hodResignationAckModal').modal('hide');
                            currentIndex++;
                            if (currentIndex < resignations.length) {
                                setTimeout(function() {
                                    showHodResignationModal(currentIndex);
                                }, 500);
                            } else if (action === 'too_early') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Noted',
                                    text: "You'll be reminded tomorrow.",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'All Done!',
                                    text: 'All resignation responses saved.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            }
                        } else {
                            Swal.fire('Error', response.response_description, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to save response. Please try again.'
                        });
                    },
                    complete: function() {
                        $btn.html(btnHtml).prop('disabled', false);
                    }
                });
            });
        });
    </script>

    <!-- HOD Resignation Acknowledgment Modal (Glass-Card Design) -->
    <div class="modal fade" id="hodResignationAckModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered notif-dialog">
            <div class="modal-content notif-glass-card">
                <input type="hidden" id="hod-record-id">
                <div class="row g-0 notif-card-row">

                    <!-- ── LEFT PANEL ── -->
                    <div class="col-5 d-flex flex-column align-items-center justify-content-center text-center notif-left-panel"
                        style="background: linear-gradient(160deg, #fff1f2 0%, #ffe4e6 100%); border-right: 1px solid #fecdd3;">

                        <!-- Photo with glow halo -->
                        <div id="hod-photo-wrapper" class="notif-photo-wrapper">
                            <div class="notif-photo-halo" style="background: linear-gradient(135deg, #f43f5e, #fb923c, #fbbf24); opacity: 0.22;"></div>
                            <div class="notif-photo-frame">
                                <img id="hod-emp-img" src="" alt="Employee" class="notif-emp-img">
                            </div>
                            <div class="notif-float-badge">
                                <i class="bi bi-envelope-open" style="color: #e11d48;"></i>
                            </div>
                        </div>

                        <!-- Emoji fallback -->
                        <div id="hod-emoji-fallback" class="notif-emoji-fallback" style="display: none;">📋</div>

                        <!-- "Notice of / Resignation" text -->
                        <div class="notif-event-text mt-2">
                            <div class="notif-label-top">Notice of</div>
                            <div class="notif-styled-event"
                                style="background: linear-gradient(135deg, #e11d48 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                Resignation
                            </div>
                        </div>

                        <p class="notif-celebrating">
                            Submitted by <strong id="hod-first-name"></strong>
                        </p>
                    </div>

                    <!-- ── RIGHT PANEL ── -->
                    <div class="col-7 d-flex flex-column justify-content-between notif-right-panel">
                        <div>
                            <!-- Type badge + counter + urgent -->
                            <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                                <span class="notif-type-badge"
                                    style="background: rgba(225,29,72,0.08); color: #be123c; border: 1px solid rgba(225,29,72,0.2);">
                                    <i class="bi bi-file-earmark-text me-1"></i>RESIGNATION NOTICE
                                </span>
                                <span id="hod-counter" class="badge bg-secondary" style="font-size: 0.7rem;"></span>
                                <span id="hod-urgent-badge" class="badge bg-danger" style="display: none; font-size: 0.7rem;">URGENT</span>
                            </div>

                            <!-- Employee name -->
                            <h4 id="hod-employee-name" class="notif-name-title mb-1"></h4>

                            <!-- Designation · Department -->
                            <div class="notif-subtitle mb-3">
                                <strong id="hod-designation"></strong>
                                <span class="notif-subtitle-dot"></span>
                                <span id="hod-department"></span>
                            </div>

                            <!-- Resignation reason -->
                            <div id="hod-reason" class="notif-description mb-3"></div>

                            <!-- Info grid -->
                            <div class="row g-0 notif-contact-grid">
                                <div class="col-6 notif-contact-item">
                                    <p class="notif-contact-label">Resignation Date</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-calendar-event"></i><span id="hod-resignation-date"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item">
                                    <p class="notif-contact-label">Last Working Day</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-calendar-check"></i><span id="hod-last-working-date"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item">
                                    <p class="notif-contact-label">Company</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-building"></i><span id="hod-company"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item">
                                    <p class="notif-contact-label">Days Remaining</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-hourglass-split"></i><span id="hod-remaining-days"></span>
                                    </div>
                                </div>
                                <!-- Manager's response (shown only when manager has responded) -->
                                <div id="hod-manager-peer-section" class="col-12 notif-contact-item" style="display: none;">
                                    <p class="notif-contact-label">Manager's Response</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-person-lines-fill"></i>
                                        <span id="hod-manager-peer-name" class="me-1"></span>
                                        <span id="hod-manager-response-badge" class="badge" style="font-size: 0.72rem;"></span>
                                    </div>
                                    <div id="hod-manager-peer-remarks-row" style="display: none; font-style: italic; color: #64748b; font-size: 0.78rem; margin-top: 0.2rem; padding-left: 1.3rem;">
                                        "<span id="hod-manager-peer-remarks"></span>"
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action area -->
                        <div class="notif-btn-wrap" style="margin-top: 1.25rem;">

                            <!-- Normal action area (hidden when manager said try_to_retain) -->
                            <div id="hod-normal-action-area">
                                <select id="hod-action-select" class="form-select mb-2"
                                    style="border-radius: 12px; font-size: 0.875rem; font-weight: 600; border-color: #e2e8f0;">
                                    <option value="" disabled selected>Select your response…</option>
                                    <option value="too_early">Remind Me Later</option>
                                    <option value="try_to_retain">Try to Retain</option>
                                    <option value="accept">Agree</option>
                                    <option value="reject">Disagree</option>
                                    <option value="acknowledge" style="display:none;">Acknowledge</option>
                                </select>
                                <div id="hod-action-error" class="text-danger mb-1" style="display: none; font-size: 0.8rem;">Please select an action.</div>
                                <div id="hod-rejection-reason-container" style="display: none;">
                                    <textarea id="hod-rejection-reason" class="form-control mb-2"
                                        placeholder="Remarks" rows="2"
                                        style="border-radius: 12px; font-size: 0.875rem;"></textarea>
                                    <div id="hod-rejection-error" class="text-danger mb-1" style="display: none; font-size: 0.8rem;">Remarks are required.</div>
                                </div>
                                <button type="button" id="hod-submit-btn"
                                    class="btn notif-action-btn w-100 d-flex align-items-center justify-content-center gap-2">
                                    <span>Submit Response</span>
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>

                            <!-- Acknowledge-only area (shown when manager said try_to_retain) -->
                            <div id="hod-acknowledge-area" style="display: none;">
                                <p class="text-muted mb-2" style="font-size:0.82rem; text-align:center;">
                                    The Manager is handling retention. Please acknowledge.
                                </p>
                                <button type="button" id="hod-acknowledge-btn"
                                    class="btn notif-action-btn w-100 d-flex align-items-center justify-content-center gap-2">
                                    <span>Acknowledge</span>
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<!-- ==================== REPORTING MANAGER RESIGNATION NOTIFICATION ==================== -->
<!-- Reporting Manager Resignation Notification Script -->
<script>
    $(document).ready(function() {
        setTimeout(function() {
            checkForReportingManagerResignationNotifications();
        }, 3000);

        function checkForReportingManagerResignationNotifications() {
            $.ajax({
                url: '<?= base_url("/ajax/resignation/reporting-manager-notifications") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.notifications.length > 0) {
                        enqueueModal(function() {
                            showReportingManagerResignationModal(response.notifications[0]);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching reporting manager notifications:', error);
                }
            });
        }

        function showReportingManagerResignationModal(notification) {
            const isHrDecision = notification.notification_type === 'hr_decision';
            $('#rm-record-id').val(notification.id).data('notification-type', isHrDecision ? 'hr_decision' : '');

            // ── Left panel ──────────────────────────────────────────────
            if (notification.employee_image) {
                $('#rm-emp-img').attr('src', notification.employee_image);
                $('#rm-photo-wrapper').show();
                $('#rm-emoji-fallback').hide();
            } else {
                $('#rm-photo-wrapper').hide();
                $('#rm-emoji-fallback').show();
            }
            $('#rm-first-name').text(notification.employee_first_name || notification.employee_name);

            // ── Right panel ──────────────────────────────────────────────
            $('#rm-employee-name').text(notification.employee_name + ' (' + (notification.internal_employee_id || '') + ')');
            $('#rm-designation').text(notification.designation_name || 'N/A');
            $('#rm-department').text(notification.department_name || 'N/A');
            $('#rm-company').text(notification.company_name || 'N/A');
            $('#rm-resignation-date').text(notification.resignation_date_formatted);
            $('#rm-last-working-date').text(notification.last_working_date_formatted);
            $('#rm-reason').text(notification.resignation_reason || 'Not specified');

            // HOD response badge
            const hodResponse = notification.hod_response || 'pending';
            const $hodBadge = $('#rm-hod-response');
            const hodLabels = {
                accept: 'Accepted',
                rejected: 'Rejected',
                try_to_retain: 'Want to Retain',
                pending: 'Pending',
                too_early: 'Too Early'
            };
            $hodBadge.text(hodLabels[hodResponse] || hodResponse);
            $hodBadge.removeClass('bg-success bg-danger bg-info bg-warning text-dark');
            if (hodResponse === 'accept') $hodBadge.addClass('bg-success');
            else if (hodResponse === 'rejected') $hodBadge.addClass('bg-danger');
            else if (hodResponse === 'try_to_retain') $hodBadge.addClass('bg-info');
            else $hodBadge.addClass('bg-warning text-dark');

            // HOD name + remarks
            const hodName = [notification.hod_first_name, notification.hod_last_name].filter(Boolean).join(' ');
            $('#rm-hod-peer-name').text(hodName || '');
            if (notification.hod_rejection_reason) {
                $('#rm-hod-peer-remarks').text(notification.hod_rejection_reason);
                $('#rm-hod-peer-remarks-row').show();
            } else {
                $('#rm-hod-peer-remarks-row').hide();
            }

            // Reset action form
            $('#rm-action-select').val('');
            $('#rm-rejection-reason-container').hide();
            $('#rm-rejection-reason').val('').attr('placeholder', 'Remarks');
            $('#rm-action-error').hide();
            $('#rm-rejection-error').hide();

            // Switch between dropdown mode and acknowledge-only mode
            if (isHrDecision) {
                const hrLabel = notification.hr_decision === 'retained' ? 'Retained' : 'Retention Failed';
                const hrColor = notification.hr_decision === 'retained' ? 'bg-success' : 'bg-danger';
                $('#rm-action-select').hide();
                $('#rm-acknowledge-mode').html(
                    `<i class="bi bi-info-circle me-1"></i> HR has marked this employee as <span class="badge ${hrColor}">${hrLabel}</span>. Please acknowledge.`
                ).show();
                $('#rm-acknowledge-btn span').text('Acknowledge');
            } else if (hodResponse === 'try_to_retain') {
                $('#rm-action-select').hide();
                $('#rm-acknowledge-mode').show();
                $('#rm-acknowledge-btn span').text('Acknowledge');
            } else {
                $('#rm-action-select').show();
                $('#rm-acknowledge-mode').hide();
                $('#rm-acknowledge-btn span').text('Submit Response');
            }

            // Hide "Remind Me Later" after 10 days from resignation date
            var daysSinceResignation = Math.floor((new Date() - new Date(notification.created_at)) / 86400000);
            $('#rm-action-select option[value="too_early"]').toggle(daysSinceResignation <= 10);

            $('#reportingManagerResignationModal').modal('show');
        }

        $('#rm-action-select').on('change', function() {
            if ($(this).val() && $(this).val() !== 'too_early') {
                const placeholders = {
                    accept: 'Remarks (required)',
                    try_to_retain: 'Remarks — why retain? (required)',
                    rejected: 'Rejection reason (required)',
                };
                $('#rm-rejection-reason').attr('placeholder', placeholders[$(this).val()] || 'Remarks');
                $('#rm-rejection-reason-container').slideDown();
            } else {
                $('#rm-rejection-reason-container').slideUp();
                $('#rm-rejection-reason').val('');
            }
            $('#rm-action-error').hide();
            $('#rm-rejection-error').hide();
        });

        $('#rm-acknowledge-btn').on('click', function() {
            const recordId = $('#rm-record-id').val();
            const isAcknowledgeMode = $('#rm-acknowledge-mode').is(':visible');
            const action = isAcknowledgeMode ? 'acknowledge' : $('#rm-action-select').val();
            const rejectionReason = $('#rm-rejection-reason').val();

            $('#rm-action-error').hide();
            $('#rm-rejection-error').hide();

            if (!isAcknowledgeMode) {
                if (!action) {
                    $('#rm-action-error').show();
                    return;
                }
                if (action !== 'too_early' && !rejectionReason.trim()) {
                    $('#rm-rejection-error').show();
                    return;
                }
            }

            const $btn = $(this);
            const btnHtml = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Processing…');

            $.ajax({
                url: '<?= base_url("/ajax/resignation/reporting-manager-notification-action") ?>',
                method: 'POST',
                data: {
                    record_id: recordId,
                    action: action,
                    rejection_reason: rejectionReason || null,
                    notification_type: $('#rm-record-id').data('notification-type') || ''
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#reportingManagerResignationModal').modal('hide');
                        if (action === 'too_early') {
                            Swal.fire({
                                icon: 'info',
                                title: 'Noted',
                                text: "You'll be reminded tomorrow.",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            setTimeout(checkForReportingManagerResignationNotifications, 2500);
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Response Saved',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process. Please try again.'
                    });
                },
                complete: function() {
                    $btn.html(btnHtml).prop('disabled', false);
                }
            });
        });
    });
</script>

<!-- Reporting Manager Resignation Notification Modal -->
<div class="modal fade" id="reportingManagerResignationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered notif-dialog">
        <div class="modal-content notif-glass-card">
            <input type="hidden" id="rm-record-id">
            <div class="row g-0 notif-card-row">

                <!-- ── LEFT PANEL ── -->
                <div class="col-5 d-flex flex-column align-items-center justify-content-center text-center notif-left-panel"
                    style="background: linear-gradient(160deg, #fff1f2 0%, #ffe4e6 100%); border-right: 1px solid #fecdd3;">

                    <!-- Photo with glow halo -->
                    <div id="rm-photo-wrapper" class="notif-photo-wrapper">
                        <div class="notif-photo-halo" style="background: linear-gradient(135deg, #f43f5e, #fb923c, #fbbf24); opacity: 0.22;"></div>
                        <div class="notif-photo-frame">
                            <img id="rm-emp-img" src="" alt="Employee" class="notif-emp-img">
                        </div>
                        <div class="notif-float-badge">
                            <i class="bi bi-envelope-open" style="color: #e11d48;"></i>
                        </div>
                    </div>

                    <!-- Emoji fallback -->
                    <div id="rm-emoji-fallback" class="notif-emoji-fallback" style="display: none;">📋</div>

                    <!-- "Notice of / Resignation" text -->
                    <div class="notif-event-text mt-2">
                        <div class="notif-label-top">Notice of</div>
                        <div class="notif-styled-event"
                            style="background: linear-gradient(135deg, #e11d48 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            Resignation
                        </div>
                    </div>

                    <p class="notif-celebrating">
                        Submitted by <strong id="rm-first-name"></strong>
                    </p>
                </div>

                <!-- ── RIGHT PANEL ── -->
                <div class="col-7 d-flex flex-column justify-content-between notif-right-panel">
                    <div>
                        <!-- Type badge -->
                        <div class="mb-3">
                            <span class="notif-type-badge"
                                style="background: rgba(225,29,72,0.08); color: #be123c; border: 1px solid rgba(225,29,72,0.2);">
                                <i class="bi bi-file-earmark-text me-1"></i>RESIGNATION NOTICE
                            </span>
                        </div>

                        <!-- Employee name -->
                        <h4 id="rm-employee-name" class="notif-name-title mb-1"></h4>

                        <!-- Designation · Department -->
                        <div class="notif-subtitle mb-3">
                            <strong id="rm-designation"></strong>
                            <span class="notif-subtitle-dot"></span>
                            <span id="rm-department"></span>
                        </div>

                        <!-- Resignation reason -->
                        <div id="rm-reason" class="notif-description mb-3"></div>

                        <!-- Info grid -->
                        <div class="row g-0 notif-contact-grid">
                            <div class="col-6 notif-contact-item">
                                <p class="notif-contact-label">Resignation Date</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-calendar-event"></i><span id="rm-resignation-date"></span>
                                </div>
                            </div>
                            <div class="col-6 notif-contact-item">
                                <p class="notif-contact-label">Last Working Day</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-calendar-check"></i><span id="rm-last-working-date"></span>
                                </div>
                            </div>
                            <div class="col-6 notif-contact-item">
                                <p class="notif-contact-label">Company</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-building"></i><span id="rm-company"></span>
                                </div>
                            </div>
                            <!-- <div class="col-12 notif-contact-item">
                                <p class="notif-contact-label">HOD Response</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-person-check"></i>
                                    <span id="rm-hod-peer-name" class="me-1"></span>
                                    <span id="rm-hod-response" class="badge bg-warning text-dark" style="font-size: 0.75rem;"></span>
                                </div>
                                <div id="rm-hod-peer-remarks-row" style="display: none; font-style: italic; color: #64748b; font-size: 0.78rem; margin-top: 0.2rem; padding-left: 1.3rem;">
                                    "<span id="rm-hod-peer-remarks"></span>"
                                </div>
                            </div> -->
                        </div>
                    </div>

                    <!-- Action area -->
                    <div class="notif-btn-wrap" style="margin-top: 1.25rem;">
                        <select id="rm-action-select" class="form-select mb-2"
                            style="border-radius: 12px; font-size: 0.875rem; font-weight: 600; border-color: #e2e8f0;">
                            <option value="" disabled selected>Select your response…</option>
                            <option value="too_early">Remind Me Later</option>
                            <option value="try_to_retain">Try to Retain</option>
                            <option value="accept">Accept</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <div id="rm-action-error" class="text-danger mb-1" style="display: none; font-size: 0.8rem;">Please select an action.</div>
                        <div id="rm-rejection-reason-container" style="display: none;">
                            <textarea id="rm-rejection-reason" class="form-control mb-2"
                                placeholder="Remarks (required for rejection)" rows="2"
                                style="border-radius: 12px; font-size: 0.875rem;"></textarea>
                            <div id="rm-rejection-error" class="text-danger mb-1" style="display: none; font-size: 0.8rem;">Remarks are required.</div>
                        </div>

                        <!-- Acknowledge-only mode (shown when HOD = try_to_retain) -->
                        <div id="rm-acknowledge-mode" style="display: none; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px; padding: 0.75rem 1rem; margin-bottom: 0.5rem; font-size: 0.85rem; color: #0369a1;">
                            <i class="bi bi-info-circle me-1"></i> HOD wants to retain this employee. Click <strong>Acknowledge</strong> to confirm you've noted this.
                        </div>

                        <button type="button" id="rm-acknowledge-btn"
                            class="btn notif-action-btn w-100 d-flex align-items-center justify-content-center gap-2">
                            <span>Submit Response</span>
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- ==================== END REPORTING MANAGER RESIGNATION NOTIFICATION ==================== -->

<!-- ==================== HR MANAGER RESIGNATION NOTIFICATION (after HOD responds) ==================== -->
<script>
    $(document).ready(function() {
        setTimeout(function() {
            checkForHrResignationNotifications();
        }, 2500);

        function checkForHrResignationNotifications() {
            $.ajax({
                url: '<?= base_url("/ajax/resignation/manager-notifications") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.notifications.length > 0) {
                        enqueueModal(function() {
                            showHrResignationModal(response.notifications[0]);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching HR resignation notifications:', error);
                }
            });
        }

        function showHrResignationModal(notification) {
            console.log(notification);
            $('#rn-record-id').val(notification.id);

            // ── Left panel ──
            if (notification.employee_image) {
                $('#rn-emp-img').attr('src', notification.employee_image);
                $('#rn-photo-wrapper').show();
                $('#rn-emoji-fallback').hide();
            } else {
                $('#rn-photo-wrapper').hide();
                $('#rn-emoji-fallback').show();
            }
            $('#rn-first-name').text(notification.first_name || notification.employee_name);

            // ── Right panel ──
            $('#rn-employee-name').text(notification.employee_name + ' (' + (notification.internal_employee_id || '') + ')');
            $('#rn-designation').text(notification.designation_name || '');
            $('#rn-department').text(notification.department_name || '');
            $('#rn-company').text(notification.company_name || '');
            $('#rn-reason').text(notification.resignation_reason || 'No reason provided');
            $('#rn-resignation-date').text(notification.resignation_date_formatted);
            $('#rn-last-working-date').text(notification.last_working_date_formatted);

            // ── HOD response ──
            const responseLabels = {
                accept: 'Accepted',
                rejected: 'Rejected',
                try_to_retain: 'Try to Retain',
                pending: 'Pending',
                too_early: 'Too Early'
            };

            const hodResp = notification.hod_response || 'pending';
            const $hodBadge = $('#rn-hod-response-badge');
            $hodBadge.text(responseLabels[hodResp] || hodResp);
            $hodBadge.removeClass('bg-success bg-danger bg-info bg-warning text-dark');
            if (hodResp === 'accept') $hodBadge.addClass('bg-success');
            else if (hodResp === 'rejected') $hodBadge.addClass('bg-danger');
            else if (hodResp === 'try_to_retain') $hodBadge.addClass('bg-info');
            else $hodBadge.addClass('bg-warning text-dark');
            $('#rn-hod-name').text(notification.hod_name || '');
            if (notification.hod_rejection_reason) {
                $('#rn-hod-remarks').text(notification.hod_rejection_reason);
                $('#rn-hod-remarks-row').show();
            } else {
                $('#rn-hod-remarks-row').hide();
            }

            // ── Manager response (always shown) ──
            const mgrResp = notification.manager_response || 'pending';
            const $mgrBadge = $('#rn-manager-response-badge');
            $mgrBadge.text(responseLabels[mgrResp] || mgrResp);
            $mgrBadge.removeClass('bg-success bg-danger bg-info bg-warning bg-secondary text-dark');
            if (mgrResp === 'accept') $mgrBadge.addClass('bg-success');
            else if (mgrResp === 'rejected') $mgrBadge.addClass('bg-danger');
            else if (mgrResp === 'try_to_retain') $mgrBadge.addClass('bg-info');
            else if (mgrResp === 'pending') $mgrBadge.addClass('bg-warning text-dark');
            else $mgrBadge.addClass('bg-secondary');
            $('#rn-manager-name').text(notification.manager_name || '');
            if (notification.manager_remarks) {
                $('#rn-manager-remarks').text(notification.manager_remarks);
                $('#rn-manager-remarks-row').show();
            } else {
                $('#rn-manager-remarks-row').hide();
            }

            $('#resignationHrNotificationModal').modal('show');
        }

        $('#rn-acknowledge-btn').on('click', function() {
            const recordId = $('#rn-record-id').val();
            const $btn = $(this);
            const btnHtml = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Processing…');

            $.ajax({
                url: '<?= base_url("/ajax/resignation/manager-notification-action") ?>',
                method: 'POST',
                data: {
                    record_id: recordId,
                    action: 'acknowledge'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#resignationHrNotificationModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Acknowledged',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(checkForHrResignationNotifications, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process. Please try again.'
                    });
                },
                complete: function() {
                    $btn.html(btnHtml).prop('disabled', false);
                }
            });
        });
    });
</script>

<!-- HR Resignation Notification Modal (Glass-Card Design) -->
<div class="modal fade" id="resignationHrNotificationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered notif-dialog">
        <div class="modal-content notif-glass-card">
            <input type="hidden" id="rn-record-id">
            <div class="row g-0 notif-card-row">

                <!-- ── LEFT PANEL ── -->
                <div class="col-5 d-flex flex-column align-items-center justify-content-center text-center notif-left-panel"
                    style="background: linear-gradient(160deg, #fff1f2 0%, #ffe4e6 100%); border-right: 1px solid #fecdd3;">

                    <!-- Photo with glow halo -->
                    <div id="rn-photo-wrapper" class="notif-photo-wrapper">
                        <div class="notif-photo-halo" style="background: linear-gradient(135deg, #f43f5e, #fb923c, #fbbf24); opacity: 0.22;"></div>
                        <div class="notif-photo-frame">
                            <img id="rn-emp-img" src="" alt="Employee" class="notif-emp-img">
                        </div>
                        <div class="notif-float-badge">
                            <i class="bi bi-envelope-open" style="color: #e11d48;"></i>
                        </div>
                    </div>

                    <!-- Emoji fallback -->
                    <div id="rn-emoji-fallback" class="notif-emoji-fallback" style="display: none;">📋</div>

                    <!-- "Notice of / Resignation" text -->
                    <div class="notif-event-text mt-2">
                        <div class="notif-label-top">Notice of</div>
                        <div class="notif-styled-event"
                            style="background: linear-gradient(135deg, #e11d48 0%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            Resignation
                        </div>
                    </div>

                    <p class="notif-celebrating">
                        Submitted by <strong id="rn-first-name"></strong>
                    </p>
                </div>

                <!-- ── RIGHT PANEL ── -->
                <div class="col-7 d-flex flex-column justify-content-between notif-right-panel">
                    <div>
                        <!-- Type badge -->
                        <div class="mb-3">
                            <span class="notif-type-badge"
                                style="background: rgba(225,29,72,0.08); color: #be123c; border: 1px solid rgba(225,29,72,0.2);">
                                <i class="bi bi-file-earmark-text me-1"></i>RESIGNATION NOTICE
                            </span>
                        </div>

                        <!-- Employee name -->
                        <h4 id="rn-employee-name" class="notif-name-title mb-1"></h4>

                        <!-- Designation · Department -->
                        <div class="notif-subtitle mb-3">
                            <strong id="rn-designation"></strong>
                            <span class="notif-subtitle-dot"></span>
                            <span id="rn-department"></span>
                        </div>

                        <!-- Resignation reason -->
                        <div id="rn-reason" class="notif-description mb-3"></div>

                        <!-- Info grid -->
                        <div class="row g-0 notif-contact-grid">
                            <div class="col-6 notif-contact-item">
                                <p class="notif-contact-label">Resignation Date</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-calendar-event"></i><span id="rn-resignation-date"></span>
                                </div>
                            </div>
                            <div class="col-6 notif-contact-item">
                                <p class="notif-contact-label">Last Working Day</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-calendar-check"></i><span id="rn-last-working-date"></span>
                                </div>
                            </div>
                            <div class="col-6 notif-contact-item">
                                <p class="notif-contact-label">Company</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-building"></i><span id="rn-company"></span>
                                </div>
                            </div>

                            <!-- HOD response -->
                            <div class="col-12 notif-contact-item">
                                <p class="notif-contact-label">HOD Response</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-person-check"></i>
                                    <span id="rn-hod-name" class="me-1"></span>
                                    <span id="rn-hod-response-badge" class="badge" style="font-size: 0.72rem;"></span>
                                </div>
                                <div id="rn-hod-remarks-row" style="display: none; font-style: italic; color: #64748b; font-size: 0.78rem; margin-top: 0.2rem; padding-left: 1.3rem;">
                                    "<span id="rn-hod-remarks"></span>"
                                </div>
                            </div>

                            <!-- Manager response (always shown) -->
                            <div class="col-12 notif-contact-item">
                                <p class="notif-contact-label">Manager Response</p>
                                <div class="notif-contact-value">
                                    <i class="bi bi-person-lines-fill"></i>
                                    <span id="rn-manager-name" class="me-1"></span>
                                    <span id="rn-manager-response-badge" class="badge" style="font-size: 0.72rem;"></span>
                                </div>
                                <div id="rn-manager-remarks-row" style="display: none; font-style: italic; color: #64748b; font-size: 0.78rem; margin-top: 0.2rem; padding-left: 1.3rem;">
                                    "<span id="rn-manager-remarks"></span>"
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acknowledge button -->
                    <div class="notif-btn-wrap" style="margin-top: 1.25rem;">
                        <button type="button" id="rn-acknowledge-btn"
                            class="btn notif-action-btn w-100 d-flex align-items-center justify-content-center gap-2">
                            <span>Acknowledge</span>
                            <i class="bi bi-check2-circle"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- ==================== END HR MANAGER RESIGNATION NOTIFICATION ==================== -->



<div class="modal fade" id="hrManagerResignationNotificationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">Resignation Notification</h5>

            </div>
            <div class="modal-body" id="hrManagerResignationNotificationContent">
                <!-- Content populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-primary" id="markHrManagerNotificationViewedBtn">Acknowledge</button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== END HR MANAGER RESIGNATION NOTIFICATION ==================== -->
<script>
    $(document).ready(function() {
        checkForHrManagerNotifications();
    });

    function checkForHrManagerNotifications() {
        $.ajax({
            url: '<?php echo base_url('ajax/resignation/hr-manager-notifications'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // if (response.success && response.data && response.data.length > 0) {
                //     displayHrManagerNotifications(response.data);
                // }
                if (response.success && response.notifications && response.notifications.length > 0) {
                    displayHrManagerNotifications(response.notifications);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching HR Manager notifications:', error);
            }
        });
    }

    function displayHrManagerNotifications(notifications) {
        if (notifications.length === 0) return;

        let html = '<div class="container-fluid">';
        html += '<div class="alert alert-info mb-3">';
        html += '<strong>You have ' + notifications.length + ' resignation(s) to review as HR Manager</strong>';
        html += '</div>';

        notifications.forEach((notification, index) => {
            let hodResponseBadge = '';
            switch (notification.hod_response) {
                case 'accept':
                    hodResponseBadge = '<span class="badge badge-success">Accepted</span>';
                    break;
                case 'reject':
                    hodResponseBadge = '<span class="badge badge-danger">Rejected</span>';
                    break;
                case 'pending':
                    hodResponseBadge = '<span class="badge badge-warning">Pending</span>';
                    break;
                case 'too_early':
                    hodResponseBadge = '<span class="badge badge-info">Too Early</span>';
                    break;
                default:
                    hodResponseBadge = '<span class="badge badge-secondary">Unknown</span>';
            }

            html += '<div class="card mb-3">';
            html += '<div class="card-header bg-light"><strong>Resignation ' + (index + 1) + ' of ' + notifications.length + '</strong></div>';
            html += '<div class="card-body">';
            html += '<div class="row">';
            html += '<div class="col-md-6">';
            html += '<p><strong>Employee:</strong> ' + notification.first_name + ' ' + notification.last_name + ' (' + notification.internal_employee_id + ')</p>';
            html += '<p><strong>Department:</strong> ' + (notification.department_name || 'N/A') + '</p>';
            html += '<p><strong>Company:</strong> ' + (notification.company_name || 'N/A') + '</p>';
            html += '<p><strong>Resignation Date:</strong> ' + notification.resignation_date + '</p>';
            html += '</div>';
            html += '<div class="col-md-6">';
            html += '<p><strong>HOD Response:</strong> ' + hodResponseBadge + '</p>';
            html += '<p><strong>HOD:</strong> ' + (notification.hod_first_name || '') + ' ' + (notification.hod_last_name || '') + '</p>';
            html += '<p><strong>Response Date:</strong> ' + (notification.hod_response_date || 'N/A') + '</p>';
            html += '<p><strong>Reason:</strong> ' + (notification.resignation_reason || 'N/A') + '</p>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        });

        html += '</div>';

        $('#hrManagerResignationNotificationContent').html(html);
        $('#hrManagerResignationNotificationModal').modal('show');

        // Store notification IDs for marking as viewed
        $('#markHrManagerNotificationViewedBtn').data('record-ids', notifications.map(n => n.id));
    }

    $(document).ready(function() {
        $('#markHrManagerNotificationViewedBtn').on('click', function() {
            const recordIds = $(this).data('record-ids');

            if (!recordIds || recordIds.length === 0) return;

            // Mark all notifications as viewed
            const promises = recordIds.map(recordId => {
                return $.ajax({
                    url: '<?php echo base_url('ajax/resignation/hr-manager-notification-action'); ?>',
                    method: 'POST',
                    data: {
                        record_id: recordId,
                        action: 'viewed'
                    },

                });
            });

            Promise.all(promises).then(() => {
                $('#hrManagerResignationNotificationModal').modal('hide');
                toastr.success('Notifications marked as viewed');
            }).catch(() => {
                toastr.error('Failed to mark some notifications as viewed');
            });
        });
    });
</script>
<!-- ==================== END RESIGNATION HOD ACKNOWLEDGMENT MODALS ==================== -->