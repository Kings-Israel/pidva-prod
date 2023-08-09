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

//approve lpo

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "approvelpo")) {
	$client_login_id = $_POST['client_login_id'];
	$client_id = $_POST['client_id'];
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
$payment_invoice_ref =  "PEL-".$client_login_id."-INV-".GeraHash3(4)."-".$datenow;
$payment_plan = $_POST['payment_plan'];
$module_id = $_POST['module_id'];
$plan_volume = $_POST['plan_volume'];
$payment_ref = $_POST['payment_ref'];
	
	if($payment_plan == 'OPEN PURCHASE' && $module_id == 'PSMT')
	{
		mysqli_select_db($connect,$database_connect);
$query_getcredits = sprintf("SELECT pel_client.client_credits FROM pel_client WHERE client_id='$client_id'");
$getcredits = mysqli_query_ported($query_getcredits, $connect) or die(mysqli_error($connect));
$row_getcredits = mysqli_fetch_assoc($getcredits);
		 $newcredits = $row_getcredits['client_credits']+$plan_volume; 

	$sql_insert2="UPDATE pel_client SET client_credits='$newcredits' WHERE client_id='$client_id'";
			//	$result_insert = mysqli_query_ported($sql_insert, $conn) or die(mysqli_error($connect));
				
    $result2 = mysqli_query_ported($sql_insert2, $connect) or die('Query failed: ' . mysqli_error($connect));	
		
	}
$updateSQL = sprintf("UPDATE pel_payments SET status=%s, verified_by=%s, verified_date=%s, invoice_number=%s WHERE payment_id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['verified_by'], "text"),
                       GetSQLValueString($_POST['verified_date'], "text"),
                       GetSQLValueString($payment_invoice_ref, "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));

 $sql_insert="UPDATE pel_psmt_request SET status='44' WHERE status='77' and client_id='$client_id' and client_login_id ='$client_login_id' and request_payment_ref='$payment_ref' ";
			//	$result_insert = mysqli_query_ported($sql_insert, $conn) or die(mysqli_error($connect));
				
    $result = mysqli_query_ported($sql_insert, $connect) or die('Query failed: ' . mysqli_error($connect));	

  $updateGoTo = "psmt.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


//reject lpo

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "rejectlpo")) {
	$client_login_id = $_POST['client_login_id'];
	$client_id = $_POST['client_id'];
	
$updateSQL = sprintf("UPDATE pel_payments SET status=%s, verified_by=%s, verified_date=%s, notes=%s WHERE payment_id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['verified_by'], "text"),
                       GetSQLValueString($_POST['verified_date'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($updateSQL, $connect) or die(mysqli_error($connect));

  $updateGoTo = "psmt.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
mysqli_select_db($connect,$database_connect);
$query_getallpayments = "SELECT * FROM pel_payments WHERE module_id='PSMT' ORDER BY payment_date DESC";
$getallpayments = mysqli_query_ported($query_getallpayments, $connect) or die(mysqli_error($connect));
$row_getallpayments = mysqli_fetch_assoc($getallpayments);
$totalRows_getallpayments = mysqli_num_rows($getallpayments);
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>PSMT Payments - Peleza Admin</title>

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
								<a href="#">Payments</a>							</li>
                             
							<li class="active">PSMT Payments</li>
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
                                    
									  <h3 align="left" class="header smaller lighter blue">PSMT PAYMENTS</h3>
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
 <?php

if (in_array('EXPORT_PAYMENTS', $roledata)) 
{

?>
											<div class="pull-right tableTools-container"></div>
                                            <?php
											}
											?>
									  </div>
										<div class="table-header">
							      Results for "PSMT PAYMENTS"										</div>

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
													  <th>PSMT PayRef</th>
                                                      
                                                  <th>Client Name</th>
                                                        <th>Payment Account</th>
                                                  <th>Amount</th>
                                                        <th>Search Credit</th>
                                                        
                                                        <th>Payment Source</th>
                                                        <th>Source Ref</th>
                                                        <th>Date Paid</th>
												
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
                                                        <a href="#"><?php echo $row_getallpayments['payment_ref']; ?></a>														</td>
                                                     
                                                     <td><?php echo $row_getallpayments['client_name']; ?></td>
                                                <td><?php echo $row_getallpayments['payment_account']; ?></td>    
                                                      <td><?php echo $row_getallpayments['currency']; ?> <?php echo $row_getallpayments['amount']; ?></td>
                                                      <td><?php echo $row_getallpayments['plan_volume']; ?> Search</td>
                                                <!--      <td><?php echo $row_getallpayments['module_id']; ?></td>-->
                                                      <td><?php echo $row_getallpayments['pay_source']; 
													  if($row_getallpayments['pay_source'] == 'LPO')
													  {
													  ?>   <a href="<?php echo $row_getallpayments['payment_file']; ?>" target="_new"><img src="../../assets/images/lpoicon.png" width="30px" height="30px"></a>
                               <?php
													  }
													  ?>
                                                      
                                                </td>
                                                      <td><?php echo $row_getallpayments['source_ref']; ?></td>
                                                      <td><?php echo $row_getallpayments['payment_date']; ?></td>
                                                      
                                                    
                                                      
                                                  <td class="hidden-480"><?php 
														
														if($row_getallpayments['status']=='11')
														{
														?>
                                                        
                                                        <span class="label label-sm label-success">SUCCESS</span>	
                                                        <?php
														}
														if($row_getallpayments['status']=='00')
														{
														?>
                                                        <span class="label label-sm label-danger">REJECTED</span>	
                                                         <?php
														}	
														if($row_getallpayments['status']=='22')
														{
														?>
                                                        <span class="label label-sm label-warning">Unverified</span>	
                                                         <?php
														}	
														?>  </td>
                                                        
                                                                                
<td>
<?php
if ($row_getallpayments['status']=='22' && $row_getallpayments['pay_source'] == 'LPO') 
{
                            
 ?><form method="POST" action="<?php echo $editFormAction; ?>" id="approvelpo" name="approvelpo">

   <input type="hidden" id="ID" name="ID"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['payment_id']; ?>"/>
   
     <input type="hidden" id="verified_by" name="verified_by"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_full_names']; ?>"/>
       <input type="hidden" id="user_id" name="user_id"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_USR_ID']; ?>"/>
            <input type="hidden" id="client_id" name="client_id"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['client_id']; ?>"/>
       <input type="hidden" id="status" name="status"  class="col-xs-10 col-sm-5" value="11"/>
        <input type="hidden" id="verified_date" name="verified_date"  class="col-xs-10 col-sm-5" value="<?php echo date('Y-m-d h:i:s'); ?>"/>
       
       <input type="hidden" id="payment_ref" name="payment_ref"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['payment_ref']; ?>"/>
       
        <input type="hidden" id="payment_plan" name="payment_plan"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['payment_plan']; ?>"/>
        
         <input type="hidden" id="plan_volume" name="plan_volume"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['plan_volume']; ?>"/>
        
          <input type="hidden" id="client_login_id" name="client_login_id"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['client_login_id']; ?>"/>
          
         <input type="hidden" id="module_id" name="module_id"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['module_id']; ?>"/>
<button  type="submit" id="submit" class="btn btn-xs btn-success" ><i class="ace-icon fa fa-check bigger-120"></i>
						  </button> 
<input type="hidden" name="MM_update" value="approvelpo">
 </form>
 
<a href="#modal-reject-<?php echo $row_getallpayments['payment_id']; ?>"  role="button" class="green" data-toggle="modal">	   <button class="btn btn-xs btn-danger">
															<i class="ace-icon fa fa-trash-o bigger-120"></i>															</button>
																	</a>
 
 
<div id="modal-reject-<?php echo $row_getallpayments['payment_id']; ?>" class="modal fade" tabindex="-1">
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
												  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												  <span class="white">&times;</span>													</button>
					      REJECT LPO									</div>
											</div>
                                            
                                            
                                            
                                            <div class="modal-body no-padding">	 <form method="POST" action="<?php echo $editFormAction; ?>" id="rejectlpo" name="rejectlpo">

   <input type="hidden" id="ID" name="ID"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['payment_id']; ?>"/>
   
     <input type="hidden" id="verified_by" name="verified_by"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_full_names']; ?>"/>
       <input type="hidden" id="user_id" name="user_id"  class="col-xs-10 col-sm-5" value="<?php echo $_SESSION['MM_USR_ID']; ?>"/>
       <input type="hidden" id="status" name="status"  class="col-xs-10 col-sm-5" value="00"/>
        <input type="hidden" id="verified_date" name="verified_date"  class="col-xs-10 col-sm-5" value="<?php echo date('Y-m-d h:i:s'); ?>"/>
       
       <input type="hidden" id="payment_ref" name="payment_ref"  class="col-xs-10 col-sm-5" value="<?php echo $row_getallpayments['payment_ref']; ?>"/>

															 
							     <br/>
															<div class="space-10"></div>
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">ENTER REJECT REASON<span class="style1"></span></label>

																<div class="col-sm-8"><span id="sprytextarea1">
																 																  <textarea name="notes" id="notes" cols="45" rows="5"></textarea>
															    <span class="textareaRequiredMsg">*</span></span></div>
															  
											   
															 				 
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
							
							                                <input type="hidden" name="MM_update" value="rejectlpo">
                                            </form></div>
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>

											
											</div></div></div>
                                  
							</div>
 
 
<?php
 }
 ?>
       <?php 
														
														if($row_getallpayments['status']=='11')
														{
														?>
                                                        
                                                      <img src="../../assets/images/index.png" width="30px" height="30px">	
                                                        <?php
														}
														?>                                            </td>
                                                  </tr>
                                                    <?php } while ($row_getallpayments = mysqli_fetch_assoc($getallpayments)); ?>

													
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
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        </script>
</body>
</html>
<?php
mysqli_free_result($getallpayments);


?>
