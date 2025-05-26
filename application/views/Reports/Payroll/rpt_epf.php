<?php
$date = date("Y/m/d");


//var_dump($data_c[0]->id);die;


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('EPF Report.pdf');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


$PDF_HEADER_TITLE = $data_cmp[0]->EmpGroupName;
$PDF_HEADER_LOGO_WIDTH = '0';
$PDF_HEADER_LOGO = '';
$PDF_HEADER_STRING = '';

// set default header data
$pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE . '', $PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.0, 'depth_h' => 0.0, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

// Set some content to print
$month_des = '';
if(!empty($month)){
    if($month == '13'){
        $month_des = '1st Half';
    }else if($month == '14'){
        $month_des = '2nd Half';
    }else{
        $month_des = date("F", mktime(0, 0, 0, $month, 10));
    }
}
$html = '
        <div style="margin-left:200px; text-align:center; font-size:13px;">EPF REPORT</div> 
        <div style="font-size: 11px; float: left; border-bottom: solid #000 1px;">Year : '.$year." ".$month_des.'</div></font><br>
            <table cellpadding="3">
                <thead style="border-bottom: #000 solid 1px;">
                    <tr style="border-bottom: 1px solid black;"> 
                        <th style="font-size:11px;border-bottom: 1px solid black;width:60px;">EMP NO</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:150px;">NAME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:50px;">EPF NO</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:80px;">NIC NO</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:80px;">TOT FOR EPF</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:80px;">Employee Contribution (8%)</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:90px;">Employer Contribution (12%)</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:90px;">Total Contribution (20%)</th>     
                    </tr>
                </thead>
             <tbody>';
$i = 0;
foreach ($data_set as $data) {

    $i++;
    $html .= '<tr>
                        <td  style="font-size:10px;width:60px;">' . $data->EmpNo . '</td>
                        <td  style="font-size:10px;width:150px;">' . $data->Emp_Full_Name . '</td>
                        <td  style="font-size:10px;width:50px;">' . $data->EPFNO . '</td>
                        <td  style="font-size:10px;width:80px;">' . $data->NIC . '</td>
                        <td style="font-size:10px;width:80px;">' . number_format($data->Total_F_Epf, 2, '.', ',') . '</td> 
                        <td  style="font-size:10px;width:80px;">' . number_format($data->Total_EPF_Worker_Amount, 2, '.', ',') . '</td>
                        <td  style="font-size:10px;width:90px;">' . number_format($data->Total_EPF_Employee_Amount, 2, '.', ',') . '</td>
                        <td style="font-size:10px;width:90px;">' . number_format($data->Total_EPF_Worker_Amount + $data->Total_EPF_Employee_Amount, 2, '.', ',') . '</td>    
                    </tr>'

    ;
}
$html .= '</tbody>
                  
          </table>
        <br>

';
$html .= '<div style="font-size:11px; font-weight:bold; text-align:left; margin-top:10px;margin-right:10px;">
            Total Records: ' . $i . '
          </div><br>';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------    

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('EPF Report.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

