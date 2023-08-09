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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "assignform")) {
  $updateSQL = sprintf("UPDATE pel_searches SET search_status=%s, user_id=%s, user_name=%s, user_lock=%s, user_lock_date=%s WHERE search_id=%s",
                       GetSQLValueString($_POST['search_status'], "text"),
                       GetSQLValueString($_POST['user_id'], "text"),
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($_POST['user_lock'], "text"),
					   GetSQLValueString($_POST['user_lock_date'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));

  $updateGoTo = "ed_requests.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "notifyform")) {

$filetracker = $_POST['file_tracker'];

$query_getfile = "SELECT * FROM pel_edu_data WHERE student_token='$filetracker'";
$getfile = mysqli_query_ported($query_getfile, $connect) or die(mysqli_error($connect));
$row_getfile = mysqli_fetch_assoc($getfile);
$totalRows_getfile = mysqli_num_rows($getfile);

  if ($totalRows_getfile) { 

$client_id = $_POST['client_id'];
$institution_name = $_POST['institution_name'];
$course_name = $_POST['course_name'];
$student_name = $_POST['student_name'];
$comments = $_POST['comments'];

$query_getclient = "SELECT * FROM pel_client WHERE client_id='$client_id'";
$getclient = mysqli_query_ported($query_getclient, $connect) or die(mysqli_error($connect));
$row_getclient = mysqli_fetch_assoc($getclient);
$totalRows_getclient = mysqli_num_rows($getclient);


$clientname =$row_getclient['client_first_name'];
$toemail= $row_getclient['client_email_address'];

require ("../../PHPMailer/PHPMailer.php");

require("../../PHPMailer/SMTP.php");
require("../../PHPMailer/Exception.php");

//$mail = new PHPMailer;
$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->isSMTP();   
//$mail->isMail();                          // Set mailer to use SMTP
$mail->Host = 'two.deepafrica.com';             // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                     // Enable SMTP authentication
$mail->Username = 'support@edcheckafrica.com';          // SMTP username
$mail->Password = '93l3z@1nt'; // SMTP password
$mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                          // TCP port to connect to

$mail->setFrom('support@edcheckafrica.com', 'EdCheck Portal Admin');

$mail->addAddress($toemail);
//$mail->addAddress('omintolbert@gmail.com');   // Add a recipient
//$mail->addCC('mwendemarita@gmail.com');
//$mail->addBCC('omintolbert@gmail.com');

$mail->isHTML(true);  // Set email format to HTML

$bodyContent = '<body>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td>Hi '.$clientname.',<br />
          <br />
        Your EdCheck Africa Academic search request has been completed for the below candidate and results are currently in the edcheck portal website. Please login to view full report.</td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
	 <td width="171" valign="middle"><br />
        <strong>STUDENT NAME:</strong>
        <br />      </td>
      <td width="251" valign="middle"><br />
      <strong>'.$student_name.'</strong><br />      </td>
      <td width="108" rowspan="3" valign="middle"><div align="right"><img src="http://edcheckafrica.com/edcheck/images/verified.png" alt="unverified" width="90" height="90" longdesc="http://edcheckafrica.com/edcheck/images/cancel.png"></div></td>
    </tr>
     <tr valign="top">
	 <td width="171" valign="middle"><br />
        <strong>COURSE NAME:</strong>
        <br />      </td>
      <td width="251" valign="middle"><br />
      <strong>'.$course_name.'</strong><br />      </td>
    </tr>
     <tr valign="top">
	 <td width="171" valign="middle"><br />
        <strong>INSTITUTION NAME:</strong>
        <br />      </td>
      <td width="251" valign="middle"><br />
      <strong>'.$institution_name.'</strong><br />      </td>
     </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td><br /></td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td></td>
    </tr>
    <tr>
      <td width="137"></td>
      <td width="216" align="center"><table align="center">
    <tr>
        <td style="background-color: #c2d247;border-color: #c2d247;border: 2px solid #c2d247;padding: 10px;text-align: center;">
            <a style="display: block;color: #094156;font-size: 12px;text-decoration: none;text-transform: uppercase;" href="www.edcheckafrica.com" target="_blank">Check Results</a>
        </td>
    </tr>
</table></td>
      <td width="137"></td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td></td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td><p><br />
          <strong><em>Thank you for trusting and working with Edcheck Africa.</em></strong></p>
        <br />
      </td>
    </tr>
   <tr>
      <td width="100%">Sincerely,<br />
        <strong>EdCheck Africa</strong> <br />
        Administrator <br />
        support@edcheckafrica.com<br />
        Westlands<br /><br />
        
		copyright©2018.All rights reserved.EdCheck Africa.<br />
          <br />
      </td>
    </tr>
  </tbody>
</table>';
//$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

$mail->Subject = 'Do Not Reply: EDCHECK SEARCH RESULTS';
$mail->Body    = $bodyContent;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
   // echo 'Message has been sent'; 

 					   $updateSQL = sprintf("UPDATE pel_searches SET search_status=%s, notify_by=%s, notify_date=%s, file_tracker=%s, comments=%s,student_id=%s WHERE search_id=%s",
                       GetSQLValueString($_POST['search_status'], "text"),
                       GetSQLValueString($_POST['notify_by'], "text"),
                       GetSQLValueString($_POST['notify_date'], "text"),
                       GetSQLValueString($_POST['file_tracker'], "text"),
					   GetSQLValueString($_POST['comments'], "text"),
					   GetSQLValueString($row_getfile['student_id'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));
 
  $updateGoTo = "ed_requests.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  }
  }
if($filetracker=='NO DATA')
  {
  
  $client_id = $_POST['client_id'];
$institution_name = $_POST['institution_name'];
$course_name = $_POST['course_name'];
$student_name = $_POST['student_name'];
$comments = $_POST['comments'];

$query_getclient = "SELECT * FROM pel_client WHERE client_id='$client_id'";
$getclient = mysqli_query_ported($query_getclient, $connect) or die(mysqli_error($connect));
$row_getclient = mysqli_fetch_assoc($getclient);
$totalRows_getclient = mysqli_num_rows($getclient);


$clientname =$row_getclient['client_first_name'];
$toemail= $row_getclient['client_email_address'];

require ("../../PHPMailer/PHPMailer.php");

require("../../PHPMailer/SMTP.php");
require("../../PHPMailer/Exception.php");

//$mail = new PHPMailer;
$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->isSMTP();   
//$mail->isMail();                          // Set mailer to use SMTP
$mail->Host = 'two.deepafrica.com';             // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                     // Enable SMTP authentication
$mail->Username = 'support@edcheckafrica.com';          // SMTP username
$mail->Password = '93l3z@1nt'; // SMTP password
$mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                          // TCP port to connect to

$mail->setFrom('support@edcheckafrica.com', 'EdCheck Portal Admin');

$mail->addAddress($toemail);
//$mail->addAddress('omintolbert@gmail.com');   // Add a recipient
//$mail->addCC('mwendemarita@gmail.com');
//$mail->addBCC('omintolbert@gmail.com');

$mail->isHTML(true);  // Set email format to HTML

$bodyContent = '<body>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td>Hi '.$clientname.',<br />
          <br />
        Your EdCehck Africa Academic search request has been completed for the below candidate and results are currently in the edcheck portal website. Please login to view full report.</td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
	 <td width="148" valign="middle"><br />
        <strong>STUDENT NAME:</strong>
        <br />      </td>
      <td width="274" valign="middle"><br />
      <strong>'.$student_name.'</strong><br />      </td>
      <td width="108" valign="middle"><div align="right"><img src="http://edcheckafrica.com/edcheck/images/cancel.png" alt="unverified" width="90" height="90" longdesc="http://edcheckafrica.com/edcheck/images/cancel.png"></div></td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td><br /></td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td></td>
    </tr>
    <tr>
      <td width="137"></td>
      <td width="216" align="center"><table align="center">
    <tr>
        <td style="background-color: #c2d247;border-color: #c2d247;border: 2px solid #c2d247;padding: 10px;text-align: center;">
            <a style="display: block;color: #094156;font-size: 12px;text-decoration: none;text-transform: uppercase;" href="www.edcheckafrica.com" target="_blank">Check Results</a>
        </td>
    </tr>
</table></td>
      <td width="137"></td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td></td>
    </tr>
  </tbody>
</table>
<table width="530" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr valign="top">
      <td><p><br />
          <strong><em>Thank you for trusting and working with Edcheck Africa.</em></strong></p>
        <br />
        </td>
    </tr>
   <tr>
      <td width="100%">Sincerely,<br />
        <strong>EdCheck Africa</strong> <br />
        Administrator <br />
        support@edcheckafrica.com<br />
        Westlands<br /><br />
        
		copyright©2018.All rights reserved.EdCheck Africa.<br />
          <br />
      </td>
    </tr>
  </tbody>
</table>';
//$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

$mail->Subject = 'Do Not Reply: EDCHECK SEARCH RESULTS';
$mail->Body    = $bodyContent;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
  //  echo 'Message has been sent'; 

$updateSQL = sprintf("UPDATE pel_searches SET search_status=%s, notify_by=%s, notify_date=%s, file_tracker=%s, comments=%s WHERE search_id=%s",
                       GetSQLValueString("22", "text"),
                       GetSQLValueString($_POST['notify_by'], "text"),
                       GetSQLValueString($_POST['notify_date'], "text"),
                       GetSQLValueString($_POST['file_tracker'], "text"),
					   GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));
 
  $updateGoTo = "ed_requests.php";
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


mysqli_select_db($connect,$database_connect);
$query_getallsearches = "SELECT * FROM pel_searches WHERE search_status IN ('33','44') ORDER BY search_date DESC";
$getallsearches = mysqli_query_ported($query_getallsearches, $connect) or die(mysqli_error($connect));
$row_getallsearches = mysqli_fetch_assoc($getallsearches);
$totalRows_getallsearches = mysqli_num_rows($getallsearches);




?><!DOCTYPE html>
<html lang="en">
<style type="text/css">
<!--
-->
</style>
<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Education Searches - Peleza Admin</title>

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
								<a href="#">Searches</a>							</li>
                             
							<li class="active">Edcheck Requests</li>
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
                                    <div  class="col-xs-12">
                                    
									  <h3 align="left" class="header smaller lighter blue">EDCHECK MANUAL REQUESTS</h3>
                                      </div>
                                        <!--    <div  class="col-xs-6">
                                        <h3 align="right" class="header smaller lighter blue">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
									<a href="#modal-newclient" role="button" class="green" data-toggle="modal">	
                                  <button class="btn btn-white btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>Add New Client
</button></a></h3>
                           
</div>-->

<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
									  </div>
										<div class="table-header">
									  Results for "Edcheck Customer Requests"										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<!--<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>															</label>-->				NO:									
                                                                
                                                   	  </th>
													  <th>Client Name</th>
                                                      
														<th>Student Name</th> 
                                                        <th>Country</th>
                                                        <th>Inst Name</th>
                                                      <th>Award</th>
                                                     
                                                         <th>Course</th>
                                                 
                                                        <th>Ref Number</th>
                                                       
                                                        <th>Date Searched</th>
												
													  <th class="hidden-480">Status</th>
                                                    
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
                                                        <a href="../clients/clientsusers.php"><?php echo $row_getallsearches['client_name']; ?></a>														</td>
                                                   
                                                      <td><?php echo $row_getallsearches['student_name']; ?></td>
                                                      <td><?php echo $row_getallsearches['search_country']; ?></td>
                                                      <td><?php echo $row_getallsearches['institution_name']; ?></td>    
                                              <td><?php echo $row_getallsearches['course_award']; ?></td>
                                                  <td><?php echo $row_getallsearches['course_name']; ?></td>
                                                    
                                                      <td><?php echo $row_getallsearches['search_ref_number']; ?></td>
                                                     
<!--   <td><a href="../payments/education.php"><?php echo $row_getallsearches['search_payment_ref']; ?></a></td>-->
                                                      <td><?php echo $row_getallsearches['search_date']; ?></td>
                                                      
                                                    
                                                      
                                                  <td class="hidden-480"><?php 
														
														if($row_getallsearches['search_status']=='44')
														{
														?>
                                                        
                                                        <span class="label label-sm label-warning">In Progress</span>	
                                                        <?php
														}
													    if($row_getallsearches['search_status']=='33')
														{
														?>
                                                        <span class="label label-sm label-purple">New Request</span>	
                                                         <?php
														}	
													
														?>  </td>
                                                        
 <td>
 <?php
 if($row_getallsearches['user_lock'] == '00')
 {


if (in_array('ASSIGN_MANUAL_SEARCH_REQUESTS', $roledata)) 
{
                            
 ?><form method="POST" action="<?php echo $editFormAction; ?>" id="assignform" name="assignform">

   <input type="hidden" id="ID" name="ID"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['search_id']; ?>"/>
   
     <input type="hidden" id="user_name" name="user_name"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_full_names']; ?>"/>
       <input type="hidden" id="user_id" name="user_id"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_USR_ID']; ?>"/>
       <input type="hidden" id="user_lock" name="user_lock"  class="col-xs-10 col-sm-5" value="11"/>
        <input type="hidden" id="user_lock_date" name="user_lock_date"  class="col-xs-10 col-sm-5" value="<?php echo date('d-m-Y'); ?>"/>
       
       <input type="hidden" id="search_status" name="search_status"  class="col-xs-10 col-sm-5" value="44"/>
<button  type="submit" id="submit" class="btn btn-xs btn-success" ><i class="ace-icon fa fa-check bigger-120"></i>
						  </button> 
<input type="hidden" name="MM_update" value="assignform">
 </form>
 <?php
 }
 }
 else
 {
 ?>
                                                                                                                                                                
                        

                                                                     <a href="#modal-viewrequest-<?php echo $row_getallsearches['search_id']; ?>"  role="button" class="green" data-toggle="modal">	    <button class="btn btn-xs btn-info">  
																		<i class="ace-icon fa fa-search-plus bigger-130"></i>																	</button></a>
                             	   <?php

if (in_array('NOTIFY_CLIENT_SEARCH_REQUESTS', $roledata)) 
{

?>                               
                            <a href="#modal-notify-<?php echo $row_getallsearches['search_id']; ?>" role="button" class="orange" data-toggle="modal">	 <button class="btn btn-xs btn-warning">
																<i class="ace-icon fa fa-flag bigger-120"></i>
							</button> </a>  
<?php
}
																		}
																		?>
                                                               
                                   
                                <div id="modal-viewrequest-<?php echo $row_getallsearches['search_id']; ?>" class="modal fade" tabindex="-1">
            
                                <div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
													VIEW REQUEST DATA											</div>
											</div>
                                            
                                            <div class="modal-body padding">	 
                                            
                                            <table align="center" width="80%" border="0" cellpadding="50" cellspacing="50" bordercolor="#DCDED3" bgcolor="#F3F6DB">
  <tr>
    <td width="50%"><div align="left"><img src="../../assets/images/EdCheck.png" alt="Edcheck Logo" width="168" height="45" /></div></td>
    <td width="50%"><div align="right"><strong><h1>STUDENT DETAILS</h1></strong></div></td>
  </tr>
  <tr>
    <td bgcolor="#7F869B"><span class="style3">REQUESTED BY:</span></td>
    <td bgcolor="#7F869B"><span class="style3">DATE:</span></td>
  </tr>
  <tr>
    <td bgcolor="#E4E69B"><strong>Name:</strong> <?php echo $row_getallsearches['client_name']; ?>
  </td>
    <td bgcolor="#E4E69B"><?php echo $row_getallsearches['search_date']; ?></td>
  </tr>
 
  <tr>
    <td bgcolor="#7F869B">DESCRIPTION:</td>
    <td bgcolor="#7F869B">INFORMATION:</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><strong>FULL NAME:</strong></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getallsearches['student_name']; ?></td>
  </tr>
 
  <tr>
    <td bgcolor="#FFFFFF"><strong>INSTITUTION:</strong></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getallsearches['institution_name']; ?></td>
  </tr>
  
  <tr>
    <td bgcolor="#FFFFFF"><strong>COURSE:</strong></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getallsearches['course_name']; ?></td>
  </tr>
    <tr>
    <td bgcolor="#FFFFFF"><strong>LEVEL:</strong></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getallsearches['course_level']; ?></td>
  </tr>
    <tr>
    <td bgcolor="#7F869B">STATUS</td>
    <td bgcolor="#7F869B">STAFF ASSIGNED</td>
  </tr>
  <tr>
    <td><strong><?php 
														
														if($row_getallsearches['search_status']=='44')
														{
														?>
                                                        
                                                       In Progress
                                                        <?php
														}
													    if($row_getallsearches['search_status']=='33')
														{
														?>
                                                    New Request
                                                         <?php
														}	
													
														?> </strong> 
  </td>
    <td><?php echo $row_getallsearches['user_name']; ?></td>
  </tr>
   <tr>
    <td>DATE ASSIGNED</td>
    <td><?php echo $row_getallsearches['user_lock_date']; ?></td>
  </tr>
 <tr> <td>CERTIFICATE</td>
    <td><div align="left"><a href="http://edcheckafrica.com/search/certfiles/<?php echo $row_getallsearches['student_certificate']; ?>" target="_blank" alt="Certificate"/><?php echo $row_getallsearches['student_certificate']; ?></a></div></td>
    
  </tr>
 
</table>
                                            
                                            
                                            </div>
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>

											
											</div></div></div>
                                  
								</div><!-- PAGE CONTENT ENDS -->              
                                                    </td>
                                                  </tr>
                                                  <div id="modal-notify-<?php echo $row_getallsearches['search_id']; ?>" class="modal fade" tabindex="-1">
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
												  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												  <span class="white">&times;</span>													</button>
												NOTIFY CUSTOMER											</div>
											</div>
                                            
                                            
                                            
                                            <div class="modal-body no-padding">	  <form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="notifyform" >
									<input type="hidden" id="ID" name="ID"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['search_id']; ?>"/>
                                    
                                    <input type="hidden" id="client_id" name="client_id"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['client_id']; ?>"/>
   
     <input type="hidden" id="notify_by" name="notify_by"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_full_names']; ?>"/>
       <input type="hidden" id="notify_date" name="notify_date"  class="col-xs-10 col-sm-5" value="<?php echo date('d-m-Y'); ?>"/>
              
       <input type="hidden" id="search_status" name="search_status"  class="col-xs-10 col-sm-5" value="11"/>

											   <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Student Name</label>
                                                                <input type="hidden" id="student_name" name="student_name"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['student_name']; ?>"/>

																<div class="col-sm-8">
																 <?php echo $row_getallsearches['student_name']; ?>
															   </div>
															  
															 
							   <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Institution Name</label>   <input type="hidden" id="institution_name" name="institution_name"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['institution_name']; ?>"/>

																<div class="col-sm-8">
																<?php echo $row_getallsearches['institution_name']; ?>
															   </div>
															  
															 
							      <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Course Name</label> <input type="hidden" id="course_name" name="course_name"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['course_name']; ?>"/>

																<div class="col-sm-8">
																<?php echo $row_getallsearches['course_name']; ?>
															   </div>
															  
															 
							     <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">File Track Number <span class="style1"><strong>(If No Data Type "NO DATA")</strong></span></label>

																<div class="col-sm-8"><span id="sprytextfield1">
																  <input type="text" id="file_tracker" name="file_tracker"  class="col-xs-10 col-sm-5" />
															    <span class="textfieldRequiredMsg">*</span></span></div>
															  
											     <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Comments</label>

																<div class="col-sm-8">
																 <textarea cols="40" rows="5" id="comments" name="comments"  class="col-xs-10 col-sm-5" ></textarea> 
															 </div>
															  
															 				 
							    <br/>
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
							
							                                <input type="hidden" name="MM_update" value="notifyform">
                                            </form></div>
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>

											
											</div></div></div>
                                  
							</div> 
                                                          
                                                    <?php } while ($row_getallsearches = mysqli_fetch_assoc($getallsearches)); ?>

													
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
				null,null,null, null,null, null,null,null,null,null,
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
			
				
			$('#client_parent_company , #id-input-file-2').ace_file_input({
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

//-->
</script>
</body>
</html>
<?php
mysqli_free_result($getallsearches);
?>
