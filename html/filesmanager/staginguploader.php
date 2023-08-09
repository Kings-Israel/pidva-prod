<?php require_once('../../Connections/connect.php'); ?>
<?php
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
mysqli_select_db($connect, $database_connect);
$query_getmodules = "SELECT * FROM pel_module WHERE module_name = 'EDUCATION'";
$getmodules = mysqli_query_ported($query_getmodules, $connect) or die(mysqli_error($connect));
$row_getmodules = mysqli_fetch_assoc($getmodules);
$totalRows_getmodules = mysqli_num_rows($getmodules);

mysqli_select_db($connect, $database_connect);
$query_getinst = "SELECT inst_name FROM pel_edu_institution ORDER BY inst_name ASC";
$getinst = mysqli_query_ported($query_getinst, $connect) or die(mysqli_error($connect));
$row_getinst = mysqli_fetch_assoc($getinst);
$totalRows_getinst = mysqli_num_rows($getinst);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <title>Upload Education Staging Data- Peleza Admin</title>

    <meta name="description" content="Common form elements and layouts"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../assets/css/font-awesome.css"/>

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="../../assets/css/jquery-ui.custom.css"/>
    <link rel="stylesheet" href="../../assets/css/chosen.css"/>
    <link rel="stylesheet" href="../../assets/css/datepicker.css"/>
    <link rel="stylesheet" href="../../assets/css/bootstrap-timepicker.css"/>
    <link rel="stylesheet" href="../../assets/css/daterangepicker.css"/>
    <link rel="stylesheet" href="../../assets/css/bootstrap-datetimepicker.css"/>
    <link rel="stylesheet" href="../../assets/css/colorpicker.css"/>

    <!-- text fonts -->
    <link rel="stylesheet" href="../../assets/css/ace-fonts.css"/>

    <!-- ace styles -->
    <link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style"/>

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../../assets/css/ace-part2.css" class="ace-main-stylesheet"/>
    <![endif]-->

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../../assets/css/ace-ie.css"/>
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="../../assets/js/ace-extra.js"></script>
    <script type="text/javascript" src="xlsx.core.min.js"></script>
    <script type="text/javascript" src="excelplus-2.3.min.js"></script>


    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="../../assets/js/html5shiv.js"></script>
    <script src="../../assets/js/respond.js"></script>
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
                        <a href="#">File Manager</a></li>


                    <li class="active">Upload Education Staging Data</li>
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

                                    <h3 align="left" class="header smaller lighter blue">EDUCATION BIG STAGING DATA</h3>
                                </div>
                                <!-- <div  class="col-xs-6">
                             <h3 align="right" class="header smaller lighter blue">
                         <i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
                         <a href="filesupload.php">
                       <button class="btn btn-white btn-info btn-bold">
                                     <i class="ace-icon bigger-120 green"></i>	 Upload New Data File
