<div class="card shadow-sm mb-5">
    <div class="card-body">
        <div class="row d-flex flex-wrap gy-3 g-x-3 stats-container">
            <div class="col-12 text-center py-5">
                <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
                <div class="mt-3 text-muted">Loading attendance statistics...</div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('javascript') ?>
<script>
    function get_attendance_stats() {
        console.log("hello from inside of get_attendance_stats");

        function get_icon(value) {
            if (value <= 10) {
                icon = '<i class="fa-solid fa-arrow-down text-success me-2"></i>';
            } else if (value > 10 && value <= 20) {
                icon = '<i class="fa-solid fa-arrow-up text-warning me-2"></i>';
            } else {
                icon = '<i class="fa-solid fa-arrow-up text-danger me-2"></i>';
            }
            return icon;
        }

        function get_stat_html(value, title, icon = false) {
            var html = `<div class="border border-gray-400 border-dashed rounded w-auto min-w-125px py-3 px-4 me-3">
                            <div class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                    ${icon ? icon : get_icon(value)}
                                </span>
                                <div class="fs-5 fw-bolder counted">${value} Minutes</div>
                            </div>
                            <div class="fw-bold fs-9 text-gray-600">${title}</div>
                        </div>`;
            return html;
        }

        $.ajax({
            method: "get",
            url: "<?php echo base_url('ajax/profile/get-attendance-stats'); ?>",
            success: function(stats) {
                console.log(stats);

                $('.stats-container').html(
                    get_stat_html(stats.seven_days_late_minutes_avg, "Avg Late in last 7 days") +
                    get_stat_html(stats.fifteen_days_late_minutes_avg, "Avg Late in last 15 days") +
                    get_stat_html(stats.current_month_late_minutes_avg, "Avg Late in Current Month") +
                    get_stat_html(stats.current_month_late_minutes, "Total Late in Current Month") +
                    get_stat_html(stats.seven_days_early_going_minutes_avg, "Avg Early Going in last 7 days") +
                    get_stat_html(stats.fifteen_days_early_going_minutes_avg, "Avg Early Going in last 15 days") +
                    get_stat_html(stats.current_month_early_going_minutes_avg, "Avg Early Going in Current Month") +
                    get_stat_html(stats.current_month_early_going_minutes, "Total Early Going in Current Month") +
                    get_stat_html(stats.current_date_early_going_minutes, "Total Early Going Today") +
                    get_stat_html(stats.balance_grace, "Balance Grace", `<i class="fa-solid fa-clock text-primary me-2"></i>`)
                );
            },
            failed: function() {
                console.log('error at ajax');
                $('.stats-container').html('<div class="col-12 text-center py-5 text-danger"><i class="fa-solid fa-exclamation-triangle me-2"></i>Failed to load attendance statistics</div>');
            }
        });
    }
</script>
<?= $this->endSection() ?>