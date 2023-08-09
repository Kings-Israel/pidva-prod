<?php require_once('../../Connections/connect.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connect,$theValue) : mysqli_escape_string($connect,$theValue);

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

if ((isset($_GET['inst_id'])) && ($_GET['inst_id'] != "")) {

if($_GET['status']=='11')
{
 $deleteSQL = sprintf("UPDATE pel_edu_institution SET status=%s, verified_by=%s, verified_date=%s WHERE inst_id=%s",
                       GetSQLValueString($_GET['status'], "text"),
					   GetSQLValueString($_GET['fullnames'], "text"),
					   GetSQLValueString(date('d-m-Y H:m:s'), "text"),
					   GetSQLValueString($_GET['inst_id'], "int"));
					   }
				if($_GET['status']=='00' || $_GET['status']=='22')
{
					   
  $deleteSQL = sprintf("UPDATE pel_edu_institution SET status=%s, added_by=%s, added_date=%s  WHERE inst_id=%s",
                        GetSQLValueString($_GET['status'], "text"),
					   GetSQLValueString($_GET['fullnames'], "text"),
					   GetSQLValueString(date('d-m-Y H:m:s'), "text"),
					   GetSQLValueString($_GET['inst_id'], "int"));
					   }

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));

  $deleteGoTo = "institution.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}


if ((isset($_GET['inst_course_id'])) && ($_GET['inst_course_id'] != "")) {

if($_GET['status']=='11')
{
 $deleteSQL = sprintf("UPDATE pel_edu_inst_courses SET status=%s, verified_by=%s, verified_date=%s WHERE inst_course_id=%s",
                       GetSQLValueString($_GET['status'], "text"),
					   GetSQLValueString($_GET['fullnames'], "text"),
					   GetSQLValueString(date('d-m-Y H:m:s'), "text"),
					   GetSQLValueString($_GET['inst_course_id'], "int"));
					   }
					   else
					   {
					   
  $deleteSQL = sprintf("UPDATE pel_edu_inst_courses SET status=%s, added_by=%s, added_date=%s WHERE inst_course_id=%s",
                       GetSQLValueString($_GET['status'], "text"),
					   GetSQLValueString($_GET['fullnames'], "text"),
					   GetSQLValueString(date('d-m-Y H:m:s'), "text"),
					   GetSQLValueString($_GET['inst_course_id'], "int"));
					   
					   }

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));

  $deleteGoTo = "institution.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}


