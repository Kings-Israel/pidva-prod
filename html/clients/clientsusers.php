<?php require_once('../../Connections/connect.php');
require_once('./credential_mailer.php');
//require_once('./apikey_mailer.php');
$updateGoTo = "clientsusers.php";


   


if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connect, $theValue) : mysqli_escape_string($connect, $theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

function randomPassword($len)
{

    //enforce min length 8
    if ($len < 10)
        $len = 8;

    //define character libraries - remove ambiguous characters like iIl|1 0oO
    $sets = array();
    $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    $sets[] = '23456789';
    $sets[] = '@#$';

    $password = '';

    //append a character from each set - gets first 4 characters
    foreach ($sets as $set) {

        $password .= $set[array_rand(str_split($set))];
    }

    //use all characters to fill up to $len
    while (strlen($password) < $len) {
        //get a random set
        $randomSet = $sets[array_rand($sets)];

        //add a random char from the random set
        $password .= $randomSet[array_rand(str_split($randomSet))];
    }

    //shuffle the password string before returning!
    return str_shuffle($password);
}

if ((isset($_GET['client_id'])) && ($_GET['client_id'] != "") && (!isset($_POST["MM_insert"])) && (!isset($_POST["MM_update"]))) {

    if ($_GET['status'] == '11') {
        function GeraHash3($qtd)
        {
            //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
            $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZabcdefghijklmnopqrstuvwxyz0123456789@#$';
            $QuantidadeCaracteres = strlen($Caracteres);
            //$QuantidadeCaracteres--;

            $Hash = NULL;
            for ($x = 1; $x <= $qtd; $x++) {
                $Posicao = rand(0, $QuantidadeCaracteres);
                $Hash .= substr($Caracteres, $Posicao, 1);
            }

            return $Hash;
        }

        function GeraHash4($qtd)
        {
            //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
            $Caracteres = 'abcdefghijklmnopqrstuvwxyz';
            $QuantidadeCaracteres = strlen($Caracteres);
            //$QuantidadeCaracteres--;

            $Hash = NULL;
            for ($x = 1; $x <= $qtd; $x++) {
                $Posicao = rand(0, $QuantidadeCaracteres);
                $Hash .= substr($Caracteres, $Posicao, 1);
            }

            return $Hash;
        }

        function GeraHash5($qtd)
        {
            //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
            $Caracteres = '0123456789';
            $QuantidadeCaracteres = strlen($Caracteres);
            //$QuantidadeCaracteres--;

            $Hash = NULL;
            for ($x = 1; $x <= $qtd; $x++) {
                $Posicao = rand(0, $QuantidadeCaracteres);
                $Hash .= substr($Caracteres, $Posicao, 1);
            }

            return $Hash;
        }


        $usr_password = randomPassword(10);
		$usr_password_client_id = randomPassword(10);

        $client_name = $_GET['name'];
        $client_email = trim($_GET['USR_EMAIL']);
        $client_company_id = $_GET['client_company_id'];
        $account_type = $_GET['client_type'] != '' ? 'psmt' : "pidva";

        $deleteSQL = sprintf(
            "UPDATE pel_client SET status=%s, verified_by=%s, verified_date=%s, client_password=%s , client_company_id=%s WHERE client_id=%s",
            GetSQLValueString('33', "text"),
            GetSQLValueString($_GET['fullnames'], "text"),
            GetSQLValueString(date('d-m-Y H:m:s'), "text"),
            GetSQLValueString(md5($usr_password), "text"),
            GetSQLValueString($client_company_id, "text"),
            GetSQLValueString($_GET['client_id'], "int")
        );

        mysqli_select_db($connect, $database_connect);
        $Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));


        // SEND MAIL'
                       
        if ($account_type == 'psmt') {
            
            $auth_mailer->send_mail($client_name, $client_email, $usr_password, $account_type, $client_company_id);
        } else {
       
            $auth_mailer->send_mail($client_name, $client_email, $usr_password, $account_type);
        }

        if (!empty($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: index.php");
        }
    } elseif ($_GET['status'] == '00') {

        $deleteSQL = sprintf(
            "UPDATE pel_client SET status=%s, added_by=%s, added_date=%s  WHERE client_id=%s",
            GetSQLValueString($_GET['status'], "text"),
            GetSQLValueString($_GET['fullnames'], "text"),
            GetSQLValueString(date('d-m-Y H:m:s'), "text"),
            GetSQLValueString($_GET['client_id'], "int")
        );
        mysqli_select_db($connect, $database_connect);
        $Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));
    } elseif ($_GET['status'] == '22') {

        $deleteSQL = sprintf(
            "UPDATE pel_client SET status=%s, added_by=%s, added_date=%s  WHERE client_id=%s",
            GetSQLValueString($_GET['status'], "text"),
            GetSQLValueString($_GET['fullnames'], "text"),
            GetSQLValueString(date('d-m-Y H:m:s'), "text"),
            GetSQLValueString($_GET['client_id'], "int")
        );
        mysqli_select_db($connect, $database_connect);
        $Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));
    }


    header("Location: /html/clients/clientsusers.php");
}

