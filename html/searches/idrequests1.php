<?php require_once('../../Connections/connect.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "assignform")) {

    $updateSQL = sprintf("UPDATE pel_psmt_request SET verification_status=%s, user_id=%s, user_name=%s, user_lock=%s, user_lock_date=%s WHERE request_id=%s",
        GetSQLValueString($_POST['verification_status'], "text"),
        GetSQLValueString($_POST['user_id'], "text"),
        GetSQLValueString($_POST['user_name'], "text"),
        GetSQLValueString($_POST['user_lock'], "text"),
        GetSQLValueString($_POST['user_lock_date'], "text"),
        GetSQLValueString($_POST['ID'], "int"));

    mysqli_select_db($connect, $database_connect);
    $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));

    $updateGoTo = "psmt_requests.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_POST['request_ref_number'])) {

    if (is_uploaded_file($_FILES['id-input-file-2']['tmp_name'])) {

        date_default_timezone_set('Africa/Nairobi');

        $USR_ID = $_SESSION['MM_USR_ID'];
        $date_insert2 = date('dmYhis');

        $ext = strtolower(end(explode('.', $_FILES['id-input-file-2']['name'])));

        $a = $USR_ID . "_" . $date_insert2;
        "Upload: " . $a . "_" . $_FILES["id-input-file-2"]["name"];
        $rawname = $_FILES['id-input-file-2']['name'];
        $file = "reportfiles/" . $a . "_" . $_FILES["id-input-file-2"]["name"];
        move_uploaded_file($_FILES["id-input-file-2"]["tmp_name"],

            "reportfiles/" . $a . "_" . $_FILES["id-input-file-2"]["name"]);


        $filenameuploaded = $a . "_" . $_FILES["id-input-file-2"]["name"];


        $client_id = $_POST['client_id'];
        $bg_dataset_name = $_POST['bg_dataset_name'];
        $request_plan = $_POST['request_plan'];
        $request_ref_number = $_POST['request_ref_number'];


        $query_getclient = "SELECT * FROM pel_client WHERE client_id='$client_id'";
        $getclient = mysqli_query_ported($query_getclient, $connect) or die(mysqli_error($connect));
        $row_getclient = mysqli_fetch_assoc($getclient);
        $totalRows_getclient = mysqli_num_rows($getclient);


        $clientname = $row_getclient['client_first_name'];
        $toemail = $row_getclient['client_email_address'];

        require("../../PHPMailer/PHPMailer.php");

        require("../../PHPMailer/SMTP.php");
        require("../../PHPMailer/Exception.php");

//$mail = new PHPMailer;
        $mail = new PHPMailer();

        $mail->isSMTP();
//$mail->isMail();                          // Set mailer to use SMTP
        $mail->Host = 'two.deepafrica.com';             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = 'verify@edcheckafrica.com';          // SMTP username
        $mail->Password = 'vkr67XpjsBnVkKK5'; // SMTP password
        $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                          // TCP port to connect to

        $mail->setFrom('verify@edcheckafrica.com', 'Backgorund Checks>> Peleza International');

        $mail->addAddress($toemail);
//$mail->addAddress('omintolbert@gmail.com');   // Add a recipient
//$mail->addCC('mwendemarita@gmail.com');
//$mail->addBCC('omintolbert@gmail.com');

        $mail->isHTML(true);  // Set email format to HTML

        $bodyContent = '<p><img src="https://admin.psmt.pidva.africa/assets/images/PelezaLogo.png" width="166" height="60" /></p>
<p><strong>Hi ' . $clientname . ',</strong></p>
<p>The background search report for  Tolbert Derek Omini has been uploaded kindly login to view it.</p>
<p><strong><a href="https://admin.psmt.pidva.africa">LOGIN</a></strong></p>
<p>  - The Peleza Team<br />
  Support: +254 796 111 020 or +254  Email:&nbsp;<a href="mailto:verify@peleza.com">verify@peleza.com</a>&nbsp;<br />
  ® Peleza Int, 2018. All rights reserved. </p>';
//$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

        $mail->Subject = 'Confidential Background Check Report';
        $mail->Body = $bodyContent;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            // echo 'Message has been sent'; 

            $updateSQL = sprintf("UPDATE pel_psmt_request SET verification_status=%s, notify_by=%s, notify_date=%s, report_file=%s  WHERE request_id=%s",
                GetSQLValueString($_POST['verification_status'], "text"),
                GetSQLValueString($_POST['notify_by'], "text"),
                GetSQLValueString($_POST['notify_date'], "text"),
                GetSQLValueString($filenameuploaded, "text"),
                GetSQLValueString($_POST['ID'], "int"));

            mysqli_select_db($connect, $database_connect);
            $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));

            $updateGoTo = "psmt_requests.php";
            if (isset($_SERVER['QUERY_STRING'])) {
                $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
                $updateGoTo .= $_SERVER['QUERY_STRING'];
            }
            header(sprintf("Location: %s", $updateGoTo));
        }
    }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

