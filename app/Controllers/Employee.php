<?php

namespace App\Controllers;
use App\models\EmployeeModel;
use Exception;
use \Firebase\JWT\JWT;

class Employee extends BaseController
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
    
    /**
	 * 
	 * Method to add new employee
	 */
	public function addEmployee(){
		
        $this->verifyAuthToken($this->request->getPost('token'));

        $employee_name = $this->request->getPost('employee-name');
        $department_id = $this->request->getPost('department-id');
        $mobile_no = $this->request->getPost('mobile-no');
        $address_arr = $this->request->getPost('address');
        
		$this->checkData($employee_name, 'Employee name');
        $this->checkData($department_id, 'Department ID');

        $this->employeeModel = new EmployeeModel();
		$emp_id = $this->employeeModel->addEmployee($employee_name, intVal($department_id));

		if($emp_id){

            $mobile = $this->checkMobile($mobile_no);
            $address = $this->checkAddress($address_arr);

            $insert_contact = [
                'employee_id' => $emp_id,
                'mobile_no' => $mobile,
                'address' => $address,
                'created_date_time' => date('Y-m-d H:i:s')
            ];

            $this->employeeModel->addEmployeeContactDetails($insert_contact);

			return json_encode(['status' => true, 'msg' => "Employee has been added successfully"]);
		}
		else{

			return json_encode(['status' => false, 'msg' => "Employee record creation failed"]);
		}
	}

    /**
     * 
     * Method to view all employees with their department and contact details
     */
    public function viewAllEmployees(){

        $this->verifyAuthToken($this->request->getPost('token'));
        $this->employeeModel = new EmployeeModel();

        $employees_arr = $this->employeeModel->viewAllEmployees();

        if(!empty($employees_arr)){

            return json_encode(['status' => true, 'data' => $employees_arr]);
        }
        else{

            return json_encode(['status' => false, 'msg' => 'No employees exist in the company']);
        }
    }

    /**
     * 
     * Method to delete an employee by their ID
     */
    public function deleteEmployee(){

        $this->verifyAuthToken($this->request->getPost('token'));
        $emp_id = $this->request->getPost('employee-id');
        $emp_id = htmlentities(stripslashes(trim($emp_id)));

        if($emp_id == '' || !is_integer(intVal($emp_id))){

            return json_encode(['status' => false, 'msg' => 'Please provide a proper employee ID']);
        }

        $this->employeeModel = new EmployeeModel();

        $result = $this->employeeModel->deleteEmployee(intVal($emp_id));

        if($result){

            return json_encode(['status' => true, 'msg' => 'Employee deleted successfully']);
        }
        else{

            return json_encode(['status' => false, 'msg' => 'Something went wrong!!']);
        }
    }

    /**
     * 
     * Method to search an employee by name/employee_id/mobile
     */

    public function searchEmployee(){

        $this->verifyAuthToken($this->request->getPost('token'));
        $search_by_columns = [];

        if(isset($_POST['employee-id']) && trim($_POST['employee-id']) != ''){

            $emp_id = $this->request->getPost('employee-id');
            $emp_id = htmlentities(stripslashes(trim($emp_id)));
            $search_by_columns[] = ['employee_id' => intVal($emp_id)];
        }
        
        if(isset($_POST['employee-name']) && trim($_POST['employee-name']) != ''){

            $emp_name = $this->request->getPost('employee-name');
            $emp_name = htmlentities(stripslashes(trim($emp_name)));
            $search_by_columns[] = ['employee_name' => $emp_name];
        }
        
        if(isset($_POST['mobile-no']) && trim($_POST['mobile-no']) != ''){

            $mobile_no = $this->request->getPost('mobile-no');
            $mobile_no = htmlentities(stripslashes(trim($mobile_no)));
            $search_by_columns[] = ['mobile_no' => $mobile_no];
        }
        
        if(!empty($search_by_columns)){
            
            $this->employeeModel = new EmployeeModel();
            $employees_arr = $this->employeeModel->searchEmployee($search_by_columns);
            
            if(!empty($employees_arr)){

                return json_encode(['status' => true, 'data' => $employees_arr]);
            }
            else{

                return json_encode(['status' => false, 'msg' => 'No employees found']);
            }
        }
        else{

            return json_encode(['status' => false, 'msg' => 'Not sufficient data provided for the search']);
        }
    }

    /**
     * Method to update employee details
     */
    public function updateEmployee(){
        
        verifyAuthToken($this->request->getPost('token'));
        $search_by = $update_fields = [];
        if(isset($_POST['employee-id']) && trim($_POST['employee-id']) != ''){

            $employee_id = $this->request->getPost('employee-id');
            $search_by = ['employee_id' => $employee_id];
        }

        if(isset($_POST['employee-name']) && trim($_POST['employee-name']) != ''){

            $employee_name = $this->request->getPost('employee-name');
            $update_fields[] = ['employee_name' => $employee_name];
        }

        if(isset($_POST['department-id']) && trim($_POST['department-id']) != ''){

            $department_id = $this->request->getPost('department-id');
            $update_fields[] = ['department_id' => $department_id];
        }
        
        if(isset($_POST['mobile-no']) && count($_POST['mobile-no']) > 0){

            $mobile_no = $this->request->getPost('mobile-no');
            $update_fields[] = ['mobile_no' => json_encode($mobile_no)];
        }
        
        if(isset($_POST['address']) && count($_POST['address']) > 0){

            $address = $this->request->getPost('address');
            $update_fields[] = ['address' => json_encode($address)];
        }

        if(empty($search_by) || empty($update_fields)){

            return json_encode(['status' => false, 'msg' => 'Either Employee ID or fields that needs to be updated are not provided']);
        }
        
        $this->employeeModel = new EmployeeModel();
        $this->employeeModel->updateEmployee($search_by, $update_fields);

        echo json_encode(['status' => false, 'msg' => "Record updated successfully"]);
        exit;
    }

    private function checkData(string $data, string $field_name){
        
        $data = htmlentities(stripslashes(trim($data)));
	
		if(!isset($data) || $data == ''){

			echo json_encode(['status' => true, 'msg' => $field_name.' is required']);
            exit;
		}
    }

    private function checkMobile(array $mobile_no_arr){

        $response = [];
        foreach($mobile_no_arr as $mobile){

            $mobile = htmlentities(stripslashes(trim($mobile)));
            
            if($mobile != ''){

                $mobile = intVal($mobile);

                if(strlen($mobile) > 10 || strlen($mobile) < 10){

                    echo json_encode(['status' => false, 'msg' => "$mobile should be of 10 digits"]);
                    exit;
                }
                else if(!is_integer($mobile)){

                    echo json_encode(['status' => false, 'msg' => "$mobile should contain 10 digits number only"]);
                    exit;
                }

                $response[] = $mobile;
            }
        }

        if(empty($response)){

            echo json_encode(['status' => false, 'msg' => "Atleast one mobile no. is required"]);
            exit;
        }

        return json_encode($response);
    }

    private function checkAddress(array $address_arr){

        $response = [];
        foreach($address_arr as $address){

            $address = htmlentities(stripslashes(trim($address)));

            if($address != ''){

                $response[] = $address;
            }
        }

        if(empty($response)){

            echo json_encode(['status' => false, 'msg' => "Atleast one address is required"]);
            exit;
        }

        return json_encode($response);
    }

    /**
     * 
     * Generate token for API Authentication
     */
    public function generateToken(){

        $key = $this->getSecretKey();
        
        $iat = time(); // current timestamp value
        $nbf = $iat + 10;
        $exp = $iat + 3600;

        $payload = array(
            "iss" => "The_claim",
            "aud" => "The_Aud",
            "iat" => $iat, // issued at
            "nbf" => $nbf, //not before in seconds
            "exp" => $exp, // expire time in seconds
            "data" => ''
        );

        return $token = JWT::encode($payload, $key);
    }

    private function getSecretKey(){

        return '28698061-0b46-1d96-c0de-336cdbdb0fa5';
    }
}
