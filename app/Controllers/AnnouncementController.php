<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;
use App\Models\AnnouncementAcknowledgmentModel;
use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
use App\Models\DesignationModel;

class AnnouncementController extends BaseController
{
    protected $announcementModel;
    protected $acknowledgmentModel;

    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
        $this->acknowledgmentModel = new AnnouncementAcknowledgmentModel();
    }

    /**
     * AJAX endpoint to get pending announcements for logged-in employee
     */
    public function getPendingAnnouncements()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $session = session();
        $employeeId =  session()->get('current_user')['employee_id'];

        if (!$employeeId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in'
            ]);
        }

        $announcements = $this->announcementModel->getPendingAnnouncementsForEmployee($employeeId);

        return $this->response->setJSON([
            'success' => true,
            'data' => $announcements,
            'count' => count($announcements)
        ]);
    }

    /**
     * AJAX endpoint to acknowledge an announcement
     */
    public function acknowledgeAnnouncement()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $session = session();
        $employeeId =  session()->get('current_user')['employee_id'];

        if (!$employeeId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in'
            ]);
        }

        $announcementId = $this->request->getPost('announcement_id');

        if (!$announcementId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Announcement ID is required'
            ]);
        }

        // Verify announcement exists
        $announcement = $this->announcementModel->find($announcementId);
        if (!$announcement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Announcement not found'
            ]);
        }

        // Record acknowledgment
        $result = $this->acknowledgmentModel->acknowledgeAnnouncement($announcementId, $employeeId);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Announcement acknowledged successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to acknowledge announcement'
            ]);
        }
    }

    /**
     * Admin: List all announcements
     */
    public function index()
    {
        $data = [
            'title' => 'Manage Announcements',
            'announcements' => $this->announcementModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('Announcements/Index', $data);
    }

    /**
     * Admin: Create announcement form
     */
    public function create()
    {
        $departmentModel = new DepartmentModel();
        $designationModel = new DesignationModel();

        $data = [
            'title' => 'Create Announcement',
            'departments' => $departmentModel->findAll(),
            'designations' => $designationModel->findAll()
        ];

        return view('Announcements/Create', $data);
    }

    /**
     * Admin: Store new announcement
     */
    public function store()
    {
        $session = session();

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'message' => 'required',
            'type' => 'required|in_list[info,warning,success,danger]',
            'priority' => 'required|in_list[low,medium,high,critical]',
            'target_type' => 'required|in_list[all,department,designation,specific]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $targetIds = null;
        $targetType = $this->request->getPost('target_type');

        if ($targetType === 'department') {
            $targetIds = implode(',', $this->request->getPost('target_departments') ?? []);
        } elseif ($targetType === 'designation') {
            $targetIds = implode(',', $this->request->getPost('target_designations') ?? []);
        } elseif ($targetType === 'specific') {
            $targetIds = $this->request->getPost('target_employees');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'message' => $this->request->getPost('message'),
            'type' => $this->request->getPost('type'),
            'priority' => $this->request->getPost('priority'),
            'target_type' => $targetType,
            'target_ids' => $targetIds,
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'requires_acknowledgment' => $this->request->getPost('requires_acknowledgment') ? 1 : 0,
            'show_once' => $this->request->getPost('show_once') ? 1 : 0,
            'created_by' => $session->get('id'),
        ];

        if ($this->announcementModel->insert($data)) {
            return redirect()->to('/announcements')->with('success', 'Announcement created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create announcement');
        }
    }

    /**
     * Admin: Edit announcement
     */
    public function edit($id)
    {
        $announcement = $this->announcementModel->find($id);

        if (!$announcement) {
            return redirect()->to('/announcements')->with('error', 'Announcement not found');
        }

        $departmentModel = new DepartmentModel();
        $designationModel = new DesignationModel();

        $data = [
            'title' => 'Edit Announcement',
            'announcement' => $announcement,
            'departments' => $departmentModel->findAll(),
            'designations' => $designationModel->findAll()
        ];

        return view('Announcements/Edit', $data);
    }

    /**
     * Admin: Update announcement
     */
    public function update($id)
    {
        $announcement = $this->announcementModel->find($id);

        if (!$announcement) {
            return redirect()->to('/announcements')->with('error', 'Announcement not found');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'message' => 'required',
            'type' => 'required|in_list[info,warning,success,danger]',
            'priority' => 'required|in_list[low,medium,high,critical]',
            'target_type' => 'required|in_list[all,department,designation,specific]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $targetIds = null;
        $targetType = $this->request->getPost('target_type');

        if ($targetType === 'department') {
            $targetIds = implode(',', $this->request->getPost('target_departments') ?? []);
        } elseif ($targetType === 'designation') {
            $targetIds = implode(',', $this->request->getPost('target_designations') ?? []);
        } elseif ($targetType === 'specific') {
            $targetIds = $this->request->getPost('target_employees');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'message' => $this->request->getPost('message'),
            'type' => $this->request->getPost('type'),
            'priority' => $this->request->getPost('priority'),
            'target_type' => $targetType,
            'target_ids' => $targetIds,
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'requires_acknowledgment' => $this->request->getPost('requires_acknowledgment') ? 1 : 0,
            'show_once' => $this->request->getPost('show_once') ? 1 : 0,
        ];

        if ($this->announcementModel->update($id, $data)) {
            return redirect()->to('/announcements')->with('success', 'Announcement updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update announcement');
        }
    }

    /**
     * Admin: Delete announcement
     */
    public function delete($id)
    {
        $announcement = $this->announcementModel->find($id);

        if (!$announcement) {
            return redirect()->to('/announcements')->with('error', 'Announcement not found');
        }

        if ($this->announcementModel->delete($id)) {
            return redirect()->to('/announcements')->with('success', 'Announcement deleted successfully');
        } else {
            return redirect()->to('/announcements')->with('error', 'Failed to delete announcement');
        }
    }

    /**
     * Admin: View announcement statistics
     */
    public function statistics($id)
    {
        $announcement = $this->announcementModel->find($id);

        if (!$announcement) {
            return redirect()->to('/announcements')->with('error', 'Announcement not found');
        }

        $stats = $this->announcementModel->getAnnouncementStats($id);
        $acknowledgedList = $this->acknowledgmentModel->getAcknowledgmentHistory($id);
        $pendingList = $this->acknowledgmentModel->getPendingEmployees($id);

        $data = [
            'title' => 'Announcement Statistics',
            'announcement' => $announcement,
            'stats' => $stats,
            'acknowledgedList' => $acknowledgedList,
            'pendingList' => $pendingList
        ];

        return view('Announcements/Statistics', $data);
    }
}
