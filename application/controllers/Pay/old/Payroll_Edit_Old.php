<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_Edit_Old extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        /*
         * Load Database model
         */
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page
     */

    public function index()
    {

        $data['title'] = "Payroll Edit | HRM System";
        $currentMonth = date('m');
        // date('M');
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary INNER JOIN tbl_empmaster ON tbl_salary.EmpNo = tbl_empmaster.EmpNo WHERE tbl_salary.Month = '" . $currentMonth . "' AND tbl_empmaster.EmpNo != '00009000' ");
        $currentMonth2 = date('F');
        $data['months'] = $currentMonth2;
        $this->load->view('Payroll/Payroll_Edit_Old/index', $data);
    }



    //  public function edit_data() {  
    //     // Decode the JSON input sent via POST
    //     $postData = json_decode(file_get_contents('php://input'), true);

    //     // Check if data is received
    //     if ($postData) {
    //         // Log the received data (for debugging purposes)
    //         log_message('info', 'Row data received: ' . print_r($postData, true));

    //         // Perform the desired update operation here (e.g., database update)
    //         // Example: Assuming $postData['row'] contains the updated row data
    //         $rowData = $postData['row'];

    //         // Sample response after processing
    //         $response = [
    //             'status' => 'success',
    //             'message' => 'Row updated successfully',
    //             'data' => $rowData // Echo back the received data for verification
    //         ];
    //     } else {
    //         // Handle case when no data is received
    //         $response = [
    //             'status' => 'error',
    //             'message' => 'No data received'
    //         ];
    //     }

    //     // Send response back as JSON
    //     echo json_encode($response);
    // }

    // public function edit_data() {
    //     header('Content-Type: application/json'); // Ensure JSON response

    //     $postData = json_decode(file_get_contents('php://input'), true);

    //     if ($postData) {
    //         // Process the data as before
    //         $rowData = $postData['row'];
    //         $tableName = 'tbl_salary';

    //         $dataArray = [
    //             'Emp_Full_Name'     => $rowData[1],
    //             'Basic_sal'         => $rowData[2],
    //             'Fixed_Allowance'   => $rowData[3],
    //             'Br_pay'            => $rowData[4],
    //             'Incentive'         => $rowData[5],
    //             'No_Pay_days'       => $rowData[6],
    //             'no_pay_deduction'  => $rowData[7],
    //             'Gross_sal'         => $rowData[8],
    //         ];

    //         $whereArray = ['EmpNo' => $rowData[0]];

    //         $this->load->model('Db_model'); // Ensure model is loaded
    //         $result = $this->Db_model->updateData($tableName, $dataArray, $whereArray);

    //         if ($result) {
    //             echo json_encode([
    //                 'status' => 'success',
    //                 'message' => 'Row updated successfully',
    //                 'updatedData' => $dataArray
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'status' => 'error',
    //                 'message' => 'Failed to update the row'
    //             ]);
    //         }
    //     } else {
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'No data received'
    //         ]);
    //     }
    // }


    public function edit_data()
    {
        // Check if the request is AJAX
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Get the JSON input
            $postData = file_get_contents('php://input');

            // Decode the JSON string into a PHP array
            $data = json_decode($postData, true);

            $ID = $data['rowData'][0];
            $salary_month = $data['month'][0];
            $year = date("Y");
            // Check if a row with the given ID exists
            $HasRow = $this->Db_model->getfilteredData("SELECT COUNT(ID) as HasRow FROM tbl_salary_edited WHERE tbl_salary_edited.Salary_t_id = '$ID'");

            // Prepare data array
            $dataArray = array(
                'Salary_t_id' => $ID,
                'Basic_sal' => $data['rowData'][3],
                'EmpNo' => $data['rowData'][1],
                'Month' => $salary_month,
                'Year' => $year,
                'Days_worked' => $data['rowData'][4],
                'Normal_OT_Pay' => $data['rowData'][5],
                'Attendances_I' => $data['rowData'][6],
                'Attendances_II' => $data['rowData'][7],
                'Budget_allowance' => $data['rowData'][8],
                'Incentive' => $data['rowData'][9],
                'Risk_allowance_I' => $data['rowData'][10],
                'Risk_allowance_II' => $data['rowData'][11],
                'Colombo' => $data['rowData'][12],
                'Gross_sal' => $data['rowData'][13],
                'EPF_Worker_Amount' => $data['rowData'][14],
                'Salary_advance' => $data['rowData'][15],
                'Bank_Accounts' => $data['rowData'][16],
                'Loan_Instalment_I' => $data['rowData'][17],
                'Festivel_Advance_I' => $data['rowData'][18],
                'Foods' => $data['rowData'][19],
                'Past_deficit' => $data['rowData'][20],
                'Loan_Instalment_II' => $data['rowData'][21],
                'Detentions' => $data['rowData'][22],
                'Bonus' => $data['rowData'][23],
                'Loan_Instalment_III' => $data['rowData'][24],
                'Loan_Instalment_IV' => $data['rowData'][25],
                'tot_deduction' => $data['rowData'][26],
                'Net_salary' => $data['rowData'][27],
                'Edited' => 1,
                'Approved' => 0,
            );


            if ($HasRow[0]->HasRow > 0) {
                // Update the existing row
                $whereArray = array("Salary_t_id" => $ID);
                $result = $this->Db_model->updateData("tbl_salary_edited", $dataArray, $whereArray);
                $dataArray_1 = array(
                    'Edited' => 1,
                    'Approved' => 0,
                );
                $whereArray_1 = array("ID" => $ID);
                $result = $this->Db_model->updateData("tbl_salary", $dataArray_1, $whereArray_1);

                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Row updated successfully'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update row'
                    ]);
                }
            } else {
                // Insert a new row
                $result = $this->db->insert('tbl_salary_edited', $dataArray);
                $dataArray_1 = array(
                    'Edited' => 1,
                    'Approved' => 0,
                );
                $whereArray_1 = array("ID" => $ID);
                $result = $this->Db_model->updateData("tbl_salary", $dataArray_1, $whereArray_1);

                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Row inserted successfully'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to insert row'
                    ]);
                }
            }
        } else {
            // Respond with error for non-POST requests
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    // public function Payroll_Search() {
    //       // Decode the incoming JSON input
    // $input = json_decode(file_get_contents("php://input"), true);

    // // Check if the expected data is present
    // $empNo = isset($input['txt_emp']) ? $input['txt_emp'] : '';
    // $empName = isset($input['txt_emp_name']) ? $input['txt_emp_name'] : '';
    // $month = isset($input['cmb_month']) ? $input['cmb_month'] : '';

    // // Create an example response
    // $response = [
    //     'empNo' => $empNo,
    //     'empName' => $empName,
    //     'month' => $month,
    //     'status' => 'success', // Example status
    // ];

    // // Set the content type to application/json
    // header('Content-Type: application/json');

    // // Send the response as JSON
    // echo json_encode($response);
    //     // $data = '';
    //     // Get JSON input
    //     // $input = json_decode(file_get_contents("php://input"), true);
    //     // $inputdata = json_decode($input, true);

    //     // $empNo = $inputdata['empNo'];
    //     // $empName = $inputdata['empName'];
    //     // $data = $inputdata['month'];
    //     // $monthNumber = date('n', strtotime($month)); // Converts month name to its number

    //     // $month = $data;

    //     // if ($data) {
    //     //     echo json_encode($data); // Send JSON response
    //     // } else {
    //     //     echo json_encode(["status" => "No data found"]);
    //     // }
    //     // $filter = '';

    //     // if (($this->input->post("cmb_month")) && ($this->input->post("cmb_year"))) {
    //     //     if ($filter == '') {
    //     //         $filter = "where tbl_salary.Month = '$month'";
    //     //     } else {
    //     //         $filter .= " AND  tbl_salary.Month = '$month'";
    //     //     }
    //     // }
    //     // if (($this->input->post("txt_emp"))) {
    //     //     if ($filter == null) {
    //     //         $filter = " where ir.EmpNo =$empNo";
    //     //     } else {
    //     //         $filter .= " AND ir.EmpNo =$empNo";
    //     //     }
    //     // }

    //     // if (($this->input->post("txt_emp_name"))) {
    //     //     if ($filter == null) {
    //     //         $filter = " where Emp.Emp_Full_Name ='$empName'";
    //     //     } else {
    //     //         $filter .= " AND Emp.Emp_Full_Name ='$empName'";
    //     //     }
    //     // }

    //     // // Call your model to get filtered results
    //     // // $data = $this->Payroll_Model->searchPayroll($empNo, $empName, $month);
    //     // $data = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary INNER JOIN tbl_empmaster ON tbl_salary.EmpNo = tbl_empmaster.EmpNo {$filter} AND tbl_empmaster.EmpNo != '00009000' ");


    //     // // Return data as JSON
    //     // echo json_encode($data);
    // }


    /*
     * Get data
     */
    public function Payroll_Search()
    {
        // Decode the JSON input
        $input = json_decode(file_get_contents("php://input"), true);

        // Check if the input was successfully decoded
        if ($input === null) {
            // If decoding fails, return an error response
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload']);
            return;
        }

        // Retrieve values from the decoded input
        $empNo = isset($input['empNo']) ? $input['empNo'] : null;
        $empName = isset($input['empName']) ? $input['empName'] : null;
        $month = isset($input['month']) ? $input['month'] : null;
        $year = date("Y");

        // Log the received data (for debugging purposes)
        // log_message('info', "Received empNo: {$empNo}, password: {$password}");
        // echo $empNo;


        // Initialize filter and parameters for SQL query
        $filter = "where tbl_salary.Approved = '1' and tbl_salary.Year = '$year'";
        $params = [];
        // echo $filter;
        // Build filter query based on provided values
        if (!empty($empNo)) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.EmpNo = '$empNo' AND tbl_empmaster.EmpNo != '00009000' ";
            } else {
                $filter .= " AND tbl_empmaster.EmpNo = '$empNo' AND tbl_empmaster.EmpNo != '00009000' ";
            }
        }

        if (!empty($empName)) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.Emp_Full_Name = '$empName' AND tbl_empmaster.EmpNo != '00009000' ";
            } else {
                $filter .= " AND tbl_empmaster.Emp_Full_Name = '$empName' AND tbl_empmaster.EmpNo != '00009000' ";
            }
        }

        if (!empty($month)) {
            if ($filter == null) {
                $filter = " where tbl_salary.Month = '$month' AND tbl_empmaster.EmpNo != '00009000' ";
            } else {
                $filter .= " AND tbl_salary.Month = '$month' AND tbl_empmaster.EmpNo != '00009000' ";
            }
        }
        // echo $filter;
        $data = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary 
            INNER JOIN tbl_empmaster 
            ON tbl_salary.EmpNo = tbl_empmaster.EmpNo 
            {$filter} ");

        if ($data) {
            echo json_encode($data); // Send the data as JSON
        } else {
            echo json_encode(["status" => "No data found"]); // Send a "No data found" status if no results are returned
        }

        // // Decode JSON input
        // $input = json_decode(file_get_contents("php://input"), true);

        // // Retrieve data from the decoded input
        // $empNo = isset($input['empNo']) ? $input['empNo'] : '';
        // $empName = isset($input['txt_emp_name']) ? $input['txt_emp_name'] : '';
        // $month = isset($input['cmb_month']) ? $input['cmb_month'] : '';

        // // Initialize filter and parameters for SQL query
        // $filter = '';
        // $params = [];

        // // Build filter query based on provided values
        // if (!empty($month)) {
        //     $filter .= " WHERE tbl_salary.Month = ?";
        //     $params[] = $month;
        // }
        // if (!empty($empNo)) {
        //     $filter .= (empty($filter) ? " WHERE " : " AND ") . "tbl_salary.EmpNo = ?";
        //     $params[] = $empNo;
        // }
        // if (!empty($empName)) {
        //     $filter .= (empty($filter) ? " WHERE " : " AND ") . "tbl_empmaster.Emp_Full_Name LIKE ?";
        //     $params[] = "%$empName%";
        // }

        // // SQL query to fetch filtered data
        // $sql = "SELECT * FROM tbl_salary 
        //     INNER JOIN tbl_empmaster 
        //     ON tbl_salary.EmpNo = tbl_empmaster.EmpNo 
        //     {$filter} AND tbl_empmaster.EmpNo != '00009000'";

        // // Call model to execute the query and get filtered data
        // $data = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary 
        //     INNER JOIN tbl_empmaster 
        //     ON tbl_salary.EmpNo = tbl_empmaster.EmpNo 
        //     {$filter} AND tbl_empmaster.EmpNo != '00009000'");

        // // Return data as JSON response
        // if ($data) {
        //     echo json_encode($data); // Send the data as JSON
        // } else {
        //     echo json_encode(["status" => "No data found"]); // Send a "No data found" status if no results are returned
        // }
    }


    public function get_details()
    {
        $id = $this->input->post('id');

        //                    echo "OkM " . $id;

        $whereArray = array('ID' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('ID,Desig_Name,Desig_Order', 'tbl_designations');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit()
    {
        $ID = $this->input->post("id", TRUE);
        $D_Name = $this->input->post("Desig_Name", TRUE);
        $D_Order = $this->input->post("Desig_Order", TRUE);

        $data = array("Desig_Name" => $D_Name, 'Desig_Order' => $D_Order);
        $whereArr = array("id" => $ID);
        $result = $this->Db_model->updateData("tbl_designations", $data, $whereArr);
        redirect(base_url() . "Master/Designation");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id)
    {
        $table = "tbl_designations";
        $where = 'id';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }
}
