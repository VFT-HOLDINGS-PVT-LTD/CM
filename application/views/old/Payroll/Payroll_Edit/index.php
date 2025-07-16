<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">

<title><?php echo $title ?></title>

<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
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
                    <ol class="breadcrumb" style="width: 100%;">
                        <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                        <li class="active"><a href="<?php echo base_url(); ?>Master/Designation/">PAYROLL ROW DATA</a></li>
                    </ol>

                    <div class="page-tabs" style="width: 100%;">
                        <ul class="nav nav-tabs">
                            <!-- <li ><a data-toggle="tab" href="#tab1">PAYROLL ROW DATA</a></li> -->
                            <li class="active"><a href="<?php echo base_url(); ?>Pay/Payroll_Edit_Old" href="#tab1">OLD PAYROLL ROW DATA</a></li>
                        </ul>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="tab-content">
                        <!-- Grid View -->
                        <div class="tab-pane " id="tab1">
                            <script>
                                window.location.href = "<?php echo base_url(); ?>Pay/Payroll_Edit_Old";
                            </script>
                        </div>
                        <!-- End Grid View -->
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

    <!-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Attach event listener to all Edit buttons
                document.querySelectorAll('.edit-btn').forEach((button) => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr'); // Get the current row
                        const isEditing = row.classList.contains('editing');

                        if (!isEditing) {
                            // Switch to edit mode
                            row.classList.add('editing');
                            this.textContent = 'Save'; // Change button text

                            Array.from(row.cells).forEach((cell, index) => {
                                if (index < row.cells.length - 1) { // Exclude the button cell
                                    const value = cell.textContent.trim();
                                    cell.innerHTML = `<input type="text" value="${value}" style="width:100%;"/>`;
                                }
                            });
                        } else {
                            // Save changes
                            row.classList.remove('editing');
                            this.textContent = 'Edit'; // Change button text back

                            Array.from(row.cells).forEach((cell, index) => {
                                if (index < row.cells.length - 1) { // Exclude the button cell
                                    const input = cell.querySelector('input');
                                    if (input) {
                                        cell.textContent = input.value; // Update cell content
                                    }
                                }
                            });

                            // Optional: Send updated data to the server
                            const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());
                            // console.log('Updated Row Data:', rowData); // Log the updated row

                            // Uncomment and customize this block to send an AJAX request

                            fetch('<?php echo base_url(); ?>Pay/Payroll_Edit/edit_data', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ rowData }) // Pass the updated row data
                            })
                                .then(response => response.json()) // Parse the JSON response
                                .then(data => {
                                    // if (data.status === 'success') {
                                    //     console.log('Row updated successfully:', data);
                                    //     alert(data.data); // Optionally alert the user
                                    // } else {
                                    //     console.error('Error:', data.message);
                                    //     alert('Error updating row: ' + data.message);
                                    // }
                                    alert(data.status);
                                    // console.log(data.status);
                                    window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Edit');
                               
                                })
                                .catch(error => {
                                    console.error('Error updating row:', error);
                                    alert('An unexpected error occurred: ' + error.message);
                                });


                        }
                    });
                });
            });

        </script> -->
    <!-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Attach event listener to all Edit buttons
                document.querySelectorAll('.edit-btn').forEach((button) => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr'); // Get the current row
                        const isEditing = row.classList.contains('editing');

                        if (!isEditing) {
                            // Switch to edit mode
                            row.classList.add('editing');
                            this.textContent = 'Save'; // Change button text

                            Array.from(row.cells).forEach((cell, index) => {
                                if (index < row.cells.length - 1) { // Exclude the button cell
                                    const value = cell.textContent.trim();
                                    cell.innerHTML = `<input type="text" value="${value}" style="width:100%;"/>`;

                                    // Add live update event listener for Total_F_Epf and Gross_pay
                                    if (index === 3 || index === 4 || index === 5 || index === 7 || index === 8 || index === 9 || index === 10 || index === 11 || index === 12 || ) {
                                        const input = cell.querySelector('input');
                                        input.addEventListener('input', () => {
                                            liveUpdate(row);
                                        });
                                    }
                                }
                            });
                        } else {
                            // Save changes
                            row.classList.remove('editing');
                            this.textContent = 'Edit'; // Change button text back

                            Array.from(row.cells).forEach((cell, index) => {
                                if (index < row.cells.length - 1) { // Exclude the button cell
                                    const input = cell.querySelector('input');
                                    if (input) {
                                        cell.textContent = input.value; // Update cell content
                                    }
                                }
                            });

                            // Optional: Send updated data to the server
                            const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());
                            // Uncomment and customize this block to send an AJAX request
                            fetch('<?php echo base_url(); ?>Pay/Payroll_Edit/edit_data', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ rowData }) // Pass the updated row data
                            })
                                .then(response => response.json()) // Parse the JSON response
                                .then(data => {
                                    alert(data.status); // Optionally alert the user
                                    window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Edit');
                                })
                                .catch(error => {
                                    console.error('Error updating row:', error);
                                    alert('An unexpected error occurred: ' + error.message);
                                });
                        }
                    });
                });

                // Function to live update Total_F_Epf and Gross_pay
                function liveUpdate(row) {
                    const cells = Array.from(row.cells);
                    const basicSal = parseFloat(cells[3].querySelector('input').value.trim()) || 0;
                    const brPay = parseFloat(cells[4].querySelector('input').value.trim()) || 0;
                    const fixed = parseFloat(cells[5].querySelector('input').value.trim()) || 0;

                    // Update Total_F_Epf
                    const totalFEpfCell = cells[6];
                    const totalFEpfOriginal = parseFloat(totalFEpfCell.textContent.trim()) || 0;
                    totalFEpfCell.textContent = (basicSal + brPay + fixed).toFixed(2);

                    // Update Gross_pay
                    const performance = parseFloat(cells[7].querySelector('input').value.trim()) || 0;
                    const attendance = parseFloat(cells[8].querySelector('input').value.trim()) || 0;
                    const transport = parseFloat(cells[9].querySelector('input').value.trim()) || 0;
                    const fuel = parseFloat(cells[10].querySelector('input').value.trim()) || 0;
                    const traveling = parseFloat(cells[11].querySelector('input').value.trim()) || 0;
                    const spAllowance = parseFloat(cells[12].querySelector('input').value.trim()) || 0;

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
                    const Late_deduction = parseFloat(cells[14].querySelector('input').value.trim()) || 0;
                    const Ed_deduction = parseFloat(cells[15].querySelector('input').value.trim()) || 0;
                    const Salary_advance = parseFloat(cells[16].querySelector('input').value.trim()) || 0;
                    const no_pay_deduction = parseFloat(cells[17].querySelector('input').value.trim()) || 0;
                    const EPF_Worker_Amount = parseFloat(cells[18].querySelector('input').value.trim()) || 0;

                    const deductionCell = cells[19];
                    const deductionOriginal = parseFloat(deductionCell.textContent.trim()) || 0;
                    deductionCell.textContent = ( Late_deduction + Ed_deduction + Salary_advance + no_pay_deduction + EPF_Worker_Amount).toFixed; // Update DEDUCTION cell content

                }
            });

        </script> -->
    <!-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Attach event listener to all Edit buttons
                document.querySelectorAll('.edit-btn').forEach((button) => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr'); // Get the current row
                        const isEditing = row.classList.contains('editing');

                        if (!isEditing) {
                            // Switch to edit mode
                            row.classList.add('editing');
                            this.textContent = 'Save'; // Change button text

                            Array.from(row.cells).forEach((cell, index) => {
                                if (index < row.cells.length - 1) { // Exclude the button cell
                                    const value = cell.textContent.trim();
                                    cell.innerHTML = `<input type="text" value="${value}" style="width:100%;"/>`;

                                    // Add live update event listener for specific columns
                                    if ([3, 4, 5, 7, 8, 9, 10, 11, 12, 14, 15, 16, 17, 18].includes(index)) {
                                        const input = cell.querySelector('input');
                                        input.addEventListener('input', () => {
                                            liveUpdate(row);
                                        });
                                    }
                                }
                            });
                        } else {
                            // Save changes
                            row.classList.remove('editing');
                            this.textContent = 'Edit'; // Change button text back

                            Array.from(row.cells).forEach((cell, index) => {
                                if (index < row.cells.length - 1) { // Exclude the button cell
                                    const input = cell.querySelector('input');
                                    if (input) {
                                        cell.textContent = input.value; // Update cell content
                                    }
                                }
                            });

                            // Optional: Send updated data to the server
                            const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());
                            // Uncomment and customize this block to send an AJAX request
                            fetch('<?php echo base_url(); ?>Pay/Payroll_Edit/edit_data', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ rowData }) // Pass the updated row data
                            })
                                .then(response => response.json()) // Parse the JSON response
                                .then(data => {
                                    alert(data.status); // Optionally alert the user
                                    window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Edit');
                                })
                                .catch(error => {
                                    console.error('Error updating row:', error);
                                    alert('An unexpected error occurred: ' + error.message);
                                });
                        }
                    });
                });

                // Function to live update Total_F_Epf, Gross_pay, and DEDUCTION
                function liveUpdate(row) {
                    const cells = Array.from(row.cells);
                    const basicSal = parseFloat(cells[3].querySelector('input').value.trim()) || 0;
                    const brPay = parseFloat(cells[4].querySelector('input').value.trim()) || 0;
                    const fixed = parseFloat(cells[5].querySelector('input').value.trim()) || 0;

                    // Update Total_F_Epf
                    const totalFEpfCell = cells[6];
                    totalFEpfCell.textContent = (basicSal + brPay + fixed).toFixed(2);

                    // Update Gross_pay
                    const performance = parseFloat(cells[7].querySelector('input').value.trim()) || 0;
                    const attendance = parseFloat(cells[8].querySelector('input').value.trim()) || 0;
                    const transport = parseFloat(cells[9].querySelector('input').value.trim()) || 0;
                    const fuel = parseFloat(cells[10].querySelector('input').value.trim()) || 0;
                    const traveling = parseFloat(cells[11].querySelector('input').value.trim()) || 0;
                    const spAllowance = parseFloat(cells[12].querySelector('input').value.trim()) || 0;

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
                    const Late_deduction = parseFloat(cells[14].querySelector('input').value.trim()) || 0;
                    const Ed_deduction = parseFloat(cells[15].querySelector('input').value.trim()) || 0;
                    const Salary_advance = parseFloat(cells[16].querySelector('input').value.trim()) || 0;
                    const no_pay_deduction = parseFloat(cells[17].querySelector('input').value.trim()) || 0;
                    const EPF_Worker_Amount = parseFloat(cells[18].querySelector('input').value.trim()) || 0;

                    const deductionCell = cells[19];
                    deductionCell.textContent = (
                        Late_deduction +
                        Ed_deduction +
                        Salary_advance +
                        no_pay_deduction +
                        EPF_Worker_Amount
                    ).toFixed(2); // Ensure proper usage of .toFixed()

                    const D_Salary = parseFloat(cells[20].querySelector('input').value.trim()) || 0;
                    const Net_salary = parseFloat(cells[23].querySelector('input').value.trim()) || 0;

                }
            });
        </script> -->
    <!-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Attach event listener to all Edit buttons
                document.querySelectorAll('.edit-btn').forEach((button) => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr'); // Get the current row
                        const isEditing = row.classList.contains('editing');

                        if (!isEditing) {
                            // Switch to edit mode
                            row.classList.add('editing');
                            this.textContent = 'Save'; // Change button text

                            Array.from(row.cells).forEach((cell, index) => {
                                if (index < row.cells.length - 1) { // Exclude the button cell
                                    const value = cell.textContent.trim();
                                    cell.innerHTML = `<input type="text" value="${value}" style="width:100%;"/>`;

                                    // Add live update event listener for specific columns
                                    if ([3, 4, 5, 7, 8, 9, 10, 11, 12, 14, 15, 16, 17, 18].includes(index)) {
                                        const input = cell.querySelector('input');
                                        input.addEventListener('input', () => {
                                            liveUpdate(row);
                                        });
                                    }
                                }
                            });
                        } else {
                            // Save changes
                            row.classList.remove('editing');
                            this.textContent = 'Edit'; // Change button text back

                            Array.from(row.cells).forEach((cell, index) => {
                                if (index < row.cells.length - 1) { // Exclude the button cell
                                    const input = cell.querySelector('input');
                                    if (input) {
                                        cell.textContent = input.value; // Update cell content
                                    }
                                }
                            });

                            // Optional: Send updated data to the server
                            const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());
                            // Uncomment and customize this block to send an AJAX request
                            fetch('<?php echo base_url(); ?>Pay/Payroll_Edit/edit_data', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ rowData }) // Pass the updated row data
                            })
                                .then(response => response.json()) // Parse the JSON response
                                .then(data => {
                                    alert(data.status); // Optionally alert the user
                                    window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Edit');
                                })
                                .catch(error => {
                                    console.error('Error updating row:', error);
                                    alert('An unexpected error occurred: ' + error.message);
                                });
                        }
                    });
                });

                // Function to live update Total_F_Epf, Gross_pay, DEDUCTION, and Net Salary
                function liveUpdate(row) {
                    const cells = Array.from(row.cells);
                    const basicSal = parseFloat(cells[3].querySelector('input').value.trim()) || 0;
                    const brPay = parseFloat(cells[4].querySelector('input').value.trim()) || 0;
                    const fixed = parseFloat(cells[5].querySelector('input').value.trim()) || 0;

                    // Update Total_F_Epf
                    const totalFEpfCell = cells[6];
                    totalFEpfCell.textContent = (basicSal + brPay + fixed).toFixed(2);

                    // Update Gross_pay
                    const performance = parseFloat(cells[7].querySelector('input').value.trim()) || 0;
                    const attendance = parseFloat(cells[8].querySelector('input').value.trim()) || 0;
                    const transport = parseFloat(cells[9].querySelector('input').value.trim()) || 0;
                    const fuel = parseFloat(cells[10].querySelector('input').value.trim()) || 0;
                    const traveling = parseFloat(cells[11].querySelector('input').value.trim()) || 0;
                    const spAllowance = parseFloat(cells[12].querySelector('input').value.trim()) || 0;

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
                    const Late_deduction = parseFloat(cells[14].querySelector('input').value.trim()) || 0;
                    const Ed_deduction = parseFloat(cells[15].querySelector('input').value.trim()) || 0;
                    const Salary_advance = parseFloat(cells[16].querySelector('input').value.trim()) || 0;
                    const no_pay_deduction = parseFloat(cells[17].querySelector('input').value.trim()) || 0;
                    const EPF_Worker_Amount = parseFloat(cells[18].querySelector('input').value.trim()) || 0;

                    const deductionCell = cells[19];
                    deductionCell.textContent = (
                        Late_deduction +
                        Ed_deduction +
                        Salary_advance +
                        no_pay_deduction +
                        EPF_Worker_Amount
                    ).toFixed(2);

                    // Update Net Salary
                    const D_Salary = parseFloat(deductionCell.textContent) || 0;
                    const Net_salary = parseFloat(grossPayCell.textContent) - D_Salary;

                    const netSalaryCell = cells[20];
                    netSalaryCell.textContent = Net_salary.toFixed(2);

                    // const netSalaryCell = cells[23];
                    // netSalaryCell.textContent = Net_salary.toFixed(2);
                }
            });
        </script> -->

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Attach event listener to all Edit buttons
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
                            if (index < row.cells.length - 1 && ![0, 1, 2, 5, 13, 18, 19, 20, 21, 22].includes(index)) {
                                const value = cell.textContent.trim();
                                cell.innerHTML = `<input type="text" value="${value}" style="width:100%;"/>`;

                                // Add live update event listener for specific columns
                                if ([3, 4, 6, 7, 8, 9, 10, 11, 12, 14, 15, 16, 17].includes(index)) {
                                    const input = cell.querySelector('input');
                                    input.addEventListener('input', () => {
                                        liveUpdate(row);
                                    });
                                }
                            }
                        });
                    } else {
                        // Save changes
                        row.classList.remove('editing');
                        this.textContent = 'Edit'; // Change button text back

                        Array.from(row.cells).forEach((cell, index) => {
                            // Exclude the button cell and restricted cells by their index
                            if (index < row.cells.length - 1 && ![0, 1, 2, 5, 13, 18, 19, 20, 21, 22].includes(index)) {

                                const input = cell.querySelector('input');
                                if (input) {
                                    cell.textContent = input.value; // Update cell content
                                }
                            }
                        });

                        // Optional: Send updated data to the server
                        const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());
                        fetch('<?php echo base_url(); ?>Pay/Payroll_Edit/edit_data', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    rowData
                                }) // Pass the updated row data
                            })
                            .then(response => response.json()) // Parse the JSON response
                            .then(data => {
                                alert(data.status); // Optionally alert the user
                                window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Edit');
                            })
                            .catch(error => {
                                console.error('Error updating row:', error);
                                alert('An unexpected error occurred: ' + error.message);
                            });
                    }
                });
            });

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
                // const EPF_Worker_Amount = parseFloat(cells[19].querySelector('input')?.value.trim()) || 0;

                const deductionCell = cells[18];
                deductionCell.textContent = (
                    Late_deduction +
                    Ed_deduction +
                    Salary_advance +
                    no_pay_deduction
                    // EPF_Worker_Amount
                ).toFixed(2);

                // Update Net Salary
                const D_Salary = parseFloat(deductionCell.textContent) || 0;
                const Net_salary = parseFloat(grossPayCell.textContent) - D_Salary;

                const netSalaryCell = cells[20];
                netSalaryCell.textContent = Net_salary.toFixed(2);

                // const epfdata1 = parseFloat(cells[19].querySelector('input')?.value.trim()) || 0;
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
        });
    </script>
    <script>
        function searchEmployee() {
            // Get input values
            const empNo = document.getElementById("txt_emp").value.trim().toLowerCase();
            const empName = document.getElementById("txt_emp_name").value.trim().toLowerCase();
            // const month = document.getElementById("cmb_month").value;

            // Get table rows
            const rows = document.querySelectorAll("table tbody tr");

            rows.forEach((row) => {
                const empNoCell = row.cells[1]?.textContent.trim().toLowerCase();
                const empNameCell = row.cells[2]?.textContent.trim().toLowerCase();
                // const monthCell = row.cells[0]?.textContent.trim(); // Modify if month data exists elsewhere.

                // Show or hide rows based on search criteria
                const matchesEmpNo = empNo ? empNoCell.includes(empNo) : true;
                const matchesEmpName = empName ? empNameCell.includes(empName) : true;
                // const matchesMonth = month ? monthCell === month : true;

                if (matchesEmpNo && matchesEmpName) {
                    row.style.display = ""; // Show row
                } else {
                    row.style.display = "none"; // Hide row
                }
            });
        }
    </script>
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

</body>


</html>