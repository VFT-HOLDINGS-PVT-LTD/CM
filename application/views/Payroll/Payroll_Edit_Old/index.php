<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">


<title><?php echo $title ?></title>

<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <style>
        /* Sticky Table Header */
        table th {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 10;
            /* Ensure the header is on top */
            box-shadow: 0 2px 2px -2px gray;
            /* Optional: Adds a shadow to make it stand out */
        }

        /* Make first two columns (ID and EMP NO) sticky horizontally */
        /* table th:nth-child(1),
        table th:nth-child(3), */
        /* table td:nth-child(1), */
        table td:nth-child(3) {
            left: 0;
            position: sticky;
            background-color: #ffe0ed;
            z-index: 5;
            /* Lower z-index to ensure they stay below the header row */
        }

        /* Adjust sticky behavior for subsequent columns */
        table th:nth-child(1) {
            z-index: 6;
            /* Higher z-index for the first column header */
        }

        table th:nth-child(2) {
            z-index: 6;
            /* Higher z-index for the second column header */
        }

        /* Allow the rest of the table body to scroll horizontally */
        .table-responsive {
            max-height: 600px;
            /* Set a maximum height for the table body */
            overflow-y: auto;
            /* Allow vertical scrolling */
            overflow-x: auto;
            /* Allow horizontal scrolling */
        }
    </style>

</head>

