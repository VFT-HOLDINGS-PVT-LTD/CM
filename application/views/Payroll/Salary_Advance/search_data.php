
<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->
<?php
if (!empty($data_set)) {
?>
    <div class="panel panel-primary">
        <div class="panel panel-default">
            <div class="panel-body panel-no-padding">
                <button type="button" class='get_data btn btn-primary'
                                                        onclick="handleApproveAll()">Approve All Selected</button>
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>ID</th>
                            <th>EMP NO</th>
                            <th>NAME</th>

                            <th>YEAR</th>
                            <th>MONTH</th>
                            <th>AMOUNT</th>
                            <th>REQUEST DATE</th>
                            <th>IS APPROVE</th>

                            <th>APPROVE</th>
                            <th>REJECT</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data_set as $data) {



                            echo "<tr class='odd gradeX'>";
                            echo "<td width='100'><input type='checkbox' class='select-item' value=". $data->id ."></td>";
                            echo "<td width='100'>" . $data->id . "</td>";
                            echo "<td width='100'>" . $data->EmpNo . "</td>";
                            echo "<td width='100'>" . $data->Emp_Full_Name . "</td>";

                            echo "<td width='100'>" . $data->Year . "</td>";
                            echo "<td width='100'>" . $data->Month . "</td>";
                            echo "<td width='100'>" . $data->Amount . "</td>";
                            echo "<td width='100'>" . $data->Request_Date . "</td>";
                            echo "<td width='100'>" . $data->Is_Approve . "</td>";





                            echo "<td width='15'>";
                            echo "<a class='get_data btn btn-primary' href='" . base_url() . "Pay/Salary_Advance/approve/" . $data->id . "'>APPROVE<i class=''></i> </a>";
                            echo "</td>";

                            echo "<td width='15'>";
                            echo "<a class='get_data btn btn-danger' href='" . base_url() . "Pay/Salary_Advance/reject/" . $data->id . "'>REJECT<i class=''></i> </a>";
                            echo "</td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="panel-footer"></div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h2 class="modal-title">EDIT ALLOWANCE</h2>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="<?php echo base_url(); ?>Payroll/Allowance/edit" method="post">
                        <div class="form-group col-sm-12">
                            <label for="focusedinput" class="col-sm-4 control-label">ID</label>
                            <div class="col-sm-8">
                                <input value="<?php echo $data->ID; ?>" type="text" class="form-control" readonly="readonly" name="id" id="id" class="m-wrap span3">
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="focusedinput" class="col-sm-4 control-label">NAME</label>
                            <div class="col-sm-8">
                                <input value="<?php echo $data->Emp_Full_Name; ?>" type="text" name="Name" id="Name" class="form-control m-wrap span6"><br>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="focusedinput" class="col-sm-4 control-label">ALLOWANCE NAME</label>
                            <div class="col-sm-8">
                                <input value="<?php echo $data->Allowance_name; ?>" type="text" name="allowance" id="allowance" class="form-control m-wrap span6"><br>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="focusedinput" class="col-sm-4 control-label">AMOUNT</label>
                            <div class="col-sm-8">
                                <input value="<?php echo $data->Amount; ?>" type="text" name="amount" id="amount" class="form-control m-wrap span6"><br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>

                </div>

                <br>
                <!--<input class="btn green" type="submit" value="submit" id="submit">-->

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
<?php
} else {
?>
    <div style="color: red; font-size: 16px; font-weight: bold; text-align: center; margin-top: 20px;">
        No data available!
    </div>
<?php
}
?>


<script type="text/javascript">
    function delete_id(id) {
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Delete This!",
                cancelButtonText: "No, Cancel This!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {

                    $.ajax({
                        url: baseurl + "index.php/Payroll/Loan_Entry/ajax_delete/" + id,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data) {

                            //if success reload ajax table
                            $('#modal_form').modal('hide');
                            reload_table();
                        }

                    });


                    swal("Deleted!", "Selected data has been deleted.", "success");


                    $(document).ready(function() {
                        setTimeout(function() {
                            window.location.replace(baseurl + "Payroll/Loan_Entry/");
                        }, 1000);
                    });


                } else {
                    swal("Cancelled", "Selected data Cancelled", "error");

                }

            });

    }

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
                form.action = '<?php echo base_url(); ?>Pay/Salary_Advance/approveAll';

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