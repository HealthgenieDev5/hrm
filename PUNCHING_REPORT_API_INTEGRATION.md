# Punching Report - API Integration Guide

## Overview

This guide shows how to integrate the Attendance API into your existing punching report to display calculated attendance data (work hours, reductions, deductions, etc.).

---

## 🎯 What You Want to See in Punching Report

Currently your punching report shows:
- Employee name, department, company
- IN time, OUT time
- Shift timings
- Status (P/A/L/H/W/O)

**With API integration, you'll also see:**
- ✅ Work hours (original and adjusted)
- ✅ Reduction applied (Yes/No)
- ✅ Reduction percentage (66.67%, 50%, etc.)
- ✅ Minutes reduced (200 mins, etc.)
- ✅ Late coming minutes
- ✅ Early going minutes
- ✅ Deductions applied
- ✅ Half-day/Absent status based on work hours

---

## 📝 Integration Steps

### Step 1: Modify the Punching Report Controller

**File:** `app/Controllers/Reports/Punching.php`

Add this method to fetch API calculated data:

```php
/**
 * Get calculated attendance data from API
 *
 * @param int $employeeId Employee ID
 * @param int $shiftId Shift ID
 * @param string $date Date (Y-m-d format)
 * @return array|null API calculated data or null
 */
private function getApiCalculatedData($employeeId, $shiftId, $date)
{
    // Check if API is enabled
    if (getenv('USE_ATTENDANCE_API') !== 'true') {
        return null;
    }

    try {
        $apiClient = new \App\Services\AttendanceApiClient();

        // Call API with minimal data
        $result = $apiClient->processSingleDay(
            $employeeId,
            $shiftId,
            $date
        );

        return $result;

    } catch (\Exception $e) {
        log_message('error', "API call failed for employee {$employeeId}: " . $e->getMessage());
        return null;
    }
}
```

### Step 2: Modify getPunchingReports Method

Find this section in your `getPunchingReports()` method (around line 190-220):

```php
if ($employee_data_row['internal_employee_id'] ==  $punching_data_row['Empcode']) {
    $temp_array['internal_employee_id']    = $punching_data_row['Empcode'];
    $temp_array['employee_name']           = $employee_data_row['employee_name'];
    $temp_array['company_short_name']      = $employee_data_row['company_short_name'];
    $temp_array['department_name']         = $employee_data_row['department_name'];
    // ... existing code ...
```

**Add API integration after the existing fields:**

```php
if ($employee_data_row['internal_employee_id'] ==  $punching_data_row['Empcode']) {
    $temp_array['internal_employee_id']    = $punching_data_row['Empcode'];
    $temp_array['employee_name']           = $employee_data_row['employee_name'];
    $temp_array['company_short_name']      = $employee_data_row['company_short_name'];
    $temp_array['department_name']         = $employee_data_row['department_name'];

    // ... your existing code ...

    // 🆕 ADD API INTEGRATION HERE
    // Get calculated data from API
    $apiData = $this->getApiCalculatedData(
        $employee_data_row['employee_id'],
        $employee_data_row['shift_id'] ?? 1, // Default shift if not set
        date('Y-m-d', strtotime($punching_data_row['DateString']))
    );

    if ($apiData) {
        // Add API calculated fields to the report
        $temp_array['api_work_hours_original']    = $apiData['work_hours_original'];
        $temp_array['api_work_hours_adjusted']    = $apiData['work_hours_adjusted'];
        $temp_array['api_work_minutes_original']  = $apiData['work_minutes_original'];
        $temp_array['api_work_minutes_adjusted']  = $apiData['work_minutes_adjusted'];
        $temp_array['api_reduction_applied']      = $apiData['reduction_applied'];
        $temp_array['api_reduction_percentage']   = $apiData['reduction_percentage'];
        $temp_array['api_minutes_reduced']        = $apiData['minutes_reduced'];
        $temp_array['api_late_coming_minutes']    = $apiData['late_coming_minutes'];
        $temp_array['api_early_going_minutes']    = $apiData['early_going_minutes'];
        $temp_array['api_deduction_minutes']      = $apiData['deduction_minutes'];
        $temp_array['api_is_present']             = $apiData['is_present'];
        $temp_array['api_is_absent']              = $apiData['is_absent'];
        $temp_array['api_is_half_day']            = $apiData['is_half_day'];
        $temp_array['api_shift_type']             = $apiData['shift_type'];
        $temp_array['api_calculated']             = true;
    } else {
        // API not available or disabled
        $temp_array['api_calculated'] = false;
    }

    // ... rest of your existing code ...
```

