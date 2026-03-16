<div class="mb-5">
    <div class="card shadow-sm d-none" id="job-notifications-card">
        <div class="card-header">
            <div class="card-title d-flex align-items-center">
                <i class="fa fa-bell text-primary me-2"></i>
                <h3 class="fw-bold mb-0">Job Listing Notifications</h3>
                <span class="badge badge-light-danger ms-2" id="notification-badge" style="display: none;"></span>
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-light" id="refresh-notifications" title="Refresh">
                    <i class="fa fa-refresh"></i>
                </button>
            </div>
        </div>
        <div class="card-body" id="notifications-container">
            <!--begin::Loading state-->
            <div class="d-flex justify-content-center py-5 d-none" id="notifications-loading">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2">Loading notifications...</span>
            </div>
            <!--end::Loading state-->

            <!--begin::Empty state-->
            <div class="d-flex flex-column justify-content-center align-items-center py-5 text-center d-none" id="notifications-empty">
                <div class="text-muted mb-3">
                    <i class="fa fa-bell-slash fs-2x"></i>
                </div>
                <h5 class="text-muted">No New Notifications</h5>
                <p class="text-muted fs-7">You're all caught up! No new job listing messages at this time.</p>
            </div>
            <!--end::Empty state-->

            <!--begin::Notification items-->
            <div id="notifications-list" class="row g-3"></div>
            <!--end::Notification items-->
        </div>
    </div>
</div>