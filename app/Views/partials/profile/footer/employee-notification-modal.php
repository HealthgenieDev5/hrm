<div class="modal fade" id="employeeNotificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered notif-dialog">
        <div class="modal-content notif-glass-card">
            <div class="row g-0 notif-card-row">

                <!-- ── LEFT PANEL ── -->
                <div class="col-5 d-flex flex-column align-items-center justify-content-center text-center notif-left-panel" id="notif-left-panel">

                    <!-- Photo with glow halo -->
                    <div id="notification-employee-image" class="notif-photo-wrapper" style="display: none;">
                        <div class="notif-photo-halo" id="notif-photo-halo"></div>
                        <div class="notif-photo-frame">
                            <img id="notification-emp-img" src="" alt="Employee" class="notif-emp-img">
                        </div>
                        <div class="notif-float-badge">
                            <i id="notif-badge-icon" class="bi bi-stars"></i>
                        </div>
                    </div>

                    <!-- Emoji fallback -->
                    <div id="notification-emoji" class="notif-emoji-fallback"></div>

                    <!-- "Happy\nBirthday" text block -->
                    <div class="notif-event-text mt-2">
                        <div id="notif-label-top" class="notif-label-top"></div>
                        <div id="notif-styled-event" class="notif-styled-event"></div>
                    </div>

                    <!-- "Today we celebrate Ayush" -->
                    <p id="notif-celebrating" class="notif-celebrating" style="display: none;">
                        Celebrating <strong id="notif-first-name"></strong>
                    </p>
                </div>

                <!-- ── RIGHT PANEL ── -->
                <div class="col-7 d-flex flex-column justify-content-between notif-right-panel">
                    <div>
                        <!-- Type badge -->
                        <div class="mb-3">
                            <span id="notification-type" class="notif-type-badge"></span>
                        </div>

                        <!-- Name / title -->
                        <h4 id="notification-title" class="notif-name-title mb-1"></h4>

                        <!-- Designation • date -->
                        <div id="notification-subtitle" class="notif-subtitle mb-4"></div>

                        <!-- Quote / description -->
                        <div id="notification-description" class="notif-description"></div>

                        <!-- Contact grid -->
                        <div id="notification-employee-details" style="display: none;">
                            <div class="row g-0 notif-contact-grid">
                                <div class="col-6 notif-contact-item" id="notif-row-mobile" style="display: none;">
                                    <p class="notif-contact-label">Mobile Number</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-phone"></i><span id="notif-mobile"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item" id="notif-row-email" style="display: none;">
                                    <p class="notif-contact-label">Work Email</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-envelope"></i><span id="notif-email" class="notif-email-truncate"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item" id="notif-row-ext" style="display: none;">
                                    <p class="notif-contact-label">Extension</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-telephone"></i><span id="notif-ext"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item" id="notif-row-emp-code" style="display: none;">
                                    <p class="notif-contact-label">Employee ID</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-fingerprint"></i><span id="notif-emp-code"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date (non-employee notifications) -->
                        <div id="notification-event-date" class="notif-date-only"></div>
                    </div>

                    <!-- Action button -->
                    <div class="notif-btn-wrap">
                        <button type="button" id="mark-as-read-btn"
                            class="btn notif-action-btn w-100 d-flex align-items-center justify-content-center gap-2">
                            <span id="notif-btn-text">Acknowledge</span>
                            <i id="notif-btn-icon" class="bi bi-check-circle"></i>
                        </button>
                        <p id="notif-event-date-label" class="notif-footer-text text-center mt-3 mb-0"></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .modal-fullscreen .modal-content {
        height: max-content;
        border: 0;
        border-radius: 0;
        max-width: 90vw;
        max-height: 90vh;
        margin-left: auto;
        margin-right: auto;
    }

    .notif-dialog {
        max-width: 760px;
    }

    .notif-glass-card {
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.45) !important;
        border-radius: 40px !important;
        overflow: hidden !important;
        box-shadow: 0 32px 64px -16px rgba(0, 0, 0, 0.13) !important;
    }

    .notif-card-row {
        min-height: 500px;
    }

    /* ── LEFT PANEL ── */
    .notif-left-panel {
        background: #eef1f8;
        border-right: 1px solid #dde2ec;
        padding: 3rem 2rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Photo wrapper */
    .notif-photo-wrapper {
        position: relative;
        width: 180px;
        margin-bottom: 2rem;
    }

    /* Gradient glow behind photo (set via JS per event type) */
    .notif-photo-halo {
        position: absolute;
        inset: -18px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #3b82f6, #8b5cf6);
        opacity: 0.18;
        transition: opacity 0.4s ease;
    }

    .notif-photo-wrapper:hover .notif-photo-halo {
        opacity: 0.34;
    }

    /* Circular photo frame */
    .notif-photo-frame {
        position: relative;
        z-index: 1;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 7px solid #fff;
        box-shadow: 0 20px 48px rgba(0, 0, 0, 0.14);
        overflow: hidden;
    }

    .notif-emp-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .notif-photo-wrapper:hover .notif-emp-img {
        transform: scale(1.07);
    }

    /* Floating badge (bottom-right of photo) — neumorphic style */
    .notif-float-badge {
        position: absolute;
        bottom: -4px;
        right: -4px;
        z-index: 2;
        width: 46px;
        height: 46px;
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.13);
        font-size: 1.4rem;
        line-height: 1;
    }

    /* Emoji fallback — neumorphic circle matching photo wrapper style */
    .notif-emoji-fallback {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: #eef1f8;
        box-shadow: 16px 16px 36px rgba(163, 177, 198, 0.55),
            -16px -16px 36px rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        line-height: 1;
        margin-bottom: 1.25rem;
    }

    /* "Happy" plain text */
    .notif-label-top {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
    }

    /* "Birthday" / "Anniversary" — Playfair Display italic gradient */
    .notif-styled-event {
        font-family: 'Playfair Display', Georgia, serif;
        font-style: italic;
        font-size: 3.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.1;
        margin-bottom: 0.65rem;
    }

    /* "Today we celebrate Name" */
    .notif-celebrating {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.05rem;
        font-weight: 300;
        color: #475569;
        letter-spacing: -0.01em;
        margin-top: 2px;
    }

    .notif-celebrating strong {
        font-weight: 700;
        color: #0f172a;
    }

    /* ── RIGHT PANEL ── */
    .notif-right-panel {
        background: rgba(255, 255, 255, 0.25);
        padding: 2.5rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Type badge */
    .notif-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 0.625rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        /* color set via JS */
    }

    /* Name heading */
    .notif-name-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    /* Designation • Date subtitle */
    .notif-subtitle {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: #64748b;
        flex-wrap: wrap;
    }

    .notif-subtitle-dot {
        width: 5px;
        height: 5px;
        background: #cbd5e1;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .notif-subtitle strong {
        font-weight: 600;
        color: #334155;
    }

    /* Description / quote box */
    .notif-description {
        background: rgba(255, 255, 255, 0.55);
        border: 1px solid rgba(255, 255, 255, 0.85);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        font-style: italic;
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.75;
        white-space: pre-wrap;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        margin-bottom: 0;
    }

    /* Contact grid */
    .notif-contact-grid {
        margin-top: 1.25rem;
    }

    .notif-contact-item {
        padding: 0.6rem 0;
    }

    .notif-contact-label {
        font-size: 0.6rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        margin-bottom: 5px;
    }

    .notif-contact-value {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
    }

    .notif-contact-value i {
        color: #94a3b8;
        font-size: 1rem;
        flex-shrink: 0;
        transition: color 0.2s;
    }

    .notif-contact-item:hover .notif-contact-value i {
        color: #10b981;
    }

    .notif-email-truncate {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }

    /* Date only (non-employee) */
    .notif-date-only {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 0.5rem;
    }

    /* Button area */
    .notif-btn-wrap {
        margin-top: 2rem;
    }

    .notif-action-btn {
        padding: 1.1rem 1.5rem !important;
        background: #0f172a !important;
        color: #fff !important;
        border: none !important;
        border-radius: 18px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.18);
        transition: all 0.3s ease;
    }

    .notif-action-btn:hover {
        background: #1e293b !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.22);
    }

    .notif-action-btn:active {
        transform: translateY(0);
    }

    /* Footer text */
    .notif-footer-text {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    /* Responsive — stack on small screens */
    @media (max-width: 600px) {
        .notif-card-row {
            flex-direction: column;
        }

        #employeeNotificationModal .col-5,
        #employeeNotificationModal .col-7 {
            width: 100% !important;
            max-width: 100% !important;
        }

        .notif-left-panel {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 2rem;
        }

        .notif-styled-event {
            font-size: 2.5rem !important;
        }

        .notif-photo-wrapper,
        .notif-photo-frame {
            width: 140px;
            height: 140px;
        }
    }