?><!DOCTYPE html>
<html lang="en">
<style type="text/css">
    <!--
    -->
</style>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <title>Psmt Requests - Peleza Admin</title>

    <meta name="description" content="Static &amp; Dynamic Tables"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../assets/css/font-awesome.css"/>

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="../../assets/css/ace-fonts.css"/>

    <!-- ace styles -->
    <link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style"/>

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet"/>
    <![endif]-->

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../assets/css/ace-ie.css"/>
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="../../assets/js/ace-extra.js"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="../assets/js/html5shiv.js"></script>
    <script src="../assets/js/respond.js"></script>
    <![endif]-->
</head>

<body class="no-skin">
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar-default">
    <script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
    </script>
    <?php include('../header2.php'); ?>
</div>

<!-- /section:basics/navbar.layout -->
<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

    <!-- #section:basics/sidebar -->
    <div id="sidebar" class="sidebar                  responsive">
        <script type="text/javascript">
            try {
                ace.settings.check('sidebar', 'fixed')
            } catch (e) {
            }
        </script>
        <?php include('../sidebarmenu2.php'); ?>


        <!-- #section:basics/sidebar.layout.minimize -->
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left"
               data-icon2="ace-icon fa fa-angle-double-right"></i></div>

        <!-- /section:basics/sidebar.layout.minimize -->
        <script type="text/javascript">
            try {
                ace.settings.check('sidebar', 'collapsed')
            } catch (e) {
            }
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
                    } catch (e) {
                    }
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a></li>

                    <li>
                        <a href="#">Pleza Searches</a></li>

                    <li class="active">ID Requests</li>
                </ul><!-- /.breadcrumb -->

                <!-- #section:basics/content.searchbox -->
                <div class="nav-search" id="nav-search">
                    <!-- <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>								</span>
                    </form> -->
                </div><!-- /.nav-search -->

                <!-- /section:basics/content.searchbox -->
            </div>

            <!-- /section:basics/content.breadcrumbs -->
            <div class="page-content">


                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <!--		
                        <div class="hr hr-18 dotted hr-double"></div>

                <h4 class="pink">
                            <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
                            <a href="#modal-table" role="button" class="green" data-toggle="modal"> Table Inside a Modal Box </a>								</h4>

                        <div class="hr hr-18 dotted hr-double"></div>
-->
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="col-xs-12">
                                    <h3 align="left" class="header smaller lighter blue">IDENTITY REQUESTS</h3>
                                </div>

                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>

                                <div class="table-header">
                                    Results for "ID BG Requests"
                                </div>

                                <div>
                                    <table id="idrequests-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center"> NO:</th>
                                                <th>Track Number</th>
                                                <th>Identity Number</th>
                                                <th>Identity Type</th>
                                                <th>Name</th>
                                                <th>Date of Birth</th>
                                                <th>Gender</th>
                                                <th>Serial Number</th>
                                                <th>Date Requested</th>
                                                <th>Report</th>
                                                <th class="hidden-480">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <div class="footer">
        <div class="footer-inner">
            <!-- #section:basics/footer -->
            <div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Peleza</span>
							Admin &copy; 2018						</span>

                &nbsp;&nbsp;
            </div>

            <!-- /section:basics/footer -->
        </div>
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i></a></div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='../../assets/js/jquery.js'>" + "<" + "/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>" + "<" + "/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='../../assets/js/jquery.mobile.custom.js'>" + "<" + "/script>");
</script>
<script src="../../assets/js/bootstrap.js"></script>

