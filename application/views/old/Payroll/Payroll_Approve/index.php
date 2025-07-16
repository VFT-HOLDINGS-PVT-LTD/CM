<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">

<title><?php echo $title ?></title>

<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <style>
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

            <!--dashboard side-->

            <?php $this->load->view('template/dashboard_side.php'); ?>

            <!--dashboard side end-->

            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb" style="width: 160%;">

                            <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li class="active"><a href="<?php echo base_url(); ?>Master/Designation/">PAYROLL ROW
                                    DATA</a></li>

                        </ol>


                        <div class="page-tabs" style="width: 160%;">
                            <ul class="nav nav-tabs">

                                <!-- <li><a href="<?php echo base_url(); ?>Pay/Payroll_Edit">PAYROLL ROW DATA</a> -->
                                <li class="active"><a data-toggle="tab" href="#tab2">OLD PAYROLL ROW DATA</a></li>

                                </li>

                            </ul>
                        </div>

                    </div>
                    <div class="container-fluid">


                        <div class="tab-content">

                            <!--***************************-->
                            <!-- Grid View -->
                            <div class="tab-pane active" id="tab2">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <div class=" col-md-12">
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
                                                    <select required="" class="form-control" id="cmb_month"
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
                                            <div class="mt-4">
                                                <button type="button" onclick="searchEmployee()"
                                                    class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                    <span>Search</span>
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
                                                    <button type="button" class='get_data btn btn-primary'
                                                        onclick="handleApproveAll()">Approve All Selected</button>
                                                    <table class="table table-striped table-bordered" cellspacing="0"
                                                        width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th><input type="checkbox" id="select-all"></th>
                                                                <th>ID</th>
                                                                <th>EMP NO</th>
                                                                <th>NAME&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                <th style="color:#dd3232;">BASIC SALARY</th>
                                                                <th>EXTRA SHIFT</th>
                                                                <th>OT</th>
                                                                <th>ATTENDANCE I</th>
                                                                <th>ATTENDANCE II</th>
                                                                <th>BUDGET ALL</th>
                                                                <th>INCENTIVE </th>
                                                                <th>RISK ALLOWANCE I&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                <th>RISK ALLOWANCE II&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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

                            <!-- End Grid View-->


                        </div>





                    </div> <!-- .container-fluid -->
                </div>
                <!--Footer-->
                <?php $this->load->view('template/footer.php'); ?>
                <!--End Footer-->
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
                    // Update the row cells
                    Array.from(row.cells).forEach((cell, index) => {
                        if (index < row.cells.length - 1 && ![0, 1, 2, 5, 13, 18, 19, 20, 21, 22].includes(index)) {
                            const input = cell.querySelector('input');
                            if (input) {
                                cell.textContent = input.value; // Update cell content
                            }
                        }
                    });

                    // Collect updated data from the row
                    const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());

                    // Send updated data to the server
                    fetch('<?php echo base_url(); ?>Pay/Payroll_Approve/edit_data', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                rowData
                            }), // Send the updated row data
                        })
                        .then((response) => response.json()) // Parse the JSON response
                        .then((data) => {
                            if (data.status === 'success') {
                                // console.log(JSON.stringify(data));
                                alert('Row updated successfully');
                                window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Approve');
                            } else {
                                // alert('Error: ' + data.message);
                                console.log(JSON.stringify(data));
                            }
                        })
                        .catch((error) => {
                            console.error('Error updating row:', error);
                            alert('An unexpected error occurred: ' + error.message);
                        });
                });
            });
        }
        //////////////////////////reject approve
        function attachRejectButtonListeners() {
            document.querySelectorAll('.reject-btn').forEach((button) => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr'); // Get the current row
                    // Update the row cells
                    Array.from(row.cells).forEach((cell, index) => {
                        if (index < row.cells.length - 1 && ![0, 1, 2, 5, 13, 18, 19, 20, 21, 22].includes(index)) {
                            const input = cell.querySelector('input');
                            if (input) {
                                cell.textContent = input.value; // Update cell content
                            }
                        }
                    });

                    // Collect updated data from the row
                    const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());

                    // Send updated data to the server
                    fetch('<?php echo base_url(); ?>Pay/Payroll_Approve/reject_data', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                rowData
                            }), // Send the updated row data
                        })
                        .then((response) => response.json()) // Parse the JSON response
                        .then((data) => {
                            // console.log(JSON.stringify(data));
                            if (data.status === 'success') {
                                console.log(JSON.stringify(data));
                                alert('Rejected');
                                window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Approve');
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch((error) => {
                            console.error('Error updating row:', error);
                            alert('An unexpected error occurred: ' + error.message);
                        });
                });
            });
        }
        //////////////////////////reject approve


        // Function to live update Total_F_Epf, Gross_pay, DEDUCTION, and Net Salary
        function liveUpdate(row) {
            const cells = Array.from(row.cells);
            const basicSal = parseFloat(cells[3].querySelector('input')?.value.trim()) || 0;
            const brPay = parseFloat(cells[4].querySelector('input')?.value.trim()) || 0;

            // Update Total_F_Epf
            const totalFEpfCell = cells[5];
            totalFEpfCell.textContent = (basicSal + brPay).toFixed(2);

            // Update Gross_pay
            const fixed = parseFloat(cells[6].querySelector('input')?.value.trim()) || 0;
            const performance = parseFloat(cells[7].querySelector('input')?.value.trim()) || 0;
            const attendance = parseFloat(cells[8].querySelector('input')?.value.trim()) || 0;
            const transport = parseFloat(cells[9].querySelector('input')?.value.trim()) || 0;
            const fuel = parseFloat(cells[10].querySelector('input')?.value.trim()) || 0;
            const traveling = parseFloat(cells[11].querySelector('input')?.value.trim()) || 0;
            const spAllowance = parseFloat(cells[12].querySelector('input')?.value.trim()) || 0;

            const grossPayCell = cells[13];
            grossPayCell.textContent = (
                basicSal +
                brPay +
                fixed +
                performance +
                attendance +
                transport +
                fuel +
                traveling +
                spAllowance
            ).toFixed(2);

            // Update DEDUCTION
            const Late_deduction = parseFloat(cells[14].querySelector('input')?.value.trim()) || 0;
            const Ed_deduction = parseFloat(cells[15].querySelector('input')?.value.trim()) || 0;
            const Salary_advance = parseFloat(cells[16].querySelector('input')?.value.trim()) || 0;
            const no_pay_deduction = parseFloat(cells[17].querySelector('input')?.value.trim()) || 0;

            const deductionCell = cells[18];
            deductionCell.textContent = (
                Late_deduction +
                Ed_deduction +
                Salary_advance +
                no_pay_deduction
            ).toFixed(2);

            // Update Net Salary
            const D_Salary = parseFloat(deductionCell.textContent) || 0;
            const Net_salary = parseFloat(grossPayCell.textContent) - D_Salary;

            const netSalaryCell = cells[20];
            netSalaryCell.textContent = Net_salary.toFixed(2);

            const epfdata1 = cells[19];
            const result = basicSal / 100 * 8;
            epfdata1.textContent = result.toFixed(2);

            const epfdata2 = cells[21];
            const result2 = basicSal / 100 * 12;
            epfdata2.textContent = result2.toFixed(2);

            const etfdata1 = cells[22];
            const result3 = basicSal / 100 * 3;
            etfdata1.textContent = result3.toFixed(2);
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
            var searchData = {
                "empNo": empNo,
                "empName": empName,
                "month": month,
            };
            // Send the data to CodeIgniter controller via fetch
            fetch("<?php echo base_url(); ?>Pay/Payroll_Approve/Payroll_Search", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json' // Set content type to JSON
                    },
                    body: JSON.stringify(searchData) // Convert the data to a JSON string
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json(); // Parse the response as JSON
                })
                .then(data => {
                    console.log("Controller Response:", data); // Log the JSON response
                    if (data.status && data.status === "No data found") {
                        alert("No records found matching your search criteria.");
                    } else {
                        updateTable(data);
                        // console.log() // Call function to update the table with new data
                    }
                })
                .catch(error => {
                    console.error("Error fetching search results:", error);
                    alert("Failed to retrieve search results. Please try again.");
                });
        }

        function updateTable(data) {
            const tableBody = document.querySelector("table tbody");
            tableBody.innerHTML = ""; // Clear current table rows

            // Insert new rows from the received data
            data.forEach(row => {
                const newRow = `
            <tr class='odd gradeX' style="height: 50px;">
            <td width='15'>
                                <input type='checkbox' class='select-item' value="${row.ID}">
                </td>
                <td>${row.ID || '-'}</td>
                <td>${row.EmpNo || '-'}</td>
                <td>${row.Emp_Full_Name || '-'}</td>
                <td style="color:#dd3232;">${row.Basic_sal || '-'}</td>
                <td>${row.Extra_shifts_amount || '-'}</td>
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
                <td style="width:80px; text-align:right;font-weight:bold">
                    <button class="edit-btn btn btn-green">APPROVE</button>
                </td>
                <td style="width:80px; text-align:right;font-weight:bold">
                <button class="reject-btn btn btn-danger">REJECT</button>
                </td>
            </tr>`;

                tableBody.insertAdjacentHTML("beforeend", newRow);
            });

            // After the table is updated, attach event listeners to the Edit buttons
            attachEditButtonListeners();
            attachRejectButtonListeners();
        }
    </script>
    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('.select-item');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        function handleApproveAll() {
            var selected = [];
            var checkboxes = document.querySelectorAll('.select-item:checked');
            for (var checkbox of checkboxes) {
                selected.push(checkbox.value);
            }

            if (selected.length > 0) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo base_url(); ?>Pay/Payroll_Approve/approveAll';

                for (var id of selected) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
                alert('Approved successfully');
                // window.location = "<?php echo base_url(); ?>Pay/Payroll_Approve";
            } else {
                alert('No leave requests selected');
            }
        }
    </script>
</body>


</html>