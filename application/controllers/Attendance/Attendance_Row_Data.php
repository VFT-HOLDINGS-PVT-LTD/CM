<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_Row_Data extends CI_Controller {

    public function __construct() {
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

    public function index() {

        $data['title'] = "Attendance Row Data | HRM System";
        $data['data_set'] = $this->Db_model->getfilteredData('SELECT 
    EventID,
    Emp_Full_Name,
    tbl_u_attendancedata.Enroll_No, 
    AttDate, 
    MIN(AttTime) AS InTime, 
    MAX(AttTime) AS OutTime 
FROM 
    tbl_u_attendancedata
LEFT OUTER JOIN 
    tbl_empmaster
ON 
    tbl_empmaster.EmpNo = tbl_u_attendancedata.Enroll_No
GROUP BY 
    tbl_u_attendancedata.Enroll_No, AttDate
ORDER BY 
    AttDate DESC,tbl_u_attendancedata.Enroll_No ASC;
');
        $this->load->view('Attendance/Attendance_Row_Data/index', $data);
    }

    /*
     * Insert
     */

    public function insert_Designation() {

        $data = array(
            'Desig_Name' => $this->input->post('txt_desig_name'),
            'Desig_Order' => $this->input->post('txt_desig_order')
        );

        $result = $this->Db_model->insertData("tbl_designations", $data);


        if ($result) {
            $condition = 1;
        } else {
            
        }

        $info[] = array('a' => $condition);
        echo json_encode($info);
    }

    /*
     * Get data
     */

    public function get_details() {
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

    public function edit() {
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

    public function ajax_delete($id) {
        $table = "tbl_designations";
        $where = 'id';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

}
