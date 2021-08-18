<?php

namespace App\Models;
use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';

    /**
     * 
     * Method to insert new department
     */
    public function addDepartment(string $department_name){
        
        $data = [
            'department_name' => $department_name,
            'created_date_time' => date('Y-m-d H:i:s')
        ];

        if($this->db->table($this->table)->insert($data)){

            return 1;
        }
        else{

            return 0;
        }
    }
}
?>