</style>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        // Check for employee notifications 30 seconds after page load
        setTimeout(function() {
            checkForEmployeeNotifications();
        }, 10000);

        function checkForEmployeeNotifications() {
            $.ajax({
                url: '<?= base_url("ajax/notifications/dashboard") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.notifications && response.notifications.length > 0) {
                        showEmployeeNotificationModal(response.notifications[0]);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching notifications:', error);
                }
            });
        }

        function showEmployeeNotificationModal(notification) {
            const titleLower = (notification.title || '').toLowerCase();
            const isBirthday = titleLower.includes('birthday');
            const isAnniversary = titleLower.includes('anniversary') || titleLower.includes('wedding');

            // ── Left panel — update photo halo gradient per event type ──────
            var haloGradient;
            if (isBirthday) {
                haloGradient = 'linear-gradient(135deg, #f43f5e, #fb923c, #fbbf24)';
            } else if (isAnniversary) {
                haloGradient = 'linear-gradient(135deg, #10b981, #3b82f6, #8b5cf6)';
            } else {
                haloGradient = 'linear-gradient(135deg, #3b82f6, #8b5cf6)';
            }
            $('#notif-photo-halo').css('background', haloGradient);

            if (notification.related_employee_id) {
                // Show photo or hide it (emoji hidden for employee notifications)
                $('#notification-emoji').hide();
                if (notification.employee_image) {
                    $('#notification-emp-img').attr('src', notification.employee_image);
                    $('#notification-employee-image').show();
                } else {
                    $('#notification-employee-image').hide();
                }

                // Badge icon on photo (Bootstrap Icons)
                const badgeIcon = isBirthday ? 'bi-stars' : (isAnniversary ? 'bi-stars' : 'bi-bell');
                const badgeColor = isBirthday ? '#e91e8c' : (isAnniversary ? '#6c3fc5' : '#1976d2');
                $('#notif-badge-icon').attr('class', 'bi ' + badgeIcon).css('color', badgeColor);

                // Styled text
                if (isBirthday) {
                    $('#notif-label-top').text('Happy');
                    $('#notif-styled-event').text('Birthday!');
                } else if (isAnniversary) {
                    $('#notif-label-top').text('Happy');
                    $('#notif-styled-event').text('Anniversary!');
                } else {
                    $('#notif-label-top').text('');
                    $('#notif-styled-event').text('');
                }

                // "Celebrating [FirstName]"
                if (notification.employee_first_name) {
                    $('#notif-first-name').text(notification.employee_first_name);
                    $('#notif-celebrating').show();
                } else {
                    $('#notif-celebrating').hide();
                }
            } else {
                // Non-employee notification — show emoji, hide photo
                $('#notification-employee-image').hide();
                $('#notif-celebrating').hide();
                var emojiMap = {
                    'event': '🎉',
                    'reminder': '⏰',
                    'alert': '⚠️',
                    'announcement': '📢',
                    'policy': '📋',
                    'other': '💬'
                };
                $('#notification-emoji')
                    .text(emojiMap[notification.notification_type] || '💬')
                    .show();
                $('#notif-label-top').text('');
                $('#notif-styled-event').text(
                    notification.notification_type.charAt(0).toUpperCase() +
                    notification.notification_type.slice(1)
                );
            }

            // ── Right panel ─────────────────────────────────────────────────
            var formattedDate = formatNotificationDate(notification.event_date);
            var ordDate = notification.event_date ?
                new Date(notification.event_date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) :
                '';

            // Badge — type + date e.g. "Event on February 24, 2026"
            var badgeColors = {
                'event': 'background: #dbeafe; color: #2563eb;',
                'reminder': 'background: #fef3c7; color: #d97706;',
                'alert': 'background: #fee2e2; color: #dc2626;',
                'announcement': 'background: #d1fae5; color: #059669;',
                'policy': 'background: #f1f5f9; color: #475569;',
                'other': 'background: #ede9fe; color: #7c3aed;'
            };
            var badgeStyle = badgeColors[notification.notification_type] || badgeColors['other'];
            var typeWord = notification.notification_type.charAt(0).toUpperCase() + notification.notification_type.slice(1);
            var badgeLabel = ordDate ? (typeWord) : typeWord;
            $('#notification-type').attr('style',
                'font-size: 0.68rem; padding: 5px 14px; border-radius: 20px; letter-spacing: 0.5px; font-weight: 700; ' + badgeStyle
            ).text(badgeLabel);

            // Title & subtitle
            if (notification.related_employee_id && notification.employee_name) {
                $('#notification-title').text(notification.employee_name);

                // Build "Designation • Department - Company"
                var deptCompany = [
                    notification.employee_department || '',
                    notification.employee_company || ''
                ].filter(Boolean).join(' - ');

                var subtitleHtml = '';
                if (notification.employee_designation) {
                    subtitleHtml += '<strong>' + notification.employee_designation + '</strong>';
                }
                if (deptCompany) {
                    if (subtitleHtml) subtitleHtml += ' <span class="notif-subtitle-dot"></span> ';
                    subtitleHtml += deptCompany;
                }
                $('#notification-subtitle').html(subtitleHtml);
            } else {
                $('#notification-title').text(notification.title);
                $('#notification-subtitle').text(formattedDate);
            }

            // Description
            $('#notification-description').text(notification.description);

            // Contact grid
            if (notification.related_employee_id) {
                function setDetailRow(rowId, cellId, value) {
                    if (value) {
                        $('#' + cellId).text(value);
                        $('#' + rowId).show();
                    } else {
                        $('#' + rowId).hide();
                    }
                }
                setDetailRow('notif-row-mobile', 'notif-mobile', notification.employee_mobile);
                setDetailRow('notif-row-email', 'notif-email', notification.employee_email);
                setDetailRow('notif-row-ext', 'notif-ext', notification.employee_extension);
                setDetailRow('notif-row-emp-code', 'notif-emp-code', notification.employee_code);
                $('#notification-employee-details').show();
                $('#notification-event-date').text('');
            } else {
                $('#notification-employee-details').hide();
                $('#notification-event-date').text('Date: ' + formatNotificationDate(notification.event_date));
            }

            // Button
            var currentEmployeeId = '<?= session()->get('current_user')['employee_id'] ?>';
            var isOwnEvent = notification.related_employee_id &&
                String(notification.related_employee_id) === String(currentEmployeeId);

            if (isOwnEvent && (isBirthday || isAnniversary)) {
                // Own birthday/anniversary — "Acknowledge" is more meaningful
                $('#notif-btn-text').text('Acknowledge');
                $('#notif-btn-icon').removeClass('bi-send').addClass('bi-check-circle');
                $('#notif-event-date-label').text('Wishing you a wonderful day!');
            } else if (notification.related_employee_id && (isBirthday || isAnniversary)) {
                // Colleague's birthday/anniversary — send wishes
                //var wishText = isBirthday ? 'Send Birthday Wishes' : 'Send Anniversary Wishes';
                //$('#notif-btn-text').text(wishText);
                $('#notif-btn-text').text('Acknowledge');
                $('#notif-btn-icon').removeClass('bi-check-circle').addClass('bi-send');
                $('#notif-event-date-label').text('Join your colleagues in wishing them well!');
            } else {
                // Generic notification
                $('#notif-btn-text').text('Acknowledge');
                $('#notif-btn-icon').removeClass('bi-send').addClass('bi-check-circle');
                $('#notif-event-date-label').text('');
            }

            $('#mark-as-read-btn').data('notification-id', notification.id);
            $('#employeeNotificationModal').modal('show');
        }

        function formatNotificationDate(dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return date.toLocaleDateString('en-US', options);
        }

        $('#mark-as-read-btn').on('click', function() {
            var notificationId = $(this).data('notification-id');

            if (!notificationId) {
                console.error('No notification ID found');
                return;
            }

            $.ajax({
                url: '<?= base_url("ajax/notifications/mark-as-read") ?>',
                method: 'POST',
                data: {
                    notification_id: notificationId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#employeeNotificationModal').modal('hide');
                        setTimeout(function() {
                            checkForEmployeeNotifications();
                        }, 500);
                    } else {
                        console.error('Failed to mark notification as read:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error marking notification as read:', error);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>