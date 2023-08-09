<?php
include_once('../../Connections/connect.php');
include('./includes/taxpin_handler.php');


$row = $taxpin_object;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peleza Admin - iTax Verification</title>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../../assets/css/font-awesome.css" />
    <link rel="stylesheet" href="../../assets/css/ace-fonts.css" />
    <link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script src="../../assets/js/ace.js"></script>


    <script src="../../assets/js/markdown/markdown.js"></script>
    <script src="../../assets/js/markdown/bootstrap-markdown.js"></script>

    <script src="../../assets/js/ace-extra.js"></script>
</head>

<body class="no-skin">
    <div id="navbar" class="navbar navbar-default">
        <script type="text/javascript">
            try {
                ace.settings.check('navbar', 'fixed')
            } catch (e) {}
        </script>
        <?php include('../header2.php'); ?>
    </div>

    <div class="main-container" id="main-container">
        <script type="text/javascript">
            try {
                ace.settings.check('main-container', 'fixed')
            } catch (e) {}
        </script>

        <!-- #section:basics/sidebar -->
        <div id="sidebar" class="sidebar responsive">
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'fixed')
                } catch (e) {}
            </script>
            <?php include('../sidebarmenu2.php'); ?>


            <!-- #section:basics/sidebar.layout.minimize -->
            <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
            </div>

            <!-- /section:basics/sidebar.layout.minimize -->
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'collapsed')
                } catch (e) {}
            </script>

        </div>

        <!-- /section:basics/sidebar -->
        <div class="main-content">
            <div class="main-content-inner">
                <!-- #section:basics/content.breadcrumbs -->
                <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">
                        try {
                            ace.settings.check('breadcrumbs', 'fixed')
                        } catch (e) {}
                    </script>

                    <ul class="breadcrumb">
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="#">Home</a>
                        </li>

                        <li>
                            <a href="#">Peleza Modules</a>
                        </li>

                        <li>
                            <a href="#">Company</a>
                        </li>

                        <li class="active">Tax PiN Verification</li>
                    </ul>


                </div>
                <div class="page-content">
                    <?php include('./includes/request_display.php'); ?>


                </div>
                <?php include('./includes/taxpin.forms.php'); ?>



                <div class="col-lg-12" align="center">

                    <?php
                    if ($rows > 0) { ?>
                        <h3 align="left" class=" smaller lighter blue"><strong>Tax Payer Details: </strong>
                        </h3>
                        <table id="simple-table" class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>
                                        PIN
                                    </th>
                                    <th>
                                        Taxpayer Name
                                    </th>
                                    <th>
                                        PIN Status
                                    </th>
                                    <th>
                                        iTax Status
                                    </th>
                                    <th>
                                        PIN Status
                                    </th>
                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $row['pin'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['taxpayer_name'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['pin_status'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['itax_status'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['pin_status'] ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == '11') {
                                        ?>

                                            <span class="label label-sm label-success">Valid Data</span>
                                        <?php
                                        }
                                        if ($row['status'] == '00') {
                                        ?>
                                            <span class="label label-sm label-danger">Not Correct Data</span>
                                        <?php
                                        }
                                        if ($row['status'] == '22') {
                                        ?>
                                            <span class="label label-sm label-warning">Not Reviewed</span>
                                        <?php
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        if ($row['status'] == '11' || $row['status'] == '22') {
                                        ?>
                                            <a href="<?php echo $formAction ?>&setstatus=00&id=<?php echo $row['id'] ?>"> <button class="btn btn-xs btn-danger">
                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i> </button></a>
                                        <?php
                                        } elseif ($row['status'] == '00') {

                                        ?>
                                            <a href="<?php echo $formAction ?>&setstatus=22&id=<?php echo $row['id'] ?>"> <button class="btn btn-xs btn-success">
                                                    <i class="ace-icon fa fa-check bigger-120"></i> </button></a>
                                        <?php
                                        }
                                        ?>

                                        <button class="btn btn-xs btn-info" data-target="#edit-taxpayer-modal-<?php echo $row['id'] ?>" data-toggle="modal">
                                            <i class="ace-icon fa fa-pencil bigger-120"></i> </button></a>

                                        </button>
                                    </td>

                                    <div class="modal fade" id="edit-taxpayer-modal-<?php echo $row['id'] ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header no-padding">
                                                    <div class="table-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                            <span class="white">&times;</span>
                                                        </button>
                                                        Edit TaxPayer Details
                                                    </div>
                                                </div>
                                                <div class="modal-body padding">
                                                    <form action="<?php echo $formAction ?>" method="POST">
                                                        <input type="hidden" value="update_taxpayer" name="update_taxpayer">
                                                        <input type="hidden" value="<?php echo $row['id'] ?>" name="id" />

                                                        <label class="col-sm-4">PIN</label>
                                                        <div class="col-sm-7"><span id="sprytextfield1">
                                                                <input type="text" id="pin" name="pin" class="form-control" value="<?php echo $row['pin'] ?>" />
                                                                <span class="textfieldRequiredMsg">*</span></span></div>
                                                        <br />

                                                        <div class="space-10"></div>
                                                        <label class="col-sm-4">TaxPayer Name</label>
                                                        <div class="col-sm-7"><span id="sprytextfield2">
                                                                <input type="text" id="taxpayer_name" name="taxpayer_name" class="form-control" value="<?php echo $row['taxpayer_name'] ?>" />
                                                                <span class="textfieldRequiredMsg">*</span></span></div>
                                                        <br />

                                                        <div class="space-10"></div>

                                                        <label class="col-sm-4">PIN Status</label>
                                                        <div class="col-sm-7"><span id="sprytextfield3">
                                                                <select class="form-control" name="pin_status" id="pin_status">
                                                                    <option disabled selected value="">Select PIN Status</option>
                                                                    <?php echo $row['pin_status'] ? '' : '' ?>
                                                                    <option value="active" <?php echo $row['pin_status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                                    <option value="inactive" <?php echo $row['pin_status'] ? 'inactive' : 'selected' ?>>Inactive</option>
                                                                </select>
                                                                <span class="textfieldRequiredMsg">*</span></span></div>
                                                        <br />

                                                        <div class="space-10"></div>

                                                        <label class="col-sm-4">iTax Status</label>
                                                        <div class="col-sm-7"><span id="sprytextfield4">
                                                                <select class="form-control" name="itax_status" id="itax_status" required>
                                                                    <option disabled selected value="">Select iTax Status</option>
                                                                    <option value="registered" <?php echo $row['itax_status'] == 'registered' ? 'selected' : '' ?>>Registered</option>
                                                                    <option value="not registered" <?php echo $row['itax_status'] == 'not registered' ? 'selected' : '' ?>>Not Registered</option>
                                                                </select>
                                                                <span class="textfieldRequiredMsg">*</span></span></div>
                                                        <br />

                                                        <div class="space-10"></div>


                                                        <div class="clearfix form-actions">
                                                            <div class="col-md-offset-3 col-md-9">
                                                                <button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
                                                                    <!--<button onClick="submit" class="btn btn-info" type="button">-->
                                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                                    Save
                                                                </button>

                                                                   
                                                                <button class="btn" type="reset">
                                                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                    Reset </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>

                            </tbody>
                        </table>
                    <?php } ?>
                    <?php if ($rows > 0 && $obligation_rows) {
                    ?>



                        <h3 align="left" class=" smaller lighter blue"><strong>Obligation Details: </strong>
                        </h3>
                        <table id="simple-table" class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>
                                        Obligation Name
                                    </th>
                                    <th>
                                        Current Status
                                    </th>
                                    <th>
                                        Effective From
                                    </th>
                                    <th>
                                        Effective To
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                while ($row1 = $tax_obligation_sql_result->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $row1['obligation_name'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row1['current_status'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row1['effective_from'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row1['effective_to'] ?>
                                        </td>
                                    </tr>


                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>


                    <?php if ($row['review_status']) {
                    ?>
                        <h3 align="left" class=" smaller lighter blue"><strong>Review Notes: </strong>
                        </h3>
                        <table id="simple-table" class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>
                                        Review Notes
                                    </th>
                                    <th>
                                        Reviewed By
                                    </th>
                                    <th>
                                        Reviewed On
                                    </th>
                                    <th>
                                        Review Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $row['review_notes'] ?>

                                    </td>
                                    <td>
                                        <?php echo $row['verified_by'] ?>

                                    </td>
                                    <td>
                                        <?php echo $row['verified_date'] ?>

                                    </td>
                                    <td>
                                        <?php
                                        $status =  $row['review_status'];
                                        if ($status == '' || $status == NULL) {
                                            echo '<span class="label label-sm label-warning">Not Reviewed</span';
                                        } elseif ($status == 'APPROVED') {
                                            echo '<span class="label label-sm label-success">APPROVED</span>';
                                        } elseif ($status == 'REJECTED') {
                                            echo '<span class="label label-sm label-danger">Rejected</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php
                    }
                    ?>



                    <!--  -->
                    <div style="display: flex;align-items:center; justify-content:center;">

                        <!-- CREATE IF CONDITION HERE -->
                        <?php
                        // echo $row['status'];
                        if ($rows > 0) { ?>
                            <?php if ($obligation_rows > 0) { ?>
                                <form action="<?php $formAction ?>" method="POST">
                                    <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
                                    <input type="hidden" name="approve" value="true">
                                    <button type="submit" class="btn btn-xs btn-success"><i class="ace-icon fa fa-check bigger-120">Approve</i></button>
                                </form>
                            <?php } ?>

                            <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a data-target="#modal-addreject" role="button" class="blue" data-toggle="modal">
                                <button class="btn btn-xs btn-danger"> Reject With Reason </button></a>


                            <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
                            <button class="btn btn-xs btn-primary btn-outline" data-toggle='modal' data-target='#add-obligation-modal'>
                                <i class="ace-icon smaller-80 green"></i>Add Obligation Details
                            </button>;
                        <?php } else { ?>
                            <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
                            <button class="btn btn-xs btn-primary" data-toggle='modal' data-target='#add-taxpayer-modal'>
                                <i class="ace-icon smaller-80 green"></i>Add TaxPayer Details
                            </button>;
                        <?php } ?>

                    </div>
                </div>
            </div>

        </div>
</body>
<style>
    .btn-outline:hover,
    .btn-outline:focus {
        background-color: transparent !important;
    }

    .btn-outline {
        border-color: #0a4157 !important;
        color: #0a4157 !important;
        background-color: transparent !important;
    }
</style>

</html>