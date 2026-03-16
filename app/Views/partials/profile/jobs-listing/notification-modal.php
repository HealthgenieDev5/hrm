<div class="modal fade" id="jobListingNotificationModal" tabindex="-1" aria-labelledby="jobListingNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobListingNotificationModalLabel">Pending Job Approvals</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The following job listings require your attention:</p>
                <ul class="list-group" id="pending-jobs-list">
                    <!-- Job items will be inserted here by JavaScript -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?= base_url('/recruitment/job-listing/all') ?>" class="btn btn-primary">View All Listings</a>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        let notificationRequest = null;
        let badgeRequest = null;

        loadJobNotifications();
        updateNotificationBadge();

        setInterval(function() {
            updateNotificationBadge();
        }, 60000);

        $('#refresh-notifications').on('click', function() {
            loadJobNotifications();
            updateNotificationBadge();
        });

        $(document).on('click', '.notification-item', function() {
            const jobId = $(this).data('job-id');
            markNotificationAsRead(jobId);
            window.location.href = `<?= base_url('/recruitment/job-listing/view/') ?>${jobId}`;
        });

        function loadJobNotifications() {

            $('#notifications-loading').removeClass('d-none');
            $('#notifications-empty').addClass('d-none');
            $('#notifications-list').empty();

            $.ajax({
                url: '<?= base_url('/recruitment/job-listing/comments/get-notifications') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#notifications-loading').addClass('d-none');

                    if (response && response.status === 'success') {
                        displayJobNotifications(response.notifications);
                        updateNotificationCount(response.total_unread);

                        if (response.notifications && response.notifications.length > 0) {
                            $('#job-notifications-card').removeClass('d-none');
                        } else {
                            $('#job-notifications-card').addClass('d-none');
                        }
                    } else {
                        console.error('Response success but wrong format:', response);
                        $('#job-notifications-card').addClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    $('#notifications-loading').addClass('d-none');
                    $('#job-notifications-card').addClass('d-none');
                },
                complete: function() {
                    $('#notifications-loading').hide();
                }
            });
        }

        function displayJobNotifications(notifications) {
            const container = $('#notifications-list');

            if (notifications.length === 0) {
                $('#notifications-empty').removeClass('d-none');
                return;
            }

            notifications.forEach(function(notification) {
                const timeAgo = formatTimeAgo(notification.latest_time);
                const typeIcon = getTypeIcon(notification.latest_type);
                const companyName = notification.company_name ? ` - ${notification.company_name}` : '';

                const notificationHtml = `
                    <div class="col-md-6 col-lg-4">
                        <div class="card border border-hover-primary notification-item cursor-pointer" data-job-id="${notification.job_id}">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="symbol symbol-45px me-3">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="${typeIcon} text-primary fs-3"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-gray-800 fs-6">${notification.job_title}</div>
                                        <div class="text-muted fs-7">${companyName}</div>
                                    </div>
                                    <span class="badge badge-light-danger">${notification.unread_count}</span>
                                </div>
                                <div class="mb-3">
                                    <div class="text-muted fs-7 mb-1">Latest from ${notification.latest_sender}:</div>
                                    <div class="text-gray-800 fs-7 text-truncate" style="max-height: 40px; overflow: hidden;">
                                        ${notification.latest_message}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-light-${getTypeBadgeColor(notification.latest_type)}">${notification.latest_type.replace('_', ' ').toUpperCase()}</span>
                                    <span class="text-muted fs-8">${timeAgo}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.append(notificationHtml);
            });
        }

        function updateNotificationBadge() {
            $.ajax({
                url: '<?= base_url('/recruitment/job-listing/comments/unread-count') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const count = response.unread_count;
                        const badge = $('#notification-badge');

                        if (count > 0) {
                            badge.text(count).show();
                        } else {
                            badge.hide();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating notification badge:', error);
                }
            });
        }

        function updateNotificationCount(count) {
            const badge = $('#notification-badge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        }

        function markNotificationAsRead(jobId) {
            $.ajax({
                url: '<?= base_url('/recruitment/job-listing/comments/mark-as-read') ?>',
                type: 'POST',
                data: {
                    job_id: jobId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        updateNotificationBadge();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error marking as read:', error);
                }
            });
        }

        function showEmptyState() {
            $('#notifications-empty').removeClass('d-none');
        }

        function getTypeIcon(type) {
            switch (type) {
                case 'question':
                    return 'fa fa-question-circle';
                case 'answer':
                    return 'fa fa-check-circle';
                case 'issue':
                    return 'fa fa-exclamation-triangle';
                case 'resolution':
                    return 'fa fa-check-square';
                case 'concern':
                    return 'fa fa-exclamation-circle';
                case 'suggestion':
                    return 'fa fa-lightbulb';
                case 'feedback':
                    return 'fa fa-comment';
                default:
                    return 'fa fa-comment';
            }
        }

        function getTypeBadgeColor(type) {
            switch (type) {
                case 'question':
                    return 'warning';
                case 'answer':
                    return 'success';
                case 'issue':
                    return 'danger';
                case 'resolution':
                    return 'primary';
                case 'concern':
                    return 'danger';
                case 'suggestion':
                    return 'info';
                case 'feedback':
                    return 'secondary';
                default:
                    return 'secondary';
            }
        }

        function formatTimeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' min ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hr ago';
            if (diffInSeconds < 2592000) return Math.floor(diffInSeconds / 86400) + ' day ago';
            return Math.floor(diffInSeconds / 2592000) + ' month ago';
        }

        function checkJobListingNotifications() {
            if ($('#jobListingNotificationModal').is(':visible') || $('body').hasClass('modal-open') || $('.swal2-container').is(':visible')) {
                return;
            }

            $.ajax({
                url: "<?= base_url('/recruitment/job-listing/pending-notifications') ?>",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'show_modal' && response.jobs?.length > 0) {
                        const pendingList = $('#pending-jobs-list').empty();

                        response.jobs.forEach(job => {
                            const jobUrl = `<?= base_url('/recruitment/job-listing/view/') ?>${job.id}`;
                            pendingList.append(`
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${job.job_title}</strong>
                                    <small class="d-block text-muted">
                                        For ${job.department_name || 'N/A'} | By: ${job.created_by_name || 'N/A'}
                                    </small>
                                </div>
                                <a href="${jobUrl}" class="btn btn-sm btn-outline-primary mark-job-as-read" data-job-id="${job.id}">View</a>
                            </li>
                        `);
                        });

                        $('#jobListingNotificationModal').modal('show');
                    }
                }
            });
        }

        setTimeout(checkJobListingNotifications, 5000);

        $(document).on('click', '.mark-job-as-read', function(e) {

            e.preventDefault();

            const jobId = $(this).data('job-id');
            const redirectUrl = $(this).attr('href');

            if (jobId) {
                $.ajax({
                    url: "<?= base_url('/recruitment/job-listing/mark-as-read') ?>",
                    type: 'POST',
                    data: {
                        job_id: [jobId],
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = redirectUrl;
                    }
                });
            }
        });

    });
</script>