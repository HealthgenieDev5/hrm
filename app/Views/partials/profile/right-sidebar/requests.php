<div class="card shadow-sm mb-5">
    <div class="card-footer pb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">

            <!--begin::Leave Request Modal-->
            <?php echo $this->include('partials/profile/right-sidebar/leave-request-modal'); ?>
            <!--End::Leave Request Modal-->

            <!--begin::OD Request Modal-->
            <?php echo $this->include('partials/profile/right-sidebar/od-request-modal'); ?>
            <!--End::OD Request Modal-->

            <!--begin::CompOff Credit Request Modal-->
            <?php echo $this->include('partials/profile/right-sidebar/compoff-credit-request-modal'); ?>
            <!--End::CompOff Credit Request Modal-->

            <!--begin::CompOff Minutes Utilization Modal-->
            <?php echo $this->include('partials/profile/right-sidebar/compoff-minutes-utilization-modal'); ?>
            <!--End::CompOff Minutes Utilization Modal-->

        </div>
    </div>
</div>