### Step 3: Update the View to Display API Data

**File:** `app/Views/Reports/PunchingReport.php` (or wherever your view is)

Find your datatable columns configuration and add new columns:

```javascript
columns: [
    // ... your existing columns ...
    { data: 'INTime', title: 'IN Time' },
    { data: 'OUTTime', title: 'OUT Time' },

    // 🆕 ADD THESE NEW COLUMNS
    {
        data: 'api_work_hours_original',
        title: 'Work Hours',
        render: function(data, type, row) {
            if (row.api_calculated) {
                return data || '00:00';
            }
            return '-';
        }
    },
    {
        data: 'api_work_hours_adjusted',
        title: 'Adjusted Hours',
        render: function(data, type, row) {
            if (row.api_calculated && row.api_reduction_applied) {
                return '<span class="badge badge-warning">' + data + '</span>';
            } else if (row.api_calculated) {
                return data || '00:00';
            }
            return '-';
        }
    },
    {
        data: 'api_reduction_applied',
        title: 'Reduction',
        render: function(data, type, row) {
            if (row.api_calculated && data) {
                return '<span class="badge badge-danger">' + row.api_reduction_percentage + '%</span>';
            }
            return '-';
        }
    },
    {
        data: 'api_late_coming_minutes',
        title: 'Late (min)',
        render: function(data, type, row) {
            if (row.api_calculated && data > 0) {
                return '<span class="text-danger">' + data + '</span>';
            }
            return '-';
        }
    },
    {
        data: 'api_early_going_minutes',
        title: 'Early (min)',
        render: function(data, type, row) {
            if (row.api_calculated && data > 0) {
                return '<span class="text-warning">' + data + '</span>';
            }
            return '-';
        }
    },
    {
        data: 'api_is_present',
        title: 'API Status',
        render: function(data, type, row) {
            if (!row.api_calculated) {
                return '<span class="badge badge-secondary">-</span>';
            }

            if (row.api_is_absent === 'yes') {
                return '<span class="badge badge-danger">Absent</span>';
            } else if (row.api_is_half_day === 'yes') {
                return '<span class="badge badge-warning">Half Day</span>';
            } else if (row.api_is_present === 'yes') {
                return '<span class="badge badge-success">Present</span>';
            }
            return '-';
        }
    },

    // ... your existing status column ...
]
```

---

## 🎨 Option: Show API Data in a Popup/Modal

If you don't want to clutter the main table, you can show API details in a popup:

### Add a "Details" Button Column

```javascript
{
    data: null,
    title: 'Details',
    render: function(data, type, row) {
        if (row.api_calculated) {
            return '<button class="btn btn-sm btn-info view-api-details" data-row="' +
                   encodeURIComponent(JSON.stringify(row)) + '">View Details</button>';
        }
        return '-';
    }
}
```

### Add Modal HTML (at the end of your view file)

```html
<!-- API Details Modal -->
<div class="modal fade" id="apiDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Calculation Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Employee</th>
                        <td id="detail-employee"></td>
                        <th>Date</th>
                        <td id="detail-date"></td>
                    </tr>
                    <tr>
                        <th>Shift Type</th>
                        <td id="detail-shift-type"></td>
                        <th>Shift Code</th>
                        <td id="detail-shift-code"></td>
                    </tr>
                    <tr>
                        <th>IN Time (Original)</th>
                        <td id="detail-in-original"></td>
                        <th>OUT Time (Original)</th>
                        <td id="detail-out-original"></td>
                    </tr>
                    <tr class="reduction-row" style="display: none;">
                        <th>IN Time (Adjusted)</th>
                        <td id="detail-in-adjusted"></td>
                        <th>OUT Time (Adjusted)</th>
                        <td id="detail-out-adjusted"></td>
                    </tr>
                    <tr>
                        <th>Work Hours (Original)</th>
                        <td id="detail-work-original"></td>
                        <th>Work Hours (Adjusted)</th>
                        <td id="detail-work-adjusted"></td>
                    </tr>
                    <tr class="reduction-row" style="display: none;">
                        <th>Reduction Applied</th>
                        <td id="detail-reduction"></td>
                        <th>Minutes Reduced</th>
                        <td id="detail-minutes-reduced"></td>
                    </tr>
                    <tr>
                        <th>Late Coming</th>
                        <td id="detail-late"></td>
                        <th>Early Going</th>
                        <td id="detail-early"></td>
                    </tr>
                    <tr>
                        <th>Total Deductions</th>
                        <td id="detail-deductions"></td>
                        <th>Status</th>
                        <td id="detail-status"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
```

