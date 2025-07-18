<?php
$date = date("Y/m/d");


//var_dump($data_c[0]->id);die;


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('ETF Report.pdf');
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
$html = '
        <div style="margin-left:200px; text-align:center; font-size:13px;">EPF REPORT</div> 
        <div style="font-size: 11px; float: left; border-bottom: solid #000 1px;">Year : '.$year.'</div></font><br>
            <table cellpadding="3">
                <thead style="border-bottom: #000 solid 1px;">
                    <tr style="border-bottom: 1px solid black;"> 
                        <th style="font-size:11px;border-bottom: 1px solid black;width:80px;">EMP NO</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:170px;">EMP NAME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:50px;">EPF NO</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:80px;">NIC NO</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:90px;">DESIGNATION</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:90px;">GROSS SALARY</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;width:90px;">ETF. 3%(LKR)</th> 
                    </tr>
                </thead>
             <tbody>';

             $total_grosssalary=0;
             $contr=0;
$i = 0;             
foreach ($data_set as $data) {
    $i++;
    $total_grosssalary=intval($data->Total_ETF)+$total_grosssalary;
    $contr=intval($data->ETF_Amount)+$contr;
    $html .= '<tr>
                <td  style="font-size:10px;width:80px;">' . $data->EmpNo . '</td>
                <td  style="font-size:10px;width:170px;">' . $data->Emp_Full_Name . '</td>
                <td  style="font-size:10px;width:50px;">' . $data->EPFNO . '</td>
                <td  style="font-size:10px;width:80px;">' . $data->NIC . '</td>
                <td  style="font-size:10px;width:90px;">' . $data->Desig_Name . '</td>
                <td  style="font-size:10px;width:90px;">' . number_format($data->Total_ETF, 2, '.', ',') . '</td> 
                <td  style="font-size:10px;width:90px;">' . number_format($data->ETF_Amount, 2, '.', ',') . '</td>     
                </tr>'
    ;
}
$html .= '<tr>
            <td style="font-size:10px;"></td>
            <td style="font-size:10px;"></td>
            <td style="font-size:11px;font-weight:bold;">&nbsp;</td>
            <td style="font-size:10px;"></td>
            <td style="font-size:10px;"></td>
            <td style="font-size:11px;">&nbsp;</td> 
            <td style="font-size:11px;">&nbsp;</td>       
        </tr>';
$html .= '<tr>
            <td style="font-size:11px;border-bottom: 1px solid black;border-top: 1px solid black;"></td>
            <td style="font-size:11px;border-bottom: 1px solid black;border-top: 1px solid black;"></td>
            <td style="font-size:11px;border-bottom: 1px solid black;border-top: 1px solid black;">TOTAL</td>
            <td style="font-size:11px;border-bottom: 1px solid black;border-top: 1px solid black;"></td>
            <td style="font-size:11px;border-bottom: 1px solid black;border-top: 1px solid black;"></td>
            <td style="font-size:11px;border-bottom: 1px solid black;border-top: 1px solid black;">' . number_format($total_grosssalary, 2, '.', ',') . '</td> 
            <td style="font-size:11px;border-bottom: 1px solid black;border-top: 1px solid black;">' . number_format($contr, 2, '.', ',') . '</td>       
        </tr>';
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
$pdf->Output('ETF Report.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

