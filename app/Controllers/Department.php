<?php

namespace App\Controllers;
use App\models\DepartmentModel; 
use Exception;
use \Firebase\JWT\JWT;

class Department extends BaseController
{
	/**
     * 
     * Method to authenticate user request via API
     */
    private function verifyAuthToken(string $token){
        
        $key = $this->getSecretKey();
        
        try {

            $decoded = JWT::decode($token, $key, array("HS256"));
            
            if ($decoded) {

                return;
            }
        } catch (Exception $ex) {
          
            echo json_encode(['status' => false, 'msg' => 'Token authentication failed']);
            exit;
        }
    }

    private function getSecretKey(){

        return '28698061-0b46-1d96-c0de-336cdbdb0fa5';
    }

	/**
	 * 
	 * Method to add new department
	 */
	public function addDepartment(){
		
		$this->verifyAuthToken($this->request->getPost('token'));
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
