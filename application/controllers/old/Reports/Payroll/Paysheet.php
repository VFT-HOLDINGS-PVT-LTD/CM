<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Paysheet extends CI_Controller
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

        $data['title'] = "Pay Sheet | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_group'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        // $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $this->load->view('Reports/Payroll/Paysheet_report', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function Report_department()
    {

        $Data['data_set'] = $this->Db_model->getData('id,Dep_Name', 'tbl_departments');

        $this->load->view('Reports/Master/rpt_Departments', $Data);
    }

    public function Pay_sheet_Report_By_Cat()
    {


        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        date_default_timezone_set('Asia/Colombo');
        $year = date("Y");

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $year1 = $this->input->post("cmb_year");
        $Month = $this->input->post("cmb_month");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");
        $departments = $this->input->post("cmb_departments");

       $groupname =  $this->Db_model->getfilteredData("SELECT tbl_emp_group.EmpGroupName FROM tbl_emp_group WHERE tbl_emp_group.Grp_ID = '$branch'");


        //        $data['f_date'] = $from_date;
        //        $data['t_date'] = $to_date;
        // Filter Data by categories
        $filter = '';

        

        if (($this->input->post("cmb_year"))) {
            if ($filter == null) {
                $filter = " where tbl_salary.Month = '$Month' and tbl_salary.Year ='$year1' AND tbl_empmaster.Status = '1' AND tbl_empmaster.EmpNo != '9000' ";
            } else {
                $filter .= " where tbl_salary.Month = '$Month' and tbl_salary.Year ='$year1' AND tbl_empmaster.Status = '1' AND tbl_empmaster.EmpNo != '9000' ";
            }
        }

        if (($this->input->post("cmb_departments"))) {
            if ($filter == null) {
                $filter = " where tbl_departments.Dep_ID = '$departments'";
            } else {
                $filter .= " AND tbl_departments.Dep_ID = '$departments'";
            }
        }
        if (($this->input->post("cmb_branch"))) {
            if ($filter == null) {
                $filter = " where tbl_emp_group.Grp_ID  ='$branch'";
            } else {
                $filter .= "AND tbl_emp_group.Grp_ID  ='$branch'";
            }
        }




        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    tbl_salary.id,
                                                                    tbl_salary.EmpNo,
                                                                    tbl_empmaster.Emp_Full_Name,
                                                                    tbl_empmaster.Status,
                                                                    tbl_emp_group.EmpGroupName,
                                                                    (tbl_salary.Incentive) as Incentive,
                                                                    tbl_salary.Late_deduction,
                                                                    tbl_empmaster.Fixed_Allowance,
                                                                    tbl_salary.EPFNO,
                                                                    tbl_salary.Ed_deduction,
                                                                    tbl_salary.Festivel_Advance_I,
                                                                    tbl_salary.Br_pay,
                                                                    tbl_salary.Fixed,
                                                                    tbl_salary.Attendances_I,
                                                                    tbl_salary.Attendances_II,
                                                                    tbl_salary.Attendance_bonus,
                                                                    tbl_salary.Risk_allowance_I,
                                                                    tbl_salary.Risk_allowance_II,
                                                                    tbl_salary.Budget_allowance,
                                                                    tbl_salary.Colombo,
                                                                    tbl_salary.Ot_Rate,
                                                                    tbl_salary.Month,
                                                                    tbl_salary.Year,
                                                                    tbl_salary.Basic_sal,
                                                                    tbl_salary.Days_worked,
                                                                    tbl_salary.Extra_shifts,
                                                                    tbl_salary.Extra_shifts_amount,
                                                                    tbl_salary.Payee_amount,
                                                                    tbl_salary.Basic_pay,
                                                                    tbl_salary.Late_deduction,
                                                                    tbl_salary.Late_min,
                                                                    tbl_departments.Dep_Name,
                                                                    tbl_salary.No_Pay_days,
                                                                    tbl_salary.no_pay_deduction,
                                                                    tbl_salary.Normal_OT_Hrs,
                                                                    tbl_salary.Normal_OT_Pay,
                                                                    tbl_salary.Double_OT_Hrs,
                                                                    tbl_salary.Double_OT_Pay,
                                                                    tbl_salary.EPF_Worker_Rate,
                                                                    tbl_salary.EPF_Worker_Amount,
                                                                    tbl_salary.EPF_Employee_Rate,
                                                                    tbl_salary.EPF_Employee_Amount,
                                                                    tbl_salary.ETF_Rate,
                                                                    tbl_salary.ETF_Amount,
                                                                    tbl_salary.Bank_Accounts,
                                                                    tbl_salary.Foods,
                                                                    tbl_salary.Past_deficit,
                                                                    tbl_salary.Detentions,
                                                                    tbl_salary.Bonus,
                                                                    tbl_salary.Loan_Instalment_I,
                                                                    tbl_salary.Loan_Instalment_II,
                                                                    tbl_salary.Loan_Instalment_III,
                                                                    tbl_salary.Loan_Instalment_IV,
                                                                    tbl_salary.Loan_Instalment_V,
                                                                    tbl_salary.Stamp_duty,
                                                                    tbl_salary.Gross_sal,
                                                                    tbl_salary.Gross_pay,
                                                                    tbl_salary.Salary_advance,
                                                                    tbl_salary.tot_deduction,
                                                                    tbl_salary.days_worked,
                                                                    (Allowance_1+Allowance_2+Allowance_3) as Allowances,
                                                                    
                                                                    (Deduct_1+Deduct_2+Deduct_3) as Deductions,
                                                                    tbl_salary.D_Salary,
                                                                    tbl_salary.Net_salary
                                                                FROM
                                                                    tbl_salary
                                                                        INNER JOIN
                                                                    tbl_departments ON tbl_departments.Dep_ID = tbl_salary.Dep_ID
                                                                        LEFT JOIN
                                                                    tbl_allowance_type ON tbl_allowance_type.Alw_ID = tbl_salary.Alw_ID_1
                                                                    inner join
                                                                    tbl_empmaster on tbl_empmaster.EmpNo = tbl_salary.EmpNo
                                                                    inner join
                                                                    tbl_branches on tbl_branches.B_id = tbl_empmaster.B_id
                                                                    inner join
                                                                    tbl_emp_group on tbl_empmaster.Grp_ID = tbl_emp_group.Grp_ID
    {$filter}
                                                                    
                                                                    order by tbl_salary.EmpNo");



        $data['data_month'] = $Month;
        $data['data_year'] = $year1;
        $data['data_group'] = $groupname[0]->EmpGroupName;

        //        var_dump($data);die;

        $this->load->view('Reports/Payroll/rpt_paysheet', $data);
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
