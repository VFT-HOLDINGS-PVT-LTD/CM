<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_Attendance_Absent_Summary extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }

        /*
         * Load Database model
         */
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page in Report attendance Abcent Summary
     */

    public function index() {
        
        $data['title'] = "Attendance In Out Report Summery | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_group'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $this->load->view('Reports/Attendance/Report_Attendance_Absent_Summary', $data);
    }

    /*
     * Generate Report attendance Absent Summary
    */

    public function Absent_Report_By_Cat() {

        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $date = $this->input->post("txt_from_date");
        $branch = $this->input->post("cmb_branch");
        $data['date'] = $date;

        // Filter Data by categories
        $filter = '';

        if (($this->input->post("cmb_desig"))) {
            $filter .= " AND dsg.Des_ID = '$desig'";
        }

        if (($this->input->post("cmb_dep"))) {
            $filter .= " AND dep.Dep_id = '$dept'";
        }

        if (($this->input->post("cmb_branch"))) {
            $filter .= " AND gr.Grp_ID = '$branch'";
        }

        //get employee absent data from empmaster table
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                Emp.EmpNo,
                                                                Emp.Emp_Full_Name,
                                                                ua.AttDate,
                                                                '{$date}' as date,
                                                                'AB' AS Attendance_Status
                                                                FROM tbl_empmaster Emp
                                                                LEFT JOIN tbl_u_attendancedata ua 
                                                                    ON ua.Enroll_No = Emp.EmpNo
                                                                    AND ua.AttDate = '{$date}'
                                                                LEFT JOIN tbl_designations dsg 
                                                                    ON dsg.Des_ID = Emp.Des_ID
                                                                LEFT JOIN tbl_departments dep 
                                                                    ON dep.Dep_id = Emp.Dep_id
                                                                INNER JOIN tbl_emp_group gr 
                                                                    ON Emp.Grp_ID = gr.Grp_ID
                                                                WHERE Emp.EmpNo != '00009000'
                                                                AND Emp.Status = '1'
                                                                AND ua.Enroll_No IS NULL
                                                                $filter;
                                                            ");
                                                            
        // var_dump($data['data_set']);die;
        $this->load->view('Reports/Attendance/rpt_absent_sum', $data);
    }

}