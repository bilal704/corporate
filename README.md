# corporate
 
Import the database file corporate.sql present in the root directory
Import the collection file Corporate.postman_collection.json into postman

API

1. Auth Token generation before hitting any of the API endpoints

use url http://localhost/corporate/generateToken to generate auth token

2. Adding Departments

URL: http://localhost/corporate/addDepartment
Params: 
    i. token: generated in step 1
    ii. department-name: name of the new departments

3. Adding employees

URL: http://localhost/corporate/addEmployee
Params: 

token:generated in step 1
employee-name:Bilal Ahmed
department-id:1
mobile-no[]:9999999999
mobile-no[]:7777777777
address[]:abc, pqr, kalina, santacurz(E), mumbai -98
address[]:abc, pqr, kalina, santacurz(E), mumbai -71
address[]:abc, pqr, kalina, santacurz(E), mumbai -29


4. View All Employees:

URL: http://localhost/corporate/viewAllEmployees
Param:

token:generated in step 1


5. Search an employee by employee ID

URL: http://localhost/corporate/searchEmployee

token:
employee-id: ID of the employee

OR

mobile-no: mobile number of the employee

OR

employee-name: name of the employee

6. Delete an employee:

URL: http://localhost/corporate/deleteEmployee
Params:

token:
employee-id:1