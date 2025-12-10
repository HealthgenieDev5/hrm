<?php
namespace App\Models;
use CodeIgniter\Model;

class CompanyModel extends Model{
	protected $table = 'companies';
	protected $allowedFields = ['company_name', 'company_short_name', 'logo_url', 'company_hod', 'address', 'city', 'state', 'pincode', 'phone_number', 'contact_person_name', 'contact_person_mobile', 'contact_person_email_id'];

	public function getAllCompanies(){
		// $CompanyModel = new CompanyModel();
        $this
        ->select('companies.*')
        ->select("trim(concat(employees.first_name, ' ', employees.last_name)) as company_hod_name")
        ->join('employees', 'employees.id = companies.company_hod', 'left');
        
        return $this->findAll();
	}

}
?>