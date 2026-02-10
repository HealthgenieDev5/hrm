<?php
/*begin::Leave Approval*/

use App\Controllers\Approval\Leave as LeaveApproval;

$routes->get('/backend/administrative/leaveapproval', [LeaveApproval::class, 'index']);
$routes->match(['get', 'post'], '/ajax/get-all-leave-approval-requests', [LeaveApproval::class, 'getAllLeaveApprovalRequests']);
$routes->match(['get', 'post'], '/ajax/administrative/get-leave-request', [LeaveApproval::class, 'getLeaveRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/approve-leave-request', [LeaveApproval::class, 'approveLeaveRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/reject-leave-request', [LeaveApproval::class, 'rejectLeaveRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/cancel-leave-request', [LeaveApproval::class, 'cancelLeaveRequest']);
/*end::Leave Approval*/
