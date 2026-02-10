<?php
namespace App\Controllers;

use App\Models\AddressConfirmationModel;
use App\Models\AddressScheduleModel;
use App\Models\EmployeeModel;

class AddressConfirmationController extends BaseController
{
    protected $addressModel;
    protected $scheduleModel;
    protected $employeeModel;
	public $session;

    public function __construct()
    {
		helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
        $this->addressModel = new AddressConfirmationModel();
        $this->scheduleModel = new AddressScheduleModel();
        $this->employeeModel = new EmployeeModel();
    }

    public function checkPopupRequired()
    {
        $employeeId = $this->session->get('current_user')['employee_id'];

        // Check if popup should be shown
        $schedule = $this->scheduleModel->where('employee_id', $employeeId)->first();
        $needsPopup = false;

        if ($schedule) {
            if ($schedule['next_confirmation_date'] <= date('Y-m-d')) {
                $this->scheduleModel->update($schedule['id'], ['is_popup_active' => true]);
                $needsPopup = true;
            }
        }

        return $this->response->setJSON(['show_popup' => $needsPopup]);
    }

    public function submitAddressConfirmation()
    {
		$rules = [
			'address_text'  =>  [
				'rules'         =>  'required',
				'errors'        =>  [
					'required'  => 'Address is required',
				]
			],
			'document_type'  =>  [
				'rules'         =>  'required|in_list[rent_agreement,aadhaar,other]',
				'errors'        =>  [
					'required'  => 'Personal Email is required',
					'in_list'  => 'Document type must be in rent_agreement,aadhaar,other'
				]
			]
		];

		$validation = $this->validate($rules);

		if (!$validation) {
			$response_array['response_type'] = 'error';
			$response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
			$errors = $this->validator->getErrors();
			$response_array['response_data']['validation'] = $errors;
		} else {
			$data = [
				'employee_id' => $this->session->get('current_user')['employee_id'],
				'address_text' => $this->request->getPost('address_text'),
				'document_type' => $this->request->getPost('document_type'),
				'status' => 'pending'
			];
			$address_document = $this->request->getFile('address_document');
			if ($address_document->isValid() && ! $address_document->hasMoved()) {
				$upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
				$address_document_uploaded = $address_document->move($upload_folder);
				if ($address_document_uploaded) {
					$data['document_path'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $address_document->getName());
				}
			}

			$this->addressModel->insert($data);

			// Update schedule
			try {
				$this->scheduleModel
				->where('employee_id', $this->session->get('current_user')['employee_id'])
				->set(['is_popup_active' => false])
				->update();
			} catch (\Throwable $th) {
				throw $th;
			}

			return $this->response->setJSON([
				'status' => 'success',
				'message' => 'Address confirmation submitted successfully'
			]);
		}
    }

    public function snoozePopup()
    {
		$this->scheduleModel->snoozeConfirmation($this->session->get('current_user')['employee_id']);

		return $this->response->setJSON([
			'status' => 'success',
			'message' => 'Popup snoozed for 1 day'
		]);
    }

    // HR Functions
    public function hrDashboard()
    {
        $pendingConfirmations = $this->addressModel->getPendingConfirmations();
        return view('hr/address_confirmations', [
            'pending_confirmations' => $pendingConfirmations
        ]);
    }

    public function reviewSubmission()
    {
        $submissionId = $this->request->getPost('submission_id');
        $action = $this->request->getPost('action'); // approve/reject
        $comments = $this->request->getPost('comments');

        $data = [
            'status' => $action === 'approve' ? 'approved' : 'rejected',
            'hr_reviewed_by' => session('user_id'),
            'hr_review_date' => date('Y-m-d H:i:s'),
            'hr_comments' => $comments
        ];

        $this->addressModel->update($submissionId, $data);

        if ($action === 'approve') {
            // Update master employee record
            $submission = $this->addressModel->find($submissionId);
            $this->employeeModel->update($submission['employee_id'], [
                'address' => $submission['address_text'],
                'last_address_confirmed_date' => date('Y-m-d H:i:s')
            ]);

            // Schedule next confirmation (6 months)
            $nextDate = date('Y-m-d', strtotime('+6 months'));
            $this->scheduleModel->where('employee_id', $submission['employee_id'])
                               ->set(['next_confirmation_date' => $nextDate])
                               ->update();
        } else {
            // Rejected - reactivate popup
            $submission = $this->addressModel->find($submissionId);
            $this->scheduleModel->where('employee_id', $submission['employee_id'])
                               ->set(['is_popup_active' => true])
                               ->update();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => ucfirst($action) . 'd successfully'
        ]);
    }

    public function bulkApprove()
    {
        $submissionIds = $this->request->getPost('submission_ids');

        foreach ($submissionIds as $id) {
            $this->addressModel->update($id, [
                'status' => 'approved',
                'hr_reviewed_by' => session('user_id'),
                'hr_review_date' => date('Y-m-d H:i:s')
            ]);

            $submission = $this->addressModel->find($id);
            $this->employeeModel->update($submission['employee_id'], [
                'address' => $submission['address_text'],
                'last_address_confirmed_date' => date('Y-m-d H:i:s')
            ]);

            $nextDate = date('Y-m-d', strtotime('+6 months'));
            $this->scheduleModel->where('employee_id', $submission['employee_id'])
                               ->set(['next_confirmation_date' => $nextDate])
                               ->update();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Bulk approval completed'
        ]);
    }
}