<!-- page specific plugin scripts -->
<script src="../../assets/js/dataTables/jquery.dataTables.js"></script>
<script src="../../assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
<script src="../../assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
<script src="../../assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

<!-- ace scripts -->
<script src="../../assets/js/ace/elements.scroller.js"></script>
<script src="../../assets/js/ace/elements.colorpicker.js"></script>
<script src="../../assets/js/ace/elements.fileinput.js"></script>
<script src="../../assets/js/ace/elements.typeahead.js"></script>
<script src="../../assets/js/ace/elements.wysiwyg.js"></script>
<script src="../../assets/js/ace/elements.spinner.js"></script>
<script src="../../assets/js/ace/elements.treeview.js"></script>
<script src="../../assets/js/ace/elements.wizard.js"></script>
<script src="../../assets/js/ace/elements.aside.js"></script>
<script src="../../assets/js/ace/ace.js"></script>
<script src="../../assets/js/ace/ace.ajax-content.js"></script>
<script src="../../assets/js/ace/ace.touch-drag.js"></script>
<script src="../../assets/js/ace/ace.sidebar.js"></script>
<script src="../../assets/js/ace/ace.sidebar-scroll-1.js"></script>
<script src="../../assets/js/ace/ace.submenu-hover.js"></script>
<script src="../../assets/js/ace/ace.widget-box.js"></script>
<script src="../../assets/js/ace/ace.settings.js"></script>
<script src="../../assets/js/ace/ace.settings-rtl.js"></script>
<script src="../../assets/js/ace/ace.settings-skin.js"></script>
<script src="../../assets/js/ace/ace.widget-on-reload.js"></script>
<script src="../../assets/js/ace/ace.searchbox-autocomplete.js"></script>

<script type="text/javascript">

    $(document).ready(function() {

        // $('input[name="reportrange"]').daterangepicker();

        var url = location.origin+"/dataTable/php/idrequests.php";

        var tableAllEntries = $('#idrequests-table')
            .DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true,
                "serverSide": true,
                "processing": true,
                "pageLength": 50,
                'dom': 'Bfrtip',
                'buttons': [
                    'pdf','print','csv'
                ],
                "ajax": url,
                "columnDefs": [
                    { "searchable": false, "targets": 9 }
                ],
                "order": [[ 8, "desc" ]]
            });

        tableAllEntries.on( 'order.dt search.dt', function () {

            tableAllEntries.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );

        } ).draw();

    });

</script>

<!-- the following scripts are used in demo only for onpage help and you don't need them -->
<link rel="stylesheet" href="../../assets/css/ace.onpage-help.css"/>
<link rel="stylesheet" href="../../docs/assets/js/themes/sunburst.css"/>

<script type="text/javascript"> ace.vars['base'] = '..'; </script>
<script src="../../assets/js/ace/elements.onpage-help.js"></script>
<script src="../../assets/js/ace/ace.onpage-help.js"></script>
<script src="../../docs/assets/js/rainbow.js"></script>
<script src="../../docs/assets/js/language/generic.js"></script>
<script src="../../docs/assets/js/language/html.js"></script>
<script src="../../docs/assets/js/language/css.js"></script>
<script src="../../docs/assets/js/language/javascript.js"></script>

<script type="text/javascript">
    <!--
    var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn: ["change"]});

    //-->
</script>
</body>
</html>
<?php
?>
