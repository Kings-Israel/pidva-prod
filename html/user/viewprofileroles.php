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
$colname_getoneprofile = "-1";
if (isset($_GET['profile_id'])) {
  $colname_getoneprofile = $_GET['profile_id'];
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

mysqli_select_db($connect,$database_connect);
$query_getoneprofile = sprintf("SELECT * FROM pel_profile WHERE profile_id = %s", GetSQLValueString($colname_getoneprofile, "int"));
$getoneprofile = mysqli_query_ported($query_getoneprofile, $connect) or die(mysqli_error($connect));
$row_getoneprofile = mysqli_fetch_assoc($getoneprofile);
$totalRows_getoneprofile = mysqli_num_rows($getoneprofile);

$query_getrolesdash = "SELECT * FROM pel_profile_roles WHERE ROLE_CAT = 'DASHBOARD' AND profile_id='$colname_getoneprofile'";
$getrolesdash = mysqli_query_ported($query_getrolesdash, $connect) or die(mysqli_error($connect));
$row_getrolesdash = mysqli_fetch_assoc($getrolesdash);
$totalRows_getrolesdash = mysqli_num_rows($getrolesdash);

$query_getrolesmod = "SELECT * FROM pel_profile_roles WHERE (ROLE_CAT = 'MODULES MANAGEMENT' OR ROLE_CAT = 'MODULES EDUCATION MANAGEMENT') AND profile_id='$colname_getoneprofile'";
$getrolesmod = mysqli_query_ported($query_getrolesmod, $connect) or die(mysqli_error($connect));
$row_getrolesmod = mysqli_fetch_assoc($getrolesmod);
$totalRows_getrolesmod = mysqli_num_rows($getrolesmod);

$query_getrolesclient = "SELECT * FROM pel_profile_roles WHERE ROLE_CAT = 'CLIENT MANAGEMENT' AND profile_id='$colname_getoneprofile'";
$getrolesclient = mysqli_query_ported($query_getrolesclient, $connect) or die(mysqli_error($connect));
$row_getrolesclient = mysqli_fetch_assoc($getrolesclient);
$totalRows_getrolesclient = mysqli_num_rows($getrolesclient);

$query_getrolessearch = "SELECT * FROM pel_profile_roles WHERE ROLE_CAT = 'SEARCH MANAGEMENT' AND profile_id='$colname_getoneprofile'";
$getrolessearch = mysqli_query_ported($query_getrolessearch, $connect) or die(mysqli_error($connect));
$row_getrolessearch = mysqli_fetch_assoc($getrolessearch);
$totalRows_getrolessearch = mysqli_num_rows($getrolessearch);

$query_getrolespay = "SELECT * FROM pel_profile_roles WHERE ROLE_CAT = 'PAYMENTS MANAGEMENT' AND profile_id='$colname_getoneprofile'";
$getrolespay = mysqli_query_ported($query_getrolespay, $connect) or die(mysqli_error($connect));
$row_getrolespay = mysqli_fetch_assoc($getrolespay);
$totalRows_getrolespay = mysqli_num_rows($getrolespay);

$query_getrolesfile = "SELECT * FROM pel_profile_roles WHERE ROLE_CAT = 'FILES MANAGEMENT' AND profile_id='$colname_getoneprofile'";
$getrolesfile = mysqli_query_ported($query_getrolesfile, $connect) or die(mysqli_error($connect));
$row_getrolesfile = mysqli_fetch_assoc($getrolesfile);
$totalRows_getrolesfile = mysqli_num_rows($getrolesfile);


$query_getrolesprofile = "SELECT * FROM pel_profile_roles WHERE ROLE_CAT = 'PROFILE MANAGEMENT' AND profile_id='$colname_getoneprofile'";
$getrolesprofile = mysqli_query_ported($query_getrolesprofile, $connect) or die(mysqli_error($connect));
$row_getrolesprofile = mysqli_fetch_assoc($getrolesprofile);
$totalRows_getrolesprofile = mysqli_num_rows($getrolesprofile);
/*
$query_getrolesconfig = "SELECT * FROM pel_profile_roles WHERE ROLE_CAT = 'CONFIGURATION MANAGEMENT' AND profile_id='$colname_getoneprofile'";
$getrolesconfig = mysqli_query_ported($query_getrolesconfig, $connect) or die(mysqli_error($connect));
$row_getrolesconfig = mysqli_fetch_assoc($getrolesconfig);
$totalRows_getrolesconfig = mysqli_num_rows($getrolesconfig);

$query_getrolesuser = "SELECT * FROM pel_profile_roles WHERE ROLE_CAT = 'USER MANAGEMENT' AND profile_id='$colname_getoneprofile'";
$getrolesuser = mysqli_query_ported($query_getrolesuser, $connect) or die(mysqli_error($connect));
$row_getrolesuser = mysqli_fetch_assoc($getrolesuser);
$totalRows_getrolesuser = mysqli_num_rows($getrolesuser);*/

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>View Profile Roles - Peleza Admin</title>

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
								<a href="#">User Management</a>							</li>
                                <li>
						  <a href="#">Profile Management</a>							</li>
							<li class="active">View Profile</li>
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
                                    
									  <h3 align="left" class="header smaller lighter blue">View Profile</h3>
                </div>
                                            <div  class="col-xs-6">
                                        <h3 align="right" class="header smaller lighter blue">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
									<a href="profilemanager.php">	
                                  <button class="btn btn-white btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>Back to Profiles
</button></a></h3>
                           



</div>



<div class="row"><div class="col-xs-12">
                                            
    
                                   	<table id="simple-table" class="table table-striped table-bordered table-hover">
  	<thead><tr>
    <td><strong>Profile Manager:</strong></td> 
    <td><strong>Profile Status:</strong></td>  
    <td><strong>Added By:</strong></td>
      <td><strong>Added Date:</strong></td>
     <td><strong>Verified By:</strong></td>
    <td><strong>Verified Date:</strong></td>
    </tr>
    </thead>
    <tr>
    <td colspan="6"></td>
  </tr>
      <tr>
    <td><?php echo $row_getoneprofile['profile_name']; ?></td>

   
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php 		if($row_getoneprofile['status']=='11')
														{
														?>
                                                       ACTIVE <?php
														}
														if($row_getoneprofile['status']=='00')
														{
														?>
                                                DEACTIVATED

                                                         <?php
														}
														if($row_getoneprofile['status']=='22')
														{
														?>
                                                   UNVERIFIED

                                                         <?php
														}
												?>  </button></td>

 
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getoneprofile['added_by']; ?></button></td>
  
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getoneprofile['added_date']; ?></button></td>
 
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getoneprofile['verified_by']; ?></button></td> 
    <td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getoneprofile['verified_date']; ?></button></td>
  </tr>
</table>
<?php

	if($row_getoneprofile['status']=='00')
														{
														?>
                                                          <div  class="col-xs-12">
                                        <h3 align="right" class="header smaller lighter blue">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
									<a href="assignprofileroles.php?profile_id=<?php echo $colname_getoneprofile;?>">	
                                  <button class="btn btn-white btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>Edit New Roles
</button></a></h3>
</div>
                                                            
                                                           <?php
														}
														
														else
														{
														echo "";
														}	
?>
    

<div class="hr hr-double hr-dotted hr18"></div>
								   
				<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
						       <form  enctype="multipart/form-data" method="POST" action="<?php echo $editFormAction; ?>" class="form-inline" name="assignroles" >
                        <div class="row">		<div class="row">		
                 
									<div class="col-sm-12">
										<!-- #section:elements.tab.position -->
										<div class="tabbable tabs-left">
											<ul class="nav nav-tabs" id="myTab3">
												<li class="active">
													<a data-toggle="tab" href="#dashboard">
														<i class="purple ace-icon fa fa-tachometer bigger-110"></i>
														DASHBOARD ROLES
													</a>
												</li>
                                                <li>
													<a data-toggle="tab" href="#modules">
														<i class="red ace-icon fa fa-desktop bigger-110"></i>
														MODULES MANAGEMENT
													</a>
												</li> 
                                                 <li>
													<a data-toggle="tab" href="#clients">
														<i class="orange ace-icon fa fa-user bigger-110"></i>
													CLIENT MANAGEMENT
													</a>
												</li>
                                                <li>
													<a data-toggle="tab" href="#search">
														<i class="blue ace-icon fa fa-search bigger-110"></i>
														SEARCH MANAGEMENT
													</a>
												</li>
                                                <li>
													<a data-toggle="tab" href="#payments">
														<i class="green ace-icon fa fa-money bigger-110"></i>
														PAYMENTS MANAGEMENT
													</a>
												</li>
                                                <li>
													<a data-toggle="tab" href="#files">
														<i class="red ace-icon fa fa-list-alt"></i>
														DATA FILES MANAGEMENT
													</a>
												</li>
                                            <li>
													<a data-toggle="tab" href="#profile">
														<i class="orange ace-icon fa fa-user"></i>
														PROFILE MANAGEMENT
													</a>
												</li>
                                                 <!--     <li>
													<a data-toggle="tab" href="#config">
														<i class="black ace-icon fa fa-gear"></i>
														CONFIGURATION MANAGEMENT
													</a>
												</li>
                                                 <li>
													<a data-toggle="tab" href="#user">
														<i class="black ace-icon fa fa-gear"></i>
														USER MANAGEMENT
													</a>
												</li>-->
											</ul>

											<div class="tab-content">
											  <div id="dashboard" class="tab-pane in active">
											    <p>DASHBOARD ROLES ASSIGNED</p>
												
														<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span>
													   <span class="lbl"> <?php echo $row_getrolesdash['role_name']; ?></span>													          </label>
														  <?php 
														
														  } while ($row_getrolesdash = mysqli_fetch_assoc($getrolesdash)); ?>
                                                     
                                                    
												</div>

												<div id="modules" class="tab-pane">
													<p>MODULES</p>
                                                  	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span>
													   <span class="lbl"> <?php echo $row_getrolesmod['role_name']; ?></span>													          </label>
														  <?php 
														 
														  } while ($row_getrolesmod = mysqli_fetch_assoc($getrolesmod)); ?>
												</div>
                                                  <div id="clients" class="tab-pane">
													<p>CLIENT</p>
                                                    
                                                      	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span>
													   <span class="lbl"> <?php echo $row_getrolesclient['role_name']; ?></span>													          </label>
														  <?php 
														 
														  } while ($row_getrolesclient = mysqli_fetch_assoc($getrolesclient)); ?>
												</div>

										  <div id="search" class="tab-pane">
													<p>SEARCH</p>
                                                    
                                                     	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span>
													   <span class="lbl"> <?php echo $row_getrolessearch['role_name']; ?></span>													          </label>
														  <?php 
														 
														  } while ($row_getrolessearch = mysqli_fetch_assoc($getrolessearch)); ?>
												</div>
                                                <div id="payments" class="tab-pane">
													<p>PAYMENTS</p>
                                                   	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span>
													   <span class="lbl"> <?php echo $row_getrolespay['role_name']; ?></span>													          </label>
														  <?php 
														 
														  } while ($row_getrolespay = mysqli_fetch_assoc($getrolespay)); ?>
												</div>
                                                <div id="clients" class="tab-pane">
													<p>CLIENTS</p>
                                                    
                                                    	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span>
													   <span class="lbl"> <?php echo $row_getrolesclient['role_name']; ?></span>													          </label>
														  <?php 
														 
														  } while ($row_getrolesclient = mysqli_fetch_assoc($getrolesclient)); ?>
												</div>
                                                <div id="files" class="tab-pane">
													<p>FILES MANAGEMENT</p>
                                                    
                                                   	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span>
													   <span class="lbl"> <?php echo $row_getrolesfile['role_name']; ?></span>													          </label>
													<?php 
														} while ($row_getrolesfile = mysqli_fetch_assoc($getrolesfile)); ?>
												</div>
                                           <div id="profile" class="tab-pane">
													<p>PROFILES</p>
                                                    
                                                  	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span><span class="lbl"> <?php echo $row_getrolesprofile['role_name']; ?></span>													          </label>
														  <?php 
														 
														  } while ($row_getrolesprofile = mysqli_fetch_assoc($getrolesprofile)); ?>
												</div>
                                                 <!--     <div id="config" class="tab-pane">
													<p>CONFIGURATION MANAGEMENT</p>
                                                 	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span> <span class="lbl"> <?php echo $row_getrolesconfig['role_name']; ?></span>													          </label>
														  <?php 
														 
														  } while ($row_getrolesconfig = mysqli_fetch_assoc($getrolesconfig)); ?>
												</div>
                                                
                                                
                                                   <div id="user" class="tab-pane">
													<p>USER MANAGEMENT</p>
                                                    
                                                   	<?php 
														$n=1;
														do {										
														
														?>
                                                         <label class="col-sm-5 inline"><span class="badge badge-yellow"><?php echo $n++; ?></span> <span class="lbl"> <?php echo $row_getrolesuser['role_name']; ?></span>													          </label>
														  <?php 
														 
														  } while ($row_getrolesuser = mysqli_fetch_assoc($getrolesuser)); ?>
												</div>-->
											</div>
										</div>

										<!-- /section:elements.tab.position -->
									</div><!-- /.col -->
						
                     
                       
						</div><!-- /.row -->
                        
                        <!-- <br>
                        <div class="space-10"></div>
									

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
						
                                   <button onClick="submit" type="submit" value="submit" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit											</button>

								         
<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset											</button>
										</div>
									</div>      -->
									<input type="hidden" name="MM_insert" value="assignroles">
					          </form>
					</div><!-- /.page-content -->
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

		<script src="../../assets/js/jquery-ui.custom.js"></script>
	<script src="../../assets/js/jquery.ui.touch-punch.js"></script>
	<script src="../../assets/js/bootbox.js"></script>
	<script src="../../assets/js/jquery.easypiechart.js"></script>
	<script src="../../assets/js/jquery.gritter.js"></script>
	<script src="../../assets/js/spin.js"></script>
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
				null,null,null, null,null, null,null,
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
 	   
	
</body>
</html>
<?php
mysqli_free_result($getoneprofile);

mysqli_free_result($getrolesdash);

mysqli_free_result($getrolesmod);
mysqli_free_result($getrolessearch);
mysqli_free_result($getrolesclient);
mysqli_free_result($getrolespay);
mysqli_free_result($getrolesfile);

mysqli_free_result($getrolesprofile);
/*mysqli_free_result($getrolesconfig);
mysqli_free_result($getrolesuser);*/



?>
