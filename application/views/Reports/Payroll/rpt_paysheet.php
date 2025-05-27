<?php

$date = date("Y/m/d");

$data_month;

//var_dump($data_c[0]->id);die;
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Paysheet_Month_' . $data_month . '.pdf');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . '', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


$PDF_HEADER_TITLE = $data_group;
$PDF_HEADER_LOGO_WIDTH = '0';
$PDF_HEADER_LOGO = '';
$PDF_HEADER_STRING = '';


// set default header data
$pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE . '', $PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------    
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('helvetica', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.



$pdf->SetMargins(5, 14, 15, 0, true);
$pdf->AddPage('L', 'LEGAL');
//$pdf->SetMargins(0, 0, 0, true);
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.0, 'depth_h' => 0.0, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

// Set some content to print
$html = '
<style>
    @media print {
        .page-break {
            page-break-before: always;
        }
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border-bottom: 1px dashed black;
        font-size: 8px;
        padding: 3px;
    }
    th {
        font-weight: bold;
        border-top: 1px solid black;
    }
</style>

<h6 style="margin-left:0px; text-align:center;">PAY SHEET</h6>
<div style="font-size: 11px; float: left; border-bottom: solid #000 1px;">Year: ' . $data_year . ' &nbsp; Month: ' . date('F', mktime(0, 0, 0, $data_month)) . '</div><br>';

$chunks = array_chunk($data_set, 13);
$grandTotalBalance = 0;
$grandTotalRecords = 0;

foreach ($chunks as $index => $chunk) {
    $html .= '<table cellpadding="3">
        <thead style="border-bottom: #000 solid 1px;">
            <tr style="border-bottom: 1px solid black; font-weight:bold"> 
                <th style="width:50px;">EMP NO</th>                      
                <th style="width:150px;">NAME</th>                       
                <th style="width:50px;">WORK DAYS</th>
                <th style="width:50px;">EXTRA SHIFTS</th>
                <th style="width:50px;">N OT HRS</th>
                <th style="width:65px;">ATTENDANCE I</th>
                <th style="width:65px;">ATTENDANCE BONUS</th>
                <th style="width:50px;">RISK ALL. I</th>
                <th style="width:55px;">INCENTIVE</th>
                <th style="width:50px;font-weight:bold;">GROSS SAL</th>
                <th style="width:40px;"></th>
                <th style="width:55px;">SAL. ADV.</th>
                <th style="width:55px;">LOAN</th>
                <th style="width:60px;">FOODS</th>
                <th style="width:60px;">RUHUNU LOAN</th>                       
                <th style="width:60px;">BONUS</th>
                <th style="width:55px;">BOOK LOAN</th>
                <th style="width:60px;">TOTAL DEDUCTION</th>
                <th style="width:50px;">EPF 12%</th>
                <th style="width:50px;">ETF 3%</th>
                <th style="width:50px;font-weight:bold;">BALANCE</th>                                                                                                                                                                                                            
            </tr>
            <tr style="border-bottom: 1px solid black; font-weight:bold"> 
                <th style="width:50px; border-bottom: 1px solid black;"></th>                      
                <th style="width:150px; border-bottom: 1px solid black;"></th>                      
                <th style="width:50px; border-bottom: 1px solid black;">BASIC SALARY</th>
                <th style="width:50px; border-bottom: 1px solid black;">EX.SHIFT AMOUNT</th>
                <th style="width:50px; border-bottom: 1px solid black;">OT</th>
                <th style="width:65px; border-bottom: 1px solid black;">ATTENDANCE II</th>
                <th style="width:65px; border-bottom: 1px solid black;">BUDGET ALL.</th>
                <th style="width:50px; border-bottom: 1px solid black;">RISK ALL. II</th>
                <th style="width:55px; border-bottom: 1px solid black;">COLOMBO</th>
                <th style="width:50px; border-bottom: 1px solid black;"></th>
                <th style="width:40px; border-bottom: 1px solid black;"></th>
                <th style="width:55px; border-bottom: 1px solid black;">BANK ACCOUNTS</th>
                <th style="width:55px; border-bottom: 1px solid black;">NEW YEAR ADV</th>
                <th style="width:60px; border-bottom: 1px solid black;">PAST DEFICIT</th>
                <th style="width:60px; border-bottom: 1px solid black;">DETENTIONS</th>                      
                <th style="width:60px; border-bottom: 1px solid black;">UNION LOAN</th>
                <th style="width:55px; border-bottom: 1px solid black;">EPF 8%</th>
                <th style="width:60px; border-bottom: 1px solid black;"></th>
                <th style="width:50px; border-bottom: 1px solid black;"></th>
                <th style="width:50px; border-bottom: 1px solid black;"></th>                       
                <th style="width:50px; border-bottom: 1px solid black;"></th>
                <th style="width:50px; border-bottom: 1px solid black;"></th>                                                                                                                                                                                                                      
            </tr>
        </thead>
        <tbody>';

    foreach ($chunk as $data) {
        $Mint = $data->Normal_OT_Hrs;
                        $hours = floor($Mint / 60);
                        $min = $Mint - ($hours * 60);

        $grandTotalBalance += $data->Net_salary;
        $grandTotalRecords++;

        $html .= '<tr>
            <td style="width:50px;">' . $data->EmpNo . '</td>                      
            <td style="width:150px;">' . $data->Emp_Full_Name . '</td>                       
            <td style="width:50px;">' . $data->Days_worked . '</td>
            <td style="width:50px;">' . $data->Extra_shifts. '</td>
            <td style="width:50px;">' . $hours . ':' . $min. '</td>
            <td style="width:65px;">' . number_format($data->Attendances_I, 2, '.', ',') . '</td>
            <td style="width:65px;">' . number_format($data->Attendance_bonus, 2, '.', ',') . '</td>
            <td style="width:50px;">' . number_format($data->Risk_allowance_I, 2, '.', ',') . '</td>
            <td style="width:55px;">' . number_format($data->Incentive, 2, '.', ',') . '</td>
            <td style="width:50px;font-weight:bold;">' . number_format($data->Gross_sal, 2, '.', ',') . '</td>
            <td style="width:40px;"></td>
            <td style="width:55px;">' .  number_format($data->Salary_advance, 2, '.', ',') . '</td>
            <td style="width:55px;">' . number_format($data->Loan_Instalment_I, 2, '.', ',') . '</td>
            <td style="width:60px;">' . number_format($data->Foods, 2, '.', ',')  . '</td>
            <td style="width:60px;">' . number_format($data->Loan_Instalment_II, 2, '.', ',') . '</td>                       
            <td style="width:60px;">' . number_format($data->Bonus, 2, '.', ',') . '</td>
            <td style="width:55px;">' . number_format($data->Loan_Instalment_IV, 2, '.', ',') . '</td>
            <td style="width:60px;">' . number_format($data->tot_deduction, 2, '.', ',') . '</td>
            <td style="width:50px;">' . number_format($data->EPF_Employee_Amount, 2, '.', ',') . '</td>
            <td style="width:50px;">' . number_format($data->ETF_Amount, 2, '.', ',') . '</td>                        
            
            <td style="width:50px;font-weight:bold;">' . number_format($data->Net_salary, 2, '.', ',') . '</td>                                                     
        </tr>
        <tr>
            <td style="width:50px; border-bottom: 1px solid black;"></td>                       
            <td style="width:150px; border-bottom: 1px solid black;"></td>                          
            <td style="width:50px; border-bottom: 1px solid black;">' . number_format($data->Basic_sal, 2, '.', ',')  . '</td>
            <td style="width:50px; border-bottom: 1px solid black;">' . number_format($data->Extra_shifts_amount, 2, '.', ',') . '</td>
            <td style="width:50px; border-bottom: 1px solid black;">' . number_format($data->Normal_OT_Pay, 2, '.', ',')  . '</td>
            <td style="width:65px; border-bottom: 1px solid black;">' . number_format($data->Attendances_II, 2, '.', ',') . '</td>
            <td style="width:65px; border-bottom: 1px solid black;">' . number_format($data->Budget_allowance, 2, '.', ',') . '</td>
            <td style="width:50px; border-bottom: 1px solid black;">' . number_format($data->Risk_allowance_II, 2, '.', ',') . '</td>
            <td style="width:55px; border-bottom: 1px solid black;">' . number_format($data->Colombo, 2, '.', ',') . '</td>
            <td style="width:50px; border-bottom: 1px solid black;"></td>
            <td style="width:40px; border-bottom: 1px solid black;"></td>
            <td style="width:55px; border-bottom: 1px solid black;">' . number_format($data->Bank_Accounts, 2, '.', ',') . '</td>
            <td style="width:55px; border-bottom: 1px solid black;">' . number_format($data->Festivel_Advance_I, 2, '.', ',') . '</td>
            <td style="width:60px; border-bottom: 1px solid black;">' . number_format($data->Past_deficit, 2, '.', ',') . '</td>
            <td style="width:60px; border-bottom: 1px solid black;">' . number_format($data->Detentions, 2, '.', ',')  . '</td>                           
            <td style="width:60px; border-bottom: 1px solid black;">' . number_format($data->Loan_Instalment_III, 2, '.', ',') . '</td>
            <td style="width:55px; border-bottom: 1px solid black;">' . number_format($data->EPF_Worker_Amount, 2, '.', ',') . '</td>                                                              
            <td style="width:60px; border-bottom: 1px solid black;"></td>                       
            <td style="width:50px; border-bottom: 1px solid black;"></td>
            <td style="width:50px; border-bottom: 1px solid black;"></td>
            <td style="width:50px; border-bottom: 1px solid black;"></td>
            <td style="width:50px; border-bottom: 1px solid black;"></td>                        
        </tr>';
    }
    $html .= '</tbody></table>';

    if ($index < count($chunks) - 1) {
        $html .= '<div class="page-break"></div>';
    }
}

$html .= '</div>';
$html .= '
    <table cellpadding="3">
        <tr style="border-bottom: 1px solid black; font-weight:bold">
            <td style="font-size:11px; font-weight:bold; text-align:right;">
                Grand Total Balance: ' . number_format($grandTotalBalance, 2, '.', ',') . '
            </td>
        </tr>
        <tr>
            <td style="font-size:11px; font-weight:bold; text-align:left; padding:2px 0;">
                Grand Total Records: ' . $grandTotalRecords . '
            </td>
        </tr>
    </table>
    <br>
';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------    
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Paysheet_Month_' . $data_month . '.pdf', 'I');

//============================================================+
    // END OF FILE
    //============================================================+
