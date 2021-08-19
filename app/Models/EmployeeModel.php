<?php

namespace App\Models;
use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $employee_table = 'employees';
    protected $employee_contact_table = 'employee_contact_details';
    protected $employee_department_table = 'department';

    /**
     * 
     * Method to insert new employee
     */
    public function addEmployee(string $employee_name, int $department_id){
        
        $data = [
            'employee_name' => $employee_name,
            'department_id' => $department_id,
            'created_date_time' => date('Y-m-d H:i:s')
        ];

        if($this->db->table($this->employee_table)->insert($data)){

            return $this->db->insertID();
        }
        else{

            return 0;
        }
    }

    /**
     * 
     * Method to insert new employee contact details
     */
    public function addEmployeeContactDetails(array $data){

        if($this->db->table($this->employee_contact_table)->insert($data)){

            return 1;
        }
        else{

            return 0;
        }
    }

    /**
     * 
     * Method to get all employees and their details
     */
    function viewAllEmployees(){

        $builder = $this->db->table($this->employee_table);
        $builder->select(
            'employees.employee_name, 
            department.department_name, 
            employee_contact_details.mobile_no,
            employee_contact_details.address,
        ');
        $builder->join(
            $this->employee_department_table,
            'department.department_id = employees.department_id', 'inner'
        );
        $builder->join(
            $this->employee_contact_table,
            'employee_contact_details.employee_id = employees.employee_id', 'inner'
        );
        $query = $builder->get();

        return $query->getResult('array');
    }

    /**
     * 
     * Method to delete employee by using employee id
     */
    function deleteEmployee(int $employee_id){

        $builder = $this->db->table($this->employee_contact_table); 
        
        if($builder->where('employee_id', $employee_id)->delete()){

            $builder = $this->db->table($this->employee_table);
            if($builder->where('employee_id', $employee_id)->delete()){

                return true;
            }
            else{

                return false;
            }
        }
        else{

            return false;
        }
    }

    /**
     * 
     * Method to seach an employee
     */
    function searchEmployee(array $search_data){
        
        $builder = $this->db->table($this->employee_table);
        $builder->select(
            'employees.employee_name, 
            department.department_name, 
            employee_contact_details.mobile_no,
            employee_contact_details.address'
        );
        $builder->join(
            $this->employee_department_table,
            'department.department_id = employees.department_id', 'inner'
        );
        $builder->join(
            $this->employee_contact_table,
            'employee_contact_details.employee_id = employees.employee_id', 'inner'
        );

        for($i=0;$i<count($search_data);$i++){

            if($i == 0){

                if(isset($search_data[$i]['employee_id'])){
                    
                    $builder->where('employees.employee_id', $search_data[$i]['employee_id']);
                }
                else{

                    $builder->like($search_data[$i]);
                }
            }
            else{

                if(isset($search_data[$i]['employee_id'])){

                    $builder->orWhere('employees.employee_id', $search_data[$i]['employee_id']);
                }
                else{

                    $builder->orLike($search_data[$i]);
                }
            }
        }

        $query = $builder->get();

        return $query->getResult('array');
    }

    /**
     * 
     * Method to update employee
     */
    public function updateEmployee(array $search_by, array $update_fields){

        $emp_builder = $this->db->table($this->employee_table);
        $emp_contact_builder = $this->db->table($this->employee_contact_table);

        $update_employee = $update_employee_contact = [];
        for($i=0;$i<count($update_fields);$i++){

            foreach($update_fields[$i] as $key => $value){

                if($key == 'department_id' || $key == 'employee_name'){

                    $update_employee[$key] = $value;
                }
                else{

                    $update_employee_contact[$key] = $value;
                }
            }
        }
    
        if(!empty($update_employee)){

            $update_employee['updated_date_time'] = date('Y-m-d H:i:s');
            $emp_builder->update($update_employee, $search_by);
        }

        if(!empty($update_employee_contact)){

            $update_employee_contact['updated_date_time'] = date('Y-m-d H:i:s');
            $emp_contact_builder->update($update_employee_contact, $search_by);
        }
    }
}
?>