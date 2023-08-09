<?php
require_once('../../Connections/connect.php');
require_once('../clients/credential_mailer.php');

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


if ((isset($_GET['USR_ID'])) && ($_GET['USR_ID'] != "")) {
	if ($_GET['status'] == '33') {
		$toemail = $_GET['USR_EMAIL'];
		$togetpwd = $_GET['USR_ID'];
		mysqli_select_db($connect, $database_connect);
		$query_getuser = "SELECT * FROM pel_users WHERE USR_ID='$togetpwd'";
		$getuser = mysqli_query_ported($query_getuser, $connect) or die(mysqli_error($connect));
		$row_getuser = mysqli_fetch_assoc($getuser);
		$totalRows_getuser = mysqli_num_rows($getuser);

		$deleteSQL = sprintf(
			"UPDATE pel_users SET USR_STATUS=%s, USR_PIN_STATUS=%s,USR_MODIFIED_BY=%s, USR_DATE_MODIFIED=%s WHERE USR_ID=%s",
			GetSQLValueString('11', "text"),
			GetSQLValueString('0', "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString(date('d-m-Y H:m:s'), "text"),
			GetSQLValueString($_GET['USR_ID'], "int")
		);
		if (!$connect->query($deleteSQL)) {
			echo "ERROR - " . $deleteSQL;
		};
	} elseif ($_GET['status'] == '22') {
		$deleteSQL = sprintf(
			"UPDATE pel_users SET usr_status=%s, USR_CREATED_BY=%s, USR_DATE_CREATED=%s WHERE USR_ID=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString(date('d-m-Y H:m:s'), "text"),
			GetSQLValueString($_GET['USR_ID'], "int")
		);

		mysqli_select_db($connect, $database_connect);
		$Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));
	} elseif ($_GET['status'] == '00') {
		$deleteSQL = sprintf(
			"UPDATE pel_users SET usr_status=%s, USR_CREATED_BY=%s, USR_DATE_CREATED=%s WHERE USR_ID=%s",
			GetSQLValueString($_GET['status'], "text"),
			GetSQLValueString($_GET['fullnames'], "text"),
			GetSQLValueString(date('d-m-Y H:m:s'), "text"),
			GetSQLValueString($_GET['USR_ID'], "int")
		);

		mysqli_select_db($connect, $database_connect);
		$Result1 = mysqli_query_ported($deleteSQL, $connect) or die(mysqli_error($connect));
	}

	$deleteGoTo = "users.php";
	header(sprintf("Location: %s", $deleteGoTo));
}


mysqli_select_db($connect, $database_connect);
$query_getusers = "SELECT * FROM pel_users WHERE USR_PROFILE_NAME NOT IN ('System Administrator', 'Super Administrator') ORDER BY USR_NAME ASC";
$getusers = mysqli_query_ported($query_getusers, $connect) or die(mysqli_error($connect));
$row_getusers = mysqli_fetch_assoc($getusers);
$totalRows_getusers = mysqli_num_rows($getusers);

mysqli_select_db($connect, $database_connect);
$query_getprofile = "SELECT * FROM pel_profile WHERE profile_name NOT IN ('System Administrator', 'Super Administrator') ORDER BY profile_name ASC";
$getprofile = mysqli_query_ported($query_getprofile, $connect) or die(mysqli_error($connect));
$row_getprofile = mysqli_fetch_assoc($getprofile);
$totalRows_getprofile = mysqli_num_rows($getprofile);