</button></a></h3>   </div>-->

                                <div class="clearfix">

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <!-- PAGE CONTENT BEGINS -->


                                            <h3><strong>Please read the instructions before proceeding.</strong></h3>
                                            <h5>NOTES:</h5>
                                            <ul>
                                                <li>- Ensure that you have an CSV file with Student Details</li>
                                                <li>- Be Careful while Naming your File: ensure there no spaces,use the
                                                    underscore character and always follow the naming convention.
                                                </li>
                                                <li>- Columns in the File should be:
                                                    file_source,graduation_date,graduation_congregration,inst_code
                                                    inst_name,faculty_code,faculty_name,course_code,course_name,specialization_code,specialization_name,student_first_name,student_reg_number,level_code,level_name,award_code,award_name
                                                </li>
                                                <li class="style1">- All Columns Should Have a Value</li>
                                            </ul>

                                            <hr color="#CCCCCC" size="1px">
                                            <?php

                                            ini_set('max_execution_time', 10000);

                                            //this verify is the file csv upload
                                            if (isset($_POST['file_token'])) {

                                                if (is_uploaded_file($_FILES['id-input-file-2']['tmp_name'])) {
                                                    /*
                                                    require_once('Connections/conn.php');

                                                    $linkdata = mysqli_pconnect("localhost", "root", "mysql")
                                                    or die('Could not connect: ' . mysqli_error($connect));

                                                    mysqli_select_db("peleza_db") or die('Could not select database');*/


                                                    $STAFF_ID = $_SESSION['MM_USR_STAFF_ID'];
                                                    date_default_timezone_set('Africa/Nairobi');
                                                    $date_insert = date('d-m-Y h:i:s');

                                                    $uploadedby = $_SESSION['MM_full_names'];
                                                    $USR_ID = $_SESSION['MM_USR_ID'];
                                                    $file_token = $_POST['file_token'];
                                                    $file_source = strtoupper($_POST['file_source']);
                                                    $module_id = $_POST['module_id'];
                                                    $module_name = $_POST['module_name'];
                                                    $institution_name = $_POST['institution_name'];
                                                    $graduation_date = $_POST['graduation_date'];
                                                    $graduation_congregration = strtoupper($_POST['graduation_congregration']);
                                                    $date_insert2 = date('dmYhis');
                                                    $file_blockchain = "STAGINGDATA_" . $date_insert2 . "" . $institution_name . "" . $_POST['file_token'];

                                                    $ext = strtolower(end(explode('.', $_FILES['id-input-file-2']['name'])));

                                                    $n = str_replace(' ', '_', $_FILES["id-input-file-2"]["name"]);
                                                    $a = "STAGING_" . $STAFF_ID . "_" . $date_insert2 . "_" . $n;
                                                    $path = "/var/www/html/pidva/html/filesmanager/datafiles/$a";

                                                    $rawname = $_FILES['id-input-file-2']['name'];

                                                    $file = "stagingfiles/" . $a . "_" . $_FILES["id-input-file-2"]["name"];

                                                    move_uploaded_file($_FILES["id-input-file-2"]["tmp_name"], $path);


                                                    $filenameuploaded = $path;//$a."_".str_replace(' ','_',$_FILES["id-input-file-2"]["name"]);

                                                    $sha1name = sha1($_FILES['id-input-file-2']['name']);
                                                    $sha1content = sha1($date_insert . "" . file_get_contents($file));

                                                    /* $file=$_FILES['userfile']['tmp_name'];*/

                                                    $randomid = rand(7, 4000);
                                                    $csv_file = "stagingfiles/" . $a . "_" . $_FILES["id-input-file-2"]["name"];


                                                    mysqli_select_db($connect, $database_connect);
                                                    $query_getinst2 = "SELECT inst_code FROM pel_edu_institution where inst_name ='$institution_name'";
                                                    $getinst2 = mysqli_query_ported($query_getinst2, $connect) or die(mysqli_error($connect));
                                                    $row_getinst2 = mysqli_fetch_assoc($getinst2);
                                                    $totalRows_getinst2 = mysqli_num_rows($getinst2);
                                                    $inst_code = $row_getinst2['inst_code'];

                                                    if ($ext == 'csv') {
                                                        ?>
                                                        <table width="100%" border="0" cellpadding="1" cellspacing="1">
                                                            <tr>
                                                                <td>
                                                                    <div align="center"><h2 class="title"><a href="#">FILE
                                                                                UPLOADED</a></h2></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div align="center"><a href="#">Scroll down to
                                                                            process data</a></div>
                                                                    <br/></td>
                                                            </tr>
                                                        </table>
                                                        <table id="dynamic-table"
                                                               class="table table-striped table-bordered table-hover">

                                                            <thead>
                                                            <tr>
                                                                <th>INSTITUTION:</th>
                                                                <th>GRADUATION DATE:</th>
                                                                <th>CONGR DATE</th>
                                                                <th>FILE SOURCE:</th>
                                                            </tr>
                                                            </thead>

                                                            <tr>
                                                                <td><?php echo $institution_name; ?></td>
                                                                <td><?php echo $graduation_date; ?></td>
                                                                <td><?php echo $graduation_congregration; ?></td>
                                                                <td><?php echo $file_source; ?></td>
                                                            </tr>
                                                            <?php

                                                            $approved_by = "MARITA MUTEMI";
                                                            $verified_by = "JOHN MUCHIRI";

                                                            $sql_insert = "INSERT INTO pel_data_files (file_name,file_token, file_source, added_date, added_by, file_blockchain, module_id,module_name, shafile,graduation_congregration, institution_name, approved_by, approved_date, verified_by, verified_date, graduation_date, institution_code) VALUES ('$filenameuploaded', '$file_token','$file_source', '$date_insert', '$uploadedby', '$file_blockchain', '$module_id', '$module_name','$sha1content','$graduation_congregration','$institution_name','$approved_by', '$date_insert',  '$verified_by',  '$date_insert',  '$graduation_date', '$inst_code')";
                                                            //	$result_insert = mysqli_query_ported($sql_insert, $conn) or die(mysqli_error($connect));

                                                            $result = mysqli_query_ported($sql_insert, $connect) or die('Query failed: ' . mysqli_error($connect));


                                                            ?>
                                                        </table>
                                                        <table id="simple-table"
                                                               class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                            <td><b class="green">FILE:</b></td>
                                                            <td><b class="black"><?php echo $filenameuploaded; ?></b>
                                                            </td>

                                                            <!--        <td><b class="green"><a href="uploads.php">Upload Another file</a></b></td>-->
                                                            <td><b class="green"><a href="student_data.php">
                                                                        <button class="btn btn-sm btn-success pull-left">
                                                                            <i class="ace-icon fa fa-tick"></i>
                                                                            Upload Another File
                                                                        </button>
                                                                    </a></b></td>
                                                            <td><b class="green"><a
                                                                            href="deleteapistaging.php?method=inputfile&file_token=<?php echo $file_token . ""; ?>&filenameuploaded=<?php echo $filenameuploaded . ""; ?>">
                                                                        <button class="btn btn-sm btn-danger pull-left">
                                                                            <i class="ace-icon fa fa-times"></i>
                                                                            Reject File
                                                                        </button>
                                                                    </a></b></td>


                                                            </thead>
                                                        </table>
                                                        <?php
                                                    }

                                                } else {
                                                    ?>
                                                    <div class="alert alert-danger">
                                                        <button type="button" class="close" data-dismiss="alert">
                                                            <i class="ace-icon fa fa-times"></i></button>

                                                        <strong>
                                                            <i class="ace-icon fa fa-times"></i>
                                                            Oh snap! </strong>

                                                        eerror upload file <a href="student_data.php">Go Back</a>
                                                        <br/>
                                                    </div>
                                                    <?php

                                                    exit;
                                                }

                                            } else {

                                                $editFormAction = $_SERVER['PHP_SELF'];
                                                if (isset($_SERVER['QUERY_STRING'])) {
                                                    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
                                                }


//form to upload
                                                ?>

                                                <div>
                                                    <form name="forminst" action="<?php echo $editFormAction; ?>"
                                                          method="POST">


                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label no-padding-right"
                                                                   for="form-field-2">Specify Institution</label>

                                                            <div class="col-sm-4"> <span id="spryselect1">
															
														  <!--    <select class="col-xs-10 col-sm-5" name="institution_name" id="institution_name">-->
                                                               	<select class="select form-control"
                                                                        name="institution_name2" id="institution_name2"
                                                                        data-placeholder="Choose Institution..."
                                                                        onchange="this.form.submit()">
														       
														        <option value="000"></option>
														         <?php do { ?>
                                                                     <option value="<?php echo $row_getinst['inst_name']; ?>"><?php echo $row_getinst['inst_name']; ?></option>
                                                                 <?php } while ($row_getinst = mysqli_fetch_assoc($getinst)); ?>
                                                              </select>
															 
														    <span class="selectInvalidMsg">*</span><span
                                                                            class="selectRequiredMsg">*</span></span>

                                                            </div>
                                                        </div>


                                                        <input type="hidden" name="MM_insert2" value="forminst">
                                                    </form>


                                                </div>

                                                <br/>
                                                <br/>
                                                <div>

                                                    <form class="form-horizontal" ENCTYPE='multipart/form-data'
                                                          action='<?php echo $_SERVER["PHP_SELF"]; ?>' method='post'>
                                                        <!-- #section:elements.form -->

                                                        <?php


                                                        if ((isset($_POST["MM_insert2"])) && ($_POST["MM_insert2"] == "forminst")) {

                                                            ?>


                                                            <div class="space-4"></div>

                                                            <hr color="#ff0000" size="2px">

                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label no-padding-right"
                                                                       for="form-field-1">Institution Selected</label>

                                                                <div class="col-sm-9">

                                                                    <input type="text" id="institution_name"
                                                                           name="institution_name"
                                                                           class="col-xs-10 col-sm-5"
                                                                           value="<?php echo $instname = $_POST['institution_name2']; ?>"
                                                                           readonly/>
                                                                </div>
                                                            </div>


                                                            <div class="space-4"></div>

                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label no-padding-right"
                                                                       for="form-field-1"><strong>File TRACKER
                                                                        Number</strong></label>

                                                                <div class="col-sm-9">
                                                                    <?php


                                                                    function GeraHash2($qtd)
                                                                    {
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
                                                                        $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
                                                                        $QuantidadeCaracteres = strlen($Caracteres);
                                                                        $QuantidadeCaracteres--;

                                                                        $Hash = NULL;
                                                                        for ($x = 1; $x <= $qtd; $x++) {
                                                                            $Posicao = rand(0, $QuantidadeCaracteres);
                                                                            $Hash .= substr($Caracteres, $Posicao, 1);
                                                                        }

                                                                        return $Hash;
                                                                    }


                                                                    //Here you specify how many characters the returning string must have
                                                                    ?>
                                                                    <input value="<?php echo "PD-" . $_SESSION['MM_Username'] . "-" . GeraHash2(4) . "" . date('dmyhmss'); ?>"
                                                                           type="text" id="file_token" name="file_token"
                                                                           class="col-xs-10 col-sm-5" readonly/>

                                                                    <input value="<?php echo "PD" . GeraHash2(6); ?>"
                                                                           type="hidden" id="student_token"
                                                                           name="student_token"
                                                                           class="col-xs-10 col-sm-5" readonly/>
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label no-padding-right"
                                                                       for="form-field-1">Specify File Source</label>

                                                                <div class="col-sm-9"><span id="sprytextfield1">
																						     
										  <input type="text" id="file_source" name="file_source"
                                                 class="col-xs-10 col-sm-5"/> <span
                                                                                class="textfieldRequiredMsg">*</span></span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label no-padding-right"
                                                                       for="form-field-1-1">Data Module</label>
                                                                <input type="hidden" id="module_id" name="module_id"
                                                                       placeholder="Text Field"
                                                                       class="col-xs-10 col-sm-5" value="1"/>
                                                                <div class="col-sm-9"><span id="sprytextfield2">
										  <input value="EDUCATION" type="text" id="module_name" name="module_name"
                                                 placeholder="Text Field" class="col-xs-10 col-sm-5" readonly/> <span
                                                                                class="textfieldRequiredMsg">*</span></span>
                                                                </div>
                                                            </div>

                                                            <!-- /section:elements.form -->

                                                            <div class="space-4"></div>


                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label no-padding-right"
                                                                       for="form-field-1-1">Graduation Date</label>
                                                                <div class="col-xs-8 col-sm-6">
                                                                    <div class="input-group">
                                                                        <input class="form-control date-picker"
                                                                               type="text" id="graduation_date"
                                                                               name="graduation_date"
                                                                               data-date-format="dd-mm-yyyy"/>
                                                                        <span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="space-4"></div>

                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label no-padding-right"
                                                                       for="form-field-1-1">Graduation Congregation e.g
                                                                    54th, 23rd</label>
                                                                <div class="col-xs-8 col-sm-6">
                                                                    <div class="input-group">
                                                                        <input type="text" id="graduation_congregration"
                                                                               name="graduation_congregration"
                                                                               class="col-xs-10 col-sm-5"/>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="space-4"></div>

                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Upload Data File</label>

                                                                <div class="col-sm-6">
                                                                    <span id="sprytextfield2">
																	<input class="col-xs-10 col-sm-5"
                                                                           id="id-input-file-2" name="id-input-file-2"
                                                                           type="file"/>
															        <span class="textfieldRequiredMsg">*</span>
                                                                    </span>
                                                                </div>
                                                            </div>


                                                            <div class="space-4"></div>


                                                            <div class="clearfix form-actions">
                                                                <div class="col-md-offset-3 col-md-9">

                                                                    <button onClick="submit" type="submit"
                                                                            value="submit" class="btn btn-info">
                                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                                        Submit
                                                                    </button>

                                                                         
                                                                    <button class="btn" type="reset">
                                                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                        Reset
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <?php


                                                        }
                                                        ?>

                                                    </form>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>


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
    window.jQuery || document.write("<script src='../../assets/js/jquery1x.js'>" + "<" + "/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='../../assets/js/jquery.mobile.custom.js'>" + "<" + "/script>");
</script>
<script src="../../assets/js/bootstrap.js"></script>

<!-- page specific plugin scripts -->

<!-- page specific plugin scripts -->
<script src="../../assets/js/dataTables/jquery.dataTables.js"></script>
<script src="../../assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
<script src="../../assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
<script src="../../assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

<!--[if lte IE 8]>
<script src="../../assets/js/excanvas.js"></script>
<![endif]-->
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
    jQuery(function ($) {
        $('#id-disable-check').on('click', function () {
            var inp = $('#form-input-readonly').get(0);
            if (inp.hasAttribute('disabled')) {
                inp.setAttribute('readonly', 'true');
                inp.removeAttribute('disabled');
                inp.value = "This text field is readonly!";
            } else {
                inp.setAttribute('disabled', 'disabled');
                inp.removeAttribute('readonly');
                inp.value = "This text field is disabled!";
            }
        });


        if (!ace.vars['touch']) {
            $('.chosen-select').chosen({allow_single_deselect: true});
            //resize the chosen on window resize

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function () {
                    $('.chosen-select').each(function () {
                        var $this = $(this);
                        $this.next().css({'width': $this.parent().width()});
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function (e, event_name, event_val) {
                if (event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function () {
                    var $this = $(this);
                    $this.next().css({'width': $this.parent().width()});
                })
            });


            $('#chosen-multiple-style .btn').on('click', function (e) {
                var target = $(this).find('input[type=radio]');
                var which = parseInt(target.val());
                if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
                else $('#form-field-select-4').removeClass('tag-input-style');
            });
        }


        $('[data-rel=tooltip]').tooltip({container: 'body'});
        $('[data-rel=popover]').popover({container: 'body'});

        $('textarea[class*=autosize]').autosize({append: "\n"});
        $('textarea.limited').inputlimiter({
            remText: '%n character%s remaining...',
            limitText: 'max allowed : %n.'
        });

        $.mask.definitions['~'] = '[+-]';
        $('.input-mask-date').mask('99/99/9999');
        $('.input-mask-phone').mask('(999) 999-9999');
        $('.input-mask-eyescript').mask('~9.99 ~9.99 999');
        $(".input-mask-product").mask("a*-999-a999", {
            placeholder: " ", completed: function () {
                alert("You typed the following: " + this.val());
            }
        });


        $("#input-size-slider").css('width', '200px').slider({
            value: 1,
            range: "min",
            min: 1,
            max: 8,
            step: 1,
            slide: function (event, ui) {
                var sizing = ['', 'input-sm', 'input-lg', 'input-mini', 'input-small', 'input-medium', 'input-large', 'input-xlarge', 'input-xxlarge'];
                var val = parseInt(ui.value);
                $('#form-field-4').attr('class', sizing[val]).val('.' + sizing[val]);
            }
        });

        $("#input-span-slider").slider({
            value: 1,
            range: "min",
            min: 1,
            max: 12,
            step: 1,
            slide: function (event, ui) {
                var val = parseInt(ui.value);
                $('#form-field-5').attr('class', 'col-xs-' + val).val('.col-xs-' + val);
            }
        });


        //"jQuery UI Slider"
        //range slider tooltip example
        $("#slider-range").css('height', '200px').slider({
            orientation: "vertical",
            range: true,
            min: 0,
            max: 100,
            values: [17, 67],
            slide: function (event, ui) {
                var val = ui.values[$(ui.handle).index() - 1] + "";

                if (!ui.handle.firstChild) {
                    $("<div class='tooltip right in' style='display:none;left:16px;top:-6px;'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>")
                        .prependTo(ui.handle);
                }
                $(ui.handle.firstChild).show().children().eq(1).text(val);
            }
        }).find('span.ui-slider-handle').on('blur', function () {
            $(this.firstChild).hide();
        });


        $("#slider-range-max").slider({
            range: "max",
            min: 1,
            max: 10,
            value: 2
        });

        $("#slider-eq > span").css({width: '90%', 'float': 'left', margin: '15px'}).each(function () {
            // read initial values from markup and remove that
            var value = parseInt($(this).text(), 10);
            $(this).empty().slider({

                value: value,
                range: "min",
                animate: true

            });
        });

        $("#slider-eq > span.ui-slider-purple").slider('disable');//disable third item

        $('#id-input-file-1 , #id-input-file-2').ace_file_input({
            no_file: 'No File ...',
            btn_choose: 'Choose',
            btn_change: 'Change',
            droppable: false,
            onchange: null,
            thumbnail: false, //| true | large
            whitelist: 'csv|xls|xlsx',
            blacklist: 'exe|php|mp4'
            //onchange:''
            //
        });
        //pre-show a file name, for example a previously selected file
        //$('#id-input-file-1').ace_file_input('show_file_list', ['myfile.txt'])


        $('#id-input-file-3').ace_file_input({
            style: 'well',
            btn_choose: 'Drop files here or click to choose',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small'//large | fit
            //,icon_remove:null//set null, to hide remove/reset button
            /**,before_change:function(files, dropped) {
						//Check an example below
						//or examples/file-upload.html
						return true;
					}*/
            /**,before_remove : function() {
						return true;
					}*/
            ,
            preview_error: function (filename, error_code) {
                //name of the file that failed
                //error_code values
                //1 = 'FILE_LOAD_FAILED',
                //2 = 'IMAGE_LOAD_FAILED',
                //3 = 'THUMBNAIL_FAILED'
                //alert(error_code);
            }

        }).on('change', function () {
            //console.log($(this).data('ace_input_files'));
            //console.log($(this).data('ace_input_method'));
        });


        //$('#id-input-file-3')
        //.ace_file_input('show_file_list', [
        //{type: 'image', name: 'name of image', path: 'http://path/to/image/for/preview'},
        //{type: 'file', name: 'hello.txt'}
        //]);


        //dynamically change allowed formats by changing allowExt && allowMime function
        $('#id-file-format').removeAttr('checked').on('change', function () {
            var whitelist_ext, whitelist_mime;
            var btn_choose
            var no_icon
            if (this.checked) {
                btn_choose = "Drop images here or click to choose";
                no_icon = "ace-icon fa fa-picture-o";

                whitelist_ext = ["jpeg", "jpg", "png", "gif", "bmp"];
                whitelist_mime = ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"];
            } else {
                btn_choose = "Drop files here or click to choose";
                no_icon = "ace-icon fa fa-cloud-upload";

                whitelist_ext = null;//all extensions are acceptable
                whitelist_mime = null;//all mimes are acceptable
            }
            var file_input = $('#id-input-file-3');
            file_input
                .ace_file_input('update_settings',
                    {
                        'btn_choose': btn_choose,
                        'no_icon': no_icon,
                        'allowExt': whitelist_ext,
                        'allowMime': whitelist_mime
                    })
            file_input.ace_file_input('reset_input');

            file_input
                .off('file.error.ace')
                .on('file.error.ace', function (e, info) {
                    //console.log(info.file_count);//number of selected files
                    //console.log(info.invalid_count);//number of invalid files
                    //console.log(info.error_list);//a list of errors in the following format

                    //info.error_count['ext']
                    //info.error_count['mime']
                    //info.error_count['size']

                    //info.error_list['ext']  = [list of file names with invalid extension]
                    //info.error_list['mime'] = [list of file names with invalid mimetype]
                    //info.error_list['size'] = [list of file names with invalid size]


                    /**
                     if( !info.dropped ) {
							//perhapse reset file field if files have been selected, and there are invalid files among them
							//when files are dropped, only valid files will be added to our file array
							e.preventDefault();//it will rest input
						}
                     */


                    //if files have been selected (not dropped), you can choose to reset input
                    //because browser keeps all selected files anyway and this cannot be changed
                    //we can only reset file field to become empty again
                    //on any case you still should check files with your server side script
                    //because any arbitrary file can be uploaded by user and it's not safe to rely on browser-side measures
                });

        });

        $('#spinner1').ace_spinner({
            value: 0,
            min: 0,
            max: 200,
            step: 10,
            btn_up_class: 'btn-info',
            btn_down_class: 'btn-info'
        })
            .closest('.ace-spinner')
            .on('changed.fu.spinbox', function () {
                //alert($('#spinner1').val())
            });
        $('#spinner2').ace_spinner({
            value: 0,
            min: 0,
            max: 10000,
            step: 100,
            touch_spinner: true,
            icon_up: 'ace-icon fa fa-caret-up bigger-110',
            icon_down: 'ace-icon fa fa-caret-down bigger-110'
        });
        $('#spinner3').ace_spinner({
            value: 0,
            min: -100,
            max: 100,
            step: 10,
            on_sides: true,
            icon_up: 'ace-icon fa fa-plus bigger-110',
            icon_down: 'ace-icon fa fa-minus bigger-110',
            btn_up_class: 'btn-success',
            btn_down_class: 'btn-danger'
        });
        $('#spinner4').ace_spinner({
            value: 0,
            min: -100,
            max: 100,
            step: 10,
            on_sides: true,
            icon_up: 'ace-icon fa fa-plus',
            icon_down: 'ace-icon fa fa-minus',
            btn_up_class: 'btn-purple',
            btn_down_class: 'btn-purple'
        });

        //$('#spinner1').ace_spinner('disable').ace_spinner('value', 11);
        //or
        //$('#spinner1').closest('.ace-spinner').spinner('disable').spinner('enable').spinner('value', 11);//disable, enable or change value
        //$('#spinner1').closest('.ace-spinner').spinner('value', 0);//reset to 0


        //datepicker plugin
        //link
        $('.date-picker').datepicker({
            autoclose: true,
            format: "d MM yyyy",
            todayHighlight: true
        })
        //show datepicker when clicking on the icon
            .next().on(ace.click_event, function () {
            $(this).prev().focus();
        });

        //or change it into a date range picker
        $('.input-daterange').datepicker({autoclose: true});


        //to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
        $('input[name=date-range-picker]').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            }
        })
            .prev().on(ace.click_event, function () {
            $(this).next().focus();
        });


        $('#timepicker1').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        }).next().on(ace.click_event, function () {
            $(this).prev().focus();
        });

        $('#date-timepicker1').datetimepicker().next().on(ace.click_event, function () {
            $(this).prev().focus();
        });


        $('#colorpicker1').colorpicker();

        $('#simple-colorpicker-1').ace_colorpicker();
        //$('#simple-colorpicker-1').ace_colorpicker('pick', 2);//select 2nd color
        //$('#simple-colorpicker-1').ace_colorpicker('pick', '#fbe983');//select #fbe983 color
        //var picker = $('#simple-colorpicker-1').data('ace_colorpicker')
        //picker.pick('red', true);//insert the color if it doesn't exist


        $(".knob").knob();


        var tag_input = $('#form-field-tags');
        try {
            tag_input.tag(
                {
                    placeholder: tag_input.attr('placeholder'),
                    //enable typeahead by specifying the source array
                    source: ace.vars['US_STATES'],//defined in ace.js >> ace.enable_search_ahead
                    /**
                     //or fetch data from database, fetch those that match "query"
                     source: function(query, process) {
						  $.ajax({url: 'remote_source.php?q='+encodeURIComponent(query)})
						  .done(function(result_items){
							process(result_items);
						  });
						}
                     */
                }
            )

            //programmatically add a new
            var $tag_obj = $('#form-field-tags').data('tag');
            $tag_obj.add('Programmatically Added');
        } catch (e) {
            //display a textarea for old IE, because it doesn't support this plugin or another one I tried!
            tag_input.after('<textarea id="' + tag_input.attr('id') + '" name="' + tag_input.attr('name') + '" rows="3">' + tag_input.val() + '</textarea>').remove();
            //$('#form-field-tags').autosize({append: "\n"});
        }


        /////////
        $('#modal-form input[type=file]').ace_file_input({
            style: 'well',
            btn_choose: 'Drop files here or click to choose',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'large'
        })

        //chosen plugin inside a modal will have a zero width because the select element is originally hidden
        //and its width cannot be determined.
        //so we set the width after modal is show
        $('#modal-form').on('shown.bs.modal', function () {
            if (!ace.vars['touch']) {
                $(this).find('.chosen-container').each(function () {
                    $(this).find('a:first-child').css('width', '210px');
                    $(this).find('.chosen-drop').css('width', '210px');
                    $(this).find('.chosen-search input').css('width', '200px');
                });
            }
        })
        /**
         //or you can activate the chosen plugin after modal is shown
         //this way select element becomes visible with dimensions and chosen works as expected
         $('#modal-form').on('shown', function () {
					$(this).find('.modal-chosen').chosen();
				})
         */



        $(document).one('ajaxloadstart.page', function (e) {
            $('textarea[class*=autosize]').trigger('autosize.destroy');
            $('.limiterBox,.autosizejs').remove();
            $('.daterangepicker.dropdown-menu,.colorpicker.dropdown-menu,.bootstrap-datetimepicker-widget.dropdown-menu').remove();
        });

    });
</script>
<script type="text/javascript">
    <!--
    var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn: ["change"]});
    var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn: ["change"]});
    var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {invalidValue: "000", validateOn: ["change"]});
    var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2", {invalidValue: "000", validateOn: ["change"]});
    var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3", {invalidValue: "000", validateOn: ["change"]});
    var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4", {invalidValue: "000", validateOn: ["change"]});
    var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5", {invalidValue: "000", validateOn: ["change"]});
    var spryselect6 = new Spry.Widget.ValidationSelect("spryselect6", {invalidValue: "000", validateOn: ["change"]});


    //-->
</script>
</body>
</html>
<?php
mysqli_free_result($getmodules);

mysqli_free_result($getinst);

?>
