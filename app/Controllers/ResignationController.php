<?php

namespace App\Controllers;

use App\Models\ResignationModel;
use App\Models\EmployeeModel;
use App\Models\CompanyModel;
use App\Models\ResignationRevisionModel;
use App\Models\ResignationHodResponseModel;
use App\Controllers\BaseController;
use App\Services\ResignationAutoCompleteService;

class ResignationController extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session = session();
    }

    /**
     * Main resignation dashboard
     */
    public function dashboard($company_id_for_filter = 'all_companies')
    {
        // Auto-complete overdue resignations (runs silently in background)
        // Commented to allow viewing overdue resignations in dashboard
        // try {
        //     ResignationAutoCompleteService::autoCompleteOverdue();
        // } catch (\Exception $e) {
        //     log_message('error', 'Failed to auto-complete resignations: ' . $e->getMessage());
        // }

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $data = [
            'page_title' => 'Resignation Tracking Dashboard',
            'current_controller' => $this->request->getUri()->getSegment(1),
            'Companies' => $Companies,
            'company_id_for_filter' => $company_id_for_filter,
        ];

        return view('Resignation/Dashboard', $data);
    }

    /**
     * Get dashboard statistics (AJAX)
     */
    public function getDashboardStats()
    {
        $ResignationModel = new ResignationModel();
        $company_id = $this->request->getPost('company_id');

        $stats = $ResignationModel->getStatistics($company_id);

        return $this->response->setJSON($stats);
    }

    /**
     * Get resignation reports for dashboard (AJAX)
     */
    public function getResignationReports()
    {
        $current_user = $this->session->get('current_user');
        $company_id = $this->request->getPost('company_id');

        $ResignationModel = new ResignationModel();
        $resignations = $ResignationModel->getActiveResignations(
            $company_id,
            $current_user['employee_id'],
            $current_user['role']
        );

        $data = [];
        foreach ($resignations as $row) {
            $remaining_days = (int)$row['remaining_days'];

            // Determine alert status
            if ($remaining_days < 0) {
                $alert_status = 'overdue';
                $badge_class = 'bg-dark text-white';
                $badge_text = 'ACTIVE';
            } elseif ($remaining_days <= 7) {
                $alert_status = 'urgent';
                $badge_class = 'bg-danger text-white';
                $badge_text = 'ACTIVE';
            } elseif ($remaining_days <= 14) {
                $alert_status = 'warning';
                $badge_class = 'bg-warning text-dark';
                $badge_text = 'ACTIVE';
            } else {
                $alert_status = 'normal';
                $badge_class = 'bg-success text-white';
                $badge_text = 'ACTIVE';
            }

            // Format calculated last working day
            $calculated_last_working_day = [
                'formatted' => date('d M Y', strtotime($row['calculated_last_working_day'])),
                'ordering' => strtotime($row['calculated_last_working_day'])
            ];

            // Format HOD response
            $hodResponse = $row['hod_response'] ?? null;
            $hodResponseFormatted = 'N/A';
            if ($hodResponse === 'accept') {
                $hodResponseFormatted = '<span class="badge bg-success">Accepted</span>';
            } elseif ($hodResponse === 'rejected') {
                $hodResponseFormatted = '<span class="badge bg-danger">Rejected</span>';
            } elseif ($hodResponse === 'try_to_retain') {
                $hodResponseFormatted = '<span class="badge bg-info">Try to Retain</span>';
            } elseif ($hodResponse === 'pending') {
                $hodResponseFormatted = '<span class="badge bg-warning text-dark">Pending</span>';
            } elseif ($hodResponse === 'too_early') {
                $hodResponseFormatted = '<span class="badge bg-secondary">Remind Later</span>';
            }
            $hodName = trim($row['hod_name'] ?? '');
            $hodDate = !empty($row['hod_response_date']) && in_array($hodResponse, ['accept', 'rejected', 'try_to_retain'])
                ? date('d M Y H:i', strtotime($row['hod_response_date']))
                : '';

            // Format Manager response
            $managerResponse = $row['manager_response'] ?? null;
            $managerResponseFormatted = match ($managerResponse) {
                'accept'        => '<span class="badge bg-success">Accepted</span>',
                'rejected'      => '<span class="badge bg-danger">Rejected</span>',
                'try_to_retain' => '<span class="badge bg-info">Try to Retain</span>',
                'too_early'     => '<span class="badge bg-secondary">Remind Later</span>',
                default         => '<span class="badge bg-warning text-dark">Pending</span>',
            };
            $managerName = trim($row['manager_name'] ?? '');
            $managerDate = in_array($managerResponse, ['accept', 'rejected', 'try_to_retain']) && !empty($row['manager_response_date'])
                ? date('d M Y H:i', strtotime($row['manager_response_date']))
                : '';

            // Determine retention outcome display
            $retentionOutcomeDisplay = '';
            $resignationStatus = $row['resignation_status'];
            if ($resignationStatus === 'retained') {
                $retentionOutcomeDisplay = '<span class="badge bg-success">Retained</span>';
            } elseif ($resignationStatus === 'retention_failed') {
                $retentionOutcomeDisplay = '<span class="badge bg-danger">Retention Failed</span>';
            } elseif ($hodResponse === 'try_to_retain' || $managerResponse === 'try_to_retain') {
                $retentionOutcomeDisplay = '<span class="badge bg-info">Trying to Retain</span>';
            }

            $data[] = [
                'resignation_id' => $row['resignation_id'],
                'internal_employee_id' => $row['internal_employee_id'],
                'employee_name' => $row['employee_name'],
                'department_name' => $row['department_name'] ?? 'N/A',
                'company_short_name' => $row['company_short_name'] ?? 'N/A',
                'resignation_date' => date('d M Y', strtotime($row['resignation_date'])),
                'notice_period' => $row['notice_period'],
                'calculated_last_working_day' => $calculated_last_working_day,
                'remaining_days' => $remaining_days,
                'days_since_resignation' => $row['days_since_resignation'],
                'buyout_days' => $row['buyout_days'] ?? 0,
                'resignation_reason' => $row['resignation_reason'] ?? '',
                'resignation_status' => $row['resignation_status'],
                'recorded_by_hr' => $row['recorded_by_hr'] ?? 'N/A',
                'alert_status' => $alert_status,
                'badge_class' => $badge_class,
                'badge_text' => $badge_text,
                'hod_response_display' => $hodResponseFormatted,
                'hod_name' => $hodName,
                'hod_response_date' => $hodDate,
                'hod_response' => $hodResponse,
                'manager_response_display' => $managerResponseFormatted,
                'manager_name' => $managerName,
                'manager_response_date' => $managerDate,
                'manager_response' => $managerResponse,
                'retention_outcome_display' => $retentionOutcomeDisplay,
                'last_working_date' => $row['last_working_date'] ?? '',
                'remarks' => $row['remarks'] ?? '',
                'current_status_text' => match ($row['resignation_status']) {
                    'active'           => 'Serving Notice Period',
                    'completed'        => 'Completed',
                    'abscond'          => 'Abscond',
                    'retained'         => 'Retained',
                    'retention_failed' => 'Retention Failed',
                    default            => $row['resignation_status'] ?? '',
                },
            ];
        }

        return $this->response->setJSON($data);
    }

    /**
     * Get completed resignations (AJAX)
     */
    public function getCompletedResignations()
    {
        $current_user = $this->session->get('current_user');
        $company_id = $this->request->getPost('company_id');

        $ResignationModel = new ResignationModel();
        $resignations = $ResignationModel->getCompletedResignations(
            $company_id,
            $current_user['employee_id'],
            $current_user['role']
        );

        $data = [];
        foreach ($resignations as $row) {
            // Format HOD response
            $hodResponse = $row['hod_response'] ?? null;
            $hodResponseFormatted = 'N/A';
            if ($hodResponse === 'accept') {
                $hodResponseFormatted = '<span class="badge bg-success">Accepted</span>';
            } elseif ($hodResponse === 'rejected') {
                $hodResponseFormatted = '<span class="badge bg-danger">Rejected</span>';
            } elseif ($hodResponse === 'try_to_retain') {
                $hodResponseFormatted = '<span class="badge bg-info">Try to Retain</span>';
            } elseif ($hodResponse === 'pending') {
                $hodResponseFormatted = '<span class="badge bg-warning text-dark">Pending</span>';
            } elseif ($hodResponse === 'too_early') {
                $hodResponseFormatted = '<span class="badge bg-secondary">Remind Later</span>';
            }
            $hodName = trim($row['hod_name'] ?? '');
            $hodDate = !empty($row['hod_response_date']) && in_array($hodResponse, ['accept', 'rejected', 'try_to_retain'])
                ? date('d M Y H:i', strtotime($row['hod_response_date']))
                : '';

            // Format Manager response
            $managerResponse = $row['manager_response'] ?? null;
            $managerResponseFormatted = match ($managerResponse) {
                'accept'        => '<span class="badge bg-success">Accepted</span>',
                'rejected'      => '<span class="badge bg-danger">Rejected</span>',
                'try_to_retain' => '<span class="badge bg-info">Try to Retain</span>',
                'too_early'     => '<span class="badge bg-secondary">Remind Later</span>',
                default         => '<span class="badge bg-warning text-dark">Pending</span>',
            };
            $managerName = trim($row['manager_name'] ?? '');
            $managerDate = in_array($managerResponse, ['accept', 'rejected', 'try_to_retain']) && !empty($row['manager_response_date'])
                ? date('d M Y H:i', strtotime($row['manager_response_date']))
                : '';

            $data[] = [
                'resignation_id' => $row['resignation_id'],
                'internal_employee_id' => $row['internal_employee_id'],
                'employee_name' => $row['employee_name'],
                'department_name' => $row['department_name'] ?? 'N/A',
                'company_short_name' => $row['company_short_name'] ?? 'N/A',
                'resignation_date' => date('d M Y', strtotime($row['resignation_date'])),
                'last_working_day' => date('d M Y', strtotime($row['calculated_last_working_day'])),
                'completed_on' => date('d M Y', strtotime($row['updated_at'])),
                'resignation_reason' => $row['resignation_reason'] ?? '',
                'hod_response_display' => $hodResponseFormatted,
                'hod_name' => $hodName,
                'hod_response_date' => $hodDate,
                'manager_response_display' => $managerResponseFormatted,
                'manager_name' => $managerName,
                'manager_response_date' => $managerDate,
            ];
        }

        return $this->response->setJSON($data);
    }

    /**
     * Get retained resignations (AJAX)
     */
    public function getRetainedResignations()
    {
        $current_user = $this->session->get('current_user');
        $company_id = $this->request->getPost('company_id');

        $ResignationModel = new ResignationModel();
        $resignations = $ResignationModel->getRetainedResignations(
            $company_id,
            $current_user['employee_id'],
            $current_user['role']
        );

        $data = [];
        foreach ($resignations as $row) {
            $hodResponse = $row['hod_response'] ?? null;
            $hodResponseFormatted = match ($hodResponse) {
                'accept'        => '<span class="badge bg-success">Accepted</span>',
                'rejected'      => '<span class="badge bg-danger">Rejected</span>',
                'try_to_retain' => '<span class="badge bg-info">Try to Retain</span>',
                'too_early'     => '<span class="badge bg-secondary">Remind Later</span>',
                default         => '<span class="badge bg-warning text-dark">Pending</span>',
            };
            $hodName = trim($row['hod_name'] ?? '');
            $hodDate = !empty($row['hod_response_date']) && in_array($hodResponse, ['accept', 'rejected', 'try_to_retain'])
                ? date('d M Y H:i', strtotime($row['hod_response_date']))
                : '';

            $managerResponse = $row['manager_response'] ?? null;
            $managerResponseFormatted = match ($managerResponse) {
                'accept'        => '<span class="badge bg-success">Accepted</span>',
                'rejected'      => '<span class="badge bg-danger">Rejected</span>',
                'try_to_retain' => '<span class="badge bg-info">Try to Retain</span>',
                'too_early'     => '<span class="badge bg-secondary">Remind Later</span>',
                default         => '<span class="badge bg-warning text-dark">Pending</span>',
            };
            $managerName = trim($row['manager_name'] ?? '');
            $managerDate = in_array($managerResponse, ['accept', 'rejected', 'try_to_retain']) && !empty($row['manager_response_date'])
                ? date('d M Y H:i', strtotime($row['manager_response_date']))
                : '';

            $data[] = [
                'resignation_id' => $row['resignation_id'],
                'internal_employee_id' => $row['internal_employee_id'],
                'employee_name' => $row['employee_name'],
                'department_name' => $row['department_name'] ?? 'N/A',
                'company_short_name' => $row['company_short_name'] ?? 'N/A',
                'resignation_date' => date('d M Y', strtotime($row['resignation_date'])),
                'retained_on' => date('d M Y', strtotime($row['updated_at'])),
                'resignation_reason' => $row['resignation_reason'] ?? '',
                'remarks' => $row['remarks'] ?? '',
                'hod_response_display' => $hodResponseFormatted,
                'hod_name' => $hodName,
                'hod_response_date' => $hodDate,
                'manager_response_display' => $managerResponseFormatted,
                'manager_name' => $managerName,
                'manager_response_date' => $managerDate,
            ];
        }

        return $this->response->setJSON($data);
    }

    /**
     * Get urgent resignation alerts (AJAX)
     */
    public function getResignationAlerts()
    {
        $current_user = $this->session->get('current_user');
        $company_id = $this->request->getPost('company_id');

        $ResignationModel = new ResignationModel();
        $resignations = $ResignationModel->getUrgentAlerts(
            $company_id,
            $current_user['employee_id'],
            $current_user['role']
        );

        $data = [];
        foreach ($resignations as $row) {
            $remaining_days = (int)$row['remaining_days'];

            // Format calculated last working day
            $calculated_last_working_day = [
                'formatted' => date('d M Y', strtotime($row['calculated_last_working_day'])),
                'ordering' => strtotime($row['calculated_last_working_day'])
            ];

            // Format HOD response
            $hodResponse = $row['hod_response'] ?? null;
            $hodResponseFormatted = 'N/A';
            if ($hodResponse === 'accept') {
                $hodResponseFormatted = '<span class="badge bg-success">Accepted</span>';
            } elseif ($hodResponse === 'rejected') {
                $hodResponseFormatted = '<span class="badge bg-danger">Rejected</span>';
            } elseif ($hodResponse === 'try_to_retain') {
                $hodResponseFormatted = '<span class="badge bg-info">Try to Retain</span>';
            } elseif ($hodResponse === 'pending') {
                $hodResponseFormatted = '<span class="badge bg-warning text-dark">Pending</span>';
            } elseif ($hodResponse === 'too_early') {
                $hodResponseFormatted = '<span class="badge bg-secondary">Remind Later</span>';
            }
            $hodName = trim($row['hod_name'] ?? '');
            $hodDate = !empty($row['hod_response_date']) && in_array($hodResponse, ['accept', 'rejected', 'try_to_retain'])
                ? date('d M Y H:i', strtotime($row['hod_response_date']))
                : '';

            // Format Manager response
            $managerResponse = $row['manager_response'] ?? null;
            $managerResponseFormatted = match ($managerResponse) {
                'accept'        => '<span class="badge bg-success">Accepted</span>',
                'rejected'      => '<span class="badge bg-danger">Rejected</span>',
                'try_to_retain' => '<span class="badge bg-info">Try to Retain</span>',
                'too_early'     => '<span class="badge bg-secondary">Remind Later</span>',
                default         => '<span class="badge bg-warning text-dark">Pending</span>',
            };
            $managerName = trim($row['manager_name'] ?? '');
            $managerDate = in_array($managerResponse, ['accept', 'rejected', 'try_to_retain']) && !empty($row['manager_response_date'])
                ? date('d M Y H:i', strtotime($row['manager_response_date']))
                : '';

            $data[] = [
                'resignation_id' => $row['resignation_id'],
                'internal_employee_id' => $row['internal_employee_id'],
                'employee_name' => $row['employee_name'],
                'department_name' => $row['department_name'] ?? 'N/A',
                'company_short_name' => $row['company_short_name'] ?? 'N/A',
                'resignation_date' => date('d M Y', strtotime($row['resignation_date'])),
                'notice_period' => $row['notice_period'],
                'calculated_last_working_day' => $calculated_last_working_day,
                'remaining_days' => $remaining_days,
                'buyout_days' => $row['buyout_days'] ?? 0,
                'resignation_status' => $row['resignation_status'],
                'resignation_reason' => $row['resignation_reason'] ?? '',
                'hod_response_display' => $hodResponseFormatted,
                'hod_name' => $hodName,
                'hod_response_date' => $hodDate,
                'manager_response_display' => $managerResponseFormatted,
                'manager_name' => $managerName,
                'manager_response_date' => $managerDate,
            ];
        }

        return $this->response->setJSON($data);
    }

    /**
     * Redirect to resignation dashboard
     */
    public function index()
    {
        return redirect()->to(base_url('resignation'));
    }

    /**
     * Show create resignation form
     */
    public function create()
    {
        $current_user = $this->session->get('current_user');
        if ($current_user['employee_id'] != 52) {
            return redirect()->to(base_url('resignation'))->with('error', 'You are not authorized to add resignations.');
        }

        $EmployeeModel = new EmployeeModel();
        $employees = $EmployeeModel->select('employees.*, companies.company_name, departments.department_name')
            ->join('companies', 'companies.id = employees.company_id', 'left')
            ->join('departments', 'departments.id = employees.department_id', 'left')
            ->where('employees.status', 'active')
            ->orderBy('employees.first_name', 'ASC')
            ->findAll();

        // Check if employee_id is passed in query parameter
        $preselected_employee_id = $this->request->getGet('employee_id');

        $data = [
            'page_title' => 'Add Resignation',
            'current_controller' => $this->request->getUri()->getSegment(1),
            'employees' => $employees,
            'preselected_employee_id' => $preselected_employee_id,
        ];

        return view('Resignation/Create', $data);
    }

    /**
     * Store new resignation
     */
    public function store()
    {
        $current_user_check = $this->session->get('current_user');
        if ($current_user_check['employee_id'] != 52) {
            return redirect()->to(base_url('resignation'))->with('error', 'You are not authorized to add resignations.');
        }

        $ResignationModel = new ResignationModel();
        $ResignationRevisionModel = new ResignationRevisionModel();
        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $data = [
            'employee_id' => $this->request->getPost('employee_id'),
            'resignation_date' => $this->request->getPost('resignation_date'),
            'resignation_reason' => $this->request->getPost('resignation_reason'),
            'buyout_days' => $this->request->getPost('buyout_days') ?? 0,
            'last_working_date' => $this->request->getPost('last_working_date') ?: null,
            'submitted_by_hr' => $current_user['employee_id'],
            'status' => 'active',
        ];

        $resignation_id = $ResignationModel->insert($data);

        if ($resignation_id) {
            // Save revision
            $ResignationRevisionModel->saveRevision(
                $resignation_id,
                $data,
                'created',
                $current_user['employee_id'],
                'Initial resignation record created'
            );
            // Notify reporting manager first; HOD is notified after manager gives a terminal response
            $employee = $EmployeeModel->select('employees.*, departments.hod_employee_id')
                ->join('departments', 'departments.id = employees.department_id', 'left')
                ->find($data['employee_id']);

            if ($employee && !empty($employee['reporting_manager_id'])) {
                $ResignationHodResponseModel->insert([
                    'resignation_id' => $resignation_id,
                    'employee_id'    => $employee['reporting_manager_id'],
                    'role'           => 'manager',
                    'response'       => 'pending',
                ]);
            }

            // Notify HR manager (293 — last ID from config) immediately on resignation creation
            $resignationHrManagerIds = array_map('intval', explode(',', env('app.resignationHrManagerIds')));
            $hrManagerId = $resignationHrManagerIds[3];
            // if ($hrManagerId > 0 && $hrManagerId !== (int) $data['employee_id']) {
            $ResignationHodResponseModel->insert([
                'resignation_id' => $resignation_id,
                'employee_id'    => $hrManagerId,
                'role'           => 'hr',
                'response'       => 'pending',
            ]);
            //}

            $msg = 'Resignation recorded successfully. Reporting manager will be notified.';

            return redirect()->to(base_url('resignation'))
                ->with('success', $msg);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('errors', $ResignationModel->errors());
        }
    }


    /**
     * Show edit resignation form
     */
    public function edit($id)
    {
        $current_user = $this->session->get('current_user');
        if ($current_user['employee_id'] != 52) {
            return redirect()->to(base_url('resignation'))->with('error', 'You are not authorized to edit resignations.');
        }

        $ResignationModel = new ResignationModel();
        $EmployeeModel = new EmployeeModel();

        $resignation = $ResignationModel->find($id);

        if (!$resignation) {
            return redirect()->to(base_url('resignation'))
                ->with('error', 'Resignation not found');
        }

        $employee = $EmployeeModel->select('employees.*, companies.company_name, departments.department_name')
            ->join('companies', 'companies.id = employees.company_id', 'left')
            ->join('departments', 'departments.id = employees.department_id', 'left')
            ->find($resignation['employee_id']);

        $data = [
            'page_title' => 'Edit Resignation',
            'current_controller' => $this->request->getUri()->getSegment(1),
            'resignation' => $resignation,
            'employee' => $employee,
        ];

        return view('Resignation/Edit', $data);
    }

    /**
     * Update resignation
     */
    public function update($id)
    {
        $current_user = $this->session->get('current_user');
        if ($current_user['employee_id'] != 52) {
            return redirect()->to(base_url('resignation'))->with('error', 'You are not authorized to edit resignations.');
        }

        $ResignationModel = new ResignationModel();
        $ResignationRevisionModel = new ResignationRevisionModel();

        // Get current resignation data before update
        $old_resignation = $ResignationModel->find($id);

        $data = [
            'employee_id' => $this->request->getPost('employee_id'),
            'resignation_date' => $this->request->getPost('resignation_date'),
            'resignation_reason' => $this->request->getPost('resignation_reason'),
            'buyout_days' => $this->request->getPost('buyout_days') ?? 0,
            'last_working_date' => $this->request->getPost('last_working_date') ?: null,
        ];

        if ($ResignationModel->update($id, $data)) {
            // Get updated resignation
            $updated_resignation = $ResignationModel->find($id);

            // Track only changed fields
            $changes = [];
            $changesList = [];
            foreach ($data as $field => $newValue) {
                $oldValue = $old_resignation[$field] ?? null;
                if ($oldValue != $newValue) {
                    $changes[$field] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                    $changesList[] = ucfirst(str_replace('_', ' ', $field));
                }
            }

            // Save revision with only changed data
            if (!empty($changes)) {
                $actionNote = 'Updated: ' . implode(', ', $changesList);
                $ResignationRevisionModel->saveRevision(
                    $id,
                    $changes, // Save only changes, not entire data
                    'updated',
                    $current_user['employee_id'],
                    $actionNote
                );
            }

            return redirect()->to(base_url('resignation'))
                ->with('success', 'Resignation updated successfully');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('errors', $ResignationModel->errors());
        }
    }

    /**
     * Mark resignation as withdrawn
     */
    public function withdrawn($id)
    {
        $ResignationModel = new ResignationModel();
        $EmployeeModel = new EmployeeModel();
        $ResignationRevisionModel = new ResignationRevisionModel();
        $current_user = $this->session->get('current_user');

        $resignation = $ResignationModel->find($id);
        if (!$resignation) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Resignation not found']);
        }

        $db = db_connect();
        $db->transStart();

        $ResignationModel->update($id, ['status' => 'withdrawn']);
        $EmployeeModel->update($resignation['employee_id'], ['date_of_leaving' => null]);

        $updated_resignation = $ResignationModel->find($id);
        $ResignationRevisionModel->saveRevision(
            $id,
            $updated_resignation,
            'withdrawn',
            $current_user['employee_id'],
            'Resignation withdrawn by HR'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to withdraw resignation']);
        }

        return $this->response->setJSON(['success' => 'Resignation withdrawn and date_of_leaving cleared']);
    }



    public function delete($id)
    {
        $current_user = $this->session->get('current_user');
        if ($current_user['employee_id'] != 52) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'You are not authorized to delete resignations.'
            ]);
        }

        $ResignationModel = new ResignationModel();
        $resignation = $ResignationModel->find($id);

        if (!$resignation) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Resignation record not found.'
            ]);
        }
        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $ResignationHodResponseModel->where('resignation_id', $id)->delete();
        if ($ResignationModel->delete($id)) {
            return $this->response->setJSON([
                'response_type' => 'success',
                'response_description' => 'Resignation deleted successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Failed to delete resignation. Please try again.'
            ]);
        }
    }


    /**
     * Change resignation status (AJAX)
     * Handles all status changes: active, withdrawn, completed, abscond, left
     */
    public function changeStatus($id)
    {
        $ResignationModel = new ResignationModel();
        $EmployeeModel = new EmployeeModel();
        $ResignationRevisionModel = new ResignationRevisionModel();
        $current_user = $this->session->get('current_user');

        $resignation = $ResignationModel->find($id);
        if (!$resignation) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Resignation not found']);
        }

        $newStatus = $this->request->getPost('status');
        $validStatuses = ['active', 'withdrawn', 'completed', 'abscond', 'left', 'retained', 'retention_failed'];

        if (!in_array($newStatus, $validStatuses)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid status']);
        }

        $oldStatus = $resignation['status'];
        if ($oldStatus === $newStatus) {
            return $this->response->setJSON(['success' => 'Status unchanged']);
        }

        $employee = $EmployeeModel->find($resignation['employee_id']);
        if (!$employee) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Employee not found']);
        }

        $remarks = trim($this->request->getPost('remarks') ?? '');

        $db = db_connect();
        $db->transStart();

        // Handle employee's date_of_leaving based on status
        $statusLabels = [
            'active' => 'Active',
            'withdrawn' => 'Withdrawn',
            'completed' => 'Completed',
            'abscond' => 'Abscond',
            'left' => 'Left',
            'retained' => 'Retention Successful',
            'retention_failed' => 'Retention Failed',
        ];

        $actionNote = "Status changed from {$statusLabels[$oldStatus]} to {$statusLabels[$newStatus]}";
        if ($remarks !== '') {
            $actionNote .= ". Remarks: {$remarks}";
        }

        if (in_array($newStatus, ['completed', 'abscond', 'left'])) {
            // Use provided last working date or calculate it
            $last_working_day = $this->request->getPost('last_working_date');

            if (empty($last_working_day)) {
                // Calculate last working day if not provided
                $notice_period = $employee['notice_period'] ?? 30;
                $buyout_days = $resignation['buyout_days'] ?? 0;
                $effective_notice_period = $notice_period - $buyout_days;
                $last_working_day = date('Y-m-d', strtotime($resignation['resignation_date'] . " +{$effective_notice_period} days"));
            }

            // Update resignation status and last_working_date
            $ResignationModel->update($id, [
                'status' => $newStatus,
                'last_working_date' => $last_working_day,
                'remarks' => $remarks ?: null,
            ]);

            // Update employee's date_of_leaving
            $EmployeeModel->update($resignation['employee_id'], ['date_of_leaving' => $last_working_day]);
            $actionNote .= ". Last working day set to: {$last_working_day}";
        } elseif (in_array($newStatus, ['active', 'withdrawn'])) {
            // Update resignation status and clear last_working_date
            $ResignationModel->update($id, [
                'status' => $newStatus,
                'last_working_date' => null,
                'remarks' => $remarks ?: null,
            ]);

            // Clear employee's date_of_leaving
            $EmployeeModel->update($resignation['employee_id'], ['date_of_leaving' => null]);
            $actionNote .= ". Date of leaving cleared";
        } elseif ($newStatus === 'retained') {
            // Employee stays — clear date_of_leaving
            $ResignationModel->update($id, [
                'status' => 'retained',
                'remarks' => $remarks ?: null,
            ]);
            $EmployeeModel->update($resignation['employee_id'], ['date_of_leaving' => null]);
        } elseif ($newStatus === 'retention_failed') {
            // Stays in active list, just record the outcome
            $ResignationModel->update($id, [
                'status' => 'retention_failed',
                'remarks' => $remarks ?: null,
            ]);
        } else {
            // Just update status
            $ResignationModel->update($id, [
                'status' => $newStatus,
                'remarks' => $remarks ?: null,
            ]);
        }

        // Notify reporting manager of HR retention decision
        if (in_array($newStatus, ['retained', 'retention_failed']) && !empty($employee['reporting_manager_id'])) {
            $ResignationHodResponseModel = new ResignationHodResponseModel();
            $ResignationHodResponseModel->insert([
                'resignation_id' => $id,
                'employee_id'    => $employee['reporting_manager_id'],
                'role'           => 'manager',
                'response'       => 'pending',
            ]);
        }

        // Save revision
        $updated_resignation = $ResignationModel->find($id);
        $ResignationRevisionModel->saveRevision(
            $id,
            $updated_resignation,
            $newStatus,
            $current_user['employee_id'],
            $actionNote
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to change status']);
        }

        return $this->response->setJSON(['success' => "Status changed to {$statusLabels[$newStatus]}"]);
    }

    /**
     * Mark resignation as completed
     */
    public function complete($id)
    {
        $ResignationModel = new ResignationModel();
        $EmployeeModel = new EmployeeModel();
        $ResignationRevisionModel = new ResignationRevisionModel();
        $current_user = $this->session->get('current_user');

        $resignation = $ResignationModel->find($id);
        if (!$resignation) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Resignation not found']);
        }

        $employee = $EmployeeModel->find($resignation['employee_id']);
        if (!$employee) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Employee not found']);
        }

        $notice_period = $employee['notice_period'] ?? 30;
        $buyout_days = $resignation['buyout_days'] ?? 0;
        $effective_notice_period = $notice_period - $buyout_days;
        $last_working_day = date('Y-m-d', strtotime($resignation['resignation_date'] . " +{$effective_notice_period} days"));

        $db = db_connect();
        $db->transStart();

        $ResignationModel->update($id, ['status' => 'completed']);
        $EmployeeModel->update($resignation['employee_id'], ['date_of_leaving' => $last_working_day]);

        $updated_resignation = $ResignationModel->find($id);
        $ResignationRevisionModel->saveRevision(
            $id,
            $updated_resignation,
            'completed',
            $current_user['employee_id'],
            "Resignation marked as completed. Last working day: {$last_working_day}"
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to complete resignation']);
        }

        return $this->response->setJSON(['success' => 'Resignation completed and date_of_leaving updated']);
    }

    /**
     * Calculate last working day (AJAX helper)
     */
    public function calculateLastWorkingDay()
    {
        $resignation_date = $this->request->getPost('resignation_date');
        $employee_id = $this->request->getPost('employee_id');
        $buyout_days = (int)($this->request->getPost('buyout_days') ?? 0);

        $EmployeeModel = new EmployeeModel();
        $employee = $EmployeeModel->find($employee_id);

        if (!$employee) {
            return $this->response->setJSON(['error' => 'Employee not found']);
        }

        $notice_period = $employee['notice_period'] ?? 30;
        $effective_notice_period = $notice_period - $buyout_days;
        $last_working_day = date('Y-m-d', strtotime($resignation_date . " +{$effective_notice_period} days"));

        return $this->response->setJSON([
            'last_working_day' => date('d M Y', strtotime($last_working_day)),
            'notice_period' => $notice_period,
        ]);
    }


    public function getEmployeesByCompany($company_id)
    {
        $EmployeeModel = new EmployeeModel();
        $employees = $EmployeeModel->where('company_id', $company_id)
            ->where('status', 'active')
            ->orderBy('first_name', 'ASC')
            ->findAll();

        $data = [];
        foreach ($employees as $employee) {
            $data[] = [
                'id' => $employee['id'],
                'name' => trim($employee['first_name'] . ' ' . $employee['last_name']),
                'employee_id' => $employee['internal_employee_id'],
            ];
        }

        return $this->response->setJSON($data);
    }


    public function exportResignations()
    {
        $current_user = $this->session->get('current_user');
        $company_id = $this->request->getGet('company_id');

        $ResignationModel = new ResignationModel();
        $resignations = $ResignationModel->getActiveResignations(
            $company_id,
            $current_user['employee_id'],
            $current_user['role']
        );


        return $this->response->setJSON($resignations);
    }

    /**
     * Get revision history for a resignation (AJAX)
     */
    public function getRevisionHistory($resignation_id)
    {
        $ResignationRevisionModel = new ResignationRevisionModel();
        $revisions = $ResignationRevisionModel->getRevisionHistory($resignation_id);

        return $this->response->setJSON($revisions);
    }

    /**
     * View revision history page
     */
    // public function viewRevisionHistory($resignation_id)
    // {
    //     $ResignationModel = new ResignationModel();
    //     $ResignationRevisionModel = new ResignationRevisionModel();
    //     $EmployeeModel = new EmployeeModel();

    //     $resignation = $ResignationModel->find($resignation_id);
    //     if (!$resignation) {
    //         return redirect()->to(base_url('resignation'))
    //             ->with('error', 'Resignation not found');
    //     }

    //     $employee = $EmployeeModel->select('employees.*, companies.company_name, departments.department_name')
    //         ->join('companies', 'companies.id = employees.company_id', 'left')
    //         ->join('departments', 'departments.id = employees.department_id', 'left')
    //         ->find($resignation['employee_id']);

    //     $revisions = $ResignationRevisionModel->getRevisionHistory($resignation_id);

    //     $data = [
    //         'page_title' => 'Resignation Revision History',
    //         'current_controller' => $this->request->getUri()->getSegment(1),
    //         'resignation' => $resignation,
    //         'employee' => $employee,
    //         'revisions' => $revisions,
    //     ];

    //     return view('Resignation/RevisionHistory', $data);
    // }



        // ==================== RESIGNATION HOD ACKNOWLEDGMENT METHODS ====================

    /**
     * Get resignations requiring HOD acknowledgment for popup
     * Returns array of resignations pending HOD response
     */
    public function getDataForResignationHodPopUp()
    {
        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $currentUserId = $this->session->get('current_user')['employee_id'];
        $resignations = $ResignationHodResponseModel->getPendingHodNotifications($currentUserId);

        if (!empty($resignations)) {
            foreach ($resignations as &$resignation) {
                if (empty($resignation['last_working_date']) && !empty($resignation['resignation_date'])) {
                    $noticePeriod = 90;
                    $resignation['last_working_date'] = date('Y-m-d', strtotime($resignation['resignation_date'] . ' +' . $noticePeriod . ' days'));
                }

                // Calculate remaining days
                $today = new \DateTime();
                $lastWorkingDate = new \DateTime($resignation['last_working_date']);
                $interval = $today->diff($lastWorkingDate);
                $resignation['remaining_days'] = $interval->days;
                $resignation['is_urgent'] = ($resignation['remaining_days'] <= 7);

                // Format dates for display
                $resignation['resignation_date_formatted'] = date('d/m/Y', strtotime($resignation['resignation_date']));
                $resignation['last_working_date_formatted'] = date('d/m/Y', strtotime($resignation['last_working_date']));
                $resignation['employee_name'] = trim($resignation['first_name'] . ' ' . $resignation['last_name']);
                $resignation['manager_name'] = trim(($resignation['manager_first_name'] ?? '') . ' ' . ($resignation['manager_last_name'] ?? ''));

                $resignation['employee_image'] = null;
                if (!empty($resignation['attachment'])) {
                    $att = json_decode($resignation['attachment'], true);
                    if (!empty($att['avatar']['file'])) {
                        $resignation['employee_image'] = base_url($att['avatar']['file']);
                    }
                }
                unset($resignation['attachment']);
            }
        }

        return $resignations;
    }

    /**
     * Save HOD response for resignations
     * Handles: too_early (remind me), accept, reject
     */
    public function saveResignationResponseOfHod()
    {
        $responses = $this->request->getPost('responses');

        if (empty($responses)) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'No responses provided'
            ]);
        }

        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $currentUserId = $this->session->get('current_user')['employee_id'];

        foreach ($responses as $recordId => $responseData) {
            $action = $responseData['action'];
            $rejectionReason = $responseData['rejection_reason'] ?? null;

            // Get the record and verify the caller is the HOD for this row
            $record = $ResignationHodResponseModel->find($recordId);
            if (!$record || $record['employee_id'] != $currentUserId || $record['role'] !== 'hod') {
                continue; // Skip unauthorized records
            }

            if ($action === 'too_early') {
                $ResignationHodResponseModel->markResponse($recordId, 'too_early');
            } elseif (in_array($action, ['accept', 'reject', 'try_to_retain', 'acknowledge'])) {
                $response = ($action === 'reject') ? 'rejected' : $action;
                $remarks  = $rejectionReason ?: null;
                $ResignationHodResponseModel->markResponse($recordId, $response, $remarks);

                // Notify HR (first HR manager from config)
                $resignationHrManagerIds = array_map('intval', explode(',', env('app.resignationHrManagerIds')));
                $hrId = $resignationHrManagerIds[0];
                $ResignationHodResponseModel->setHrPending($record['resignation_id'], $hrId);

                // When HOD wants to retain — re-notify the reporting manager via a new pending row
                if ($action === 'try_to_retain') {
                    $resignation = (new ResignationModel())->find($record['resignation_id']);
                    if ($resignation) {
                        $employee = (new EmployeeModel())->find($resignation['employee_id']);
                        if ($employee && !empty($employee['reporting_manager_id'])) {
                            $ResignationHodResponseModel->insert([
                                'resignation_id' => $record['resignation_id'],
                                'employee_id'    => (int) $employee['reporting_manager_id'],
                                'role'           => 'manager',
                                'response'       => 'pending',
                            ]);
                        }
                    }
                }

                // Send email notification
                $this->sendResignationResponseEmail($record, $action, $remarks, 'HOD');
            }
        }

        return $this->response->setJSON([
            'response_type' => 'success',
            'response_description' => 'HOD Response saved successfully'
        ]);
    }

    /**
     * Get manager notifications for HOD responses
     * Returns resignations where HOD has responded and manager needs to review
     */
    public function getManagerResignationNotifications()
    {
        $currentUserId = $this->session->get('current_user')['employee_id'];

        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $notifications = $ResignationHodResponseModel->getPendingHrNotifications($currentUserId);

        // Format data for frontend
        foreach ($notifications as &$notification) {
            $notification['employee_name'] = trim($notification['first_name'] . ' ' . $notification['last_name']);
            $notification['hod_name']      = trim($notification['hod_first_name'] . ' ' . $notification['hod_last_name']);
            $notification['manager_name']  = trim(($notification['manager_first_name'] ?? '') . ' ' . ($notification['manager_last_name'] ?? ''));
            $notification['resignation_date_formatted']  = date('d/m/Y', strtotime($notification['resignation_date']));
            $notification['last_working_date_formatted'] = !empty($notification['last_working_date']) ? date('d/m/Y', strtotime($notification['last_working_date'])) : 'N/A';

            $notification['employee_image'] = null;
            if (!empty($notification['attachment'])) {
                $att = json_decode($notification['attachment'], true);
                if (!empty($att['avatar']['file'])) {
                    $notification['employee_image'] = base_url($att['avatar']['file']);
                }
            }
            unset($notification['attachment']);
        }

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Handle manager notification action (mark as viewed)
     */
    public function handleManagerResignationNotificationAction()
    {
        $recordId = $this->request->getPost('record_id');
        $action = $this->request->getPost('action');

        $currentUserId = $this->session->get('current_user')['employee_id'];
        $resignationHrManagerIds = array_map('intval', explode(',', env('app.resignationHrManagerIds')));

        if (!in_array($currentUserId, $resignationHrManagerIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $record = $ResignationHodResponseModel->find($recordId);

        if (!$record) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Record not found'
            ]);
        }

        if ($record['employee_id'] != $currentUserId || $record['role'] !== 'hr') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authorized for this record'
            ]);
        }

        if ($action === 'acknowledge') {
            $success = $ResignationHodResponseModel->markResponse($recordId, 'acknowledge');

            return $this->response->setJSON([
                'success' => $success,
                'message' => $success ? 'Resignation acknowledged' : 'Failed to update'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid action'
        ]);
    }

    /**
     * Send email notification when HOD or Manager responds to a resignation
     */
    private function sendResignationResponseEmail($record, $action, $remarks = null, string $responderRole = 'HOD')
    {
        $EmployeeModel = new EmployeeModel();
        $ResignationModel = new ResignationModel();
        $currentUserId = $this->session->get('current_user')['employee_id'];

        $responderData = $EmployeeModel->find($currentUserId);

        // employee_id on the response row is the responder; get the resigning employee via the resignation record.
        $resignation  = $ResignationModel->find($record['resignation_id']);
        $employeeData = $resignation ? $EmployeeModel->find($resignation['employee_id']) : null;

        if (!$responderData || !$employeeData) {
            return false;
        }

        $responderName = trim($responderData['first_name'] . ' ' . $responderData['last_name']);
        $employeeName  = trim($employeeData['first_name'] . ' ' . $employeeData['last_name']);

        $email = \Config\Services::email();
        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
        $toEmails = ['developer3@healthgenie.in', 'hrd@gstc.com', 'careers@gstc.com', 'developer2@healthgenie.in'];
        //$toEmails = ['developer2@healthgenie.in'];
        $email->setTo($toEmails);

        $responseText = match ($action) {
            'accept'                => 'Accepted',
            'reject', 'rejected'   => 'Rejected',
            'try_to_retain'        => 'Try to Retain',
            'acknowledge'          => 'Acknowledged',
            default                => ucfirst($action),
        };
        $email->setSubject('Resignation Response - ' . $employeeName . ' (' . $responseText . ')');

        $remarksSection = '';
        if ($remarks) {
            $remarksSection = '<div style="padding-bottom: 10px; color: #555;"><strong>Remarks:</strong> ' . htmlspecialchars($remarks) . '</div>';
        }

        $emailMessage = '
            <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                    <tbody>
                        <tr>
                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                                    <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="center">
                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                    <div style="padding-bottom: 30px; font-size: 17px;">
                                        <strong>' . $responderName . ' (' . $responderRole . ') has responded to ' . $employeeName . '\'s resignation</strong>
                                    </div>
                                    <div style="padding-bottom: 10px"><strong>Response:</strong> ' . $responseText . '</div>
                                    ' . $remarksSection . '
                                    <div style="padding-bottom: 10px">Kind regards,
                                    <br>HRM Team.
                                    <tr>
                                        <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                            <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                                            <p>Copyright ©
                                            <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                                        </td>
                                    </tr></br></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>';

        $email->setMessage($emailMessage);
        return $email->send();
    }

    // ==================== END RESIGNATION HOD ACKNOWLEDGMENT METHODS ====================

    // ==================== REPORTING MANAGER NOTIFICATION METHODS ====================

    /**
     * Get pending resignation notifications for the reporting manager
     * Triggered immediately when HR creates a resignation
     */
    public function getReportingManagerResignationNotifications()
    {
        $currentUserId = $this->session->get('current_user')['employee_id'];

        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $notifications = $ResignationHodResponseModel->getPendingManagerNotifications($currentUserId);

        foreach ($notifications as &$notification) {
            $notification['employee_name']       = trim($notification['first_name'] . ' ' . $notification['last_name']);
            $notification['employee_first_name'] = $notification['first_name'] ?? '';
            $notification['hod_name']            = trim(($notification['hod_first_name'] ?? '') . ' ' . ($notification['hod_last_name'] ?? ''));
            $notification['resignation_date_formatted']  = date('d/m/Y', strtotime($notification['resignation_date']));
            $notification['last_working_date_formatted'] = !empty($notification['last_working_date']) ? date('d/m/Y', strtotime($notification['last_working_date'])) : 'N/A';

            $notification['employee_image'] = null;
            if (!empty($notification['attachment'])) {
                $att = json_decode($notification['attachment'], true);
                if (!empty($att['avatar']['file'])) {
                    $notification['employee_image'] = base_url($att['avatar']['file']);
                }
            }
            unset($notification['attachment']);
        }

        // Append HR-decision rows (retained / retention_failed) for this manager
        $hrDecisionRows = array(); //$ResignationHodResponseModel->getPendingHrDecisionNotifications($currentUserId);
        foreach ($hrDecisionRows as $row) {
            $att = json_decode($row['attachment'] ?? '{}', true);
            $notifications[] = [
                'id'                          => $row['id'],
                'employee_name'               => trim($row['first_name'] . ' ' . $row['last_name']),
                'employee_first_name'         => $row['first_name'] ?? '',
                'internal_employee_id'        => $row['internal_employee_id'] ?? '',
                'designation_name'            => $row['designation_name'] ?? '',
                'department_name'             => $row['department_name'] ?? '',
                'company_name'                => $row['company_name'] ?? '',
                'resignation_date_formatted'  => date('d M Y', strtotime($row['resignation_date'])),
                'last_working_date_formatted' => !empty($row['last_working_date']) ? date('d M Y', strtotime($row['last_working_date'])) : 'N/A',
                'resignation_reason'          => $row['resignation_reason'] ?? '',
                'employee_image'              => !empty($att['avatar']['file']) ? base_url($att['avatar']['file']) : null,
                'notification_type'           => 'hr_decision',
                'hr_decision'                 => $row['hr_decision'],
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Handle reporting manager notification action
     * Supports: too_early (remind tomorrow), accept, rejected, try_to_retain
     */
    public function handleReportingManagerNotificationAction()
    {
        $recordId        = $this->request->getPost('record_id');
        $action          = $this->request->getPost('action');
        $rejectionReason = $this->request->getPost('rejection_reason');
        $currentUserId   = $this->session->get('current_user')['employee_id'];

        $ResignationHodResponseModel = new ResignationHodResponseModel();

        // HR decision acknowledgment — just stamp response_date, no cascade
        if ($this->request->getPost('notification_type') === 'hr_decision') {
            $record = $ResignationHodResponseModel->find($recordId);
            if (!$record || $record['employee_id'] != $currentUserId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Not authorized']);
            }
            $ResignationHodResponseModel->update($recordId, ['response_date' => date('Y-m-d H:i:s')]);
            return $this->response->setJSON(['success' => true]);
        }

        $record = $ResignationHodResponseModel->find($recordId);

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Record not found']);
        }

        if ($record['employee_id'] != $currentUserId || $record['role'] !== 'manager') {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authorized for this record']);
        }

        $validActions = ['too_early', 'accept', 'rejected', 'try_to_retain', 'acknowledge'];
        if (!in_array($action, $validActions)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid action']);
        }

        $remarks = ($action !== 'too_early' && $action !== 'acknowledge') ? ($rejectionReason ?: null) : null;
        $success = $ResignationHodResponseModel->markResponse($recordId, $action, $remarks);

        // After a terminal response, notify the HOD
        if ($success && !in_array($action, ['too_early', 'acknowledge'])) {
            $resignation = (new ResignationModel())->find($record['resignation_id']);
            if ($resignation) {
                $employee = (new EmployeeModel())
                    ->select('employees.*, departments.hod_employee_id')
                    ->join('departments', 'departments.id = employees.department_id', 'left')
                    ->find($resignation['employee_id']);

                if ($employee && !empty($employee['hod_employee_id'])) {
                    $ResignationHodResponseModel->setHodPending(
                        (int) $record['resignation_id'],
                        (int) $employee['hod_employee_id']
                    );
                }
            }

            // Send email notification for manager response
            $this->sendResignationResponseEmail($record, $action, $rejectionReason, 'Manager');
        }

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Response saved successfully' : 'Failed to update'
        ]);
    }

    // ==================== END REPORTING MANAGER NOTIFICATION METHODS ====================

    // ==================== HR MANAGER NOTIFICATION METHODS ====================

    public function getHrManagerResignationNotifications()
    {
        $currentUserId = $this->session->get('current_user')['employee_id'];

        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $notifications = $ResignationHodResponseModel->getPendingHrManagerNotifications($currentUserId);


        foreach ($notifications as &$notification) {
            $notification['employee_name'] = trim($notification['first_name'] . ' ' . $notification['last_name']);
            $notification['hod_name'] = trim(($notification['hod_first_name'] ?? '') . ' ' . ($notification['hod_last_name'] ?? ''));
            $notification['resignation_date_formatted'] = date('d/m/Y', strtotime($notification['resignation_date']));
            $notification['last_working_date_formatted'] = !empty($notification['last_working_date']) ? date('d/m/Y', strtotime($notification['last_working_date'])) : 'N/A';
            $notification['hod_response_date'] = !empty($notification['hod_response_date']) ? date('d/m/Y H:i', strtotime($notification['hod_response_date'])) : 'N/A';
        }

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    public function handleHrManagerResignationNotificationAction()
    {
        $recordId = $this->request->getPost('record_id');
        $action = $this->request->getPost('action');
        $currentUserId = $this->session->get('current_user')['employee_id'];

        $ResignationHodResponseModel = new ResignationHodResponseModel();
        $record = $ResignationHodResponseModel->find($recordId);

        if (!$record) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Record not found'
            ]);
        }

        if ($record['employee_id'] != $currentUserId || $record['role'] !== 'hr_manager') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authorized for this record'
            ]);
        }

        if ($action === 'viewed') {
            $success = $ResignationHodResponseModel->markResponse($recordId, 'accept');

            return $this->response->setJSON([
                'success' => $success,
                'message' => $success ? 'Notification acknowledged' : 'Failed to update'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid action'
        ]);
    }

    // ==================== END HR MANAGER NOTIFICATION METHODS ====================

}