$errorcode='';

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editinst")) {
if (is_uploaded_file($_FILES['inst_logo']['tmp_name'])) {
date_default_timezone_set('Africa/Nairobi');
$date_insert = date('d-m-Y h:i:s');
$a = $_POST['inst_code']; 
$rawname = $_FILES['inst_logo']['name'];
 	  "Upload: ".$a."_". $_FILES["inst_logo"]["name"];
	  $file="logoimages/".$a."_". $_FILES["inst_logo"]["name"];

    require_once "../../uploads.php";
    $filenameuploaded = uploadFile("inst_logo","institution-logoimages",$a."_".$_FILES["inst_logo"]["name"]);

}
else
{
$filenameuploaded = $_POST['inst_logo2']; 
}


  $updateSQL = sprintf("UPDATE pel_edu_institution SET inst_name=%s, inst_registration_number=%s, status=%s, inst_registered_date=%s, inst_mobile_number=%s, inst_bio=%s, inst_logo=%s, inst_country=%s, added_by=%s, inst_campus=%s, added_date=%s, inst_code=%s, inst_email_address=%s, inst_town=%s WHERE inst_id=%s",
                       GetSQLValueString(strtoupper($_POST['inst_name']), "text"),
                       GetSQLValueString(strtoupper($_POST['inst_registration_number']), "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['inst_registered_date'], "text"),
                       GetSQLValueString($_POST['inst_mobile_number'], "text"),
                       GetSQLValueString($_POST['inst_bio'], "text"),
                       GetSQLValueString($filenameuploaded, "text"),
                       GetSQLValueString($_POST['inst_country'], "text"),
                       GetSQLValueString($_POST['added_by'], "text"),
                       GetSQLValueString(strtoupper($_POST['inst_campus']), "text"),
                       GetSQLValueString($_POST['added_date'], "text"),
                       GetSQLValueString(strtoupper($_POST['inst_code']), "text"),
                       GetSQLValueString($_POST['inst_email_address'], "text"),
                       GetSQLValueString(strtoupper($_POST['inst_town']), "text"),
                       GetSQLValueString($_POST['inst_id'], "int"));

  mysqli_select_db($connect,$database_connect); 
  mysqli_query_ported($updateSQL, $connect);
  
//echo $Result1 = mysqli_query_ported($insertSQL, $connect)or die(mysqli_error($connect));
  if (mysqli_error($connect))
  {
$errorcode='<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												Oh snap!
											</strong>

											ERROR!!!!! Institution Name or Institution Code already exisiting.
											<br />
										</div>';
}
else
{
$insertGoTo = "institution.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
 }
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newinst")) {
if (is_uploaded_file($_FILES['inst_logo']['tmp_name'])) {
date_default_timezone_set('Africa/Nairobi');
$date_insert = date('d-m-Y h:i:s');
$a = $_POST['inst_code']; 
$rawname = $_FILES['inst_logo']['name'];
 	  "Upload: ".$a."_". $_FILES["inst_logo"]["name"];
	  $file="logoimages/".$a."_". $_FILES["inst_logo"]["name"];
	

    require_once "../../uploads.php";
    $filenameuploaded = uploadFile("inst_logo","institution-logoimages",$a."_".$_FILES["inst_logo"]["name"]);

}
else
{
$filenameuploaded = ""; 
}

  $insertSQL = sprintf("INSERT INTO pel_edu_institution (inst_name, inst_registration_number, status, inst_registered_date, inst_mobile_number, inst_bio, inst_logo, inst_country, added_by, inst_campus, added_date, inst_code, inst_email_address, inst_town) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString(strtoupper($_POST['inst_name']), "text"),
                       GetSQLValueString(strtoupper($_POST['inst_registration_number']), "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['inst_registered_date'], "text"),
                       GetSQLValueString($_POST['inst_mobile_number'], "text"),
                       GetSQLValueString($_POST['inst_bio'], "text"),
                       GetSQLValueString($filenameuploaded, "text"),
                       GetSQLValueString($_POST['inst_country'], "text"),
                       GetSQLValueString($_POST['added_by'], "text"),
                       GetSQLValueString(strtoupper($_POST['inst_campus']), "text"),
                       GetSQLValueString($_POST['added_date'], "text"),
                       GetSQLValueString(strtoupper($_POST['inst_code']), "text"),
                       GetSQLValueString($_POST['inst_email_address'], "text"),
                       GetSQLValueString(strtoupper($_POST['inst_town']), "text"));
 mysqli_select_db($connect,$database_connect); 
  mysqli_query_ported($insertSQL, $connect);

  if (mysqli_error($connect))
  {
$errorcode='<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												ERROR!!!!!
											</strong>

											 Institution Name or Institution Code already exisiting.
											<br />
										</div>';

}
else
{

  $updateGoTo = "institution.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  }

}

mysqli_select_db($connect,$database_connect);
$query_getallinst = "SELECT * FROM pel_edu_institution ORDER BY inst_name ASC";
$getallinst = mysqli_query_ported($query_getallinst, $connect) or die(mysqli_error($connect));
$row_getallinst = mysqli_fetch_assoc($getallinst);
$totalRows_getallinst = mysqli_num_rows($getallinst);

mysqli_select_db($connect,$database_connect);
$query_getcourses = "SELECT * FROM pel_edu_courses ORDER BY faculty_name ASC";
$getcourses = mysqli_query_ported($query_getcourses, $connect) or die(mysqli_error($connect));
$row_getcourses = mysqli_fetch_assoc($getcourses);
$totalRows_getcourses = mysqli_num_rows($getcourses);

mysqli_select_db($connect,$database_connect);
$query_getallplans = "SELECT * FROM pel_plans ORDER BY plan_name ASC";
$getallplans = mysqli_query_ported($query_getallplans, $connect) or die(mysqli_error($connect));
$row_getallplans = mysqli_fetch_assoc($getallplans);
$totalRows_getallplans = mysqli_num_rows($getallplans);

$query_getallinst2 = "SELECT * FROM pel_edu_institution ORDER BY inst_name ASC";
$getallinst2 = mysqli_query_ported($query_getallinst2, $connect) or die(mysqli_error($connect));
$row_getallinst2 = mysqli_fetch_assoc($getallinst2);
$totalRows_getallinst2 = mysqli_num_rows($getallinst2);

$query_getallinst3 = "SELECT * FROM pel_edu_institution ORDER BY inst_name ASC";
$getallinst3 = mysqli_query_ported($query_getallinst3, $connect) or die(mysqli_error($connect));
$row_getallinst3 = mysqli_fetch_assoc($getallinst3);
$totalRows_getallinst3 = mysqli_num_rows($getallinst3);

$query_getcountries = "SELECT * FROM pel_countries ORDER BY country_name ASC";
$getcountries = mysqli_query_ported($query_getcountries, $connect) or die(mysqli_error($connect));
$row_getcountries = mysqli_fetch_assoc($getcountries);
$totalRows_getcountries = mysqli_num_rows($getcountries);



?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Institution - Peleza Admin</title>

		<meta name="description" content="Static &amp; Dynamic Tables" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../../assets/css/bootstrap.css" />
		<link rel="stylesheet" href="../../assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
        <link rel="stylesheet" href="../../assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="../../assets/css/chosen.css" />
		<link rel="stylesheet" href="../../assets/css/datepicker.css" />
		<link rel="stylesheet" href="../../assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="../../assets/css/daterangepicker.css" />
		<link rel="stylesheet" href="../../assets/css/bootstrap-datetimepicker.css" />
		<link rel="stylesheet" href="../../assets/css/colorpicker.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="../../assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
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
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
<?php include('../header2.php');?>
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar                  responsive">
			  <script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>
              <?php include('../sidebarmenu2.php');?>
                
                
	<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
			  <script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
		  </div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
					  <script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>							</li>

							<li>
								<a href="#">Configurations</a>							</li>
                                <li>
						  <a href="#">Education</a>							</li>
							<li class="active">Institution</li>
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
                                    <div  class="col-xs-6">
                                    
									  <h3 align="left" class="header smaller lighter blue">EDUCATION INSTITUTION MANAGEMENT</h3>
                                      </div>
                                           
                                     
                                            <div  class="col-xs-6">    
                                        <h3 align="right" class="header smaller lighter blue">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
								 <?php
if (in_array('ADD_MODULES_CONFIGURATION', $roledata)) 
{
?>	<a href="#modal-newinst" role="button" class="green" data-toggle="modal">	
                                  <button class="btn btn-white btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>Add New Institution</button></a>&nbsp;<!--<a href="#modal-assigncourse" role="button" class="green" data-toggle="modal">	
                                  <button class="btn btn-success btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>Assign Course</button></a>&nbsp;--><a href="addinstitutioncourse.php" role="button" class="green">	
                                  <button class="btn btn-success btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>Assign Course</button></a> <?php
}

?>&nbsp; <?php
if (in_array('ASSIGN_PLANS_MODULES', $roledata)) 
{
?><!--<a href="#modal-assignplan" role="button" class="green" data-toggle="modal">	
                                  <button class="btn btn-success btn-info btn-bold">
											  <i class="ace-icon bigger-120 green"></i>Assign Plan</button></a>--> <a href="addinstitutionplans.php" role="button" class="green">	
                                  <button class="btn btn-success btn-info btn-bold">
											  <i class="ace-icon bigger-120 green"></i>Assign Plan</button></a><?php
}

?></h3>
</div>
<div class="col-xs-12">

<?php
echo $errorcode;

?>
</div>
<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
									  </div>
										<div class="table-header">
									  Results for "Institutions configured"										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<!--<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>															</label>-->				NO:                                                   	  </th>
													  <th>Institution Name</th>
														<th>Institution Code</th>
                                                        <th>Campus</th>
                                                        <th>Country</th>
												
														<th class="hidden-480">Status</th>
                                                         <th>Courses Offered</th>
                                                         <th>Plans Assigned</th>

													  <th>Action</th>
													</tr>
												</thead>

												<tbody>
                                                  <?php
												  
												  $x=1;												  
												  do { ?>
                                                  <tr>
                                                     	<td class="center">
														 <!--	<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>	-->	<?php echo $x++; ?>													</label>														</td>
                                                      
                                                      <td>
                                                        <a href="#"><?php echo $row_getallinst['inst_name']; ?></a>														</td>
                                                      <td><?php echo $row_getallinst['inst_code']; ?></td>
                                                      <td><?php echo $row_getallinst['inst_campus']; ?></td>
                                                      <td><?php echo $row_getallinst['inst_country']; ?></td>
                                                      
                                                    
                                                      
                                                      <td class="hidden-480"><?php 
														
														if($row_getallinst['status']=='11')
														{
														?>
                                                        
                                                        <span class="label label-sm label-success">Active</span>	
                                                        <?php
														}
														if($row_getallinst['status']=='00')
														{
														?>
                                                        <span class="label label-sm label-danger">Deactivated</span>	
                                                         <?php
														}	
														if($row_getallinst['status']=='22')
														{
														?>
                                                        <span class="label label-sm label-warning">Unverified</span>	
                                                         <?php
														}	
														?>  </td>
                                                        
                                                          <td><a href="viewinstitutioncourses.php?inst_id=<?php echo $row_getallinst['inst_id']; ?>">	   <span class="label label-sm label-primary">View Courses</span></a></td>
                                                            <td><a href="viewinstitutionplans.php?inst_id=<?php echo $row_getallinst['inst_id']; ?>">	   <span class="label label-sm label-purple">View Plans</span></a></td>
                                                       
                           
<td>
                                                          <div class="hidden-sm hidden-xs action-buttons">
                                                                                                                        
                                                                     <a href="#modal-viewinstiution-<?php echo $row_getallinst['inst_id']; ?>" role="button" class="green" data-toggle="modal">	    <button class="btn btn-xs btn-primary">  
																		<i class="ace-icon fa fa-search-plus bigger-130"></i>																	</button></a>  
                                                                 
                                                                 
            
                                                                 
                                                                  <div id="modal-viewinstiution-<?php echo $row_getallinst['inst_id']; ?>" class="modal fade" tabindex="-1">
            
                                
                                
                                
									<div class="modal-dialog"><div class="modal-content">
                                    
                                    <div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
											VIEW INSTITUTION DETAILS											</div>
											</div>
                                            
                                            <div class="modal-body padding">	 
                                            
    
                                      <table width="100%" border="0">
  <tr>
    <td><strong>Institution Name:</strong></td>
    <td><?php echo $row_getallinst['inst_name']; ?></td>
    <td><strong>Institution Code:</strong></td>
    <td><?php echo $row_getallinst['inst_code']; ?></td>
  </tr>
  <tr>
    <td colspan="4"><br/></td>
  </tr>
   <tr>
    <td><strong>Institution Reg Number:</strong></td>
    <td><?php echo $row_getallinst['inst_registration_number']; ?></td>
    <td><strong>Registered Date:</strong></td>
    <td><?php echo $row_getallinst['inst_registered_date']; ?></td>
  </tr>
  <tr>
    <td colspan="4"><br/></td>
  </tr>
   <tr>
    <td><strong>Campus:</strong></td>
    <td><?php echo $row_getallinst['inst_campus']; ?></td>
    <td><strong>Country:</strong></td>
    <td><?php echo $row_getallinst['inst_country']; ?></td>
  </tr><tr>
    <td colspan="4"><br/></td>
  </tr>
    <tr>
    <td><strong>Town Located:</strong></td>
    <td><?php echo $row_getallinst['inst_town']; ?></td>
    <td><strong>Mobile Number:</strong></td>
    <td><?php echo $row_getallinst['inst_mobile_number']; ?></td>
  </tr><tr>
    <td colspan="4"><br/></td>
  </tr>
   
  <tr><td><strong>Email Address:</strong></td>
    <td><?php echo $row_getallinst['inst_email_address']; ?></td>
    <td><strong>Institution Status:</strong></td>
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php 		if($row_getallinst['status']=='11')
														{
														?>
                                                       ACTIVE <?php
														}
														if($row_getallinst['status']=='00')
														{
														?>
                                                DEACTIVATED

                                                         <?php
														}
														if($row_getallinst['status']=='22')
														{
														?>
                                                   UNVERIFIED

                                                         <?php
														}
												?>  </button></td>
  </tr>
  <tr>
    <td colspan="4"><br/></td>
  </tr>
  <tr>
    <td width="20%"><strong>BIO:</strong></td>
    <td  width="30%"><?php echo $row_getallinst['inst_bio']; ?></td>
    <td width="20%"><strong>LOGO:</strong></td>
    <td  width="30%"><img src="<?php echo $row_getallinst['inst_logo']; ?>" alt="Institution Logo"></td>
  </tr><tr>
    <td colspan="4"><br/></td>
  </tr>
    <tr>
    <td><strong>Added By:</strong></td>
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getallinst['added_by']; ?></button></td>
    <td><strong>Added Date:</strong></td>
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getallinst['added_date']; ?></button></td>
  </tr>
  <tr>
    <td colspan="4"><br/></td>
  </tr>
    <tr>
    <td><strong>Verified By:</strong></td>
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getallinst['verified_by']; ?></button></td>
    <td><strong>Verified Date:</strong></td>
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getallinst['verified_date']; ?></button></td>
  </tr><tr>
    <td colspan="4"><br/></td>
  </tr>
</table>
											    </div>
                                            <div class="modal-footer margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>
											</div></div></div>
								</div><!-- PAGE CONTENT ENDS -->                                                     
                                                                     <?php
if (in_array('EDIT_MODULES_CONFIGURATION', $roledata)) 
{
?>                                             
                                           
                                                            <a href="#modal-editinst-<?php echo $row_getallinst['inst_id']; ?>" role="button" class="green" data-toggle="modal">	    <button class="btn btn-xs btn-info">  
															<i class="ace-icon fa fa-pencil bigger-120"></i>															</button></a>  
                                                            <?php
															}
															?>
                                   
                                <div id="modal-editinst-<?php echo $row_getallinst['inst_id']; ?>" class="modal fade" tabindex="-1">
            
                                
                                
                                
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
													Edit Institution												</div>
											</div>
                                            
                                            <div class="modal-body padding">	  <form enctype="multipart/form-data" method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="editinst" >
										<input type="hidden" id="inst_id" name="inst_id" value="<?php echo $row_getallinst['inst_id']; ?>"/>
                                        
                                        <input type="hidden" id="status" name="status" value="22"/>
     <input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names']."(".$_SESSION['MM_USR_EMAIL'].")"; ?>"/>
           <input type="hidden" id="added_date" name="added_date" value="<?php echo date('d-m-Y H:m:s');?>"/>
                                      
																							<div class="space-10"></div>

											
																<label class="col-sm-4">Institution Name</label>

																<div class="col-sm-8"><span id="sprytextfield10">
																  <input type="text" id="inst_name" name="inst_name" value="<?php echo $row_getallinst['inst_name']; ?>" readonly/>
											    <span class="textfieldRequiredMsg">*</span></span></div>
															  
															 
						<br/>
															<div class="space-10"></div>

														
																<label class="col-sm-4">Institution Code</label>

															  <div class="col-sm-8"><span id="sprytextfield11">
															    <input type="text" id="inst_code" name="inst_code" value="<?php echo $row_getallinst['inst_code']; ?>" />
														      <span class="textfieldRequiredMsg">*</span></span></div>
							
                                
                                	<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Registration Number</label>

															  <div class="col-sm-8">
															    <input value="<?php echo $row_getallinst['inst_registration_number']; ?>" type="text" id="inst_registration_number" name="inst_registration_number" />
														     </div>
							
                                
                                	<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Registration Date</label>

															  <div class="col-sm-8">
														      <input placeholder="dd-mm-yyyy" id="inst_registered_date" name="inst_registered_date" type="text" value="<?php echo $row_getallinst['inst_registered_date']; ?>"/>
															</div>
								<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Country</label>

															  <div class="col-sm-8"><span id="spryselect1">
                                                              
                                                              	<select class="chosen-select form-control" name="inst_country" id="inst_country" data-placeholder="Choose Country...">
                                                                <option value="<?php echo $row_getallinst['inst_country']; ?>"><?php echo $row_getallinst['inst_country']; ?></option><option value="000"></option>
															
														<!--      <select name="inst_country" id="inst_country">-->
														        
														         <?php 
																 $query_getcountries2 = "SELECT * FROM pel_countries ORDER BY country_name ASC";
$getcountries2 = mysqli_query_ported($query_getcountries2, $connect) or die(mysqli_error($connect));
$row_getcountries2 = mysqli_fetch_assoc($getcountries2);
$totalRows_getcountries2 = mysqli_num_rows($getcountries2);
																 
																 do { ?> 
                                                                 <option value="<?php echo $row_getcountries2['country_name']; ?>"><?php echo $row_getcountries2['country_name']; ?></option> 
                                                                   <?php } while ($row_getcountries2 = mysqli_fetch_assoc($getcountries2)); ?>
                                                              </select>
															 
														    <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
								<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Campus</label>

															  <div class="col-sm-8"><span id="sprytextfield14">
															   <input id="inst_campus" name="inst_campus" type="text" value="<?php echo $row_getallinst['inst_campus']; ?>"/>
															  <span class="textfieldRequiredMsg">*</span></span></div>
									<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Town Located</label>

															  <div class="col-sm-8">
															   <input id="inst_town" name="inst_town" type="text" value="<?php echo $row_getallinst['inst_town']; ?>"/>
															</div>
							
                              	<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Mobile Number</label>

															  <div class="col-sm-8">
															   <input id="inst_mobile_number" name="inst_mobile_number" type="text" value="<?php echo $row_getallinst['inst_mobile_number']; ?>"/>
																</div>
							
                              	<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Email Address</label>

															  <div class="col-sm-8">
															   <input id="inst_email_address" name="inst_email_address" type="text" value="<?php echo $row_getallinst['inst_email_address']; ?>"/>
																</div>
									<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Institution Logo</label>

														<div class="col-sm-8">
															 Currennt Logo <br/>
                                                             	<img width="80px" height="80px" src="<?php echo $row_getallinst['inst_logo']; ?>" alt="Institution Logo">
                                                                 
                                                  <input id="inst_logo2" name="inst_logo2" type="hidden" value="<?php echo $row_getallinst['inst_logo']; ?>"/>    
											  </div>
                                                                
                                                                	<br/>
                                                                
															<div class="space-10"></div> 
                                                               <label class="col-sm-4">Click to Change Logo</label>
                                                                <div class="col-sm-8">
															          <input id="inst_logo" name="inst_logo" type="file"/>
																</div>
								<br/>
															<div class="space-10"></div>

														<label class="col-sm-4">Institution Data</label>

															  <div class="col-sm-8">
														<textarea name="inst_bio" id="inst_bio" cols="60" rows="3"><?php echo $row_getallinst['inst_bio']; ?></textarea>
														</div>
							
                                                 <br/><br/>   <br/><br/>
															<div class="space-10"></div>  
                                                            	
															<div class="space-10"></div>         
                                              <div class="clearfix form-actions">
													<div class="col-md-offset-3 col-md-9">
                                                    	<button onClick="submit" type="submit" value="submit" class="btn btn-info">
														<!--<button onClick="submit" class="btn btn-info" type="button">-->
															<i class="ace-icon fa fa-check bigger-110"></i>
															Save														</button>

											       
<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
															Reset														</button>
													</div>
                                                </div>
							
										         <input type="hidden" name="MM_update" value="editinst">
										         <input type="hidden" name="MM_insert" value="editinst">
							  </form>  
						 </div>
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>
											</div></div></div>
								</div><!-- PAGE CONTENT ENDS -->                             
                                                                
                                                                
                                                                
                                                                    <?php 
														
if (in_array('DEACTIVATE_MODULES_CONFIGURATION', $roledata)) 
{

														if($row_getallinst['status']=='11')
														{
														?>
                                                        <a href="institution.php?fullnames=<?php echo $_SESSION['MM_full_names']."(".$_SESSION['MM_USR_EMAIL'].")"; ?>&status=00&inst_id=<?php echo $row_getallinst['inst_id']; ?>"> <button class="btn btn-xs btn-danger">
																<i class="ace-icon fa fa-trash-o bigger-120"></i>															</button></a>  <?php
														}
														}
														
															 if (in_array('ACTIVATE_MODULES_CONFIGURATION', $roledata) && $row_getallinst['added_by']!=$_SESSION['MM_full_names']) 
{
														if($row_getallinst['status']=='00')
														{
														?>
                                                    <a href="institution.php?fullnames=<?php echo $_SESSION['MM_full_names']."(".$_SESSION['MM_USR_EMAIL'].")"; ?>&status=22&inst_id=<?php echo $row_getallinst['inst_id']; ?>"> <button class="btn btn-xs btn-success">
																<i class="ace-icon fa fa-check bigger-120"></i>															</button></a>

                                                         <?php
														}
														}
														
															 if (in_array('VERIFY_MODULES_CONFIGURATION', $roledata) && $row_getallinst['added_by']!=$_SESSION['MM_full_names']) 
{
														if($row_getallinst['status']=='22')
														{
														?>
                                                    <a href="institution.php?fullnames=<?php echo $_SESSION['MM_full_names']."(".$_SESSION['MM_USR_EMAIL'].")"; ?>&status=11&inst_id=<?php echo $row_getallinst['inst_id']; ?>"> <button class="btn btn-xs btn-warning">
																<i class="ace-icon fa fa-check bigger-120"></i>															</button></a>

                                                         <?php
														}
														}
												?>                  
                                                        </div>
                                                        <div class="hidden-md hidden-lg">
                                                          <div class="inline pos-rel">
                                                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                                              <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>																	</button>
      
															  <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
															    <li>
																      <a href="#" class="tooltip-info" data-rel="tooltip" title="View">
																	      <span class="blue">
														        <i class="ace-icon fa fa-search-plus bigger-120"></i>																				</span>																			</a>																		</li>
                                                                            <li>
																	  <a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																		    
																	      <span class="green">
																		      <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>																				</span>																			</a>																		</li>
      
																  <li>
																	  <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																	      <span class="red">
																		      <i class="ace-icon fa fa-trash-o bigger-120"></i>																				</span>																			</a>																		</li>
														      </ul>
												          </div>
										                </div></td>
                                                  </tr>
                                                    <?php } while ($row_getallinst = mysqli_fetch_assoc($getallinst)); ?>
											  </tbody>
											</table>
										</div>
									</div>
							  </div>
                              		<div id="modal-newinst" class="modal fade" tabindex="-1">
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
													Add New Institution												</div>
											</div>	<div class="modal-body no-padding">
                                            
                                            
                                          	  <form  enctype="multipart/form-data" method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="newinst" >
										
                                    <input type="hidden" id="status" name="status" value="22"/>
     <input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names']."(".$_SESSION['MM_USR_EMAIL'].")"; ?>"/>
           <input type="hidden" id="added_date" name="added_date" value="<?php echo date('d-m-Y H:m:s');?>"/>
															<div class="space-10"></div>

											  <div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Institution Name</label>

																<div class="col-sm-9"><span id="sprytextfield1">
																  <input type="text" id="inst_name" name="inst_name" />
											    <span class="textfieldRequiredMsg">*</span></span></div>
							    </div>

															<div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Instituion Code</label>

															  <div class="col-sm-9"><span id="sprytextfield2">
															    <input type="text" id="inst_code" name="inst_code" />
														      <span class="textfieldRequiredMsg">*</span></span></div>
								</div>
                                
                                	<div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Registration Number</label>

															  <div class="col-sm-9">
															    <input type="text" id="inst_registration_number" name="inst_registration_number" />
													</div>
								</div>
                                
                                <div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Registration Date</label>

															  <div class="col-sm-9">
														      <input placeholder="dd-mm-yyyy" id="inst_registered_date" name="inst_registered_date" type="text"/>
														</div>
								</div>
                                
                                	<div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Country</label>

															  <div class="col-sm-6"><span id="spryselect2">
															
														   
                                                              	<select class="chosen-select form-control" name="inst_country" id="inst_country" data-placeholder="Choose Country...">
															
														        <option value="000"></option>
														         <?php do { ?> 
                                                                 <option value="<?php echo $row_getcountries['country_name']; ?>"><?php echo $row_getcountries['country_name']; ?></option> 
                                                                   <?php } while ($row_getcountries = mysqli_fetch_assoc($getcountries)); ?>
                                                              </select>
															 
														    <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
								</div>
                                
                                <div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Campus</label>

															  <div class="col-sm-9"><span id="sprytextfield5">
															   <input id="inst_campus" name="inst_campus" type="text"/>
															  <span class="textfieldRequiredMsg">*</span></span></div>
								</div>
                                
                                <div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Town Located</label>

															  <div class="col-sm-9">
															   <input id="inst_town" name="inst_town" type="text"/>
															</div>
								</div>
                                
                                  <div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Mobile Number</label>

															  <div class="col-sm-9">
															   <input id="inst_mobile_number" name="inst_mobile_number" type="text"/>
														</div>
								</div>
                                 <div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Email Address</label>

															  <div class="col-sm-9">
															   <input id="inst_email_address" name="inst_email_address" type="text"/>
																</div>
								</div>
                                
                                <div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Institution Logo</label>

															  <div class="col-sm-9">	<div class="col-sm-5">
															   <input id="inst_logo" name="inst_logo" type="file"/>
																</div></div>
								</div>
                                
                                <div class="space-4"></div>


															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Institution Data</label>

															  <div class="col-sm-9">
														<textarea name="inst_bio" id="inst_bio" cols="45" rows="6"></textarea>
														</div>
								</div>
                                                            
                                              <div class="clearfix form-actions">
													<div class="col-md-offset-3 col-md-9">
                                                    	<button onClick="submit" type="submit" value="submit" class="btn btn-info">
														<!--<button onClick="submit" class="btn btn-info" type="button">-->
															<i class="ace-icon fa fa-check bigger-110"></i>
															Save														</button>

											       
<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
															Reset														</button>
													</div>
                                                </div>
							
										        <input type="hidden" name="MM_insert" value="newinst">
							  </form>  
                                            
                                         
                                            
                                            </div>
                                            
                                            
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>
											</div>
                                            
                                            </div>
                                      </div>
								</div><!-- PAGE CONTENT ENDS -->

                                
                                
                                	<div id="modal-assigncourse" class="modal fade" tabindex="-1">
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
													ASSIGN COURSE TO INSTITUTION											</div>
											</div>	<div class="modal-body no-padding">
                                            
                                            
                                          	  <form method="POST" action="" class="form-horizontal" name="assigncourse" >
										
                                    <input type="hidden" id="status" name="status" value="22"/>
     <input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names']."(".$_SESSION['MM_USR_EMAIL'].")"; ?>"/>
           <input type="hidden" id="added_date" name="added_date" value="<?php echo date('d-m-Y H:m:s');?>"/>
															<div class="space-10"></div>

				
															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Institution</label>

															  <div class="col-sm-6"><span id="spryselect3">
															   	<select class="chosen-select form-control" name="institution_id" id="institution_id" data-placeholder="Choose Institution...">
															
														        <option value="000"></option>
														 <!--     <select name="institution_id" id="institution_id">
														        <option value="000">Select Institution</option>-->
														         <?php do { ?> 
                                                                 <option value="<?php echo $row_getallinst2['inst_id']; ?>"><?php echo $row_getallinst2['inst_name']; ?></option> 
                                                                   <?php } while ($row_getallinst2 = mysqli_fetch_assoc($getallinst2)); ?>
                                                              </select>
															 
														    <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
								</div>
                                
                                <div class="space-4"></div>

																<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Assign Course</label>

															  <div class="col-sm-6"><span id="spryselect4">
															  	<select class="chosen-select form-control" name="course_id" id="course_id" data-placeholder="Choose Course...">
															
														        <option value="000"></option>
														<!--      <select name="course_id" id="course_id">
														        <option value="000">Select Course</option>-->
														         <?php do { ?> 
                                                                 <option value="<?php echo $row_getcourses['course_id']; ?>"><?php echo $row_getcourses['course_name']; ?>-(<?php echo $row_getcourses['faculty_name']; ?>)</option> 
                                                                   <?php } while ($row_getcourses = mysqli_fetch_assoc($getcourses)); ?>
                                                              </select>
															 
														    <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
								</div>
                                
                                    <div class="space-4"></div>

																<div class="form-group">
																<label class="col-sm-6 control-label no-padding-right" for="form-field-pass2">SELECT COURSE</label>

								</div>  
                                
                                <div class="clearfix form-actions">
													<div class="col-md-offset-3 col-md-9">
                                                    	<button onClick="submit" type="submit" value="submit" class="btn btn-info">
														<!--<button onClick="submit" class="btn btn-info" type="button">-->
															<i class="ace-icon fa fa-check bigger-110"></i>
															Save														</button>

											       
<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
															Reset														</button>
													</div>
                                                </div>
							
										        <input type="hidden" name="MM_insert" value="assigncourse">
							  </form>  
                                            
                                         
                                            
                                            </div>
                                            
                                            
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>
											</div>
                                            
                                            </div>
                                      </div>
								</div><!-- PAGE CONTENT ENDS -->


	<div id="modal-assignplan" class="modal fade" tabindex="-1">
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
													ASSIGN PLANS TO INSTITUTIONS											</div>
											</div>	<div class="modal-body no-padding">
                                            
                                            
                                          	  <form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="assignplan" >
										
                                    <input type="hidden" id="status" name="status" value="22"/>
     <input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names']."(".$_SESSION['MM_USR_EMAIL'].")"; ?>"/>
           <input type="hidden" id="added_date" name="added_date" value="<?php echo date('d-m-Y H:m:s');?>"/>
															<div class="space-10"></div>

				
															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Institution</label>

															  <div class="col-sm-6"><span id="spryselect3">
															   	<select class="chosen-select form-control" name="institution_id" id="institution_id" data-placeholder="Choose Institution...">
															
														        <option value="000"></option>
														 <!--     <select name="institution_id" id="institution_id">
														        <option value="000">Select Institution</option>-->
														         <?php do { ?> 
                                                                 <option value="<?php echo $row_getallinst3['inst_id']; ?>"><?php echo $row_getallinst3['inst_name']; ?></option> 
                                                                   <?php } while ($row_getallinst3 = mysqli_fetch_assoc($getallinst3)); ?>
                                                              </select>
															 
														    <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
								</div>
                                
                                <div class="space-4"></div>

																<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Assign Plan</label>

															  <div class="col-sm-6"><span id="spryselect4">
															  	<select class="chosen-select form-control" name="plan_id" id="plan_id" data-placeholder="Choose Course...">
															
														        <option value="000"></option>
														<!--      <select name="course_id" id="course_id">
														        <option value="000">Select Course</option>-->
														         <?php do { ?> 
                                                                 <option value="<?php echo $row_getallplans['plan_id']; ?>"><?php echo $row_getallplans['plan_name']; ?>-(<?php echo $row_getallplans['plan_cost']; ?>)</option> 
                                                                   <?php } while ($row_getallplans = mysqli_fetch_assoc($getallplans)); ?>
                                                              </select>
															 
														    <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
								</div>
                                
                               
                                                            
                                              <div class="clearfix form-actions">
													<div class="col-md-offset-3 col-md-9">
                                                    	<button onClick="submit" type="submit" value="submit" class="btn btn-info">
														<!--<button onClick="submit" class="btn btn-info" type="button">-->
															<i class="ace-icon fa fa-check bigger-110"></i>
															Save														</button>

											       
<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
															Reset														</button>
													</div>
                                                </div>
							
										        <input type="hidden" name="MM_insert" value="assignplan">
							  </form>  
                                            
                                         
                                            
                                            </div>
                                            
                                            
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>
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
							Admin &copy; 2018						</span>

&nbsp;&nbsp;											</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i></a>		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='../../assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../../assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
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
				.dataTable( {
					bAutoWidth: false,
					"aoColumns": [
				null,null,null, null,null, null,null,null,
					  { "bSortable": false }
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
			    } );
				//oTable1.fnAdjustColumnSizing();
			
			
				//TableTools settings
				TableTools.classes.container = "btn-group btn-overlap";
				TableTools.classes.print = {
					"body": "DTTT_Print",
					"info": "tableTools-alert gritter-item-wrapper gritter-info gritter-center white",
					"message": "tableTools-print-navbar"
				}
			
				//initiate TableTools extension
				var tableTools_obj = new $.fn.dataTable.TableTools( oTable1, {
					"sSwfPath": "../../assets/js/dataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf", //in Ace demo ../assets will be replaced by correct assets path
					
					"sRowSelector": "td:not(:last-child)",
					"sRowSelect": "multi",
					"fnRowSelected": function(row) {
						//check checkbox when row is selected
						try { $(row).find('input[type=checkbox]').get(0).checked = true }
						catch(e) {}
					},
					"fnRowDeselected": function(row) {
						//uncheck checkbox
						try { $(row).find('input[type=checkbox]').get(0).checked = false }
						catch(e) {}
					},
			
					"sSelectedClass": "success",
			        "aButtons": [
						{
							"sExtends": "copy",
							"sToolTip": "Copy to clipboard",
							"sButtonClass": "btn btn-white btn-primary btn-bold",
							"sButtonText": "<i class='fa fa-copy bigger-110 pink'></i>",
							"fnComplete": function() {
								this.fnInfo( '<h3 class="no-margin-top smaller">Table copied</h3>\
									<p>Copied '+(oTable1.fnSettings().fnRecordsTotal())+' row(s) to the clipboard.</p>',
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
			    } );
				//we put a container before our table and append TableTools element to it
			    $(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));
				
				//also add tooltips to table tools buttons
				//addding tooltips directly to "A" buttons results in buttons disappearing (weired! don't know why!)
				//so we add tooltips to the "DIV" child after it becomes inserted
				//flash objects inside table tools buttons are inserted with some delay (100ms) (for some reason)
				setTimeout(function() {
					$(tableTools_obj.fnContainer()).find('a.DTTT_button').each(function() {
						var div = $(this).find('> div');
						if(div.length > 0) div.tooltip({container: 'body'});
						else $(this).tooltip({container: 'body'});
					});
				}, 200);
				
				
				
				//ColVis extension
				var colvis = new $.fn.dataTable.ColVis( oTable1, {
					"buttonText": "<i class='fa fa-search'></i>",
					"aiExclude": [0, 6],
					"bShowAll": true,
					//"bRestore": true,
					"sAlign": "right",
					"fnLabel": function(i, title, th) {
						return $(th).text();//remove icons, etc
					}
					
				}); 
				
				//style it
				$(colvis.button()).addClass('btn-group').find('button').addClass('btn btn-white btn-info btn-bold')
				
				//and append it to our table tools btn-group, also add tooltip
				$(colvis.button())
				.prependTo('.tableTools-container .btn-group')
				.attr('title', 'Show/hide columns').tooltip({container: 'body'});
				
				//and make the list, buttons and checkboxed Ace-like
				$(colvis.dom.collection)
				.addClass('dropdown-menu dropdown-light dropdown-caret dropdown-caret-right')
				.find('li').wrapInner('<a href="javascript:void(0)" />') //'A' tag is required for better styling
				.find('input[type=checkbox]').addClass('ace').next().addClass('lbl padding-8');
			
			
				
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) tableTools_obj.fnSelect(row);
						else tableTools_obj.fnDeselect(row);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(!this.checked) tableTools_obj.fnSelect(row);
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
				$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
						else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
					var $row = $(this).closest('tr');
					if(this.checked) $row.addClass(active_class);
					else $row.removeClass(active_class);
				});
			
				
			$('#inst_logo , #id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
				/********************************/
				//add tooltip for small view action buttons in dropdown menu
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				
				//tooltip placement on right or left
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			})
		</script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="../../assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="../../docs/assets/js/themes/sunburst.css" />

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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["change"]});


var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "none");



var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {invalidValue:"000", validateOn:["change"]});


var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "none", {validateOn:["change"]});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11", "none", {validateOn:["change"]});

var sprytextfield14 = new Spry.Widget.ValidationTextField("sprytextfield14", "none", {validateOn:["change"]});



var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2", {invalidValue:"000", validateOn:["change"]});
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3", {invalidValue:"000", validateOn:["change"]});
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4", {invalidValue:"000", validateOn:["change"]});


//-->
</script>
</body>
</html>
<?php
mysqli_free_result($getallinst);

mysqli_free_result($getcourses);

mysqli_free_result($getallplans);

mysqli_free_result($getallinst2);

mysqli_free_result($getallinst3);

mysqli_free_result($getcountries);

mysqli_free_result($getcountries2);
?>