$errorcode = '';

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editclient")) {

    if ($_POST['client_parent_company'] == 'NO AFFILIATION') {
        $client_company_id = strtoupper($_POST['client_first_name']) . " " . strtoupper($_POST['client_last_name']);
    } else {
        $client_company_id2 = strtoupper($_POST['client_parent_company']);

        mysqli_select_db($connect, $database_connect);
        $query_getcompanycode = "SELECT company_code FROM pel_client_co WHERE company_name='$client_company_id2'";
        $getcompanycode = mysqli_query_ported($query_getcompanycode, $connect) or die(mysqli_error($connect));
        $row_getcompanycode = mysqli_fetch_assoc($getcompanycode);
        $totalRows_getcompanycode = mysqli_num_rows($getcompanycode);
        $client_company_id = $row_getcompanycode['company_code'];
    }

    $updateSQL = sprintf(
        "UPDATE pel_client SET client_first_name=%s, client_mobile_number=%s, status=%s, client_email_address=%s, client_login_username=%s, client_parent_company=%s, client_country=%s, added_by=%s, client_industry=%s, added_date=%s, client_last_name=%s, client_company_id=%s WHERE client_id=%s",
        GetSQLValueString(strtoupper($_POST['client_first_name']), "text"),
        GetSQLValueString(strtoupper($_POST['client_mobile_number']), "text"),
        GetSQLValueString($_POST['status'], "text"),
        GetSQLValueString($_POST['client_email_address'], "text"),
        GetSQLValueString($_POST['client_email_address'], "text"),
        GetSQLValueString(strtoupper($_POST['client_parent_company']), "text"),
        GetSQLValueString($_POST['client_country'], "text"),
        GetSQLValueString($_POST['added_by'], "text"),
        GetSQLValueString(strtoupper($_POST['client_industry']), "text"),
        GetSQLValueString($_POST['added_date'], "text"),
        GetSQLValueString($_POST['client_last_name'], "text"),
        GetSQLValueString($client_company_id, "text"),
        GetSQLValueString($_POST['client_id'], "int")
    );

    mysqli_select_db($connect, $database_connect);
    mysqli_query_ported($updateSQL, $connect);

    if (mysqli_error($connect)) {
        $errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												ERROR!!!!!
											</strong>

											 Details of the Client in the Particluar Country already existing.
											<br />
										</div>';
    } else {

        $updateGoTo = "clientsusers.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
            $updateGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $updateGoTo));
    }
} elseif ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newclient")) {
    $account_type = $_POST['account_type'];

    if ($_POST['client_parent_company'] == 'NO AFFILIATION') {

        $client_company_id = strtoupper($_POST['client_first_name']) . " " . strtoupper($_POST['client_last_name']);
    } else {

        $client_company_id2 = strtoupper($_POST['client_parent_company']);

        mysqli_select_db($connect, $database_connect);
        $query_getcompanycode = "SELECT company_code FROM pel_client_co WHERE company_name='$client_company_id2'";
        $getcompanycode = mysqli_query_ported($query_getcompanycode, $connect) or die(mysqli_error($connect));
        $row_getcompanycode = mysqli_fetch_assoc($getcompanycode);
        $totalRows_getcompanycode = mysqli_num_rows($getcompanycode);
        $client_company_id = $row_getcompanycode['company_code'];
    }

    $usr_password = randomPassword(8);
    $usr_password_client_id = hash('sha256', randomPassword(10));
	
$insertSQL = sprintf(
        "INSERT INTO pel_client (client_first_name, client_mobile_number, status, client_email_address, client_parent_company, client_country, added_by, client_industry, added_date, client_last_name, client_login_username, client_company_id,client_password,client_type) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString(strtoupper($_POST['client_first_name']), "text"),
        GetSQLValueString(strtoupper($_POST['client_mobile_number']), "text"),
        GetSQLValueString($_POST['status'], "text"),
        GetSQLValueString($_POST['client_email_address'], "text"),
        GetSQLValueString(strtoupper($_POST['client_parent_company']), "text"),
        GetSQLValueString($_POST['client_country'], "text"),
        GetSQLValueString($_POST['added_by'], "text"),
        GetSQLValueString(strtoupper($_POST['client_industry']), "text"),
        GetSQLValueString($_POST['added_date'], "text"),
        GetSQLValueString(strtoupper($_POST['client_last_name']), "text"),
        GetSQLValueString($_POST['client_email_address'], "text"),
        GetSQLValueString($client_company_id, "text"),
        GetSQLValueString(md5($usr_password), "text"),
	//GetSQLValueString($usr_password_client_id, "text"),
        GetSQLValueString($account_type, "text")
    );
