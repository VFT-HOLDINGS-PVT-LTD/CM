<?php

defined('BASEPATH') or exit('No direct script access allowed');

class EPF_Report extends CI_Controller
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
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page in Departmrnt
     */

    public function index()
    {

        $data['title'] = "EPF_Report | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['data_group'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $this->load->view('Reports/Payroll/EPF_Report', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function Report_department()
    {

        $Data['data_set'] = $this->Db_model->getData('id,Dep_Name', 'tbl_departments');

        $this->load->view('Reports/Master/rpt_Departments', $Data);
    }

    public function EPF_Report_Report_By_Cat()
    {


        if(!empty($this->input->post("cmb_group"))){
            $data['data_cmp'] = $this->Db_model->getfilteredData("SELECT tbl_emp_group.EmpGroupName FROM tbl_emp_group WHERE tbl_emp_group.Grp_ID = '" . $this->input->post("cmb_group") . "'");
        }
        date_default_timezone_set('Asia/Colombo');
        // $year = date("Y");

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $deptmnt = $this->input->post("cmb_dep");
        $group = $this->input->post("cmb_group");
        $year = $this->input->post("cmb_year");
        $Month = $this->input->post("cmb_month");

        $data['year'] = $year;
        $data['month'] = $Month;
        $filter = '';

        if (($this->input->post("cmb_year"))) {
            if ($filter == '') {
                $filter = "where tbl_salary.Year = '$year' AND tbl_empmaster.Status = '1' AND tbl_empmaster.EmpNo != '9000' ";
            } else {
                $filter .= " AND tbl_salary.Year = '$year' AND tbl_empmaster.Status = '1' AND tbl_empmaster.EmpNo != '9000' ";
            }
        }else{
            redirect(base_url() . 'Reports/Payroll/EPF_Report');
        }
        if (($this->input->post("cmb_month"))) {
            if ($filter == null) {
                $filter = " where tbl_salary.Month = '$Month'";
            } else {
                $filter .= " AND tbl_salary.Month = '$Month'";
            }
        }
        
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.EmpNo ='$emp'";
            } else {
                $filter .= " AND tbl_empmaster.EmpNo ='$emp'";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.Emp_Full_Name ='$emp_name'";
            } else {
                $filter .= " AND tbl_empmaster.Emp_Full_Name ='$emp_name'";
            }
        }
        if (($this->input->post("cmb_desig"))) {
            if ($filter == null) {
                $filter = " where tbl_designations.Des_ID  ='$desig'";
            } else {
                $filter .= " AND tbl_designations.Des_ID  ='$desig'";
            }
        }
        if (($this->input->post("cmb_dep"))) {
            if ($filter == null) {
                $filter = " where tbl_departments.Dep_id  ='$deptmnt'";
            } else {
                $filter .= " AND tbl_departments.Dep_id  ='$deptmnt'";
            }
        }

        if (($this->input->post("cmb_group"))) {
            if ($filter == null) {
                $filter = " where tbl_emp_group.Grp_ID  ='$group'";
            } else {
                $filter .= " AND tbl_emp_group.Grp_ID  ='$group'";
            }
        }

        



        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
   tbl_salary.Year, 
   tbl_salary.EmpNo,
   tbl_salary.Total_F_Epf, 
   tbl_empmaster.Emp_Full_Name, 
   tbl_empmaster.EPFNO,
   tbl_empmaster.Grp_ID,
   tbl_empmaster.NIC,
   tbl_emp_group.EmpGroupName,
   tbl_empmaster.Status,
   tbl_departments.Dep_Name,
   tbl_designations.Desig_Name,
    SUM(tbl_salary.EPF_Worker_Amount) AS Total_EPF_Worker_Amount, 
    SUM(tbl_salary.EPF_Employee_Amount) AS Total_EPF_Employee_Amount
FROM tbl_salary
INNER JOIN tbl_empmaster ON tbl_empmaster.EmpNo = tbl_salary.EmpNo
INNER JOIN tbl_departments ON tbl_departments.Dep_ID = tbl_salary.Dep_ID
INNER JOIN tbl_designations ON tbl_salary.Des_ID = tbl_designations.Des_ID
INNER JOIN tbl_emp_group on tbl_empmaster.Grp_ID = tbl_emp_group.Grp_ID
    {$filter}
                                                                    
GROUP BY tbl_salary.Year, tbl_salary.EmpNo, tbl_empmaster.Emp_Full_Name
ORDER BY tbl_salary.EmpNo");



        $data['data_month'] = $Month;
        $this->load->view('Reports/Payroll/rpt_epf', $data);
    }

    function get_auto_emp_name()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_name($q);
        }
    }

    function get_auto_emp_no()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_no($q);
        }
    }
}
