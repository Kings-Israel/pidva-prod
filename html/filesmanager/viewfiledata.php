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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "rejectform")) {
  $updateSQL = sprintf("UPDATE pel_data_files SET FILE_STATUS=%s, verified_by=%s, verified_date=%s, COMMENTS_VERIFIER=%s WHERE file_id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['rejected_verifier'], "text"),
                       GetSQLValueString($_POST['date_verifier_rejected'], "date"),
                       GetSQLValueString($_POST['comments_verifier'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));

  $updateGoTo = "viewfiledata.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "rejectformapprover")) {
  $updateSQL = sprintf("UPDATE pel_data_files SET FILE_STATUS=%s, approved_by=%s, approved_date=%s, COMMENTS_APPROVER=%s WHERE FILE_ID=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['rejected_approver'], "text"),
                       GetSQLValueString($_POST['date_approver_rejected'], "date"),
                       GetSQLValueString($_POST['comments_approver'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));

  $updateGoTo = "viewfiledata.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}



$colname_getdatafiles = "-1";
if (isset($_GET['file_id'])) {
  $colname_getdatafiles = $_GET['file_id'];
}
mysqli_select_db($connect,$database_connect);
$query_getdatafiles = sprintf("SELECT * FROM pel_data_files WHERE file_id = %s", GetSQLValueString($colname_getdatafiles, "int"));
$getdatafiles = mysqli_query_ported($query_getdatafiles, $connect) or die(mysqli_error($connect));
$row_getdatafiles = mysqli_fetch_assoc($getdatafiles);
$totalRows_getdatafiles = mysqli_num_rows($getdatafiles);

$searchparam1= $row_getdatafiles['file_token'];
$searchparam2= $row_getdatafiles['shafile'];

mysqli_select_db($connect,$database_connect);
$query_getfilecontents = "SELECT * FROM pel_edu_data WHERE shafile = '$searchparam2' and file_token= '$searchparam1' ORDER BY student_first_name ASC";
$getfilecontents = mysqli_query_ported($query_getfilecontents, $connect) or die(mysqli_error($connect));
$row_getfilecontents = mysqli_fetch_assoc($getfilecontents);
$totalRows_getfilecontents = mysqli_num_rows($getfilecontents);

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>View Data File- Peleza Admin</title>

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
								<a href="#">File Manager</a>							</li>
                                <li>
								<a href="#">Data Files</a>							</li>
                               
							<li class="active">View Data File</li>
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

                                    <div  class="col-xs-12">
                                    
									  <h3 align="left" class="header smaller lighter blue">DATA FILE CONTENTS</h3>
                                      </div>
                                           <!-- <div  class="col-xs-6">
                                        <h3 align="right" class="header smaller lighter blue">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
									<a href="filesupload.php">	
                                  <button class="btn btn-white btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>	 Upload New Data File
</button></a>
                                                
                                                  
                   							  </h3>
                           



</div>-->


						<div class="page-header">
                        		<h1>
		
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									UPLOAD REFERENCE NUMBER: <strong class="green"><?php echo $row_getdatafiles['file_token']; ?> </strong>				</small>							</h1>		
					  </div><!-- /.page-header -->

						<div class="row">
<div class="col-xs-12">

										
                                    <table id="simple-table" class="table table-striped table-bordered table-hover"><thead>
                                  <td><b class="green">FILE :</b></td>
                                    <td> <b class="black"><?php echo $row_getdatafiles['file_name']; ?></b></td>
                                    <td><b class="green">SOURCE:</b></td>
                                    <td><b class="black"><?php echo $row_getdatafiles['file_source']; ?></b></td>
                                    <td><b class="green">RECORDS:</b></td>
                                    <td><b class="black"><?php echo $row_getdatafiles['count_records']; ?></b></td>
                                   <td><b class="green">MODULE NAME:</b></td>
                                    <td><b class="black"><?php echo $row_getdatafiles['module_name']; ?></b></td>
                                      <td><b class="green">Congregation:</b></td>
                                    <td><b class="black"><?php echo $row_getdatafiles['graduation_congregration']; ?></b></td>
                                    
                                    </thead></table>
                                    
                                    	<table  id="simple-table" class="table table-striped table-bordered table-hover">
  <tbody><tr>
  <?php 
													  if($row_getdatafiles['file_status']== '00')
													  
													  {
													  ?>
                                                      <td><b class="green">UPLOADED DATE: </b></td>
													
													<td><?php echo $row_getdatafiles['added_date']; ?></td>
                                                      <?php
													}
													?>									
  <?php 
													  if($row_getdatafiles['file_status']== '11')
													  
													  {
													  ?>
                                                  <td><b class="green">UPLOADED DATE: </b></td>
													
													<td><?php echo $row_getdatafiles['added_date']; ?></td>
                                                        <td><b class="green">VERIFIED DATE: </b></td>
														<td><?php echo $row_getdatafiles['verified_date']; ?></td>	
                                                      <?php
													}
													?>
<?php 
													  if($row_getdatafiles['file_status']== '22')
													  
													  {
													  ?>
                        <td><b class="green">UPLOADED DATE: </b></td>													
													<td><?php echo $row_getdatafiles['added_date']; ?></td>
                                                        <td><b class="green">VERIFIED DATE: </b></td>
						<td><?php echo $row_getdatafiles['verified_date']; ?></td>	
                                                       <td><b class="green">APPROVED DATE: </b></td>
													   <td><?php echo $row_getdatafiles['approved_date']; ?></td>	
                                                      <?php
													}
													?><?php 
													  if($row_getdatafiles['file_status']== '33')
													  
													  {
													  ?>
                                                      <td><b class="green">UPLOADED DATE: </b></td>
													
													<td><?php echo $row_getdatafiles['added_date']; ?></td>
                                                      <td><b class="green">DATE REJECTED: </b></td>
													  <td><?php echo $row_getdatafiles['verified_date']; ?></td>
                                                  
                                                      <?php
													}
													?><?php 
													  if($row_getdatafiles['file_status']== '44')
													  
													  {
													  ?>
                        <td><b class="green">UPLOADED DATE: </b></td>													
													<td><?php echo $row_getdatafiles['added_date']; ?></td>
                                                        <td><b class="green">VERIFIED DATE: </b></td>
														<td><?php echo $row_getdatafiles['verified_date']; ?></td>		
                                                      <td><b class="green">DATE REJECTED: </b></td>
													  <td><?php echo $row_getdatafiles['approved_date']; ?></td>
                                                  
                                                      <?php
													}
													?>
 </tr> <tr>
  <?php 
													  if($row_getdatafiles['file_status']== '00')
													  
													  {
													  ?>
                                                      <td><b class="green">UPLOADED BY: </b></td>
													
													<td><?php echo $row_getdatafiles['added_by']; ?></td>
                                                      <?php
													}
													?>									
  <?php 
													  if($row_getdatafiles['file_status']== '11')
													  
													  {
													  ?>
                                                        <td> <b class="green">UPLOADED BY: </b></td>
														<td><?php echo $row_getdatafiles['added_by']; ?></td>
                                                        <td><b class="green">VERIFIED BY: </b></td>
														<td><?php echo $row_getdatafiles['verified_by']; ?></td>	
                                                      <?php
													}
													?>
<?php 
													  if($row_getdatafiles['file_status']== '22')
													  
													  {
													  ?>
                        <td> <b class="green">UPLOADED BY: </b></td>
														<td><?php echo $row_getdatafiles['added_by']; ?></td>
                                                        <td><b class="green">VERIFIED BY: </b></td>
														<td><?php echo $row_getdatafiles['verified_by']; ?></td>	
                                                       <td><b class="green">APPROVED BY: </b></td>
													   <td><?php echo $row_getdatafiles['approved_by']; ?></td>	
                                                      <?php
													}
													?><?php 
													  if($row_getdatafiles['file_status']== '33')
													  
													  {
													  ?>
                                                      <td><b class="green">UPLOADED BY: </b></td>
													  <td><?php echo $row_getdatafiles['added_by']; ?></td>
                                                      <td><b class="green">REJECTED BY: </b></td>
													  <td><?php echo $row_getdatafiles['verified_by']; ?></td>
                                                  
                                                      <?php
													}
													?><?php 
													  if($row_getdatafiles['file_status']== '44')
													  
													  {
													  ?>
                        <td> <b class="green">UPLOADED BY: </b></td>
														<td><?php echo $row_getdatafiles['added_by']; ?></td>
                                                        <td><b class="green">VERIFIED BY: </b></td>
														<td><?php echo $row_getdatafiles['verified_by']; ?></td>	
                                                      <td><b class="green">REJECTED BY: </b></td>
													  <td><?php echo $row_getdatafiles['approved_by']; ?></td>
                                                  
                                                      <?php
													}
													?>
 </tr></tbody>
</table>

	
  <?php 
													  if($row_getdatafiles['file_status']== '33')
													  
													  {
													  ?><table  id="simple-table" class="table table-striped table-bordered table-hover">
  <tbody><tr>
                                                      <td><b class="green">REJECTED REASON: </b></td>
													
													<td>  <?php echo $row_getdatafiles['comments_verifier']; ?></td>  </tr>
              </tbody>
                                                    </table>
                                                      <?php
													}
													?>	
                                                	
                                                     <?php 
													  if($row_getdatafiles['file_status']== '44')
													  
													  {
													  ?><table  id="simple-table" class="table table-striped table-bordered table-hover">
  <tbody><tr>
                                                      <td><b class="green">REJECTED REASON: </b></td>
													
													<td><?php echo $row_getdatafiles['comments_approver']; ?></td>  </tr>
                                                    </tbody>
                                                    </table>
                                                      <?php
													}
													?>	
                                                    	<table  id="simple-table" class="table table-striped table-bordered table-hover">
<tr>
  <td><b class="green">INSTITUTION</b></td><td><b class="green">FACULTY</b></td><td><b class="green">COURSE</b></td></tr>
  <tr>
  <td><?php echo $row_getdatafiles['institution_name']; ?></td><td><?php //echo $row_getfilecontents['faculty_name']; ?></td><td><?php //echo $row_getfilecontents['course_name']; ?></td></tr></table>

                                    <!--	<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>	-->			
										<div class="table-header">RECORDS UPLOADED:
										
 <div class="pull-right tableTools-container"></div></div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
                                 
													<tr>
														<th>No:</th>
                                                        <th>Token:</th>
														<th>Name:</th>
                                                        <th>Faculty</th>
                                                        <th>Course</th>
                                                        <th>Specialization</th>
                                                          <th>Level:</th>                                                       
														<th>Award:</th>
                                                        <th>Graduation Date:</th>          
                                                         <th>Status:</th> 
                                                  </tr>
												</thead>

												<tbody>
                                                  <?php 
												  
												    $n=1;
												//	 $TOTAL=0;
												  do { 
												  
												  if($row_getdatafiles['file_status']=='22' )
												  {
												  ?>
                                                  
                                                     <tr>
                                                          <td class="center">
                                                          
														   <label class="pos-rel">
                                                              <?php
															  
															  echo $n++;
															  
																													  ?>
                                                              <span class="lbl"></span>														</label>									</td> <td>
                                                          <a href="#"><?php echo $row_getfilecontents['student_token']; ?></a>													</td>
                                                      <td>
                                                          <a href="#"><?php echo crypt($row_getfilecontents['student_first_name'],'st'); ?></a>													</td>
                                                          <td><?php echo crypt($row_getfilecontents['faculty_name'],'st'); ?></td>
                                                          <td><?php echo crypt($row_getfilecontents['course_name'],'st'); ?></td>
                                                          <td><?php echo crypt($row_getfilecontents['student_specialization'],'st'); ?></td>
                                                          <td><?php echo crypt($row_getfilecontents['course_level'],'st'); ?></td> 
                                                       <td><?php echo crypt($row_getfilecontents['award'],'st'); ?></td>
                                                   <td><?php echo $row_getfilecontents['graduation_date']; ?></td>                                                          
                                                   <td>
                                                        
                                                        <?php 
													  if($row_getfilecontents['student_status']== '11')
													  
													  {
													  ?>
                                                        <span class="label label-sm label-success">VALID</span>		
                                                        <?php
													}
													  
													    if($row_getfilecontents['student_status']== '00')
													  
													  {
													  
													   ?>
                                                        
                                                        <span class="label label-sm label-warning">Pending</span>		
                                                        <?php
													}
													    if($row_getfilecontents['student_status']== '22')
													  
													  {
													  
													   ?>
                                                        
                                                        <span class="label label-sm label-danger">INVALID</span>		
                                                        <?php
													
											
													}
													?>                                                      </td>
                                                     
                                                  </tr>
                                                  
                                                  				  
												  
												<?php
												  }
												  else
												  {
												  
												  
												  ?>
                                                  <tr>
                                                          <td class="center">
                                                          
														   <label class="pos-rel">
                                                              <?php
															  
															  echo $n++;
															  ?>
                                                              <span class="lbl"></span>														</label>									</td><td>
                                                          <a href="#"><?php echo $row_getfilecontents['student_token']; ?></a>													</td>
                                                      <td>
                                                          <a href="#"><?php echo $row_getfilecontents['student_first_name']; ?></a>														</td>
                                                  <td><?php echo $row_getfilecontents['faculty_name']; ?></td>
                                                          <td><?php echo $row_getfilecontents['course_name']; ?></td>
                                                          <td><?php echo $row_getfilecontents['student_specialization']; ?></td>
                                                          <td><?php echo $row_getfilecontents['course_level']; ?></td> 
                                                       <td><?php echo $row_getfilecontents['award']; ?></td>
                                                   <td><?php echo $row_getfilecontents['graduation_date']; ?></td>                                                          
                                                   <td>
                                                        
                                                        <?php 
													  if($row_getfilecontents['student_status']== '11')
													  
													  {
													  ?>
                                                        <span class="label label-sm label-success">VALID</span>		
                                                        <?php
													}
													  
													    if($row_getfilecontents['student_status']== '00')
													  
													  {
													  
													   ?>
                                                        
                                                        <span class="label label-sm label-warning">Pending</span>		
                                                        <?php
													}
													    if($row_getfilecontents['student_status']== '22')
													  
													  {
													  
													   ?>
                                                        
                                                        <span class="label label-sm label-danger">INVALID</span>		
                                                        <?php
													
											
													}
													?>                                                      </td>
                                                     
                                                  </tr>
                                                  <?php
                                                  
												  }
												   } while ($row_getfilecontents = mysqli_fetch_assoc($getfilecontents)); 
													
												//	echo $TOTAL;
													
													?>

												
											  </tbody>
										  </table>
									  </div>
                          
          <BR/>
                                          
            <?php
			
			if (in_array('FILE_VERIFIER', $roledata)) 
{

											
											if($row_getdatafiles['file_status']=='00')
											{
											?>
  <table align="center" width="70%">
   <tr>  
  
   
  <td> <form method="POST" action="<?php echo $editFormAction; ?>" id="rejectform" name="rejectform">

   <input type="hidden" id="ID" name="ID"  class="col-xs-10 col-sm-5" value="<?php echo $row_getdatafiles['file_id']; ?>"/>
   
     <input type="hidden" id="rejected_verifier" name="rejected_verifier"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_full_names']; ?>"/>
       <input type="hidden" id="date_verifier_rejected" name="date_verifier_rejected"  class="col-xs-10 col-sm-5" value="<?php echo date('Y-m-d h:m:s'); ?>"/>
       <input type="hidden" id="status" name="status"  class="col-xs-10 col-sm-5" value="33"/><span id="sprytextarea1">
    <textarea name="comments_verifier"  class="form-control" id="comments_verifier" placeholder="If you are rejecting Enter Reject Reason"></textarea>
    <span class="textareaRequiredMsg">You Must Input Reject Reason.</span></span></td>
      <td></td>
  </tr>
  <tr>
  <td><br/>	<!--<a href="reject.php?idreject=<?php echo $row_getfile['ID']; ?>"></a>-->	<button  type="submit" id="submit" class="btn btn-sm btn-danger pull-left">
													<i class="ace-icon fa fa-times"></i>
											Reject									</button>
                  </td> <input type="hidden" name="MM_update" value="rejectform">
  </form>
      <td><br/>  <a href="approve.php?idapprove=<?php echo $row_getdatafiles['file_id']; ?>">    <button class="btn btn-sm btn-success pull-left">
													<i class="ace-icon fa fa-tick"></i>
													Approve								</button></a></td>
  </tr>
 
</table>

       
                                                                             	
<?php

}
}
?>
                        <?php
									if (in_array('FILE_APPROVER', $roledata)) 
{

							
											if($row_getdatafiles['file_status']=='11')
											{
											?>
                                            
                                            
                                            
           
   <table align="center" width="70%">
   <tr>  <form method="POST" action="<?php echo $editFormAction; ?>" id="rejectformapprover" name="rejectformapprover">
 
   <input type="hidden" id="ID" name="ID"  class="col-xs-10 col-sm-5" value="<?php echo $row_getdatafiles['file_id']; ?>"/>
   
     <input type="hidden" id="rejected_approver" name="rejected_approver"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_full_names']; ?>"/>
       <input type="hidden" id="date_approver_rejected" name="date_approver_rejected"  class="col-xs-10 col-sm-5" value="<?php echo date('Y-m-d h:m:s'); ?>"/>
       <input type="hidden" id="status" name="status"  class="col-xs-10 col-sm-5" value="44"/>
   
  <td><span id="sprytextarea1">
    <textarea name="comments_approver"  class="form-control" id="comments_approver" placeholder="If you are rejecting Enter Reject Reason"></textarea>
    <span class="textareaRequiredMsg">You Must Input Reject Reason.</span></span></td>
      <td></td>
  </tr>
  <tr>
  <td><br/>	<!--<a href="reject.php?idreject=<?php echo $row_getfile['ID']; ?>"></a>-->	<button  type="submit" id="submit" class="btn btn-sm btn-danger pull-left">
													<i class="ace-icon fa fa-times"></i>
											Reject									</button>
                  </td> <input type="hidden" name="MM_update" value="rejectformapprover">
  </form>
      <td><br/>  <a href="approveapprover.php?idapprove=<?php echo $row_getdatafiles['file_id']; ?>&shafile=<?php echo $row_getdatafiles['shafile']; ?>">    <button class="btn btn-sm btn-success pull-left">
													<i class="ace-icon fa fa-tick"></i>
													Approve								</button></a></td>
  </tr>
 
</table>

<?php
}
}
?>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
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
				null,null,null, null,  null, null,  null,null,null,
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
 	   
	  
</body>
</html>
<?php
mysqli_free_result($getdatafiles);

mysqli_free_result($getfilecontents);


?>
