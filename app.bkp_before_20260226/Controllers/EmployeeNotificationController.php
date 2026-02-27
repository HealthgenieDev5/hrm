<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Notification\EmployeeNotificationModel;
use App\Models\Notification\NotificationReadModel;
use App\Models\Notification\NotificationEmailLogModel;
use App\Models\EmployeeModel;

class EmployeeNotificationController extends BaseController
{
    protected $notificationModel;
    protected $notificationReadModel;
    protected $emailLogModel;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->notificationModel = new EmployeeNotificationModel();
        $this->notificationReadModel = new NotificationReadModel();
        $this->emailLogModel = new NotificationEmailLogModel();
    }

    /**
     * List all notifications (Available for all employees)
     */
    public function index()
    {
        $currentUser = session()->get('current_user');
        $employeeId = $currentUser['employee_id'];
        $isAdmin = in_array($currentUser['role'], ['superuser', 'hr']) || in_array($employeeId, ['40', '93']);

        $data = [
            'page_title'            => 'Employee Notifications',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'is_admin'              => $isAdmin,
            'current_employee_id'   => $employeeId,
        ];

        return view('Notifications/Index', $data);
    }

    /**
     * Get all notifications via AJAX (Available for all employees)
     * Employees see only notifications targeted to them
     * Admins see all notifications
     */
    public function getAllNotifications()
    {
        $currentUser = session()->get('current_user');
        $employeeId = $currentUser['employee_id'];
        $isAdmin = in_array($currentUser['role'], ['superuser', 'hr']) || in_array($employeeId, ['40', '93']);



        $query = $this->notificationModel
            ->select('employee_notifications.*')
            ->select("CONCAT(employees.first_name, ' ', employees.last_name) as created_by_name")
            ->select('(SELECT COUNT(*) FROM notification_reads WHERE notification_reads.notification_id = employee_notifications.id) as read_count')
            ->join('employees', 'employees.id = employee_notifications.created_by', 'left');

        // If not admin, filter notifications for current employee only
        if (!$isAdmin) {
            $query->where("(employee_notifications.target_employees IS NULL OR JSON_CONTAINS(employee_notifications.target_employees, '\"$employeeId\"'))");
        }

        $notifications = $query->orderBy('created_at', 'DESC')->findAll();

        // Add read status for current employee
        foreach ($notifications as &$notification) {
            $isRead = $this->notificationReadModel->isRead($notification['id'], $employeeId);
            $notification['is_read_by_me'] = $isRead;
        }

        return $this->response->setJSON($notifications);
    }


    public function create()
    {

        $currentUser = session()->get('current_user');
        $employeeId = $currentUser['employee_id'];
        $canSeeAllEmployees = in_array($currentUser['role'], ['superuser', 'admin', 'hr', 'hod', 'tl', 'manager']) || in_array($employeeId, ['385']);
        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->select('id, internal_employee_id, first_name, last_name, work_email')
            ->orderBy('first_name', 'ASC')
            ->findAll();

        if ($canSeeAllEmployees) {
            $employees = $employeeModel->select('id, internal_employee_id, first_name, last_name, work_email')
                ->orderBy('first_name', 'ASC')
                ->findAll();
        } else {
            $employees = $employeeModel->select('id, internal_employee_id, first_name, last_name, work_email')
                ->where('id', $employeeId)
                ->findAll();
        }

        $data = [
            'page_title'            => 'Create Notification',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $employees,
            'canSeeAllEmployees'    => $canSeeAllEmployees,
            'current_employee_id'   => $employeeId,
        ];

        return view('Notifications/Create', $data);
    }

    /**
     * Store new notification
     */
    public function store()
    {
        // Authorization check commented out to allow all employees to create notifications
        // if (
        //     !in_array(session()->get('current_user')['role'], ['superuser', 'hr'])
        //     && !in_array(session()->get('current_user')['employee_id'], ['40', '93'])
        // ) {
        //     return $this->response->setJSON([
        //         'response_type' => 'error',
        //         'response_description' => 'Unauthorized'
        //     ]);
        // }

        $response_array = array();

        $rules = [
            'title' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Title is required',
                    'min_length' => 'Title must be at least 3 characters',
                    'max_length' => 'Title cannot exceed 255 characters'
                ]
            ],
            'description' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Description is required'
                ]
            ],
            'notification_type' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Notification type is required'
                ]
            ],
            'event_date' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Event date is required',
                    'valid_date' => 'Please enter a valid date'
                ]
            ],
        ];

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $targetEmployees = $this->request->getPost('target_employees');

            // Handle reminder dates from repeater
            $reminderDates = $this->request->getPost('reminder_dates');
            $reminder1 = null;
            $reminder2 = null;
            $reminder3 = null;

            if ($reminderDates && is_array($reminderDates)) {
                // Extract reminder dates from repeater array
                $validReminders = array_filter($reminderDates, function ($item) {
                    return !empty($item['reminder_date']);
                });

                // Assign to individual fields (max 3)
                $validReminders = array_values($validReminders);
                if (isset($validReminders[0]['reminder_date'])) {
                    $reminder1 = $validReminders[0]['reminder_date'];
                }
                if (isset($validReminders[1]['reminder_date'])) {
                    $reminder2 = $validReminders[1]['reminder_date'];
                }
                if (isset($validReminders[2]['reminder_date'])) {
                    $reminder3 = $validReminders[2]['reminder_date'];
                }
            }

            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'notification_type' => $this->request->getPost('notification_type'),
                'event_date' => $this->request->getPost('event_date'),
                'reminder_1_date' => $reminder1,
                'reminder_2_date' => $reminder2,
                'reminder_3_date' => $reminder3,
                'target_employees' => ($targetEmployees && count($targetEmployees) > 0) ? json_encode($targetEmployees) : null,
                'is_active' => $this->request->getPost('is_active') ?? 1,
                'created_by' => session()->get('current_user')['employee_id'],
            ];

            if ($this->notificationModel->insert($data)) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Notification created successfully';
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Failed to create notification';
            }
        }

        return $this->response->setJSON($response_array);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $currentUser = session()->get('current_user');
        $employeeId = $currentUser['employee_id'];
        $isAdmin = in_array($currentUser['role'], ['superuser', 'hr']) || in_array($employeeId, ['40', '93']);

        $notification = $this->notificationModel->find($id);

        if (!$notification) {
            return redirect()->to(base_url('/backend/notifications'));
        }

        // Check if user is admin OR is the creator of the notification
        if (!$isAdmin && $notification['created_by'] != $employeeId) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->select('id, internal_employee_id, first_name, last_name, work_email')
            ->orderBy('first_name', 'ASC')
            ->findAll();

        $data = [
            'page_title'            => 'Edit Notification',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'notification'          => $notification,
            'employees'             => $employees,
            'is_admin'              => $isAdmin,
            'current_employee_id'   => $employeeId,
        ];

        return view('Notifications/Edit', $data);
    }

    /**
     * Update notification
     */
    public function update($id)
    {
        $currentUser = session()->get('current_user');
        $employeeId = $currentUser['employee_id'];
        $isAdmin = in_array($currentUser['role'], ['superuser', 'hr']) || in_array($employeeId, ['40', '93']);

        $notification = $this->notificationModel->find($id);

        if (!$notification) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Notification not found'
            ]);
        }

        // Check if user is admin OR is the creator of the notification
        if (!$isAdmin && $notification['created_by'] != $employeeId) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Unauthorized'
            ]);
        }

        $response_array = array();

        $rules = [
            'title' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Title is required',
                    'min_length' => 'Title must be at least 3 characters',
                    'max_length' => 'Title cannot exceed 255 characters'
                ]
            ],
            'description' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Description is required'
                ]
            ],
            'notification_type' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Notification type is required'
                ]
            ],
            'event_date' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Event date is required',
                    'valid_date' => 'Please enter a valid date'
                ]
            ],
        ];

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $targetEmployees = $this->request->getPost('target_employees');

            // Handle reminder dates from repeater
            $reminderDates = $this->request->getPost('reminder_dates');
            $reminder1 = null;
            $reminder2 = null;
            $reminder3 = null;

            if ($reminderDates && is_array($reminderDates)) {
                // Extract reminder dates from repeater array
                $validReminders = array_filter($reminderDates, function ($item) {
                    return !empty($item['reminder_date']);
                });

                // Assign to individual fields (max 3)
                $validReminders = array_values($validReminders);
                if (isset($validReminders[0]['reminder_date'])) {
                    $reminder1 = $validReminders[0]['reminder_date'];
                }
                if (isset($validReminders[1]['reminder_date'])) {
                    $reminder2 = $validReminders[1]['reminder_date'];
                }
                if (isset($validReminders[2]['reminder_date'])) {
                    $reminder3 = $validReminders[2]['reminder_date'];
                }
            }

            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'notification_type' => $this->request->getPost('notification_type'),
                'event_date' => $this->request->getPost('event_date'),
                'reminder_1_date' => $reminder1,
                'reminder_2_date' => $reminder2,
                'reminder_3_date' => $reminder3,
                'target_employees' => ($targetEmployees && count($targetEmployees) > 0) ? json_encode($targetEmployees) : null,
                'is_active' => $this->request->getPost('is_active') ?? 1,
            ];

            if ($this->notificationModel->update($id, $data)) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Notification updated successfully';
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Failed to update notification';
            }
        }

        return $this->response->setJSON($response_array);
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        $currentUser = session()->get('current_user');
        $employeeId = $currentUser['employee_id'];
        $isAdmin = in_array($currentUser['role'], ['superuser', 'hr']) || in_array($employeeId, ['40', '93']);

        $notification = $this->notificationModel->find($id);

        if (!$notification) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Notification not found'
            ]);
        }

        // Check if user is admin OR is the creator of the notification
        if (!$isAdmin && $notification['created_by'] != $employeeId) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Unauthorized'
            ]);
        }

        if ($this->notificationModel->delete($id)) {
            return $this->response->setJSON([
                'response_type' => 'success',
                'response_description' => 'Notification deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'response_type' => 'error',
            'response_description' => 'Failed to delete notification'
        ]);
    }

    /**
     * Get dashboard notifications for current employee
     */
    public function getDashboardNotifications()
    {
        $employeeId = session()->get('current_user')['employee_id'];
        $notifications = $this->notificationModel->getUnreadNotificationsForEmployee($employeeId);

        // Collect all unique related_employee_ids in one pass
        $relatedEmployeeIds = array_values(array_unique(array_filter(
            array_column($notifications, 'related_employee_id')
        )));

        $relatedEmployeesMap = [];
        if (!empty($relatedEmployeeIds)) {
            $employees = (new EmployeeModel())
                ->select('employees.id, employees.first_name, employees.last_name, employees.attachment, employees.work_mobile, employees.work_email, employees.work_phone_extension_number, employees.internal_employee_id, designations.designation_name, departments.department_name, companies.company_short_name')
                ->join('designations', 'designations.id = employees.designation_id', 'left')
                ->join('departments', 'departments.id = employees.department_id', 'left')
                ->join('companies', 'companies.id = employees.company_id', 'left')
                ->whereIn('employees.id', $relatedEmployeeIds)
                ->findAll();
            $relatedEmployeesMap = array_column($employees, null, 'id');
        }

        // Enrich each notification using the in-memory map
        foreach ($notifications as &$notification) {
            $notification['employee_image']       = null;
            $notification['employee_mobile']      = null;
            $notification['employee_email']       = null;
            $notification['employee_extension']   = null;
            $notification['employee_code']        = null;
            $notification['employee_name']        = null;
            $notification['employee_first_name']  = null;
            $notification['employee_designation'] = null;
            $notification['employee_department']  = null;
            $notification['employee_company']     = null;
            $relatedId = $notification['related_employee_id'] ?? null;
            if ($relatedId && isset($relatedEmployeesMap[$relatedId])) {
                $emp = $relatedEmployeesMap[$relatedId];
                if (!empty($emp['attachment'])) {
                    $attachment = json_decode($emp['attachment'], true);
                    if (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) {
                        $notification['employee_image'] = base_url($attachment['avatar']['file']);
                    }
                }
                $notification['employee_mobile']      = $emp['work_mobile'] ?? null;
                $notification['employee_email']       = $emp['work_email'] ?? null;
                $notification['employee_extension']   = $emp['work_phone_extension_number'] ?? null;
                $notification['employee_code']        = $emp['internal_employee_id'] ?? null;
                $notification['employee_name']        = trim(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? ''));
                $notification['employee_first_name']  = $emp['first_name'] ?? null;
                $notification['employee_designation'] = $emp['designation_name'] ?? null;
                $notification['employee_department']  = $emp['department_name'] ?? null;
                $notification['employee_company']     = $emp['company_short_name'] ?? null;
            }
        }
        unset($notification);

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $notificationId = $this->request->getPost('notification_id');
        $employeeId = session()->get('current_user')['employee_id'];

        if (!$notificationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification ID required'
            ]);
        }

        if ($this->notificationReadModel->markAsRead($notificationId, $employeeId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to mark as read'
        ]);
    }
}
