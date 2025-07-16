<?php

$date = date("Y/m/d");





// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//set_time_limit(0);
ini_set('memory_limit', '-1');
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('IN OUT Report' . $f_date . ' to ' . $t_date . '.pdf');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


//var_dump($data_cmp[0]->Company_Name);
$PDF_HEADER_TITLE = $data_cmp[0]->Company_Name;
$PDF_HEADER_LOGO_WIDTH = '0';
$PDF_HEADER_LOGO = '';
$PDF_HEADER_STRING = '';


// set default header data
$pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE . '', $PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(PDF_MARGIN_LEFT, 12, PDF_MARGIN_RIGHT);
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
        <div style="margin-left:200px; text-align:center; font-size:13px;">OT REPORT</div>
            <div style="font-size: 11px; float: left; border-bottom: solid #000 1px;">From Date:' . $f_date . ' &nbsp;- To Date : ' . $t_date . '</div></font><br>
            <table cellpadding="3">
                <thead style="border-bottom: #000 solid 1px;">
                    <tr style="border-bottom: 1px solid black;"> 
                        <th style="font-size:11px;border-bottom: 1px solid black; width:60px;">EMP NO</th>
                        <th style="font-size:11px;border-bottom: 1px solid black; width:120px;">NAME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;">DATE</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;">FROM TIME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;">TO TIME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;">IN TIME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;">OUT TIME</th>
                        <th style="font-size:11px;border-bottom: 1px solid black;">OT (MIN:HRS)</th>

                    </tr>
                </thead>
             <tbody>';

function convertMinutesToHours($minutes)
{
    $isNegative = $minutes < 0;

    // Get absolute value for calculation
    $minutes = abs($minutes);

    // Calculate hours and minutes
    $hours = intval($minutes / 60);
    $min = $minutes % 60;

    // Adjust for negative minutes
    if ($isNegative) {
        $hours = -$hours;  // Set hours to negative
        $min = -$min;      // Set minutes to negative
    }

    // Return as an array
    return [$hours, $min];
}
$i = 0;
$Total_OT= 0;


foreach ($data_set as $data) {
$Mint =   $data->AfterExH; 
$hours = floor($Mint / 60);
$min = $Mint - ($hours * 60); 

    $Total_OT+=$data->AfterExH;
    
    $i++;

    $html .= ' <tr>
                        <td  style="font-size:10px;  width:60px;">' . $data->EmpNo . '</td>
                        <td  style="font-size:10px; width:120px;">' . $data->Emp_Full_Name . '</td>
                        <td style="font-size:10px;">' . $data->FDate . '</td> 
                        <td style="font-size:10px;">' . $data->FTime . '</td>    
                        <td style="font-size:10px;">' . $data->TTime . '</td>
                        <td style="font-size:10px;">' . $data->InTime . '</td>
                        <td style="font-size:10px;">' . $data->OutTime . '</td>
                                <td style="font-size:10px;">' .$hours .':' . $min .  '</td>

                    </tr>'

    ;
}
list($total_ot_hours, $total_ot_min) = convertMinutesToHours($Total_OT);
$html .= '
    </tbody>
    </table>
    <hr style="border: none; border-top: 1px solid #000; width: 700px; margin: 10px 0 10px 100px;">

    <tr>
        <td style="font-size:10px; width:70px;"></td>
        <td style="font-size:10px; width:150px;"></td>
        <td style="font-size:10px;width:65px;"></td>
        <td style="font-size:10px;width:50px;"></td>
        <td style="font-size:10px;width:65px;"></td> 
        <td style="font-size:10px;width:60px;"></td>
        <td style="font-size:10px;width:40px;"></td>
        <td style="font-size:10px;width:30px;"></td>
        <td style="font-size:10px;width:50px;">Total OT:</td>
        <td style="font-size:10px;width:100px;">' . $total_ot_hours . ':' . str_pad(abs($total_ot_min), 2, '0', STR_PAD_LEFT) . '</td>
    </tr>

    <div style="width: 700px; margin: 0 0 0 100px; display: flex; flex-direction: row; align-items: center; justify-content: space-between; font-size: 11px; font-weight: bold; line-height: 1; padding: 0;">
        <div style="text-align: left; margin: 0; padding: 0;">
            Total Records: ' . $i . '
        </div>
    </div>
    <br>
';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------    
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('IN OUT Report' . $f_date . ' to ' . $t_date . '.pdf', 'I');

//============================================================+
    // END OF FILE
    //============================================================+
    
