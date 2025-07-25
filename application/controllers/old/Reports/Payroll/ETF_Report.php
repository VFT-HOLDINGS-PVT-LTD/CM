<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ETF_Report extends CI_Controller
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

        $data['title'] = "ETF_Report | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_group'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $this->load->view('Reports/Payroll/ETF_Report', $data);
    }

    /*
     * Get ETF Report By Category
     */

    public function ETF_Report_Report_By_Cat()
    {
        //get company profile
        if (!empty($this->input->post("group"))) {
            $data['data_cmp'] = $this->Db_model->getfilteredData("SELECT tbl_emp_group.EmpGroupName FROM tbl_emp_group WHERE tbl_emp_group.Grp_ID = '" . $this->input->post("group") . "'");
        }
        //get inputa data
        $emp_no = $this->input->post("emp_no");
        $emp_name = $this->input->post("emp_name");
        $designation = $this->input->post("designation");
        $department = $this->input->post("department");
        $branch = $this->input->post("branch");
        $year = $this->input->post("year");
        $month = $this->input->post("month");
        $group = $this->input->post("group");

        if (!empty($year) && (empty($month))) {
            $filter = '';

            if (($this->input->post("year"))) {
                if ($filter == '') {
                    $filter = " where  sa.Year =$year";
                } else {
                    $filter .= " AND  sa.Year =$year";
                }
            }
            if (($this->input->post("emp_no"))) {
                if ($filter == null) {
                    $filter = " where sa.EmpNo =$emp_no";
                } else {
                    $filter .= " AND sa.EmpNo =$emp_no";
                }
            }
            if (($this->input->post("emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("designation"))) {
                if ($filter == null) {
                    $filter = " where dsg.Des_ID  ='$designation'";
                } else {
                    $filter .= " AND dsg.Des_ID  ='$designation'";
                }
            }
            if (($this->input->post("department"))) {
                if ($filter == null) {
                    $filter = " where dep.Dep_id  ='$department'";
                } else {
                    $filter .= " AND dep.Dep_id  ='$department'";
                }
            }
            if (($this->input->post("branch"))) {
                if ($filter == null) {
                    $filter = " where bra.B_id  ='$branch'";
                } else {
                    $filter .= " AND bra.B_id  ='$branch'";
                }
            }
            if (($this->input->post("group"))) {
                if ($filter == null) {
                    $filter = " where gr.Grp_ID  ='$group'";
                } else {
                    $filter .= " AND gr.Grp_ID  ='$group'";
                }
            }


            $data['year'] = $this->input->post('year');
            $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    Emp.EmpNo,
                                                                    Emp.Emp_Full_Name,
                                                                    Emp.EPFNO,
                                                                    Emp.NIC,
                                                                    dsg.Desig_Name,
                                                                    SUM(sa.Total_F_Epf) as Total_ETF,
                                                                    sa.ETF_Amount
                                                                FROM
                                                                    tbl_salary sa
                                                                LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = sa.EmpNo
                                                                LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                LEFT JOIN
                                                                    tbl_branches bra ON bra.B_id = Emp.Dep_id
                                                                LEFT JOIN
	                                                                tbl_emp_group gr ON gr.Grp_ID = Emp.Grp_ID    
                                                                {$filter}
                                                                GROUP BY Emp.EmpNo");

            //cehck if data is empty or not
            if (!empty($data['data_set'])) {
                $this->load->view('Reports/Payroll/rpt_etf', $data);
            } else {
                $this->session->set_flashdata('error_message', 'No Data Found.');
                redirect(base_url() . "Reports/Payroll/ETF_Report");
            }
        } else {
            $filter = '';

            $first_month = 1;
            $middle_month = 6;
            $last_month = 12;

            if (!empty($month)) {
                if ($month == 13) {
                    $filter = " where sa.Month BETWEEN $first_month AND $middle_month";
                } elseif ($month == 14) {
                    $filter = " where sa.Month BETWEEN " . ($middle_month + 1) . " AND $last_month";
                } else {
                    $filter = " where sa.Month = $month";
                }
            }
            if (($this->input->post("emp_no"))) {
                if ($filter == null) {
                    $filter = " where sa.EmpNo =$emp_no";
                } else {
                    $filter .= " AND sa.EmpNo =$emp_no";
                }
            }
            if (($this->input->post("emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("designation"))) {
                if ($filter == null) {
                    $filter = " where dsg.Des_ID  ='$designation'";
                } else {
                    $filter .= " AND dsg.Des_ID  ='$designation'";
                }
            }
            if (($this->input->post("department"))) {
                if ($filter == null) {
                    $filter = " where dep.Dep_id  ='$department'";
                } else {
                    $filter .= " AND dep.Dep_id  ='$department'";
                }
            }
            if (($this->input->post("branch"))) {
                if ($filter == null) {
                    $filter = " where bra.B_id  ='$branch'";
                } else {
                    $filter .= " AND bra.B_id  ='$branch'";
                }
            }
            if (($this->input->post("group"))) {
                if ($filter == null) {
                    $filter = " where gr.Grp_ID  ='$group'";
                } else {
                    $filter .= " AND gr.Grp_ID  ='$group'";
                }
            }


            $data['year'] = $this->input->post('year');
            $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    Emp.EmpNo,
                                                                    Emp.Emp_Full_Name,
                                                                    Emp.EPFNO,
                                                                    Emp.NIC,
                                                                    dsg.Desig_Name,
                                                                    SUM(sa.Total_F_Epf) as Total_ETF,
                                                                    sa.ETF_Amount
                                                                FROM
                                                                    tbl_salary sa
                                                                LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = sa.EmpNo
                                                                LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                LEFT JOIN
                                                                    tbl_branches bra ON bra.B_id = Emp.Dep_id
                                                                LEFT JOIN
	                                                                tbl_emp_group gr ON gr.Grp_ID = Emp.Grp_ID     
                                                                {$filter}
                                                                GROUP BY Emp.EmpNo");
            //check data exists or not
            if (!empty($data['data_set'])) {
                $this->load->view('Reports/Payroll/rpt_etf', $data);
            } else {
                $this->session->set_flashdata('error_message', 'No Data Found.');
                redirect(base_url() . "Reports/Payroll/ETF_Report");
            }
        }
    }

    /*
     * Insert Departmrnt
     */

    // public function Report_department()
    // {
    //     $Data['data_set'] = $this->Db_model->getData('id,Dep_Name', 'tbl_departments');
    //     $this->load->view('Reports/Master/rpt_Departments', $Data);
    // }

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
