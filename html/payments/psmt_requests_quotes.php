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


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "quoteform")) {
	
	$package_cost_currency = $_POST['package_cost_currency'];
	$request_ref_number = $_POST['request_ref_number'];
	$request_dataset_cat = $_POST['request_dataset_cat'];	
	$client_login_id = $_POST['client_login_id'];
	$package_cost = 0;
	
	
	
mysqli_select_db($connect,$database_connect);
$query_getprogress2= sprintf("SELECT pel_packages_module.modulepackage_id, pel_packages_module.cost_currency, pel_psmt_request_modules.request_package_id FROM pel_packages_module Inner Join pel_psmt_request_modules ON pel_psmt_request_modules.module_id = pel_packages_module.module_id
WHERE pel_psmt_request_modules.request_ref_number= %s AND pel_packages_module.package_name = %s", GetSQLValueString($request_ref_number, "text"), GetSQLValueString($request_dataset_cat, "text"));
$getprogress2 = mysqli_query_ported($query_getprogress2, $connect) or die(mysqli_error($connect));
$row_getprogress2 = mysqli_fetch_assoc($getprogress2);
$totalRows_getprogress2 = mysqli_num_rows($getprogress2);  
  
  do
 {

	$modulepackage_id = "price_".$row_getprogress2['modulepackage_id']; 
	$cost_currency_module = $row_getprogress2['cost_currency'];
	$modulepackage_id2 = $_POST[$modulepackage_id];
	
if($cost_currency_module == $package_cost_currency)
{
 $package_cost = $package_cost + $modulepackage_id2;
}
else
{
mysqli_select_db($connect,$database_connect);
$query_getprogress3= sprintf("SELECT pel_credits.credit_id, pel_credits.credit_name,pel_credits.credit_cost, pel_credits.credit_currency, pel_credits.credit_volume, pel_currency.currency_code, pel_currency.currency_name
FROM pel_credits Inner Join pel_currency ON pel_currency.currency_id = pel_credits.credit_currency WHERE pel_currency.currency_code= %s", GetSQLValueString($package_cost_currency, "text"));
$getprogress3 = mysqli_query_ported($query_getprogress3, $connect) or die(mysqli_error($connect));
$row_getprogress3 = mysqli_fetch_assoc($getprogress3);
$totalRows_getprogress3 = mysqli_num_rows($getprogress3);

mysqli_select_db($connect,$database_connect);
$query_getprogress4= sprintf("SELECT pel_credits.credit_id, pel_credits.credit_name,pel_credits.credit_cost, pel_credits.credit_currency, pel_credits.credit_volume, pel_currency.currency_code, pel_currency.currency_name
FROM pel_credits Inner Join pel_currency ON pel_currency.currency_id = pel_credits.credit_currency WHERE pel_currency.currency_code= %s", GetSQLValueString($cost_currency_module, "text"));
$getprogress4 = mysqli_query_ported($query_getprogress4, $connect) or die(mysqli_error($connect));
$row_getprogress4 = mysqli_fetch_assoc($getprogress4);
$totalRows_getprogress4 = mysqli_num_rows($getprogress4);
if($row_getprogress3['credit_cost']>= $row_getprogress4['credit_cost'])
{
$package_cost = $package_cost + ($modulepackage_id2*$row_getprogress3['credit_cost']);	

}
if($row_getprogress3['credit_cost']< $row_getprogress4['credit_cost'])
{
$package_cost = $package_cost + ($modulepackage_id2/$row_getprogress4['credit_cost']);	

}	
}

$updateSQL = sprintf("UPDATE pel_psmt_request_modules SET module_cost_quote=%s WHERE request_package_id=%s",
                       GetSQLValueString($modulepackage_id2, "text"),
                       GetSQLValueString($row_getprogress2['request_package_id'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));


 } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));

function GeraHash3($qtd){
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
$QuantidadeCaracteres = strlen($Caracteres);
$QuantidadeCaracteres--;

$Hash=NULL;
    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }

return $Hash;
}
$datenow = date('dmyhis');
$request_quotation_ref =  $client_login_id."-QT-".GeraHash3(4)."-".$datenow;

$package_cost2 = ($package_cost * 0.16) + $package_cost;
$updateSQL = sprintf("UPDATE pel_psmt_request SET quotation_by=%s, quotation_date=%s, package_cost=%s, request_quotation_ref=%s WHERE request_id=%s",
                       GetSQLValueString($_POST['quotation_by'], "text"),
                       GetSQLValueString($_POST['status_date'], "text"),
                       GetSQLValueString(round($package_cost2,2), "text"),
					   GetSQLValueString($request_quotation_ref, "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));


  $updateGoTo = "psmt_requests_quotes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_POST['request_ref_number2']))  {

if (is_uploaded_file($_FILES['id-input-file-2']['tmp_name'])) {

  date_default_timezone_set('Africa/Nairobi');

   $USR_ID = $_SESSION['MM_USR_ID']; 
 $date_insert2 = date('dmYhis');
 
  $ext = strtolower(end(explode('.', $_FILES['id-input-file-2']['name'])));

 $a = $USR_ID."_".$date_insert2;
 	  "Upload: ".$a."_". $_FILES["id-input-file-2"]["name"];
	  $rawname = $_FILES['id-input-file-2']['name'];
 $file="reportfiles/".$a."_". $_FILES["id-input-file-2"]["name"];


    require_once "../../uploads.php";
    $prefix = "d-input-file-2";
    $filenameuploaded = uploadFile($prefix,"payments-reportfiles",$a."_".$_FILES[$prefix]["name"]);


$client_id = $_POST['client_id'];
$bg_dataset_name = $_POST['bg_dataset_name'];
$request_plan = $_POST['request_plan'];
$request_ref_number = $_POST['request_ref_number'];


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

$bodyContent = '<p><img src="https://admin.pidva.africa/assets/images/PelezaLogo.png" width="166" height="60" /></p>
<p><strong>Hi '.$clientname.',</strong></p>
<p>The background search report for  Tolbert Derek Omini has been uploaded kindly login to view it.</p>
<p><strong><a href="https://psmt.pidva.africa">LOGIN</a></strong></p>
<p>  - The Peleza Team<br />
  Support: +254 796 111 020 or +254  Email:&nbsp;<a href="mailto:verify@peleza.com">verify@peleza.com</a>&nbsp;<br />
  ® Peleza Int, 2018. All rights reserved. </p>';
//$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

$mail->Subject = 'Confidential Background Check Report';
$mail->Body    = $bodyContent;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
   // echo 'Message has been sent'; 

 					   $updateSQL = sprintf("UPDATE pel_psmt_request SET status=%s, notify_by=%s, notify_date=%s, report_file=%s  WHERE request_id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['notify_by'], "text"),
                       GetSQLValueString($_POST['notify_date'], "text"),
                       GetSQLValueString($filenameuploaded, "text"),
					   GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
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


mysqli_select_db($connect,$database_connect);
$query_getallsearches = "SELECT * FROM pel_psmt_request ORDER BY request_date DESC, status ASC";
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
		<title>Psmt Requests Quotes - Peleza Admin</title>

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
								<a href="#">Peleza Payments</a>							</li>
                             
							<li class="active">Psmt Quotes</li>
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
                                    
									  <h3 align="left" class="header smaller lighter blue">PSMT QUOTES</h3>
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
									  Results for "Psmt Quotes"										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<!--<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>															</label>-->				NO:	 </th>
													  <th>Client Company</th>
                                                     <!-- <th>Client Name</th>   
                                                                                                         
													  <th>Data Category</th>-->
                                                        <th>Bg Dataset Name</th> 
                                                      <th>Package</th>
                                                      <th>Ref Number</th>
                                                      <th>Date Requested</th> 
                                                      <th>Status Date</th>
                                                      <th>Currency</th>
                                                      <?php 
														
														//if($row_getallsearches['status']=='55')
//														{
														?>
                                                     
                                                      	  <th class="hidden-480">Status</th>
                                                     
                                                        <?php
													//	}
//													    if($row_getallsearches['status']=='66' ||  $row_getallsearches['status']=='44'  ||  $row_getallsearches['status']=='33'  ||  $row_getallsearches['status']=='11')
//														{
														?>
                                                        <th class="hidden-480">Quotation Price</th> 
                                                        <th>Download Quote</th>
                                                         <th>Generate Quote</th>  
                                                      
                                                      <?php
													//	}
														?>
                                                 <!--       <th>Report</th>-->
												    
													</tr>
												</thead>

												<tbody>
                                                  <?php
												  
												  $x=1;												  
												  do { 
												  ?>
                                                  <tr>
                                                     	<td class="center">
														 <!--	<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>	-->	<?php echo $x++; ?>													</label>														</td>
                                                       <td>
                                                        <a href="../clients/companys.php"><?php echo $row_getallsearches['company_name']; ?></a>														</td>
                                                    <!--  <td>
                                                        <a href="../clients/clientsusers.php"><?php echo $row_getallsearches['client_name']; ?></a>														</td>
                                                   
                                                      <td><?php echo $row_getallsearches['dataset_name']; ?></td>-->
                                                         <td><?php echo $row_getallsearches['bg_dataset_name']; ?></td>
                                                      <td><?php echo $row_getallsearches['request_dataset_cat']; ?></td>
                                                
                                                         <td><?php echo $row_getallsearches['request_ref_number']; ?></td>   
                                                  <td><?php echo $row_getallsearches['request_date']; ?></td> 
                                                    <td><?php echo $row_getallsearches['status_date']; ?></td>
                                                        <td><?php echo $row_getallsearches['package_cost_currency']; ?></td>                        <!--  <td><a href="reportfiles/<?php echo $row_getallsearches['report_file']; ?>" target="_self"><img src="../../assets/images/index.png" width="30px" height="30px"></a></td>  -->
                                                    
                                                  <td class="hidden-480"><?php 
														if($row_getallsearches['status']=='00')
														{
														?>
                                                        
                                                        <span class="label label-sm label-warning">New Request</span>	
                                                        <?php
														}
														if($row_getallsearches['status']=='55')
														{
														?>
                                                        
                                                        <span class="label label-sm label-warning">Awaiting Quotation</span>	
                                                        <?php
														}
													    if($row_getallsearches['status']=='66')
														{
														?>
                                         <span class="label label-sm label-warning">Awaiting Payment</span>	
                                      
                                                         <?php
														}	
													if($row_getallsearches['status']=='11')
														{
														?>
                                                    <span class="label label-sm label-warning">Final</span>
                                                         <?php
														}	
													if($row_getallsearches['status']=='33')
														{
														?>
                                <span class="label label-sm label-warning">Interim</span>
                                                         <?php
														}	
															if($row_getallsearches['status']=='44')
														{
														?>
                                           <span class="label label-sm label-warning">In Progress</span>
                                                         <?php
														}	
														
														?>   </td>
                                                        
                                                        <td class="hidden-480"><?php 
															if($row_getallsearches['status']=='00')
														{
														?>
                                                        
                                                                 <?php echo $row_getallsearches['package_cost']; ?>
                                                        <?php
														}
														if($row_getallsearches['status']=='55')
														{
														?>
                                                        
                                                                 <?php echo $row_getallsearches['package_cost']; ?>
                                                        <?php
														}
													    if($row_getallsearches['status']=='66')
														{
														?>
                                            <?php echo $row_getallsearches['package_cost']; ?>
                                                         <?php
														}	
													if($row_getallsearches['status']=='11')
														{
														?>
                                                         <?php echo $row_getallsearches['package_cost']; ?>
                                                         <?php
														}	
													if($row_getallsearches['status']=='33')
														{
														?>
                                             <?php echo $row_getallsearches['package_cost']; ?>
                                                         <?php
														}	
															if($row_getallsearches['status']=='44')
														{
														?>
                                             <?php echo $row_getallsearches['package_cost']; ?>
                                                         <?php
														}	
														?>   </td>
                                                        
                                                        <td> <?php

if($row_getallsearches['package_cost']=='' || $row_getallsearches['package_cost']==' ')
{
	?>

<?php
}
else
{
?>
 <a href="dompdf/downloadpdfquote.php?requestid=<?php echo $row_getallsearches['request_id']; ?>&request_ref_number=<?php echo $row_getallsearches['request_ref_number']; ?>" target="_new"><img src="../../assets/images/index.png" width="30px" height="30px"></a>
<?php
}
?>
</td>
                                                        
 <td>
 <?php
//if (in_array('ASSIGN_MANUAL_SEARCH_REQUESTS', $roledata)) 
//{
                            
 ?>
 <?php
// }
if($row_getallsearches['package_cost']=='' || $row_getallsearches['package_cost']==' ')
{
//	?>
  <a href="#modal-viewrequest-<?php echo $row_getallsearches['request_id']; ?>"  role="button" class="green" data-toggle="modal">	    <button class="btn btn-xs btn-info">  
																		<i class="ace-icon fa fa-search-plus bigger-130"></i>																	</button></a>
 <?php
}
?>
                                   
                                <div id="modal-viewrequest-<?php echo $row_getallsearches['request_id']; ?>" class="modal fade" tabindex="-1">
            
                                <div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
													VIEW REQUEST DATA											</div>
											</div>
                                            
                                            <div class="modal-body padding">	
                                            <form method="POST" action="<?php echo $editFormAction; ?>" id="quoteform" name="quoteform">

   <input type="hidden" id="ID" name="ID"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['request_id']; ?>"/>
   
     <input type="hidden" id="quotation_by" name="quotation_by"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_full_names']; ?>"/>
       <input type="hidden" id="user_id" name="user_id"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_USR_ID']; ?>"/>
       <input type="hidden" id="status" name="status"  class="col-xs-10 col-sm-5" value="66"/>
        <input type="hidden" id="status_date" name="status_date"  class="col-xs-10 col-sm-5" value="<?php echo date('Y-m-d h:i:s'); ?>"/>
        
          <input type="hidden" id="package_cost_currency" name="package_cost_currency"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['package_cost_currency']; ?>"/>
           <input type="hidden" id="request_ref_number" name="request_ref_number"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['request_ref_number']; ?>"/>           
            <input type="hidden" id="request_dataset_cat" name="request_dataset_cat"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['request_dataset_cat']; ?>"/>
            
             <input type="hidden" id="client_login_id" name="client_login_id"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['client_login_id']; ?>"/>
        
  

 
     <table align="center" width="100%" border="0" cellpadding="30" cellspacing="30">
  
  <tr>
    <td>REQUESTED BY:</td>
    <td><?php echo $row_getallsearches['client_name']; ?></td>
  </tr>
  <tr>
    <td><strong>Company Name:</strong> 
  </td>
    <td><?php echo $row_getallsearches['company_name']; ?></td>
  </tr>
 
 <tr>
    <td><strong>Dataset Name:</strong> 
  </td>
    <td><?php echo $row_getallsearches['dataset_name']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><strong>PACKAGE:</strong></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getallsearches['request_dataset_cat']; ?></td>
  </tr>
  
   <tr>
    <td bgcolor="#FFFFFF"><strong>CURRENCY:</strong></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getallsearches['package_cost_currency']; ?></td>
  </tr>
 
  
  <tr>
    <td bgcolor="#FFFFFF"><br/><strong>MODULES</strong></td>
    <td bgcolor="#FFFFFF"></td>
  </tr>
  
  
  <tr>
    <td bgcolor="#FFFFFF"><strong>FILES UPLOADED</strong></td>
    <td bgcolor="#FFFFFF"></td>
  </tr>
   <?php
  	$filetracker = mysqli_escape_string($connect,$row_getallsearches['file_tracker']);
												
mysqli_select_db($connect,$database_connect);
$query_getfiles = "SELECT pel_psmt_request.request_id, pel_psmt_files.psmtfile_filetoken,pel_psmt_files.psmtfile_id,pel_psmt_files.psmtfile_name,pel_psmt_files.psmtfile_type,pel_psmt_files.`status`,
pel_psmt_files.request_id,pel_psmt_files.client_id FROM pel_psmt_request
Inner Join pel_psmt_files ON pel_psmt_request.file_tracker = pel_psmt_files.psmtfile_filetoken WHERE pel_psmt_request.file_tracker = '$filetracker'";
$getfiles = mysqli_query_ported($query_getfiles, $connect) or die(mysqli_error($connect));
$row_getfiles = mysqli_fetch_assoc($getfiles);
$totalRows_getfiles = mysqli_num_rows($getfiles);	  
  
  do
 {
  ?>
  <tr>
    <td bgcolor="#FFFFFF"><strong><?php echo $row_getfiles['psmtfile_type']; ?></strong></td>
    <td bgcolor="#FFFFFF"><a href="<?php echo $row_getfiles['psmtfile_name']; ?>" target="_blank"><?php echo $row_getfiles['psmtfile_name']; ?> </a></td>
  </tr>
  
   <?php
 } while ($row_getfiles = mysqli_fetch_assoc($getfiles)); ?>
  
  
  </table>
  <hr/>
  
 	<table id="simple-table" class="table table-striped table-bordered table-hover">
     <tr>
    <td bgcolor="#FFFFFF"><strong>Module Name</strong></td>
    <td bgcolor="#FFFFFF"><strong>Currency</strong></td>
   <td bgcolor="#FFFFFF"><strong>Module Cost</strong></td>   
    <td bgcolor="#FFFFFF"><strong>Cost Review</strong></td>
    <td bgcolor="#FFFFFF"><strong>Quote Cost</strong></td>
  </tr>
  
   <?php
$refnumber = $row_getallsearches['request_ref_number'];
$packagename =$row_getallsearches['request_dataset_cat'];
												
  mysqli_select_db($connect,$database_connect);
$query_getprogress2= sprintf("SELECT pel_packages_module.modulepackage_id, pel_packages_module.module_name,
pel_packages_module.`status`, pel_packages_module.added_by, pel_packages_module.added_date, pel_packages_module.verified_by,
pel_packages_module.verified_date, pel_packages_module.package_id, pel_packages_module.package_name, pel_packages_module.module_id,
pel_packages_module.module_cost, pel_packages_module.cost_currency, pel_packages_module.cost_review, pel_psmt_request_modules.module_id, pel_psmt_request_modules.module_name, pel_psmt_request_modules.request_ref_number
FROM pel_packages_module Inner Join pel_psmt_request_modules ON pel_psmt_request_modules.module_id = pel_packages_module.module_id
WHERE pel_psmt_request_modules.request_ref_number= %s AND pel_packages_module.package_name = %s ORDER BY pel_packages_module.module_name ASC", GetSQLValueString($refnumber, "text"), GetSQLValueString($packagename, "text"));
$getprogress2 = mysqli_query_ported($query_getprogress2, $connect) or die(mysqli_error($connect));
$row_getprogress2 = mysqli_fetch_assoc($getprogress2);
$totalRows_getprogress2 = mysqli_num_rows($getprogress2);  
  $total=0;
  do
 {
  ?>
  <tr>
    <td bgcolor="#FFFFFF"><?php echo $row_getprogress2['module_name']; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getprogress2['cost_currency']; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getprogress2['module_cost']; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row_getprogress2['cost_review']; ?></td>
    <td bgcolor="#FFFFFF">  <?php
	if($row_getprogress2['cost_review'] == 'FLAT')
	{	
	?>  
    <input type="text" id="price_<?php echo $row_getprogress2['modulepackage_id']; ?>" name="price_<?php echo $row_getprogress2['modulepackage_id']; ?>"  class="col-xs-10 col-sm-10" value="<?php echo $row_getprogress2['module_cost']; ?>" readonly/>
    <?php
	}
	if($row_getprogress2['cost_review'] == 'VARIED')
	{
		?><input type="text" id="price_<?php echo $row_getprogress2['modulepackage_id']; ?>" name="price_<?php echo $row_getprogress2['modulepackage_id']; ?>"  class="col-xs-10 col-sm-10" value="<?php echo $row_getprogress2['module_cost']; ?>"/>
        <?php
	}
	?></td>
  </tr>
  
   <?php
   $total= $total + $row_getprogress2['module_cost'];
 } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));

 
  ?>
 
</table>
                                            
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
							
							                                <input type="hidden" name="MM_update" value="quoteform">
                                            </form>                                  
                                            </div>
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>

											
											</div></div></div>
                                  
								</div><!-- PAGE CONTENT ENDS -->              
                                                    </td>
                                                    
                                                  </tr>
                                                  <div id="modal-notify-<?php echo $row_getallsearches['request_id']; ?>" class="modal fade" tabindex="-1">
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
												  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												  <span class="white">&times;</span>													</button>
												NOTIFY CUSTOMER											</div>
											</div>
                                            
                                            
                                            
                                            <div class="modal-body no-padding">	  <form class="form-horizontal" ENCTYPE='multipart/form-data' action='<?php echo $_SERVER["PHP_SELF"];?>' method='post'>
									<input type="hidden" id="ID" name="ID"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['request_id']; ?>"/>
                                    
                                    <input type="hidden" id="client_id" name="client_id"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['client_id']; ?>"/>
   
     <input type="hidden" id="notify_by" name="notify_by"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_full_names']; ?>"/>
       <input type="hidden" id="notify_date" name="notify_date"  class="col-xs-10 col-sm-5" value="<?php echo date('Y-m-d h:i:s'); ?>"/>
              
       <input type="hidden" id="status" name="status"  class="col-xs-10 col-sm-5" value="11"/>

											   <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Data Set Name</label>
                                                                <input type="hidden" id="bg_dataset_name" name="bg_dataset_name"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['bg_dataset_name']; ?>"/>

																<div class="col-sm-8">
																 <?php echo $row_getallsearches['bg_dataset_name']; ?>
															   </div>
															  
															 
							   <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Package</label>   <input type="hidden" id="request_plan" name="request_plan"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['request_plan']; ?>"/>

																<div class="col-sm-8">
																<?php echo $row_getallsearches['request_plan']; ?>
															   </div>
															  
															 
							      <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Request Ref Number</label> <input type="hidden" id="request_ref_number" name="request_ref_number"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallsearches['request_ref_number']; ?>"/>

																<div class="col-sm-8">
																<?php echo $row_getallsearches['request_ref_number']; ?>
															   </div>
															  
															 
							     <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Upload File<span class="style1"></span></label>

																<div class="col-sm-8"><span id="sprytextfield1">
				<input class="col-xs-10 col-sm-5" id="id-input-file-2" name="id-input-file-2" type="file"/>
															    <span class="textfieldRequiredMsg">*</span></span></div>
															  
											   
															 				 
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
				null,null,null, null,null,null,null,null,null,null,null,
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
				
				$('#id-input-file-1 , #id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false, //| true | large
					whitelist:'pdf|csv|xls|xlsx',
					blacklist:'exe|php|mp4'
					//onchange:''
					//
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