mysqli_select_db($connect, $database_connect);
$query_getprofile4 = "SELECT * FROM pel_profile WHERE profile_name NOT IN ('System Administrator', 'Super Administrator') ORDER BY profile_name ASC";
$getprofile4 = mysqli_query_ported($query_getprofile4, $connect) or die(mysqli_error($connect));
$row_getprofile4 = mysqli_fetch_assoc($getprofile4);
$totalRows_getprofile4 = mysqli_num_rows($getprofile4);
$error_code = "";
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editclient")) {

	$togetprofile = $_POST['USR_PROFILE'];
	mysqli_select_db($connect, $database_connect);
	$query_getprofile3 = "SELECT * FROM pel_profile WHERE profile_id='$togetprofile'";
	$getprofile3 = mysqli_query_ported($query_getprofile3, $connect) or die(mysqli_error($connect));
	$row_getprofile3 = mysqli_fetch_assoc($getprofile3);
	$totalRows_getprofile3 = mysqli_num_rows($getprofile3);

	mysqli_free_result($getprofile3);

	$updateSQL = sprintf(
		"UPDATE pel_users SET USR_NAME=%s, USR_PHONE_NO=%s, USR_STATUS=%s, USR_EMAIL=%s, USR_USERNAME=%s, USR_STAFF_ID=%s, USR_NATIONAL_ID=%s, USR_CREATED_BY=%s, USR_DATE_CREATED=%s, USR_PROFILE=%s, USR_PROFILE_NAME=%s  WHERE USR_ID=%s",
		GetSQLValueString(strtoupper($_POST['USR_NAME']), "text"),
		GetSQLValueString(strtoupper($_POST['USR_PHONE_NO']), "text"),
		GetSQLValueString($_POST['status'], "text"),
		GetSQLValueString($_POST['USR_EMAIL'], "text"),
		GetSQLValueString($_POST['USR_EMAIL'], "text"),
		GetSQLValueString($_POST['USR_STAFF_ID'], "text"),
		GetSQLValueString($_POST['USR_NATIONAL_ID'], "text"),
		GetSQLValueString($_POST['USR_CREATED_BY'], "text"),
		GetSQLValueString($_POST['USR_DATE_CREATED'], "text"),
		GetSQLValueString($_POST['USR_PROFILE'], "text"),
		GetSQLValueString($row_getprofile3['profile_name'], "text"),
		GetSQLValueString($_POST['USR_ID'], "int")
	);

	mysqli_select_db($connect, $database_connect);
	mysqli_query_ported($updateSQL, $connect);

	if (mysqli_error($connect)) {

		$error_code = "<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>
												<i class='ace-icon fa fa-times'></i>
											</button>

											<strong>
												<i class='ace-icon fa fa-times'></i>
												Oh snap!
											</strong>

											ERROR!!!!! Staff Id/Email/Id Number/Phone Number is already Registered.
											<br />
										</div>";
	} else {
		$updateGoTo = "users.php";
		if (isset($_SERVER['QUERY_STRING'])) {
			$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
			$updateGoTo .= $_SERVER['QUERY_STRING'];
		}
		header(sprintf("Location: %s", $updateGoTo));
	}
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newuser")) {

	$togetprofile = $_POST['USR_PROFILE'];
	mysqli_select_db($connect, $database_connect);
	$query_getprofile5 = "SELECT * FROM pel_profile WHERE profile_id='$togetprofile'";
	$getprofile5 = mysqli_query_ported($query_getprofile5, $connect) or die(mysqli_error($connect));
	$row_getprofile5 = mysqli_fetch_assoc($getprofile5);
	$totalRows_getprofile5 = mysqli_num_rows($getprofile5);

	mysqli_free_result($getprofile5);


	$insertSQL = sprintf(
		"INSERT INTO pel_users (USR_CREATED_BY, USR_DATE_CREATED, USR_EMAIL, USR_NAME, USR_PHONE_NO, USR_STAFF_ID, USR_NATIONAL_ID, USR_PROFILE, USR_PROFILE_NAME, USR_PASSWORD, USR_PIN, USR_USERNAME) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
		GetSQLValueString($_POST['USR_CREATED_BY'], "text"),
		GetSQLValueString($_POST['USR_DATE_CREATED'], "date"),
		GetSQLValueString($_POST['USR_EMAIL'], "text"),
		GetSQLValueString(strtoupper($_POST['USR_NAME']), "text"),
		GetSQLValueString($_POST['USR_PHONE_NO'], "text"),
		GetSQLValueString(strtoupper($_POST['USR_STAFF_ID']), "text"),
		GetSQLValueString($_POST['USR_NATIONAL_ID'], "text"),
		GetSQLValueString($_POST['USR_PROFILE'], "text"),
		GetSQLValueString($row_getprofile5['profile_name'], "text"),
		GetSQLValueString(md5($_POST['USR_PASSWORD']), "text"),
		GetSQLValueString($_POST['USR_PASSWORD'], "text"),
		GetSQLValueString($_POST['USR_EMAIL'], "text")
	);

	mysqli_select_db($connect, $database_connect);
	mysqli_query_ported($insertSQL, $connect);

	//echo $Result1 = mysqli_query_ported($insertSQL, $connect)or die(mysqli_error($connect));
	if (mysqli_error($connect)) {
		$error_code = "<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>
												<i class='ace-icon fa fa-times'></i>
											</button>

											<strong>
												<i class='ace-icon fa fa-times'></i>
												Oh snap!
											</strong>

											ERROR!!!!! Staff Id/Email/Id Number/Phone Number is already Registered.
											<br />
										</div>";
	} else {
		$insertGoTo = "users.php";
		// send credential mail if user is registered
		$auth_mailer->send_mail($_POST['USR_NAME'], $_POST['USR_EMAIL'], $_POST['USR_PASSWORD'], 'pidva', $_POST['USR_STAFF_ID'], true);

		if (isset($_SERVER['QUERY_STRING'])) {
			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
			$insertGoTo .= $_SERVER['QUERY_STRING'];
		}
		header(sprintf("Location: %s", $insertGoTo));
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>User - Peleza Admin</title>

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
	<link rel="stylesheet" href="../../assets/css/jquery-ui.custom.css" />
	<link rel="stylesheet" href="../../assets/css/chosen.css" />
	<link rel="stylesheet" href="../../assets/css/datepicker.css" />
	<link rel="stylesheet" href="../../assets/css/bootstrap-timepicker.css" />
	<link rel="stylesheet" href="../../assets/css/daterangepicker.css" />
	<link rel="stylesheet" href="../../assets/css/bootstrap-datetimepicker.css" />
	<link rel="stylesheet" href="../../assets/css/colorpicker.css" />
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
								<a href="#">Users</a>
							</li>

							<li class="active">System Users</li>
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

										<h3 align="left" class="header smaller lighter blue">PELEZA SYSTEM USERS</h3>
									</div>
									<?php
									if (in_array('ADD_USERS', $roledata)) {
									?>

										<div class="col-xs-6">
											<h3 align="right" class="header smaller lighter blue">
												<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
												<a href="#modal-newuser" role="button" class="green" data-toggle="modal">
													<button class="btn btn-white btn-info btn-bold">
														<i class="ace-icon bigger-120 green"></i>Add New User
													</button></a>
											</h3>

										</div>
									<?php
									}
									?><div class="col-xs-12">
										<?php
										echo $error_code;
										?>

									</div>

									<div class="clearfix">
										<div class="pull-right tableTools-container"></div>
									</div>
									<div class="table-header">
										Results for "System Users Created" </div>

									<!-- div.table-responsive -->

									<!-- div.dataTables_borderWrap -->
									<div>
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th class="center">
														<!--<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>															</label>--> NO:

													</th>
													<th>Name</th>
													<th>Staff Number</th>
													<th>Id Number</th>

													<th>Email Address</th>
													<th>Mobile Number</th>
													<th>Profile</th>
													<th>Last Login</th>


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
															<a href="#"><?php echo $row_getusers['USR_NAME']; ?></a>
														</td>
														<td><?php echo $row_getusers['USR_STAFF_ID']; ?></td>
														<td><?php echo $row_getusers['USR_NATIONAL_ID']; ?></td>
														<td><?php echo $row_getusers['USR_EMAIL']; ?></td>
														<td><?php echo $row_getusers['USR_PHONE_NO']; ?></td>
														<td><?php echo $row_getusers['USR_PROFILE_NAME']; ?></td>
														<td><?php echo $row_getusers['USR_LAST_LOGIN']; ?></td>



														<td class="hidden-480"><?php

																				if ($row_getusers['USR_STATUS'] == '11') {
																				?>

																<span class="label label-sm label-success">Active</span>
															<?php
																				}
																				if ($row_getusers['USR_STATUS'] == '00') {
															?>
																<span class="label label-sm label-danger">Deactivated</span>
															<?php
																				}
																				if ($row_getusers['USR_STATUS'] == '22') {
															?>
																<span class="label label-sm label-warning">Unverified</span>
															<?php
																				}
																				if ($row_getusers['USR_STATUS'] == '33') {
															?>
																<span class="label label-sm label-purple">Password Reset</span>
															<?php
																				}
															?>
														</td>


														<td>
															<div class="hidden-sm hidden-xs action-buttons">

																<a href="#modal-viewuser-<?php echo $row_getusers['USR_ID']; ?>" role="button" class="green" data-toggle="modal"> <button class="btn btn-xs btn-primary">
																		<i class="ace-icon fa fa-search-plus bigger-130"></i> </button></a>




																<div id="modal-viewuser-<?php echo $row_getusers['USR_ID']; ?>" class="modal fade" tabindex="-1">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-header no-padding">
																				<div class="table-header">
																					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
																						<span class="white">&times;</span> </button>
																					VIEW USER DETAILS
																				</div>
																			</div>
																			<div class="modal-body padding">
																				<table width="100%" border="0">
																					<tr>
																						<td><strong>User Names:</strong></td>
																						<td><?php echo $row_getusers['USR_NAME']; ?></td>

																						<td><strong>STAFF ID:</strong></td>
																						<td><?php echo $row_getusers['USR_STAFF_ID']; ?></td>
																					</tr>
																					<tr>
																						<td colspan="4"><br /></td>
																					</tr>
																					<tr>
																						<td><strong>Mobile Number</strong></td>
																						<td><?php echo $row_getusers['USR_PHONE_NO']; ?></td>
																						<td><strong>Email Address:</strong></td>
																						<td><?php echo $row_getusers['USR_EMAIL']; ?></td>

																					</tr>
																					<tr>
																						<td colspan="4"><br /></td>
																					</tr>
																					<tr>
																						<td><strong>Profile Assigned</strong></td>
																						<td><?php echo $row_getusers['USR_PROFILE_NAME']; ?></td>
																						<td><strong>National Id:</strong></td>
																						<td><?php echo $row_getusers['USR_NATIONAL_ID']; ?></td>




																					</tr>
																					<tr>
																						<td colspan="4"><br /></td>
																					</tr>

																					<tr>
																						<td><strong>User Status:</strong></td>
																						<td><button type="button" class="btn disabled btn-white btn-primary"><?php if ($row_getusers['USR_STATUS'] == '11') {
																																								?>
																									ACTIVE <?php
																																								}
																																								if ($row_getusers['USR_STATUS'] == '00') {
																											?>
																									DEACTIVATED

																								<?php
																																								}
																																								if ($row_getusers['USR_STATUS'] == '22') {
																								?>
																									UNVERIFIED

																								<?php
																																								}
																																								if ($row_getusers['USR_STATUS'] == '33') {
																								?>
																									PASSWORD RESET

																								<?php
																																								}
																								?> </button></td>
																						<td><strong>User Last Login</strong></td>


																						<td><?php echo $row_getusers['USR_LAST_LOGIN']; ?></td>
																					</tr>
																					<tr>
																						<td colspan="4"><br /></td>
																					</tr>

																					<tr>
																						<td><strong>Modified By:</strong></td>
																						<td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getusers['USR_CREATED_BY']; ?></button></td>
																						<td><strong>Modified Date:</strong></td>
																						<td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getusers['USR_DATE_CREATED']; ?></button></td>
																					</tr>
																					<tr>
																						<td colspan="4"><br /></td>
																					</tr>
																					<tr>
																						<td><strong>Verified By:</strong></td>
																						<td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getusers['USR_MODIFIED_BY']; ?></button></td>
																						<td><strong>Verified Date:</strong></td>
																						<td><button type="button" class="btn disabled btn-white btn-primary"><?php echo $row_getusers['USR_DATE_MODIFIED']; ?></button></td>
																					</tr>
																					<tr>
																						<td colspan="4"><br /></td>
																					</tr>

																				</table>
																			</div>
																			<div class="modal-footer margin-top">
																				<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
																					<i class="ace-icon fa fa-times"></i>
																					Close </button>


																			</div>
																		</div>
																	</div>

																</div><!-- PAGE CONTENT ENDS -->
																<?php
																if (in_array('ADD_USERS', $roledata)) {

																?>

																	<a href="#modal-edituser-<?php echo $row_getusers['USR_ID']; ?>" role="button" class="green" data-toggle="modal"> <button class="btn btn-xs btn-info">
																			<i class="ace-icon fa fa-pencil bigger-120"></i> </button></a>
																<?php
																}
																?>





																<?php
																if (in_array('DEACTIVATE_USERS', $roledata)) {

																	if ($row_getusers['USR_STATUS'] == '11') {
																?>
																		<a href="users.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=00&USR_ID=<?php echo $row_getusers['USR_ID']; ?>"> <button class="btn btn-xs btn-danger">
																				<i class="ace-icon fa fa-trash-o bigger-120"></i> </button></a> <?php
																																			}
																																		}

																																		if (in_array('ACTIVATE_USERS', $roledata) && $row_getusers['USR_CREATED_BY'] != $_SESSION['MM_full_names']) {

																																			if ($row_getusers['USR_STATUS'] == '00') {
																																				?>
																		<a href="users.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=22&USR_ID=<?php echo $row_getusers['USR_ID']; ?>"> <button class="btn btn-xs btn-success">
																				<i class="ace-icon fa fa-check bigger-120"></i> </button></a>

																	<?php
																																			}
																																		}
																																		if (in_array('ACTIVATE_USERS', $roledata) && $row_getusers['USR_CREATED_BY'] != $_SESSION['MM_full_names']) {
																																			if ($row_getusers['USR_STATUS'] == '22') {
																	?>
																		<a href="users.php?fullnames=<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>&status=33&USR_ID=<?php echo $row_getusers['USR_ID']; ?>&USR_EMAIL=<?php echo $row_getusers['USR_EMAIL']; ?>"> <button class="btn btn-xs btn-warning">
																				<i class="ace-icon fa fa-check bigger-120"></i> </button></a>

																<?php
																																			}
																																		}
																?>
															</div>
															<div class="hidden-md hidden-lg">
																<div class="inline pos-rel">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i> </button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>
																			<a href="#" class="tooltip-info" data-rel="tooltip" title="View">
																				<span class="blue">
																					<i class="ace-icon fa fa-search-plus bigger-120"></i> </span> </a>
																		</li>
																		<li>
																			<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">

																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i> </span> </a>
																		</li>

																		<li>
																			<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="ace-icon fa fa-trash-o bigger-120"></i> </span> </a>
																		</li>
																	</ul>
																</div>
															</div>
														</td>
													</tr>

													<div id="modal-edituser-<?php echo $row_getusers['USR_ID']; ?>" class="modal fade" tabindex="-1">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header no-padding">
																	<div class="table-header">
																		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
																			<span class="white">&times;</span> </button>
																		Edit Admin User
																	</div>
																</div>

																<div class="modal-body padding">
																	<form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="editclient">
																		<input type="hidden" id="USR_ID" name="USR_ID" value="<?php echo $row_getusers['USR_ID']; ?>" />


																		<input type="hidden" id="status" name="status" value="22" />
																		<input type="hidden" id="USR_CREATED_BY" name="USR_CREATED_BY" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
																		<input type="hidden" id="USR_DATE_CREATED" name="USR_DATE_CREATED" value="<?php echo date('d-m-Y H:m:s'); ?>" />

																		<div class="space-10"></div>


																		<label class="col-sm-4">User Names</label>

																		<div class="col-sm-8"><span id="sprytextfield1">
																				<input type="text" id="USR_NAME" name="USR_NAME" value="<?php echo $row_getusers['USR_NAME']; ?>" />
																				<span class="textfieldRequiredMsg">*</span></span></div>


																		<br />
																		<div class="space-10"></div>


																		<label class="col-sm-4">STAFF ID</label>

																		<div class="col-sm-8"><span id="sprytextfield2">
																				<input type="text" id="USR_STAFF_ID" name="USR_STAFF_ID" value="<?php echo $row_getusers['USR_STAFF_ID']; ?>" />
																				<span class="textfieldRequiredMsg">*</span></span></div>


																		<br />
																		<div class="space-10"></div>
																		<label class="col-sm-4">Email Address</label>

																		<div class="col-sm-8"><span id="sprytextfield3">
																				<input type="text" id="USR_EMAIL" name="USR_EMAIL" value="<?php echo $row_getusers['USR_EMAIL']; ?>" />
																				<span class="textfieldRequiredMsg">*</span></span></div>


																		<br />
																		<div class="space-10"></div>

																		<label class="col-sm-4">Mobile Number</label>

																		<div class="col-sm-8"><span id="sprytextfield4">
																				<input value="<?php echo $row_getusers['USR_PHONE_NO']; ?>" type="text" id="USR_PHONE_NO" name="USR_PHONE_NO" />
																				<span class="textfieldRequiredMsg">*</span></span></div>
																		<br />
																		<div class="space-10"></div>

																		<label class="col-sm-4">National Id</label>

																		<div class="col-sm-8"><span id="sprytextfield5">
																				<input value="<?php echo $row_getusers['USR_NATIONAL_ID']; ?>" type="text" id="USR_NATIONAL_ID" name="USR_NATIONAL_ID" />
																				<span class="textfieldRequiredMsg">*</span></span></div>

																		<br />
																		<div class="space-10"></div>


																		<label class="col-sm-4">Profile</label>

																		<div class="col-sm-7"><span id="spryselect1">
																				<select class="chosen-select form-control" name="USR_PROFILE" id="USR_PROFILE" data-placeholder="Assign Profile...">
																					<option value="<?php echo $row_getusers['USR_PROFILE']; ?>"><?php echo $row_getusers['USR_PROFILE_NAME']; ?></option>
																					<option value="000"></option>

																					<?php do {

																					?>

																						<option value="<?php echo $row_getprofile4['profile_id']; ?>"><?php echo $row_getprofile4['profile_name']; ?></option>
																					<?php

																					} while ($row_getprofile4 = mysqli_fetch_assoc($getprofile4)); ?>
																				</select>

																				<span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>

																		<br />
																		<div class="space-10"></div>


																		<div class="clearfix form-actions">
																			<div class="col-md-offset-3 col-md-9">
																				<button onClick="submit" type="submit" value="submit" class="btn btn-info">
																					<!--<button onClick="submit" class="btn btn-info" type="button">-->
																					<i class="ace-icon fa fa-check bigger-110"></i>
																					Save
																				</button>

																				   
																				<button class="btn" type="reset">
																					<i class="ace-icon fa fa-undo bigger-110"></i>
																					Reset </button>
																			</div>
																		</div>

																		<input type="hidden" name="MM_update" value="editclient">

																	</form>

																</div>
																<div class="modal-footer no-margin-top">
																	<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
																		<i class="ace-icon fa fa-times"></i>
																		Close </button>


																</div>
															</div>
														</div>

													</div><!-- PAGE CONTENT ENDS -->
												<?php } while ($row_getusers = mysqli_fetch_assoc($getusers)); ?>


											</tbody>
										</table>
									</div>
								</div>
							</div>

							<div id="modal-newuser" class="modal fade" tabindex="-1">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span> </button>
												Add New User
											</div>
										</div>
										<div class="modal-body no-padding">


											<form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="newuser">
												<?php


												function GeraHash2($qtd)
												{
													//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
													$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789£$@abcdefghijklmnopqrstuvwxyz!#@';
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

												<input id="USR_PASSWORD" name="USR_PASSWORD" type="hidden" class="form-control" value="<?php echo "" . GeraHash2(6) . "" . date('ms'); ?>" />

												<input type="hidden" id="status" name="status" value="22" />
												<input type="hidden" id="USR_CREATED_BY" name="USR_CREATED_BY" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
												<input type="hidden" id="USR_DATE_CREATED" name="USR_DATE_CREATED" value="<?php echo date('d-m-Y H:m:s'); ?>" />
												<div class="space-10"></div>

												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Full Names</label>

													<div class="col-sm-9"><span id="sprytextfield5">
															<input type="text" id="USR_NAME" name="USR_NAME" />
															<span class="textfieldRequiredMsg">*</span></span></div>


												</div>

												<div class="space-4"></div>
												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Staff Id</label>

													<div class="col-sm-9"><span id="sprytextfield6">
															<input type="text" id="USR_STAFF_ID" name="USR_STAFF_ID" />
															<span class="textfieldRequiredMsg">*</span></span></div>


												</div>

												<div class="space-4"></div>

												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Email Address</label>

													<div class="col-sm-9"><span id="sprytextfield7">
															<input type="text" id="USR_EMAIL" name="USR_EMAIL" />
															<span class="textfieldRequiredMsg">*</span></span></div>
												</div>

												<div class="space-4"></div>

												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Mobile Number</label>

													<div class="col-sm-9"><span id="sprytextfield8">
															<input type="text" id="USR_PHONE_NO" name="USR_PHONE_NO" />
															<span class="textfieldRequiredMsg">*</span></span></div>
												</div>
												<div class="space-4"></div>

												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Id Number</label>

													<div class="col-sm-9"><span id="sprytextfield9">
															<input type="text" id="USR_NATIONAL_ID" name="USR_NATIONAL_ID" />
															<span class="textfieldRequiredMsg">*</span><span class="textfieldInvalidFormatMsg">Numbers Only</span></span></div>
												</div>



												<div class="space-4"></div>

												<div class="form-group">
													<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Profile</label>

													<div class="col-sm-6"><span id="spryselect2">
															<select class="chosen-select form-control" name="USR_PROFILE" id="USR_PROFILE" data-placeholder="Assign Profile...">

																<option value="000">Choose Profile</option>

																<?php do {
																?> <option value="<?php echo $row_getprofile['profile_id']; ?>"><?php echo $row_getprofile['profile_name']; ?></option>
																<?php

																} while ($row_getprofile = mysqli_fetch_assoc($getprofile)); ?>
															</select>

															<span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
												</div>

												<div class="space-4"></div>

										</div>
										<div class="clearfix form-actions">
											<div class="col-md-offset-3 col-md-9">
												<button onClick="submit" type="submit" value="submit" class="btn btn-info">
													<!--<button onClick="submit" class="btn btn-info" type="button">-->
													<i class="ace-icon fa fa-check bigger-110"></i>
													Save
												</button>
												   
												<button class="btn" type="reset">
													<i class="ace-icon fa fa-undo bigger-110"></i>
													Reset </button>
											</div>
										</div>

										<input type="hidden" name="MM_insert" value="newuser">
										</form>

									</div>


									<div class="modal-footer no-margin-top">
										<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
											<i class="ace-icon fa fa-times"></i>
											Close </button>


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

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i></a> </div><!-- /.main-container -->

	<!-- basic scripts -->

	<!--[if !IE]> -->
	<script type="text/javascript">
		window.jQuery || document.write("<script src='../../assets/js/jquery.js'>" + "<" + "/script>");
	</script>

	<!-- <![endif]-->

	<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
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
						null, null, null, null, null, null, null, null, null,
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

	<!-- the following scripts are used in demo only for onpage help and you don't need them -->
	<link rel="stylesheet" href="../../assets/css/ace.onpage-help.css" />
	<link rel="stylesheet" href="../../docs/assets/js/themes/sunburst.css" />

	<script type="text/javascript">
		ace.vars['base'] = '..';
	</script>
	<script src="../../assets/js/ace/elements.onpage-help.js"></script>
	<script src="../../assets/js/ace/ace.onpage-help.js"></script>
	<script src="../../docs/assets/js/rainbow.js"></script>
	<script src="../../docs/assets/js/language/generic.js"></script>
	<script src="../../docs/assets/js/language/html.js"></script>
	<script src="../../docs/assets/js/language/css.js"></script>
	<script src="../../docs/assets/js/language/javascript.js"></script>

	<script type="text/javascript">
		<!--
		var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {
			validateOn: ["change"]
		});
		var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {
			validateOn: ["change"]
		});
		var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {
			validateOn: ["change"]
		});
		var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {
			validateOn: ["change"]
		});
		var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "none", {
			validateOn: ["change"]
		});
		var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {
			validateOn: ["change"]
		});
		var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "none", {
			validateOn: ["change"]
		});
		var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "none", {
			validateOn: ["change"]
		});
		var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9", "integer", {
			validateOn: ["change"]
		});

		var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {
			invalidValue: "000",
			validateOn: ["change"]
		});
		var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2", {
			invalidValue: "000",
			validateOn: ["change"]
		});



		//
		-->
	</script>
</body>

</html>
<?php
mysqli_free_result($getusers);

mysqli_free_result($getprofile);

mysqli_free_result($getprofile4);



?>