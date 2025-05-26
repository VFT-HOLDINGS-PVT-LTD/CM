<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 210mm;
            height: 297mm;
            display: flex;
            flex-wrap: wrap;
            padding: 5mm;
            box-sizing: border-box;
            justify-content: space-between;
        }

        .payslip-container {
            width: 48%; /* Adjusted width for 4 reports per page */
            margin: 0 1% 1% 0; /* Margin for spacing */
            border: 1px solid #000;
            padding: 5px;
            box-sizing: border-box;
            page-break-inside: avoid;
            font-size: 9px;
            display: flex;
            flex-direction: column;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                display: block;
                width: 100%;
                height: 100%;
            }

            .payslip-container {
                width: 48%; /* Adjusted width for 4 reports per page */
                margin: 0 1% 1% 0;
                page-break-inside: avoid;
            }

            .print-btn {
                display: none;
            }

            /* Ensure 4 reports per page in print */
            @page {
                size: A4;
                margin: 0;
            }

            .page {
                display: flex;
                flex-wrap: wrap;
            }
        }

        h2 {
            text-align: center;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        td {
            padding: 1px;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .border-top {
            border-top: 1px solid #000;
        }

        /* Print button */
        .button-container {
            text-align: center;
            margin: 20px;
        }

        .print-btn {
            padding: 10px 20px;
            font-size: 16px;
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .print-btn:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <!-- Print Button -->
    <div class="button-container">
        <button class="print-btn" onclick="window.print()">Print Payslips</button>
    </div>

    <div class="page">
        <?php foreach ($data_set as $index => $data) { ?>
            <div class="payslip-container">
                <h2><?php echo "Payslip - " . $data->Emp_Full_Name; ?></h2>

                <table>
                    <tr>
                        <td class="bold">නම:</td>
                        <td><?php echo $data->Emp_Full_Name . ' - ' . $data->EmpNo; ?></td>
                    </tr>
                    <tr>
                        <td class="bold">කණ්ඩායම:</td>
                        <td><?php echo $data->EmpGroupName?></td>
                    </tr>
                    <tr>
                        <td class="bold">මාසය:</td>
                        <td><?php echo date('F', mktime(0, 0, 0, $data_month)) . " of " . $data_year; ?></td>
                    </tr>
                </table>

                <hr>

                <table>
                    <tr class="bold">
                        <td>ඉපැයීම්</td>
                        <td class="right">මුදල</td>
                    </tr>
                    <tr>
                        <td>වැඩ කරන දින (<?php echo $data->Days_worked; ?>)</td>
                        <td class="right"><?php echo number_format($data->Basic_sal, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                    <?php
                        $Mint = $data->Normal_OT_Hrs;
                        $hours = floor($Mint / 60);
                        $min = $Mint - ($hours * 60);
                        ?>
                        <td>OT පැය ගණන (<?php echo $hours . ':' . $min ?>) (<?php echo $data->Ot_Rate; ?>)</td>
                        <td class="right"><?php echo number_format($data->Normal_OT_Pay, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>පැමිණීමේ දීමනාව I</td>
                        <td class="right"><?php echo number_format($data->Attendances_I, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>පැමිණීමේ දීමනාව II</td>
                        <td class="right"><?php echo number_format($data->Attendances_II, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>අමතර අත්තම (<?php echo $data->Extra_shifts; ?>)</td>
                        <td class="right"><?php echo number_format($data->Extra_shifts_amount, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>අයවැය දීමනාව</td>
                        <td class="right"><?php echo number_format($data->Budget_allowance, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>දිරි දීමනාව</td>
                        <td class="right"><?php echo number_format($data->Incentive, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>අවදානම් දීමනාව I</td>
                        <td class="right"><?php echo number_format($data->Risk_allowance_I, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>අවදානම් දීමනාව II</td>
                        <td class="right"><?php echo number_format($data->Risk_allowance_II, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>කොළඹ</td>
                        <td class="right"><?php echo number_format($data->Colombo, 2, '.', ','); ?></td>
                    </tr>
                    <tr class="bold">
                        <td>දල වැටුප</td>
                        <td class="right bold"><?php echo number_format($data->Gross_sal, 2, '.', ','); ?></td>
                    </tr>
                </table>

                <hr>

                <table>
                    <tr class="bold">
                        <td>අඩු කිරීම</td>
                        <td class="right">මුදල</td>
                    </tr>
                    <tr>
                        <td>EPF (8%)</td>
                        <td class="right"><?php echo number_format($data->EPF_Worker_Amount, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>අත්තිකාරම්</td>
                        <td class="right"><?php echo number_format($data->Salary_advance, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>බැංකු ගිණුම් සදහා</td>
                        <td class="right"><?php echo number_format($data->Bank_Accounts, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>ණය මුදල</td>
                        <td class="right"><?php echo number_format($data->Loan_Instalment_I, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>අවුරුදු අත්තිකාරම්</td>
                        <td class="right"><?php echo number_format($data->Festivel_Advance_I, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>කෑම සදහා</td>
                        <td class="right"><?php echo number_format($data->Foods, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>පසුගිය හිග</td>
                        <td class="right"><?php echo number_format($data->Past_deficit, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>රුහුණු ණය</td>
                        <td class="right"><?php echo number_format($data->Loan_Instalment_II, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>රඳවා ගැනීම්</td>
                        <td class="right"><?php echo number_format($data->Detentions, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>Bonus</td>
                        <td class="right"><?php echo number_format($data->Bonus, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>සමිති ණය</td>
                        <td class="right"><?php echo number_format($data->Loan_Instalment_III, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td>පොත් ණය</td>
                        <td class="right"><?php echo number_format($data->Loan_Instalment_IV, 2, '.', ','); ?></td>
                    </tr>
                    <tr class="bold">
                        <td>මුළු අඩුකිරීම්</td>
                        <td class="right bold"><?php echo number_format($data->tot_deduction, 2, '.', ','); ?></td>
                    </tr>
                    <!-- <tr class="bold">
                        <td>ශුද්ද වැටුප</td>
                        <td class="right bold"><?php echo number_format($data->Net_salary, 2, '.', ','); ?></td>
                    </tr> -->
                </table>

                <hr>

                <table>
                    <tr class="bold">
                        <td>ශුද්ද වැටුප</td>
                        <td class="right"><?php echo number_format($data->Net_salary, 2, '.', ','); ?></td>
                    </tr>
                </table>
            </div>
        <?php } ?>
    </div>

</body>

</html>