//exit ();
    if ($connect->query($insertSQL)) {
        header("Location: " . $updateGoTo);
    } else {
        echo 'NOT';
        echo $connect->error;
        header("Location: " . $updateGoTo);
    };
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    mysqli_select_db($connect, $database_connect);
    $query_getallclients = "SELECT * FROM pel_client ORDER BY client_id DESC";
    $getallclients = $connect->query($query_getallclients);
    $row_getallclients = mysqli_fetch_assoc($getallclients);
    $totalRows_getallclients = mysqli_num_rows($getallclients);

    $query_getcompanys = "SELECT * FROM pel_client_co WHERE status = '11' ORDER BY company_name DESC";
    $getcompanys = mysqli_query_ported($query_getcompanys, $connect) or die(mysqli_error($connect));
    $row_getcompanys = mysqli_fetch_assoc($getcompanys);
    $totalRows_getcompanys = mysqli_num_rows($getcompanys);

    $query_getcountries = "SELECT * FROM pel_countries ORDER BY country_name DESC";
    $getcountries = mysqli_query_ported($query_getcountries, $connect) or die(mysqli_error($connect));
    $row_getcountries = mysqli_fetch_assoc($getcountries);
    $totalRows_getcountries = mysqli_num_rows($getcountries);

    $query_getindustries = "SELECT * FROM pel_industries ORDER BY industry_name DESC";
    $getindustries = mysqli_query_ported($query_getindustries, $connect) or die(mysqli_error($connect));
    $row_getindustries = mysqli_fetch_assoc($getindustries);
    $totalRows_getindustries = mysqli_num_rows($getindustries);
}

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Clients - Peleza Admin</title>

    <meta name="description" content="Static &amp; Dynamic Tables" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../../assets/css/font-awesome.css" />

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="../../assets/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet"/>
    <![endif]-->

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../assets/css/ace-ie.css"/>
    <![endif]-->

    <!-- inline styles related to this page -->
    <link rel="stylesheet" href="../../assets/css/jquery-ui.custom.css" />
    <link rel="stylesheet" href="../../assets/css/chosen.css" />
    <link rel="stylesheet" href="../../assets/css/datepicker.css" />
    <link rel="stylesheet" href="../../assets/css/bootstrap-timepicker.css" />
    <link rel="stylesheet" href="../../assets/css/daterangepicker.css" />
    <link rel="stylesheet" href="../../assets/css/bootstrap-datetimepicker.css" />
    <link rel="stylesheet" href="../../assets/css/colorpicker.css" />

    <!--    <link href="assets/sweetalert/sweetalert.css" rel="stylesheet">-->

    <link href="../../assets/sweetalert/sweetalert.css">
    </script>

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
            } catch (e) {}
        </script>
        <?php include('../header2.php'); ?>
    </div>

    <!-- /section:basics/navbar.layout -->
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

                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <a href="#">Home</a>
                            </li>

                            <li>
                                <a href="#">Clients</a>
                            </li>

                            <li class="active">Registered Users</li>
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
                                    <div class="col-xs-6">

                                        <h3 align="left" class="header smaller lighter blue">PELEZA CLIENT REGISTERED</h3>
                                    </div>

                                    <?php
                                    if (in_array('ADD_CLIENTS', $roledata)) {
                                    ?>
                                        <div class="col-xs-6">
                                            <h3 align="right" class="header smaller lighter blue">
                                                <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
                                                <a href="#modal-newclient" role="button" class="green" data-toggle="modal">
                                                    <button class="btn btn-white btn-info btn-bold">
                                                        <i class="ace-icon bigger-120 green"></i>Add New Client
                                                    </button>
                                                </a> <a href="assignpackageclients.php" role="button" class="green">
                                                    <button class="btn btn-success btn-info btn-bold">
                                                        <i class="ace-icon bigger-120 green"></i>Assign Packages
                                                    </button>
                                                </a>
                                            </h3>

                                        </div>
                                    <?php
                                    }

                                    ?>
                                    <div class="col-xs-12">
                                        <?php

                                        echo $errorcode;
                                        ?>
                                    </div>

                                    <div class="clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div class="table-header">
                                        Results for "Clients Registered"
                                    </div>

                                    <!-- div.table-responsive -->

                                    <!-- div.dataTables_borderWrap -->
                                    <div>
                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="center">
                                                        <!--<label class="pos-rel">
                                                    <input type="checkbox" class="ace" />
                                                    <span class="lbl"></span>															</label>-->
                                                        NO:

                                                    </th>
                                                    <th>Client Name</th>
                                                    <th>Client Credits</th>
                                                    <th>Country</th>
                                                    <th>Industry</th>
                                                    <th>Email Address</th>
                                                    <th>Mobile Number</th>
                                                    <th>Parent Company</th>

                                                    <th>More</th>


                                                    <th class="hidden-480">Status</th>

                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php

                                                $x = 1;
                                                do { ?>
                                                    <tr>
                                                        <td class="center">
                                                            <!--	<label class="pos-rel">
                                                           <input type="checkbox" class="ace" />
                                                           <span class="lbl"></span>	--> <?php echo $x++; ?> </label>
                                                        </td>

                                                        <td>
                                                            <a href="#"><?php echo $row_getallclients['client_first_name'] . " " . $row_getallclients['client_last_name']; ?></a>
                                                        </td>
                                                        <td><?php echo $row_getallclients['client_credits']; ?> Credits</td>

                                                        <td><?php echo $row_getallclients['client_country']; ?></td>
                                                        <td><?php echo $row_getallclients['client_industry']; ?></td>
                                                        <td><?php echo $row_getallclients['client_email_address']; ?></td>
                                                        <td><?php echo $row_getallclients['client_mobile_number']; ?></td>
                                                        <td><?php echo $row_getallclients['client_parent_company']; ?></td>
                                                        <td>
                                                            <a href="viewpackageclients.php?client_id=<?php echo $row_getallclients['client_id']; ?>" role="button" class="green" data-toggle="modal"> <span class="label label-sm label-primary">View Packages</span></a>
                                                            <br />
                                                            <br />
                                                            <div class="">
                                                                <button client_id="<?php echo $row_getallclients['client_id']; ?>" class="btn btn-sm btn-danger" onClick="generateAPIKey(this.getAttribute('client_id'))">
                                                                    API Keys
                                                                </button>
                                                            </div>

                                                        </td>


                                                        <td class="hidden-480"><?php

                                                                                if ($row_getallclients['status'] == '11') {
                                                                                ?>

                                                                <span class="label label-sm label-success">Active</span>
                                                            <?php
                                                                                }
                                                                                if ($row_getallclients['status'] == '00') {
                                                            ?>
                                                                <span class="label label-sm label-danger">Deactivated</span>
                                                            <?php
                                                                                }
                                                                                if ($row_getallclients['status'] == '22') {
                                                            ?>
                                                                <span class="label label-sm label-warning">Unverified</span>
                                                            <?php
                                                                                }
                                                            ?>
                                                        </td>


                                                        <td>
                                                            <div class="hidden-sm hidden-xs action-buttons">

                                                                <a href="#modal-viewclient-<?php echo $row_getallclients['client_id']; ?>" role="button" class="green" data-toggle="modal">
                                                                    <button type="button" class="btn btn-xs btn-primary">
                                                                        <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                                                    </button>
                                                                </a>


                                                                <div id="modal-viewclient-<?php echo $row_getallclients['client_id']; ?>" class="modal fade" tabindex="-1">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header no-padding">
                                                                                <div class="table-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                                        <span class="white">&times;</span>
                                                                                    </button>
                                                                                    VIEW CLIENT DETAILS
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-body padding">
                                                                                <table width="100%" border="0">
                                                                                    <tr>
                                                                                        <td><strong>Client Names:</strong></td>
                                                                                        <td><?php echo $row_getallclients['client_first_name']; ?><?php echo $row_getallclients['client_last_name']; ?></td>
                                                                                        <td><strong>Client Credits:</strong>
                                                                                        </td>
                                                                                        <td><?php echo $row_getallclients['client_credits']; ?></td>

                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="4"><br /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Mobile Number</strong></td>
                                                                                        <td><?php echo $row_getallclients['client_mobile_number']; ?></td>
                                                                                        <td><strong>Client Email
                                                                                                Address:</strong></td>
                                                                                        <td><?php echo $row_getallclients['client_email_address']; ?></td>

                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="4"><br /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Country</strong></td>
                                                                                        <td><?php echo $row_getallclients['client_country']; ?></td>
                                                                                        <td><strong>Industry:</strong></td>
                                                                                        <td><?php echo $row_getallclients['client_industry']; ?></td>


                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="4"><br /></td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td><strong>Client Status:</strong></td>
                                                                                        <td>
                                                                                            <button type="button" class="btn disabled btn-white btn-primary"><?php if ($row_getallclients['status'] == '11') {
                                                                                                                                                                ?>
                                                                                                    ACTIVE <?php
                                                                                                                                                                }
                                                                                                                                                                if ($row_getallclients['status'] == '00') {
                                                                                                            ?>
                                                                                                    DEACTIVATED

                                                                                                <?php
                                                                                                                                                                }
                                                                                                                                                                if ($row_getallclients['status'] == '22') {
                                                                                                ?>
                                                                                                    UNVERIFIED

                                                                                                <?php
                                                                                                                                                                }
                                                                                                ?> </button>
                                                                                        </td>
                                                                                        <td><strong>Parent Company</strong></td>


                                                                                        <td><?php echo $row_getallclients['client_parent_company']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="4"><br /></td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td><strong>Added By:</strong></td>
                                                                                        <td>
                                                                                            <button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getallclients['added_by']; ?></button>
                                                                                        </td>
                                                                                        <td><strong>Added Date:</strong></td>
                                                                                        <td>
                                                                                            <button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getallclients['added_date']; ?></button>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="4"><br /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Verified By:</strong></td>
                                                                                        <td>
                                                                                            <button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getallclients['verified_by']; ?></button>
                                                                                        </td>
                                                                                        <td><strong>Verified Date:</strong></td>
                                                                                        <td>
                                                                                            <button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getallclients['verified_date']; ?></button>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="4"><br /></td>
                                                                                    </tr>

                                                                                </table>
                                                                            </div>
                                                                            <div class="modal-footer margin-top">
                                                                                <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                                                                                    <i class="ace-icon fa fa-times"></i>
                                                                                    Close
                                                                                </button>


                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div><!-- PAGE CONTENT ENDS -->
                                                                <?php
                                                                if (in_array('EDIT_CLIENTS', $roledata)) {
                                                                ?>

                                                                    <a href="#modal-editclient-<?php echo $row_getallclients['client_id']; ?>" role="button" class="green" data-toggle="modal">
                                                                        <button class="btn btn-xs btn-info">
                                                                            <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                                        </button>
                                                                    </a>

                                                                    <button client_id="<?php echo $row_getallclients['client_id']; ?>" class="btn btn-xs btn-success" onClick="resetPassword(this.getAttribute('client_id'))">
                                                                        <i class="ace-icon fa fa-lock bigger-120"></i>
                                                                    </button>

                                                                <?php
                                                                }
                                                                ?>

                                                                <div id="modal-editclient-<?php echo $row_getallclients['client_id']; ?>" class="modal fade" tabindex="-1">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header no-padding">
                                                                                <div class="table-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                                        <span class="white">&times;</span>
                                                                                    </button>
                                                                                    Edit Client
                                                                                </div>
                                                                            </div>

                                                                            <div class="modal-body padding">
                                                                                <form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="editclient">
                                                                                    <input type="hidden" id="client_id" name="client_id" value="<?php echo $row_getallclients['client_id']; ?>" />

                                                                                    <input type="hidden" id="status" name="status" value="22" />
                                                                                    <input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
                                                                                    <input type="hidden" id="added_date" name="added_date" value="<?php echo date('d-m-Y H:m:s'); ?>" />

                                                                                    <div class="space-10"></div>


                                                                                    <label class="col-sm-4">Client First
                                                                                        Name</label>

                                                                                    <div class="col-sm-8"><span id="sprytextfield1">
                                                                                            <input type="text" id="client_first_name" name="client_first_name" value="<?php echo $row_getallclients['client_first_name']; ?>" readonly />
                                                                                            <span class="textfieldRequiredMsg">*</span></span></div>


                                                                                    <br />
                                                                                    <div class="space-10"></div>


                                                                                    <label class="col-sm-4">Other Names</label>

                                                                                    <div class="col-sm-8"><span id="sprytextfield2">
                                                                                            <input type="text" id="client_last_name" name="client_last_name" value="<?php echo $row_getallclients['client_last_name']; ?>" readonly />
                                                                                            <span class="textfieldRequiredMsg">*</span></span></div>


                                                                                    <br />
                                                                                    <div class="space-10"></div>
                                                                                    <label class="col-sm-4">Email
                                                                                        Address</label>

                                                                                    <div class="col-sm-8"><span id="sprytextfield3">
                                                                                            <input type="text" id="client_email_address" name="client_email_address" value="<?php echo $row_getallclients['client_email_address']; ?>" />
                                                                                            <span class="textfieldRequiredMsg">*</span></span></div>


                                                                                    <br />
                                                                                    <div class="space-10"></div>

                                                                                    <label class="col-sm-4">Mobile
                                                                                        Number</label>

                                                                                    <div class="col-sm-8"><span id="sprytextfield4">
                                                                                            <input value="<?php echo $row_getallclients['client_mobile_number']; ?>" type="text" id="client_mobile_number" name="client_mobile_number" />
                                                                                            <span class="textfieldRequiredMsg">*</span></span></div>


                                                                                    <br />
                                                                                    <div class="space-10"></div>


                                                                                    <label class="col-sm-4">Country</label>

                                                                                    <div class="col-sm-7"><span id="spryselect1">
                                                                                            <select class="chosen-select form-control" name="client_country" id="client_country" data-placeholder="Choose Country...">
                                                                                                <!--     <select name="client_country" id="client_country">-->
                                                                                                <option value="<?php echo $row_getallclients['client_country']; ?>"><?php echo $row_getallclients['client_country']; ?></option>
                                                                                                <option value="000"></option>
                                                                                                <?php


                                                                                                $query_getcountries2 = "SELECT * FROM pel_countries ORDER BY country_name ASC";
                                                                                                $getcountries2 = mysqli_query_ported($query_getcountries2, $connect) or die(mysqli_error($connect));
                                                                                                $row_getcountries2 = mysqli_fetch_assoc($getcountries2);
                                                                                                $totalRows_getcountries2 = mysqli_num_rows($getcountries2);


                                                                                                do { ?>
                                                                                                    <option value="<?php echo $row_getcountries2['country_name']; ?>"><?php echo $row_getcountries2['country_name']; ?></option>
                                                                                                <?php } while ($row_getcountries2 = mysqli_fetch_assoc($getcountries2)); ?>
                                                                                            </select>

                                                                                            <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span>
                                                                                    </div>
                                                                                    <br />
                                                                                    <div class="space-10"></div>

                                                                                    <label class="col-sm-4">Industry</label>

                                                                                    <div class="col-sm-7"><span id="spryselect2">
                                                                                            <select class="chosen-select form-control" name="client_industry" id="client_industry" data-placeholder="Choose Industry...">
                                                                                                <!--  <select name="client_industry" id="client_industry">-->
                                                                                                <option value="<?php echo $row_getallclients['client_industry']; ?>"><?php echo $row_getallclients['client_industry']; ?></option>
                                                                                                <option value="000"></option>
                                                                                                <?php


                                                                                                $query_getindustries2 = "SELECT * FROM pel_industries ORDER BY industry_name ASC";
                                                                                                $getindustries2 = mysqli_query_ported($query_getindustries2, $connect) or die(mysqli_error($connect));
                                                                                                $row_getindustries2 = mysqli_fetch_assoc($getindustries2);
                                                                                                $totalRows_getindustries2 = mysqli_num_rows($getindustries2);

                                                                                                do { ?>
                                                                                                    <option value="<?php echo $row_getindustries2['industry_name']; ?>"><?php echo $row_getindustries2['industry_name']; ?></option>
                                                                                                <?php } while ($row_getindustries2 = mysqli_fetch_assoc($getindustries2)); ?>
                                                                                            </select>

                                                                                            <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span>
                                                                                    </div>

                                                                                    <br />
                                                                                    <div class="space-10"></div>

                                                                                    <label class="col-sm-4">Parent
                                                                                        Company</label>

                                                                                    <div class="col-sm-7"><span id="spryselect3">
                                                                                            <select class="chosen-select form-control" name="client_parent_company" id="client_parent_company" data-placeholder="Choose Company...">
                                                                                                <!--  <select name="client_industry" id="client_industry">-->
                                                                                                <option value="<?php echo $row_getallclients['client_parent_company']; ?>"><?php echo $row_getallclients['client_parent_company']; ?></option>
                                                                                                <option value="000"></option>
                                                                                                <option value="NO AFFILIATION">NO AFFILIATION</option>
                                                                                                <?php
                                                                                                $query_getcompanys2 = "SELECT * FROM pel_client_co WHERE status = '11' ORDER BY company_name ASC";
                                                                                                $getcompanys2 = mysqli_query_ported($query_getcompanys2, $connect) or die(mysqli_error($connect));
                                                                                                $row_getcompanys2 = mysqli_fetch_assoc($getcompanys2);
                                                                                                $totalRows_getcompanys2 = mysqli_num_rows($getcompanys2);

                                                                                                do { ?>
                                                                                                    <option value="<?php echo $row_getcompanys2['company_name']; ?>"><?php echo $row_getcompanys2['company_name']; ?></option>
                                                                                                <?php } while ($row_getcompanys2 = mysqli_fetch_assoc($getcompanys2)); ?>
                                                                                            </select>

                                                                                            <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span>
                                                                                    </div>


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
                                                                                                Reset
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>

                                                                                    <input type="hidden" name="MM_update" value="editclient">

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

                                                                </div><!-- PAGE CONTENT ENDS -->

                                                                <?php
                                                                if (in_array('DEACTIVATE_CLIENTS', $roledata)) {

                                                                    if ($row_getallclients['status'] == '11') {
                                                                ?>
                                                                        <a href="clientsusers.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=00&client_id=<?php echo $row_getallclients['client_id']; ?>&USR_EMAIL=<?php echo $row_getallclients['client_email_address']; ?>">
                                                                            <button class="btn btn-xs btn-danger">
                                                                                <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                                            </button>
                                                                        </a> <?php
                                                                            }

                                                                            if ($row_getallclients['status'] == '33') {
                                                                                ?>
                                                                        <a href="clientsusers.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=00&client_id=<?php echo $row_getallclients['client_id']; ?>&USR_EMAIL=<?php echo $row_getallclients['client_email_address']; ?>">
                                                                            <button class="btn btn-xs btn-danger">
                                                                                <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                                            </button>
                                                                        </a> <?php
                                                                            }
                                                                        }

                                                                        if (in_array('ACTIVATE_CLIENTS', $roledata) && $row_getallclients['added_by'] != $_SESSION['MM_full_names']) {

                                                                            if ($row_getallclients['status'] == '00') {
                                                                                ?>
                                                                        <a href="clientsusers.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=22&client_id=<?php echo $row_getallclients['client_id']; ?>&USR_EMAIL=<?php echo $row_getallclients['client_email_address']; ?>">
                                                                            <button class="btn btn-xs btn-success">
                                                                                <i class="ace-icon fa fa-check bigger-120"></i>
                                                                            </button>
                                                                        </a>

                                                                    <?php
                                                                            }
                                                                        }

                                                                        if (in_array('VERIFY_CLIENTS', $roledata) && $row_getallclients['added_by'] != $_SESSION['MM_full_names']) {
                                                                            if ($row_getallclients['status'] == '22') {
                                                                    ?>
                                                                        <a href="clientsusers.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=11&client_type=<?php echo $row_getallclients['client_type']; ?>&client_id=<?php echo $row_getallclients['client_id']; ?>&USR_EMAIL=<?php echo $row_getallclients['client_email_address']; ?>&name=<?php echo $row_getallclients['client_first_name']; ?>&client_company_id=<?php echo $row_getallclients['client_company_id']; ?>">
                                                                            <button class="btn btn-xs btn-warning">
                                                                                <i class="ace-icon fa fa-check bigger-120"></i>
                                                                            </button>
                                                                        </a>

                                                                <?php
                                                                            }
                                                                        }
                                                                ?>
                                                            </div>
                                                            <div class="hidden-md hidden-lg">
                                                                <div class="inline pos-rel">
                                                                    <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                                                        <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                                                    </button>

                                                                    <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                                        <li>
                                                                            <a href="#" class="tooltip-info" data-rel="tooltip" title="View">
                                                                                <span class="blue">
                                                                                    <i class="ace-icon fa fa-search-plus bigger-120"></i> </span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">

                                                                                <span class="green">
                                                                                    <i class="ace-icon fa fa-pencil-square-o bigger-120"></i> </span>
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
                                                                                <span class="red">
                                                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i> </span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } while ($row_getallclients = mysqli_fetch_assoc($getallclients)); ?>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="modal-newclient" class="modal fade" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header no-padding">
                                            <div class="table-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                    <span class="white">&times;</span></button>
                                                Add New Client
                                            </div>
                                        </div>
                                        <div class="modal-body no-padding">


                                            <form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="newclient">

                                                <input type="hidden" id="status" name="status" value="22" />
                                                <input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
                                                <input type="hidden" id="added_date" name="added_date" value="<?php echo date('d-m-Y H:m:s'); ?>" />
                                                <div class="space-10"></div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Client Name</label>

                                                    <div class="col-sm-9"><span id="sprytextfield5">
                                                            <input type="text" id="client_first_name" name="client_first_name" />
                                                            <span class="textfieldRequiredMsg">*</span></span></div>


                                                </div>

                                                <div class="space-4"></div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Other Names</label>

                                                    <div class="col-sm-9"><span id="sprytextfield6">
                                                            <input type="text" id="client_last_name" name="client_last_name" />
                                                            <span class="textfieldRequiredMsg">*</span></span></div>

                                                </div>

                                                <div class="space-4"></div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Email Address</label>

                                                    <div class="col-sm-9"><span id="sprytextfield7">
                                                            <input type="text" id="client_email_address" name="client_email_address" />
                                                            <span class="textfieldRequiredMsg">*</span></span></div>
                                                </div>

                                                <div class="space-4"></div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Mobile Number</label>

                                                    <div class="col-sm-9"><span id="sprytextfield8">
                                                            <input type="text" id="client_mobile_number" name="client_mobile_number" />
                                                            <span class="textfieldRequiredMsg">*</span></span></div>
                                                </div>


                                                <div class="space-4"></div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Country</label>

                                                    <div class="col-sm-6"><span id="spryselect4">
                                                            <select class="chosen-select form-control" name="client_country" id="client_country" data-placeholder="Choose Industry...">

                                                                <option value="000">Choose Country</option>

                                                                <?php do { ?>
                                                                    <option value="<?php echo $row_getcountries['country_name']; ?>"><?php echo $row_getcountries['country_name']; ?></option>
                                                                <?php } while ($row_getcountries = mysqli_fetch_assoc($getcountries)); ?>
                                                            </select>

                                                            <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
                                                </div>

                                                <div class="space-4"></div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Industry</label>

                                                    <div class="col-sm-6"><span id="spryselect5">
                                                            <select class="chosen-select form-control" name="client_industry" id="client_industry" data-placeholder="Choose Industry...">

                                                                <option value="000">Choose Industry</option>

                                                                <?php do { ?>
                                                                    <option value="<?php echo $row_getindustries['industry_name']; ?>"><?php echo $row_getindustries['industry_name']; ?></option>
                                                                <?php } while ($row_getindustries = mysqli_fetch_assoc($getindustries)); ?>
                                                            </select>

                                                            <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
                                                </div>

                                                <div class="space-4"></div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Parent Company</label>

                                                    <div class="col-sm-6"><span id="spryselect6">
                                                            <select class="chosen-select form-control" name="client_parent_company" id="client_parent_company" data-placeholder="Choose Company...">

                                                                <option value="000"></option>
                                                                <option value="NO AFFILIATION">NO AFFILIATION</option>

                                                                <?php do { ?>
                                                                    <option value="<?php echo $row_getcompanys['company_name']; ?>"><?php echo $row_getcompanys['company_name']; ?></option>
                                                                <?php } while ($row_getcompanys = mysqli_fetch_assoc($getcompanys)); ?>
                                                            </select>

                                                            <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="account_type">Account Type</label>

                                                    <div class="col-sm-6">
                                                        <span id="spryselect6">
                                                            <select class="chosen-select form-control" name="account_type" id="account_type" data-placeholder="Choose Account Type...">
                                                                <option value="edcheck">EDCheck Africa</option>
                                                                <option value="idcheck">IDCheck Africa</option>
                                                                <option value="psmt">PSMT</option>
