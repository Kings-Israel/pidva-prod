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


if ((isset($_GET['currency_id'])) && ($_GET['currency_id'] != "")) {
  $deleteSQL = sprintf("UPDATE pel_currency SET status=%s WHERE currency_id=%s",
                       GetSQLValueString($_GET['status'], "int"),
					   GetSQLValueString($_GET['currency_id'], "int"));

  mysqli_select_db($connect,$database_connect);
  $Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));

  $deleteGoTo = "currency.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $deleteGoTo));
}

$errorcode='';


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newcurrency")) {
  $insertSQL = sprintf("INSERT INTO pel_currency (currency_name, currency_code) VALUES (%s, %s)",
                       GetSQLValueString(strtoupper($_POST['currency_name']), "text"),
                       GetSQLValueString(strtoupper($_POST['currency_code']), "text"));

 mysqli_select_db($connect,$database_connect); 
  mysqli_query_ported($insertSQL, $connect);
  
//echo $Result1 = mysqli_query_ported($insertSQL, $connect)or die(mysqli_error($connect));
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

											Currency Name or Currency Code Already Existing.
											<br />
										</div>';

}
else
{
$insertGoTo = "currency.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  
  }
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editcurrency")) {
  $updateSQL = sprintf("UPDATE pel_currency SET currency_name=%s, currency_code=%s WHERE currency_id=%s",
                       GetSQLValueString(strtoupper($_POST['currency_name']), "text"),
                       GetSQLValueString(strtoupper($_POST['currency_code']), "text"),
                       GetSQLValueString($_POST['currency_id'], "int"));

 mysqli_select_db($connect,$database_connect); 
  mysqli_query_ported($updateSQL, $connect);

  if (mysqli_error($connect))
  {
$errorcode= '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
											ERROR!!!!! 
											</strong>

								Currency Name or Currency Code Already Existing.
											<br />
										</div>';

}
else
{

 $updateGoTo = "currency.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  }
}

mysqli_select_db($connect,$database_connect);
$query_getallcurrency = "SELECT * FROM pel_currency ORDER BY currency_name ASC";
$getallcurrency = mysqli_query_ported($query_getallcurrency, $connect) or die(mysqli_error($connect));
$row_getallcurrency = mysqli_fetch_assoc($getallcurrency);
$totalRows_getallcurrency = mysqli_num_rows($getallcurrency);
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Currency - Peleza Admin</title>

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
								<a href="#">Configurations</a>							</li>
							<li class="active">Currency</li>
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
                                    
									  <h3 align="left" class="header smaller lighter blue">CURRENCY CONFIGURATION</h3>
                                      </div>
                                            <?php
