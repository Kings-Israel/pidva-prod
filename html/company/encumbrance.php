<?php
include_once('../../Connections/connect.php');
include('./includes/encumbrance_handler.php');

$getencumbrance_sql = sprintf("SELECT * FROM pel_company_encumbrances WHERE search_id=$ref_number");

$getencumbrance_sql_result = $connect->query($getencumbrance_sql);
// $encumbrance_objects = ;

global $encumbrance_objects;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peleza Admin - Company Encumbrance</title>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../../assets/css/font-awesome.css" />
    <link rel="stylesheet" href="../../assets/css/ace-fonts.css" />
    <link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script src="../../assets/js/ace.js"></script>
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
        <div id="sidebar" class="sidebar                  responsive">
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

                        <li class="active">Company Encumbrance Check Details</li>
                    </ul>


                </div>
                <div class="page-content">
                    <?php include('./includes/request_display.php') ?>


                </div>
                <?php include('./includes/encumbrance.forms.php'); ?>



                <?php
                $rows = $getencumbrance_sql_result->num_rows;
                if ($rows > 0) {
                ?>
                    <div class="col-lg-12" align="center">
                        <h3 align="left" class=" smaller lighter blue"><strong>COMPANY ENCUMBRANCE DETAILS: </strong>
                        </h3>

                        <table id="simple-table" class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>
                                        Amount Secured
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Description
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
                                <?php while ($row = $getencumbrance_sql_result->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['amount_secured'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['date'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['description'] ?>
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

                                            <button class="btn btn-xs btn-info" data-target="#edit-encumbrance-modal-<?php echo $row['id'] ?>" data-toggle="modal">
                                                <i class="ace-icon fa fa-pencil bigger-120"></i> </button></a>

                                            </button>
                                        </td>

                                        <div class="modal fade" tabindex="-1" role="dialog" id="edit-encumbrance-modal-<?php echo $row['id'] ?>">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header no-padding">
                                                        <div class="table-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                <span class="white">&times;</span>
                                                            </button>
                                                            Add Encumbrance Details
                                                        </div>
                                                    </div>
                                                    <div class="modal-body padding">
                                                        <form action="<?php echo $formAction ?>" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                            <input type="hidden" name="updateencumbrance" value="updateencumbrance">

                                                            <label class="col-sm-4">Amount Secured</label>
                                                            <div class="col-sm-7"><span id="sprytextfield7">
                                                                    <input type=" text" id="data" name="amount_secured" class="form-control" value="<?php echo $row['amount_secured'] ?>" />
                                                                    <span class="textfieldRequiredMsg">*</span></span></div>
                                                            <br />
                                                            <div class="space-10"></div>
                                                            <label class="col-sm-4">Date</label>
                                                            <div class="col-sm-7"><span id="sprytextfield7">
                                                                    <input type=" text" id="date" name="date" class="form-control" value="<?php echo $row['date'] ?>"" />
                                                                <span class=" textfieldRequiredMsg">*</span></span></div>
                                                            <br />
                                                            <div class="space-10"></div>
                                                            <label class="col-sm-4">Description</label>
                                                            <div class="col-sm-7 form-group"><span id="sprytextfield7">
                                                                    <textarea name="description" id="" data-provide="markdown" data-iconlibrary="fa" rows="10" class="form-control"><?php echo $row['description'] ?></textarea>
                                                                    <span class="textfieldRequiredMsg">*</span>
                                                                </span></div>
                                                            <br />
                                                            <div class="space-10"></div>

                                                            <div class="col-md-9">
                                                                <button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
                                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                                    Save
                                                                </button>

                                                                   
                                                                <button class="btn" type="reset">
                                                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                    Reset
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer no-margin-top">
                                                        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                                                            <i class="ace-icon fa fa-times"></i>
                                                            Close
                                                        </button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                <div style="display: flex; width:100%;align-items:center">
                    <form action="<?php $formAction ?>" method="POST">
                        <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
                        <input type="hidden" name="approve" value="true">
                        <button type="submit" class="btn btn-xs btn-success"><i class="ace-icon fa fa-check bigger-120">Approve</i></button>
                    </form>
                    <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i><a data-target="#modal-addreject" role="button" class="blue" data-toggle="modal">
                        <button class="btn btn-xs btn-danger"> Reject With Reason </button></a>

                    <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
                    <button class="btn btn-xs btn-primary" data-toggle='modal' data-target='#add-encumbrance-modal'>
                        <i class="ace-icon smaller-80 green"></i>Add Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>