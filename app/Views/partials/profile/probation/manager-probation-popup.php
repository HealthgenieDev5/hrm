<style>
    .swal2-container {
        z-index: 1100 !important;
    }

    .swal2-select {
        position: relative;
        z-index: 1200;
    }
</style>
<?= $this->section('javascript') ?>
<script type="text/javascript">
    $(document).ready(function() {
        const probationPopUpEmployees = JSON.parse('<?php echo json_encode($probationPopUpEmployees); ?>');
        let htmlContent = '<ul class="list-group text-start">';
        let cancellable = true;
        probationPopUpEmployees.forEach(employee => {
            cancellable = (cancellable == true && employee.cancellable == true) ? true : false;
            htmlContent += `
                <li class="employee-dropdown list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">${employee.employee_name}</div>

                            <small style="font-size: 0.70rem;">is currently on ${employee.probation_status}</small>
                        </div>
                        <span class="badge bg-white border border-primary p-0">
                             <select id="employee_${employee.employee_id}">
                                <option value="" disabled selected>Select an action</option>
                                ${employee.available_actions.map(action => 
                                    `<option value="${action}">${action}</option>`
                                ).join('')}
                            </select>
                        </span>
                    </div>
                    <div id="error_${employee.employee_id}" style="color: red; display: none; font-size: 0.70rem;">Please select an action!</div>
              </li>
            `;
        });
        htmlContent += '</ul>';

        Swal.fire({
            title: 'Manage Employees on Probation',
            html: htmlContent,
            padding: "1rem",
            confirmButtonText: 'Save',
            showCancelButton: cancellable,
            allowOutsideClick: false,
            preConfirm: () => {
                let valid = true;
                probationPopUpEmployees.forEach(employee => {
                    const selectedValue = $(`#employee_${employee.employee_id}`).val();
                    if (!selectedValue) {
                        $(`#error_${employee.employee_id}`).show();
                        valid = false;
                    } else {
                        $(`#error_${employee.employee_id}`).hide();
                    }
                });

                if (!valid) {
                    // Swal.showValidationMessage('Please select an action for all employees.');
                    return false;
                }

                const formData = {};
                probationPopUpEmployees.forEach(employee => {
                    formData[`${employee.employee_id}`] = $(`#employee_${employee.employee_id}`).val();
                });

                return formData;
            },
        }).then(async (result) => {

            console.log(result);
            if (result.isConfirmed) {
                const selectedData = {
                    'reponses': result.value
                };
                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('backend/master/employee/save-probation-response-of-hod'); ?>",
                        data: selectedData,
                        success: function(response) {
                            console.log(response);
                            Swal.fire('Saved!', 'Actions have been saved successfully.', 'success');
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to save actions. Please try again.', 'error');
                        },
                    });
                } catch (error) {
                    Swal.fire('Error', 'Failed to save actions. Please try again.', 'error');
                }
            }
            //showAnnouncementPopup();
        });
    })
</script>
<?= $this->endSection() ?>