### Add JavaScript to Handle Modal

```javascript
$(document).on('click', '.view-api-details', function() {
    var rowData = JSON.parse(decodeURIComponent($(this).data('row')));

    // Populate modal
    $('#detail-employee').text(rowData.employee_name);
    $('#detail-date').text(rowData.DateString);
    $('#detail-shift-type').text(rowData.api_shift_type.toUpperCase());
    $('#detail-shift-code').text(rowData.shift_code);
    $('#detail-in-original').text(rowData.INTime);
    $('#detail-out-original').text(rowData.OUTTime);
    $('#detail-work-original').text(rowData.api_work_hours_original);
    $('#detail-work-adjusted').text(rowData.api_work_hours_adjusted);
    $('#detail-late').text(rowData.api_late_coming_minutes + ' min');
    $('#detail-early').text(rowData.api_early_going_minutes + ' min');
    $('#detail-deductions').text(rowData.api_deduction_minutes + ' min');

    // Show reduction info if applicable
    if (rowData.api_reduction_applied) {
        $('.reduction-row').show();
        $('#detail-in-adjusted').text(rowData.api_punch_in_adjusted || '-');
        $('#detail-out-adjusted').text(rowData.api_punch_out_adjusted || '-');
        $('#detail-reduction').html('<span class="badge badge-danger">' +
                                   rowData.api_reduction_percentage + '%</span>');
        $('#detail-minutes-reduced').text(rowData.api_minutes_reduced + ' min');
    } else {
        $('.reduction-row').hide();
    }

    // Status
    var statusHtml = '';
    if (rowData.api_is_present === 'yes') {
        statusHtml = '<span class="badge badge-success">Present</span>';
    } else if (rowData.api_is_half_day === 'yes') {
        statusHtml = '<span class="badge badge-warning">Half Day</span>';
    } else if (rowData.api_is_absent === 'yes') {
        statusHtml = '<span class="badge badge-danger">Absent</span>';
    }
    $('#detail-status').html(statusHtml);

    // Show modal
    $('#apiDetailsModal').modal('show');
});
```

---

## 🚀 Quick Implementation

### Minimal Changes Approach

If you want to start simple, just add these 3 columns to your existing report:

1. **Adjusted Work Hours** - Shows calculated work hours (with reduction if applicable)
2. **Reduction %** - Shows if reduction was applied
3. **View Details** - Button to show full API data in popup

This way your existing report stays the same, but users can see API calculated data when needed.

---

## 📊 Example Output

Your punching report will show:

| Employee | Date | IN | OUT | Work Hrs | Adj Hrs | Reduction | Late | Early | Status | Details |
|----------|------|----|----|----------|---------|-----------|------|-------|--------|---------|
| John Doe | 11-Nov | 09:00 | 19:00 | 10:00 | 06:40 | 66.67% | 0 | 0 | Present | [View] |
| Jane Smith | 11-Nov | 09:30 | 18:00 | 08:30 | 08:30 | - | 30 | 0 | Present | [View] |

---

## 🔧 Testing

1. **Enable API in .env:**
   ```env
   USE_ATTENDANCE_API=true
   ```

2. **Sync employee data:**
   ```bash
   php spark sync:api-database
   ```

3. **View punching report:**
   - Navigate to your punching report
   - Select an employee and date range
   - You should see the new API calculated columns

4. **Click "View Details":**
   - Should show popup with complete calculation breakdown

---

## 💡 Tips

- Start by testing with 1-2 employees first
- Compare API calculated hours with your existing calculations
- If API is down, the report still works (shows "-" in API columns)
- You can enable/disable API globally with `USE_ATTENDANCE_API` env variable

---

## 📝 Summary

**What you need to do:**

1. ✅ Add `getApiCalculatedData()` method to `Punching.php` controller
2. ✅ Modify `getPunchingReports()` to call API for each employee/date
3. ✅ Add new columns to your datatable view
4. ✅ (Optional) Add modal popup for detailed view
5. ✅ Enable API in `.env` and test

The API will automatically handle:
- Regular vs reduce shift detection
- Work hour calculations
- Reduction applications
- Late coming/early going
- Half-day/absent determination

Your report will show all this data!