<option value="psmt">KYC</option>
                                                            </select>
                                                            <span class="selectInvalidMsg">*</span>
                                                            <span class="selectRequiredMsg">*</span>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="space-4"></div>

                                                <div class="clearfix form-actions">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
                                                            <!--<button onClick="submit" class="btn btn-info" type="button">-->
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            Save
                                                        </button>

                                                           
                                                        <button class="btn" type="reset">
                                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                                            Reset
                                                        </button>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="MM_insert" value="newclient">

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


                            </div><!-- PAGE CONTENT ENDS -->
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
                        Admin &copy; 2018 </span>

                    &nbsp;&nbsp;
                </div>

                <!-- /section:basics/footer -->
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Password Reset</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Reset Password</button>
                    </div>
                </div>
            </div>
        </div>


        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i></a>
    </div><!-- /.main-container -->

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

    <script src="../../assets/js/jquery-ui.custom.js"></script>
    <script src="../../assets/js/jquery.ui.touch-punch.js"></script>
    <script src="../../assets/js/chosen.jquery.js"></script>
    <script src="../../assets/js/fuelux/fuelux.spinner.js"></script>
    <script src="../../assets/js/date-time/bootstrap-datepicker.js"></script>
    <script src="../../assets/js/date-time/bootstrap-timepicker.js"></script>
    <script src="../../assets/js/date-time/moment.js"></script>
    <script src="../../assets/js/date-time/daterangepicker.js"></script>
    <script src="../../assets/js/date-time/bootstrap-datetimepicker.js"></script>
    <script src="../../assets/js/bootstrap-colorpicker.js"></script>
    <script src="../../assets/js/jquery.knob.js"></script>
    <script src="../../assets/js/jquery.autosize.js"></script>
    <script src="../../assets/js/jquery.inputlimiter.1.3.1.js"></script>
    <script src="../../assets/js/jquery.maskedinput.js"></script>
    <script src="../../assets/js/bootstrap-tag.js"></script>
    <!--<script src="../../assets/sweetalert/sweetalert.min.js"></script>-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
        function generateAPIKey(client_id) {

                     var data = {
                client_id: client_id
            };

            $.post('../../v1/api.php', data, function(data) {

                console.log(JSON.stringify(data, undefined, 2))


            }).fail(function(data) {

                // just in case posting your form failed
                console.log(JSON.stringify(data, undefined, 2))

            });

        }

        function resetPassword(client_id) {

            swal("Pick user account to reset password?", {
                buttons: {
                    psmt: {
                        text: "PSMT",
                        value: "psmt",
                    },
 		 kyc: {
                        text: "KYC",
                        value: "psmt",
                    },

                    idcheck: {
                        text: "ID Check",
                        value: "idcheck",
                    },
                    edcheck: {
                        text: "ED Check",
                        value: "edcheck",
                    }
                },
            }).then(function(value) {

                var data = {
                    client_id: client_id,
                    account_type: value,
                };

                $.post('../../v1/api.php', data, function(data) {

                    swal("Great!", "Password reset successful", "success");
                    console.log(JSON.stringify(data, undefined, 2))

                }).fail(function(data) {

                    swal("Opps!", "Unfortunate. SOmething happened. We could not send an email", "error");
                    // just in case posting your form failed
                    console.log(JSON.stringify(data, undefined, 2))

                });

            });
        }
    </script>

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

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        jQuery(function($) {
            //initiate dataTables plugin
            var oTable1 =
                $('#dynamic-table')
                //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                .dataTable({
                    bAutoWidth: false,
                    "aoColumns": [
                        null, null, null, null, null, null, null, null, null, null,
                        {
                            "bSortable": false
                        }
                    ],
                    "aaSorting": [],

                    //,
                    //"sScrollY": "200px",
                    //"bPaginate": false,

                    //"sScrollX": "100%",
                    //"sScrollXInner": "120%",
                    //"bScrollCollapse": true,
                    //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                    //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                    //"iDisplayLength": 50
                });
            //oTable1.fnAdjustColumnSizing();


            //TableTools settings
            TableTools.classes.container = "btn-group btn-overlap";
            TableTools.classes.print = {
                "body": "DTTT_Print",
                "info": "tableTools-alert gritter-item-wrapper gritter-info gritter-center white",
                "message": "tableTools-print-navbar"
            }

            //initiate TableTools extension
            var tableTools_obj = new $.fn.dataTable.TableTools(oTable1, {
                "sSwfPath": "../../assets/js/dataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf", //in Ace demo ../assets will be replaced by correct assets path

                "sRowSelector": "td:not(:last-child)",
                "sRowSelect": "multi",
                "fnRowSelected": function(row) {
                    //check checkbox when row is selected
                    try {
                        $(row).find('input[type=checkbox]').get(0).checked = true
                    } catch (e) {}
                },
                "fnRowDeselected": function(row) {
                    //uncheck checkbox
                    try {
                        $(row).find('input[type=checkbox]').get(0).checked = false
                    } catch (e) {}
                },

                "sSelectedClass": "success",
                "aButtons": [{
                        "sExtends": "copy",
                        "sToolTip": "Copy to clipboard",
                        "sButtonClass": "btn btn-white btn-primary btn-bold",
                        "sButtonText": "<i class='fa fa-copy bigger-110 pink'></i>",
                        "fnComplete": function() {
                            this.fnInfo('<h3 class="no-margin-top smaller">Table copied</h3>\
									<p>Copied ' + (oTable1.fnSettings().fnRecordsTotal()) + ' row(s) to the clipboard.</p>',
                                1500
                            );
                        }
                    },

                    {
                        "sExtends": "csv",
                        "sToolTip": "Export to CSV",
                        "sButtonClass": "btn btn-white btn-primary  btn-bold",
                        "sButtonText": "<i class='fa fa-file-excel-o bigger-110 green'></i>"
                    },

                    {
                        "sExtends": "pdf",
                        "sToolTip": "Export to PDF",
                        "sButtonClass": "btn btn-white btn-primary  btn-bold",
                        "sButtonText": "<i class='fa fa-file-pdf-o bigger-110 red'></i>"
                    },

                    {
                        "sExtends": "print",
                        "sToolTip": "Print view",
                        "sButtonClass": "btn btn-white btn-primary  btn-bold",
                        "sButtonText": "<i class='fa fa-print bigger-110 grey'></i>",

                        "sMessage": "<div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Optional Navbar &amp; Text</small></a></div></div>",

                        "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
									  <p>Please use your browser's print function to\
									  print this table.\
									  <br />Press <b>escape</b> when finished.</p>",
                    }
                ]
            });
            //we put a container before our table and append TableTools element to it
            $(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));

            //also add tooltips to table tools buttons
            //addding tooltips directly to "A" buttons results in buttons disappearing (weired! don't know why!)
            //so we add tooltips to the "DIV" child after it becomes inserted
            //flash objects inside table tools buttons are inserted with some delay (100ms) (for some reason)
            setTimeout(function() {
                $(tableTools_obj.fnContainer()).find('a.DTTT_button').each(function() {
                    var div = $(this).find('> div');
                    if (div.length > 0) div.tooltip({
                        container: 'body'
                    });
                    else $(this).tooltip({
                        container: 'body'
                    });
                });
            }, 200);


            //ColVis extension
            var colvis = new $.fn.dataTable.ColVis(oTable1, {
                "buttonText": "<i class='fa fa-search'></i>",
                "aiExclude": [0, 6],
                "bShowAll": true,
                //"bRestore": true,
                "sAlign": "right",
                "fnLabel": function(i, title, th) {
                    return $(th).text(); //remove icons, etc
                }

            });

            //style it
            $(colvis.button()).addClass('btn-group').find('button').addClass('btn btn-white btn-info btn-bold')

            //and append it to our table tools btn-group, also add tooltip
            $(colvis.button())
                .prependTo('.tableTools-container .btn-group')
                .attr('title', 'Show/hide columns').tooltip({
                    container: 'body'
                });

            //and make the list, buttons and checkboxed Ace-like
            $(colvis.dom.collection)
                .addClass('dropdown-menu dropdown-light dropdown-caret dropdown-caret-right')
                .find('li').wrapInner('<a href="javascript:void(0)" />') //'A' tag is required for better styling
                .find('input[type=checkbox]').addClass('ace').next().addClass('lbl padding-8');


            /////////////////////////////////
            //table checkboxes
            $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

            //select/deselect all rows according to table header checkbox
            $('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function() {
                var th_checked = this.checked; //checkbox inside "TH" table header

                $(this).closest('table').find('tbody > tr').each(function() {
                    var row = this;
                    if (th_checked) tableTools_obj.fnSelect(row);
                    else tableTools_obj.fnDeselect(row);
                });
            });

            //select/deselect a row when the checkbox is checked/unchecked
            $('#dynamic-table').on('click', 'td input[type=checkbox]', function() {
                var row = $(this).closest('tr').get(0);
                if (!this.checked) tableTools_obj.fnSelect(row);
                else tableTools_obj.fnDeselect($(this).closest('tr').get(0));
            });


            $(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
                e.stopImmediatePropagation();

                e.stopPropagation();
                e.preventDefault();
            });


            //And for the first simple table, which doesn't have TableTools or dataTables
            //select/deselect all rows according to table header checkbox
            var active_class = 'active';
            $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function() {
                var th_checked = this.checked; //checkbox inside "TH" table header

                $(this).closest('table').find('tbody > tr').each(function() {
                    var row = this;
                    if (th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
                    else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
                });
            });

            //select/deselect a row when the checkbox is checked/unchecked
            $('#simple-table').on('click', 'td input[type=checkbox]', function() {
                var $row = $(this).closest('tr');
                if (this.checked) $row.addClass(active_class);
                else $row.removeClass(active_class);
            });


            $('#company_logo , #id-input-file-2').ace_file_input({
                no_file: 'No File ...',
                btn_choose: 'Choose',
                btn_change: 'Change',
                droppable: false,
                onchange: null,
                thumbnail: false //| true | large
                //whitelist:'gif|png|jpg|jpeg'
                //blacklist:'exe|php'
                //onchange:''
                //
            });
            /********************************/
            //add tooltip for small view action buttons in dropdown menu
            $('[data-rel="tooltip"]').tooltip({
                placement: tooltip_placement
            });

            //tooltip placement on right or left
            function tooltip_placement(context, source) {
                var $source = $(source);
                var $parent = $source.closest('table')
                var off1 = $parent.offset();
                var w1 = $parent.width();

                var off2 = $source.offset();
                //var w2 = $source.width();

                if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
                return 'left';
            }

        })
    </script>


</body>

</html>
<?php
// mysqli_free_result($getallclients);
// mysqli_free_result($getcompanys2);
// mysqli_free_result($getcountries2);
// mysqli_free_result($getindustries);


?>