if (in_array('ADD_CONFIGURATION', $roledata)) 
{
?> 
                                           
                                           
                                            <div  class="col-xs-6">
                                        <h3 align="right" class="header smaller lighter blue">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
									<a href="#modal-newcurrency" role="button" class="green" data-toggle="modal">	
                                  <button class="btn btn-white btn-info btn-bold">
												<i class="ace-icon bigger-120 green"></i>
										  Add New Currency										</button></a>
                                                
                                                  
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
									  Results for "Currency configured"										</div>

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
													  <th>Currency Name</th>
														<th>Currency Code</th>
												
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
                                                        <a href="#"><?php echo $row_getallcurrency['currency_name']; ?></a>														</td>
                                                      <td><?php echo $row_getallcurrency['currency_code']; ?></td>
                                                      <td class="hidden-480"><?php 
														
														if($row_getallcurrency['status']=='11')
														{
														?>
                                                        
                                                        <span class="label label-sm label-success">Active</span>	
                                                        <?php
														}
														if($row_getallcurrency['status']=='00')
														{
														?>
                                                        <span class="label label-sm label-danger">Deactivated</span>	
                                                         <?php
														}	
														if($row_getallcurrency['status']=='22')
														{
														?>
                                                        <span class="label label-sm label-warning">Unverified</span>	
                                                         <?php
														}	
														?>  </td>
                                                      <td>
                                                          <div class="hidden-sm hidden-xs action-buttons">
                                                           <!--   <a class="blue" href="#">
                                                                  <i class="ace-icon fa fa-search-plus bigger-130"></i>																</a>
                                                                  -->	
                                                                   <?php
if (in_array('EDIT_CONFIGURATION', $roledata)) 
{
?> 
                                                            <a href="#modal-editcurrency-<?php echo $row_getallcurrency['currency_id']; ?>" role="button" class="green" data-toggle="modal">	    <button class="btn btn-xs btn-info">  
															<i class="ace-icon fa fa-pencil bigger-120"></i>															</button></a>  <?php 
															}
															
if (in_array('DEACTIVATE_CONFIGURATION', $roledata)) 
{
														
														if($row_getallcurrency['status']=='11')
														{
														?>
                                                        <a href="currency.php?status=00&currency_id=<?php echo $row_getallcurrency['currency_id']; ?>"> <button class="btn btn-xs btn-danger">
																<i class="ace-icon fa fa-trash-o bigger-120"></i>															</button></a>  <?php
														}
														}
														if (in_array('ACTIVATE_CONFIGURATION', $roledata)) 
{
														if($row_getallcurrency['status']=='00')
														{
														?>
                                                    <a href="currency.php?status=11&currency_id=<?php echo $row_getallcurrency['currency_id']; ?>"> <button class="btn btn-xs btn-success">
																<i class="ace-icon fa fa-check bigger-120"></i>															</button></a>

                                                         <?php
														}
														}
														if (in_array('VERIFY_CONFIGURATION', $roledata)) 
{
														if($row_getallcurrency['status']=='22')
														{
														?>
                                                    <a href="currency.php?status=11&currency_id=<?php echo $row_getallcurrency['currency_id']; ?>"> <button class="btn btn-xs btn-warning">
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
                                                  </tr>   <div id="modal-editcurrency-<?php echo $row_getallcurrency['currency_id']; ?>" class="modal fade" tabindex="-1">
            
                                
                                
                                
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
													Edit Currency												</div>
											</div>
                                            
                                            <div class="modal-body no-padding">	  <form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="editcurrency" >
										<input type="hidden" id="currency_id" name="currency_id" value="<?php echo $row_getallcurrency['currency_id']; ?>"/>

											  <div class="space-10"></div>

											  <div class="form-group">
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Currency Name</label>

																<div class="col-sm-8"><span id="sprytextfield1">
																  <input type="text" id="currency_name" name="currency_name" value="<?php echo $row_getallcurrency['currency_name']; ?>"/>
															    <span class="textfieldRequiredMsg">*</span></span></div>
															  
															 
							    </div>
                                <br/>

															<div class="space-10"></div>

															<div class="form-group">
																<label class="col-sm-4 control-label no-padding-right" for="form-field-pass2">Currency Code</label>

															  <div class="col-sm-8"><span id="sprytextfield2">
															    <input type="text" id="currency_code" name="currency_code" value="<?php echo $row_getallcurrency['currency_code']; ?>" />
														      <span class="textfieldRequiredMsg">*</span></span></div>
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
							
							                                <input type="hidden" name="MM_update" value="editcurrency">
                                            </form></div>
                                            <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close												</button>

											
											</div></div></div>
                                  
								</div><!-- PAGE CONTENT ENDS -->   
                                                    <?php } while ($row_getallcurrency = mysqli_fetch_assoc($getallcurrency)); ?>

													
											  </tbody>
											</table>
										</div>
									</div>
							  </div>
                              		<div id="modal-newcurrency" class="modal fade" tabindex="-1">
									<div class="modal-dialog"><div class="modal-content"><div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>													</button>
													Add New Currency												</div>
											</div>	<div class="modal-body no-padding">
                                            
                                            
                                          	  <form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="newcurrency" >
										

															<div class="space-10"></div>

											  <div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Currency Name</label>

																<div class="col-sm-9"><span id="sprytextfield3">
																  <input type="text" id="currency_name" name="currency_name" />
															    <span class="textfieldRequiredMsg">*</span></span></div>
															  
															 
							    </div>

															<div class="space-4"></div>

															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Currency Code</label>

															  <div class="col-sm-9"><span id="sprytextfield4">
															    <input type="text" id="currency_code" name="currency_code" />
														      <span class="textfieldRequiredMsg">*</span></span></div>
								</div>
                                
                                
                                
                                
                                                            <div class="clearfix form-actions">
													<div class="col-md-offset-3 col-md-9">
                                                    	<button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
														<!--<button onClick="submit" class="btn btn-info" type="button">-->
															<i class="ace-icon fa fa-check bigger-110"></i>
															Save														</button>

											       
<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
															Reset														</button>
													</div>
                                                    </div>
							
											        <input type="hidden" name="MM_insert" value="newcurrency">
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
				null,null,null, null,
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
 	   
	    <script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {validateOn:["change"]});
//-->
</script>
</body>
</html>
<?php
mysqli_free_result($getallcurrency);

?>
