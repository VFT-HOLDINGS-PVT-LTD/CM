<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_Process_New extends CI_Controller
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
        $this->load->model('Db_model', '', true);
    }

    /*
     * Index page
     */

    public function index()
    {

        $data['title'] = "Attendance Process | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_roster'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');

        $data['sh_employees'] = $this->Db_model->getfilteredData("SELECT
                                                                    tbl_empmaster.EmpNo
                                                                FROM
                                                                    tbl_empmaster
                                                                        LEFT JOIN
                                                                    tbl_individual_roster ON tbl_individual_roster.EmpNo = tbl_empmaster.EmpNo
                                                                    where tbl_individual_roster.EmpNo is null AND tbl_empmaster.status=1 AND tbl_empmaster.EmpNo != '9000' and Active_process=1");

        $this->load->view('Attendance/Attendance_Process/index', $data);
    }

    public function re_process()
    {
        $data['title'] = "Attendance Process | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_roster'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');

        $data['sh_employees'] = $this->Db_model->getfilteredData("SELECT
                                                                    tbl_empmaster.EmpNo
                                                                FROM
                                                                    tbl_empmaster
                                                                        LEFT JOIN
                                                                    tbl_individual_roster ON tbl_individual_roster.EmpNo = tbl_empmaster.EmpNo
                                                                    where tbl_individual_roster.EmpNo is null AND tbl_empmaster.status=1 and Active_process=1");

        $this->load->view('Attendance/Attendance_REProcess/index', $data);
    }

    /*
     * Insert Data
     */



    public function emp_attendance_process()
    {

        $month = $this->input->post('cmb_month');
        if (empty($month)) {
            $this->session->set_flashdata('error_message', 'Please Select Month');
            redirect('/Attendance/Attendance_Process_New');
        } else {
            date_default_timezone_set('Asia/Colombo');
            $year = date("Y");
            $HasRow = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) AS HasRow FROM tbl_individual_roster WHERE EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate)=$year");
            if (!empty($HasRow)) {


                $dtEmp['EmpData'] = $this->Db_model->getfilteredData("select * from tbl_individual_roster where EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate)=$year");

                $AfterShift = 0;

                if (!empty($dtEmp['EmpData'])) {

                    for ($x = 0; $x < count($dtEmp['EmpData']); $x++) {
                        $EmpNo = $dtEmp['EmpData'][$x]->EmpNo;

                        $FromDate = $dtEmp['EmpData'][$x]->FDate;
                        $ToDate = $dtEmp['EmpData'][$x]->TDate;
                        //Check If From date less than to Date
                        if ($FromDate <= $ToDate) {
                            $settings = $this->Db_model->getfilteredData("SELECT tbl_setting.Group_id,tbl_setting.Ot_m,tbl_setting.Ot_e,tbl_setting.Ot_d_Late,
                            tbl_setting.Late,tbl_setting.Ed,tbl_setting.Min_time_t_ot_m,tbl_setting.Min_time_t_ot_e,
                            tbl_setting.late_Grs_prd,tbl_setting.`Round`,tbl_setting.Hd_d_from,tbl_setting.Dot_f_holyday,tbl_setting.Dot_f_offday
                             FROM tbl_setting INNER JOIN tbl_emp_group ON tbl_setting.Group_id = tbl_emp_group.Grp_ID
                             INNER JOIN tbl_empmaster ON tbl_empmaster.Grp_ID = tbl_emp_group.Grp_ID WHERE tbl_empmaster.EmpNo = '$EmpNo'");
                            $ApprovedExH = 0;
                            $DayStatus = "not";
                            $ID_Roster = '';
                            $InDate = '';
                            $InTime = '';
                            $OutDate = '';
                            $OutTime = '';

                            $from_date = '';
                            $from_time = '';
                            $to_date = '';
                            $to_time = '';
                            $Day_Type = 0;
                            $lateM = '';
                            $ED = '';
                            $DayStatus = '';
                            $AfterShiftWH = '';
                            $BeforeShift = '';
                            $DOT = '';
                            $Late_Status = 0;
                            $NetLateM = 0;


                            // **************************************************************************************//
                            // tbl_individual_roster eken shift details tika gannawa
                            $ShiftDetails['shift'] = $this->Db_model->getfilteredData("select `ID_Roster`,`ShType`,`ShiftDay`,`FDate`,`FTime`,`TDate`,`TTime`,`GracePrd`,`HDSession` from tbl_individual_roster where FDate = '$FromDate' AND EmpNo = '$EmpNo' ");
                            $ID_Roster = $ShiftDetails['shift'][0]->ID_Roster;
                            $shift_type = $ShiftDetails['shift'][0]->ShType;
                            $shift_day = $ShiftDetails['shift'][0]->ShiftDay;
                            $from_date = $ShiftDetails['shift'][0]->FDate;
                            $from_time = $ShiftDetails['shift'][0]->FTime;
                            $to_date = $ShiftDetails['shift'][0]->TDate;
                            $to_time = $ShiftDetails['shift'][0]->TTime;
                            $GracePrd = $ShiftDetails['shift'][0]->GracePrd;
                            $cutofftime = $ShiftDetails['shift'][0]->HDSession;


                            //duty dawasaska samnyen yana widiya
                            if ($shift_type == "DU") {
                                $InDate = '';
                                $InTime = '';
                                $OutDate = '';
                                $OutTime = '';


                                $lateM = '';
                                $ED = '';
                                $DayStatus = '';
                                $AfterShiftWH = '';
                                $DOT = '';
                                // Get the CheckIN
                                $dt_in_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from 
                                tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='0' OR AttTime <'14:00:00') ");
                                $InDate = $dt_in_Records['dt_Records'][0]->AttDate;
                                $InTime = $dt_in_Records['dt_Records'][0]->INTime;

                                // Get the CheckOut
                                $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from 
                                tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='1' OR AttTime > '12:00:00') ");
                                $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                                $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;

                                // Out Ekak nethnm check nextday(1st nextDay)
                                if ($FromDate != $to_date) {
                                    // $newDate = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                                    $newDate = $to_date;

                                    // Get the CheckOut in the nextDay (before 9am)
                                    $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OutTime,Enroll_No,AttDate from 
                                    tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='$newDate' AND AttTime <'11:59:00' AND Status='1' "); //update the 9 to 11.59 
                                    // $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OutTime,Enroll_No,AttDate from 
                                    // tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='$newDate' AND AttTime <'09:00:00' "); old-code
                                    $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                                    $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                                }

                                if ($InTime != '' && $OutTime != '') {
                                    $fromtime = $InDate . " " . $InTime;
                                    $totime = $OutDate . " " . $OutTime;
                                    $timestamp1 = strtotime($fromtime);
                                    $timestamp2 = strtotime($totime);
                                    $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    $time_difference_minutes = $time_difference_seconds / 60;
                                    if ($time_difference_minutes < 60) {
                                        $OutDate = '';
                                        $OutTime = '';
                                    }
                                    //ms wela thiyenne out time ekada balanw
                                    $fromtime = $to_date . " " . $to_time;
                                    $deduct_fromtime = strtotime($fromtime . " -3 hour");
                                    $plus_fromtime = strtotime($fromtime . " +3 hour");
                                    $ct = $InDate . " " . $InTime;
                                    $check_time = strtotime($ct);
                                    if ($deduct_fromtime <= $check_time && $check_time <= $plus_fromtime) {
                                        $OutDate = $InDate;
                                        $OutTime = $InTime;
                                        $InDate = '';
                                        $InTime = '';
                                    }
                                }

                                $lateM = 0;
                                $Late_Status = 0;
                                $NetLateM = 0;
                                $ED = 0;
                                $EDF = 0;
                                $Att_Allowance = 1;
                                $Nopay = 0;
                                $AfterShiftWH = 0;
                                $lateM = 0;
                                $iCalcHaffT = 0;
                                $aththam = 0;

                                $icalData = 0;
                                $ot_munites = 0;
                                if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $shift_type == 'DU' && $OutTime != "00:00:00") {

                                    // group eke evening ot thiyenawanan
                                    if ($settings[0]->Ot_e == 1) {
                                        $fromtime = $InDate . " " . $InTime;
                                        $totime = $OutDate . " " . $OutTime;
                                        $timestamp1 = strtotime($fromtime);
                                        $timestamp2 = strtotime($totime);
                                        $time_difference_seconds = ($timestamp2 - $timestamp1);
                                        // echo $time_difference_minutes = ($time_difference_seconds / 60);

                                        if ($shift_day == 'SUN') {
                                             if ($time_difference_minutes > 271 ) {
                                                //ot balanawa paya 9kata wada wada karapuwa
                                                $ot_munites = $time_difference_minutes - 510;
                                                if ($ot_munites < 0) {
                                                    $icalData = $ot_munites;
                                                    $aththam = 1;
                                                } else {
                                                    $icalData = $ot_munites;
                                                    $aththam = 1;
                                                }

                                                //paya 4.5ta adu
                                            } else if ($time_difference_minutes < 271) {
                                                if ($time_difference_minutes == 270 || ($time_difference_minutes > 270 && $time_difference_minutes < 510)) {
                                                    $icalData = 0;
                                                    $aththam = 0.5;
                                                } else if ($time_difference_minutes < 270) {
                                                    $ot_munites = $time_difference_minutes - 270;
                                                    if ($ot_munites < 0) {
                                                        $icalData = $ot_munites;
                                                        $aththam = 0;
                                                    }
                                                }
                                            }
                                        } else {
                                             if ($time_difference_minutes > 271 ) {
                                                //ot balanawa paya 9kata wada wada karapuwa
                                                $ot_munites = $time_difference_minutes - 540;
                                                if ($ot_munites < 0) {
                                                    $icalData = $ot_munites;
                                                    $aththam = 1;
                                                } else {
                                                    $icalData = $ot_munites;
                                                    $aththam = 1;
                                                }

                                                //paya 4.5ta adu
                                            } else if ($time_difference_minutes < 271) {
                                                if ($time_difference_minutes == 270 || ($time_difference_minutes > 270 && $time_difference_minutes < 540)) {
                                                    $icalData = 0;
                                                    $aththam = 0.5;
                                                } else if ($time_difference_minutes < 270) {
                                                    $ot_munites = $time_difference_minutes - 270;
                                                    if ($ot_munites < 0) {
                                                        $icalData = $ot_munites;
                                                        $aththam = 0;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $AfterShiftWH = round($icalData, 2);
                                }
                            }


                            // **************************************************************************************//
                            if ($InTime == $OutTime || $OutTime == null || $OutTime == '') {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                                $Day_Type = 0.5;
                            }

                            /*
                     * If In Available & Out Missing
                     */
                            if ($InTime != '' && $InTime == $OutTime) {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                                $OutTime = "00:00:00";
                                $Day_Type = 0.5;
                            }

                            // If Out Available & In Missing
                            if ($OutTime != '' && $OutTime == $InTime) {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                                $OutTime = "00:00:00";
                                $Day_Type = 0.5;
                            }

                            // If In Available & Out Missing
                            if ($InTime != '' && $OutTime == '') {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                                $Day_Type = 0.5;
                            }

                            // If Out Available & In Missing
                            if ($OutTime != '' && $InTime == '') {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $Nopay_Hrs = 0;
                                $Day_Type = 0.5;
                            }
                            // **************************************************************************************//

                            if ($OutTime == "00:00:00") {
                                $DayStatus = 'MS';
                                $Late_Status = 0;
                                $Nopay = 0;
                                $OutTime = "00:00:00";
                                $Day_Type = 0.5;
                            }
                            if ($InTime == '' && $OutTime == '') {
                                $DayStatus = 'AB';
                                $Day_Type = 1;
                                $Nopay = 1;
                            }

                            if ($InTime != '' && $InTime != $OutTime && $OutTime != '' && ($InTime != '00:00:00' && $OutTime != '00:00:00')) {
                                $Nopay = 0;
                                $DayStatus = 'PR';
                                $Nopay_Hrs = 0;
                                $Day_Type = 1;
                            }

                            // **************************************************************************************//
                            $Nopay_Hrs = 0;

                            $Leave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='1' AND Is_Approve = '1' ");
                            if (!empty($Leave[0]->Is_Approve)) {
                                $Nopay = 0;
                                $DayStatus = 'LV';
                                $Nopay_Hrs = 0;
                                $Att_Allowance = 0;
                                $Day_Type = 1;
                                if ($InTime != '' && $InTime != $OutTime && $OutTime != '') {
                                    $Nopay = 0;
                                    $DayStatus = 'LV-PR';
                                    $Nopay_Hrs = 0;
                                    $Day_Type = 1;
                                }
                            }

                            $halfd_late = 0;
                            $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                            if (!empty($HaffDayaLeave[0]->Is_Approve)) {

                                if ($InTime == '' && $OutTime == '' && $shift_type == 'DU') {

                                    $fromtime = $from_date . " " . $cutofftime;
                                    $totime = $from_date . " " . $from_time;
                                    $timestamp1 = strtotime($totime);
                                    $timestamp2 = strtotime($fromtime);
                                    $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    $time_difference_minutes = $time_difference_seconds / 60;
                                    $halfd_late = round($time_difference_minutes, 2);
                                    $DayStatus = 'HFD-AB';
                                    $lateM = $halfd_late;
                                }
                            }


                            // echo $ID_Roster;
                            // echo "<br/>";
                            // echo $EmpNo;
                            // echo "<br/>";
                            // echo $FromDate;
                            // echo "<br/>";
                            // echo "from date-" . $from_date;
                            // echo "<br/>";
                            // echo "from time-" . $from_time;
                            // echo "<br/>";
                            // echo "in date-" . $InDate;
                            // echo "<br/>";
                            // echo "in time-" . $InTime;
                            // echo "<br/>";
                            // echo "<br/>";
                            // echo "to date-" . $to_date;
                            // echo "<br/>";
                            // echo "to time-" . $to_time;
                            // echo "<br/>";
                            // echo "out date-" . $OutDate;
                            // echo "<br/>";
                            // echo "out time-" . $OutTime;
                            // echo "<br/>";
                            // echo "Late " . $lateM;
                            // echo "<br/>";
                            // echo "ED " . $ED;
                            // echo "<br/>";
                            // echo "DayStatus " . $DayStatus;
                            // echo "<br/>";
                            // echo "OT " . $AfterShiftWH;
                            // echo "<br/>";
                            // echo "aththam " . $aththam;
                            // echo "<br/>";
                            // echo "shift_day " . $shift_day;
                            // echo "<br/>";
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
                            // die;
                            $data_arr = array("InRec" => 1, "InDate" => $FromDate, "InTime" => $InTime, "OutRec" => 1, "Day_Type" => $aththam, "OutDate" => $OutDate, "OutTime" => $OutTime, "nopay" => $Nopay, "Is_processed" => 1, "DayStatus" => $DayStatus, "BeforeExH" => $BeforeShift, "AfterExH" => $AfterShiftWH, "LateSt" => $Late_Status, "LateM" => $lateM, "EarlyDepMin" => $ED, "NetLateM" => $NetLateM, "ApprovedExH" => $ApprovedExH, "nopay_hrs" => $Nopay_Hrs, "Att_Allow" => $Att_Allowance, "DOT" => $DOT);
                            $whereArray = array("ID_roster" => $ID_Roster);
                            $result = $this->Db_model->updateData("tbl_individual_roster", $data_arr, $whereArray);
                        }
                    }
                    // }
                    $this->session->set_flashdata('success_message', 'Attendance Process successfully');
                    redirect('/Attendance/Attendance_Process_New');
                } else {
                    $this->session->set_flashdata('success_message', 'Attendance Process successfully');
                    redirect('/Attendance/Attendance_Process_New');
                }
                $this->session->set_flashdata('success_message', 'Attendance Process successfully');
                redirect('/Attendance/Attendance_Process_New');
            } else {
                $this->session->set_flashdata('error_message', 'Allocate shift first');
                redirect('/Attendance/Attendance_Process_New');
            }
        }
    }
}
