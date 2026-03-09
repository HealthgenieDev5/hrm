<?php

namespace App\Models;

use CodeIgniter\Model;

class ResignationModel extends Model
{
    protected $table = 'resignations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'employee_id',
        'resignation_date',
        'resignation_reason',
        'submitted_by_hr',
        'buyout_days',
        'last_working_date',
        'status',
        'remarks',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'employee_id' => 'required|integer',
        'resignation_date' => 'required|valid_date',
        'submitted_by_hr' => 'required|integer',
        'status' => 'in_list[active,withdrawn,completed,abscond,left,retained,retention_failed]'
    ];

    protected $validationMessages = [
        'employee_id' => [
            'required' => 'Employee is required',
            'integer' => 'Employee ID must be a valid number'
        ],
        'resignation_date' => [
            'required' => 'Resignation date is required',
            'valid_date' => 'Please provide a valid date'
        ],
        'submitted_by_hr' => [
            'required' => 'HR employee is required',
            'integer' => 'HR employee ID must be a valid number'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get active resignations with employee details
     */
    public function getActiveResignations($company_id = null, $current_employee_id = null, $user_role = null)
    {
        $builder = $this->db->table('resignations r');
        $builder->select("
            r.id as resignation_id,
            r.employee_id,
            r.resignation_date,
            r.resignation_reason,
            r.buyout_days,
            r.status as resignation_status,
            r.remarks,
            e.internal_employee_id,
            TRIM(CONCAT(e.first_name, ' ', e.last_name)) as employee_name,
            e.notice_period,
            d.department_name,
            c.company_short_name,
            c.id as company_id,
            TRIM(CONCAT(hr.first_name, ' ', hr.last_name)) as recorded_by_hr,
            COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)) as calculated_last_working_day,
            DATEDIFF(COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)), CURDATE()) as remaining_days,
            DATEDIFF(CURDATE(), r.resignation_date) as days_since_resignation,
            hod_row.response AS hod_response,
            hod_row.response_date AS hod_response_date,
            hod_row.remarks AS hod_rejection_reason,
            TRIM(CONCAT(COALESCE(hod_emp.first_name, ''), ' ', COALESCE(hod_emp.last_name, ''))) as hod_name,
            manager_row.response AS manager_response,
            manager_row.response_date AS manager_response_date,
            TRIM(CONCAT(COALESCE(mgr_emp.first_name, ''), ' ', COALESCE(mgr_emp.last_name, ''))) as manager_name
        ");
        $builder->join('employees e', 'e.id = r.employee_id', 'left');
        $builder->join('employees hr', 'hr.id = r.submitted_by_hr', 'left');
        $builder->join('departments d', 'd.id = e.department_id', 'left');
        $builder->join('companies c', 'c.id = e.company_id', 'left');
        $builder->join('resignation_response hod_row', "hod_row.resignation_id = r.id AND hod_row.role = 'hod'", 'left');
        $builder->join('resignation_response manager_row', "manager_row.id = (SELECT id FROM resignation_response WHERE resignation_id = r.id AND role = 'manager' ORDER BY id DESC LIMIT 1)", 'left');
        $builder->join('employees hod_emp', 'hod_emp.id = hod_row.employee_id', 'left');
        $builder->join('employees mgr_emp', 'mgr_emp.id = manager_row.employee_id', 'left');
        $builder->whereIn('r.status', ['active', 'retention_failed']);
        // $builder->where('DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY) >=', date('Y-m-d')); // Commented to include overdue resignations

        // Apply role-based access control
        if ($user_role && !in_array($user_role, ['admin', 'superuser', 'hr'])) {
            $builder->groupStart();
            $builder->where('e.id', $current_employee_id);
            $builder->orWhere('e.reporting_manager_id', $current_employee_id);
            $builder->orWhere('d.hod_employee_id', $current_employee_id);
            $builder->groupEnd();
        }

        // Apply company filter
        if ($company_id && $company_id !== 'all_companies' && $company_id !== '') {
            $builder->where('e.company_id', $company_id);
        }

        $builder->orderBy('remaining_days', 'ASC');
        $builder->orderBy('c.company_short_name', 'ASC');
        $builder->orderBy('e.first_name', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get urgent resignation alerts (<=7 days remaining)
     */
    public function getUrgentAlerts($company_id = null, $current_employee_id = null, $user_role = null)
    {
        $builder = $this->db->table('resignations r');
        $builder->select("
            r.id as resignation_id,
            r.employee_id,
            r.resignation_date,
            r.resignation_reason,
            r.buyout_days,
            r.status as resignation_status,
            e.internal_employee_id,
            TRIM(CONCAT(e.first_name, ' ', e.last_name)) as employee_name,
            e.notice_period,
            d.department_name,
            c.company_short_name,
            COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)) as calculated_last_working_day,
            DATEDIFF(COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)), CURDATE()) as remaining_days,
            hod_row.response AS hod_response,
            hod_row.response_date AS hod_response_date,
            hod_row.remarks AS hod_rejection_reason,
            TRIM(CONCAT(COALESCE(hod_emp.first_name, ''), ' ', COALESCE(hod_emp.last_name, ''))) as hod_name,
            manager_row.response AS manager_response,
            manager_row.response_date AS manager_response_date,
            TRIM(CONCAT(COALESCE(mgr_emp.first_name, ''), ' ', COALESCE(mgr_emp.last_name, ''))) as manager_name
        ");
        $builder->join('employees e', 'e.id = r.employee_id', 'left');
        $builder->join('departments d', 'd.id = e.department_id', 'left');
        $builder->join('companies c', 'c.id = e.company_id', 'left');
        $builder->join('resignation_response hod_row', "hod_row.resignation_id = r.id AND hod_row.role = 'hod'", 'left');
        $builder->join('resignation_response manager_row', "manager_row.id = (SELECT id FROM resignation_response WHERE resignation_id = r.id AND role = 'manager' ORDER BY id DESC LIMIT 1)", 'left');
        $builder->join('employees hod_emp', 'hod_emp.id = hod_row.employee_id', 'left');
        $builder->join('employees mgr_emp', 'mgr_emp.id = manager_row.employee_id', 'left');
        $builder->where('r.status', 'active');
        $builder->where('DATEDIFF(COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)), CURDATE()) <=', 7);
        $builder->where('COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)) >=', date('Y-m-d'));

        // Apply role-based access control
        if ($user_role && !in_array($user_role, ['admin', 'superuser', 'hr'])) {
            $builder->groupStart();
            $builder->where('e.id', $current_employee_id);
            $builder->orWhere('e.reporting_manager_id', $current_employee_id);
            $builder->orWhere('d.hod_employee_id', $current_employee_id);
            $builder->groupEnd();
        }

        // Apply company filter
        if ($company_id && $company_id !== 'all_companies' && $company_id !== '') {
            $builder->where('e.company_id', $company_id);
        }

        $builder->orderBy('remaining_days', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get completed resignations with employee details
     */
    public function getCompletedResignations($company_id = null, $current_employee_id = null, $user_role = null)
    {
        $builder = $this->db->table('resignations r');
        $builder->select("
            r.id as resignation_id,
            r.employee_id,
            r.resignation_date,
            r.resignation_reason,
            r.buyout_days,
            r.status as resignation_status,
            r.updated_at,
            e.internal_employee_id,
            TRIM(CONCAT(e.first_name, ' ', e.last_name)) as employee_name,
            e.notice_period,
            d.department_name,
            c.company_short_name,
            COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)) as calculated_last_working_day,
            hod_row.response AS hod_response,
            hod_row.response_date AS hod_response_date,
            hod_row.remarks AS hod_rejection_reason,
            TRIM(CONCAT(COALESCE(hod_emp.first_name, ''), ' ', COALESCE(hod_emp.last_name, ''))) as hod_name,
            manager_row.response AS manager_response,
            manager_row.response_date AS manager_response_date,
            TRIM(CONCAT(COALESCE(mgr_emp.first_name, ''), ' ', COALESCE(mgr_emp.last_name, ''))) as manager_name
        ");
        $builder->join('employees e', 'e.id = r.employee_id', 'left');
        $builder->join('departments d', 'd.id = e.department_id', 'left');
        $builder->join('companies c', 'c.id = e.company_id', 'left');
        $builder->join('resignation_response hod_row', "hod_row.resignation_id = r.id AND hod_row.role = 'hod'", 'left');
        $builder->join('resignation_response manager_row', "manager_row.id = (SELECT id FROM resignation_response WHERE resignation_id = r.id AND role = 'manager' ORDER BY id DESC LIMIT 1)", 'left');
        $builder->join('employees hod_emp', 'hod_emp.id = hod_row.employee_id', 'left');
        $builder->join('employees mgr_emp', 'mgr_emp.id = manager_row.employee_id', 'left');
        $builder->where('r.status', 'completed');

        // Apply role-based access control
        if ($user_role && !in_array($user_role, ['admin', 'superuser', 'hr'])) {
            $builder->groupStart();
            $builder->where('e.id', $current_employee_id);
            $builder->orWhere('e.reporting_manager_id', $current_employee_id);
            $builder->orWhere('d.hod_employee_id', $current_employee_id);
            $builder->groupEnd();
        }

        // Apply company filter
        if ($company_id && $company_id !== 'all_companies' && $company_id !== '') {
            $builder->where('e.company_id', $company_id);
        }

        $builder->orderBy('r.updated_at', 'DESC');
        $builder->orderBy('c.company_short_name', 'ASC');
        $builder->orderBy('e.first_name', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get abscond resignations with employee details
     */
    public function getAbscondResignations($company_id = null, $current_employee_id = null, $user_role = null)
    {
        $builder = $this->db->table('resignations r');
        $builder->select("
            r.id as resignation_id,
            r.employee_id,
            r.resignation_date,
            r.resignation_reason,
            r.buyout_days,
            r.status as resignation_status,
            r.updated_at,
            e.internal_employee_id,
            TRIM(CONCAT(e.first_name, ' ', e.last_name)) as employee_name,
            e.notice_period,
            d.department_name,
            c.company_short_name,
            COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)) as calculated_last_working_day,
            hod_row.response AS hod_response,
            hod_row.response_date AS hod_response_date,
            hod_row.remarks AS hod_rejection_reason,
            TRIM(CONCAT(COALESCE(hod_emp.first_name, ''), ' ', COALESCE(hod_emp.last_name, ''))) as hod_name,
            manager_row.response AS manager_response,
            manager_row.response_date AS manager_response_date,
            TRIM(CONCAT(COALESCE(mgr_emp.first_name, ''), ' ', COALESCE(mgr_emp.last_name, ''))) as manager_name
        ");
        $builder->join('employees e', 'e.id = r.employee_id', 'left');
        $builder->join('departments d', 'd.id = e.department_id', 'left');
        $builder->join('companies c', 'c.id = e.company_id', 'left');
        $builder->join('resignation_response hod_row', "hod_row.resignation_id = r.id AND hod_row.role = 'hod'", 'left');
        $builder->join('resignation_response manager_row', "manager_row.id = (SELECT id FROM resignation_response WHERE resignation_id = r.id AND role = 'manager' ORDER BY id DESC LIMIT 1)", 'left');
        $builder->join('employees hod_emp', 'hod_emp.id = hod_row.employee_id', 'left');
        $builder->join('employees mgr_emp', 'mgr_emp.id = manager_row.employee_id', 'left');
        $builder->where('r.status', 'abscond');

        // Apply role-based access control
        if ($user_role && !in_array($user_role, ['admin', 'superuser', 'hr'])) {
            $builder->groupStart();
            $builder->where('e.id', $current_employee_id);
            $builder->orWhere('e.reporting_manager_id', $current_employee_id);
            $builder->orWhere('d.hod_employee_id', $current_employee_id);
            $builder->groupEnd();
        }

        // Apply company filter
        if ($company_id && $company_id !== 'all_companies' && $company_id !== '') {
            $builder->where('e.company_id', $company_id);
        }

        $builder->orderBy('r.updated_at', 'DESC');
        $builder->orderBy('c.company_short_name', 'ASC');
        $builder->orderBy('e.first_name', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get retained resignations with employee details
     */
    public function getRetainedResignations($company_id = null, $current_employee_id = null, $user_role = null)
    {
        $builder = $this->db->table('resignations r');
        $builder->select("
            r.id as resignation_id,
            r.employee_id,
            r.resignation_date,
            r.resignation_reason,
            r.buyout_days,
            r.status as resignation_status,
            r.remarks,
            r.updated_at,
            e.internal_employee_id,
            TRIM(CONCAT(e.first_name, ' ', e.last_name)) as employee_name,
            e.notice_period,
            d.department_name,
            c.company_short_name,
            COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)) as calculated_last_working_day,
            hod_row.response AS hod_response,
            hod_row.response_date AS hod_response_date,
            hod_row.remarks AS hod_rejection_reason,
            TRIM(CONCAT(COALESCE(hod_emp.first_name, ''), ' ', COALESCE(hod_emp.last_name, ''))) as hod_name,
            manager_row.response AS manager_response,
            manager_row.response_date AS manager_response_date,
            TRIM(CONCAT(COALESCE(mgr_emp.first_name, ''), ' ', COALESCE(mgr_emp.last_name, ''))) as manager_name
        ");
        $builder->join('employees e', 'e.id = r.employee_id', 'left');
        $builder->join('departments d', 'd.id = e.department_id', 'left');
        $builder->join('companies c', 'c.id = e.company_id', 'left');
        $builder->join('resignation_response hod_row', "hod_row.resignation_id = r.id AND hod_row.role = 'hod'", 'left');
        $builder->join('resignation_response manager_row', "manager_row.id = (SELECT id FROM resignation_response WHERE resignation_id = r.id AND role = 'manager' ORDER BY id DESC LIMIT 1)", 'left');
        $builder->join('employees hod_emp', 'hod_emp.id = hod_row.employee_id', 'left');
        $builder->join('employees mgr_emp', 'mgr_emp.id = manager_row.employee_id', 'left');
        $builder->where('r.status', 'retained');

        if ($user_role && !in_array($user_role, ['admin', 'superuser', 'hr'])) {
            $builder->groupStart();
            $builder->where('e.id', $current_employee_id);
            $builder->orWhere('e.reporting_manager_id', $current_employee_id);
            $builder->orWhere('d.hod_employee_id', $current_employee_id);
            $builder->groupEnd();
        }

        if ($company_id && $company_id !== 'all_companies' && $company_id !== '') {
            $builder->where('e.company_id', $company_id);
        }

        $builder->orderBy('r.updated_at', 'DESC');
        $builder->orderBy('c.company_short_name', 'ASC');
        $builder->orderBy('e.first_name', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get resignation statistics
     */
    public function getStatistics($company_id = null)
    {
        $stats = [];

        // Total active resignations
        $builder = $this->db->table('resignations r');
        $builder->join('employees e', 'e.id = r.employee_id');
        $builder->where('r.status', 'active');
        $builder->where('COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)) >=', date('Y-m-d'));
        if ($company_id && $company_id !== 'all_companies') {
            $builder->where('e.company_id', $company_id);
        }
        $stats['total_active'] = $builder->countAllResults();

        // Overdue resignations (past last working day but still active)
        $builder = $this->db->table('resignations r');
        $builder->join('employees e', 'e.id = r.employee_id');
        $builder->where('r.status', 'active');
        $builder->where('COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)) <', date('Y-m-d'));
        if ($company_id && $company_id !== 'all_companies') {
            $builder->where('e.company_id', $company_id);
        }
        $stats['overdue'] = $builder->countAllResults();

        // Urgent alerts (1-7 days remaining)
        $builder = $this->db->table('resignations r');
        $builder->join('employees e', 'e.id = r.employee_id');
        $builder->where('r.status', 'active');
        $builder->where('DATEDIFF(COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)), CURDATE()) <=', 7);
        $builder->where('DATEDIFF(COALESCE(r.last_working_date, DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY)), CURDATE()) >', 0);
        if ($company_id && $company_id !== 'all_companies') {
            $builder->where('e.company_id', $company_id);
        }
        $stats['urgent_alerts'] = $builder->countAllResults();

        // This month new resignations
        $builder = $this->db->table('resignations r');
        $builder->join('employees e', 'e.id = r.employee_id');
        $builder->where('MONTH(r.resignation_date)', date('m'));
        $builder->where('YEAR(r.resignation_date)', date('Y'));
        if ($company_id && $company_id !== 'all_companies') {
            $builder->where('e.company_id', $company_id);
        }
        $stats['month_new'] = $builder->countAllResults();

        // Completed this month
        $builder = $this->db->table('resignations r');
        $builder->join('employees e', 'e.id = r.employee_id');
        $builder->where('r.status', 'completed');
        $builder->where('MONTH(r.updated_at)', date('m'));
        $builder->where('YEAR(r.updated_at)', date('Y'));
        if ($company_id && $company_id !== 'all_companies') {
            $builder->where('e.company_id', $company_id);
        }
        $stats['month_completed'] = $builder->countAllResults();

        // Total abscond resignations
        $builder = $this->db->table('resignations r');
        $builder->join('employees e', 'e.id = r.employee_id');
        $builder->where('r.status', 'abscond');
        if ($company_id && $company_id !== 'all_companies') {
            $builder->where('e.company_id', $company_id);
        }
        $stats['total_abscond'] = $builder->countAllResults();

        // Total retained resignations
        $builder = $this->db->table('resignations r');
        $builder->join('employees e', 'e.id = r.employee_id');
        $builder->where('r.status', 'retained');
        if ($company_id && $company_id !== 'all_companies') {
            $builder->where('e.company_id', $company_id);
        }
        $stats['total_retained'] = $builder->countAllResults();

        return $stats;
    }
}