<body class="infobar-offcanvas">

    <!--header-->

    <?php $this->load->view('template/header.php'); ?>

    <!--end header-->

    <div id="wrapper">
        <div id="layout-static">
            <!-- Dashboard Side -->
            <?php $this->load->view('template/dashboard_side.php'); ?>
            <!-- Dashboard Side End -->

            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li class="active"><a href="<?php echo base_url(); ?>Master/Designation/">PAYROLL ROW
                                    DATA</a></li>
                        </ol>

                        <div class="page-tabs">
                            <ul class="nav nav-tabs">
                                <!-- <li><a href="<?php echo base_url(); ?>Pay/Payroll_Edit">PAYROLL ROW DATA</a></li> -->
                                <li class="active"><a data-toggle="tab" href="#tab2">OLD PAYROLL ROW DATA</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab2">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label for="focusedinput" class="col-sm-4 control-label">Emp No</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="txt_emp" id="txt_emp"
                                                        placeholder="Ex: 0001">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="focusedinput" class="col-sm-4 control-label">Emp
                                                    Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="txt_emp_name"
                                                        id="txt_emp_name" placeholder="Ex: Ashan">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="focusedinput" class="col-sm-4 control-label">Month</label>
                                                <div class="col-sm-8">
                                                    <select required class="form-control" id="cmb_month"
                                                        name="cmb_month">
                                                        <option value="">--Select--</option>
                                                        <option value="1">January</option>
                                                        <option value="2">February</option>
                                                        <option value="3">March</option>
                                                        <option value="4">April</option>
                                                        <option value="5">May</option>
                                                        <option value="6">June</option>
                                                        <option value="7">July</option>
                                                        <option value="8">August</option>
                                                        <option value="9">September</option>
                                                        <option value="10">October</option>
                                                        <option value="11">November</option>
                                                        <option value="12">December</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <button type="button" onclick="searchEmployee()"
                                                    class="btn btn-primary mt-4">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h2>PAYROLL ROW DATA</h2>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered" cellspacing="0"
                                                        style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>EMP NO</th>
                                                                <th>NAME</th>
                                                                <th style="color:#dd3232;">BASIC SALARY</th>
                                                                <th>WORKED DAYS</th>
                                                                <th>OT</th>
                                                                <th>ATTENDANCE I</th>
                                                                <th>ATTENDANCE II</th>
                                                                <th>BUDGET ALL</th>
                                                                <th>INCENTIVE</th>
                                                                <th>RISK ALLOWANCE I</th>
                                                                <th>RISK ALLOWANCE II</th>
                                                                <th>COLOMBO</th>
                                                                <th style="color:#dd3232;">GROSS SAL</th>
                                                                <th>EPF</th>
                                                                <th>SAL ADV.</th>
                                                                <th>BANK ACC.</th>
                                                                <th>LOAN</th>
                                                                <th>NEW YEAR</th>
                                                                <th>FOODS</th>
                                                                <th>PAST DEFICIT</th>
                                                                <th>RUHUNU LOAN</th>
                                                                <th>DETENTIONS</th>
                                                                <th>BONUS</th>
                                                                <th>UNION LOAN</th>
                                                                <th>BOOK LOAN</th>
                                                                <th style="color:#dd3232;">TOT DEDUCTION</th>
                                                                <th style="color:rgb(6 143 6);">NET SALARY</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Table data goes here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <?php $this->load->view('template/footer.php'); ?>
                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>




    <!-- Load site level scripts -->

    <?php $this->load->view('template/js.php'); ?> <!-- Initialize scripts for this page-->

    <!-- End loading page level scripts-->

    <!--Ajax-->
    <script src="<?php echo base_url(); ?>system_js/Master/Designation.js"></script>

    <script type="text/javascript">
        $(function() {
            $("#txt_emp_name").autocomplete({
                source: "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_In_Out/get_auto_emp_name"
            });
        });

        $(function() {
            $("#txt_emp").autocomplete({
                source: "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_In_Out/get_auto_emp_no"
            });
        });
    </script>
    <script>
        // Attach event listeners to all Edit buttons
        function attachEditButtonListeners() {
            document.querySelectorAll('.edit-btn').forEach((button) => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr'); // Get the current row
                    const isEditing = row.classList.contains('editing');

                    if (!isEditing) {
                        // Switch to edit mode
                        row.classList.add('editing');
                        this.textContent = 'Save'; // Change button text

                        Array.from(row.cells).forEach((cell, index) => {
                            // Exclude the button cell and restricted cells by their index
                            if (index < row.cells.length - 1 && ![0, 1, 2, 3, 4, 5, 6, 8, 11, 13, 14, 15, 17, 18, 21, 24, 25, 26, 27].includes(index)) {
                                const value = cell.textContent.trim();
                                cell.innerHTML = `<input type="text" value="${value}" style="width:100%;"/>`;

                                // Add live update event listener for specific columns
                                if ([7, 9, 10, 12, 16, 19, 20, 22, 23].includes(index)) {
                                    const input = cell.querySelector('input');
                                    input.addEventListener('input', () => {
                                        // Extract meaningful data from the row
                                        const rowData = Array.from(row.cells).map((cell) => {
                                            const input = cell.querySelector('input');
                                            return input ? input.value.trim() : cell.textContent.trim();
                                        });

                                        // Alert the extracted data
                                        // alert(JSON.stringify(rowData));
                                        // console.log(JSON.stringify(rowData))
                                        liveUpdate(rowData, row);
                                    });
                                }
                            }
                        });
                    } else {
                        // Save changes
                        row.classList.remove('editing');
                        this.textContent = 'Edit'; // Change button text back

                        const month = document.getElementById("cmb_month").value;

                        Array.from(row.cells).forEach((cell, index) => {
                            // Exclude the button cell and restricted cells by their index
                            if (index < row.cells.length - 1 && ![0, 1].includes(index)) {
                                const input = cell.querySelector('input');
                                if (input) {
                                    cell.textContent = input.value; // Update cell content
                                }
                            }
                        });

                        // Collect updated data from the row
                        const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());

                        // Send updated data to the server
                        fetch('<?php echo base_url(); ?>Pay/Payroll_Edit_Old/edit_data', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    rowData,
                                    month

                                }) // Send the updated row data
                            })
                            .then(response => response.json()) // Parse the JSON response
                            .then(data => {
                                alert(data.status); // Optionally alert the user
                                window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Edit_Old'); // Reload the page after saving
                            })
                            .catch(error => {
                                console.error('Error updating row:', error);
                                alert('An unexpected error occurred: ' + error.message);
                            });
                    }
                });
            });
        }

        // Function to live update Total_F_Epf, Gross_pay, DEDUCTION, and Net Salary
        function liveUpdate(rowData, therow) {
            // Assuming rowData is an array of values
            console.log("Updated Row Data:", rowData);
            const cells = Array.from(therow.cells);
            // Extract values from rowData (this array corresponds to the columns in your table)
            const basicSal = parseFloat(rowData[3]) || 0;
            const Normal_OT_Pay = parseFloat(rowData[5]) || 0;
            const Attendances_I = parseFloat(rowData[6]) || 0;
            const Attendances_II = parseFloat(rowData[7]) || 0;
            const Budget_allowance = parseFloat(rowData[8]) || 0;
            const Incentive = parseFloat(rowData[9]) || 0;
            const Risk_allowance_I = parseFloat(rowData[10]) || 0;
            const Risk_allowance_II = parseFloat(rowData[11]) || 0;
            const Colombo = parseFloat(rowData[12]) || 0;

            // Calculate Gross Pay
            const grossPay = basicSal + Normal_OT_Pay + Attendances_I + Attendances_II + Budget_allowance + Incentive + Risk_allowance_I + Risk_allowance_II + Colombo;
            const grossPayCell = cells[13];
            grossPayCell.textContent = (
                grossPay
            ).toFixed(2);
            // Update the Gross Pay cell (assuming index 13 in rowData corresponds to the gross pay column)
            // rowData[13] = grossPay.toFixed(2);

            // Deductions
            const EPF_Worker_Amount = parseFloat(rowData[14]) || 0;
            const Salary_advance = parseFloat(rowData[15]) || 0;
            const Bank_Accounts = parseFloat(rowData[16]) || 0;
            const Loan_Instalment_I = parseFloat(rowData[17]) || 0;
            const Festivel_Advance_I = parseFloat(rowData[18]) || 0;
            const Foods = parseFloat(rowData[19]) || 0;
            const Past_deficit = parseFloat(rowData[20]) || 0;
            const Loan_Instalment_II = parseFloat(rowData[21]) || 0;
            const Detentions = parseFloat(rowData[22]) || 0;
            const Bonus = parseFloat(rowData[23]) || 0;
            const Loan_Instalment_III = parseFloat(rowData[24]) || 0;
            const Loan_Instalment_IV = parseFloat(rowData[25]) || 0;
            // const nopay = parseFloat(rowData[24]) || 0;


            // Update the Deduction cell (assuming index 18 corresponds to the deduction column)
            // rowData[18] = totalDeductions.toFixed(2);


            // Update the Net Salary cell (assuming index 20 corresponds to the net salary column)
            // rowData[20] = netSalary.toFixed(2);

            // EPF and ETF contributions
            // const epfEmployee = total_f_epf * 0.08; // 8% of basic salary
            // const epfEmployer = total_f_epf * 0.12; // 12% of basic salary
            // const etf = total_f_epf * 0.03; // 3% of basic salary

            // Calculate Total Deductions
            const totalDeductions = EPF_Worker_Amount + Salary_advance + Bank_Accounts + Loan_Instalment_I + Festivel_Advance_I + Foods + Past_deficit + Loan_Instalment_II + Detentions + Bonus + Loan_Instalment_III + Loan_Instalment_IV;
            const tot_ded = cells[26];
            tot_ded.textContent = (
                totalDeductions
            ).toFixed(2);

            // Calculate Net Salary
            const netSalary = grossPay - totalDeductions;
            const net_ssal = cells[27];
            net_ssal.textContent = (
                netSalary
            ).toFixed(2);

            // Update EPF and ETF cells (assuming indices 19, 21, and 22 correspond to these columns)
            rowData[19] = epfEmployee.toFixed(2);
            rowData[21] = epfEmployer.toFixed(2);
            rowData[22] = etf.toFixed(2);

            // Log updated row data for debugging
            // console.log("Updated Row Data after calculations:", rowData);

            // Optionally, you can return the updated rowData to update the table or send it to the server
            return rowData;
        }


        function searchEmployee() {
            // Get input values
            const empNo = document.getElementById("txt_emp").value.trim();
            const empName = document.getElementById("txt_emp_name").value.trim();
            const month = document.getElementById("cmb_month").value;

            if (month == "") {
                alert("Please enter month.");
                return;
            }

            var payload = {
                "empNo": empNo,
                "empName": empName,
                "month": month,
            };
            $.ajax({
                url: '<?php echo base_url(); ?>Pay/Payroll_Edit_Old/Payroll_Search',
                type: "POST",
                contentType: 'application/json',
                data: JSON.stringify(payload),
                success: function(response) {
                    console.log(response);
                    // alert(response.token);
                    // const apiToken = response.token;
                    // alert(apiToken);
                    try {
                        const data = JSON.parse(response); // Parse the JSON string if needed
                        if (Array.isArray(data)) {
                            updateTable(data); // Call updateTable if data is an array
                        } else if (data.status && data.status === "error") {
                            alert(data.message || "An error occurred.");
                        } else {
                            console.error("Unexpected data format:", data);
                            alert("Unexpected data format received from the server.");
                        }
                    } catch (e) {
                        console.error("Error parsing response:", e);
                        alert("Failed to parse server response.");
                    }
                },
                error: function(error) {
                    console.error('Error in the request:', error);
                    alert('An error occurred while processing your request. Please check the console for more details.');
                }
            });

            // // Prepare data to send as JSON
            // const searchData = {
            //     txt_emp: empNo,
            //     txt_emp_name: empName,
            //     cmb_month: month
            // };

            // // alert(searchData.txt_emp);
            // // Send the data to CodeIgniter controller via fetch
            // fetch("<?php echo base_url(); ?>Pay/Payroll_Edit_Old/Payroll_Search", {
            //         method: "POST",
            //         headers: {
            //             "Content-Type": "application/json" // Set content type to JSON
            //         },
            //         body: JSON.stringify(searchData) // Convert the data to a JSON string
            //     })
            //     .then(response => {
            //         if (!response.ok) {
            //             throw new Error(`HTTP error! Status: ${response.status}`);
            //         }
            //         return response.json(); // Parse the response as JSON
            //     })
            //     .then(data => {
            //         console.log("Controller Response:", data); // Log the JSON response
            //         if (data.status && data.status === "No data found") {
            //             alert("No records found matching your search criteria.");
            //         } else {
            //             console.log(data); // Handle the response data (e.g., update the table)
            //         }
            //     })
            //     .catch(error => {
            //         console.error("Error fetching search results:", error);
            //         alert("Failed to retrieve search results. Please try again.");
            //     });
        }


        function updateTable(data) {
            const tableBody = document.querySelector("table tbody");
            tableBody.innerHTML = ""; // Clear current table rows

            // Insert new rows from the received data
            data.forEach(row => {
                const newRow = `
            <tr class='odd gradeX' style="height: 50px;">
                <td style="width:200px" >${row.ID || '-'}</td>
                <td style="width:200px">${row.EmpNo || '-'}</td>
                <td>${row.Emp_Full_Name || '-'}</td>
                <td style="color:#dd3232;">${row.Basic_sal || '-'}</td>
                <td>${row.Days_worked || '-'}</td>
                <td>${row.Normal_OT_Pay || '-'}</td>
                <td>${row.Attendances_I || '-'}</td>
                <td>${row.Attendances_II || '-'}</td>
                <td>${row.Budget_allowance || '-'}</td>
                <td>${row.Incentive || '-'}</td>
                <td>${row.Risk_allowance_I || '-'}</td>
                <td>${row.Risk_allowance_II || '-'}</td>
                <td>${row.Colombo || '-'}</td>
                <td style="color:#dd3232;">${row.Gross_sal || '-'}</td>
                <td>${row.EPF_Worker_Amount || '-'}</td>
                <td>${row.Salary_advance || '-'}</td>
                <td>${row.Bank_Accounts || '-'}</td>
                <td>${row.Loan_Instalment_I || '-'}</td>
                <td>${row.Festivel_Advance_I || '-'}</td>
                <td>${row.Foods || '-'}</td>
                <td>${row.Past_deficit || '-'}</td>
                <td>${row.Loan_Instalment_II || '-'}</td>
                <td>${row.Detentions || '-'}</td>
                <td>${row.Bonus || '-'}</td>
                <td>${row.Loan_Instalment_III || '-'}</td>
                <td>${row.Loan_Instalment_IV || '-'}</td>
                <td style="color:#dd3232;">${row.tot_deduction || '-'}</td>
                <td style="color:rgb(6, 143, 6);">${row.Net_salary || '-'}</td>
                <td style="width:100px; text-align:right;font-weight:bold">
                    <button class="edit-btn btn btn-primary">Edit</button>
                </td>
            </tr>`;

                tableBody.insertAdjacentHTML("beforeend", newRow);
            });

            // After the table is updated, attach event listeners to the Edit buttons
            attachEditButtonListeners();
        }
    </script>
</body>


</html>