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

            <!--dashboard side-->

            <?php $this->load->view('template/dashboard_side.php'); ?>

            <!--dashboard side end-->

            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb">

                            <li class=""><a href="index.html">HOME</a></li>
                            <li class="active"><a href="index.html">COMPANY PROFILE</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">

                                <li class="active"><a data-toggle="tab" href="#tab1">COMPANY PROFILE</a></li>
                                <!-- <li><a data-toggle="tab" href="#tab2">COMPANY PROFILE</a></li> -->


                            </ul>
                        </div>
                        <div class="container-fluid">


                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="row">
                                        <div class="col-xs-12">

                                            <img src=""
                                                <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <h2>ADD COMPANY PROFILE</h2>
                                                    </div>
                                                    <div class="panel-body">

                                                        <form class="form-horizontal" name="frm_comp_pro" action="<?php echo base_url(); ?>Company_Profile/Company_Profile/insert_or_update_Data" method="POST" enctype="multipart/form-data">
                                                            <!--success Message-->
                                                            <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                <div id="spnmessage" class="alert alert-dismissable alert-success success_redirect">
                                                                    <strong>Success !</strong> <?php echo $_SESSION['success_message'] ?>
                                                                </div>
                                                            <?php } ?>

                                                            <!--Error Message-->
                                                            <?php if (isset($_SESSION['error_message']) && $_SESSION['error_message'] != '') { ?>
                                                                <div id="spnmessage" class="alert alert-dismissable alert-danger error_redirect">
                                                                    <strong>Error !</strong> <?php echo $_SESSION['error_message'] ?>
                                                                </div>
                                                            <?php } ?>

                                                            <div class="form-group col-sm-12">
                                                                <div class="col-sm-8">
                                                                    <img class="imagecss" src="<?php echo base_url(); ?>assets/images/company.png">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="focusedinput" class="col-sm-4 control-label">Company Name</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" value="<?php if (isset($data_set[0]->Company_Name)) {
                                                                                                    echo $data_set[0]->Company_Name;
                                                                                                } ?>" class="form-control" required="" name="txt_comp_name" id="txt_comp_name" placeholder="Ex: ABC Company (PVT) Ltd.">
                                                                </div>

                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label for="focusedinput" class="col-sm-4 control-label">Company Address</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" value="<?php if (isset($data_set[0]->Company_Name)) {
                                                                                                                        echo $data_set[0]->comp_Address;
                                                                                                                    } ?>" required="" name="txt_comp_ad" id="txt_comp_ad" placeholder="Ex: No:123, Blank Road, Country">
                                                                </div>

                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="focusedinput" class="col-sm-4 control-label">Company Telephone</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" value="<?php if (isset($data_set[0]->Company_Name)) {
                                                                                                                        echo $data_set[0]->comp_Tel;
                                                                                                                    } ?>" required="" name="txt_comp_tel" id="txt_comp_tel" placeholder="Ex: 0112242321">
                                                                </div>

                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label for="focusedinput" class="col-sm-4 control-label">Company E Mail</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" name="txt_comp_email" value="<?php if (isset($data_set[0]->Company_Name)) {
                                                                                                                                                echo $data_set[0]->comp_Email;
                                                                                                                                            } ?>" id="txt_comp_email" placeholder="Ex: info@abc.com">
                                                                </div>

                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="focusedinput" class="col-sm-4 control-label">Company Web</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" name="txt_comp_web" value="<?php if (isset($data_set[0]->Company_Name)) {
                                                                                                                                            echo $data_set[0]->comp_web;
                                                                                                                                        } ?>" id="txt_comp_web" placeholder="www.company.com">
                                                                </div>

                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label for="focusedinput" class="col-sm-4 control-label">Company Business Registration</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" value="<?php if (isset($data_set[0]->Company_Name)) {
                                                                                                                        echo $data_set[0]->comp_reg_no;
                                                                                                                    } ?>" name="txt_comp_reg" id="txt_comp_reg" placeholder="Ex: ">
                                                                </div>

                                                            </div>

                                                            <div class="form-group col-sm-12">
                                                                <div class="col-sm-8">
                                                                    <!-- Display existing logo -->
                                                                    <img class="imagecss" id="current_logo"
                                                                        src="<?php echo !empty($data_set[0]->comp_logo) ? base_url() . $data_set[0]->comp_logo : base_url() . 'assets/images/company.png'; ?>"
                                                                        alt="Company Logo" style="max-width: 100%; margin-bottom: 10px; cursor: pointer;"
                                                                        onclick="triggerFileInput()">

                                                                    <!-- Hidden file input -->
                                                                    <input type="file" id="file_input" name="txt_comp_logo" accept="image/*" style="display: none;" onchange="previewImage(event)">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-8 col-sm-offset-2">
                                                                    <button type="submit" id="submit" class="btn-primary btn fa fa-check">&nbsp;&nbsp;SUBMIT</button>
                                                                    <button type="button" id="Cancel" name="Cancel" class="btn btn-danger-alt fa fa-times-circle">&nbsp;&nbsp;CANCEL</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                        <hr>

                                                        <div id="divmessage" class="">

                                                            <div id="spnmessage"> </div>
                                                        </div>


                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                    </div>
                                </div>


                                <!--***************************-->
                                <!-- Grid View -->
                                

                                <!-- End Grid View-->

                                <!-- Modal -->
                                



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
        <script src="<?php echo base_url(); ?>system_js/Company_Profile/profile.js"></script>
        <script>
            // Trigger file input when the image is clicked
            function triggerFileInput() {
                document.getElementById('file_input').click();
            }

            // Preview the selected image in real-time
            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('current_logo').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        </script>
        <script>
            $("#success_message_my").hide("bounce", 2000, 'fast');


            $("#submit").click(function() {
                $('#search_body').html('<center><p><img style="width: 50;height: 50;" src="<?php echo base_url(); ?>assets/images/processing.gif" /></p><center>');

            });
        </script>


</body>


</html>