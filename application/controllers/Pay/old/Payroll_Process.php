<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_Process extends CI_Controller
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

        $this->load->helper('url');
        $data['title'] = "Payroll Process | HRM SYSTEM";
        $data['data_emp'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $this->load->view('Payroll/Payroll_process/index', $data);
    }

    /*
     * Payroll Process
     */

    public function emp_payroll_process()
    {
        //die;
        date_default_timezone_set('Asia/Colombo');
        $year = date("Y");
        $month = $this->input->post('cmb_month');

        $date = date_create();
        $timestamp = date_format($date, 'Y-m-d H:i:s');
        $check_servicedate = $timestamp = date_format($date, 'Y-m-d');


        $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT EmpNo,Grp_ID,EMP_ST_ID,Enroll_No,BR1,BR2, EPFNO,Dep_ID,Des_ID,RosterCode, Status  FROM  tbl_empmaster where status=1 and Active_process=1");
        //        $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT EmpNo,Enroll_No, EPFNO,Dep_ID,Des_ID,RosterCode, Status  FROM  tbl_empmaster where EmpNo=3316");
        //For loop for All active employees 
        for ($x = 0; $x < count($dtEmp['EmpData']); $x++) {



            $EmpNo = $dtEmp['EmpData'][$x]->EmpNo;
            $EmpGrp = $dtEmp['EmpData'][$x]->Grp_ID;
            $EpfNo = $dtEmp['EmpData'][$x]->EPFNO;
            $Dep_ID = $dtEmp['EmpData'][$x]->Dep_ID;
            $Des_ID = $dtEmp['EmpData'][$x]->Des_ID;
            $br1 = $dtEmp['EmpData'][$x]->BR1;
            $br2 = $dtEmp['EmpData'][$x]->BR2;

            $Emp_ST = $dtEmp['EmpData'][$x]->EMP_ST_ID;


            $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow , tbl_salary.Edited , tbl_salary.Approved from tbl_salary where EmpNo=$EmpNo and month=$month and year=$year");
            // $setting = $this->Db_model->getfilteredData("SELECT * FROM tbl_payroll_setting INNER JOIN tbl_emp_group ON tbl_payroll_setting.Group_Id = tbl_emp_group.Grp_ID WHERE tbl_emp_group.Grp_ID = '$EmpGrp'");

            //            var_dump($HasRow);die;
            //IF Salary records have in Salary table update salary records into salary table

            if ($HasRow[0]->HasRow > 0 && $HasRow[0]->Edited == 0) {

                $SalData = $this->Db_model->getfilteredData("select EmpNo,EPFNO,Is_EPF, Dep_ID, Des_ID,Basic_Salary,Fixed_Allowance,is_nopay_calc,ApointDate,Permanent_Date from tbl_empmaster where EmpNo=$EmpNo");
                //setting wala daily rate yanawanan
                $daily_rate = $SalData[0]->Basic_Salary;
                $Fixed_Allowance = $SalData[0]->Fixed_Allowance;
                $Is_EPF = $SalData[0]->Is_EPF;
                $is_no_pay = $SalData[0]->is_nopay_calc;
                $service_time = $SalData[0]->ApointDate;
                $permenent_time = $SalData[0]->Permanent_Date;

                //shifts tika serama
                $shift_count = $this->Db_model->getfilteredData("select sum(Day_Type) as shifts from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate) =$year and DayStatus='PR' ");
                $no_of_shifts = $shift_count[0]->shifts;
                $no_of_days = 0;
                //wada karapu dawas gana
                $day_count = $this->Db_model->getfilteredData("SELECT SUM(CASE WHEN Day_Type = 2 THEN 1 ELSE Day_Type END) AS days_of_worked FROM tbl_individual_roster WHERE EmpNo = '$EmpNo' AND EXTRACT(MONTH FROM FDate) = $month AND EXTRACT(YEAR FROM FDate) = $year AND DayStatus = 'PR';");
                if (!empty($day_count[0]->days_of_worked)) {
                    $no_of_days = $day_count[0]->days_of_worked;
                }


                //wadipura wada karapu shifts tika
                $extra_shifts = ($no_of_shifts - $no_of_days);
                if ($extra_shifts < 0 || $extra_shifts == 0) {
                    $extra_shifts = 0;
                }

                //maseta ot paya 25 karalada balanawa
                $N_OT = 0;
                $Overtime = $this->Db_model->getfilteredData("select sum(AfterExH) as N_OT from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and  EXTRACT(YEAR FROM FDate) =$year");
                if(!empty($Overtime[0]->N_OT)){
                    $N_OT = $Overtime[0]->N_OT;
                }
                $N_OT_Hours = round($N_OT / 60, 2);

                //paya 25 ta wadiya karalanan dawasaka paiyata rs.50 ekathu karanw
                if ($N_OT_Hours == 25 || $N_OT_Hours > 25) {
                    $daily_rate += 50;
                }

                //basic salary eka hadanawa
                $BasicSal = $no_of_days * $daily_rate;

                //extra shift amount
                $extra_shift_amount = $extra_shifts * $daily_rate;

                //**** Get Allowance Details
                $welfair = $this->Db_model->getfilteredData("select welfair_id, Amount from tbl_variable_welfair where EmpNo=$EmpNo and Month=$month and Year=$year");

                //**** Get Allowance Details
                $Allowances = $this->Db_model->getfilteredData("select Alw_ID, Amount from tbl_varialble_allowance where EmpNo=$EmpNo and Month=$month and Year=$year ORDER BY tbl_varialble_allowance.Alw_ID");
                $FixedAllowances = $this->Db_model->getfilteredData("select Alw_ID, Amount from tbl_fixed_allowance where EmpNo=$EmpNo ORDER BY tbl_fixed_allowance.Alw_ID");
                $Att_Allowance_I = 0;
                $Budget_allowance = 0;
                $Budget_allowance_for_otrate = 0;
                $Incentive_allowance = 0;
                $Risk_allowance_I = 0;
                $Colombo = 0;
                $Att_Allowance_II = 0;
                $Risk_allowance_II = 0;
                $Allowance_1 = 0;
                $Allowance_2 = 0;
                $Allowance_3 = 0;
                $Allowance_4 = 0;
                $welfair_1 = 0;

                $welfair_1 = 0;
                $welfair_1Fix = 0;
                if (!empty($welfair)) {
                    $welfair_1 = $welfair[0]->Amount;
                }

                /*
                 * Allowence special types
                 */

                $allowanceIndices = range(0, 8); // Indices from 0 to 8

                foreach ($allowanceIndices as $index) {
                    if (
                        isset($Allowances[$index], $FixedAllowances[$index]) &&
                        (($Allowances[$index]->Alw_ID == 1 && $FixedAllowances[$index]->Alw_ID == 1) ||
                            ($Allowances[$index]->Alw_ID == 2 && $FixedAllowances[$index]->Alw_ID == 2) ||
                            ($Allowances[$index]->Alw_ID == 3 && $FixedAllowances[$index]->Alw_ID == 3) ||
                            ($Allowances[$index]->Alw_ID == 4 && $FixedAllowances[$index]->Alw_ID == 4) ||
                            ($Allowances[$index]->Alw_ID == 5 && $FixedAllowances[$index]->Alw_ID == 5) ||
                            ($Allowances[$index]->Alw_ID == 6 && $FixedAllowances[$index]->Alw_ID == 6) ||
                            ($Allowances[$index]->Alw_ID == 7 && $FixedAllowances[$index]->Alw_ID == 7) ||
                            ($Allowances[$index]->Alw_ID == 8 && $FixedAllowances[$index]->Alw_ID == 8))
                    ) {
                        switch ($Allowances[$index]->Alw_ID) {
                            case 1:
                                $Budget_allowance = $Allowances[$index]->Amount;
                                $Budget_allowance_for_otrate = $Allowances[$index]->Amount;
                                break;
                            case 2:
                                $Incentive_allowance = $Allowances[$index]->Amount;
                                break;
                            case 3:
                                $Risk_allowance_I = $Allowances[$index]->Amount;
                                break;
                            case 4:
                                $Colombo = $Allowances[$index]->Amount;
                                break;
                            case 5:
                                $Allowance_1 = $Allowances[$index]->Amount;
                                break;
                            case 6:
                                $Allowance_2 = $Allowances[$index]->Amount;
                                break;
                            case 7:
                                $Allowance_3 = $Allowances[$index]->Amount;
                                break;
                            case 8:
                                $Allowance_4 = $Allowances[$index]->Amount;
                                break;
                        }
                    } else {
                        if (!empty($Allowances[$index])) {
                            switch ($Allowances[$index]->Alw_ID) {
                                case 1:
                                    $Budget_allowance = $Allowances[$index]->Amount;
                                    $Budget_allowance_for_otrate = $Allowances[$index]->Amount;
                                    break;
                                case 2:
                                    $Incentive_allowance = $Allowances[$index]->Amount;
                                    break;
                                case 3:
                                    $Risk_allowance_I = $Allowances[$index]->Amount;
                                    break;
                                case 4:
                                    $Colombo = $Allowances[$index]->Amount;
                                    break;
                                case 5:
                                    $Allowance_1 = $Allowances[$index]->Amount;
                                    break;
                                case 6:
                                    $Allowance_2 = $Allowances[$index]->Amount;
                                    break;
                                case 7:
                                    $Allowance_3 = $Allowances[$index]->Amount;
                                    break;
                                case 8:
                                    $Allowance_4 = $Allowances[$index]->Amount;
                                    break;
                            }
                        }
                        if (!empty($FixedAllowances[$index])) {
                            switch ($FixedAllowances[$index]->Alw_ID) {
                                case 1:
                                    $Budget_allowance = $FixedAllowances[$index]->Amount;
                                    $Budget_allowance_for_otrate = $FixedAllowances[$index]->Amount;
                                    break;
                                case 2:
                                    $Incentive_allowance = $FixedAllowances[$index]->Amount;
                                    break;
                                case 3:
                                    $Risk_allowance_I = $FixedAllowances[$index]->Amount;
                                    break;
                                case 4:
                                    $Colombo = $FixedAllowances[$index]->Amount;
                                    break;
                                case 5:
                                    $Allowance_1 = $FixedAllowances[$index]->Amount;
                                    break;
                                case 6:
                                    $Allowance_2 = $FixedAllowances[$index]->Amount;
                                    break;
                                case 7:
                                    $Allowance_3 = $FixedAllowances[$index]->Amount;
                                    break;
                                case 8:
                                    $Allowance_4 = $FixedAllowances[$index]->Amount;
                                    break;
                            }
                        }
                    }
                }

                //attendance allowence eka  masa 6kata wadi ho nathi seen eka
                $startDate = new DateTime($service_time);
                $endDate = new DateTime($check_servicedate);
                $interval_service_time = $startDate->diff($endDate);
                if ($interval_service_time->y == 0 && $interval_service_time->m < 6) {
                    if ($no_of_days >= 25) {
                        $Att_Allowance_I = 7500;
                    } else if ($no_of_days >= 22 && $no_of_days < 25) {
                        $Att_Allowance_I = 5000;
                    }
                } else {
                    if ($no_of_days >= 25) {
                        $Att_Allowance_I = 12500;
                    } else if ($no_of_days >= 22 && $no_of_days < 25) {
                        $Att_Allowance_I = 5000;
                    }
                }

                //awadanam deemanawa dawas 20 wadi seen eka
                if ($no_of_days >= 20) {
                    $Risk_allowance_II = 3000;
                }

                //ayawaya deemanawa daws ganen wadi karanna onene
                $Budget_allowance = ($Budget_allowance * $no_of_days);

                ////////////////////////////////////attendance allowance II///////////////////////
                $check_time = '';
                $att_day_price = 0;
                $att_pay_full_price = 0;

                $min_in_time_range_1 = $this->Db_model->getfilteredData("SELECT tbl_att_allowance.Ftime,tbl_att_allowance.Price,tbl_att_allowance.Ttime FROM tbl_att_allowance WHERE 
                tbl_att_allowance.Ftime = (SELECT MIN(Ftime) FROM tbl_att_allowance) ORDER BY tbl_att_allowance.Ttime ASC");
                if (!empty($min_in_time_range_1[0]->Ftime)) {
                    $check_time = $min_in_time_range_1[0]->Ftime;
                }
                $intime_and_outtime = $this->Db_model->getfilteredData("select tbl_individual_roster.InTime,tbl_individual_roster.OutTime,tbl_individual_roster.FDate,tbl_individual_roster.EmpNo from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate) =$year and DayStatus='PR' ");
                $att_day_price = 0;
                $att_pay_full_price = 0;
                foreach ($intime_and_outtime as $inout) {
                    $att_day_price = 0;
                    if (!empty($check_time) && ($inout->InTime <= $check_time)) {
                        //out check
                        $check_time_loop = count($min_in_time_range_1);
                        for ($i = 0; $i < $check_time_loop; $i++) {
                            if (!empty($min_in_time_range_1[$i]->Ttime)) {
                                if ($inout->OutTime >= $min_in_time_range_1[$i]->Ttime) {
                                    $att_day_price = $min_in_time_range_1[$i]->Price;
                                    // echo "check time =" . $check_time;
                                    // echo "<br/>";
                                    // echo $inout->FDate;
                                    // echo "<br/>";
                                    // echo $check_time;
                                    // echo "<br/>";
                                    // echo $min_in_time_range_1[$i]->Ttime;
                                    // echo "<br/>";
                                    // echo $att_day_price;
                                    // echo "<br/>";
                                    // echo "//////////////////////////////";
                                }
                            }
                        }
                    } else {
                        $min_in_time_range_2 = $this->Db_model->getfilteredData("SELECT tbl_att_allowance.Ftime,tbl_att_allowance.Price,tbl_att_allowance.Ttime FROM tbl_att_allowance WHERE 
                        tbl_att_allowance.Ftime = 
					    (SELECT MIN(Ftime) FROM tbl_att_allowance WHERE tbl_att_allowance.Ftime != '$check_time' AND tbl_att_allowance.Ftime > '$check_time') ORDER BY tbl_att_allowance.Ttime ASC");
                        $check_time_2 = $min_in_time_range_2[0]->Ftime;

                        if (!empty($check_time_2) && ($inout->InTime <= $check_time_2)) {
                            //out check
                            $check_time_loop_2 = count($min_in_time_range_2);
                            for ($i = 0; $i < $check_time_loop_2; $i++) {
                                if (!empty($min_in_time_range_2[$i]->Ttime)) {
                                    if ($inout->OutTime >= $min_in_time_range_2[$i]->Ttime) {
                                        $att_day_price = $min_in_time_range_2[$i]->Price;
                                        // echo "check time =" . $check_time_2;
                                        // echo "<br/>";
                                        // echo $inout->FDate;
                                        // echo "<br/>";
                                        // echo $check_time_2;
                                        // echo "<br/>";
                                        // echo $min_in_time_range_2[$i]->Ttime;
                                        // echo "<br/>";
                                        // echo $att_day_price;
                                        // echo "<br/>";
                                        // echo "//////////////////////////////";
                                    }
                                }
                            }
                        } else {
                            $att_day_price = 0;
                        }
                    }
                    // echo $att_day_price;
                    // echo "<br/>";
                    $Att_Allowance_II += $att_day_price;
                    // echo $att_pay_full_price;
                    // echo "<br/>";
                    // echo "//////////////////////////////";
                }
                ////////////////////////////////////attendance allowance II///////////////////////

                //attendance bonus eka hadanawa dawas ganata hariyanna
                $attendance_bonus_days = 0;
                $att_rate_for_ot = $Att_Allowance_I;
                if ($no_of_days > 25) {
                    $attendance_bonus_days = 0;
                    $floar_dates = floor($no_of_days);
                    $attendace_bonus_amount = $this->Db_model->getfilteredData("SELECT tbl_att_bonus.Price FROM tbl_att_bonus WHERE tbl_att_bonus.Day_number = '$floar_dates'");
                    if (!empty($attendace_bonus_amount[0]->Price)) {
                        $attendance_bonus_days = $attendace_bonus_amount[0]->Price;
                    }
                    $Att_Allowance_I += $attendance_bonus_days;
                }
                //ot rate create
                $Ot_Rate = 0;
                $Ot_Amount = 0;
                if ($no_of_days >= 25) {
                    $Ot_Rate = (($daily_rate + $Budget_allowance_for_otrate + ($att_rate_for_ot / 25)) / 8);
                } else if ($no_of_days < 25) {
                    $Ot_Rate = (($daily_rate + ($Budget_allowance_for_otrate / 25) + ($att_rate_for_ot / 25)) / 8);
                }
                $Ot_Amount = round(($N_OT_Hours * $Ot_Rate));

                //**** Get deduction Details
                $Deductions = $this->Db_model->getfilteredData("select Ded_ID,Amount from tbl_variable_deduction where EmpNo=$EmpNo and Month=$month and Year=$year");
                $FixedDeductions = $this->Db_model->getfilteredData("select Deduction_ID,Amount from tbl_fixed_deduction where EmpNo=$EmpNo ORDER BY tbl_fixed_deduction.Deduction_ID");

                $payee = $this->Db_model->getfilteredData("SELECT * FROM tbl_payee");
                $stamp_Duty = $this->Db_model->getfilteredData("select ID, Amount from tbl_variable_stamp where EmpNo=$EmpNo and Month=$month and Year=$year");
                $fixed_stamp_Duty = $this->Db_model->getfilteredData("select ID, Amount from tbl_variable_stamp where EmpNo=$EmpNo and Month='0' ");

                //**** Get salary advance
                $Sal_Advance = $this->Db_model->getfilteredData("select Amount from tbl_salary_advance where Is_Approve=1 and EmpNo=$EmpNo and month=$month and year = $year");


                $Bank_Accounts = 0;
                $Foods = 0;
                $Past_deficit = 0;
                $Detentions = 0;
                $Bonus = 0;
                $Deduction_1 = 0;

                $stamp_Duty1 = 0;
                if (!empty($stamp_Duty)) {
                    $stamp_Duty1 = $stamp_Duty[0]->Amount;
                } else if (empty($stamp_Duty) && !empty($fixed_stamp_Duty)) {
                    $stamp_Duty1 = $fixed_stamp_Duty[0]->Amount;
                }

                $deductionsIndices = range(0, 5); // Indices from 0 to 8
                // $Deductions[0]->Ded_ID
                foreach ($deductionsIndices as $index) {
                    if (
                        isset($Deductions[$index], $FixedDeductions[$index]) &&
                        (($Deductions[$index]->Ded_ID == 1 && $FixedDeductions[$index]->Deduction_ID == 1) ||
                            ($Deductions[$index]->Ded_ID == 2 && $FixedDeductions[$index]->Deduction_ID == 2) ||
                            ($Deductions[$index]->Ded_ID == 3 && $FixedDeductions[$index]->Deduction_ID == 3) ||
                            ($Deductions[$index]->Ded_ID == 4 && $FixedDeductions[$index]->Deduction_ID == 4) ||
                            ($Deductions[$index]->Ded_ID == 5 && $FixedDeductions[$index]->Deduction_ID == 5) ||
                            ($Deductions[$index]->Ded_ID == 6 && $FixedDeductions[$index]->Deduction_ID == 6))
                    ) {
                        switch ($Deductions[$index]->Ded_ID) {
                            case 1:
                                $Bank_Accounts = $Deductions[$index]->Amount;
                                break;
                            case 2:
                                $Foods = $Deductions[$index]->Amount;
                                break;
                            case 3:
                                $Past_deficit = $Deductions[$index]->Amount;
                                break;
                            case 4:
                                $Detentions = $Deductions[$index]->Amount;
                                break;
                            case 5:
                                $Bonus = $Deductions[$index]->Amount;
                                break;
                            case 6:
                                $Deduction_1 = $Deductions[$index]->Amount;
                                break;
                        }
                    } else {
                        if (!empty($Deductions[$index])) {
                            switch ($Deductions[$index]->Ded_ID) {
                                case 1:
                                    $Bank_Accounts = $Deductions[$index]->Amount;
                                    break;
                                case 2:
                                    $Foods = $Deductions[$index]->Amount;
                                    break;
                                case 3:
                                    $Past_deficit = $Deductions[$index]->Amount;
                                    break;
                                case 4:
                                    $Detentions = $Deductions[$index]->Amount;
                                    break;
                                case 5:
                                    $Bonus = $Deductions[$index]->Amount;
                                    break;
                                case 6:
                                    $Deduction_1 = $Deductions[$index]->Amount;
                                    break;
                            }
                        }
                        if (!empty($FixedDeductions[$index])) {
                            switch ($FixedDeductions[$index]->Deduction_ID) {
                                case 1:
                                    $Bank_Accounts = $FixedDeductions[$index]->Amount;
                                    break;
                                case 2:
                                    $Foods = $FixedDeductions[$index]->Amount;
                                    break;
                                case 3:
                                    $Past_deficit = $FixedDeductions[$index]->Amount;
                                    break;
                                case 4:
                                    $Detentions = $FixedDeductions[$index]->Amount;
                                    break;
                                case 5:
                                    $Bonus = $FixedDeductions[$index]->Amount;
                                    break;
                                case 6:
                                    $Deduction_1 = $FixedDeductions[$index]->Amount;
                                    break;
                            }
                        }
                    }
                }

                $Fest_Advance = $this->Db_model->getfilteredData("SELECT * from tbl_festival_advance where EmpNo=$EmpNo and Month=$month and Year = $year");
                $Festivel_Advance_I = 0;
                $Festivel_Advance_II = 0;
                foreach ($Fest_Advance as $Fest_Advance_data) {
                    if ($Fest_Advance_data->Fest_ID == 1) {
                        $Festivel_Advance_I = $Fest_Advance_data->Month_Installment;
                        $Festival_id = $Fest_Advance_data->Fest_ID;
                        $FestMonth = $Fest_Advance_data->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_festival_trans where EmpNo=$EmpNo and month=$month and year=$year AND Festival_ID = '$Festival_id' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $Fest_Advance_data->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $FestMonth;
                            $Full_Amount = $Fest_Advance_data->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $FestMonth,
                                'Festival_ID' => $Festival_id,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_festival_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Fest_ID" => $Festival_id);
                            $result = $this->Db_model->updateData("tbl_festival_advance", $data_loan, $whereArray_loan);
                        }
                    } else if ($Fest_Advance_data->Fest_ID == 2) {
                        $Festivel_Advance_I = $Fest_Advance_data->Month_Installment;
                        $Festival_id = $Fest_Advance_data->Fest_ID;
                        $FestMonth = $Fest_Advance_data->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_festival_trans where EmpNo=$EmpNo and month=$month and year=$year AND Festival_ID = '$Festival_id' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $Fest_Advance_data->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $FestMonth;
                            $Full_Amount = $Fest_Advance_data->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $FestMonth,
                                'Festival_ID' => $Festival_id,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_festival_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Fest_ID" => $Festival_id);
                            $result = $this->Db_model->updateData("tbl_festival_advance", $data_loan, $whereArray_loan);
                        }
                    }
                }

                /*
                 * Loan Details
                 */
                //**** Get loan Details
                $Loan = $this->Db_model->getfilteredData("select Loan_ID,Loan_amount,Month_Installment,FullAmount,Paid_Amount from tbl_loans where Is_Settled=0 and EmpNo=$EmpNo");
                $Loan_Instalment_I = 0;
                $Loan_Instalment_II = 0;
                $Loan_Instalment_III = 0;
                $Loan_Instalment_IV = 0;
                $Loan_Instalment_V = 0;

                foreach ($Loan as $LoanData) {
                    if ($LoanData->Loan_ID == 1) {
                        $Loan_Instalment_I = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    } else if ($LoanData->Loan_ID == 2) {
                        $Loan_Instalment_II = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    } else if ($LoanData->Loan_ID == 3) {
                        $Loan_Instalment_III = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    } else if ($LoanData->Loan_ID == 4) {
                        $Loan_Instalment_IV = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    } else if ($LoanData->Loan_ID == 5) {
                        $Loan_Instalment_V = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;

                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    }
                }
                /*
                 * Loan Details
                 */

                /*
                 * Salary Advance Details
                 */

                if (empty($Sal_Advance[0]->Amount)) {
                    $Sal_advance = 0;
                } else {
                    $Sal_advance = $Sal_Advance[0]->Amount;
                }

                /*
                 * Budget relevances
                 */
                if (empty($br1)) {
                    $budgetrelevance1 = 0;
                } else {
                    $budgetrelevance1 = $br1;
                }

                if (empty($br2)) {
                    $budgetrelevance2 = 0;
                } else {
                    $budgetrelevance2 = $br2;
                }

                /*
                 * Allowance Details
                 */
                if (empty($Allowances[6]->Alw_ID)) {
                    $Allowance_ID_1 = 0;
                } else {
                    $Allowance_ID_1 = $Allowances[0]->Alw_ID;
                }

                if (empty($Allowances[7]->Alw_ID)) {
                    $Allowance_ID_2 = 0;
                } else {
                    $Allowance_ID_2 = $Allowances[1]->Alw_ID;
                }

                if (empty($Allowances[8]->Alw_ID)) {
                    $Allowance_ID_3 = 0;
                } else {
                    $Allowance_ID_3 = $Allowances[2]->Alw_ID;
                }


                if (empty($Allowances[9]->Alw_ID)) {
                    $Allowance_ID_4 = 0;
                } else {
                    $Allowance_ID_4 = $Allowances[3]->Alw_ID;
                }

                if (empty($Allowances[10]->Alw_ID)) {
                    $Allowance_ID_5 = 0;
                } else {
                    $Allowance_ID_5 = $Allowances[4]->Alw_ID;
                }

                /*
                 * Deduction Details
                 */

                if (empty($Deductions[0]->Ded_ID)) {
                    $Deduction_ID_1 = 0;
                } else {
                    $Deduction_ID_1 = $Deductions[0]->Ded_ID;
                }

                if (empty($Deductions[1]->Ded_ID)) {
                    $Deduction_ID_2 = 0;
                } else {
                    $Deduction_ID_2 = $Deductions[1]->Ded_ID;
                }

                if (empty($Deductions[2]->Ded_ID)) {
                    $Deduction_ID_3 = 0;
                } else {
                    $Deduction_ID_3 = $Deductions[2]->Ded_ID;
                }

                //All Allowances
                $Allowances = $Att_Allowance_I + $Att_Allowance_II + $Risk_allowance_I + $attendance_bonus_days + $Risk_allowance_II + $Budget_allowance + $Colombo + $Incentive_allowance + $Allowance_1 + $Allowance_2 + $Allowance_3 + $Allowance_4;

                //Calculate Gross salary
                $Gross_sal = ($BasicSal + $Allowances + $Ot_Amount + $extra_shift_amount);

                /*
                 *payee tax calculate start
                 */
                $gross_for_payee = $Gross_sal;

                $st_gross_Pay = $gross_for_payee * 12;

                $free_rate = 150000;
                $anual_freee_rate = $free_rate * 12;
                $payee_now_amount = 0;

                $calculate_gross_pay = $st_gross_Pay - $anual_freee_rate;

                if (0 > $calculate_gross_pay) {
                    $payee_now_amount = 0;
                } else {
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[0]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[0]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[1]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[1]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[2]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[2]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[3]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[3]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[4]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[4]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        $payeeamount = ($calculate_gross_pay / 12) * ($payee[5]->Tax_rate / 100);
                        $calculate_gross_pay -= 500000;
                        $payee_now_amount += $payeeamount;
                    }
                }
                /*
                 *payee tax calculate end
                 */

                //Calculate EPF Employee
                $Budget_allowance_for_epf = 0;
                $salary_for_epf = 0;
                $startDate_1 = new DateTime($permenent_time);
                $endDate_1 = new DateTime($check_servicedate);

                if ($endDate_1 >= $startDate_1 && $permenent_time != '0000-00-00') {
                    if ($no_of_days >= 25) {
                        $salary_for_epf = ($daily_rate * 25);
                        $Budget_allowance_for_epf = ($Budget_allowance_for_otrate * 25);
                    } else if ($no_of_days < 25) {
                        $salary_for_epf = ($daily_rate * $no_of_days);
                        $Budget_allowance_for_epf = ($Budget_allowance_for_otrate * $no_of_days);
                    }
                }

                $EPF_Worker = (8 / 100) * ($salary_for_epf + $Budget_allowance_for_epf);

                //Total for epf
                $tottal_for_epf = $salary_for_epf + $Budget_allowance_for_epf;

                //Calculate EPF Employer
                $EPF_Employer = (12 / 100) * ($salary_for_epf + $Budget_allowance_for_epf);

                //Calculate ETF Employee
                $ETF = (3 / 100) * ($salary_for_epf + $Budget_allowance_for_epf);

                //Calculate Total Deductions
                $Tot_deductions =  $EPF_Worker + $Sal_advance + $Loan_Instalment_I + $Loan_Instalment_II + $Loan_Instalment_III + $Loan_Instalment_IV + $Loan_Instalment_V + $Bank_Accounts + $Foods + $Past_deficit + $Detentions + $Bonus + $Deduction_1 + $Festivel_Advance_I + $Festivel_Advance_II;

                //Calculate Net Salary
                $netSal = ($Gross_sal) - $Tot_deductions;

                //calculate Gross pay
                $grosspay = $Gross_sal + $Allowances;

                $D_Salary = $grosspay - $Tot_deductions;

                // echo $EmpNo . '-' . $Gross_sal. '-' . $grosspay .'/';
                // echo $EmpNo;
                // echo "<br/>";
                // echo "basic salary-" . $BasicSal;
                // echo "<br/>";
                // echo "ot amount-" . $N_OT_Amount;
                // echo "<br/>";
                // echo "nopay amount-" . $Nopay_Deduction;
                // echo "<br/>";
                // // echo "in time-" . $InTime;
                // // echo "<br/>";
                // // echo "<br/>";
                // // echo "to date-" . $to_date;
                // // echo "<br/>";
                // // echo "to time-" . $to_time;
                // // echo "<br/>";
                // // echo "out date-" . $OutDate;
                // // echo "<br/>";
                // // echo "out time-" . $OutTime;
                // // echo "<br/>";
                // // echo "Late " . $lateM;
                // // echo "<br/>";
                // // echo "ED " . $ED;
                // // echo "<br/>";
                // // echo "DayStatus " . $DayStatus;
                // // echo "<br/>";
                // // echo "OT " . $AfterShiftWH;
                // // echo "<br/>";
                // // echo "dot" . $DOT;
                // // echo "<br/>";
                // // // echo "in 3-" . $InmoTime3;
                // // // echo "<br/>";
                // // // echo "out 3-" . $OutDate3;
                // // // echo "<br/>";
                // // // echo "out 3-" . $OutTime3;
                // // // echo "<br/>";
                // // // echo "workhours1-" . $workhours1;
                // // // echo "<br/>";
                // // // echo "workhours2-" . $workhours2;
                // // // echo "<br/>";
                // // // echo "workhours3-" . $workhours3;
                // // // echo "<br/>";
                // // // echo "workhours3-" . $workhours;
                // // // echo "<br/>";
                // // // echo "dot1-" . $DOT1;
                // // // echo "<br/>";
                // // // echo "dot2-" . $DOT2;
                // // // echo "<br/>";
                // // // echo "dot3-" . $DOT3;
                // // // echo "<br/>";
                // // // echo "dot-" . $DOT;
                // // // echo "<br/>";
                // // // echo "out" . $OutTime;
                // // // echo "<br/>";
                // // // echo "outd-" . $OutDate;
                // echo "<br/>";
                // echo "<br/>";
                // echo "<br/>";
                // echo "<br/>";
                $data = array(
                    'EmpNo' => $EmpNo,
                    'EPFNO' => $EpfNo,
                    'Month' => $month,
                    'Year' => $year,
                    'Basic_sal' => $BasicSal,
                    'Days_worked' => $no_of_days,
                    'Extra_shifts' => $extra_shifts,
                    'Total_F_Epf' => $tottal_for_epf,
                    'Extra_shifts_amount' => $extra_shift_amount,
                    'Dep_ID' => $Dep_ID,
                    'Des_ID' => $Des_ID,
                    'No_Pay_days' => 0,
                    'no_pay_deduction' => 0,
                    'EPF_Worker_Rate' => 8,
                    'EPF_Worker_Amount' => $EPF_Worker,
                    'EPF_Employee_Rate' => 12,
                    'EPF_Employee_Amount' => $EPF_Employer,
                    'ETF_Rate' => 3,
                    'ETF_Amount' => $ETF,
                    'Loan_Instalment_I' => $Loan_Instalment_I,
                    'Loan_Instalment_II' => $Loan_Instalment_II,
                    'Loan_Instalment_III' => $Loan_Instalment_III,
                    'Loan_Instalment_IV' => $Loan_Instalment_IV,
                    'Loan_Instalment_V' => $Loan_Instalment_V,
                    'Salary_advance' => $Sal_advance,
                    // 'Ed_min' => $ed_min,
                    // 'Late_min' => $Late_Min,
                    // 'Ed_deduction' => $ed_amount,
                    // 'Late_deduction' => $Late_Amount,
                    'Alw_ID_1' => 0,
                    'Allowance_1' => 0,
                    'Alw_ID_2' => $Allowance_ID_2,
                    'Alw_ID_3' => $Allowance_ID_3,
                    'Alw_ID_4' => $Allowance_ID_4,
                    'Allowance_4' => 0,
                    'Alw_ID_5' => $Allowance_ID_5,
                    'Allowance_5' => 0,
                    'Budget_allowance' => $Budget_allowance,
                    'Incentive' => $Incentive_allowance,
                    'Attendances_I' => $Att_Allowance_I,
                    'Attendances_II' => $Att_Allowance_II,
                    'Attendance_bonus' => $attendance_bonus_days,
                    'Risk_allowance_I' => $Risk_allowance_I,
                    'Risk_allowance_II' => $Risk_allowance_II,
                    'Colombo' => $Colombo,
                    'Ot_Rate' => $Ot_Rate,
                    'Normal_OT_Hrs' => $N_OT,
                    'Normal_OT_Pay' => $Ot_Amount,
                    'Double_OT_Hrs' => 0,
                    'Double_OT_Pay' => 0,
                    'Bank_Accounts' => $Bank_Accounts,
                    'Foods' => $Foods,
                    'Past_deficit' => $Past_deficit,
                    'Detentions' => $Detentions,
                    'Bonus' => $Bonus,
                    'Deduction_1' => $Deduction_1,
                    'Wellfare' => $welfair_1,
                    'Festivel_Advance_I' => $Festivel_Advance_I,
                    'Festivel_Advance_II' => $Festivel_Advance_II,
                    'Payee_amount' => $payee_now_amount,
                    'Stamp_duty' => $stamp_Duty1,
                    'tot_deduction' => $Tot_deductions,
                    'Gross_pay' => $grosspay,
                    'Gross_sal' => $Gross_sal,
                    'D_Salary' => $D_Salary,
                    'Net_salary' => $netSal,
                    'Approved' => 1,
                    'Edited' => 0
                );


                //***** Update Salary Table
                $whereArray = array("EmpNo" => $EmpNo, 'Month' => $month, 'Year' => $year);
                $result = $this->Db_model->updateData("tbl_salary", $data, $whereArray);

                //*******Else Salary records haven't in Salary table insert salary records into salary table
            } else if($HasRow[0]->HasRow == 0 && $HasRow[0]->Edited == 0){
                $SalData = $this->Db_model->getfilteredData("select EmpNo,EPFNO,Is_EPF, Dep_ID, Des_ID,Basic_Salary,Fixed_Allowance,is_nopay_calc,ApointDate,Permanent_Date from tbl_empmaster where EmpNo=$EmpNo");
                //setting wala daily rate yanawanan
                $daily_rate = $SalData[0]->Basic_Salary;
                $Fixed_Allowance = $SalData[0]->Fixed_Allowance;
                $Is_EPF = $SalData[0]->Is_EPF;
                $is_no_pay = $SalData[0]->is_nopay_calc;
                $service_time = $SalData[0]->ApointDate;
                $permenent_time = $SalData[0]->Permanent_Date;

                //shifts tika serama
                $shift_count = $this->Db_model->getfilteredData("select sum(Day_Type) as shifts from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate) =$year and DayStatus='PR' ");
                $no_of_shifts = $shift_count[0]->shifts;
                $no_of_days = 0;
                //wada karapu dawas gana
                $day_count = $this->Db_model->getfilteredData("SELECT SUM(CASE WHEN Day_Type = 2 THEN 1 ELSE Day_Type END) AS days_of_worked FROM tbl_individual_roster WHERE EmpNo = '$EmpNo' AND EXTRACT(MONTH FROM FDate) = $month AND EXTRACT(YEAR FROM FDate) = $year AND DayStatus = 'PR';");
                if (!empty($day_count[0]->days_of_worked)) {
                    $no_of_days = $day_count[0]->days_of_worked;
                }


                //wadipura wada karapu shifts tika
                $extra_shifts = ($no_of_shifts - $no_of_days);
                if ($extra_shifts < 0 || $extra_shifts == 0) {
                    $extra_shifts = 0;
                }

                //maseta ot paya 25 karalada balanawa
                $N_OT = 0;
                $Overtime = $this->Db_model->getfilteredData("select sum(AfterExH) as N_OT from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and  EXTRACT(YEAR FROM FDate) =$year");
                if(!empty($Overtime[0]->N_OT)){
                    $N_OT = $Overtime[0]->N_OT;
                }
                
                $N_OT_Hours = round($N_OT / 60, 2);

                //paya 25 ta wadiya karalanan dawasaka paiyata rs.50 ekathu karanw
                if ($N_OT_Hours == 25 || $N_OT_Hours > 25) {
                    $daily_rate += 50;
                }

                //basic salary eka hadanawa
                $BasicSal = $no_of_days * $daily_rate;

                //extra shift amount
                $extra_shift_amount = $extra_shifts * $daily_rate;

                //**** Get Allowance Details
                $welfair = $this->Db_model->getfilteredData("select welfair_id, Amount from tbl_variable_welfair where EmpNo=$EmpNo and Month=$month and Year=$year");

                //**** Get Allowance Details
                $Allowances = $this->Db_model->getfilteredData("select Alw_ID, Amount from tbl_varialble_allowance where EmpNo=$EmpNo and Month=$month and Year=$year ORDER BY tbl_varialble_allowance.Alw_ID");
                $FixedAllowances = $this->Db_model->getfilteredData("select Alw_ID, Amount from tbl_fixed_allowance where EmpNo=$EmpNo ORDER BY tbl_fixed_allowance.Alw_ID");
                $Att_Allowance_I = 0;
                $Budget_allowance = 0;
                $Budget_allowance_for_otrate = 0;
                $Incentive_allowance = 0;
                $Risk_allowance_I = 0;
                $Colombo = 0;
                $Att_Allowance_II = 0;
                $Risk_allowance_II = 0;
                $Allowance_1 = 0;
                $Allowance_2 = 0;
                $Allowance_3 = 0;
                $Allowance_4 = 0;
                $welfair_1 = 0;

                $welfair_1 = 0;
                $welfair_1Fix = 0;
                if (!empty($welfair)) {
                    $welfair_1 = $welfair[0]->Amount;
                }

                /*
                 * Allowence special types
                 */

                $allowanceIndices = range(0, 8); // Indices from 0 to 8

                foreach ($allowanceIndices as $index) {
                    if (
                        isset($Allowances[$index], $FixedAllowances[$index]) &&
                        (($Allowances[$index]->Alw_ID == 1 && $FixedAllowances[$index]->Alw_ID == 1) ||
                            ($Allowances[$index]->Alw_ID == 2 && $FixedAllowances[$index]->Alw_ID == 2) ||
                            ($Allowances[$index]->Alw_ID == 3 && $FixedAllowances[$index]->Alw_ID == 3) ||
                            ($Allowances[$index]->Alw_ID == 4 && $FixedAllowances[$index]->Alw_ID == 4) ||
                            ($Allowances[$index]->Alw_ID == 5 && $FixedAllowances[$index]->Alw_ID == 5) ||
                            ($Allowances[$index]->Alw_ID == 6 && $FixedAllowances[$index]->Alw_ID == 6) ||
                            ($Allowances[$index]->Alw_ID == 7 && $FixedAllowances[$index]->Alw_ID == 7) ||
                            ($Allowances[$index]->Alw_ID == 8 && $FixedAllowances[$index]->Alw_ID == 8))
                    ) {
                        switch ($Allowances[$index]->Alw_ID) {
                            case 1:
                                $Budget_allowance = $Allowances[$index]->Amount;
                                $Budget_allowance_for_otrate = $Allowances[$index]->Amount;
                                break;
                            case 2:
                                $Incentive_allowance = $Allowances[$index]->Amount;
                                break;
                            case 3:
                                $Risk_allowance_I = $Allowances[$index]->Amount;
                                break;
                            case 4:
                                $Colombo = $Allowances[$index]->Amount;
                                break;
                            case 5:
                                $Allowance_1 = $Allowances[$index]->Amount;
                                break;
                            case 6:
                                $Allowance_2 = $Allowances[$index]->Amount;
                                break;
                            case 7:
                                $Allowance_3 = $Allowances[$index]->Amount;
                                break;
                            case 8:
                                $Allowance_4 = $Allowances[$index]->Amount;
                                break;
                        }
                    } else {
                        if (!empty($Allowances[$index])) {
                            switch ($Allowances[$index]->Alw_ID) {
                                case 1:
                                    $Budget_allowance = $Allowances[$index]->Amount;
                                    $Budget_allowance_for_otrate = $Allowances[$index]->Amount;
                                    break;
                                case 2:
                                    $Incentive_allowance = $Allowances[$index]->Amount;
                                    break;
                                case 3:
                                    $Risk_allowance_I = $Allowances[$index]->Amount;
                                    break;
                                case 4:
                                    $Colombo = $Allowances[$index]->Amount;
                                    break;
                                case 5:
                                    $Allowance_1 = $Allowances[$index]->Amount;
                                    break;
                                case 6:
                                    $Allowance_2 = $Allowances[$index]->Amount;
                                    break;
                                case 7:
                                    $Allowance_3 = $Allowances[$index]->Amount;
                                    break;
                                case 8:
                                    $Allowance_4 = $Allowances[$index]->Amount;
                                    break;
                            }
                        }
                        if (!empty($FixedAllowances[$index])) {
                            switch ($FixedAllowances[$index]->Alw_ID) {
                                case 1:
                                    $Budget_allowance = $FixedAllowances[$index]->Amount;
                                    $Budget_allowance_for_otrate = $FixedAllowances[$index]->Amount;
                                    break;
                                case 2:
                                    $Incentive_allowance = $FixedAllowances[$index]->Amount;
                                    break;
                                case 3:
                                    $Risk_allowance_I = $FixedAllowances[$index]->Amount;
                                    break;
                                case 4:
                                    $Colombo = $FixedAllowances[$index]->Amount;
                                    break;
                                case 5:
                                    $Allowance_1 = $FixedAllowances[$index]->Amount;
                                    break;
                                case 6:
                                    $Allowance_2 = $FixedAllowances[$index]->Amount;
                                    break;
                                case 7:
                                    $Allowance_3 = $FixedAllowances[$index]->Amount;
                                    break;
                                case 8:
                                    $Allowance_4 = $FixedAllowances[$index]->Amount;
                                    break;
                            }
                        }
                    }
                }

                //attendance allowence eka  masa 6kata wadi ho nathi seen eka
                $startDate = new DateTime($service_time);
                $endDate = new DateTime($check_servicedate);
                $interval_service_time = $startDate->diff($endDate);
                if ($interval_service_time->y == 0 && $interval_service_time->m < 6) {
                    if ($no_of_days >= 25) {
                        $Att_Allowance_I = 7500;
                    } else if ($no_of_days >= 22 && $no_of_days < 25) {
                        $Att_Allowance_I = 5000;
                    }
                } else {
                    if ($no_of_days >= 25) {
                        $Att_Allowance_I = 12500;
                    } else if ($no_of_days >= 22 && $no_of_days < 25) {
                        $Att_Allowance_I = 5000;
                    }
                }

                //awadanam deemanawa dawas 20 wadi seen eka
                if ($no_of_days >= 20) {
                    $Risk_allowance_II = 3000;
                }

                //ayawaya deemanawa daws ganen wadi karanna onene
                $Budget_allowance = ($Budget_allowance * $no_of_days);

                ////////////////////////////////////attendance allowance II///////////////////////
                $check_time = '';
                $att_day_price = 0;
                $att_pay_full_price = 0;

                $min_in_time_range_1 = $this->Db_model->getfilteredData("SELECT tbl_att_allowance.Ftime,tbl_att_allowance.Price,tbl_att_allowance.Ttime FROM tbl_att_allowance WHERE 
                tbl_att_allowance.Ftime = (SELECT MIN(Ftime) FROM tbl_att_allowance) ORDER BY tbl_att_allowance.Ttime ASC");
                if (!empty($min_in_time_range_1[0]->Ftime)) {
                    $check_time = $min_in_time_range_1[0]->Ftime;
                }
                $intime_and_outtime = $this->Db_model->getfilteredData("select tbl_individual_roster.InTime,tbl_individual_roster.OutTime,tbl_individual_roster.FDate,tbl_individual_roster.EmpNo from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate) =$year and DayStatus='PR' ");
                $att_day_price = 0;
                $att_pay_full_price = 0;
                foreach ($intime_and_outtime as $inout) {
                    $att_day_price = 0;
                    if (!empty($check_time) && ($inout->InTime <= $check_time)) {
                        //out check
                        $check_time_loop = count($min_in_time_range_1);
                        for ($i = 0; $i < $check_time_loop; $i++) {
                            if (!empty($min_in_time_range_1[$i]->Ttime)) {
                                if ($inout->OutTime >= $min_in_time_range_1[$i]->Ttime) {
                                    $att_day_price = $min_in_time_range_1[$i]->Price;
                                    // echo "check time =" . $check_time;
                                    // echo "<br/>";
                                    // echo $inout->FDate;
                                    // echo "<br/>";
                                    // echo $check_time;
                                    // echo "<br/>";
                                    // echo $min_in_time_range_1[$i]->Ttime;
                                    // echo "<br/>";
                                    // echo $att_day_price;
                                    // echo "<br/>";
                                    // echo "//////////////////////////////";
                                }
                            }
                        }
                    } else {
                        $min_in_time_range_2 = $this->Db_model->getfilteredData("SELECT tbl_att_allowance.Ftime,tbl_att_allowance.Price,tbl_att_allowance.Ttime FROM tbl_att_allowance WHERE 
                        tbl_att_allowance.Ftime = 
					    (SELECT MIN(Ftime) FROM tbl_att_allowance WHERE tbl_att_allowance.Ftime != '$check_time' AND tbl_att_allowance.Ftime > '$check_time') ORDER BY tbl_att_allowance.Ttime ASC");
                        $check_time_2 = $min_in_time_range_2[0]->Ftime;

                        if (!empty($check_time_2) && ($inout->InTime <= $check_time_2)) {
                            //out check
                            $check_time_loop_2 = count($min_in_time_range_2);
                            for ($i = 0; $i < $check_time_loop_2; $i++) {
                                if (!empty($min_in_time_range_2[$i]->Ttime)) {
                                    if ($inout->OutTime >= $min_in_time_range_2[$i]->Ttime) {
                                        $att_day_price = $min_in_time_range_2[$i]->Price;
                                        // echo "check time =" . $check_time_2;
                                        // echo "<br/>";
                                        // echo $inout->FDate;
                                        // echo "<br/>";
                                        // echo $check_time_2;
                                        // echo "<br/>";
                                        // echo $min_in_time_range_2[$i]->Ttime;
                                        // echo "<br/>";
                                        // echo $att_day_price;
                                        // echo "<br/>";
                                        // echo "//////////////////////////////";
                                    }
                                }
                            }
                        } else {
                            $att_day_price = 0;
                        }
                    }
                    // echo $att_day_price;
                    // echo "<br/>";
                    $Att_Allowance_II += $att_day_price;
                    // echo $att_pay_full_price;
                    // echo "<br/>";
                    // echo "//////////////////////////////";
                }
                ////////////////////////////////////attendance allowance II///////////////////////

                //attendance bonus eka hadanawa dawas ganata hariyanna
                $attendance_bonus_days = 0;
                $att_rate_for_ot = $Att_Allowance_I;
                if ($no_of_days > 25) {
                    $attendance_bonus_days = 0;
                    $floar_dates = floor($no_of_days);
                    $attendace_bonus_amount = $this->Db_model->getfilteredData("SELECT tbl_att_bonus.Price FROM tbl_att_bonus WHERE tbl_att_bonus.Day_number = '$floar_dates'");
                    if (!empty($attendace_bonus_amount[0]->Price)) {
                        $attendance_bonus_days = $attendace_bonus_amount[0]->Price;
                    }
                    $Att_Allowance_I += $attendance_bonus_days;
                }
                //ot rate create
                $Ot_Rate = 0;
                $Ot_Amount = 0;
                if ($no_of_days >= 25) {
                    $Ot_Rate = (($daily_rate + $Budget_allowance_for_otrate + ($att_rate_for_ot / 25)) / 8);
                } else if ($no_of_days < 25) {
                    $Ot_Rate = (($daily_rate + ($Budget_allowance_for_otrate / 25) + ($att_rate_for_ot / 25)) / 8);
                }
                $Ot_Amount = round(($N_OT_Hours * $Ot_Rate));

                //**** Get deduction Details
                $Deductions = $this->Db_model->getfilteredData("select Ded_ID,Amount from tbl_variable_deduction where EmpNo=$EmpNo and Month=$month and Year=$year");
                $FixedDeductions = $this->Db_model->getfilteredData("select Deduction_ID,Amount from tbl_fixed_deduction where EmpNo=$EmpNo ORDER BY tbl_fixed_deduction.Deduction_ID");

                $payee = $this->Db_model->getfilteredData("SELECT * FROM tbl_payee");
                $stamp_Duty = $this->Db_model->getfilteredData("select ID, Amount from tbl_variable_stamp where EmpNo=$EmpNo and Month=$month and Year=$year");
                $fixed_stamp_Duty = $this->Db_model->getfilteredData("select ID, Amount from tbl_variable_stamp where EmpNo=$EmpNo and Month='0' ");

                //**** Get salary advance
                $Sal_Advance = $this->Db_model->getfilteredData("select Amount from tbl_salary_advance where Is_Approve=1 and EmpNo=$EmpNo and month=$month and year = $year");


                $Bank_Accounts = 0;
                $Foods = 0;
                $Past_deficit = 0;
                $Detentions = 0;
                $Bonus = 0;
                $Deduction_1 = 0;

                $stamp_Duty1 = 0;
                if (!empty($stamp_Duty)) {
                    $stamp_Duty1 = $stamp_Duty[0]->Amount;
                } else if (empty($stamp_Duty) && !empty($fixed_stamp_Duty)) {
                    $stamp_Duty1 = $fixed_stamp_Duty[0]->Amount;
                }

                $deductionsIndices = range(0, 5); // Indices from 0 to 8
                // $Deductions[0]->Ded_ID
                foreach ($deductionsIndices as $index) {
                    if (
                        isset($Deductions[$index], $FixedDeductions[$index]) &&
                        (($Deductions[$index]->Ded_ID == 1 && $FixedDeductions[$index]->Deduction_ID == 1) ||
                            ($Deductions[$index]->Ded_ID == 2 && $FixedDeductions[$index]->Deduction_ID == 2) ||
                            ($Deductions[$index]->Ded_ID == 3 && $FixedDeductions[$index]->Deduction_ID == 3) ||
                            ($Deductions[$index]->Ded_ID == 4 && $FixedDeductions[$index]->Deduction_ID == 4) ||
                            ($Deductions[$index]->Ded_ID == 5 && $FixedDeductions[$index]->Deduction_ID == 5) ||
                            ($Deductions[$index]->Ded_ID == 6 && $FixedDeductions[$index]->Deduction_ID == 6))
                    ) {
                        switch ($Deductions[$index]->Ded_ID) {
                            case 1:
                                $Bank_Accounts = $Deductions[$index]->Amount;
                                break;
                            case 2:
                                $Foods = $Deductions[$index]->Amount;
                                break;
                            case 3:
                                $Past_deficit = $Deductions[$index]->Amount;
                                break;
                            case 4:
                                $Detentions = $Deductions[$index]->Amount;
                                break;
                            case 5:
                                $Bonus = $Deductions[$index]->Amount;
                                break;
                            case 6:
                                $Deduction_1 = $Deductions[$index]->Amount;
                                break;
                        }
                    } else {
                        if (!empty($Deductions[$index])) {
                            switch ($Deductions[$index]->Ded_ID) {
                                case 1:
                                    $Bank_Accounts = $Deductions[$index]->Amount;
                                    break;
                                case 2:
                                    $Foods = $Deductions[$index]->Amount;
                                    break;
                                case 3:
                                    $Past_deficit = $Deductions[$index]->Amount;
                                    break;
                                case 4:
                                    $Detentions = $Deductions[$index]->Amount;
                                    break;
                                case 5:
                                    $Bonus = $Deductions[$index]->Amount;
                                    break;
                                case 6:
                                    $Deduction_1 = $Deductions[$index]->Amount;
                                    break;
                            }
                        }
                        if (!empty($FixedDeductions[$index])) {
                            switch ($FixedDeductions[$index]->Deduction_ID) {
                                case 1:
                                    $Bank_Accounts = $FixedDeductions[$index]->Amount;
                                    break;
                                case 2:
                                    $Foods = $FixedDeductions[$index]->Amount;
                                    break;
                                case 3:
                                    $Past_deficit = $FixedDeductions[$index]->Amount;
                                    break;
                                case 4:
                                    $Detentions = $FixedDeductions[$index]->Amount;
                                    break;
                                case 5:
                                    $Bonus = $FixedDeductions[$index]->Amount;
                                    break;
                                case 6:
                                    $Deduction_1 = $FixedDeductions[$index]->Amount;
                                    break;
                            }
                        }
                    }
                }

                $Fest_Advance = $this->Db_model->getfilteredData("SELECT * from tbl_festival_advance where EmpNo=$EmpNo and Month=$month and Year = $year");
                $Festivel_Advance_I = 0;
                $Festivel_Advance_II = 0;
                foreach ($Fest_Advance as $Fest_Advance_data) {
                    if ($Fest_Advance_data->Fest_ID == 1) {
                        $Festivel_Advance_I = $Fest_Advance_data->Month_Installment;
                        $Festival_id = $Fest_Advance_data->Fest_ID;
                        $FestMonth = $Fest_Advance_data->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_festival_trans where EmpNo=$EmpNo and month=$month and year=$year AND Festival_ID = '$Festival_id' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $Fest_Advance_data->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $FestMonth;
                            $Full_Amount = $Fest_Advance_data->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $FestMonth,
                                'Festival_ID' => $Festival_id,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_festival_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Fest_ID" => $Festival_id);
                            $result = $this->Db_model->updateData("tbl_festival_advance", $data_loan, $whereArray_loan);
                        }
                    } else if ($Fest_Advance_data->Fest_ID == 2) {
                        $Festivel_Advance_I = $Fest_Advance_data->Month_Installment;
                        $Festival_id = $Fest_Advance_data->Fest_ID;
                        $FestMonth = $Fest_Advance_data->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_festival_trans where EmpNo=$EmpNo and month=$month and year=$year AND Festival_ID = '$Festival_id' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $Fest_Advance_data->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $FestMonth;
                            $Full_Amount = $Fest_Advance_data->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $FestMonth,
                                'Festival_ID' => $Festival_id,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_festival_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Fest_ID" => $Festival_id);
                            $result = $this->Db_model->updateData("tbl_festival_advance", $data_loan, $whereArray_loan);
                        }
                    }
                }

                /*
                 * Loan Details
                 */
                //**** Get loan Details
                $Loan = $this->Db_model->getfilteredData("select Loan_ID,Loan_amount,Month_Installment,FullAmount,Paid_Amount from tbl_loans where Is_Settled=0 and EmpNo=$EmpNo");
                $Loan_Instalment_I = 0;
                $Loan_Instalment_II = 0;
                $Loan_Instalment_III = 0;
                $Loan_Instalment_IV = 0;
                $Loan_Instalment_V = 0;

                foreach ($Loan as $LoanData) {
                    if ($LoanData->Loan_ID == 1) {
                        $Loan_Instalment_I = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    } else if ($LoanData->Loan_ID == 2) {
                        $Loan_Instalment_II = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    } else if ($LoanData->Loan_ID == 3) {
                        $Loan_Instalment_III = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    } else if ($LoanData->Loan_ID == 4) {
                        $Loan_Instalment_IV = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;
                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    } else if ($LoanData->Loan_ID == 5) {
                        $Loan_Instalment_V = $LoanData->Month_Installment;
                        $LoanID = $LoanData->Loan_ID;
                        $LoanMonth = $LoanData->Month_Installment;

                        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year AND Loan_ID = '$LoanID' ");
                        if ($HasRow[0]->HasRow) {
                        } else {
                            $PaidAmount = $LoanData->Paid_Amount;

                            $PaidAmount_to = $PaidAmount + $LoanMonth;
                            $Full_Amount = $LoanData->FullAmount;
                            $BalanceAmount = $Full_Amount - $PaidAmount_to;

                            if ($BalanceAmount <= 0) {
                                $Is_Settele = 1;
                            } else {
                                $Is_Settele = 0;
                            }

                            $dataArray = array(
                                'Year' => $year,
                                'EmpNo' => $EmpNo,
                                'Month' => $month,
                                'Amount_month' => $LoanMonth,
                                'Loan_ID' => $LoanID,
                                'Time_Trans' => $timestamp,
                            );

                            $this->Db_model->insertData("tbl_loan_trans", $dataArray);

                            $data_loan = array(
                                'EmpNo' => $EmpNo,
                                'Paid_Amount' => $PaidAmount_to,
                                'Balance_amount' => $BalanceAmount,
                                'Is_Settled' => $Is_Settele,
                            );

                            $whereArray_loan = array("EmpNo" => $EmpNo, "Loan_ID" => $LoanID);
                            $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                        }
                    }
                }
                /*
                 * Loan Details
                 */

                /*
                 * Salary Advance Details
                 */

                if (empty($Sal_Advance[0]->Amount)) {
                    $Sal_advance = 0;
                } else {
                    $Sal_advance = $Sal_Advance[0]->Amount;
                }

                /*
                 * Budget relevances
                 */
                if (empty($br1)) {
                    $budgetrelevance1 = 0;
                } else {
                    $budgetrelevance1 = $br1;
                }

                if (empty($br2)) {
                    $budgetrelevance2 = 0;
                } else {
                    $budgetrelevance2 = $br2;
                }

                /*
                 * Allowance Details
                 */
                if (empty($Allowances[6]->Alw_ID)) {
                    $Allowance_ID_1 = 0;
                } else {
                    $Allowance_ID_1 = $Allowances[0]->Alw_ID;
                }

                if (empty($Allowances[7]->Alw_ID)) {
                    $Allowance_ID_2 = 0;
                } else {
                    $Allowance_ID_2 = $Allowances[1]->Alw_ID;
                }

                if (empty($Allowances[8]->Alw_ID)) {
                    $Allowance_ID_3 = 0;
                } else {
                    $Allowance_ID_3 = $Allowances[2]->Alw_ID;
                }


                if (empty($Allowances[9]->Alw_ID)) {
                    $Allowance_ID_4 = 0;
                } else {
                    $Allowance_ID_4 = $Allowances[3]->Alw_ID;
                }

                if (empty($Allowances[10]->Alw_ID)) {
                    $Allowance_ID_5 = 0;
                } else {
                    $Allowance_ID_5 = $Allowances[4]->Alw_ID;
                }

                /*
                 * Deduction Details
                 */

                if (empty($Deductions[0]->Ded_ID)) {
                    $Deduction_ID_1 = 0;
                } else {
                    $Deduction_ID_1 = $Deductions[0]->Ded_ID;
                }

                if (empty($Deductions[1]->Ded_ID)) {
                    $Deduction_ID_2 = 0;
                } else {
                    $Deduction_ID_2 = $Deductions[1]->Ded_ID;
                }

                if (empty($Deductions[2]->Ded_ID)) {
                    $Deduction_ID_3 = 0;
                } else {
                    $Deduction_ID_3 = $Deductions[2]->Ded_ID;
                }

                //All Allowances
                $Allowances = $Att_Allowance_I + $Att_Allowance_II + $Risk_allowance_I + $attendance_bonus_days + $Risk_allowance_II + $Budget_allowance + $Colombo + $Incentive_allowance + $Allowance_1 + $Allowance_2 + $Allowance_3 + $Allowance_4;

                //Calculate Gross salary
                $Gross_sal = ($BasicSal + $Allowances + $Ot_Amount + $extra_shift_amount);

                /*
                 *payee tax calculate start
                 */
                $gross_for_payee = $Gross_sal;

                $st_gross_Pay = $gross_for_payee * 12;

                $free_rate = 150000;
                $anual_freee_rate = $free_rate * 12;
                $payee_now_amount = 0;

                $calculate_gross_pay = $st_gross_Pay - $anual_freee_rate;

                if (0 > $calculate_gross_pay) {
                    $payee_now_amount = 0;
                } else {
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[0]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[0]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[1]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[1]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[2]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[2]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[3]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[3]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[4]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[4]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        $payeeamount = ($calculate_gross_pay / 12) * ($payee[5]->Tax_rate / 100);
                        $calculate_gross_pay -= 500000;
                        $payee_now_amount += $payeeamount;
                    }
                }
                /*
                 *payee tax calculate end
                 */

                //Calculate EPF Employee
                $Budget_allowance_for_epf = 0;
                $salary_for_epf = 0;
                $startDate_1 = new DateTime($permenent_time);
                $endDate_1 = new DateTime($check_servicedate);

                if ($endDate_1 >= $startDate_1 && $permenent_time != '0000-00-00') {
                    if ($no_of_days >= 25) {
                        $salary_for_epf = ($daily_rate * 25);
                        $Budget_allowance_for_epf = ($Budget_allowance_for_otrate * 25);
                    } else if ($no_of_days < 25) {
                        $salary_for_epf = ($daily_rate * $no_of_days);
                        $Budget_allowance_for_epf = ($Budget_allowance_for_otrate * $no_of_days);
                    }
                }

                $EPF_Worker = (8 / 100) * ($salary_for_epf + $Budget_allowance_for_epf);

                //Total for epf
                $tottal_for_epf = $salary_for_epf + $Budget_allowance_for_epf;

                //Calculate EPF Employer
                $EPF_Employer = (12 / 100) * ($salary_for_epf + $Budget_allowance_for_epf);

                //Calculate ETF Employee
                $ETF = (3 / 100) * ($salary_for_epf + $Budget_allowance_for_epf);

                //Calculate Total Deductions
                $Tot_deductions =  $EPF_Worker + $Sal_advance + $Loan_Instalment_I + $Loan_Instalment_II + $Loan_Instalment_III + $Loan_Instalment_IV + $Loan_Instalment_V + $Bank_Accounts + $Foods + $Past_deficit + $Detentions + $Bonus + $Deduction_1 + $Festivel_Advance_I + $Festivel_Advance_II;

                //Calculate Net Salary
                $netSal = ($Gross_sal) - $Tot_deductions;

                //calculate Gross pay
                $grosspay = $Gross_sal + $Allowances;

                $D_Salary = $grosspay - $Tot_deductions;
                // echo $EmpNo;
                // echo "<br/>";
                // echo "basic salary = " . $BasicSal;
                // echo "<br/>";
                // echo "shift_count = " . $no_of_shifts;
                // echo "<br/>";
                // echo "day_count = " . $no_of_days;
                // echo "<br/>";
                // echo "extra_shifts = " . $extra_shifts;
                // echo "<br/>";
                // echo "extra shift amount = " . $extra_shift_amount;
                // echo "<br/>";
                // echo "N_OT_Hours = " . $N_OT_Hours;
                // echo "<br/>";
                // echo "Att_Allowance_I = " . $Att_Allowance_I;
                // echo "<br/>";
                // echo "Att_Allowance_II = " . $Att_Allowance_II;
                // echo "<br/>";
                // echo "Budget_allowance = " . $Budget_allowance;
                // echo "<br/>";
                // echo "Budget_allowance_for_otrate = " . $Budget_allowance_for_otrate;
                // echo "<br/>";
                // echo "Attendance allowanceII = " . $att_pay_full_price;
                // echo "<br/>";
                // echo "Risk_allowance_I = " . $Risk_allowance_I;
                // echo "<br/>";
                // echo "Risk_allowance_II = " . $Risk_allowance_II;
                // echo "<br/>";
                // echo "Attendance bonus =  " . $attendance_bonus_days;
                // echo "<br/>";
                // echo "Insentive =  " . $Incentive_allowance;
                // echo "<br/>";
                // echo "OT Rate = " . $Ot_Rate;
                // echo "<br/>";
                // echo "OT Amount = " . $Ot_Amount;
                // echo "<br/>";
                // echo "EPF Amount = " . $EPF_Worker;
                // echo "<br/>";
                // echo "Gross Salary = " . $Gross_sal;
                // echo "<br/>";
                // echo "Colombo = " . $Colombo;
                // echo "<br/>";
                // echo "Loan_Instalment_I = " . $Loan_Instalment_I;
                // echo "<br/>";
                // echo "Loan_Instalment_II = " . $Loan_Instalment_II;
                // echo "<br/>";
                // echo "Loan_Instalment_III = " . $Loan_Instalment_III;
                // echo "<br/>";
                // echo "Loan_Instalment_IV = " . $Loan_Instalment_IV;
                // echo "<br/>";
                // echo "Loan_Instalment_V = " . $Loan_Instalment_V;
                // echo "<br/>";
                // echo "Bank_Accounts = " . $Bank_Accounts;
                // echo "<br/>";
                // echo "Foods = " . $Foods;
                // echo "<br/>";
                // echo "Past_deficit = " . $Past_deficit;
                // echo "<br/>";
                // echo "Detentions = " . $Detentions;
                // echo "<br/>";
                // echo "Bonus = " . $Bonus;
                // echo "<br/>";
                // echo "Deduction_1 = " . $Deduction_1;
                // echo "<br/>";
                // echo "Festivel_Advance_I = " . $Festivel_Advance_I;
                // echo "<br/>";
                // echo "Festivel_Advance_II = " . $Festivel_Advance_II;
                // echo "<br/>";
                // echo "Net Salary = " . $netSal;
                // echo "<br/>";
                // echo "<br/>";
                // echo "<br/>";
                // echo "<br/>";
                // echo "<br/>";
                $data = array(
                    array(
                    'EmpNo' => $EmpNo,
                    'EPFNO' => $EpfNo,
                    'Month' => $month,
                    'Year' => $year,
                    'Basic_sal' => $BasicSal,
                    'Days_worked' => $no_of_days,
                    'Extra_shifts' => $extra_shifts,
                    'Total_F_Epf' => $tottal_for_epf,
                    'Extra_shifts_amount' => $extra_shift_amount,
                    'Dep_ID' => $Dep_ID,
                    'Des_ID' => $Des_ID,
                    'No_Pay_days' => 0,
                    'no_pay_deduction' => 0,
                    'EPF_Worker_Rate' => 8,
                    'EPF_Worker_Amount' => $EPF_Worker,
                    'EPF_Employee_Rate' => 12,
                    'EPF_Employee_Amount' => $EPF_Employer,
                    'ETF_Rate' => 3,
                    'ETF_Amount' => $ETF,
                    'Loan_Instalment_I' => $Loan_Instalment_I,
                    'Loan_Instalment_II' => $Loan_Instalment_II,
                    'Loan_Instalment_III' => $Loan_Instalment_III,
                    'Loan_Instalment_IV' => $Loan_Instalment_IV,
                    'Loan_Instalment_V' => $Loan_Instalment_V,
                    'Salary_advance' => $Sal_advance,
                    'Alw_ID_1' => 0,
                    'Allowance_1' => 0,
                    'Alw_ID_2' => $Allowance_ID_2,
                    'Alw_ID_3' => $Allowance_ID_3,
                    'Alw_ID_4' => $Allowance_ID_4,
                    'Allowance_4' => 0,
                    'Alw_ID_5' => $Allowance_ID_5,
                    'Allowance_5' => 0,
                    'Budget_allowance' => $Budget_allowance,
                    'Incentive' => $Incentive_allowance,
                    'Attendances_I' => $Att_Allowance_I,
                    'Attendances_II' => $Att_Allowance_II,
                    'Attendance_bonus' => $attendance_bonus_days,
                    'Risk_allowance_I' => $Risk_allowance_I,
                    'Risk_allowance_II' => $Risk_allowance_II,
                    'Colombo' => $Colombo,
                    'Ot_Rate' => $Ot_Rate,
                    'Normal_OT_Hrs' => $N_OT,
                    'Normal_OT_Pay' => $Ot_Amount,
                    'Double_OT_Hrs' => 0,
                    'Double_OT_Pay' => 0,
                    'Bank_Accounts' => $Bank_Accounts,
                    'Foods' => $Foods,
                    'Past_deficit' => $Past_deficit,
                    'Detentions' => $Detentions,
                    'Bonus' => $Bonus,
                    'Deduction_1' => $Deduction_1,
                    'Wellfare' => $welfair_1,
                    'Festivel_Advance_I' => $Festivel_Advance_I,
                    'Festivel_Advance_II' => $Festivel_Advance_II,
                    'Payee_amount' => $payee_now_amount,
                    'Stamp_duty' => $stamp_Duty1,
                    'tot_deduction' => $Tot_deductions,
                    'Gross_pay' => $grosspay,
                    'Gross_sal' => $Gross_sal,
                    'D_Salary' => $D_Salary,
                    'Net_salary' => $netSal,
                    'Approved' => 1,
                    'Edited' => 0
                    )
                );

                $this->db->insert_batch('tbl_salary', $data);



                $this->session->set_flashdata('success_message', 'Allovance added successfully');
            }
        }

        $this->session->set_flashdata('success_message', 'Payroll Process successfully');
        redirect(base_url() . 'Pay/Payroll_Process');
    }
}
