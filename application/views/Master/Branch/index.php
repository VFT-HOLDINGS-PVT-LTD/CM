<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->




<html lang="en">


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

                <!--dashboard side-->

                <?php $this->load->view('template/dashboard_side.php'); ?>

                <!--dashboard side end-->

                <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <ol class="breadcrumb">

                                <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                                <li class="active"><a href="<?php echo base_url(); ?>Master/Branch/">ADD BRANCH</a></li>

                            </ol>


                            <div class="page-tabs">
                                <ul class="nav nav-tabs">

                                    <li class="active"><a data-toggle="tab" href="#tab1">ADD BRANCH</a></li>
                                    <li><a data-toggle="tab" href="#tab2">VIEW BRANCH</a></li>
                                    

                                </ul>
                            </div>
                            <div class="container-fluid">


                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab1">

                                        <div class="row">
                                            <div class="col-xs-12">


                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-primary">
                                                            <div class="panel-heading"><h2>ADD BRANCH</h2></div>
                                                            <div class="panel-body">
                                                                <form action="<?php echo base_url(); ?>index.php/Master/Branch/insert_branch" class="form-horizontal" id="frmBranches" name="frmBranches" method="POST">
                                                                    
                                                                    
                                                                    <!--success Message-->
                                                                    <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                        <div id="spnmessage" class="alert alert-dismissable alert-success">
                                                                            <strong>Success !</strong> <?php echo $_SESSION['success_message'] ?>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <!--Error Message-->
                                                                    <?php if (isset($_SESSION['error_message']) && $_SESSION['error_message'] != '') { ?>
                                                                        <div id="spnmessage" class="alert alert-dismissable alert-danger error_redirect">
                                                                            <strong>Error !</strong> <?php echo $_SESSION['error_message'] ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    
                                                                    
                                                                    
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Branch Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="txt_B_name" name="txt_B_name" placeholder="Ex: Maharagama">
                                                                        </div>

                                                                    </div>



                                                                    <div class="form-group col-md-6">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Address</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="txt_address" name="txt_address" placeholder="Ex:No: 12, Dehiwala Road, Maharagama.">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Telephone</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="txt_tp" name="txt_tp" placeholder="Ex: 0112840392">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Mobile</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="txt_mobile" name="txt_mobile" placeholder="Ex: 0752840392">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Fax</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="txt_fax" name="txt_fax" placeholder="Ex:0112840392">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">E Mail</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="txt_Email" name="txt_Email" placeholder="Ex: dedigamagroup@yahoo.com">
                                                                        </div>
                                                                    </div>



                                                                    <!--submit button-->
                                                                    <?php $this->load->view('template/btn_submit.php'); ?>
                                                                    <!--end submit-->



                                                                </form>

                                                                <hr>

                                                                <div id="divmessage" class="">
                                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                                    <div id="spnmessage"> </div>

                                                                </div>


                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane" id="tab2">

                                          <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-primary">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h2>VIEW BRANCHES</h2>
                                                                <div class="panel-ctrls">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body panel-no-padding">
                                                                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Code</th>
                                                                            <th>Name</th>
                                                                            <th>Address</th>
                                                                            <th>Telephone</th>
                                                                            <th>Mobile</th>
                                                                            <th>Fax</th>
                                                                            <th>E Mail</th>
                                                                            <th>Edit</th>
                                                                            <!--<th>Delete</th>-->
                                                                            

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        
                                                                        
                                                                        
                                                                        foreach ($data_array as $data) {


                                                                            echo "<tr class='odd gradeX'>";
//                                                                                  echo "<td width='5'><input type='checkbox' class='checkboxes' value='1' /></td>";

                                                                            echo "<td width='100'>" . $data->B_id . "</td>";
                                                                            echo "<td width='100'>" . $data->B_name . "</td>";
                                                                            echo "<td width='100'>" . $data->Address . "</td>";
                                                                            echo "<td width='100'>" . $data->Tel1 . "</td>";
                                                                            echo "<td width='100'>" . $data->Tel2 . "</td>";
                                                                            echo "<td width='100'>" . $data->Fax . "</td>";
                                                                            echo "<td width='100'>" . $data->Email . "</td>";



//                                                                                    echo "<td width='15'>";
//                                                                                        echo "<a class='edit_button_Dep' data-toggle='modal' data-target='#myModal' data-id='$userItem->Ref_No' href='".base_url()."index.php/Department/department_details".$userItem->Ref_No."'><i class='icon-edit'></i></a>";
//                                                                                        //echo "<a class='edit_button_designation' data-toggle='modal' data-target='#myModal' data-id='$userItem->Desig_Code' href='".base_url()."index.php/Designation/designation_details".$userItem->Desig_Code."' ><i class='icon-edit'></i></a>";
//                                                                                        echo  "</td>";

                                                                            echo "<td width='15'>";
                                                                            echo "<a class='get_data btn btn-green' data-toggle='modal' data-target='#myModal' data-id='$data->B_id' href='" . base_url() . "index.php/Master/Branch/branch_details" . $data->B_id . "'><i class='fa fa-edit'></i></a>";
                                                                            echo "</td>";

//                                                                            echo "<td width='15'>";
//                                                                            echo "<a class='action_comp' data-toggle='modal' data-target='#myModal' data-id='$data->id' href='" . base_url() . "index.php/Action_Complain/action" . $data->id . "'><i class='fa fa-edit'></i></a>";

//                                                                            echo "<a class='action_comp btn btn-danger' data-toggle='modal' href='javascript:void()' title='DELETE' onclick='delete_id($data->B_id)'><i class='fa fa-times-circle'></i></a>";


//                                                                            echo "</td>";



                                                                            echo "</tr>";
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                                <div class="panel-footer"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                
                                
                                 <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h2 class="modal-title">Branch</h2>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="<?php echo base_url(); ?>index.php/Master/Branch/edit" method="post">
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Code</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->B_id; ?>" type="text" class="form-control" readonly="readonly" name="id" id="id" class="m-wrap span3" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Name</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->B_name; ?>" type="text" name="B_name" id="B_name"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Address</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->Address; ?>" type="text" name="Address" id="Address"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Telephone</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->Tel1; ?>" type="text" name="TelNo" id="TelNo"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Mobile</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->Tel2; ?>" type="text" name="TelNo1" id="TelNo1"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Fax</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->Fax; ?>" type="text" name="FaxNo" id="FaxNo"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">E Mail</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->Email; ?>" type="text" name="Email" id="Email"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>

                                                    

                                                    <br>
                                                    <!--<input class="btn green" type="submit" value="submit" id="submit">-->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                            
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                            </div> <!-- .container-fluid -->
                        </div>

                        <!--Footer-->
                        <?php $this->load->view('template/footer.php'); ?>
                        <!--End Footer-->

                    </div>
                </div>
            </div>




            <!-- Load site level scripts -->

            <?php $this->load->view('template/js.php'); ?>							<!-- Initialize scripts for this page-->

            <!-- End loading page level scripts-->

            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="<?php echo base_url(); ?>system_js/Master/Branch.js"></script>
        <script>
            var baseurl = "<?= base_url(); ?>";

            function confirmDelete(id) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this data!",
                    icon: "warning",
                    buttons: {
                        cancel: "No, Cancel This!",
                        confirm: "Yes, Delete This!"
                    },
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: baseurl + "index.php/Master/Branch/ajax_delete/" + encodeURIComponent(id),
                            type: "POST",
                            dataType: "JSON",
                            success: function(data) {
                                if (data.status) {
                                    swal("Deleted!", data.message, "success");
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    swal("Not Allowed", data.message, "error");
                                }
                            },
                            error: function() {
                                swal("Error", "There was an error deleting the data.", "error");
                            }
                        });
                    } else {
                        swal("Cancelled", "Selected data was not deleted.", "error");
                    }
                });
            }
        </script>
            
            <!--JQuary Validation-->
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#frm_designation").validate();
                    $("#spnmessage").hide("shake", {times: 6}, 3000);
                });
            </script>

    </body>


</html>