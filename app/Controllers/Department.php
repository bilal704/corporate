<?php

namespace App\Controllers;
use App\models\DepartmentModel; 

class Department extends BaseController
{
	/**
	 * 
	 * Method to add new department
	 */
	public function addDepartment(){
		
		$department_name = $this->request->getPost('department-name');
		$department_name = htmlentities(stripslashes(trim($department_name)));
	
		if(!isset($department_name) || $department_name == ''){

			return json_encode(['status' => false, 'msg' => 'Department name is required']);
		}

		$this->departmentModel = new DepartmentModel();
		
		if($this->departmentModel->addDepartment($department_name)){

			return json_encode(['status' => true, 'msg' => "New department $department_name has been created successfully"]);
		}
		else{

			return json_encode(['status' => false, 'msg' => "New department $department_name creation failed"]);
		}
	}
}
